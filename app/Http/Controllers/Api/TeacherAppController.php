<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\MgmpMember;
use App\Models\MgmpReport;
use App\Models\Presensi;
use App\Models\TeachingAttendance;
use App\Models\TeachingSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherAppController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $today = Carbon::today('Asia/Jakarta');
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        $presensiThisMonth = Presensi::query()
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get();

        $todayPresensi = Presensi::query()
            ->with('madrasah')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->orderBy('tanggal')
            ->get();

        $todaySchedules = $this->todaySchedules($user, $today);
        $todayAttendances = TeachingAttendance::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->get()
            ->keyBy('teaching_schedule_id');

        $scheduleItems = $todaySchedules->map(function (TeachingSchedule $schedule) use ($todayAttendances) {
            $attendance = $todayAttendances->get($schedule->id);

            return [
                'id' => $schedule->id,
                'subject' => $schedule->subject,
                'class_name' => $schedule->class_name,
                'school_name' => $schedule->school?->name,
                'start_time' => $this->formatTime($schedule->start_time),
                'end_time' => $this->formatTime($schedule->end_time),
                'attendance_status' => $attendance ? 'completed' : 'pending',
                'materi' => $attendance?->materi,
            ];
        })->values();

        $monthlyPresentCount = $presensiThisMonth->where('status', 'hadir')->count();
        $monthlyIzinCount = $presensiThisMonth->where('status', 'izin')->count();
        $monthlyAlphaCount = $presensiThisMonth->where('status', 'alpha')->count();

        $checkInDone = $todayPresensi->contains(fn ($item) => !empty($item->waktu_masuk));
        $checkOutDone = $todayPresensi->contains(fn ($item) => !empty($item->waktu_keluar));
        $performanceSteps = collect([
            [
                'label' => 'Presensi Masuk',
                'status' => $checkInDone ? 'completed' : 'pending',
                'icon' => 'login',
            ],
            ...$scheduleItems->values()->map(function ($schedule, $index) {
                return [
                    'label' => 'Mengajar ' . ($index + 1),
                    'status' => $schedule['attendance_status'],
                    'icon' => 'school',
                ];
            })->all(),
            [
                'label' => 'Presensi Keluar',
                'status' => $checkOutDone ? 'completed' : 'pending',
                'icon' => 'logout',
            ],
        ])->values();

        $performanceCompleted = $performanceSteps
            ->where('status', 'completed')
            ->count();
        $performancePercent = $performanceSteps->isEmpty()
            ? 0
            : (int) round(($performanceCompleted / $performanceSteps->count()) * 100);

        return response()->json([
            'message' => 'OK',
            'data' => [
                'greeting' => 'Selamat datang, ' . strtok(trim((string) $user->name), ' '),
                'user_name' => $user->name,
                'role' => $user->role,
                'school_name' => $user->madrasah?->name ?? '-',
                'today_label' => $today->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'user_card' => [
                    'name' => $user->name,
                    'school_name' => $user->madrasah?->name ?? '-',
                    'avatar_url' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                    'nuist_id' => $user->nuist_id ?? '-',
                    'status_kepegawaian' => $user->statusKepegawaian?->name ?? '-',
                    'ketugasan' => $user->ketugasan ?? '-',
                ],
                'summary' => [
                    'attendance_percent' => $this->attendancePercent($presensiThisMonth),
                    'pending_izin_count' => Izin::query()
                        ->where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->count(),
                    'teaching_today_count' => $todaySchedules->count(),
                    'completed_teaching_today_count' => $scheduleItems
                        ->where('attendance_status', 'completed')
                        ->count(),
                ],
                'monthly_stats' => [
                    'present_count' => $monthlyPresentCount,
                    'izin_count' => $monthlyIzinCount,
                    'alpha_count' => $monthlyAlphaCount,
                ],
                'performance' => [
                    'percent' => $performancePercent,
                    'level' => $this->performanceLevel($performancePercent),
                    'steps' => $performanceSteps,
                ],
                'today_attendance' => $this->serializeTodayAttendance($todayPresensi),
                'today_schedules' => $scheduleItems,
                'recent_izin' => Izin::query()
                    ->where('user_id', $user->id)
                    ->latest('tanggal')
                    ->limit(3)
                    ->get()
                    ->map(fn (Izin $izin) => $this->serializeIzin($izin))
                    ->values(),
            ],
        ]);
    }

    public function schedule(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);

        $schedules = TeachingSchedule::query()
            ->with('school')
            ->where('teacher_id', $user->id)
            ->orderByRaw($this->dayOrderSql())
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'items' => $schedules->map(function (TeachingSchedule $schedule) {
                    return [
                        'id' => $schedule->id,
                        'day' => $schedule->day,
                        'subject' => $schedule->subject,
                        'class_name' => $schedule->class_name,
                        'school_name' => $schedule->school?->name,
                        'start_time' => $this->formatTime($schedule->start_time),
                        'end_time' => $this->formatTime($schedule->end_time),
                    ];
                })->values(),
            ],
        ]);
    }

    public function attendance(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $today = Carbon::today('Asia/Jakarta');

        $todayPresensi = Presensi::query()
            ->with('madrasah')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->orderBy('tanggal')
            ->get();

        $recent = Presensi::query()
            ->with('madrasah')
            ->where('user_id', $user->id)
            ->latest('tanggal')
            ->limit(10)
            ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'today_label' => $today->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'today_attendance' => $this->serializeTodayAttendance($todayPresensi),
                'recent' => $recent->map(function (Presensi $item) {
                    return [
                        'id' => $item->id,
                        'date' => optional($item->tanggal)->format('Y-m-d'),
                        'date_label' => $item->tanggal
                            ? Carbon::parse($item->tanggal)->locale('id')->isoFormat('D MMMM YYYY')
                            : '-',
                        'status' => $item->status ?? '-',
                        'check_in' => optional($item->waktu_masuk)->format('H:i'),
                        'check_out' => optional($item->waktu_keluar)->format('H:i'),
                        'location' => $item->lokasi ?: $item->madrasah?->name,
                        'school_name' => $item->madrasah?->name,
                    ];
                })->values(),
            ],
        ]);
    }

    public function teachingJournal(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $today = Carbon::today('Asia/Jakarta');
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        $items = TeachingAttendance::query()
            ->with(['teachingSchedule.school'])
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'month_label' => $today->locale('id')->isoFormat('MMMM YYYY'),
                'summary' => [
                    'total_entries' => $items->count(),
                    'total_present_students' => (int) $items->sum('present_students'),
                    'total_classes' => $items->pluck('teaching_schedule_id')->filter()->unique()->count(),
                ],
                'items' => $items->map(function (TeachingAttendance $item) {
                    return [
                        'id' => $item->id,
                        'date' => optional($item->tanggal)->format('Y-m-d'),
                        'date_label' => $item->tanggal
                            ? Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY')
                            : '-',
                        'subject' => $item->teachingSchedule?->subject ?? '-',
                        'class_name' => $item->teachingSchedule?->class_name ?? '-',
                        'school_name' => $item->teachingSchedule?->school?->name,
                        'time' => $this->formatTime($item->waktu),
                        'materi' => $item->materi ?: 'Belum ada materi tercatat.',
                        'present_students' => $item->present_students,
                        'class_total_students' => $item->class_total_students,
                        'student_attendance_percentage' => $item->student_attendance_percentage,
                    ];
                })->values(),
            ],
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $now = Carbon::now('Asia/Jakarta');

        $mgmpMemberships = MgmpMember::query()
            ->with('mgmpGroup')
            ->where('user_id', $user->id)
            ->get();

        $mgmpGroupIds = $mgmpMemberships->pluck('mgmp_group_id')->filter()->unique()->values();

        $activities = $mgmpGroupIds->isEmpty()
            ? collect()
            : MgmpReport::query()
                ->with('mgmpGroup')
                ->whereIn('mgmp_group_id', $mgmpGroupIds)
                ->whereDate('tanggal', '>=', $now->toDateString())
                ->orderBy('tanggal')
                ->orderBy('waktu_mulai')
                ->limit(5)
                ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_hp,
                    'role' => $user->role,
                    'school_name' => $user->madrasah?->name,
                    'avatar_url' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                ],
                'details' => [
                    ['label' => 'Nuist ID', 'value' => $user->nuist_id ?? '-'],
                    ['label' => 'Status Kepegawaian', 'value' => $user->statusKepegawaian?->name ?? '-'],
                    ['label' => 'Ketugasan', 'value' => $user->ketugasan ?? '-'],
                    ['label' => 'Tempat Lahir', 'value' => $user->tempat_lahir ?? '-'],
                    ['label' => 'Tanggal Lahir', 'value' => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('d-m-Y') : '-'],
                    ['label' => 'TMT', 'value' => $user->tmt ? Carbon::parse($user->tmt)->format('d-m-Y') : '-'],
                    ['label' => 'NUPTK', 'value' => $user->nuptk ?? '-'],
                    ['label' => 'NPK', 'value' => $user->npk ?? '-'],
                    ['label' => 'KartaNU', 'value' => $user->kartanu ?? '-'],
                    ['label' => 'NIP', 'value' => $user->nip ?? '-'],
                    ['label' => 'Pendidikan Terakhir', 'value' => $user->pendidikan_terakhir ?? '-'],
                    ['label' => 'Program Studi', 'value' => $user->program_studi ?? '-'],
                ],
                'mgmp_memberships' => $mgmpMemberships->map(function ($membership) {
                    return [
                        'id' => $membership->id,
                        'group_name' => $membership->mgmpGroup?->name ?? '-',
                    ];
                })->values(),
                'upcoming_activities' => $activities->map(function (MgmpReport $activity) {
                    return [
                        'id' => $activity->id,
                        'title' => $activity->judul ?? ($activity->mgmpGroup?->name ?? 'Kegiatan MGMP'),
                        'group_name' => $activity->mgmpGroup?->name ?? '-',
                        'date_label' => $activity->tanggal
                            ? Carbon::parse($activity->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY')
                            : '-',
                        'time_label' => trim($this->formatTime($activity->waktu_mulai) . ' - ' . $this->formatTime($activity->waktu_selesai)),
                    ];
                })->values(),
            ],
        ]);
    }

    public function izin(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);

        $items = Izin::query()
            ->where('user_id', $user->id)
            ->latest('tanggal')
            ->latest('id')
            ->limit(20)
            ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'summary' => [
                    'pending' => $items->where('status', 'pending')->count(),
                    'approved' => $items->where('status', 'approved')->count(),
                    'rejected' => $items->where('status', 'rejected')->count(),
                ],
                'items' => $items->map(fn (Izin $izin) => $this->serializeIzin($izin))->values(),
            ],
        ]);
    }

    private function resolveTeacher(Request $request): User
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($user && $user->role === 'tenaga_pendidik', 403);

        return $user->loadMissing(['madrasah', 'statusKepegawaian']);
    }

    private function todaySchedules(User $user, Carbon $today)
    {
        $todayName = $today->copy()->locale('id')->dayName;

        return TeachingSchedule::query()
            ->with('school')
            ->where('teacher_id', $user->id)
            ->whereRaw('LOWER(day) = ?', [strtolower($todayName)])
            ->orderBy('start_time')
            ->get();
    }

    private function attendancePercent($items): float
    {
        $total = $items->count();
        if ($total === 0) {
            return 0;
        }

        $present = $items->whereIn('status', ['hadir', 'izin'])->count();

        return round(($present / $total) * 100, 1);
    }

    private function serializeTodayAttendance($items): array
    {
        $checkIn = $items->filter(fn ($item) => !empty($item->waktu_masuk))->sortBy('waktu_masuk')->first();
        $checkOut = $items->filter(fn ($item) => !empty($item->waktu_keluar))->sortByDesc('waktu_keluar')->first();

        $status = 'belum_presensi';
        if ($items->where('status', 'hadir')->isNotEmpty()) {
            $status = 'hadir';
        } elseif ($items->where('status', 'izin')->isNotEmpty()) {
            $status = 'izin';
        } elseif ($items->where('status', 'alpha')->isNotEmpty()) {
            $status = 'alpha';
        }

        return [
            'status' => $status,
            'status_label' => match ($status) {
                'hadir' => 'Sudah presensi',
                'izin' => 'Tercatat izin',
                'alpha' => 'Tercatat alpha',
                default => 'Belum presensi',
            },
            'check_in' => optional($checkIn?->waktu_masuk)->format('H:i'),
            'check_out' => optional($checkOut?->waktu_keluar)->format('H:i'),
            'location' => $checkIn?->lokasi ?: $checkIn?->madrasah?->name,
        ];
    }

    private function serializeIzin(Izin $izin): array
    {
        return [
            'id' => $izin->id,
            'type' => $izin->type,
            'title' => $this->izinTitle($izin->type),
            'status' => $izin->status,
            'reason' => $izin->alasan ?: $izin->deskripsi_tugas,
            'submitted_at' => optional($izin->tanggal)->format('Y-m-d'),
            'submitted_at_label' => $izin->tanggal
                ? Carbon::parse($izin->tanggal)->locale('id')->isoFormat('D MMMM YYYY')
                : '-',
            'end_date_label' => $izin->tanggal_selesai
                ? Carbon::parse($izin->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY')
                : null,
        ];
    }

    private function izinTitle(?string $type): string
    {
        return match ($type) {
            'tugas_luar' => 'Izin Tugas Luar',
            'tidak_masuk' => 'Izin Tidak Masuk',
            'mengajar_sekolah_lain' => 'Izin Mengajar di Sekolah Lain',
            'sakit' => 'Izin Sakit',
            'terlambat' => 'Izin Terlambat',
            default => 'Izin ' . ucwords(str_replace('_', ' ', (string) $type)),
        };
    }

    private function performanceLevel(int $percent): string
    {
        return match (true) {
            $percent >= 100 => 'Teladan',
            $percent >= 80 => 'Baik Sekali',
            $percent >= 50 => 'Baik',
            $percent >= 10 => 'Cukup Baik',
            default => 'Belum Ada Progress',
        };
    }

    private function formatTime($value): ?string
    {
        if (!$value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->format('H:i');
        }

        try {
            return Carbon::parse((string) $value)->format('H:i');
        } catch (\Throwable $exception) {
            return (string) $value;
        }
    }

    private function dayOrderSql(): string
    {
        return "CASE day
            WHEN 'Senin' THEN 1
            WHEN 'Selasa' THEN 2
            WHEN 'Rabu' THEN 3
            WHEN 'Kamis' THEN 4
            WHEN 'Jumat' THEN 5
            WHEN 'Sabtu' THEN 6
            WHEN 'Minggu' THEN 7
            ELSE 8
        END";
    }
}
