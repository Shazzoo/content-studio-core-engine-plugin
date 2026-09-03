{{-- Fallback article detail, used when the active theme ships no
     `templates.articles-show` of its own. --}}
@extends($layout)

@section('content')
    <div class="mx-auto w-full max-w-[1280px] px-5 py-12 md:px-8">
        @include('strategy-engine::components.blocks.article-body', [
            'article' => $article,
            'setting' => $setting,
        ])
    </div>
@endsection
