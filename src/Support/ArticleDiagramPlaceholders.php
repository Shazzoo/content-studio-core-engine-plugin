<?php

namespace Shazzoo\ContentStudio\Support;

final class ArticleDiagramPlaceholders
{
    /**
     * @param  array<string, mixed>  $item
     */
    public static function replace(string $html, array $item, ?callable $imageUrlResolver = null): string
    {
        $replacements = self::diagramReplacements($item, $imageUrlResolver);

        if ($replacements === []) {
            return $html;
        }

        return preg_replace_callback(
            '/<!--\s*diagram-placeholder:([0-9a-fA-F-]{36})\s*-->/',
            static fn (array $matches): string => $replacements[strtolower($matches[1])] ?? $matches[0],
            $html
        ) ?? $html;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, string>
     */
    private static function diagramReplacements(array $item, ?callable $imageUrlResolver): array
    {
        $replacements = [];

        foreach ([$item, $item['meta'] ?? null] as $source) {
            if (! is_array($source)) {
                continue;
            }

            foreach (['diagram_placeholders', 'diagrams', 'diagram_urls', 'diagram_image_urls'] as $key) {
                $value = $source[$key] ?? null;

                if (! is_array($value)) {
                    continue;
                }

                $replacements = array_merge($replacements, self::replacementsFromPayload($value, $imageUrlResolver));
            }

            $id = self::placeholderId($source['diagram_id'] ?? $source['diagram_uuid'] ?? null);
            $url = self::stringValue($source['diagram_url'] ?? $source['diagram_image_url'] ?? null);

            if ($id !== null && $url !== null) {
                $replacements[$id] = self::replacementFromUrl($url, null, null, $imageUrlResolver, $id);
            }
        }

        return $replacements;
    }

    private static function placeholderId(mixed $value): ?string
    {
        $value = self::stringValue($value);

        if ($value === null) {
            return null;
        }

        if (preg_match('/([0-9a-fA-F-]{36})/', $value, $matches) !== 1) {
            return null;
        }

        return strtolower($matches[1]);
    }

    /**
     * @param  array<mixed>  $payload
     * @return array<string, string>
     */
    private static function replacementsFromPayload(array $payload, ?callable $imageUrlResolver): array
    {
        $replacements = [];

        foreach ($payload as $key => $entry) {
            if (is_string($key)) {
                $id = self::placeholderId($key);
                $replacement = is_array($entry)
                    ? self::replacementFromEntry($entry, $imageUrlResolver, $id)
                    : self::replacementFromUrl(self::stringValue($entry), null, null, $imageUrlResolver, $id);

                if ($id !== null && $replacement !== null) {
                    $replacements[$id] = $replacement;
                }

                continue;
            }

            if (! is_array($entry)) {
                continue;
            }

            $id = self::placeholderId($entry['placeholder'] ?? $entry['id'] ?? $entry['uuid'] ?? $entry['placeholder_id'] ?? null);
            $replacement = self::replacementFromEntry($entry, $imageUrlResolver, $id);

            if ($id !== null && $replacement !== null) {
                $replacements[$id] = $replacement;
            }
        }

        return $replacements;
    }

    /**
     * @param  array<mixed>  $entry
     */
    private static function replacementFromEntry(array $entry, ?callable $imageUrlResolver, ?string $placeholderId): ?string
    {
        $url = self::stringValue($entry['url'] ?? $entry['image_url'] ?? $entry['diagram_url'] ?? $entry['src'] ?? null);

        if ($url === null) {
            return null;
        }

        $caption = self::stringValue($entry['caption'] ?? null);
        $alt = self::stringValue($entry['alt'] ?? null) ?? $caption;

        return self::replacementFromUrl($url, $alt, $caption, $imageUrlResolver, $placeholderId, $entry);
    }

    /**
     * @param  array<mixed>  $entry
     */
    private static function replacementFromUrl(?string $url, ?string $alt, ?string $caption, ?callable $imageUrlResolver, ?string $placeholderId, array $entry = []): ?string
    {
        if ($url === null) {
            return null;
        }

        if ($imageUrlResolver !== null) {
            $url = $imageUrlResolver($url, $placeholderId, $entry);
        }

        if ($caption === null && $alt === null) {
            return $url;
        }

        return '<figure class="article-diagram"><img src="'.self::escape($url).'" alt="'.self::escape($alt ?? '').'" loading="lazy"><figcaption>'.self::escape($caption ?? '').'</figcaption></figure>';
    }

    private static function stringValue(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value !== '' ? $value : null;
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
