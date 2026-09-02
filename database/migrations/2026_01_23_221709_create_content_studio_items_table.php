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
        if (Schema::hasTable('content_studio_items')) {
            return;
        }

        Schema::create('content_studio_items', function (Blueprint $table) {
            $table->id();
            // Content identity
            $table->string('title')->nullable();
            $table->string('type', 50)->default('article'); // article|landing|faq|email|seo-brief|social
            $table->string('stage', 50)->default('idea');   // idea|draft|review|approved|published|archived

            // Localization
            $table->string('locale', 10)->default('nl');    // nl, en, de, etc
            $table->uuid('translation_key')->nullable()->index();

            // Core content
            $table->text('summary')->nullable();            // good for ideas
            $table->longText('content')->nullable();        // good for drafts

            // Flexible config
            $table->json('meta')->nullable();               // tone, keywords, seo title/desc, etc
            $table->json('target')->nullable();             // {"page_id":12,"channel":"website"} etc

            // Ownership / auditing
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Helpful indexes
            $table->index(['type', 'stage']);
            $table->index(['locale', 'stage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_studio_items');
    }
};
