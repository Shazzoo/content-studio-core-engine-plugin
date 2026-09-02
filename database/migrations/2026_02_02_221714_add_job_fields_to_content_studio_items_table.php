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
            $table->string('job_status')->nullable(); // queued|running|done|failed
            $table->text('job_error')->nullable();
            $table->foreignId('generated_page_id')->nullable()->constrained('pages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_studio_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('generated_page_id');
            $table->dropColumn('job_error');
            $table->dropColumn('job_status');
        });
    }
};
