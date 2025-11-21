<?php

namespace App\Exports;

use App\Models\Presensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiPerBulanExport implements FromCollection, WithHeadings
{
    protected $madrasahId;
    protected $bulan;

    public function __construct($madrasahId, $bulan)
    {
        $this->madrasahId = $madrasahId;
        $this->bulan = $bulan;
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

        // Parse bulan format (e.g., "January 2025" -> month and year)
        $bulanParts = explode(' ', $this->bulan);
        $monthName = $bulanParts[0] ?? 'January';
        $year = $bulanParts[1] ?? date('Y');

        // Convert month name to number
        $monthNum = date('m', strtotime($monthName . ' 1'));

        $presensis = Presensi::with(['user.statusKepegawaian'])
            ->whereHas('user', function ($q) {
                $q->where('madrasah_id', $this->madrasahId)
                  ->where('role', 'tenaga_pendidik');
            })
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $monthNum)
            ->orderBy('tanggal', 'desc')
            ->get();

        foreach ($presensis as $presensi) {
            $data->push([
                'Tanggal' => $presensi->tanggal->format('Y-m-d'),
                'Nama Guru' => $presensi->user->name,
                'Status Kepegawaian' => $presensi->user->statusKepegawaian->name ?? '-',
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
