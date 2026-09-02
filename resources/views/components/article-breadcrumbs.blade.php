@php
    $setting = \Shazzoo\ContentStudio\Models\ContentStudioSetting::singleton();
    $prefix = trim((string) ($setting->route_prefix ?: 'blog'), '/');
    $blogBasePath = \Shazzoo\ContentStudio\Support\ArticleRoutes::indexPath($locale, $prefix);
@endphp

<nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.08em]">
    <a href="{{ url($blogBasePath) }}"
        class="text-ink-400 transition hover:text-amber-600">{{ __('content-studio-plugin::content_studio.articles') }}</a>
    <span class="text-line-strong">/</span>
    <span class="truncate text-ink-600">{{ $article->title }}</span>
</nav>
