<?php

namespace Shazzoo\ContentStudio\Support;

use Shazzoo\ContentStudio\Models\ContentStudioSetting;

final class ArticleRoutes
{
    public static function indexPath(?string $locale = null, ?string $prefix = null): string
    {
        $segments = array_filter([
            self::usesLocalizedRoutes() ? self::normalizeSegment($locale) : null,
            self::prefix($prefix),
        ]);

        return '/'.implode('/', $segments);
    }

    public static function articlePath(string $slug, ?string $locale = null, ?string $prefix = null): string
    {
        return self::indexPath($locale, $prefix).'/'.self::normalizeSegment($slug);
    }

    public static function indexUrl(?string $locale = null, ?string $prefix = null): string
    {
        return url(self::indexPath($locale, $prefix));
    }

    public static function articleUrl(string $slug, ?string $locale = null, ?string $prefix = null): string
    {
        return url(self::articlePath($slug, $locale, $prefix));
    }

    public static function usesLocalizedRoutes(): bool
    {
        return function_exists('cms_is_multilang') && cms_is_multilang();
    }

    public static function prefix(?string $prefix = null): string
    {
        $prefix = $prefix ?? self::settingPrefix();

        return self::normalizeSegment($prefix) ?: 'blog';
    }

    private static function settingPrefix(): string
    {
        return trim((string) (ContentStudioSetting::singleton()->route_prefix ?: 'blog'), '/');
    }

    private static function normalizeSegment(?string $segment): string
    {
        return trim((string) $segment, '/');
    }
}
