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
            'Hari',
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

                $hari = Carbon::parse($date)->locale('id')->dayName;

                if ($presensi) {
                    // User has presensi
                    $data->push([
                        'Tanggal' => $date,
                        'Hari' => $hari,
                        'Nama Guru' => $tp->name,
                        'Status Kepegawaian' => $tp->statusKepegawaian->name ?? '-',
                        'NIP' => $tp->nip,
                        'NUPTK' => $tp->nuptk,
                        'Status Presensi' => $presensi->status,
                        'Waktu Masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                        'Waktu Keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                        'Keterangan' => $presensi->keterangan,
                        'Lokasi' => $presensi->lokasi,
                    ]);
                } else {
                    // User belum presensi
                    $data->push([
                        'Tanggal' => $date,
                        'Hari' => $hari,
                        'Nama Guru' => $tp->name,
                        'Status Kepegawaian' => $tp->statusKepegawaian->name ?? '-',
                        'NIP' => $tp->nip,
                        'NUPTK' => $tp->nuptk,
                        'Status Presensi' => 'alpha/tidak presensi',
                        'Waktu Masuk' => null,
                        'Waktu Keluar' => null,
                        'Keterangan' => null,
                        'Lokasi' => null,
                    ]);
                }
            }
        }

        return $data;
    }


}
