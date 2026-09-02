<?php

return [
    'default_threshold' => (float) env('FACE_SIMILARITY_THRESHOLD', 0.55),
    'engine' => env('FACE_ENGINE', 'opencv'),
    'model' => env('FACE_MODEL', 'sface'),
    'model_version' => env('FACE_MODEL_VERSION', 'v1'),
    'dimension' => (int) env('FACE_EMBEDDING_DIMENSION', 128),
    'liveness_threshold' => (float) env('FACE_MIN_LIVENESS', 0.68),
    'min_quality' => (float) env('FACE_MIN_QUALITY', 0.45),
    'min_frames' => (int) env('FACE_MIN_STABLE_FRAMES', 3),
];
