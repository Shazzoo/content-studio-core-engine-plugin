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
        Schema::table('content_studio_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('content_studio_settings', 'cs_api_key')) {
                $table->string('cs_api_key')->nullable()->after('site_context');
            }

            if (! Schema::hasColumn('content_studio_settings', 'cs_project_code')) {
                $table->string('cs_project_code')->nullable()->after('cs_api_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_studio_settings', function (Blueprint $table) {
            if (Schema::hasColumn('content_studio_settings', 'cs_project_code')) {
                $table->dropColumn('cs_project_code');
            }

            if (Schema::hasColumn('content_studio_settings', 'cs_api_key')) {
                $table->dropColumn('cs_api_key');
            }
        });
    }
};
