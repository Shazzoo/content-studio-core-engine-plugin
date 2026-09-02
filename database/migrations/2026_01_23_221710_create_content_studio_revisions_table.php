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
        Schema::create('content_studio_revisions', function (Blueprint $table) {
            $table->id();
            // Content identity
            $table->foreignId('item_id')
                ->constrained('content_studio_items')
                ->cascadeOnDelete();

            $table->unsignedInteger('version')->default(1);

            $table->string('title')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();

            $table->json('meta')->nullable();
            $table->json('target')->nullable();
            $table->string('reason')->nullable()->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['item_id', 'version']);
            $table->index(['item_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_studio_revisions');
    }
};
