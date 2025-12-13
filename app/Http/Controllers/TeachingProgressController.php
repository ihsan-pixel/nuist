<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\User;
use App\Models\TeachingSchedule;
use App\Models\TeachingAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeachingProgressController extends Controller
{
    /**
     * Display the teaching progress for all madrasahs
     */
    public function index(Request $request)
    {
        // Format input week: YYYY-Www (contoh: 2025-W49)
        $weekInput = trim($request->input('week', now()->format('Y-\WW')));

        // Pecah format: 2025-W49
        if (!preg_match('/^(\d{4})-W(\d{2})$/', $weekInput, $matches)) {
            abort(400, 'Format minggu tidak valid');
        }

        $year = (int) $matches[1];
        $week = (int) $matches[2];

        // ISO Week → AMAN, TANPA Trailing Data
        $startOfWeek = Carbon::now()
            ->setISODate($year, $week)
            ->startOfWeek(Carbon::MONDAY);

        $endOfWeek = $startOfWeek->copy()
            ->endOfWeek(Carbon::SATURDAY);

        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $laporanData = [];

        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            $kabupatenData = [
                'kabupaten' => $kabupaten,
                'madrasahs' => [],
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_alpha' => 0,
                'total_presensi' => 0,
                'persentase_kehadiran' => 0
            ];

            foreach ($madrasahs as $madrasah) {
                // Get all teachers for this madrasah excluding status_kepegawaian_id 7 and 8
                $teachers = User::where('madrasah_id', $madrasah->id)
                    ->where('role', 'tenaga_pendidik')
                    ->whereNotIn('status_kepegawaian_id', [7, 8])
                    ->get();

                $totalTeachers = $teachers->count();

                if ($totalTeachers == 0) {
                    $kabupatenData['madrasahs'][] = [
                        'scod' => $madrasah->scod,
                        'nama' => $madrasah->name,
                        'hari_kbm' => $madrasah->hari_kbm,
                        'presensi' => array_fill(0, 6, ['hadir' => 0, 'izin' => 0, 'alpha' => $totalTeachers]),
                        'persentase_kehadiran' => 0
                    ];
                    continue;
                }

                $presensiMingguan = [];
                $totalHadir = 0;
                $totalPresensi = 0;

                $currentDate = $startOfWeek->copy();
                $daysToCount = $madrasah->hari_kbm == 5 ? 5 : 6; // Jika 5 hari kerja, jangan hitung Sabtu

                for ($i = 0; $i < $daysToCount; $i++) {
                    $hadir = 0;
                    $izin = 0;
                    $alpha = 0;

                    foreach ($teachers as $guru) {
                        $attendance = TeachingAttendance::whereHas('teachingSchedule', function ($q) use ($guru) {
                            $q->where('teacher_id', $guru->id);
                        })
                        ->whereDate('tanggal', $currentDate)
                        ->first();

                        if ($attendance) {
                            if ($attendance->status === 'hadir') $hadir++;
                            elseif ($attendance->status === 'izin') $izin++;
                            else $alpha++;
                        } else {
                            $alpha++;
                        }
                    }

                    $presensiMingguan[] = compact('hadir', 'izin', 'alpha');

                    $totalHadir += $hadir;
                    $totalPresensi += ($hadir + $izin + $alpha);

                    $currentDate->addDay();
                }

                // Jika 5 hari kerja, tambahkan data kosong untuk Sabtu agar tetap 6 kolom
                if ($madrasah->hari_kbm == 5) {
                    $presensiMingguan[] = ['hadir' => '-', 'izin' => '-', 'alpha' => '-'];
                }

                $persentase = $totalPresensi > 0
                    ? ($totalHadir / $totalPresensi) * 100
                    : 0;

                $kabupatenData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'presensi' => $presensiMingguan,
                    'persentase_kehadiran' => $persentase
                ];

                $totalIzin = $totalPresensi - $totalHadir - ($totalTeachers * $daysToCount - $totalPresensi); // Izin = total presensi - hadir - alpha
                $totalAlpha = $totalTeachers * $daysToCount - $totalPresensi; // Alpha = total possible - actual presensi

                $kabupatenData['total_hadir'] += $totalHadir;
                $kabupatenData['total_izin'] += $totalIzin;
                $kabupatenData['total_alpha'] += $totalAlpha;
                $kabupatenData['total_presensi'] += $totalPresensi;
            }

            $kabupatenData['persentase_kehadiran'] =
                $kabupatenData['total_presensi'] > 0
                    ? ($kabupatenData['total_hadir'] / $kabupatenData['total_presensi']) * 100
                    : 0;

            $laporanData[] = $kabupatenData;
        }

        return view('admin.teaching_progress', compact('laporanData', 'startOfWeek'));
    }

    /**
     * Get teacher detail data for a madrasah.
     * Includes users with status_kepegawaian 7 and 8 only if they have presensi.
     */
    public function getMadrasahTeachers(Request $request, $madrasahId)
    {
        // Format input week: YYYY-Www (contoh: 2025-W49)
        $weekInput = trim($request->input('week', now()->format('Y-\WW')));

        // Pecah format: 2025-W49
        if (!preg_match('/^(\d{4})-W(\d{2})$/', $weekInput, $matches)) {
            abort(400, 'Format minggu tidak valid');
        }

        $year = (int) $matches[1];
        $week = (int) $matches[2];

        // ISO Week → AMAN, TANPA Trailing Data
        $startOfWeek = Carbon::now()
            ->setISODate($year, $week)
            ->startOfWeek(Carbon::MONDAY);

        $endOfWeek = $startOfWeek->copy()
            ->endOfWeek(Carbon::SATURDAY);

        // Get all teachers
        $allTeachers = User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->get();

        $teachersFiltered = $allTeachers->filter(function ($teacher) use ($startOfWeek, $endOfWeek) {
            if (in_array($teacher->status_kepegawaian_id, [7, 8])) {
                $hasPresensi = TeachingAttendance::whereHas('teachingSchedule', function ($q) use ($teacher) {
                        $q->where('teacher_id', $teacher->id);
                    })
                    ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                    ->exists();
                return $hasPresensi;
            }
            return true;
        });

        $teachersWithPresensi = $teachersFiltered->map(function ($teacher) use ($startOfWeek, $endOfWeek) {
            $hasPresensi = TeachingAttendance::whereHas('teachingSchedule', function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                ->exists();

            return [
                'name' => $teacher->name,
                'status_kepegawaian' => $teacher->statusKepegawaian->name ?? '-',
                'presensi_status' => $hasPresensi ? 'Sudah Presensi' : 'Belum Presensi',
            ];
        });

        return response()->json([
            'teachers' => $teachersWithPresensi->values()->all()
        ]);
    }
}
