<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_studio_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('content_studio_settings', 'articles_per_block')) {
                $table->unsignedTinyInteger('articles_per_block')->default(6)->after('route_prefix');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_studio_settings', function (Blueprint $table) {
            if (Schema::hasColumn('content_studio_settings', 'articles_per_block')) {
                $table->dropColumn('articles_per_block');
            }
        });
    }
};
