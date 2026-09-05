<?php

return [
    'driver' => env('KIOSK_FACE_V2_DRIVER', 'python'),
    'python_service' => [
        'base_url' => rtrim((string) env('FACE_ENGINE_URL', env('KIOSK_FACE_SERVICE_URL', 'http://127.0.0.1:8800')), '/'),
        'api_key' => env('KIOSK_FACE_SERVICE_KEY'),
        'timeout' => (int) env('KIOSK_FACE_SERVICE_TIMEOUT', 20),
        'connect_timeout' => (int) env('KIOSK_FACE_SERVICE_CONNECT_TIMEOUT', 5),
    ],
    'capture' => [
        'frame_count' => (int) env('KIOSK_FACE_CAPTURE_FRAME_COUNT', 6),
        'max_frames' => (int) env('KIOSK_FACE_CAPTURE_MAX_FRAMES', 8),
    ],
    'provider' => env('FACE_PROVIDER', 'insightface_arcface'),
    'model' => env('FACE_MODEL', 'arcface'),
    'model_version' => env('FACE_MODEL_VERSION', 'buffalo_l_w600k_r50'),
    'disable_liveness' => env('KIOSK_FACE_DISABLE_LIVENESS', false),
    'thresholds' => [
        'min_detection_score' => (float) env('FACE_MIN_DETECTION_SCORE', 0.88),
        'min_quality' => (float) env('FACE_MIN_QUALITY', 0.45),
        'min_liveness' => (float) env('FACE_MIN_LIVENESS', 0.68),
        'similarity' => (float) env('FACE_SIMILARITY_THRESHOLD', 0.55),
        'min_stable_frames' => (int) env('FACE_MIN_STABLE_FRAMES', 3),
    ],
];
