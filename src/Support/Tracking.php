<?php

namespace Shazzoo\StrategyEngine\Support;

class Tracking
{
    /**
     * Events gaan altijd naar de engine, nooit naar de site zelf.
     */
    public const DEFAULT_ENDPOINT = 'https://engine.content-studio.com/api/tracking/event';

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
}
