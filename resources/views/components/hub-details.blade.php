@props([
    'article',
    'setting' => null,
    'locale' => null,
    'date' => null,
    'image_url' => null,
    'processedBody' => null,
    'headings' => [],
])

@php
    $locale = $locale ?? app()->getLocale();
    $date = $date ?? locale_date($article->generated_at);
    $readTime = $readTime ?? read_time($article->body_html);
    $image_url = $image_url ?? \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image_url);
    $processedBody = $processedBody ?? $article->body_html;
    $authors = collect(preg_split('/\s*[,;]\s*/', (string) $article->author_name, -1, PREG_SPLIT_NO_EMPTY))
        ->map(fn($author) => trim($author))
        ->filter()
        ->values()
        ->all();
@endphp

<div class="mx-auto mt-5 max-w-7xl">
    <x-content-studio-plugin::article-breadcrumbs :article="$article" :locale="$locale" />
    <header class="pt-8">
        <div class="flex flex-col items-center gap-12 lg:flex-row">
            <div class="md:w-2/3">

                <h1 class="mb-6 text-[clamp(34px,4.8vw,58px)] leading-[1.1] text-ink-950">
                    {!! $article->title !!}
                </h1>

                <p class="max-w-2xl border-l-4 border-amber pl-6 text-xl italic leading-relaxed text-ink-600">
                    {!! $article->excerpt !!}
                </p>

                <div class="pt-6">
                    <x-content-studio-plugin::article-info :date="$date" :time="$readTime" :authors="$authors" />
                </div>
            </div>

            <div class="block md:w-1/3">
                <div class="relative">
                    <div class="absolute inset-0 rotate-2 rounded-lg bg-amber-soft"></div>
                    <img src="{{ $image_url }}" alt="{{ $article->featured_image_alt }}"
                        class="relative aspect-[16/9] w-full -rotate-2 rounded-lg border border-line object-cover shadow-sm transition duration-500 hover:rotate-0">
                </div>
            </div>
        </div>
    </header>
    <div class="mx-auto max-w-7xl py-16">
        <div class="flex flex-col gap-16 lg:flex-row">

            <aside class="lg:w-1/4 pt-6">
                <div class="sticky top-36">
                    <div>
                        <h4 class="mb-4 ml-2 text-xs font-bold uppercase tracking-[0.08em] text-ink-600">
                            {{ __('content-studio-plugin::content_studio.table_of_contents') }}
                        </h4>
                        <nav class="space-y-3 border-l border-line">
                            @foreach ($headings as $heading)
                                <a href="#{{ str($heading['title'])->slug() }}"
                                    class="block pl-4 text-sm text-ink-600 transition hover:text-amber-600">{{ $heading['title'] }}</a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="rounded-lg border border-line bg-bg-alt p-6">
                        <p class="mb-2 text-xs font-bold uppercase text-ink-400">
                            {{ __('content-studio-plugin::content_studio.expert_review') }}</p>
                        <p class="text-sm leading-relaxed text-ink-800">
                            {{ __('content-studio-plugin::content_studio.updated_on_by_team', ['date' => $date]) }}
                        </p>
                    </div>
                </div>
            </aside>

            <section class="lg:w-3/4">
                @include('content-studio-plugin::partials.article-styles')

                <section class="article-body max-w-none">
                    {!! $processedBody !!}
                </section>

                <section id="verdieping" class="mt-24 border-t border-line pt-16">
                    <h2 class="mb-2 text-3xl font-black text-ink-950">
                        {{ __('content-studio-plugin::content_studio.cluster_overview') }}
                    </h2>
                    <p class="mb-10 text-lg leading-relaxed text-ink-600">
                        {{ __('content-studio-plugin::content_studio.dive_into_details') }}</p>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach ($article->related_hub_articles as $child_article)
                            <x-content-studio-plugin::article-card :article="$child_article" :clickable="true" />
                        @endforeach
                    </div>
                </section>
            </section>
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            function getOrCreate(name) {
                var value = localStorage.getItem(name);

                if (!value) {
                    value = crypto.randomUUID ? crypto.randomUUID() : String(Date.now()) + Math.random();
                    localStorage.setItem(name, value);
                }

                return value;
            }


            var baseData = {
                project_key: @json($setting->cs_project_code),
                content_id: @json($article->content_studio_article_id),
                article_slug: @json($article->slug),
                referrer: document.referrer || '',
                landing_url: window.location.href,
                path: window.location.pathname,
                user_agent: navigator.userAgent || '',
                visitor_id: getOrCreate('shazzoo_visitor_id'),
                session_id: sessionStorage.getItem('shazzoo_session_id') || ''
            };

            if (!baseData.session_id) {
                baseData.session_id = crypto.randomUUID ? crypto.randomUUID() : String(Date.now()) + Math.random();
                sessionStorage.setItem('shazzoo_session_id', baseData.session_id);
            }

            function send(payload) {
                var body = JSON.stringify(payload);

                if (navigator.sendBeacon) {
                    navigator.sendBeacon('https://engine.content-studio.com/api/tracking/event', new Blob([JSON
                        .stringify(
                            payload)
                    ], {
                        type: 'text/plain;charset=UTF-8'
                    }));
                    return;
                }

                fetch('https://engine.content-studio.com/api/tracking/event', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: body,
                    keepalive: true
                });
            }

            send(Object.assign({}, baseData, {
                event_type: 'page_view',
                visitor_id: getVisitorId(),
            }));

            document.addEventListener('click', function(event) {
                var link = event.target.closest('a');

                if (!link || !link.href) {
                    return;
                }

                if (link.href.indexOf('javascript:') === 0 || link.href.indexOf('#') === 0) {
                    return;
                }

                send(Object.assign({}, baseData, {
                    visitor_id: getVisitorId(),
                    event_type: 'click',
                    target_url: link.href
                }));
            }, true);

            function getVisitorId() {
                const cookieName = "shazzoo_visitor_id";

                const existing = document.cookie
                    .split("; ")
                    .find((row) => row.startsWith(cookieName + "="))
                    ?.split("=")[1];
                if (existing) {
                    return existing;
                }
        
                const visitorId = crypto.randomUUID();
                document.cookie = [
                    `${cookieName}=${visitorId}`,
                    "Path=/",
                    "Max-Age=31536000",
                    "SameSite=Lax",
                    "Secure",
                ].join("; ");

                return visitorId;
            }
        })();
    </script>
</div>
