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
        Schema::table('content_studio_items', function (Blueprint $table) {
            if (! Schema::hasColumn('content_studio_items', 'job_status')) {
                $table->string('job_status')->nullable(); // queued|running|done|failed
                $table->text('job_error')->nullable();
            }

            if (! Schema::hasColumn('content_studio_items', 'generated_page_id')) {
                $table->foreignId('generated_page_id')->nullable()->constrained('pages')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('content_studio_items')) {
            return;
        }

        Schema::table('content_studio_items', function (Blueprint $table) {
            if (Schema::hasColumn('content_studio_items', 'generated_page_id')) {
                $table->dropConstrainedForeignId('generated_page_id');
            }

            foreach (['job_error', 'job_status'] as $column) {
                if (Schema::hasColumn('content_studio_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
