{{-- Fallback article index, used when the active theme ships no
     `templates.articles-index` of its own. The theme layout already renders the
     site header and footer, so this only supplies the page body. --}}
@extends($layout)

@section('content')
    <div class="mx-auto w-full max-w-[1280px] px-5 py-12 md:px-8">
        @include('content-studio-plugin::components.blocks.article-overview', [
            'title' => __('content-studio-plugin::content_studio.articles'),
            'articles' => $articles,
            'showExcerpt' => true,
            'blogBasePath' => $blogBasePath,
        ])
    </div>
@endsection
