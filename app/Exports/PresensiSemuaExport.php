<?php

namespace App\Exports;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiSemuaExport implements FromCollection, WithHeadings
{
    protected $madrasahId;

    public function __construct($madrasahId)
    {
        $this->madrasahId = $madrasahId;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Guru',
            'Status Kepegawaian',
            'NIP',
            'NUPTK',
            'Status Presensi',
            'Waktu Masuk',
            'Waktu Keluar',
            'Keterangan',
            'Lokasi',
        ];
    }

    public function collection()
    {
        $data = collect();

        $presensis = Presensi::with(['user.statusKepegawaian'])
            ->whereHas('user', function ($q) {
                $q->where('madrasah_id', $this->madrasahId)
                  ->where('role', 'tenaga_pendidik');
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        foreach ($presensis as $presensi) {
            $data->push([
                'Tanggal' => $presensi->tanggal->format('Y-m-d'),
                'Nama Guru' => $presensi->user->name,
                'Status Kepegawaian' => $presensi->statusKepegawaian->name ?? '-',
                'NIP' => $presensi->user->nip,
                'NUPTK' => $presensi->user->nuptk,
                'Status Presensi' => $presensi->status,
                'Waktu Masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                'Waktu Keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                'Keterangan' => $presensi->keterangan,
                'Lokasi' => $presensi->lokasi,
            ]);
        }

        return $data;
    }
}
