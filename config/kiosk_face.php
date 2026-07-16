<?php

return [
    'driver' => env('KIOSK_FACE_DRIVER', 'browser'),
    'python_service' => [
        'base_url' => rtrim((string) env('KIOSK_FACE_SERVICE_URL', 'http://127.0.0.1:8800'), '/'),
        'api_key' => env('KIOSK_FACE_SERVICE_KEY'),
        'timeout' => (int) env('KIOSK_FACE_SERVICE_TIMEOUT', 20),
        'connect_timeout' => (int) env('KIOSK_FACE_SERVICE_CONNECT_TIMEOUT', 5),
    ],
    'capture' => [
        'frame_count' => (int) env('KIOSK_FACE_CAPTURE_FRAME_COUNT', 6),
        'max_frames' => (int) env('KIOSK_FACE_CAPTURE_MAX_FRAMES', 8),
    ],
];
