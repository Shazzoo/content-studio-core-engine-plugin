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
            if (! Schema::hasColumn('content_studio_articles', 'published_url')) {
                $table->string('published_url')->nullable()->after('slug');
            }

            if (! Schema::hasColumn('content_studio_articles', 'published_confirmed_at')) {
                $table->timestamp('published_confirmed_at')->nullable()->after('published_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_studio_articles', function (Blueprint $table) {
            if (Schema::hasColumn('content_studio_articles', 'published_confirmed_at')) {
                $table->dropColumn('published_confirmed_at');
            }

            if (Schema::hasColumn('content_studio_articles', 'published_url')) {
                $table->dropColumn('published_url');
            }
        });
    }
};
