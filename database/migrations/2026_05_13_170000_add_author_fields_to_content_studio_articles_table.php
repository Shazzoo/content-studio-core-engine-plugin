<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('content_studio_articles', function (Blueprint $table) {
            if (! Schema::hasColumn('content_studio_articles', 'author_name')) {
                $table->string('author_name')->nullable()->after('hub_content_id');
            }

            if (! Schema::hasColumn('content_studio_articles', 'author_role_title')) {
                $table->string('author_role_title')->nullable()->after('author_name');
            }

            if (! Schema::hasColumn('content_studio_articles', 'author_experience_label')) {
                $table->string('author_experience_label')->nullable()->after('author_role_title');
            }

            if (! Schema::hasColumn('content_studio_articles', 'author_experience_summary')) {
                $table->text('author_experience_summary')->nullable()->after('author_experience_label');
            }

            if (! Schema::hasColumn('content_studio_articles', 'author_article_relevance')) {
                $table->text('author_article_relevance')->nullable()->after('author_experience_summary');
            }

            if (! Schema::hasColumn('content_studio_articles', 'author_boundary_note')) {
                $table->text('author_boundary_note')->nullable()->after('author_article_relevance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('content_studio_articles')) {
            return;
        }

        Schema::table('content_studio_articles', function (Blueprint $table) {
            foreach ([
                'author_name',
                'author_role_title',
                'author_experience_label',
                'author_experience_summary',
                'author_article_relevance',
                'author_boundary_note',
            ] as $column) {
                if (Schema::hasColumn('content_studio_articles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
