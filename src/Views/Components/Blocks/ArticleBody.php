<?php

namespace Shazzoo\ContentStudio\Views\Components\Blocks;

use Illuminate\View\Component;
use Illuminate\View\View;
use Shazzoo\ContentStudio\Models\ContentStudioArticle;
use Shazzoo\ContentStudio\Models\ContentStudioSetting;

class ArticleBody extends Component
{
    public function __construct(
        public array $data = [],
    ) {}

    public function render(): View
    {
        return view('content-studio-plugin::components.blocks.article-body', [
            'article' => ContentStudioArticle::findOrFail($this->data['article_id'] ?? null),
            'setting' => ContentStudioSetting::singleton(),
        ]);
    }
}
