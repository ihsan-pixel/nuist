<?php

return [
    // Match by name, never by database IDs (which differ between environments).
    // Regional organisations and different subjects must not be merged by fuzzy matching.
    'aliases' => [
        'MGMP Bahasa Indonesia SMK' => ['Bahasa Indonesia', 'B. Indonesia', 'B Indonesia Maarif'],
        'MGBK (Musyawarah Guru Bimbingan dan Konseling)' => ['MGBK', 'BK', 'Bimbingan Konseling'],
        "META YOGYAKARTA (Ma'arif English Teacher Association)" => ['META', 'Bahasa Inggris'],
        'MGMP EKOBANK' => ['EKOBANK', 'EKOBANK KWU', 'Ekobank dan KWU'],
        'MGMP Teknik Sepeda Motor' => ['TSM', 'TBSM', 'TBSM LPM'],
        'IPA Terapan' => ['IPA Terapa'],
        'PENDIDIKAN PANCASILA' => ['PKN'],
    ],
];
