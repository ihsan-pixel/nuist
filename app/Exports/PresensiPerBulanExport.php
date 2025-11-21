<?php

namespace App\Exports;

use App\Models\Presensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PresensiPerBulanExport implements FromCollection, WithHeadings, WithDrawings
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
            'Foto Masuk',
            'Foto Keluar',
        ];
    }

    public function collection()
    {
        $data = collect();

        // Jika format bulan adalah "YYYY-MM"
        if (preg_match('/^\d{4}-\d{2}$/', $this->bulan)) {
            list($year, $monthNum) = explode('-', $this->bulan);
        }
        // Jika format bulan hanya "MM"
        elseif (preg_match('/^\d{1,2}$/', $this->bulan)) {
            $year = date('Y');
            $monthNum = $this->bulan;
        }
        // Jika format bulan seperti "November 2025"
        else {
            $bulanParts = explode(' ', $this->bulan);
            $monthName = $bulanParts[0];
            $year = $bulanParts[1] ?? date('Y');
            $monthNum = date('m', strtotime("$monthName 1"));
        }

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

    public function drawings()
    {
        $drawings = [];

        // Parse bulan format (e.g., "November 2025" -> month and year)
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

        foreach ($presensis as $index => $presensi) {
            $row = $index + 2; // +2 because Excel starts at 1 and we have headers

            // Add foto masuk if exists
            if ($presensi->foto_masuk && file_exists(public_path('uploads/presensi/' . $presensi->foto_masuk))) {
                $drawing = new Drawing();
                $drawing->setName('Foto Masuk ' . ($index + 1));
                $drawing->setDescription('Foto Presensi Masuk');
                $drawing->setPath(public_path('uploads/presensi/' . $presensi->foto_masuk));
                $drawing->setHeight(50);
                $drawing->setWidth(50);
                $drawing->setCoordinates('K' . $row); // Column K for Foto Masuk
                $drawings[] = $drawing;
            }

            // Add foto keluar if exists
            if ($presensi->foto_keluar && file_exists(public_path('uploads/presensi/' . $presensi->foto_keluar))) {
                $drawing = new Drawing();
                $drawing->setName('Foto Keluar ' . ($index + 1));
                $drawing->setDescription('Foto Presensi Keluar');
                $drawing->setPath(public_path('uploads/presensi/' . $presensi->foto_keluar));
                $drawing->setHeight(50);
                $drawing->setWidth(50);
                $drawing->setCoordinates('L' . $row); // Column L for Foto Keluar
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }
}
