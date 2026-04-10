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
            'nis',
            'nama_lengkap_siswa',
            'nama_orang_tua_wali',
            'email_siswa',
            'email_orang_tua_wali',
            'no_hp_siswa',
            'no_hp_orang_tua_wali',
            'kelas',
            'alamat',
        ];
    }

    public function array(): array
    {
        return [
            [
                '20250001',
                'AHMAD FAUZI',
                'BAPAK FAUZI',
                'ahmad.fauzi@example.com',
                'wali.ahmad@example.com',
                '081234567890',
                '081298765432',
                'X IPA 1',
                'Jl. Contoh No. 123, Kudus',
            ],
        ];
    }
}
