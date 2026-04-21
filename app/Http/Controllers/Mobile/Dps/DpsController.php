<?php

namespace App\Http\Controllers\Mobile\Dps;

use App\Http\Controllers\Controller;
use App\Models\DpsMember;
use App\Models\DataSekolah;
use App\Models\Holiday;
use App\Models\Madrasah;
use App\Models\Presensi;
use App\Models\TeachingAttendance;
use App\Models\TeachingClassStudentCount;
use App\Models\TeachingSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DpsController extends Controller
{
    private function ensureRole(): User
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'dps') {
            abort(403, 'Unauthorized.');
        }
        return $user;
    }

    private function getAccessibleMadrasahs(User $user)
    {
        $madrasahIds = DpsMember::where('user_id', $user->id)
            ->pluck('madrasah_id')
            ->unique()
            ->filter()
            ->values()
            ->all();

        return Madrasah::whereIn('id', $madrasahIds)
            ->orderByRaw('CAST(scod AS UNSIGNED) ASC')
            ->get();
    }

    private function getSelectedMadrasah(User $user, ?int $madrasahId): Madrasah
    {
        $madrasahs = $this->getAccessibleMadrasahs($user);
        if ($madrasahs->isEmpty()) {
            abort(400, 'Akun DPS belum memiliki sekolah.');
        }

        if ($madrasahId) {
            $selected = $madrasahs->firstWhere('id', $madrasahId);
            if ($selected) return $selected;
        }

        return $madrasahs->first();
    }

    private function getJumlahSiswa(int $madrasahId): int
    {
        $fromTeachingClassCounts = (int) TeachingClassStudentCount::query()
            ->where('school_id', $madrasahId)
            ->sum('total_students');

        if ($fromTeachingClassCounts > 0) {
            return $fromTeachingClassCounts;
        }

        $fromTeachingAttendances = (int) DB::table('teaching_attendances')
            ->join('teaching_schedules', 'teaching_attendances.teaching_schedule_id', '=', 'teaching_schedules.id')
            ->where('teaching_schedules.school_id', $madrasahId)
            ->whereNotNull('teaching_attendances.class_total_students')
            ->select('teaching_schedules.class_name', DB::raw('MAX(teaching_attendances.class_total_students) as total_students'))
            ->groupBy('teaching_schedules.class_name')
            ->get()
            ->sum('total_students');

        if ($fromTeachingAttendances > 0) {
            return $fromTeachingAttendances;
        }

        $dataSekolah = DataSekolah::where('madrasah_id', $madrasahId)
            ->orderBy('tahun', 'desc')
            ->first();

        return (int)($dataSekolah->jumlah_siswa ?? 0);
    }

    private function getJumlahGuru(int $madrasahId): int
    {
        return User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->count();
    }

    public function dashboard(Request $request)
    {
        $user = $this->ensureRole();
        $madrasahs = $this->getAccessibleMadrasahs($user);
        $selectedMadrasah = $this->getSelectedMadrasah($user, $request->integer('madrasah_id') ?: null);

        $dataSekolah = DataSekolah::where('madrasah_id', $selectedMadrasah->id)
            ->orderBy('tahun', 'desc')
            ->first();

        $jumlahGuru = $this->getJumlahGuru($selectedMadrasah->id);
        $jumlahSiswa = $this->getJumlahSiswa($selectedMadrasah->id);

        $tenagaPendidik = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $selectedMadrasah->id)
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'ketugasan']);

        $kepalaSekolahUser = User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $selectedMadrasah->id)
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->orderBy('name')
            ->first(['id', 'name', 'ketugasan']);

        $kepalaSekolah = $kepalaSekolahUser?->name ?: '-';

        return view('mobile.dps.dashboard', compact(
            'user',
            'madrasahs',
            'selectedMadrasah',
            'dataSekolah',
            'jumlahGuru',
            'jumlahSiswa',
            'tenagaPendidik',
            'kepalaSekolah'
        ));
    }

    public function presensiKehadiran(Request $request)
    {
        $user = $this->ensureRole();
        $madrasahs = $this->getAccessibleMadrasahs($user);
        $selectedMadrasah = $this->getSelectedMadrasah($user, $request->integer('madrasah_id') ?: null);

        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $monthly = $this->getMonthlyAttendanceSummary($selectedMadrasah->id, $selectedMonth);

        return view('mobile.dps.presensi-kehadiran', [
            'user' => $user,
            'madrasahs' => $madrasahs,
            'selectedMadrasah' => $selectedMadrasah,
            'selectedMonth' => $selectedMonth,
            'monthly' => $monthly,
        ]);
    }

    public function presensiMengajar(Request $request)
    {
        $user = $this->ensureRole();
        $madrasahs = $this->getAccessibleMadrasahs($user);
        $selectedMadrasah = $this->getSelectedMadrasah($user, $request->integer('madrasah_id') ?: null);

        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $teaching = $this->getTeachingAttendanceSummary($selectedMadrasah->id, $selectedMonth);

        return view('mobile.dps.presensi-mengajar', [
            'user' => $user,
            'madrasahs' => $madrasahs,
            'selectedMadrasah' => $selectedMadrasah,
            'selectedMonth' => $selectedMonth,
            'teaching' => $teaching,
        ]);
    }

    public function profile(Request $request)
    {
        $user = $this->ensureRole();
        $madrasahs = $this->getAccessibleMadrasahs($user);

        $assignments = DpsMember::with('madrasah')
            ->where('user_id', $user->id)
            ->orderByRaw('madrasah_id ASC')
            ->orderBy('periode')
            ->get();

        return view('mobile.dps.profile', compact('user', 'madrasahs', 'assignments'));
    }

    private function getMonthlyAttendanceSummary(int $madrasahId, string $month): array
    {
        $selectedMonth = Carbon::createFromFormat('Y-m', $month);
        $start = $selectedMonth->copy()->startOfMonth()->startOfDay();
        $end = $selectedMonth->copy()->endOfMonth()->endOfDay();

        $madrasah = Madrasah::find($madrasahId);
        $hariKbm = (int)($madrasah->hari_kbm ?? 5);

        $teachers = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $madrasahId)
            ->orderBy('name')
            ->get(['id', 'name', 'avatar', 'ketugasan']);

        // Build working days list
        $workingDates = [];
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $dayOfWeek = $cursor->dayOfWeek; // 0 Sun ... 6 Sat
            $isWorking = $hariKbm === 6
                ? ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::SATURDAY)
                : ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::FRIDAY);

            $dateStr = $cursor->toDateString();
            $isHoliday = Holiday::where('date', $dateStr)->where('is_active', true)->exists();

            if ($isWorking && !$isHoliday) {
                $workingDates[] = $dateStr;
            }
            $cursor->addDay();
        }

        $totalWorkingDays = count($workingDates);
        $teacherRows = [];

        foreach ($teachers as $t) {
            $records = Presensi::where('user_id', $t->id)
                ->whereBetween('tanggal', [$start, $end])
                ->get(['tanggal', 'status', 'status_izin']);

            $byDate = $records->keyBy(function ($r) {
                return Carbon::parse($r->tanggal)->toDateString();
            });

            $hadir = 0;
            $izin = 0;
            $alpha = 0;

            foreach ($workingDates as $dateStr) {
                $r = $byDate->get($dateStr);
                if (!$r) {
                    $alpha++;
                    continue;
                }
                if (in_array($r->status, ['hadir', 'terlambat'], true)) {
                    $hadir++;
                } elseif ($r->status === 'izin') {
                    $izin++;
                } else {
                    $alpha++;
                }
            }

            $total = $hadir + $izin + $alpha;
            $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            $teacherRows[] = [
                'id' => $t->id,
                'name' => $t->name,
                'ketugasan' => $t->ketugasan,
                'hadir' => $hadir,
                'izin' => $izin,
                'alpha' => $alpha,
                'persentase_hadir' => $persentase,
            ];
        }

        $totalHadir = array_sum(array_column($teacherRows, 'hadir'));
        $totalIzin = array_sum(array_column($teacherRows, 'izin'));
        $totalAlpha = array_sum(array_column($teacherRows, 'alpha'));
        $totalPresensi = $totalHadir + $totalIzin + $totalAlpha;
        $persentaseSekolah = $totalPresensi > 0 ? round(($totalHadir / $totalPresensi) * 100, 1) : 0;

        return [
            'month_name' => $selectedMonth->locale('id')->isoFormat('MMMM YYYY'),
            'hari_kbm' => $hariKbm,
            'total_working_days' => $totalWorkingDays,
            'summary' => [
                'total_teachers' => $teachers->count(),
                'total_hadir' => $totalHadir,
                'total_izin' => $totalIzin,
                'total_alpha' => $totalAlpha,
                'persentase_sekolah' => $persentaseSekolah,
            ],
            'teachers' => $teacherRows,
        ];
    }

    private function getTeachingAttendanceSummary(int $madrasahId, string $month): array
    {
        $selectedMonth = Carbon::createFromFormat('Y-m', $month);
        $startOfMonth = $selectedMonth->copy()->startOfMonth()->startOfDay();
        $endOfMonth = $selectedMonth->copy()->endOfMonth()->endOfDay();

        $madrasah = Madrasah::find($madrasahId);
        $hariKbm = (int)($madrasah->hari_kbm ?? 5);

        $teachingSchedules = TeachingSchedule::where('school_id', $madrasahId)->get();

        $teachingAttendances = TeachingAttendance::with(['teachingSchedule', 'user'])
            ->whereHas('teachingSchedule', function ($q) use ($madrasahId) {
                $q->where('school_id', $madrasahId);
            })
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->get();

        $availableMonths = DB::table('teaching_attendances')
            ->join('teaching_schedules', 'teaching_attendances.teaching_schedule_id', '=', 'teaching_schedules.id')
            ->selectRaw("DISTINCT DATE_FORMAT(teaching_attendances.tanggal, '%Y-%m') as month_year, DATE_FORMAT(teaching_attendances.tanggal, '%M %Y') as month_name")
            ->where('teaching_schedules.school_id', $madrasahId)
            ->orderBy('month_year', 'desc')
            ->get();

        // Count scheduled classes per day (rough estimate based on schedules)
        $scheduledByDate = [];
        $totalScheduled = 0;
        foreach ($teachingSchedules as $schedule) {
            $dayOfWeek = (int) $schedule->day;
            $cursor = $startOfMonth->copy();
            while ($cursor <= $endOfMonth) {
                if ($cursor->dayOfWeek === $dayOfWeek) {
                    $dateStr = $cursor->toDateString();
                    $scheduledByDate[$dateStr] = ($scheduledByDate[$dateStr] ?? 0) + 1;
                    $totalScheduled++;
                }
                $cursor->addDay();
            }
        }

        return [
            'month_name' => $selectedMonth->locale('id')->isoFormat('MMMM YYYY'),
            'hari_kbm' => $hariKbm,
            'summary' => [
                'total_scheduled_classes' => $totalScheduled,
                'total_conducted_classes' => $teachingAttendances->count(),
                'persentase_pelaksanaan' => $totalScheduled > 0 ? round(($teachingAttendances->count() / $totalScheduled) * 100, 1) : 0,
            ],
            'available_months' => $availableMonths,
            'records' => $teachingAttendances->map(function ($a) {
                $schedule = $a->teachingSchedule;
                return [
                    'date' => Carbon::parse($a->tanggal)->toDateString(),
                    'time' => $a->waktu,
                    'teacher' => $a->user ? $a->user->name : '-',
                    'subject' => $schedule ? $schedule->subject : '-',
                    'class_name' => $schedule ? $schedule->class_name : '-',
                    'schedule_time' => $schedule ? ($schedule->start_time . ' - ' . $schedule->end_time) : '-',
                    'materi' => $a->materi,
                    'present_students' => $a->present_students,
                    'class_total_students' => $a->class_total_students,
                    'percentage' => $a->student_attendance_percentage,
                    'status' => $a->status ?? 'hadir',
                ];
            })->toArray(),
        ];
    }
}
