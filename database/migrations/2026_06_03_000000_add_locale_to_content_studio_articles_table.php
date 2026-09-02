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
            if (! Schema::hasColumn('content_studio_articles', 'locale')) {
                $table->string('locale', 10)
                    ->default(config('app.locale', 'nl'))
                    ->after('content_studio_article_id')
                    ->index();
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
            if (Schema::hasColumn('content_studio_articles', 'locale')) {
                $table->dropIndex(['locale']);
                $table->dropColumn('locale');
            }
        });
    }
};
