<?php

return [
    'domain' => env('AMI_DOMAIN', 'ami.nuist.id'),
    'fallback_domains' => array_filter(array_map('trim', explode(',', (string) env('AMI_FALLBACK_DOMAINS', 'localhost,127.0.0.1')))),
    'min_evidence_per_indicator' => (int) env('AMI_MIN_EVIDENCE_PER_INDICATOR', 1),
    'seed_dummy_data' => env('AMI_SEED_DUMMY_DATA', env('APP_ENV') !== 'production'),
    'score_labels' => [
        1 => 'Kurang',
        2 => 'Cukup Baik',
        3 => 'Baik',
        4 => 'Sangat Baik',
    ],
    'followup_statuses' => [
        'belum_ditindaklanjuti' => 'Belum Ditindaklanjuti',
        'dalam_proses' => 'Dalam Proses',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'selesai' => 'Selesai',
        'perlu_perbaikan' => 'Perlu Perbaikan',
    ],
];
