<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * De Engine levert deze velden al mee in de API; ze werden alleen nog niet
     * opgeslagen. og_description en twitter_description kunnen langer zijn dan
     * een titel, vandaar text.
     */
    private const COLUMNS = [
        'seo_title' => 'string',
        'og_title' => 'string',
        'og_description' => 'text',
        'twitter_title' => 'string',
        'twitter_description' => 'text',
    ];

    public function up(): void
    {
        Schema::table('content_studio_articles', function (Blueprint $table) {
            foreach (self::COLUMNS as $column => $type) {
                if (Schema::hasColumn('content_studio_articles', $column)) {
                    continue;
                }

                $table->{$type}($column)->nullable()->after('meta_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_studio_articles', function (Blueprint $table) {
            foreach (array_keys(self::COLUMNS) as $column) {
                if (Schema::hasColumn('content_studio_articles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
