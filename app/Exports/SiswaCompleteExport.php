<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaCompleteExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly Collection $siswas
    ) {
    }

    public function headings(): array
    {
        return [
            'No',
            'SCOD',
            'Asal Sekolah / Madrasah',
            'NIS',
            'NISN',
            'NIK',
            'No. KK',
            'Nama Peserta Didik',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Kelas',
            'Jurusan',
            'Alamat',
            'Dusun',
            'Kelurahan',
            'Kecamatan',
            'No. HP Siswa',
            'Email Siswa',
            'Nama Ayah',
            'Nama Ibu',
            'Nama Orang Tua / Wali',
            'No. HP Orang Tua / Wali',
            'Status Aktif',
        ];
    }

    public function collection(): Collection
    {
        return $this->siswas->values()->map(function (Siswa $siswa, int $index) {
            return [
                'no' => $index + 1,
                'scod' => $siswa->scod ?: ($siswa->madrasah->scod ?? '-'),
                'asal_sekolah_madrasah' => $siswa->nama_madrasah ?: ($siswa->madrasah->name ?? '-'),
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'nik' => $siswa->nik,
                'no_kk' => $siswa->no_kk,
                'nama_peserta_didik' => $siswa->nama_lengkap,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'tempat_lahir' => $siswa->tempat_lahir,
                'tanggal_lahir' => optional($siswa->tanggal_lahir)->format('Y-m-d'),
                'agama' => $siswa->agama,
                'kelas' => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'alamat' => $siswa->alamat,
                'dusun' => $siswa->dusun,
                'kelurahan' => $siswa->kelurahan,
                'kecamatan' => $siswa->kecamatan,
                'no_hp_siswa' => $siswa->no_hp,
                'email_siswa' => $siswa->email,
                'nama_ayah' => $siswa->nama_ayah,
                'nama_ibu' => $siswa->nama_ibu,
                'nama_orang_tua_wali' => $siswa->nama_orang_tua_wali ?: ($siswa->nama_ayah ?: $siswa->nama_ibu),
                'no_hp_orang_tua_wali' => $siswa->no_hp_orang_tua_wali,
                'status_aktif' => $siswa->is_active ? 'Aktif' : 'Nonaktif',
            ];
        });
    }
}
