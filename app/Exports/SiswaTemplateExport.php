<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'SCOD',
            'ASAL SEKOLAH/MADRASAH',
            'NIS',
            'NISN',
            'NIK',
            'NO_KK',
            'NAMA_PESERTA_DIDIK',
            'JENIS_KELAMIN',
            'TEMPAT_LAHIR',
            'TANGGAL_LAHIR',
            'AGAMA',
            'KELAS',
            'JURUSAN',
            'ALAMAT',
            'DUSUN',
            'KELURAHAN',
            'KECAMATAN',
            'NO_HP_SISWA',
            'EMAIL_SISWA',
            'NAMA_AYAH',
            'NAMA_IBU',
            'NO_HP_ORANG_TUA_WALI',
        ];
    }

    public function array(): array
    {
        return [
            [
                '11001',
                'MA CONTOH NUIST',
                '20250001',
                '0098877665',
                '3401010101010001',
                '3401010101010002',
                'AHMAD FAUZI',
                'L',
                'YOGYAKARTA',
                '2010-01-15',
                'Islam',
                'X IPA 1',
                'IPA',
                'Jl. Contoh No. 123',
                'Karangrejo',
                'Wedomartani',
                'Ngemplak',
                '081234567890',
                'ahmad.fauzi@example.com',
                'FAUZI',
                'SITI AMINAH',
                '081298765432',
            ],
        ];
    }
}
