@props(['setting', 'article'])

@php
    use Shazzoo\StrategyEngine\Support\Tracking;
@endphp

@if (Tracking::enabled())
    <div id="content-studio-tracking" hidden data-endpoint="{{ Tracking::endpoint() }}"
        data-project-key="{{ $setting?->cs_project_code }}"
        data-content-id="{{ $article?->content_studio_article_id }}" data-article-slug="{{ $article?->slug }}"></div>

    <script src="{{ route('content-studio.tracking-script') }}" defer></script>
@endif
