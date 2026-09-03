@php
    $title = $title ?? __('strategy-engine::content_studio.articles');
    $articles = $articles ?? collect();
    $showExcerpt = $showExcerpt ?? true;
    $locale = app()->getLocale();
    $setting = \Shazzoo\StrategyEngine\Models\ContentStudioSetting::singleton();
    $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
    $blogBasePath = $blogBasePath ?? \Shazzoo\StrategyEngine\Support\ArticleRoutes::indexPath($locale, $prefix);
@endphp

<section class="py-6">
    <p class="mb-[14px] text-[13px] font-bold uppercase tracking-[0.06em] text-amber-600">
        {{ __('strategy-engine::content_studio.blog_eyebrow') }}
    </p>

    <h2 class="text-[clamp(32px,4.2vw,50px)]">{{ $title }}</h2>

    <p class="mb-8 mt-5 max-w-[62ch] text-[18px] leading-[1.6] text-ink-600">
        {{ __('strategy-engine::content_studio.blog_description') }}
    </p>

    @if ($articles->isEmpty())
        <p class="text-sm text-ink-600">{{ __('strategy-engine::content_studio.no_articles_available') }}</p>
    @else
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                @php
                    $content_type = $article->content_type ?? 'article';
                @endphp
                @switch($content_type)
                    @case('hub')
                        <x-strategy-engine::article-hub :article="$article" :blog-base-path="$blogBasePath" />
                    @break

                    @default
                        <x-strategy-engine::article-card :article="$article" :show-excerpt="$showExcerpt" :blog-base-path="$blogBasePath" />
                @endswitch
            @endforeach
        </div>

        @if (method_exists($articles, 'hasPages') && $articles->hasPages())
            <div class="mt-12">
                {{ $articles->links('strategy-engine::partials.pagination') }}
            </div>
        @endif
    @endif
</section>
