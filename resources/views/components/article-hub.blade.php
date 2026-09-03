@props(['article', 'blogBasePath' => null])

@php
    use Illuminate\Support\Facades\Storage;

    $image_url = Storage::disk('public')->url($article->featured_image_url);
    $locale = app()->getLocale();
    $setting = \Shazzoo\StrategyEngine\Models\ContentStudioSetting::singleton();
    $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
    $blogBasePath = $blogBasePath ?? \Shazzoo\StrategyEngine\Support\ArticleRoutes::indexPath($locale, $prefix);
    $fm = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $date = $fm->format($article->generated_at);
    $child_article_count = $article->related_hub_articles->count();
    $cluster_label = ucfirst(str_replace('_', ' ', $article->cluster_key));
    $hub_url = url(rtrim($blogBasePath, '/') . '/' . ltrim((string) $article->slug, '/'));
@endphp

@include('strategy-engine::partials.article-styles')

<div class="col-span-full my-8 flex flex-col overflow-hidden rounded-lg border border-line bg-surface shadow-sm lg:flex-row">
    <div class="overflow-hidden lg:w-4/5">
        <img src="{{ $image_url }}" alt="{{ $article->featured_image_alt }}"
            class="cs-featured-image bg-bg-alt">
    </div>
    <div class="flex flex-col justify-between p-8 md:p-10">
        <div>
            <h2 class="mb-4 text-3xl font-bold leading-tight text-ink-950">
                {!! $article->title !!}
            </h2>

            <p class="text-lg italic leading-relaxed text-ink-600">
                {!! $article->excerpt !!}
            </p>
        </div>
        <div class="mt-8 flex flex-col justify-between gap-6 border-t border-line pt-8 sm:flex-row sm:items-center">
            <div class="text-sm font-medium text-ink-600">
                <span class="block text-xs font-bold uppercase tracking-[0.06em] text-amber-600">
                    {{ __('strategy-engine::content_studio.pillar_content') }}
                </span>

                {{ __('strategy-engine::content_studio.contains_deep_dive_articles', ['count' => $child_article_count]) }}
            </div>
            <a href="{{ $hub_url }}"
                class="inline-flex items-center justify-center rounded-lg border border-transparent bg-amber px-4 py-2 text-center text-sm font-bold text-amber-ink transition hover:bg-amber-hover">
                {{ __('strategy-engine::content_studio.view_full_guide') }}
            </a>
        </div>
    </div>
</div>
