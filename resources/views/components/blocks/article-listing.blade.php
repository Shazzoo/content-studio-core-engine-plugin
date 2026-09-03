@php
    $data = $data ?? [];
    $title = $title ?? 'Latest Articles';
    $showExcerpt = $showExcerpt ?? true;
    $locale = app()->getLocale();
    $setting = \Shazzoo\StrategyEngine\Models\ContentStudioSetting::singleton();
    $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
    $blogBasePath = $blogBasePath ?? \Shazzoo\StrategyEngine\Support\ArticleRoutes::indexPath($locale, $prefix);

    if (isset($data) && is_array($data)) {
        $title = (string) ($data['title'] ?? $title);
        $showExcerpt = (bool) ($data['show_excerpt'] ?? $showExcerpt);
    }

    if (!isset($articles)) {
        $limit = 3;

        $articles = \Shazzoo\StrategyEngine\Models\ContentStudioArticle::query()
            ->where('locale', $locale)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('generated_at')
            ->limit($limit)
            ->get();
    }

    $articles = collect($articles)->take(3);
    $allArticlesUrl = url($blogBasePath);
@endphp

<section class="mx-auto w-full max-w-[1280px] px-5 md:px-8">
    <div class="mb-6 flex items-center justify-between gap-4">
        <h2>{{ $title }}</h2>
        <a href="{{ $allArticlesUrl }}"
            class="inline-flex items-center rounded-lg border border-transparent bg-amber px-4 py-2 text-sm font-bold text-amber-ink transition hover:bg-amber-hover">
            {{ __('strategy-engine::content_studio.all_articles') }}
        </a>
    </div>

    @if ($articles->isEmpty())
        <p class="text-sm text-ink-600">{{ __('strategy-engine::content_studio.no_articles_available') }}</p>
    @else
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-strategy-engine::article-card :article="$article" :clickable="true" :show-excerpt="$showExcerpt" :blog-base-path="$blogBasePath" />
            @endforeach
        </div>
    @endif
</section>
