<?php

namespace App\Exports;

use App\Models\Madrasah;
use App\Models\Holiday;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PresensiSemuaExport implements FromCollection, WithHeadings, WithDrawings
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
            'Foto Masuk',
            'Foto Keluar',
        ];
    }

    public function collection()
    {
        $data = collect();

        // Get madrasah to determine hari_kbm
        $madrasah = Madrasah::find($this->madrasahId);
        $hariKbm = $madrasah ? $madrasah->hari_kbm : '5'; // Default to 5 if not set

        // Get all tenaga pendidik for this madrasah
        $tenagaPendidik = User::with('statusKepegawaian')
            ->where('madrasah_id', $this->madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->get();

        // Get all unique dates that have presensi for this madrasah, filtered by working days and excluding holidays
        $rawDates = Presensi::whereHas('user', function ($q) {
                $q->where('madrasah_id', $this->madrasahId)
                  ->where('role', 'tenaga_pendidik');
            })
            ->selectRaw('DATE(tanggal) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date');

        // Filter dates to only working days based on hari_kbm and exclude holidays
        $dates = [];
        foreach ($rawDates as $date) {
            $carbonDate = Carbon::parse($date);
            $dayOfWeek = $carbonDate->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday

            // Check if it's a working day based on hari_kbm
            $isWorkingDay = false;
            if ($hariKbm == '5') {
                // Monday to Friday (1-5)
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 5);
            } elseif ($hariKbm == '6') {
                // Monday to Saturday (1-6)
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 6);
            }

            // Exclude holidays
            if ($isWorkingDay && !Holiday::isHoliday($date)) {
                $dates[] = $date;
            }
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
                        'Foto Masuk' => $presensi->foto_masuk ? 'Foto Masuk' : '-',
                        'Foto Keluar' => $presensi->foto_keluar ? 'Foto Keluar' : '-',
                    ]);
                } else {
                    // User belum presensi
                    $data->push([
                        'Tanggal' => $date,
                        'Nama Guru' => $tp->name,
                        'Status Kepegawaian' => $tp->statusKepegawaian->name ?? '-',
                        'NIP' => $tp->nip,
                        'NUPTK' => $tp->nuptk,
                        'Status Presensi' => 'alpha/tidak presensi',
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

        // Get madrasah to determine hari_kbm
        $madrasah = Madrasah::find($this->madrasahId);
        $hariKbm = $madrasah ? $madrasah->hari_kbm : '5'; // Default to 5 if not set

        // Get all tenaga pendidik for this madrasah
        $tenagaPendidik = User::where('madrasah_id', $this->madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->get();

        // Get all unique dates that have presensi for this madrasah, filtered by working days and excluding holidays
        $rawDates = Presensi::whereHas('user', function ($q) {
                $q->where('madrasah_id', $this->madrasahId)
                  ->where('role', 'tenaga_pendidik');
            })
            ->selectRaw('DATE(tanggal) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date');

        // Filter dates to only working days based on hari_kbm and exclude holidays
        $dates = [];
        foreach ($rawDates as $date) {
            $carbonDate = Carbon::parse($date);
            $dayOfWeek = $carbonDate->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday

            // Check if it's a working day based on hari_kbm
            $isWorkingDay = false;
            if ($hariKbm == '5') {
                // Monday to Friday (1-5)
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 5);
            } elseif ($hariKbm == '6') {
                // Monday to Saturday (1-6)
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 6);
            }

            // Exclude holidays
            if ($isWorkingDay && !Holiday::isHoliday($date)) {
                $dates[] = $date;
            }
        }

        $rowIndex = 2; // Start after headers

        foreach ($dates as $date) {
            foreach ($tenagaPendidik as $tp) {
                $presensi = Presensi::where('user_id', $tp->id)
                    ->whereDate('tanggal', $date)
                    ->first();

                if ($presensi) {
                    // Add foto masuk if exists
                    if ($presensi->foto_masuk && file_exists(public_path('uploads/presensi/' . $presensi->foto_masuk))) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto Masuk ' . $rowIndex);
                        $drawing->setDescription('Foto Presensi Masuk');
                        $drawing->setPath(public_path('uploads/presensi/' . $presensi->foto_masuk));
                        $drawing->setHeight(50);
                        $drawing->setWidth(50);
                        $drawing->setCoordinates('K' . $rowIndex); // Column K for Foto Masuk
                        $drawings[] = $drawing;
                    }

                    // Add foto keluar if exists
                    if ($presensi->foto_keluar && file_exists(public_path('uploads/presensi/' . $presensi->foto_keluar))) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto Keluar ' . $rowIndex);
                        $drawing->setDescription('Foto Presensi Keluar');
                        $drawing->setPath(public_path('uploads/presensi/' . $presensi->foto_keluar));
                        $drawing->setHeight(50);
                        $drawing->setWidth(50);
                        $drawing->setCoordinates('L' . $rowIndex); // Column L for Foto Keluar
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
