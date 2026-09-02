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
        Schema::create('content_studio_actions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                ->constrained('content_studio_items')
                ->cascadeOnDelete();

            // What happened
            $table->string('type', 50);     // ai_generate|ai_rewrite|manual_edit|status_change|publish_push
            $table->string('status', 20)->default('success'); // success|error

            // Inputs/outputs
            $table->json('input')->nullable();     // prompts, params, ids, etc
            $table->longText('output')->nullable(); // generated text, error message, etc

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['item_id', 'type']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_studio_actions');
    }
};
