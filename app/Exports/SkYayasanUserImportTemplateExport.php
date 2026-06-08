<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SkYayasanUserImportTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'No',
            'NUIST ID',
            'Nama',
            'Gelar',
            'Tempat Lahir',
            'Tanggal Lahir',
            "NIP Ma'arif",
            'NUPTK',
            'Nomor Kartanu',
            'TMT Pertama',
            'Masa Kerja',
            'Pendidikan Terakhir',
            'Tahun Lulus',
            'Program Studi',
            'Mapel/Tugas yang Diampu',
            'Penilaian Kinerja',
            'Keterangan',
        ];
    }

    public function array(): array
    {
        return [
            [
                1,
                '',
                'Agus',
                '',
                'Sleman',
                '04 Mei 1990',
                "19900504-201507-401-697",
                '718290171615',
                '34072891.0001',
                '01 Juli 2015',
                '11 Tahun 00 Bulan',
                'S1, Universitas Negeri Yogyakarta',
                '2014',
                'Pendidikan Matematika',
                'Matematika',
                '85,00',
                'Perpanjangan/Pengangkatan GTY, GTT, PTY, PTT',
            ],
        ];
    }
}
