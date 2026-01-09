<?php

namespace App\Exports;

use App\Models\PPDBPendaftar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PPDBPendaftarExport implements FromCollection, WithHeadings
{
    protected $ppdbSetting;

    public function __construct($ppdbSetting)
    {
        $this->ppdbSetting = $ppdbSetting;
    }

    public function headings(): array
    {
        return [
            'No Pendaftaran',
            'Nama Lengkap',
            'NISN',
            'Asal Sekolah',
            'Jurusan Pilihan',
            'Jalur',
            'Skor Total',
            'Status',
            'Tanggal Daftar',
            'Email',
            'No HP',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Nama Ayah',
            'Nama Ibu',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'Penghasilan Orang Tua',
            'Jumlah Saudara',
            'Anak Ke',
            'Prestasi Akademik',
            'Prestasi Non Akademik',
            'Keterangan Prestasi',
            'KIP/PKH',
            'Bukti Domisili',
            'Surat Mutasi',
            'Surat Keterangan Lulus',
            'Catatan Verifikasi'
        ];
    }

    public function collection()
    {
        $pendaftars = $this->ppdbSetting->pendaftars()
            ->with('ppdbJalur')
            ->orderBy('skor_total', 'desc')
            ->get();

        return $pendaftars->map(function ($pendaftar, $index) {
            return [
                'No Pendaftaran' => $pendaftar->nomor_pendaftaran,
                'Nama Lengkap' => $pendaftar->nama_lengkap,
                'NISN' => $pendaftar->nisn,
                'Asal Sekolah' => $pendaftar->asal_sekolah,
                'Jurusan Pilihan' => $pendaftar->jurusan_pilihan,
                'Jalur' => $pendaftar->ppdbJalur->nama_jalur ?? '',
                'Skor Total' => $pendaftar->skor_total ?? 0,
                'Status' => $this->getStatusLabel($pendaftar->status),
                'Tanggal Daftar' => $pendaftar->created_at->format('d/m/Y H:i'),
                'Email' => $pendaftar->email,
                'No HP' => $pendaftar->no_hp,
                'Tempat Lahir' => $pendaftar->tempat_lahir,
                'Tanggal Lahir' => $pendaftar->tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d/m/Y') : '',
                'Alamat' => $pendaftar->alamat,
                'Nama Ayah' => $pendaftar->nama_ayah,
                'Nama Ibu' => $pendaftar->nama_ibu,
                'Pekerjaan Ayah' => $pendaftar->pekerjaan_ayah,
                'Pekerjaan Ibu' => $pendaftar->pekerjaan_ibu,
                'Penghasilan Orang Tua' => $pendaftar->penghasilan_orang_tua,
                'Jumlah Saudara' => $pendaftar->jumlah_saudara,
                'Anak Ke' => $pendaftar->anak_ke,
                'Prestasi Akademik' => $pendaftar->prestasi_akademik,
                'Prestasi Non Akademik' => $pendaftar->prestasi_non_akademik,
                'Keterangan Prestasi' => $pendaftar->keterangan_prestasi,
                'KIP/PKH' => $pendaftar->berkas_kip_pkh ? 'Ada' : 'Tidak Ada',
                'Bukti Domisili' => $pendaftar->berkas_bukti_domisili ? 'Ada' : 'Tidak Ada',
                'Surat Mutasi' => $pendaftar->berkas_surat_mutasi ? 'Ada' : 'Tidak Ada',
                'Surat Keterangan Lulus' => $pendaftar->berkas_skl ? 'Ada' : 'Tidak Ada',
                'Catatan Verifikasi' => $pendaftar->catatan_verifikasi
            ];
        });
    }

    private function getStatusLabel($status)
    {
        switch ($status) {
            case 'pending':
                return 'Menunggu Verifikasi';
            case 'verifikasi':
                return 'Dalam Verifikasi';
            case 'lulus':
                return 'Lulus';
            case 'tidak_lulus':
                return 'Tidak Lulus';
            default:
                return $status;
        }
    }
}
