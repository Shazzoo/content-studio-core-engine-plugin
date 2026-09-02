@props([
    'article',
    'setting' => null,
])

@php
    use Illuminate\Support\Facades\Storage;
    use Shazzoo\ContentStudio\Models\ContentStudioArticle;

    $image_url = Storage::disk('public')->url($article->featured_image_url);
    $locale = app()->getLocale();
    $date = locale_date($article->generated_at);
    $readTime = read_time($article->body_html);
    $isHub = ($article->content_type ?? 'article') === 'hub';

    $previousArticle = ContentStudioArticle::query()
        ->where('locale', app()->getLocale())
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->where(function ($query) use ($article) {
            $query->where('generated_at', '>', $article->generated_at)->orWhere(function ($query) use ($article) {
                $query->where('generated_at', $article->generated_at)->where('id', '>', $article->id);
            });
        })
        ->orderBy('generated_at')
        ->orderBy('id')
        ->first();

    $nextArticle = ContentStudioArticle::query()
        ->where('locale', app()->getLocale())
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->where(function ($query) use ($article) {
            $query->where('generated_at', '<', $article->generated_at)->orWhere(function ($query) use ($article) {
                $query->where('generated_at', $article->generated_at)->where('id', '<', $article->id);
            });
        })
        ->orderByDesc('generated_at')
        ->orderByDesc('id')
        ->first();

    $processedBody = $article->body_html;
    $headings = $headings ?? [];
@endphp

@if ($isHub)
    <x-content-studio-plugin::hub-details :article="$article" :setting="$setting" />
@else
    <x-content-studio-plugin::card-details :article="$article" :previousArticle="$previousArticle" :nextArticle="$nextArticle" :setting="$setting" />
@endif
