<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\User;
use App\Models\TeachingSchedule;
use App\Models\TeachingAttendance;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TeachingProgressController extends Controller
{
    private const TEACHING_DAY_NAMES = [
        Carbon::MONDAY => 'Senin',
        Carbon::TUESDAY => 'Selasa',
        Carbon::WEDNESDAY => 'Rabu',
        Carbon::THURSDAY => 'Kamis',
        Carbon::FRIDAY => 'Jumat',
        Carbon::SATURDAY => 'Sabtu',
    ];

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

            $teacherDailyStatusKeys = $this->getTeacherDailyStatusKeys(
                $teachers->pluck('id'),
                $startOfMonth,
                $effectiveEndOfMonth
            );
            $totalHadir = 0;
            $totalPresensi = 0;
            $currentDate = $startOfMonth->copy();

            while ($currentDate <= $effectiveEndOfMonth) {
                $dayOfWeek = $currentDate->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday
                $isWorkingDay = ($madrasah->hari_kbm == 5) ? ($dayOfWeek >= 1 && $dayOfWeek <= 5) : ($dayOfWeek >= 1 && $dayOfWeek <= 6);

                if ($isWorkingDay) {
                    foreach ($teachers as $guru) {
                        $teachingStatus = $this->resolveTeacherDailyTeachingStatus(
                            $guru->id,
                            $currentDate,
                            $teacherDailyStatusKeys['attendance'],
                            $teacherDailyStatusKeys['izin']
                        );

                        if (in_array($teachingStatus, ['hadir', 'izin'], true)) {
                            $totalHadir++;
                        }
                        $totalPresensi++;
                    }
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
        $teachingRecapData = $this->getTeachingRecapData($request);

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
                'persentase_kehadiran' => 0,
                'daily_totals' => array_fill(0, 6, ['hadir' => 0, 'izin' => 0, 'alpha' => 0]),
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
                        'presensi' => array_fill(0, 6, ['hadir' => 0, 'izin' => 0, 'alpha' => 0]),
                        'persentase_kehadiran' => 0
                    ];
                    continue;
                }

                $teacherDailyStatusKeys = $this->getTeacherDailyStatusKeys(
                    $teachers->pluck('id'),
                    $startOfWeek,
                    $endOfWeek
                );
                $presensiMingguan = [];
                $totalHadir = 0;
                $totalIzin = 0;
                $totalPresensi = 0;

                $currentDate = $startOfWeek->copy();
                $daysToCount = $madrasah->hari_kbm == 5 ? 5 : 6; // Jika 5 hari kerja, jangan hitung Sabtu

                for ($i = 0; $i < $daysToCount; $i++) {
                    $hadir = 0;
                    $izin = 0;
                    $alpha = 0;

                    foreach ($teachers as $guru) {
                        $teachingStatus = $this->resolveTeacherDailyTeachingStatus(
                            $guru->id,
                            $currentDate,
                            $teacherDailyStatusKeys['attendance'],
                            $teacherDailyStatusKeys['izin']
                        );

                        if ($teachingStatus === 'hadir') {
                            $hadir++;
                        } elseif ($teachingStatus === 'izin') {
                            $izin++;
                        } else {
                            $alpha++;
                        }
                    }

                    $presensiMingguan[] = compact('hadir', 'izin', 'alpha');
                    $kabupatenData['daily_totals'][$i]['hadir'] += $hadir;
                    $kabupatenData['daily_totals'][$i]['izin'] += $izin;
                    $kabupatenData['daily_totals'][$i]['alpha'] += $alpha;

                    $totalHadir += $hadir;
                    $totalIzin += $izin;
                    $totalPresensi += ($hadir + $izin + $alpha);

                    $currentDate->addDay();
                }

                // Jika 5 hari kerja, tambahkan data kosong untuk Sabtu agar tetap 6 kolom
                if ($madrasah->hari_kbm == 5) {
                    $presensiMingguan[] = ['hadir' => '-', 'izin' => '-', 'alpha' => '-'];
                }

                $persentase = $totalPresensi > 0
                    ? (($totalHadir + $totalIzin) / $totalPresensi) * 100
                    : 0;

                $kabupatenData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'sudah' => $teachersWithSchedule,
                    'belum' => $teachersWithoutSchedule,
                    'total' => $totalTeachers,
                    'presensi' => $presensiMingguan,
                    'total_hadir' => $totalHadir,
                    'total_izin' => $totalIzin,
                    'total_alpha' => $totalPresensi - $totalHadir - $totalIzin,
                    'persentase_kehadiran' => $persentase
                ];

                $totalAlpha = collect($presensiMingguan)->sum(function ($item) {
                    return is_numeric($item['alpha']) ? $item['alpha'] : 0;
                });

                $kabupatenData['total_hadir'] += $totalHadir;
                $kabupatenData['total_izin'] += $totalIzin;
                $kabupatenData['total_alpha'] += $totalAlpha;
                $kabupatenData['total_presensi'] += $totalPresensi;
            }

            $kabupatenData['persentase_kehadiran'] =
                $kabupatenData['total_presensi'] > 0
                    ? (($kabupatenData['total_hadir'] + $kabupatenData['total_izin']) / $kabupatenData['total_presensi']) * 100
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
                'total_izin' => 0,
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
                        'total_izin' => 0,
                        'total_alpha' => 0,
                        'persentase_kehadiran' => 0
                    ];
                    continue;
                }

                $teacherDailyStatusKeys = $this->getTeacherDailyStatusKeys(
                    $teachers->pluck('id'),
                    $startOfMonth,
                    $effectiveEndOfMonth
                );
                $totalHadirBulanan = 0;
                $totalIzinBulanan = 0;
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
                        $izin = 0;

                        foreach ($teachers as $guru) {
                            $teachingStatus = $this->resolveTeacherDailyTeachingStatus(
                                $guru->id,
                                $currentDate,
                                $teacherDailyStatusKeys['attendance'],
                                $teacherDailyStatusKeys['izin']
                            );

                            if ($teachingStatus === 'hadir') {
                                $hadir++;
                            } elseif ($teachingStatus === 'izin') {
                                $izin++;
                            }
                        }

                        $alpha = $totalTeachers - $hadir - $izin;

                        $totalHadirBulanan += $hadir;
                        $totalIzinBulanan += $izin;
                        $totalAlphaBulanan += $alpha;
                        $totalPresensiBulanan += $totalTeachers;
                    }

                    $currentDate->addDay();
                }

                $persentaseBulanan = $totalPresensiBulanan > 0
                    ? (($totalHadirBulanan + $totalIzinBulanan) / $totalPresensiBulanan) * 100
                    : 0;

                $kabupatenBulananData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'sudah' => $teachersWithSchedule,
                    'belum' => $teachersWithoutSchedule,
                    'total' => $totalTeachers,
                    'total_hadir' => $totalHadirBulanan,
                    'total_izin' => $totalIzinBulanan,
                    'total_alpha' => $totalAlphaBulanan,
                    'persentase_kehadiran' => $persentaseBulanan
                ];

                $kabupatenBulananData['total_hadir'] += $totalHadirBulanan;
                $kabupatenBulananData['total_izin'] += $totalIzinBulanan;
                $kabupatenBulananData['total_alpha'] += $totalAlphaBulanan;
                $kabupatenBulananData['total_presensi'] += $totalPresensiBulanan;
            }

            $kabupatenBulananData['persentase_kehadiran'] =
                $kabupatenBulananData['total_presensi'] > 0
                    ? (($kabupatenBulananData['total_hadir'] + $kabupatenBulananData['total_izin']) / $kabupatenBulananData['total_presensi']) * 100
                    : 0;

            $laporanBulananData[] = $kabupatenBulananData;
        }

        return view('admin.teaching_progress', compact(
            'laporanData',
            'laporanBulananData',
            'startOfWeek',
            'startOfMonth',
            'month',
            'top10Madrasah',
            'teachingRecapData'
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

    private function getTeachingRecapData(Request $request): array
    {
        $today = Carbon::today('Asia/Jakarta');
        $period = $request->input('teaching_recap_period') === 'month' ? 'month' : 'week';

        $selectedWeekValue = $request->input('teaching_recap_week', $today->format('o-\WW'));
        if (preg_match('/^(\d{4})-W(\d{2})$/', $selectedWeekValue, $matches)) {
            $startOfWeek = Carbon::now('Asia/Jakarta')
                ->setISODate((int) $matches[1], (int) $matches[2])
                ->startOfWeek(Carbon::MONDAY);
        } else {
            $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
            $selectedWeekValue = $startOfWeek->format('o-\WW');
        }

        $selectedMonthValue = $request->input('teaching_recap_month', $today->format('Y-m'));
        if (preg_match('/^\d{4}-\d{2}$/', $selectedMonthValue)) {
            $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonthValue, 'Asia/Jakarta')->startOfMonth();
        } else {
            $startOfMonth = $today->copy()->startOfMonth();
            $selectedMonthValue = $startOfMonth->format('Y-m');
        }

        $startDate = $period === 'month'
            ? $startOfMonth->copy()
            : $startOfWeek->copy();
        $endDate = $period === 'month'
            ? $startOfMonth->copy()->endOfMonth()
            : $startOfWeek->copy()->endOfWeek(Carbon::SATURDAY);
        $effectiveEndDate = $endDate->copy()->min($today);

        $teachers = $this->getTeachingRecapEligibleTeachers();
        $teacherIds = $teachers->pluck('id');
        $schedules = TeachingSchedule::query()
            ->whereIn('teacher_id', $teacherIds)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $schedulesByTeacher = $schedules->groupBy('teacher_id');
        $attendanceKeys = collect();

        if ($schedules->isNotEmpty() && !$effectiveEndDate->lt($startDate)) {
            $attendanceKeys = TeachingAttendance::query()
                ->whereIn('teaching_schedule_id', $schedules->pluck('id'))
                ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
                ->where('status', 'hadir')
                ->get()
                ->mapWithKeys(function ($attendance) {
                    $date = Carbon::parse($attendance->tanggal)->toDateString();
                    return [$attendance->teaching_schedule_id . '|' . $date => true];
                });
        }

        $approvedIzinKeys = $this->getApprovedIzinKeys($teacherIds, $startDate, $effectiveEndDate);

        $recapRows = collect();

        foreach ($teachers as $teacher) {
            $teacherSchedules = $schedulesByTeacher->get($teacher->id, collect());
            $scheduleSummary = $this->summarizeTeachingSchedules(
                $teacherSchedules,
                $startDate,
                $endDate,
                $effectiveEndDate,
                $attendanceKeys,
                $approvedIzinKeys
            );

            $hasSchedule = $teacherSchedules->count() > 0;
            $hasMissingAttendance = $scheduleSummary['total_belum_presensi'] > 0;
            $attendanceStatus = 'Tidak ada jadwal berjalan';

            if (!$hasSchedule) {
                $attendanceStatus = 'Belum memiliki jadwal';
            } elseif ($hasMissingAttendance) {
                $attendanceStatus = 'Belum presensi mengajar';
            } elseif ($scheduleSummary['total_jadwal_berjalan'] > 0 && $scheduleSummary['total_izin'] > 0) {
                $attendanceStatus = 'Presensi / izin lengkap';
            } elseif ($scheduleSummary['total_jadwal_berjalan'] > 0) {
                $attendanceStatus = 'Sudah presensi mengajar';
            }

            $recapRows->push([
                'scod' => $teacher->madrasah->scod ?? '-',
                'name' => $teacher->name,
                'madrasah' => $teacher->madrasah->name ?? '-',
                'status_kepegawaian' => $teacher->statusKepegawaian->name ?? '-',
                'jumlah_jadwal_master' => $teacherSchedules->count(),
                'total_jadwal_periode' => $scheduleSummary['total_jadwal_periode'],
                'status_jadwal' => $hasSchedule ? 'Sudah memiliki jadwal' : 'Belum memiliki jadwal',
                'total_jadwal_berjalan' => $scheduleSummary['total_jadwal_berjalan'],
                'total_presensi' => $scheduleSummary['total_presensi'],
                'total_izin' => $scheduleSummary['total_izin'],
                'total_belum_presensi' => $scheduleSummary['total_belum_presensi'],
                'persentase_tidak_presensi' => $scheduleSummary['persentase_tidak_presensi'],
                'status_presensi' => $attendanceStatus,
                'rincian_tanggal' => $scheduleSummary['rincian_tanggal'],
            ]);
        }

        $sortedRows = $recapRows
            ->sortBy([
                ['total_belum_presensi', 'desc'],
                ['scod', 'asc'],
                ['name', 'asc'],
            ])
            ->values();

        return [
            'period' => $period,
            'week_value' => $selectedWeekValue,
            'month_value' => $selectedMonthValue,
            'label' => $startDate->locale('id')->translatedFormat('d F Y') . ' - ' .
                $endDate->locale('id')->translatedFormat('d F Y'),
            'rows' => $sortedRows,
            'summary' => [
                'total_tenaga_pendidik' => $teachers->count(),
                'total_tidak_presensi' => $recapRows->where('total_belum_presensi', '>', 0)->count(),
                'total_sudah_jadwal' => $recapRows->where('jumlah_jadwal_master', '>', 0)->count(),
                'total_belum_jadwal' => $recapRows->where('jumlah_jadwal_master', 0)->count(),
                'total_sesi_tidak_presensi' => $recapRows->sum('total_belum_presensi'),
            ],
        ];
    }

    private function getTeachingRecapEligibleTeachers()
    {
        return User::query()
            ->where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->where(function ($query) {
                $query->whereNull('ketugasan')
                    ->orWhereRaw('LOWER(ketugasan) NOT LIKE ?', ['%kepala%']);
            })
            ->where(function ($query) {
                $query->whereNull('status_kepegawaian_id')
                    ->orWhereNotIn('status_kepegawaian_id', [1, 2, 7, 8]);
            })
            ->with(['madrasah', 'statusKepegawaian'])
            ->join('madrasahs', 'users.madrasah_id', '=', 'madrasahs.id')
            ->orderByRaw("CAST(madrasahs.scod AS UNSIGNED) ASC")
            ->orderBy('users.name')
            ->select('users.*')
            ->get();
    }

    private function summarizeTeachingSchedules(
        $schedules,
        Carbon $startDate,
        Carbon $endDate,
        Carbon $effectiveEndDate,
        $attendanceKeys,
        $approvedIzinKeys
    ): array {
        $totalJadwalPeriode = 0;
        $totalJadwalBerjalan = 0;
        $totalPresensi = 0;
        $totalIzin = 0;
        $totalBelumPresensi = 0;
        $missingByDate = [];
        $izinByDate = [];

        foreach ($schedules as $schedule) {
            foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
                if ($this->getTeachingDayName($date) !== $schedule->day) {
                    continue;
                }

                $totalJadwalPeriode++;

                if ($date->gt($effectiveEndDate)) {
                    continue;
                }

                $totalJadwalBerjalan++;
                $dateKey = $date->toDateString();
                $attendanceKey = $schedule->id . '|' . $dateKey;

                if ($attendanceKeys->has($attendanceKey)) {
                    $totalPresensi++;
                } elseif ($approvedIzinKeys->has($schedule->teacher_id . '|' . $dateKey)) {
                    $totalIzin++;
                    $izinByDate[$dateKey] = ($izinByDate[$dateKey] ?? 0) + 1;
                } else {
                    $totalBelumPresensi++;
                    $missingByDate[$dateKey] = ($missingByDate[$dateKey] ?? 0) + 1;
                }
            }
        }

        $missingRincian = collect($missingByDate)
            ->map(function ($count, $date) {
                $label = Carbon::parse($date)->locale('id')->translatedFormat('d M Y');
                return $count > 1 ? "{$label} ({$count} jadwal)" : $label;
            })
            ->implode(', ');

        $izinRincian = collect($izinByDate)
            ->map(function ($count, $date) {
                $label = Carbon::parse($date)->locale('id')->translatedFormat('d M Y');
                return $count > 1 ? "{$label} ({$count} jadwal)" : $label;
            })
            ->implode(', ');

        $rincianTanggal = collect([
            $missingRincian ? 'Belum presensi: ' . $missingRincian : null,
            $izinRincian ? 'Izin: ' . $izinRincian : null,
        ])->filter()->implode(' | ');

        return [
            'total_jadwal_periode' => $totalJadwalPeriode,
            'total_jadwal_berjalan' => $totalJadwalBerjalan,
            'total_presensi' => $totalPresensi,
            'total_izin' => $totalIzin,
            'total_belum_presensi' => $totalBelumPresensi,
            'persentase_tidak_presensi' => $totalJadwalBerjalan > 0
                ? round(($totalBelumPresensi / $totalJadwalBerjalan) * 100, 1)
                : 0,
            'rincian_tanggal' => $rincianTanggal ?: '-',
        ];
    }

    private function getTeacherDailyStatusKeys($teacherIds, Carbon $startDate, Carbon $endDate): array
    {
        $teacherIds = collect($teacherIds)->filter()->unique()->values();

        if ($teacherIds->isEmpty() || $endDate->lt($startDate)) {
            return [
                'attendance' => collect(),
                'izin' => collect(),
            ];
        }

        $attendanceKeys = TeachingAttendance::query()
            ->whereIn('user_id', $teacherIds)
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'hadir')
            ->get(['user_id', 'tanggal'])
            ->mapWithKeys(function ($attendance) {
                return [$attendance->user_id . '|' . Carbon::parse($attendance->tanggal)->toDateString() => true];
            });

        return [
            'attendance' => $attendanceKeys,
            'izin' => $this->getApprovedIzinKeys($teacherIds, $startDate, $endDate),
        ];
    }

    private function getApprovedIzinKeys($teacherIds, Carbon $startDate, Carbon $endDate)
    {
        $teacherIds = collect($teacherIds)->filter()->unique()->values();

        if ($teacherIds->isEmpty() || $endDate->lt($startDate)) {
            return collect();
        }

        return Presensi::query()
            ->whereIn('user_id', $teacherIds)
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'izin')
            ->where('status_izin', 'approved')
            ->get(['user_id', 'tanggal'])
            ->mapWithKeys(function ($presensi) {
                return [$presensi->user_id . '|' . Carbon::parse($presensi->tanggal)->toDateString() => true];
            });
    }

    private function resolveTeacherDailyTeachingStatus(
        int $teacherId,
        Carbon $date,
        $attendanceKeys,
        $approvedIzinKeys
    ): string {
        $dateKey = $date->toDateString();
        $teacherDateKey = $teacherId . '|' . $dateKey;

        if ($attendanceKeys->has($teacherDateKey)) {
            return 'hadir';
        }

        if ($approvedIzinKeys->has($teacherDateKey)) {
            return 'izin';
        }

        return 'alpha';
    }

    private function getTeachingDayName(Carbon $date): ?string
    {
        return self::TEACHING_DAY_NAMES[$date->dayOfWeek] ?? null;
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
