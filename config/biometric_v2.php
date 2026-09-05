<?php

return [
    'default_threshold' => (float) env('FACE_SIMILARITY_THRESHOLD', 0.55),
    'engine' => env('FACE_ENGINE', 'onnxruntime'),
    'model' => env('FACE_MODEL', 'arcface'),
    'model_version' => env('FACE_MODEL_VERSION', 'buffalo_l_w600k_r50'),
    'dimension' => (int) env('FACE_EMBEDDING_DIMENSION', 512),
    'liveness_threshold' => (float) env('FACE_MIN_LIVENESS', 0.68),
    'min_quality' => (float) env('FACE_MIN_QUALITY', 0.45),
    'min_frames' => (int) env('FACE_MIN_STABLE_FRAMES', 3),
];
