@props(['setting', 'article'])

@if (config('content-studio.tracking.enabled', true) && config('content-studio.tracking.endpoint'))
    <div id="content-studio-tracking" hidden data-endpoint="{{ config('content-studio.tracking.endpoint') }}"
        data-project-key="{{ $setting?->cs_project_code }}"
        data-content-id="{{ $article?->content_studio_article_id }}" data-article-slug="{{ $article?->slug }}"></div>

    <script src="{{ route('content-studio.tracking-script') }}" defer></script>
@endif
