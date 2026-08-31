<?php

return [
    // Final threshold must be calibrated after the TFLite model is finalized.
    'default_threshold' => (float) env('BIOMETRIC_DEFAULT_THRESHOLD', 0.75),
];
