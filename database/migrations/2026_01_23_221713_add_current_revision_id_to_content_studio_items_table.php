<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_studio_items', function (Blueprint $table) {
            $table->foreignId('current_revision_id')
                ->nullable()
                ->after('id') // of na status/type
                ->constrained('content_studio_revisions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('content_studio_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_revision_id');
        });
    }
};
