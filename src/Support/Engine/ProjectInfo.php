<?php

namespace Shazzoo\ContentStudio\Support\Engine;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Shazzoo\ContentStudio\Models\ContentStudioSetting;

class ProjectInfo
{
    /**
     * @return array<string,mixed>|null
     */
    public static function refresh(): ?array
    {
        $data = self::request();

        if ($data === null) {
            return null;
        }

        $setting = ContentStudioSetting::singleton();
        $meta = (array) ($setting->meta ?? []);
        $meta['project'] = $data;

        $setting->meta = $meta;
        $setting->save();

        self::$memo = $data;

        return $data;
    }

    /**
     * @return array<string,mixed>|null
     */
    public static function stored(): ?array
    {
        if (self::$memo !== null) {
            return self::$memo;
        }

        $project = ContentStudioSetting::singleton()->meta['project'] ?? null;

        self::$memo = is_array($project) ? $project : [];

        return self::$memo;
    }

    public static function forget(): void
    {
        self::$memo = null;
    }

    /** @var array<string,mixed>|null */
    private static ?array $memo = null;

    public static function primaryLocale(): ?string
    {
        $locale = self::stored()['primary_locale'] ?? null;

        return filled($locale) ? (string) $locale : null;
    }

    /**
     * @return array<int,string>
     */
    public static function locales(): array
    {
        return array_values(array_filter((array) (self::stored()['locales'] ?? [])));
    }

    public static function name(): ?string
    {
        return self::stored()['name'] ?? null;
    }

    public static function website(): ?string
    {
        return self::stored()['website'] ?? null;
    }

    public static function syncableTotal(): ?int
    {
        $total = self::stored()['content_counts']['total'] ?? null;

        return $total === null ? null : (int) $total;
    }

    /**
     * @return array<string,mixed>|null
     */
    private static function request(): ?array
    {
        $setting = ContentStudioSetting::singleton();

        $api_key = $setting->cs_api_key ?? null;
        $project_code = $setting->cs_project_code ?? null;

        if (! $api_key || ! $project_code) {
            return null;
        }

        $url = ConfirmPublished::apiUrl().'/projects/'.$project_code;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$api_key,
                'Accept' => 'application/json',
            ])->timeout(10)->get($url);
        } catch (\Throwable $e) {
            Log::warning('[ContentStudio] Kon projectgegevens niet ophalen: '.$e->getMessage(), ['url' => $url]);

            return null;
        }

        if ($response->failed()) {
            Log::warning('[ContentStudio] Kon projectgegevens niet ophalen', [
                'status' => $response->status(),
                'url' => $url,
            ]);

            return null;
        }

        $data = $response->json('data');

        return is_array($data) ? $data : null;
    }
}
