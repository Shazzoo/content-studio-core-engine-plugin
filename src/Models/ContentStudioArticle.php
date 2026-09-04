<?php

namespace Shazzoo\StrategyEngine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContentStudioArticle extends Model
{
    protected $table = 'content_studio_articles';

    protected $fillable = [
        'title',
        'excerpt',
        'body_html',
        'featured_image_url',
        'featured_image_alt',
        'meta_title',
        'meta_description',
        'seo_title',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
        'content_studio_article_id',
        'locale',
        'slug',
        'published_url',
        'published_confirmed_at',
        'cta',
        'primary_keyword',
        'cluster',
        'funnel_stage',
        'intent',
        'angle',
        'source_month',
        'generated_at',
        'planned_at',
        'content_type',
        'cluster_key',
        'hub_content_id',
        'author_name',
        'author_role_title',
        'author_experience_label',
        'author_experience_summary',
        'author_article_relevance',
        'author_boundary_note',
        'authors',
    ];

    protected $casts = [
        'cta' => 'array',
        'cluster' => 'array',
        'authors' => 'array',
        'generated_at' => 'datetime',
        'planned_at' => 'datetime',
        'published_confirmed_at' => 'datetime',
    ];

    public function related_hub_articles()
    {
        return $this->hasMany(ContentStudioArticle::class, 'hub_content_id', 'content_studio_article_id')
            ->where('locale', $this->locale);
    }

    /*
     * De accessors hieronder bestaan zodat een site zijn eigen views kan
     * schrijven zonder de kolomnamen van de Engine te kennen. Verander ze niet
     * zonder reden: overschreven views in resources/views/vendor leunen erop.
     */

    public function thumbnailUrl(): ?string
    {
        return $this->featured_image_url;
    }

    /** Excerpt uit de Engine, of anders een ingekorte versie van de body. */
    public function excerpt(int $limit = 160): string
    {
        return (string) ($this->excerpt ?: Str::limit(strip_tags((string) $this->body_html), $limit));
    }

    public function getContentAttribute(): ?string
    {
        return $this->body_html;
    }

    public function getAuthorAttribute(): ?object
    {
        if (! $this->author_name) {
            return null;
        }

        return (object) [
            'name' => $this->author_name,
            'profile_photo_url' => null,
        ];
    }
}
