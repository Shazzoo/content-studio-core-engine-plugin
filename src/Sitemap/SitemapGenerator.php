<?php

namespace Shazzoo\StrategyEngine\Sitemap;

use Shazzoo\StrategyEngine\Models\ContentStudioArticle;
use Shazzoo\StrategyEngine\Models\ContentStudioSetting;
use Shazzoo\StrategyEngine\Support\ArticleRoutes;
use Shazzoo\ContentStudioCore\Support\Sitemap\Contracts\SitemapGeneratorInterface;
use Shazzoo\ContentStudioCore\Support\Sitemap\SitemapBuilder;

class SitemapGenerator implements SitemapGeneratorInterface
{
    public function generate(SitemapBuilder $sitemap): void
    {
        $setting = ContentStudioSetting::singleton();
        $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
        $locales = ContentStudioArticle::query()
            ->whereNotNull('locale')
            ->where('locale', '!=', '')
            ->distinct()
            ->pluck('locale')
            ->all();

        if ($locales === []) {
            $locales = [app()->getLocale()];
        }

        foreach ($locales as $locale) {
            // Add the article overview page for each available locale.
            $sitemap->addUrl(
                index: 'articles',
                url: ArticleRoutes::indexUrl($locale, $prefix),
                lastmod: now(),
            );

            foreach (ContentStudioArticle::query()
                ->where('locale', $locale)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->orderBy('updated_at', 'desc')
                ->cursor() as $article) {
                $sitemap->addUrl(
                    index: 'articles',
                    url: ArticleRoutes::articleUrl($article->slug, $locale, $prefix),
                    lastmod: $article->updated_at,
                );
            }
        }
    }
}
