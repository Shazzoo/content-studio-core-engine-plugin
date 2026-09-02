<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_studio_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('content_studio_settings', 'index_template_key')) {
                $table->string('index_template_key')->nullable()->after('articles_per_page');
            }

            if (! Schema::hasColumn('content_studio_settings', 'index_template_settings')) {
                $table->json('index_template_settings')->nullable()->after('index_template_key');
            }

            if (! Schema::hasColumn('content_studio_settings', 'article_template_key')) {
                $table->string('article_template_key')->nullable()->after('index_template_settings');
            }

            if (! Schema::hasColumn('content_studio_settings', 'article_template_settings')) {
                $table->json('article_template_settings')->nullable()->after('article_template_key');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('content_studio_settings')) {
            return;
        }

        Schema::table('content_studio_settings', function (Blueprint $table) {
            foreach ([
                'index_template_key',
                'index_template_settings',
                'article_template_key',
                'article_template_settings',
            ] as $column) {
                if (Schema::hasColumn('content_studio_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
