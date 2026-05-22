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
            'nisn',
            'nik',
            'no_kk',
            'nama_peserta_didik',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'agama',
            'kelas',
            'jurusan',
            'tahun_masuk',
            'jenis_tinggal',
            'alat_transportasi',
            'alamat',
            'dusun',
            'kelurahan',
            'kecamatan',
            'kode_pos',
            'no_hp_siswa',
            'email_siswa',
            'nama_ayah',
            'pendidikan_ayah',
            'pekerjaan_ayah',
            'penghasilan_ayah',
            'nama_ibu',
            'pendidikan_ibu',
            'pekerjaan_ibu',
            'penghasilan_ibu',
            'nama_wali',
            'pendidikan_wali',
            'pekerjaan_wali',
            'penghasilan_wali',
            'nama_orang_tua_wali',
            'no_hp_orang_tua_wali',
            'email_orang_tua_wali',
        ];
    }

    public function array(): array
    {
        return [
            [
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
                '2025',
                'Bersama Orang Tua',
                'Sepeda Motor',
                'Jl. Contoh No. 123',
                'Karangrejo',
                'Wedomartani',
                'Ngemplak',
                '55584',
                '081234567890',
                'ahmad.fauzi@example.com',
                'FAUZI',
                'S1',
                'Wiraswasta',
                '3 - 5 Juta',
                'SITI AMINAH',
                'SMA',
                'Ibu Rumah Tangga',
                '1 - 2 Juta',
                'BAPAK FAUZI',
                'S1',
                'Wiraswasta',
                '3 - 5 Juta',
                'BAPAK FAUZI',
                '081298765432',
                'wali.ahmad@example.com',
            ],
        ];
    }
}
