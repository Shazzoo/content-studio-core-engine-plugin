<?php

namespace Shazzoo\StrategyEngine\Views\Components\Blocks;

use Illuminate\View\Component;
use Illuminate\View\View;
use Shazzoo\StrategyEngine\Models\ContentStudioArticle;
use Shazzoo\StrategyEngine\Models\ContentStudioSetting;

class ArticleBody extends Component
{
    public function __construct(
        public array $data = [],
    ) {}

    public function render(): View
    {
        return view('strategy-engine::components.blocks.article-body', [
            'article' => ContentStudioArticle::findOrFail($this->data['article_id'] ?? null),
            'setting' => ContentStudioSetting::singleton(),
        ]);
    }
}
