<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('content_studio_settings')) {
            return;
        }

        if (! Schema::hasColumns('content_studio_settings', ['index_template_settings', 'article_template_settings'])) {
            return;
        }

        DB::table('content_studio_settings')
            ->orderBy('id')
            ->each(function (object $setting): void {
                DB::table('content_studio_settings')
                    ->where('id', $setting->id)
                    ->update([
                        'index_template_settings' => $this->templateSettingsWithCtaDefaults($setting->index_template_settings),
                        'article_template_settings' => $this->templateSettingsWithCtaDefaults($setting->article_template_settings),
                    ]);
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('content_studio_settings')) {
            return;
        }

        if (! Schema::hasColumns('content_studio_settings', ['index_template_settings', 'article_template_settings'])) {
            return;
        }

        DB::table('content_studio_settings')
            ->orderBy('id')
            ->each(function (object $setting): void {
                DB::table('content_studio_settings')
                    ->where('id', $setting->id)
                    ->update([
                        'index_template_settings' => $this->templateSettingsWithoutCtaDefaults($setting->index_template_settings),
                        'article_template_settings' => $this->templateSettingsWithoutCtaDefaults($setting->article_template_settings),
                    ]);
            });
    }

    private function templateSettingsWithCtaDefaults(?string $templateSettings): string
    {
        $settings = $this->decodeTemplateSettings($templateSettings);
        $locales = $this->locales();
        $fallbackTitle = is_string($settings['title'] ?? null) ? $settings['title'] : null;

        $settings = array_replace(['title' => [], 'cta' => []], $settings);

        if (! is_array($settings['title'])) {
            $settings['title'] = [];
        }

        if (! is_array($settings['cta'])) {
            $settings['cta'] = [];
        }

        foreach ($locales as $locale) {
            $settings['title'][$locale] = $settings['title'][$locale] ?? $fallbackTitle;

            $localizedSettings = is_array($settings['cta'][$locale] ?? null) ? $settings['cta'][$locale] : [];

            $settings['cta'][$locale] = array_replace([
                'title' => null,
                'content' => null,
                'button_text' => null,
                'button_url' => null,
            ], $localizedSettings);
        }

        return json_encode($settings, JSON_THROW_ON_ERROR);
    }

    private function templateSettingsWithoutCtaDefaults(?string $templateSettings): ?string
    {
        $settings = $this->decodeTemplateSettings($templateSettings);

        unset($settings['title'], $settings['cta']);

        if ($settings === []) {
            return null;
        }

        return json_encode($settings, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeTemplateSettings(?string $templateSettings): array
    {
        if (! filled($templateSettings)) {
            return [];
        }

        $decoded = json_decode($templateSettings, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return array<int, string>
     */
    private function locales(): array
    {
        $runtime = function_exists('cms_runtime') ? cms_runtime() : [];
        $locales = $runtime['available_locales'] ?? [config('app.locale', 'nl'), config('app.fallback_locale', 'en')];

        return array_values(array_unique(array_filter($locales)));
    }
};
