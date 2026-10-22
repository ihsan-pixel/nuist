<?php

return [
    'creatable_groups' => [
        'MGMP Matematika', 'MGMP PAI', 'MGMP PJOK',
        'MGMP Soseogeo', 'MGMP Seni Budaya', 'MGMP Aswaja',
        'MGMP TITL', 'MGMP Tata Busana', 'MGMP Farmasi',
        'MGMP Bahasa Arab', 'MGMP Akidah', 'MGMP SKI',
        'MGMP Sosiologi', 'MGMP Geografi', 'MGMP Sejarah',
        'MGMP Keterampilan', 'MGMP Otomotif', 'MGMP KWU',
    ],
    // Match by name, never by database IDs (which differ between environments).
    // Regional organisations and different subjects must not be merged by fuzzy matching.
    'aliases' => [
        'MGMP PAI' => ['PAI SMK', 'LPM PAI'],
        'MGMP PJOK' => ["PJOK LP MA'ARIF DIY", 'PJOK LPM'],
        'MGMP Soseogeo' => ['Sesogeo', 'Sosiogeo', 'Sesegio', 'Sosigio'],
        'MGMP Bahasa Arab' => ['DIY Bahasa Arab'],
        'MGMP Akidah' => ['DIY Akidah'],
        'MGMP Bahasa Indonesia SMK' => ['Bahasa Indonesia', 'B. Indonesia', 'B Indonesia Maarif'],
        'MGBK (Musyawarah Guru Bimbingan dan Konseling)' => ['MGBK', 'BK', 'Bimbingan Konseling'],
        "META YOGYAKARTA (Ma'arif English Teacher Association)" => ['META', 'Bahasa Inggris'],
        'MGMP EKOBANK' => ['EKOBANK', 'EKOBANK KWU', 'Ekobank dan KWU'],
        'MGMP Teknik Sepeda Motor' => ['TSM', 'TBSM', 'TBSM LPM'],
        'IPA Terapan' => ['IPA Terapa'],
        'PENDIDIKAN PANCASILA' => ['PKN'],
    ],
];
