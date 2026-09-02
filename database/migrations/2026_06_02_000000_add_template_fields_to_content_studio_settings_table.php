<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_studio_settings', function (Blueprint $table) {
            $table->string('index_template_key')->nullable()->after('articles_per_page');
            $table->json('index_template_settings')->nullable()->after('index_template_key');
            $table->string('article_template_key')->nullable()->after('index_template_settings');
            $table->json('article_template_settings')->nullable()->after('article_template_key');
        });
    }

    public function down(): void
    {
        Schema::table('content_studio_settings', function (Blueprint $table) {
            $table->dropColumn([
                'index_template_key',
                'index_template_settings',
                'article_template_key',
                'article_template_settings',
            ]);
        });
    }
};
