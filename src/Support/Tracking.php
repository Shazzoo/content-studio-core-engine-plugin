<?php

namespace Shazzoo\StrategyEngine\Support;

class Tracking
{
    /**
     * Events gaan altijd naar de engine, nooit naar de site zelf.
     */
    public const DEFAULT_ENDPOINT = 'https://engine.content-studio.com/api/tracking/event';

    /**
     * Pad van het gepubliceerde script binnen public/.
     */
    public const PUBLIC_PATH = 'vendor/strategy-engine/tracking.js';

    public static function enabled(): bool
    {
        return (bool) config('content-studio.tracking.enabled', true);
    }

    /**
     * Altijd een absolute engine-URL. Een ontbrekende (bijv. verouderde
     * config-cache) of relatieve waarde zou het event naar de site zelf
     * sturen, dus die valt terug op de default.
     */
    public static function endpoint(): string
    {
        $endpoint = trim((string) config('content-studio.tracking.endpoint', ''));

        if ($endpoint === '' || ! preg_match('#^https?://#i', $endpoint)) {
            return self::DEFAULT_ENDPOINT;
        }

        return $endpoint;
    }

    /**
     * Geef het gepubliceerde bestand voorrang: dat serveert de webserver
     * direct, ook als die /js/* als statische assets afhandelt en het
     * verzoek dus nooit bij PHP aankomt. Zonder publicatie valt het terug
     * op de route.
     */
    public static function scriptUrl(): string
    {
        $file = public_path(self::PUBLIC_PATH);

        if (is_file($file)) {
            return asset(self::PUBLIC_PATH).'?v='.filemtime($file);
        }

        return route('content-studio.tracking-script');
    }
}
