@props(['article', 'previousArticle' => null, 'nextArticle' => null, 'setting' => null])

@php
    $locale = $locale ?? app()->getLocale();
    $date = $date ?? locale_date($article->generated_at);
    $readTime = $readTime ?? read_time($article->body_html);
    $image_url = $image_url ?? \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image_url);
    $prefix = trim((string) ($setting?->route_prefix ?? null ?: 'blog'), '/');
    $blogBasePath = \Shazzoo\ContentStudio\Support\ArticleRoutes::indexPath($locale, $prefix);
@endphp

<div class="mx-auto mt-5 max-w-5xl">
    <x-content-studio-plugin::article-breadcrumbs :article="$article" :locale="$locale" />

    <header class="py-8">
        <h1 class="mb-3 text-[clamp(34px,4.2vw,50px)] leading-[1.1] text-ink-950">
            {!! $article->title !!}
        </h1>
        <x-content-studio-plugin::article-info :date="$date" :time="$readTime" :authors="[$article->author_name]" />
    </header>

    <div class="mb-10">
        <img src="{{ $image_url }}" alt="{{ $article->featured_image_alt }}"
            class="h-[450px] w-full rounded-lg border border-line bg-bg-alt object-cover shadow-sm">
    </div>

    <div>
        <div class="article-body max-w-none">
            {!! $article->body_html !!}
        </div>

        {{-- Currently hardcoded for testing, is not yet in API response --}}
        {{-- <x-content-studio-plugin::article-tags /> --}}

        <section class="mt-14 grid grid-cols-1 gap-8 border-t border-line pt-8 md:grid-cols-2">
            <div>
                <p class="mb-4 text-xs font-bold uppercase tracking-[0.08em] text-ink-400">
                    {{ __('content-studio-plugin::content_studio.next_article') }}</p>
                @if ($previousArticle)
                    <a href="{{ url(rtrim($blogBasePath, '/') . '/' . ltrim((string) $previousArticle->slug, '/')) }}"
                        class="text-xl font-bold text-ink-950 transition hover:text-amber-600">
                        {{ $previousArticle->title }}
                    </a>
                @else
                    <p class="text-xl font-bold text-ink-400">
                        {{ __('content-studio-plugin::content_studio.no_previous_article') }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="mb-4 text-xs font-bold uppercase tracking-[0.08em] text-ink-400">
                    {{ __('content-studio-plugin::content_studio.previous_article') }}</p>
                @if ($nextArticle)
                    <a href="{{ url(rtrim($blogBasePath, '/') . '/' . ltrim((string) $nextArticle->slug, '/')) }}"
                        class="text-lg font-bold text-ink-950 transition hover:text-amber-600">
                        {{ $nextArticle->title }}
                    </a>
                @else
                    <p class="text-xl font-bold text-ink-400">
                        {{ __('content-studio-plugin::content_studio.no_next_article') }}</p>
                @endif
            </div>
        </section>
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
