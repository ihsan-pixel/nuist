<?php

namespace App\Exports;

use App\Models\Presensi;
use App\Models\User;
use App\Models\Madrasah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;

class PresensiMonthlyExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $year;
    protected $user;

    public function __construct($month, $year, $user)
    {
        $this->month = $month;
        $this->year = $year;
        $this->user = $user;
    }

    public function headings(): array
    {
        return [
            'Madrasah',
            'Nama Guru',
            'Status Kepegawaian',
            'NIP',
            'NUPTK',
            'Total Hari Kerja',
            'Hadir',
            'Terlambat',
            'Izin',
            'Tidak Hadir',
            'Persentase Kehadiran (%)'
        ];
    }

    public function collection()
    {
        $data = collect();

        // Get start and end of month
        $startDate = Carbon::createFromFormat('Y-m', $this->year . '-' . $this->month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $this->year . '-' . $this->month)->endOfMonth();

        // Get working days (exclude weekends and holidays)
        $workingDays = $this->getWorkingDays($startDate, $endDate);

        if (in_array($this->user->role, ['super_admin', 'pengurus'])) {
            // For super_admin and pengurus: all madrasah
            $madrasahs = Madrasah::all();
        } else {
            // For admin: only their madrasah
            $madrasahs = Madrasah::where('id', $this->user->madrasah_id)->get();
        }

        foreach ($madrasahs as $madrasah) {
            $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $madrasah->id)
                ->with('statusKepegawaian')
                ->get();

            foreach ($tenagaPendidik as $guru) {
                $presensiData = $this->getPresensiSummary($guru->id, $startDate, $endDate, $workingDays);

                // Only include if there is actual presensi data for this month
                if ($presensiData['total_hari_kerja'] > 0 && ($presensiData['hadir'] > 0 || $presensiData['terlambat'] > 0 || $presensiData['izin'] > 0 || $presensiData['tidak_hadir'] > 0)) {
                    $data->push([
                        'Madrasah' => $madrasah->name,
                        'Nama Guru' => $guru->name,
                        'Status Kepegawaian' => $guru->statusKepegawaian->name ?? '-',
                        'NIP' => $guru->nip,
                        'NUPTK' => $guru->nuptk,
                        'Total Hari Kerja' => $presensiData['total_hari_kerja'],
                        'Hadir' => $presensiData['hadir'],
                        'Terlambat' => $presensiData['terlambat'],
                        'Izin' => $presensiData['izin'],
                        'Tidak Hadir' => $presensiData['tidak_hadir'],
                        'Persentase Kehadiran (%)' => $presensiData['persentase_kehadiran']
                    ]);
                }
            }
        }

        return $data;
    }

    private function getWorkingDays($startDate, $endDate)
    {
        $workingDays = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Skip weekends (0 = Sunday, 6 = Saturday)
            if ($currentDate->dayOfWeek !== 0 && $currentDate->dayOfWeek !== 6) {
                // Check if it's a holiday
                $isHoliday = \App\Models\Holiday::whereDate('tanggal', $currentDate)->exists();
                if (!$isHoliday) {
                    $workingDays[] = $currentDate->toDateString();
                }
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    private function getPresensiSummary($userId, $startDate, $endDate, $workingDays)
    {
        $presensis = Presensi::where('user_id', $userId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return $item->tanggal->toDateString();
            });

        $hadir = 0;
        $terlambat = 0;
        $izin = 0;
        $tidak_hadir = 0;

        foreach ($workingDays as $date) {
            if (isset($presensis[$date])) {
                $presensi = $presensis[$date];
                switch ($presensi->status) {
                    case 'hadir':
                        $hadir++;
                        break;
                    case 'terlambat':
                        $terlambat++;
                        break;
                    case 'izin':
                        $izin++;
                        break;
                    default:
                        $tidak_hadir++;
                        break;
                }
            } else {
                $tidak_hadir++;
            }
        }

        $totalHariKerja = count($workingDays);
        $totalHadir = $hadir + $terlambat; // Hadir dan terlambat dihitung sebagai kehadiran
        $persentaseKehadiran = $totalHariKerja > 0 ? round(($totalHadir / $totalHariKerja) * 100, 2) : 0;

        return [
            'total_hari_kerja' => $totalHariKerja,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'tidak_hadir' => $tidak_hadir,
            'persentase_kehadiran' => $persentaseKehadiran
        ];
    }
}
