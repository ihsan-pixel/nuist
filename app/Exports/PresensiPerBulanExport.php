<?php

namespace App\Exports;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
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

        // Parse bulan input
        if (preg_match('/^\d{4}-\d{2}$/', $this->bulan)) {
            list($year, $monthNum) = explode('-', $this->bulan);
        }
        elseif (preg_match('/^\d{1,2}$/', $this->bulan)) {
            $year = date('Y');
            $monthNum = $this->bulan;
        }
        else {
            $bulanParts = explode(' ', $this->bulan);
            $monthName = $bulanParts[0];
            $year = $bulanParts[1] ?? date('Y');
            $monthNum = date('m', strtotime("$monthName 1"));
        }

        // Get all tenaga pendidik for this madrasah
        $tenagaPendidik = User::with('statusKepegawaian')
            ->where('madrasah_id', $this->madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->get();

        // Generate all dates in the month
        $startDate = Carbon::createFromDate($year, $monthNum, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        foreach ($dates as $date) {
            foreach ($tenagaPendidik as $tp) {
                $presensi = Presensi::where('user_id', $tp->id)
                    ->whereDate('tanggal', $date)
                    ->first();

                if ($presensi) {
                    // User has presensi
                    $data->push([
                        'Tanggal' => $date,
                        'Nama Guru' => $tp->name,
                        'Status Kepegawaian' => $tp->statusKepegawaian->name ?? '-',
                        'NIP' => $tp->nip,
                        'NUPTK' => $tp->nuptk,
                        'Status Presensi' => $presensi->status,
                        'Waktu Masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                        'Waktu Keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                        'Keterangan' => $presensi->keterangan,
                        'Lokasi' => $presensi->lokasi,
                        'Foto Masuk' => $presensi->selfie_masuk_path ? 'Foto Masuk' : '-',
                        'Foto Keluar' => $presensi->selfie_keluar_path ? 'Foto Keluar' : '-',
                    ]);
                } else {
                    // User belum presensi
                    $data->push([
                        'Tanggal' => $date,
                        'Nama Guru' => $tp->name,
                        'Status Kepegawaian' => $tp->statusKepegawaian->name ?? '-',
                        'NIP' => $tp->nip,
                        'NUPTK' => $tp->nuptk,
                        'Status Presensi' => 'belum presensi',
                        'Waktu Masuk' => null,
                        'Waktu Keluar' => null,
                        'Keterangan' => null,
                        'Lokasi' => null,
                        'Foto Masuk' => '-',
                        'Foto Keluar' => '-',
                    ]);
                }
            }
        }

        return $data;
    }

    public function drawings()
    {
        $drawings = [];

        // Parse bulan input
        if (preg_match('/^\d{4}-\d{2}$/', $this->bulan)) {
            list($year, $monthNum) = explode('-', $this->bulan);
        }
        elseif (preg_match('/^\d{1,2}$/', $this->bulan)) {
            $year = date('Y');
            $monthNum = $this->bulan;
        }
        else {
            $bulanParts = explode(' ', $this->bulan);
            $monthName = $bulanParts[0];
            $year = $bulanParts[1] ?? date('Y');
            $monthNum = date('m', strtotime("$monthName 1"));
        }

        // Get all tenaga pendidik for this madrasah
        $tenagaPendidik = User::where('madrasah_id', $this->madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->get();

        // Generate all dates in the month
        $startDate = Carbon::createFromDate($year, $monthNum, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $rowIndex = 2; // Start after headers

        foreach ($dates as $date) {
            foreach ($tenagaPendidik as $tp) {
                $presensi = Presensi::where('user_id', $tp->id)
                    ->whereDate('tanggal', $date)
                    ->first();

                if ($presensi) {
                    // FOTO MASUK
                    $fotoMasukPath = public_path('storage/' . $presensi->selfie_masuk_path);
                    if ($presensi->selfie_masuk_path && file_exists($fotoMasukPath)) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto Masuk ' . $rowIndex);
                        $drawing->setDescription('Foto Presensi Masuk');
                        $drawing->setPath($fotoMasukPath);
                        $drawing->setHeight(50);
                        $drawing->setCoordinates('K' . $rowIndex);
                        $drawings[] = $drawing;
                    }

                    // FOTO KELUAR
                    $fotoKeluarPath = public_path('storage/' . $presensi->selfie_keluar_path);
                    if ($presensi->selfie_keluar_path && file_exists($fotoKeluarPath)) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto Keluar ' . $rowIndex);
                        $drawing->setDescription('Foto Presensi Keluar');
                        $drawing->setPath($fotoKeluarPath);
                        $drawing->setHeight(50);
                        $drawing->setCoordinates('L' . $rowIndex);
                        $drawings[] = $drawing;
                    }
                }
                // If no presensi, no drawings to add

                $rowIndex++;
            }
        }

        return $drawings;
    }
}
