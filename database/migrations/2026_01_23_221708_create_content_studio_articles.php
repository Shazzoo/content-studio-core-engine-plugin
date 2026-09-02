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
        Schema::create('content_studio_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_studio_article_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->string('excerpt')->nullable();
            $table->longText('body_html')->nullable();
            $table->string('featured_image_url')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('slug')->nullable();
            $table->json('cta')->nullable();
            $table->string('primary_keyword')->nullable();
            $table->json('cluster')->nullable();
            $table->string('funnel_stage')->nullable();
            $table->string('intent')->nullable();
            $table->string('angle')->nullable();
            $table->string('source_month')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('planned_at')->nullable();
            $table->string('content_type')->nullable();
            $table->string('cluster_key')->nullable();
            $table->string('hub_content_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_studio_articles');
    }
};
