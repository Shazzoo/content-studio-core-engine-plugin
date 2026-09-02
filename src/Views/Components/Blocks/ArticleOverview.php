<?php

namespace Shazzoo\ContentStudio\Views\Components\Blocks;

use Illuminate\View\Component;
use Illuminate\View\View;
use Shazzoo\ContentStudio\Models\ContentStudioArticle;
use Shazzoo\ContentStudio\Models\ContentStudioSetting;

class ArticleOverview extends Component
{
    public function __construct(
        public array $data = [],
    ) {}

    public function render(): View
    {
        $setting = ContentStudioSetting::singleton();
        $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
        $perPage = max(1, min(48, (int) ($setting->articles_per_page ?: 12)));
        $locale = app()->getLocale();

        return view('content-studio-plugin::components.blocks.article-overview', [
            'title' => (string) ($this->data['title'] ?? __('content-studio-plugin::content_studio.articles')),
            'articles' => ContentStudioArticle::query()
                ->where('locale', $locale)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->orderBy('generated_at', 'desc')
                ->paginate($perPage)
                ->withQueryString(),
            'showExcerpt' => (bool) ($this->data['show_excerpt'] ?? true),
            'blogBasePath' => '/'.$locale.'/'.$prefix,
        ]);
    }
}
