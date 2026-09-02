{{--
    Styling voor markup die de Content Studio Engine in de artikeltekst zet en
    waar het thema dus geen klasse voor heeft. Staat hier inline omdat de plugin
    geen eigen assetbuild heeft; de site hoeft niets te bundelen.

    Overschrijf de kleuren met de custom properties hieronder, of publiceer dit
    bestand als je de opmaak helemaal zelf wilt doen:

        php artisan vendor:publish --tag=content-studio-views
--}}
@once
    <style>
        .article-body {
            --cs-pull-quote-accent: #f59e0b;
            --cs-pull-quote-bg: rgb(245 158 11 / 0.06);
            --cs-pull-quote-text: #0f172a;
        }

        .article-body .pull-quote {
            position: relative;
            margin: 2rem 0;
            padding: 1rem 1.25rem 1rem 1.5rem;
            border-left: 4px solid var(--cs-pull-quote-accent);
            border-radius: 0 0.5rem 0.5rem 0;
            background: var(--cs-pull-quote-bg);
        }

        .article-body .pull-quote p {
            margin: 0;
            font-size: 1.25rem;
            font-style: italic;
            font-weight: 500;
            line-height: 1.375;
            color: var(--cs-pull-quote-text);
        }

        @media (min-width: 768px) {
            .article-body .pull-quote p {
                font-size: 1.5rem;
            }
        }

        .article-body .pull-quote p::before {
            content: "\201C";
            margin-right: 0.25rem;
            font-style: normal;
            color: var(--cs-pull-quote-accent);
        }

        .article-body .pull-quote p::after {
            content: "\201D";
            margin-left: 0.25rem;
            font-style: normal;
            color: var(--cs-pull-quote-accent);
        }

        @media (prefers-color-scheme: dark) {
            .article-body {
                --cs-pull-quote-bg: rgb(245 158 11 / 0.12);
                --cs-pull-quote-text: #ffffff;
            }
        }

        .dark .article-body {
            --cs-pull-quote-bg: rgb(245 158 11 / 0.12);
            --cs-pull-quote-text: #ffffff;
        }
    </style>
@endonce
