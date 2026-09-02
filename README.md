# Content Studio Core — Engine plugin

Blog articles generated and synced from the Content Studio Engine, as a plugin
for [Content Studio Core](https://github.com/Shazzoo/content-studio-core).

> **This is the plugin for the Core CMS.** Sites still running the pre-Core CMS
> (where the CMS lives in the site's own `App\Support\*` namespace, such as
> shazzoo-site) use the separate `Shazzoo/content-studio-plugin` repository
> instead. The two are not interchangeable: this package imports
> `Shazzoo\ContentStudioCore\Support\*`, which does not exist on a pre-Core
> site.

One package, many sites. The functionality lives here and is updated with
`composer update`; **each site keeps its own styling** through view overrides.

## Requirements

- PHP 8.3+
- `shazzoo/content-studio-core` ^0.1.6 (the override mechanism needs 0.1.6)

## Install

```bash
composer require shazzoo/content-studio-core-engine-plugin
```

Then activate **Content Studio** under *Instellingen → Plugins*. Activation runs
the migrations and publishes assets. Configure the API key, project code and
route prefix on the Content Studio Settings page.

## Styling per site

The plugin ships working, deliberately plain views. A site overrides only what
it wants to restyle; everything else keeps coming from the package, so plugin
updates still reach the views you did not touch.

There are three levels, cheapest first.

### 1. Override individual views

Publish the views you want to change:

```bash
php artisan vendor:publish --tag=content-studio-components
```

That copies the component views into `resources/views/vendor/content-studio-plugin/components/`.
Edit them freely — markup, Tailwind classes, structure. Any file you delete from
that directory falls back to the package version.

Use `--tag=content-studio-views` instead to publish *all* views including the
article pages and pagination.

> **Publish only what you restyle.** Every published file is a file you now
> maintain: it stops receiving upstream changes. Restyling three components is
> three files to maintain, not sixteen.

Available views:

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

### 2. Theme templates

For the index and detail pages, the active theme wins over the plugin entirely.
`ArticleController` looks for these first:

```
{theme}::templates.articles-index
{theme}::templates.articles-show
```

If your theme provides them, the plugin's own `articles/index` and
`articles/show` are never used. This is the right level for a site whose article
pages have a fundamentally different layout rather than different styling.

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

### A note on the view namespace

The Composer package is `shazzoo/content-studio-core-engine-plugin`, but the
plugin **slug stays `content-studio-plugin`**, so views and components resolve as
`content-studio-plugin::…` exactly as they did when the plugin was a directory
under `app/Plugins/`. That keeps existing themes working when a site switches
from the directory copy to this package.

Do not change the slug without updating every `content-studio-plugin::` view
reference and `<x-content-studio-plugin::…>` tag in every theme that uses it.

### How resolution works

Core registers the site override directory ahead of the package's own views, so
the finder picks the first path that has the file:

```
1. resources/views/vendor/content-studio-plugin/   ← your overrides
2. vendor/shazzoo/content-studio-core-engine-plugin/resources/views/   ← package defaults
```

Resolution is per file, not all-or-nothing. This requires core ≥ 0.1.6; before
that the package path shadowed the override directory and publishing had no
effect.

## Other publishable tags

```bash
php artisan vendor:publish --tag=content-studio-config
```

```bash
php artisan vendor:publish --tag=content-studio-lang
```

Config controls the Engine API URL, which also reads
`CONTENT_STUDIO_ENGINE_API_URL` from `.env`.

## Syncing articles

```bash
php artisan content-studio:articles
```

Registered to run daily. The plugin confirms publication back to the Engine, so
a synced article drops out of the pending list.

## Multilingual sites

Locale-prefixed routes are registered only when `cms_is_multilang()` is true.
Build article URLs through `Support\ArticleRoutes` rather than `route()` or a
hand-built path — it checks the same flag, so the same code works on
single-language and multilingual sites:

```php
use Shazzoo\ContentStudio\Support\ArticleRoutes;

ArticleRoutes::indexPath($locale);
ArticleRoutes::articleUrl($slug, $locale);
```

## Development

Point a site at a working copy:

```json
{
  "repositories": [
    { "type": "path", "url": "../content-studio-core-engine-plugin" }
  ]
}
```

After changing blocks, views or namespaces:

```bash
php artisan cms:build
```
