<?php

namespace Shazzoo\StrategyEngine\Support\Seo;

use Shazzoo\StrategyEngine\Models\ContentStudioArticle;
use Shazzoo\StrategyEngine\Support\ArticleRoutes;
use Shazzoo\ContentStudioCore\Support\Seo\Dto\SeoMetadata;

final class ArticleSeoBuilder
{
    public function indexBuild($articles = null, ?string $locale = null): SeoMetadata
    {
        $appName = config('app.name');
        $title = "Artikelen & Insights - {$appName}";

        $currentPage = request()->get('page', 1);
        $canonical = $this->articleIndexUrl($locale);

        if ($currentPage > 1) {
            $title .= " - Pagina {$currentPage}";
            $canonical = $this->articleIndexUrl($locale, ['page' => $currentPage]);
        }

        $description = 'Ontdek de laatste trends over Laravel, AI-oplossingen en softwareontwikkeling bij Shazzoo.';

        // Bouw ItemList voor JSON-LD als je artikelen meegeeft
        $itemList = [];
        if ($articles) {
            foreach ($articles as $index => $article) {
                if (! filled($article->slug)) {
                    continue;
                }

                $itemList[] = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => $this->articleUrl($article->slug, $locale),
                    'name' => $article->title,
                ];
            }
        }

        return new SeoMetadata(
            title: $title,
            description: $description,
            canonical: $canonical,
            alternates: [],
            openGraph: [
                'title' => $title,
                'description' => $description,
                'url' => $canonical,
                'type' => 'website',
                'image' => 'https://shazzoo.nl/images/og-blog-overview.png', // Nooit null laten!
            ],
            jsonLd: [
                '@context' => 'https://schema.org',
                '@type' => 'Blog',
                'name' => $title,
                'description' => $description,
                'url' => $canonical,
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => $appName,
                    'logo' => ['@type' => 'ImageObject', 'url' => 'https://shazzoo.nl/themes/shazzoo-corporate-theme/images/shazzooVert.svg'],
                ],
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'itemListElement' => $itemList,
                ],
            ],
            robots: 'index,follow',
        );
    }

    /**
     * Bouw SEO metadata specifiek voor een enkel artikel.
     */
    public function build(ContentStudioArticle $article, ?string $locale = null): SeoMetadata
    {
        // 1. Haal de SEO velden uit het artikel model (ervan uitgaande dat je een 'seo' JSON kolom hebt)
        $title = $article->title ?? '';
        $description = $article->meta_description ?? '';

        // 2. Bouw de automatische Canonical URL
        $canonical = $this->articleUrl($article->slug, $locale);

        // 4. Indexering instellingen (standaard op index, follow)
        $index = 'index';
        $follow = 'follow';
        $robots = "{$index},{$follow}";

        // 5. Alternates (leeg laten omdat het niet meertalig is)
        $alternates = [];

        $image_url = $article->featured_image_url ?? null;
        // MAKE ABSOLUTE URL IF NOT ALREADY. IT IS LOCATED IN PUBLIC STORAGE.
        if ($image_url && ! str_starts_with($image_url, 'http')) {
            $image_url = asset("storage/{$image_url}");
        }

        return new SeoMetadata(
            title: $title,
            description: $description,
            canonical: $canonical,
            alternates: $alternates,
            openGraph: [
                'title' => $title,
                'description' => $description,
                'url' => $canonical,
                'type' => 'article',
                'article:published_time' => $article->created_at?->toIso8601String(),
                'article:author' => 'Shazzoo Engineering Team', // Harde referentie naar auteur
                'image' => $image_url, // Voeg eventueel een afbeelding toe
            ],
            jsonLd: [
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => $title,
                'description' => $description,
                'url' => $canonical,
                'datePublished' => $article->created_at?->toIso8601String(),
                'dateModified' => $article->updated_at?->toIso8601String() ?? $article->created_at?->toIso8601String(),
                'author' => [
                    '@type' => 'Organization',
                    'name' => 'Shazzoo',
                    'url' => 'https://shazzoo.nl',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Shazzoo',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => 'https://shazzoo.nl/themes/shazzoo-corporate-theme/images/shazzooVert.svg',
                    ],
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $canonical,
                ],
                'image' => $image_url ? [
                    '@type' => 'ImageObject',
                    'url' => $image_url,
                ] : null,
            ],
            robots: $robots,
        );
    }

    private function articleIndexUrl(?string $locale = null, array $parameters = []): string
    {
        $path = ArticleRoutes::indexPath($locale);

        if ($parameters !== []) {
            return url($path).'?'.http_build_query($parameters);
        }

        return url($path);
    }

    private function articleUrl(string $slug, ?string $locale = null): string
    {
        return ArticleRoutes::articleUrl($slug, $locale);
    }
}
