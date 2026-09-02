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
        Schema::create('content_studio_settings', function (Blueprint $table) {
            $table->id();

            // 1 record per site/tenant (voor nu single instance)
            $table->boolean('ai_enabled')->default(true);
            $table->string('model')->default('gpt-4o-mini');

            $table->string('default_language')->default('nl')->nullable();
            $table->string('default_tone')->default('friendly, practical')->nullable();
            $table->string('default_audience')->nullable();

            $table->longText('site_context')->nullable();

            // future-proof
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_studio_settings');
    }
};
