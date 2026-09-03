# Content Studio — Engine plugin

A plugin for [Content Studio Core](https://github.com/Shazzoo/content-studio-core)
that publishes blog articles generated in the Content Studio Engine.

It syncs articles from the Engine into your site, gives them routes, an index
and detail page, blocks you can drop into any page, SEO metadata and sitemap
entries — and confirms back to the Engine once an article is live.

One package serves every site. The functionality is updated with
`composer update`; each site keeps its own look through view overrides, so no
site needs its own copy.

## What it gives you

| | |
|---|---|
| **Article sync** | `content-studio:articles`, scheduled daily, pulling approved content from the Engine |
| **Routes** | `/{prefix}` and `/{prefix}/{slug}`, locale-prefixed on multilingual sites |
| **Pages** | An article index and a detail page, overridable by your theme |
| **Blocks** | An article listing block for any page, plus overview and body blocks |
| **Admin** | A Content Studio settings page for API credentials, route prefix and paging |
| **SEO** | Metadata, Open Graph and schema.org, applied through Core's SEO layer |
| **Sitemap** | Article URLs registered with Core's sitemap generator |
| **Publish confirmation** | Tells the Engine an article is live, so it drops out of the pending list |

## Requirements

- PHP 8.3+
- `shazzoo/content-studio-core` ^1.0

## Install

```bash
composer require shazzoo/strategy-engine-plugin
```

Activate **Content Studio** under *Instellingen → Plugins*. Activating it runs
the plugin's migrations and publishes its assets.

## Configure

Open **Content Studio Settings** in the admin and fill in:

| Setting | Meaning |
|---|---|
| `cs_api_key` | Your Content Studio Engine API key |
| `cs_project_code` | The project to pull articles from |
| `route_prefix` | URL segment for articles. `blog` gives `/blog` and `/blog/{slug}` |
| `articles_per_page` | Articles on the index page |
| `articles_per_block` | Articles in the listing block |

The page shows which Engine project you are connected to and how many articles
are available, so you can confirm the credentials work before syncing.

The Engine API URL defaults to the hosted Engine and can be pointed elsewhere
with `CONTENT_STUDIO_ENGINE_API_URL` in `.env`.

## Sync articles

```bash
php artisan content-studio:articles
```

Registered to run daily. Only content approved in the Engine is pulled. After an
article is stored the plugin confirms publication back to the Engine, so a
synced article drops out of the pending list on the next run.

## Styling per site

The plugin ships working, deliberately plain views. A site overrides only what
it wants to restyle; everything else keeps coming from the package, so plugin
updates still reach the views you did not touch.

Three levels, cheapest first.

### 1. Override individual views

```bash
php artisan vendor:publish --tag=content-studio-components
```

That copies the component views into
`resources/views/vendor/strategy-engine/components/`. Edit them freely —
markup, classes, structure. Delete any file from that directory and it falls
back to the package version.

Use `--tag=content-studio-views` to publish everything, including the article
pages and pagination.

> **Publish only what you restyle.** Every published file is one you now
> maintain: it stops receiving upstream changes. Restyling three components
> means three files to maintain, not sixteen.

| View | Purpose |
|---|---|
| `components/article-card.blade.php` | Article card in listings |
| `components/article-hub.blade.php` | Hub/cluster card |
| `components/article-info.blade.php` | Author and metadata line |
| `components/article-tag.blade.php` | A single tag |
| `components/article-tags.blade.php` | Tag list |
| `components/article-breadcrumbs.blade.php` | Breadcrumbs |
| `components/card-details.blade.php` | Card body detail |
| `components/hub-details.blade.php` | Hub body detail |
| `components/blocks/article-body.blade.php` | Article body block |
| `components/blocks/article-listing.blade.php` | Listing block |
| `components/blocks/article-overview.blade.php` | Overview block |
| `articles/index.blade.php` | Article index page |
| `articles/show.blade.php` | Article detail page |
| `partials/pagination.blade.php` | Pagination |

**When copying a view out of another project**, rewrite any references to that
project's namespaces to this package's:

```
strategy-engine::            (view and translation namespace)
Shazzoo\StrategyEngine\             (PHP classes, including inside @php blocks)
```

A stale namespace in an override fails at render time, not at install time.

### 2. Theme templates

For the index and detail pages the active theme wins over the plugin entirely.
`ArticleController` looks for these first:

```
{theme}::templates.articles-index
{theme}::templates.articles-show
```

If your theme provides them, the plugin's own `articles/index` and
`articles/show` are never used. This is the right level when your article pages
have a fundamentally different layout rather than different styling.

You can also pick a specific theme template per site under **Templates** on the
settings page. Leave it empty and nothing changes — the plugin falls back to the
theme's `articles-index` / `articles-show`, then to its own views.

A chosen template receives a page-shaped object, so a generic theme page
template can render articles:

```blade
$templateSettings = $page->template_settings ?? [];
$ctaTitle = data_get($templateSettings, "cta.{$locale}.title");
```

`template_settings` is per-template configuration you edit in the admin,
including a localised CTA. Templates that render articles as a block also
receive `$pageBlocks` and `$contentStudioContentView`.

### 3. Model accessors

When writing your own views, read article data through the accessors rather than
raw Engine column names, so your views survive schema changes upstream:

```blade
{{ $article->title }}
{{ $article->excerpt(160) }}      {{-- excerpt, or a trimmed body --}}
{{ $article->thumbnailUrl() }}
{!! $article->content !!}          {{-- body_html --}}
@if ($article->author)
    {{ $article->author->name }}
@endif
```

### How view resolution works

Core registers the site override directory ahead of the package's own views, so
the finder picks the first path that has the file:

```
1. resources/views/vendor/strategy-engine/                      ← your overrides
2. vendor/shazzoo/strategy-engine-plugin/resources/views/  ← package defaults
```

Resolution is per file, not all-or-nothing.

### A note on the view namespace

The package is `shazzoo/strategy-engine-plugin`, but the plugin slug
is **`strategy-engine`**, so views and components resolve as
`strategy-engine::…`. Do not change the slug without updating every
`strategy-engine::` reference and `<x-strategy-engine::…>` tag in
every theme that uses it.

## Other publishable tags

```bash
php artisan vendor:publish --tag=content-studio-config
```

```bash
php artisan vendor:publish --tag=content-studio-lang
```

## Multilingual sites

Locale-prefixed routes are registered only when `cms_is_multilang()` is true.
Build article URLs through `Support\ArticleRoutes` rather than `route()` or a
hand-built path — it checks the same flag, so the same code works on
single-language and multilingual sites:

```php
use Shazzoo\StrategyEngine\Support\ArticleRoutes;

ArticleRoutes::indexPath($locale);
ArticleRoutes::articleUrl($slug, $locale);
```

## Migrations

Every migration checks the state it changes before touching it, so the package
installs cleanly on a site that already has these tables and columns from an
earlier copy of the plugin, even when that copy named a migration differently.

## Development

Point a site at a working copy:

```json
{
  "repositories": [
    { "type": "path", "url": "../strategy-engine-plugin" }
  ]
}
```

After changing blocks, views or namespaces:

```bash
php artisan cms:build
```
