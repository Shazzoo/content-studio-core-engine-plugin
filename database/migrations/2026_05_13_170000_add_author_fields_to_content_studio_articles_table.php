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
            $table->string('author_name')->nullable()->after('hub_content_id');
            $table->string('author_role_title')->nullable()->after('author_name');
            $table->string('author_experience_label')->nullable()->after('author_role_title');
            $table->text('author_experience_summary')->nullable()->after('author_experience_label');
            $table->text('author_article_relevance')->nullable()->after('author_experience_summary');
            $table->text('author_boundary_note')->nullable()->after('author_article_relevance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_studio_articles', function (Blueprint $table) {
            $table->dropColumn([
                'author_name',
                'author_role_title',
                'author_experience_label',
                'author_experience_summary',
                'author_article_relevance',
                'author_boundary_note',
            ]);
        });
    }
};
