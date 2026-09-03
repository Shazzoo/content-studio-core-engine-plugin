<?php

return [
    'engine' => [
        'api_url' => env('CONTENT_STUDIO_ENGINE_API_URL', 'https://engine.content-studio.com/api/v1'),
    ],

    'tracking' => [
        'enabled' => env('CONTENT_STUDIO_TRACKING_ENABLED', true),
        'endpoint' => env(
            'CONTENT_STUDIO_TRACKING_ENDPOINT',
            'https://engine.content-studio.com/api/tracking/event'
        ),
    ],
];
