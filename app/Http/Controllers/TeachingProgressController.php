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
        $month = $request->input('month', now()->format('Y-m'));
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);
        $startOfMonth = Carbon::create($year, $monthNum, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $effectiveEndOfMonth = $startOfMonth->isSameMonth(now())
            ? now()->copy()->endOfDay()
            : $endOfMonth->copy()->endOfDay();

        $madrasahPercentages = [];
        $allMadrasahs = Madrasah::orderByRaw("CAST(scod AS UNSIGNED) ASC")->get();

        foreach ($allMadrasahs as $madrasah) {
            $teachers = $this->getEligibleTeachers($madrasah->id, false);

            $totalTeachers = $teachers->count();

            if ($totalTeachers == 0) {
                $madrasahPercentages[] = [
                    'nama' => $madrasah->name,
                    'persentase' => 0
                ];
                continue;
            }

            $totalHadir = 0;
            $totalPresensi = 0;
            $currentDate = $startOfMonth->copy();

            while ($currentDate <= $effectiveEndOfMonth) {
                $dayOfWeek = $currentDate->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday
                $isWorkingDay = ($madrasah->hari_kbm == 5) ? ($dayOfWeek >= 1 && $dayOfWeek <= 5) : ($dayOfWeek >= 1 && $dayOfWeek <= 6);

                if ($isWorkingDay) {
                    $hadir = 0;

                    foreach ($teachers as $guru) {
                        if ($this->hasTeachingAttendance($guru->id, $currentDate)) {
                            $hadir++;
                        }
                    }

                    $totalHadir += $hadir;
                    $totalPresensi += $totalTeachers;
                }

                $currentDate->addDay();
            }

            $persentase = $totalPresensi > 0 ? ($totalHadir / $totalPresensi) * 100 : 0;

            $madrasahPercentages[] = [
                'nama' => $madrasah->name,
                'persentase' => $persentase
            ];
        }

        $top10Madrasah = collect($madrasahPercentages)->sortByDesc('persentase')->take(10)->values()->all();

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
            'Kabupaten Bantul',
            'Kabupaten Gunungkidul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $laporanData = [];
        $laporanBulananData = [];

        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            $kabupatenData = [
                'kabupaten' => $kabupaten,
                'madrasahs' => [],
                'total_hadir' => 0,
                'total_alpha' => 0,
                'total_presensi' => 0,
                'persentase_kehadiran' => 0
            ];

            foreach ($madrasahs as $madrasah) {
                $teachers = $this->getEligibleTeachers($madrasah->id);

                $totalTeachers = $teachers->count();

                $teachersWithSchedule = $teachers->filter(function ($teacher) {
                    return TeachingSchedule::where('teacher_id', $teacher->id)->exists();
                })->count();
                $teachersWithoutSchedule = $totalTeachers - $teachersWithSchedule;

                if ($totalTeachers == 0) {
                    $kabupatenData['madrasahs'][] = [
                        'scod' => $madrasah->scod,
                        'nama' => $madrasah->name,
                        'hari_kbm' => $madrasah->hari_kbm,
                        'sudah' => 0,
                        'belum' => 0,
                        'total' => 0,
                        'presensi' => array_fill(0, 6, ['hadir' => 0, 'alpha' => 0]),
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
                    $alpha = 0;

                    foreach ($teachers as $guru) {
                        if ($this->hasTeachingAttendance($guru->id, $currentDate)) {
                            $hadir++;
                        } else {
                            $alpha++;
                        }
                    }

                    $presensiMingguan[] = compact('hadir', 'alpha');

                    $totalHadir += $hadir;
                    $totalPresensi += ($hadir + $alpha);

                    $currentDate->addDay();
                }

                // Jika 5 hari kerja, tambahkan data kosong untuk Sabtu agar tetap 6 kolom
                if ($madrasah->hari_kbm == 5) {
                    $presensiMingguan[] = ['hadir' => '-', 'alpha' => '-'];
                }

                $persentase = $totalPresensi > 0
                    ? ($totalHadir / $totalPresensi) * 100
                    : 0;

                $kabupatenData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'sudah' => $teachersWithSchedule,
                    'belum' => $teachersWithoutSchedule,
                    'total' => $totalTeachers,
                    'presensi' => $presensiMingguan,
                    'persentase_kehadiran' => $persentase
                ];

                $totalAlpha = collect($presensiMingguan)->sum(function ($item) {
                    return is_numeric($item['alpha']) ? $item['alpha'] : 0;
                });

                $kabupatenData['total_hadir'] += $totalHadir;
                $kabupatenData['total_alpha'] += $totalAlpha;
                $kabupatenData['total_presensi'] += $totalPresensi;
            }

            $kabupatenData['persentase_kehadiran'] =
                $kabupatenData['total_presensi'] > 0
                    ? ($kabupatenData['total_hadir'] / $kabupatenData['total_presensi']) * 100
                    : 0;

            $laporanData[] = $kabupatenData;
        }

        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            $kabupatenBulananData = [
                'kabupaten' => $kabupaten,
                'madrasahs' => [],
                'total_hadir' => 0,
                'total_alpha' => 0,
                'total_presensi' => 0,
                'persentase_kehadiran' => 0
            ];

            foreach ($madrasahs as $madrasah) {
                $teachers = $this->getEligibleTeachers($madrasah->id);
                $totalTeachers = $teachers->count();
                $teachersWithSchedule = $teachers->filter(function ($teacher) {
                    return TeachingSchedule::where('teacher_id', $teacher->id)->exists();
                })->count();
                $teachersWithoutSchedule = $totalTeachers - $teachersWithSchedule;

                if ($totalTeachers == 0) {
                    $kabupatenBulananData['madrasahs'][] = [
                        'scod' => $madrasah->scod,
                        'nama' => $madrasah->name,
                        'hari_kbm' => $madrasah->hari_kbm,
                        'sudah' => 0,
                        'belum' => 0,
                        'total' => 0,
                        'total_hadir' => 0,
                        'total_alpha' => 0,
                        'persentase_kehadiran' => 0
                    ];
                    continue;
                }

                $totalHadirBulanan = 0;
                $totalAlphaBulanan = 0;
                $totalPresensiBulanan = 0;
                $currentDate = $startOfMonth->copy();

                while ($currentDate <= $effectiveEndOfMonth) {
                    $dayOfWeek = $currentDate->dayOfWeek;
                    $isWorkingDay = $madrasah->hari_kbm == 5
                        ? ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::FRIDAY)
                        : ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::SATURDAY);

                    if ($isWorkingDay) {
                        $hadir = 0;

                        foreach ($teachers as $guru) {
                            if ($this->hasTeachingAttendance($guru->id, $currentDate)) {
                                $hadir++;
                            }
                        }

                        $alpha = $totalTeachers - $hadir;

                        $totalHadirBulanan += $hadir;
                        $totalAlphaBulanan += $alpha;
                        $totalPresensiBulanan += $totalTeachers;
                    }

                    $currentDate->addDay();
                }

                $persentaseBulanan = $totalPresensiBulanan > 0
                    ? ($totalHadirBulanan / $totalPresensiBulanan) * 100
                    : 0;

                $kabupatenBulananData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'sudah' => $teachersWithSchedule,
                    'belum' => $teachersWithoutSchedule,
                    'total' => $totalTeachers,
                    'total_hadir' => $totalHadirBulanan,
                    'total_alpha' => $totalAlphaBulanan,
                    'persentase_kehadiran' => $persentaseBulanan
                ];

                $kabupatenBulananData['total_hadir'] += $totalHadirBulanan;
                $kabupatenBulananData['total_alpha'] += $totalAlphaBulanan;
                $kabupatenBulananData['total_presensi'] += $totalPresensiBulanan;
            }

            $kabupatenBulananData['persentase_kehadiran'] =
                $kabupatenBulananData['total_presensi'] > 0
                    ? ($kabupatenBulananData['total_hadir'] / $kabupatenBulananData['total_presensi']) * 100
                    : 0;

            $laporanBulananData[] = $kabupatenBulananData;
        }

        return view('admin.teaching_progress', compact(
            'laporanData',
            'laporanBulananData',
            'startOfWeek',
            'startOfMonth',
            'month',
            'top10Madrasah'
        ));
    }

    private function getEligibleTeachers($madrasahId, $excludePrincipal = true)
    {
        $query = User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->whereNotIn('status_kepegawaian_id', [7, 8]);

        if ($excludePrincipal) {
            $query->where('ketugasan', '!=', 'kepala madrasah/sekolah');
        }

        return $query->get();
    }

    private function hasTeachingAttendance($teacherId, Carbon $date)
    {
        return TeachingAttendance::whereHas('teachingSchedule', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })
            ->whereDate('tanggal', $date)
            ->where('status', 'hadir')
            ->exists();
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
