<?php

namespace Shazzoo\ContentStudio\Http\Controllers;

use Shazzoo\ContentStudio\Models\ContentStudioArticle;
use Shazzoo\ContentStudio\Models\ContentStudioSetting;
use Shazzoo\ContentStudio\Support\ArticleRoutes;
use Shazzoo\ContentStudio\Support\Engine\ProjectInfo;
use Shazzoo\ContentStudio\Support\Seo\ArticleSeoBuilder;
use Shazzoo\ContentStudioCore\Support\Seo\SeoApplier;
use Shazzoo\ContentStudioCore\Support\Theming\ThemeManager;

class ArticleController
{
    private function resolveContext($locale = null): array
    {
        $resolvedLocale = $locale ?: (ProjectInfo::primaryLocale() ?: app()->getLocale());
        app()->setLocale($resolvedLocale);

        $setting = ContentStudioSetting::singleton();
        $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
        $perPage = max(1, min(48, (int) ($setting->articles_per_page ?: 12)));

        $active = app(ThemeManager::class)->active();
        $ns = $active['slug'] ?? null;
        $layout = $ns ? "{$ns}::layouts.app" : 'layouts.guest';

        return [
            'locale' => $resolvedLocale,
            'setting' => $setting,
            'prefix' => $prefix,
            'perPage' => $perPage,
            'ns' => $ns,
            'layout' => $layout,
        ];
    }

    public function handle(?string $locale = null, ?string $slug = null)
    {
        $context = $this->resolveContext($locale);
        $prefix = $context['prefix'];

        $segments = explode('/', trim((string) $slug, '/'));
        if (($segments[0] ?? null) === $prefix) {
            array_shift($segments);
        }

        if ($segments === []) {
            return $this->index($locale);
        }

        return $this->show($locale, implode('/', $segments));
    }

    public function index($locale = null)
    {
        $context = $this->resolveContext($locale);
        $setting = $context['setting'];
        $prefix = $context['prefix'];
        $perPage = $context['perPage'];
        $ns = $context['ns'];
        $layout = $context['layout'];

        $viewCandidates = [];
        if ($ns) {
            $viewCandidates[] = "{$ns}::templates.articles-index";
        }
        $viewCandidates[] = 'content-studio-plugin::articles.index';

        $articles = ContentStudioArticle::query()
            ->where('locale', $context['locale'])
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('generated_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $seo = app(ArticleSeoBuilder::class)->indexBuild(
            articles: $articles,
            locale: $context['locale'],
        );

        app(SeoApplier::class)->apply($seo);

        return view()->first($viewCandidates, [
            'setting' => $setting,
            'layout' => $layout,
            'articles' => $articles,
            'blogBasePath' => ArticleRoutes::indexPath($context['locale'], $prefix),
            'seo_data' => [
                'title' => 'Artikelen',
                'description' => 'Bekijk onze nieuwste artikelen.',
                'url' => ArticleRoutes::indexUrl($context['locale'], $prefix),
                'image' => null, // TODO: add image if available
            ],
        ]);
    }

    public function show($locale = null, $slug = null)
    {
        $context = $this->resolveContext($locale);
        $setting = $context['setting'];
        $prefix = $context['prefix'];
        $ns = $context['ns'];
        $layout = $context['layout'];

        $viewCandidates = [];
        if ($ns) {
            $viewCandidates[] = "{$ns}::templates.articles-show";
        }
        $viewCandidates[] = 'content-studio-plugin::articles.show';

        $article = ContentStudioArticle::where('locale', $context['locale'])
            ->where('slug', $slug)
            ->with(['related_hub_articles'])
            ->firstOrFail();

        if (! $article) {
            abort(404);
        }

        $seo = app(ArticleSeoBuilder::class)->build(
            article: $article,
            locale: $context['locale'],
        );

        app(SeoApplier::class)->apply($seo);

        return view()->first($viewCandidates, [
            'setting' => $setting,
            'slug' => $slug,
            'id' => $article->id,
            'layout' => $layout,
            'article' => $article,
            'blogBasePath' => ArticleRoutes::indexPath($context['locale'], $prefix),
            'seo_data' => [
                'title' => $article->title,
                'description' => $article->meta_description,
                'url' => ArticleRoutes::articleUrl($slug, $context['locale'], $prefix),
                'image' => $article->featured_image_url,
            ],
        ]);
    }
}
