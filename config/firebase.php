<?php

return [
    'app_check' => [
        // disabled: bypass verification, monitor: report failures, enforce: reject failures.
        'mode' => env('FIREBASE_APP_CHECK_MODE', 'monitor'),
        'project_number' => env('FIREBASE_PROJECT_NUMBER'),
        'allowed_app_ids' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('FIREBASE_APP_CHECK_ALLOWED_APP_IDS', ''))
        ))),
        'jwks_url' => env(
            'FIREBASE_APP_CHECK_JWKS_URL',
            'https://firebaseappcheck.googleapis.com/v1/jwks'
        ),
        'jwks_cache_seconds' => (int) env('FIREBASE_APP_CHECK_JWKS_CACHE_SECONDS', 18000),
    ],
];
