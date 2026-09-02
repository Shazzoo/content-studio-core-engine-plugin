<?php

namespace Shazzoo\ContentStudio\Views\Components\Blocks;

use Illuminate\View\Component;
use Shazzoo\ContentStudio\Models\ContentStudioArticle;
use Shazzoo\ContentStudio\Models\ContentStudioSetting;
use Shazzoo\ContentStudio\Support\ArticleRoutes;

class ContentStudioArticles extends Component
{
    public function __construct(
        public array $data = [],
    ) {}

    public function render()
    {
        $setting = ContentStudioSetting::singleton();
        $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
        $locale = app()->getLocale();
        $limit = max(1, min(24, (int) ($setting->articles_per_block ?: 6)));

        $articles = ContentStudioArticle::query()
            ->where('locale', $locale)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('generated_at')
            ->limit($limit)
            ->get();

        return view('content-studio-plugin::components.blocks.article-listing', [
            'title' => (string) ($this->data['title'] ?? 'Latest Articles'),
            'showExcerpt' => (bool) ($this->data['show_excerpt'] ?? true),
            'articles' => $articles,
            'blogBasePath' => ArticleRoutes::indexPath($locale, $prefix),
        ]);
    }
}
