<?php

namespace App\Exports;

use App\Models\Izin;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class MobileAttendanceRecapExport implements FromCollection
{
    public function __construct(
        protected User $user,
        protected string $type,
        protected Carbon $startDate,
        protected Carbon $endDate,
        protected array $summary
    ) {
    }

    public function collection(): Collection
    {
        $effectiveEndDate = $this->endDate->copy()->min(Carbon::today('Asia/Jakarta'));

        $presensiRecords = Presensi::with('madrasah')
            ->where('user_id', $this->user->id)
            ->whereBetween('tanggal', [$this->startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->get()
            ->map(function ($item) {
                return [
                    'Tanggal' => $item->tanggal->format('Y-m-d'),
                    'Hari' => ucfirst($item->tanggal->locale('id')->dayName),
                    'Jenis Data' => 'Presensi',
                    'Status' => ucfirst($item->status),
                    'Waktu Masuk' => optional($item->waktu_masuk)->format('H:i'),
                    'Waktu Keluar' => optional($item->waktu_keluar)->format('H:i'),
                    'Madrasah' => $item->madrasah->name ?? '-',
                    'Keterangan' => $item->keterangan ?? '-',
                ];
            });

        $izinRecords = Izin::query()
            ->where('user_id', $this->user->id)
            ->whereBetween('tanggal', [$this->startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->get()
            ->map(function ($item) {
                return [
                    'Tanggal' => $item->tanggal->format('Y-m-d'),
                    'Hari' => ucfirst($item->tanggal->locale('id')->dayName),
                    'Jenis Data' => 'Izin',
                    'Status' => ucfirst($item->status),
                    'Waktu Masuk' => optional($item->waktu_masuk)->format('H:i'),
                    'Waktu Keluar' => optional($item->waktu_keluar)->format('H:i'),
                    'Madrasah' => $this->user->madrasah->name ?? '-',
                    'Keterangan' => $item->deskripsi_tugas ?: ($item->alasan ?? '-'),
                ];
            });

        $records = $presensiRecords
            ->concat($izinRecords)
            ->sortBy([
                ['Tanggal', 'asc'],
                ['Jenis Data', 'asc'],
            ])
            ->values();

        $rows = collect([
            ['Rekap Presensi Kehadiran', ''],
            ['Nama', $this->user->name],
            ['Madrasah', $this->user->madrasah->name ?? '-'],
            ['Jenis Rekap', $this->thisTypeLabel()],
            ['Periode', $this->summary['periode_label'] ?? ($this->startDate->format('d M Y') . ' - ' . $effectiveEndDate->format('d M Y'))],
            ['Persentase Kehadiran', ($this->summary['persentase_kehadiran'] ?? 0) . '%'],
            ['Total Hari Kerja', $this->summary['total_hari_kerja'] ?? 0],
            ['Total Hadir', $this->summary['total_hadir'] ?? 0],
            ['Total Izin Disetujui', $this->summary['total_izin'] ?? 0],
            ['Total Belum Hadir', $this->summary['total_belum_hadir'] ?? 0],
            [],
            ['Tanggal', 'Hari', 'Jenis Data', 'Status', 'Waktu Masuk', 'Waktu Keluar', 'Madrasah', 'Keterangan'],
        ]);

        foreach ($records as $record) {
            $rows->push([
                $record['Tanggal'],
                $record['Hari'],
                $record['Jenis Data'],
                $record['Status'],
                $record['Waktu Masuk'],
                $record['Waktu Keluar'],
                $record['Madrasah'],
                $record['Keterangan'],
            ]);
        }

        return $rows;
    }

    private function thisTypeLabel(): string
    {
        return match ($this->type) {
            'weekly' => 'Mingguan',
            'all' => 'Keseluruhan',
            default => 'Bulanan',
        };
    }
}
