<?php

namespace Shazzoo\StrategyEngine\Http\Controllers;

use Illuminate\Http\Response;

class TrackingScriptController
{
    /**
     * Serveer het tracking script vanaf de eigen origin, zodat een CSP met
     * script-src 'self' het toestaat (inline scripts worden geblokkeerd).
     */
    public function __invoke(): Response
    {
        $path = dirname(__DIR__, 3).'/resources/js/tracking.js';

        return response(
            is_file($path) ? (string) file_get_contents($path) : '',
            200,
            [
                'Content-Type' => 'application/javascript; charset=UTF-8',
                'Cache-Control' => 'public, max-age=3600',
            ]
        );
    }
}
