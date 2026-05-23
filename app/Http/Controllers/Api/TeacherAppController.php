<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Izin;
use App\Models\MgmpMember;
use App\Models\MgmpReport;
use App\Models\Notification;
use App\Models\Presensi;
use App\Models\TeachingAttendance;
use App\Models\TeachingClassStudentCount;
use App\Models\TeachingSchedule;
use App\Models\User;
use App\Services\AcademicCalendarEventService;
use App\Services\ApprovedIzinSyncService;
use App\Services\FcmPushService;
use App\Services\ExternalTeachingPermissionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TeacherAppController extends Controller
{
    private const SCHEDULE_DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    private const SCHEDULE_NEW_VALUE = '__new__';

    public function __construct(private AcademicCalendarEventService $academicCalendarEventService)
    {
    }

    public function dashboard(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $today = Carbon::today('Asia/Jakarta');
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        ApprovedIzinSyncService::syncApprovedIzinPresensiInRange($user, $monthStart, $monthEnd);

        $presensiThisMonth = Presensi::query()
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get();
        $holidaysThisMonth = Holiday::query()
            ->where('is_active', true)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('date')
            ->get();

        $todayPresensi = Presensi::query()
            ->with('madrasah')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->orderBy('tanggal')
            ->get();

        $this->academicCalendarEventService->syncTeacherDate($user, $today);

        $todaySchedules = $this->todaySchedules($user, $today);
        $todayAttendances = TeachingAttendance::query()
            ->with('academicCalendarEvent')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->get()
            ->keyBy('teaching_schedule_id');
        $approvedTeachingJournalIzin = $this->findApprovedTeachingJournalIzin($user, $today);
        $attendanceStats = $this->calculateDashboardAttendanceStats($user, $presensiThisMonth, $today);

        $scheduleItems = $todaySchedules->map(function (TeachingSchedule $schedule) use ($todayAttendances, $approvedTeachingJournalIzin) {
            $attendance = $todayAttendances->get($schedule->id);
            $status = $attendance
                ? 'completed'
                : ($approvedTeachingJournalIzin ? 'izin' : 'pending');

            return [
                'id' => $schedule->id,
                'subject' => $schedule->subject,
                'class_name' => $schedule->class_name,
                'school_name' => $schedule->school?->name,
                'start_time' => $this->formatTime($schedule->start_time),
                'end_time' => $this->formatTime($schedule->end_time),
                'attendance_status' => $status,
                'attendance_status_label' => match ($status) {
                    'completed' => ($attendance?->is_academic_calendar_auto ?? false)
                        ? ($attendance?->display_status_label ?? 'Kalender Akademik')
                        : 'Sudah Presensi',
                    'izin' => 'Izin Disetujui',
                    default => 'Belum Presensi',
                },
                'materi' => $attendance?->materi,
                'is_auto_generated' => (bool) $attendance?->is_auto_generated,
                'attendance_source' => $attendance?->attendance_source,
                'event_name' => $attendance?->academicCalendarEvent?->name,
            ];
        })->values();

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
            ->filter(fn ($step) => in_array($step['status'] ?? 'pending', ['completed', 'izin'], true))
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
                'current_month_label' => $today->locale('id')->isoFormat('MMMM YYYY'),
                'user_card' => [
                    'name' => $user->name,
                    'school_name' => $user->madrasah?->name ?? '-',
                    'avatar_url' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                    'nuist_id' => $user->nuist_id ?? '-',
                    'status_kepegawaian' => $user->statusKepegawaian?->name ?? '-',
                    'ketugasan' => $user->ketugasan ?? '-',
                ],
                'permissions' => [
                    'can_manage_izin' => $this->canManageIzinRequests($user),
                ],
                'summary' => [
                    'attendance_percent' => $attendanceStats['attendance_percent'],
                    'attendance_basis_label' => $attendanceStats['attendance_basis_label'],
                    'pending_izin_count' => Izin::query()
                        ->where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->count(),
                    'pending_approval_izin_count' => $this->canManageIzinRequests($user)
                        ? Izin::query()
                            ->whereHas('user', function ($query) use ($user) {
                                $query->where('madrasah_id', $user->madrasah_id);
                            })
                            ->where('status', 'pending')
                            ->count()
                        : 0,
                    'teaching_today_count' => $todaySchedules->count(),
                    'completed_teaching_today_count' => $scheduleItems
                        ->filter(fn ($item) => in_array($item['attendance_status'] ?? 'pending', ['completed', 'izin'], true))
                        ->count(),
                ],
                'monthly_stats' => [
                    'present_count' => $attendanceStats['present_count'],
                    'izin_count' => $attendanceStats['izin_count'],
                    'alpha_count' => $attendanceStats['alpha_count'],
                    'working_days' => $attendanceStats['working_days'],
                    'hari_kbm' => $attendanceStats['hari_kbm'],
                ],
                'performance' => [
                    'percent' => $performancePercent,
                    'level' => $this->performanceLevel($performancePercent),
                    'steps' => $performanceSteps,
                ],
                'attendance_calendar_leading_empty_days' => $monthStart->dayOfWeekIso - 1,
                'attendance_calendar' => $this->buildAttendanceCalendar($presensiThisMonth, $holidaysThisMonth, $today),
                'holiday_notes' => $holidaysThisMonth
                    ->map(fn (Holiday $holiday) => $this->serializeHoliday($holiday))
                    ->values(),
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
                'items' => $schedules->map(fn (TeachingSchedule $schedule) => $this->serializeSchedule($schedule))->values(),
                'can_manage' => (bool) $user->madrasah_id,
            ],
        ]);
    }

    public function scheduleOptions(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $schoolId = $user->madrasah_id;

        if (!$schoolId) {
            throw ValidationException::withMessages([
                'school' => 'Akun Anda belum terhubung ke madrasah, sehingga tidak bisa mengelola jadwal.',
            ]);
        }

        return response()->json([
            'message' => 'OK',
            'data' => [
                'days' => self::SCHEDULE_DAYS,
                'new_value' => self::SCHEDULE_NEW_VALUE,
                'subjects' => $this->getScheduleSubjects($schoolId)->values(),
                'classes' => $this->getScheduleClasses($schoolId)->values(),
            ],
        ]);
    }

    public function storeSchedule(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $schoolId = $user->madrasah_id;

        if (!$schoolId) {
            throw ValidationException::withMessages([
                'school' => 'Akun Anda belum terhubung ke madrasah, sehingga tidak bisa membuat jadwal.',
            ]);
        }

        $validated = $this->validateSchedulePayload($request);
        $this->ensureScheduleDoesNotOverlap($user, $validated);

        $schedule = TeachingSchedule::create([
            'school_id' => $schoolId,
            'teacher_id' => $user->id,
            'day' => $validated['day'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'created_by' => $user->id,
        ])->load('school');

        return response()->json([
            'message' => 'Jadwal mengajar berhasil ditambahkan.',
            'data' => [
                'item' => $this->serializeSchedule($schedule),
            ],
        ], 201);
    }

    public function updateSchedule(Request $request, TeachingSchedule $schedule): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        abort_unless($schedule->teacher_id === $user->id, 403);

        $validated = $this->validateSchedulePayload($request);
        $this->ensureScheduleDoesNotOverlap($user, $validated, $schedule->id);

        $schedule->update([
            'day' => $validated['day'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        $schedule->load('school');

        return response()->json([
            'message' => 'Jadwal mengajar berhasil diperbarui.',
            'data' => [
                'item' => $this->serializeSchedule($schedule),
            ],
        ]);
    }

    public function destroySchedule(Request $request, TeachingSchedule $schedule): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        abort_unless($schedule->teacher_id === $user->id, 403);

        $schedule->delete();

        return response()->json([
            'message' => 'Jadwal mengajar berhasil dihapus.',
        ]);
    }

    public function attendance(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $today = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate($user, $today);

        $todayPresensi = Presensi::query()
            ->with('madrasah:id,name')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->orderBy('tanggal')
            ->select([
                'id',
                'user_id',
                'madrasah_id',
                'tanggal',
                'waktu_masuk',
                'waktu_keluar',
                'lokasi',
                // 'lokasi_keluar',
                'status',
                'keterangan',
                'selfie_masuk_path',
                'selfie_keluar_path',
            ])
            ->get();

        $recent = Presensi::query()
            ->with('madrasah:id,name')
            ->where('user_id', $user->id)
            ->latest('tanggal')
            ->latest('id')
            ->limit(10)
            ->select([
                'id',
                'user_id',
                'madrasah_id',
                'tanggal',
                'waktu_masuk',
                'waktu_keluar',
                'lokasi',
                // 'lokasi_keluar',
                'status',
                'keterangan',
            ])
            ->get();

        $holiday = Holiday::query()
            ->where('is_active', true)
            ->whereDate('date', $today->toDateString())
            ->first();
        $isSunday = $today->isSunday();
        $approvedBlockingIzin = $this->findApprovedBlockingIzin($user, $today);
        $pendingLatePermit = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->where('status', 'izin')
            ->where('status_izin', 'pending')
            ->where('keterangan', 'like', '%terlambat%')
            ->exists();
        $attendanceForm = $this->buildAttendanceFormState(
            $user,
            $today,
            $todayPresensi,
            $holiday,
            $isSunday,
            $approvedBlockingIzin,
            $pendingLatePermit,
        );

        return response()->json([
            'message' => 'OK',
            'data' => [
                'today_label' => $today->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'server_time' => $now->toIso8601String(),
                'school_name' => $user->madrasah?->name ?? '-',
                'teacher_name' => $user->name,
                'time_ranges' => $this->buildPresensiTimeRanges($user, $today),
                'form' => $attendanceForm,
                'today_attendance' => $this->buildTodayAttendanceState(
                    $todayPresensi,
                    $approvedBlockingIzin,
                    $holiday,
                    $isSunday,
                ),
                'recent' => $recent->map(function (Presensi $item) {
                    $autoPresentIzin = $this->findApprovedAutoPresentIzin($item->user_id, $item->tanggal);

                    return [
                        'id' => $item->id,
                        'date' => optional($item->tanggal)->format('Y-m-d'),
                        'date_label' => $item->tanggal?->locale('id')->isoFormat('D MMMM YYYY') ?? '-',
                        'status' => $item->status ?? '-',
                        'status_label' => $this->presensiStatusLabel($item, $autoPresentIzin),
                        'is_auto_present' => $autoPresentIzin !== null,
                        'check_in' => $this->formatTimeValue($item->waktu_masuk),
                        'check_out' => $this->formatTimeValue($item->waktu_keluar),
                        'location' => $item->lokasi ?: $item->madrasah?->name,
                        // 'location_out' => $item->lokasi_keluar,
                        'school_name' => $item->madrasah?->name,
                        'note' => $item->keterangan,
                    ];
                })->values(),
            ],
        ]);
    }

    public function storeAttendance(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);

        $validated = $request->validate([
            'presensi_mode' => ['required', Rule::in(['masuk', 'keluar'])],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'lokasi' => ['nullable', 'string'],
            'accuracy' => ['nullable', 'numeric'],
            'altitude' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
            'device_info' => ['nullable', 'string'],
            'location_readings' => ['nullable'],
            'selfie_data' => ['required', 'string', 'min:100'],
        ]);

        if (!$this->isValidBase64Image($validated['selfie_data'])) {
            throw ValidationException::withMessages([
                'selfie_data' => 'Data foto selfie tidak valid. Silakan ambil foto lagi.',
            ]);
        }

        $tanggal = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate($user, $tanggal);

        $holiday = Holiday::query()
            ->where('is_active', true)
            ->whereDate('date', $tanggal->toDateString())
            ->first();

        if ($holiday || $tanggal->isSunday()) {
            $reason = $holiday
                ? 'hari libur (' . $holiday->name . ')'
                : 'hari Minggu';

            throw ValidationException::withMessages([
                'attendance' => "Presensi tidak dapat dilakukan pada {$reason}.",
            ]);
        }

        $approvedBlockingIzin = $this->findApprovedBlockingIzin($user, $tanggal);
        if ($approvedBlockingIzin) {
            $izinLabel = ucfirst(str_replace('_', ' ', (string) $approvedBlockingIzin->type));

            throw ValidationException::withMessages([
                'attendance' => "{$izinLabel} Anda sudah disetujui. Hari ini otomatis tercatat sebagai izin.",
            ]);
        }

        $pendingLatePermit = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal->toDateString())
            ->where('status', 'izin')
            ->where('status_izin', 'pending')
            ->where('keterangan', 'like', '%terlambat%')
            ->exists();

        if ($pendingLatePermit) {
            throw ValidationException::withMessages([
                'attendance' => 'Izin terlambat Anda sedang menunggu persetujuan. Presensi belum dapat dilakukan.',
            ]);
        }

        $existingPresensi = $user->ketugasan === 'penjaga sekolah'
            ? Presensi::query()
                ->where('user_id', $user->id)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->latest('tanggal')
                ->first()
            : Presensi::query()
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal->toDateString())
                ->first();

        if ($validated['presensi_mode'] === 'masuk' && $existingPresensi?->waktu_masuk && $existingPresensi?->waktu_keluar) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi hari ini sudah lengkap.',
            ]);
        }

        if ($validated['presensi_mode'] === 'keluar' && (!$existingPresensi || !$existingPresensi->waktu_masuk)) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi keluar belum dapat dilakukan karena presensi masuk belum tercatat.',
            ]);
        }

        if ($validated['presensi_mode'] === 'keluar' && $existingPresensi?->waktu_keluar) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi keluar hari ini sudah dicatat.',
            ]);
        }

        $this->ensureAttendanceTimeAllowed($user, $validated['presensi_mode'], $now, $existingPresensi, $validated);

        $determinedMadrasahId = $this->determineAttendanceMadrasahId(
            $user,
            (float) $validated['latitude'],
            (float) $validated['longitude'],
        );

        if (!$determinedMadrasahId) {
            throw ValidationException::withMessages([
                'location' => 'Lokasi Anda berada di luar area sekolah yang telah ditentukan.',
            ]);
        }

        $locationValidation = $this->validateLocationForFakeGps(
            $validated,
            $user,
            $validated['presensi_mode'] === 'masuk',
        );

        if ($locationValidation['is_fake']) {
            throw ValidationException::withMessages([
                'location' => $locationValidation['message'],
            ]);
        }

        $selfiePath = $this->processAndSaveSelfie(
            $validated['selfie_data'],
            $user->id,
            $tanggal->toDateString(),
            $validated['presensi_mode'] === 'masuk',
        );

        $message = '';

        if ($validated['presensi_mode'] === 'masuk') {
            $keterangan = $this->buildCheckInNote($user, $tanggal, $now);

            Presensi::create($this->filterPresensiAttributes([
                'user_id' => $user->id,
                'madrasah_id' => $determinedMadrasahId,
                'tanggal' => $tanggal->toDateString(),
                'waktu_masuk' => $now,
                'status' => 'hadir',
                'keterangan' => $keterangan,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'lokasi' => $validated['lokasi'] ?? null,
                'accuracy' => $validated['accuracy'] ?? null,
                'altitude' => $validated['altitude'] ?? null,
                'speed' => $validated['speed'] ?? null,
                'device_info' => $validated['device_info'] ?? null,
                'location_readings' => $this->normalizeLocationReadings($validated['location_readings'] ?? null),
                'selfie_masuk_path' => $selfiePath,
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
                'is_fake_location' => false,
                'fake_location_analysis' => $locationValidation['analysis'] ?? null,
            ]));

            $message = 'Presensi masuk berhasil dicatat.';
        } else {
            $newKeterangan = trim((string) ($existingPresensi->keterangan ?? ''));
            if ($this->isEarlyCheckout($user, $now)) {
                $newKeterangan = $newKeterangan === ''
                    ? 'pulang awal'
                    : $newKeterangan . ' / pulang awal';
            }

            $existingPresensi->update($this->filterPresensiAttributes([
                'waktu_keluar' => $now,
                'latitude_keluar' => $validated['latitude'],
                'longitude_keluar' => $validated['longitude'],
                // 'lokasi_keluar' => $validated['lokasi'] ?? null,
                'accuracy_keluar' => $validated['accuracy'] ?? null,
                'altitude_keluar' => $validated['altitude'] ?? null,
                'speed_keluar' => $validated['speed'] ?? null,
                'device_info_keluar' => $validated['device_info'] ?? null,
                'location_readings_keluar' => $this->normalizeLocationReadings($validated['location_readings'] ?? null),
                'selfie_keluar_path' => $selfiePath,
                'keterangan' => $newKeterangan,
                'is_fake_location_keluar' => false,
                'fake_location_analysis_keluar' => $locationValidation['analysis'] ?? null,
            ]));

            $message = 'Presensi keluar berhasil dicatat.';
        }

        $this->notifyUserWithPush(
            $user,
            $validated['presensi_mode'] === 'masuk' ? 'attendance_check_in_success' : 'attendance_check_out_success',
            $validated['presensi_mode'] === 'masuk' ? 'Presensi Masuk Berhasil' : 'Presensi Keluar Berhasil',
            $message,
            [
                'tanggal' => $tanggal->toDateString(),
                'waktu' => $now->format('H:i:s'),
                'mode' => $validated['presensi_mode'],
            ]
        );

        $todayPresensi = Presensi::query()
            ->with('madrasah')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal->toDateString())
            ->orderBy('tanggal')
            ->get();

        return response()->json([
            'message' => $message,
            'data' => [
                'today_attendance' => $this->serializeTodayAttendance($todayPresensi),
            ],
        ]);
    }

    public function teachingJournal(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $today = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        $approvedTeachingJournalIzin = $this->findApprovedTeachingJournalIzin($user, $today);

        $this->academicCalendarEventService->syncTeacherRange($user, $monthStart, $monthEnd);

        $todaySchedules = $this->todaySchedules($user, $today);
        $this->attachTeachingClassStudentCounts($todaySchedules);

        $todayAttendances = TeachingAttendance::query()
            ->with('academicCalendarEvent')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today->toDateString())
            ->get()
            ->keyBy('teaching_schedule_id');

        $items = TeachingAttendance::query()
            ->with(['teachingSchedule.school', 'academicCalendarEvent'])
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'today_label' => $today->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'server_time' => $now->toIso8601String(),
                'month_label' => $today->locale('id')->isoFormat('MMMM YYYY'),
                'approved_izin_today' => $approvedTeachingJournalIzin
                    ? [
                        'message' => $this->teachingJournalIzinMessage($approvedTeachingJournalIzin),
                        'note' => $this->teachingJournalIzinNote($approvedTeachingJournalIzin),
                    ]
                    : null,
                'summary' => [
                    'total_entries' => $items->count(),
                    'total_present_students' => (int) $items->sum('present_students'),
                    'total_classes' => $items->pluck('teaching_schedule_id')->filter()->unique()->count(),
                ],
                'today_summary' => [
                    'total_schedules' => $todaySchedules->count(),
                    'completed_schedules' => $todaySchedules
                        ->filter(fn (TeachingSchedule $schedule) => $todayAttendances->has($schedule->id) || $approvedTeachingJournalIzin !== null)
                        ->count(),
                    'pending_schedules' => $todaySchedules
                        ->filter(fn (TeachingSchedule $schedule) => !$todayAttendances->has($schedule->id) && $approvedTeachingJournalIzin === null)
                        ->count(),
                ],
                'today_schedules' => $todaySchedules->map(function (TeachingSchedule $schedule) use ($todayAttendances, $approvedTeachingJournalIzin, $now) {
                    $attendance = $todayAttendances->get($schedule->id);
                    $timeState = $this->buildTeachingAttendanceTimeState($schedule, $now);
                    $classTotalStudents = $schedule->class_student_count?->total_students;

                    return [
                        'id' => $schedule->id,
                        'subject' => $schedule->subject,
                        'class_name' => $schedule->class_name,
                        'school_name' => $schedule->school?->name,
                        'start_time' => $this->formatTime($schedule->start_time),
                        'end_time' => $this->formatTime($schedule->end_time),
                        'class_total_students' => $classTotalStudents,
                        'requires_class_total_students' => $classTotalStudents === null,
                        'time_state' => $timeState['state'],
                        'time_message' => $timeState['message'],
                        'can_submit' => $attendance === null
                            && $approvedTeachingJournalIzin === null
                            && $timeState['state'] === 'within',
                        'status' => $attendance
                            ? (($attendance->status ?: 'hadir') === 'izin' ? 'izin' : 'hadir')
                            : ($approvedTeachingJournalIzin ? 'izin' : 'pending'),
                        'status_label' => $attendance
                            ? (($attendance->is_academic_calendar_auto ?? false)
                                ? $attendance->display_status_label
                                : ((($attendance->status ?: 'hadir') === 'izin') ? 'Izin' : 'Presensi Berhasil'))
                            : ($approvedTeachingJournalIzin ? 'Izin (Disetujui)' : 'Belum Presensi'),
                        'is_auto_generated' => (bool) $attendance?->is_auto_generated,
                        'attendance_source' => $attendance?->attendance_source,
                        'event_name' => $attendance?->academicCalendarEvent?->name,
                        'attendance' => $attendance ? $this->serializeTeachingAttendanceEntry($attendance) : null,
                    ];
                })->values(),
                'items' => $items->map(function (TeachingAttendance $item) {
                    return $this->serializeTeachingAttendanceEntry($item);
                })->values(),
            ],
        ]);
    }

    public function checkTeachingJournalLocation(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $validated = $request->validate([
            'teaching_schedule_id' => ['required', 'exists:teaching_schedules,id'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $schedule = TeachingSchedule::query()
            ->with('school')
            ->findOrFail($validated['teaching_schedule_id']);

        $today = Carbon::today('Asia/Jakarta');

        $this->ensureTeachingJournalScheduleAllowed($user, $schedule, $today);

        $calendarEvent = $this->academicCalendarEventService->ensureAutomaticAttendanceForSchedule($schedule, $today);
        if ($calendarEvent) {
            throw ValidationException::withMessages([
                'teaching_schedule_id' => 'Jadwal ini sudah ditandai otomatis sebagai "' . $calendarEvent->resolved_type_label . '" melalui Kalender Akademik.',
            ]);
        }

        $locationState = $this->checkTeachingAttendanceLocationAgainstSchool(
            $schedule,
            (float) $validated['latitude'],
            (float) $validated['longitude'],
        );

        return response()->json([
            'message' => $locationState['message'],
            'data' => [
                'is_within_polygon' => $locationState['is_within_polygon'],
            ],
        ]);
    }

    public function storeTeachingJournalAttendance(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $validated = $request->validate([
            'teaching_schedule_id' => ['required', 'exists:teaching_schedules,id'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'lokasi' => ['nullable', 'string'],
            'accuracy' => ['nullable', 'numeric'],
            'altitude' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
            'device_info' => ['nullable', 'string'],
            'location_readings' => ['nullable'],
            'materi' => ['required', 'string', 'max:1000'],
            'present_students' => ['required', 'integer', 'min:0', 'max:10000'],
            'class_total_students' => ['nullable', 'integer', 'min:1', 'max:10000'],
        ]);

        $validated['materi'] = trim((string) $validated['materi']);
        if ($validated['materi'] === '') {
            throw ValidationException::withMessages([
                'materi' => 'Materi atau topik yang disampaikan wajib diisi.',
            ]);
        }

        $today = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $approvedTeachingJournalIzin = $this->findApprovedTeachingJournalIzin($user, $today);

        if ($approvedTeachingJournalIzin) {
            throw ValidationException::withMessages([
                'attendance' => $this->teachingJournalIzinMessage($approvedTeachingJournalIzin),
            ]);
        }

        $schedule = TeachingSchedule::query()
            ->with('school')
            ->findOrFail($validated['teaching_schedule_id']);

        $this->ensureTeachingJournalScheduleAllowed($user, $schedule, $today);

        $calendarEvent = $this->academicCalendarEventService->ensureAutomaticAttendanceForSchedule($schedule, $today);
        if ($calendarEvent) {
            throw ValidationException::withMessages([
                'attendance' => 'Jadwal ini sudah ditandai otomatis sebagai "' . $calendarEvent->resolved_type_label . '" melalui Kalender Akademik.',
            ]);
        }

        $existingAttendance = TeachingAttendance::query()
            ->where('teaching_schedule_id', $schedule->id)
            ->whereDate('tanggal', $today->toDateString())
            ->first();

        if ($existingAttendance) {
            throw ValidationException::withMessages([
                'attendance' => 'Anda sudah melakukan presensi untuk jadwal ini hari ini.',
            ]);
        }

        $timeState = $this->buildTeachingAttendanceTimeState($schedule, $now);
        if ($timeState['state'] !== 'within') {
            throw ValidationException::withMessages([
                'attendance' => $timeState['message'],
            ]);
        }

        $locationState = $this->checkTeachingAttendanceLocationAgainstSchool(
            $schedule,
            (float) $validated['latitude'],
            (float) $validated['longitude'],
        );

        if (!$locationState['is_within_polygon']) {
            throw ValidationException::withMessages([
                'location' => $locationState['message'],
            ]);
        }

        $locationValidation = $this->validateLocationForFakeGps(
            $validated,
            $user,
            true,
        );

        if ($locationValidation['is_fake']) {
            throw ValidationException::withMessages([
                'location' => $locationValidation['message'],
            ]);
        }

        $classStudentCount = TeachingClassStudentCount::query()
            ->where('school_id', $schedule->school_id)
            ->where('class_name', trim((string) $schedule->class_name))
            ->first();

        if (!$classStudentCount && empty($validated['class_total_students'])) {
            throw ValidationException::withMessages([
                'class_total_students' => 'Jumlah siswa di kelas wajib diisi untuk jadwal ini.',
            ]);
        }

        $presentStudents = (int) $validated['present_students'];
        $classTotalStudents = $classStudentCount
            ? (int) $classStudentCount->total_students
            : (int) $validated['class_total_students'];

        if ($presentStudents > $classTotalStudents) {
            throw ValidationException::withMessages([
                'present_students' => 'Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.',
            ]);
        }

        $studentAttendancePercentage = $classTotalStudents > 0
            ? round(($presentStudents / $classTotalStudents) * 100, 2)
            : 0;

        $attendance = DB::transaction(function () use (
            $classStudentCount,
            $classTotalStudents,
            $locationState,
            $presentStudents,
            $request,
            $schedule,
            $studentAttendancePercentage,
            $today,
            $now,
            $user,
            $validated
        ) {
            if (!$classStudentCount) {
                TeachingClassStudentCount::create([
                    'school_id' => $schedule->school_id,
                    'class_name' => trim((string) $schedule->class_name),
                    'total_students' => $classTotalStudents,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }

            $attendance = TeachingAttendance::create([
                'teaching_schedule_id' => $schedule->id,
                'user_id' => $user->id,
                'tanggal' => $today->toDateString(),
                'waktu' => $now->format('H:i:s'),
                'status' => 'hadir',
                'attendance_source' => TeachingAttendance::SOURCE_MANUAL,
                'is_auto_generated' => false,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'lokasi' => $validated['lokasi'] ?? $locationState['location_label'],
                'materi' => $validated['materi'],
                'class_total_students' => $classTotalStudents,
                'present_students' => $presentStudents,
                'student_attendance_percentage' => $studentAttendancePercentage,
            ]);

            $this->notifyUserWithPush(
                $user,
                'teaching_success',
                'Presensi Mengajar Berhasil',
                'Presensi mengajar berhasil dicatat pada ' . $now->format('H:i') . '.',
                [
                    'attendance_id' => $attendance->id,
                    'schedule_id' => $schedule->id,
                    'tanggal' => $today->toDateString(),
                    'waktu' => $now->format('H:i:s'),
                    'school_name' => $schedule->school?->name,
                    'materi' => $attendance->materi,
                    'class_total_students' => $attendance->class_total_students,
                    'present_students' => $attendance->present_students,
                    'student_attendance_percentage' => $attendance->student_attendance_percentage,
                ]
            );

            return $attendance;
        });

        $attendance->loadMissing('teachingSchedule.school');

        return response()->json([
            'message' => 'Presensi mengajar berhasil dicatat pada ' . $now->format('H:i') . '.',
            'data' => [
                'item' => $this->serializeTeachingAttendanceEntry($attendance),
            ],
        ], 201);
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
                    'ketugasan' => $user->ketugasan,
                    'school_name' => $user->madrasah?->name,
                    'avatar_url' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                ],
                'permissions' => [
                    'can_manage_izin' => $this->canManageIzinRequests($user),
                ],
                'editable_profile' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_hp,
                    'tempat_lahir' => $user->tempat_lahir,
                    'tanggal_lahir' => $user->tanggal_lahir?->format('Y-m-d'),
                    'tmt' => $user->tmt?->format('Y-m-d'),
                    'pendidikan_terakhir' => $user->pendidikan_terakhir,
                    'nip' => $user->nip,
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

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'tmt' => ['nullable', 'date'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_hp = trim((string) ($validated['phone'] ?? '')) ?: null;
        $user->tempat_lahir = trim((string) ($validated['tempat_lahir'] ?? '')) ?: null;
        $user->tanggal_lahir = $validated['tanggal_lahir'] ?? null;
        $user->tmt = $validated['tmt'] ?? null;
        $user->pendidikan_terakhir = trim((string) ($validated['pendidikan_terakhir'] ?? '')) ?: null;
        $user->nip = trim((string) ($validated['nip'] ?? '')) ?: null;
        $user->save();

        $user->refresh();

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_hp,
                    'avatar_url' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                ],
                'editable_profile' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_hp,
                    'tempat_lahir' => $user->tempat_lahir,
                    'tanggal_lahir' => $user->tanggal_lahir?->format('Y-m-d'),
                    'tmt' => $user->tmt?->format('Y-m-d'),
                    'pendidikan_terakhir' => $user->pendidikan_terakhir,
                    'nip' => $user->nip,
                    'avatar_url' => $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null,
                ],
            ],
        ]);
    }

    public function updateProfileAvatar(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ]);

        $file = $request->file('avatar');
        abort_unless($file !== null, 422);

        $this->deletePublicStoredFile($user->avatar);
        $path = $this->storeUploadedFileForWeb($file, 'avatars');

        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto profil berhasil diperbarui.',
            'data' => [
                'avatar_url' => asset('storage/' . ltrim($path, '/')),
            ],
        ]);
    }

    public function downloadIzinAttachment(Request $request, Izin $izin): BinaryFileResponse
    {
        $user = $this->resolveTeacher($request);
        $izin->loadMissing('user');

        $canAccessOwnAttachment = (int) $izin->user_id === (int) $user->id;
        $canAccessManagedAttachment = $this->canManageIzinRequests($user)
            && (int) optional($izin->user)->madrasah_id === (int) $user->madrasah_id;

        abort_unless($canAccessOwnAttachment || $canAccessManagedAttachment, 403);
        abort_unless($izin->file_path, 404);

        $filePath = $this->resolvePublicStoredFilePath($izin->file_path);
        abort_unless($filePath !== null, 404);

        return response()->download(
            $filePath,
            $izin->file_name ?: basename($filePath)
        );
    }

    public function updateProfilePassword(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
                'confirmed',
            ],
        ], [
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.regex' => 'Password baru harus mengandung huruf kecil, huruf besar, angka, dan simbol (@$!%*?&).',
        ]);

        if (!Hash::check($validated['current_password'], (string) $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->password = Hash::make($validated['password']);
        if (isset($user->password_changed)) {
            $user->password_changed = true;
        }
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui.',
            'data' => [
                'password_changed' => (bool) $user->password_changed,
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
                'menu' => $this->izinMenu($user),
                'form_meta' => [
                    'external_school_name' => $user->madrasahTambahan?->name,
                    'can_apply_external_teaching' => ExternalTeachingPermissionService::isEligibleUser($user),
                    'day_options' => $this->izinDayOptions(),
                ],
                'permissions' => [
                    'can_manage_izin' => $this->canManageIzinRequests($user),
                ],
                'summary' => [
                    'pending' => $items->where('status', 'pending')->count(),
                    'approved' => $items->where('status', 'approved')->count(),
                    'rejected' => $items->where('status', 'rejected')->count(),
                ],
                'items' => $items->map(fn (Izin $izin) => $this->serializeIzin($izin))->values(),
            ],
        ]);
    }

    public function storeIzin(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $type = strtolower(trim((string) $request->input('type')));

        if ($type === '') {
            throw ValidationException::withMessages([
                'type' => 'Tipe izin tidak diketahui.',
            ]);
        }

        $this->ensureIzinCanBeSubmitted($user, $type);
        $izinData = $this->buildIzinPayload($request, $user, $type);

        $izin = Izin::create(array_merge($izinData, [
            'user_id' => $user->id,
            'type' => $type,
            'status' => 'pending',
        ]));

        $this->notifyUserWithPush(
            $user,
            'izin_submitted',
            'Izin Diajukan',
            'Pengajuan izin Anda telah dikirim dan menunggu persetujuan.',
            [
                'izin_id' => $izin->id,
                'tanggal' => optional($izin->tanggal)->format('Y-m-d'),
                'type' => $izin->type,
            ]
        );

        $this->notifyApprovalManagersAboutIncomingIzin($user, $izin);

        return response()->json([
            'message' => 'Izin berhasil diajukan dan menunggu persetujuan.',
            'data' => [
                'item' => $this->serializeIzin($izin),
            ],
        ]);
    }

    public function updateIzin(Request $request, Izin $izin): JsonResponse
    {
        $user = $this->resolveTeacher($request);

        if ((int) $izin->user_id !== (int) $user->id) {
            abort(404);
        }

        if ($izin->status !== 'pending') {
            throw ValidationException::withMessages([
                'izin' => 'Izin yang sudah diproses tidak dapat diubah lagi.',
            ]);
        }

        $type = strtolower(trim((string) $request->input('type', $izin->type)));
        if ($type !== (string) $izin->type) {
            throw ValidationException::withMessages([
                'type' => 'Jenis izin tidak dapat diubah dari form edit.',
            ]);
        }

        $this->ensureIzinCanBeSubmitted($user, $type, $izin);
        $izinData = $this->buildIzinPayload($request, $user, $type, $izin);
        $izinData = $this->mergeUpdatedIzinAttachment($izin, $izinData);

        $izin->update($izinData);

        return response()->json([
            'message' => 'Izin berhasil diperbarui.',
            'data' => [
                'item' => $this->serializeIzin($izin->fresh()),
            ],
        ]);
    }

    public function attendanceReports(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $scope = $this->normalizeReportScope($request->query('scope'));
        $month = $this->resolveReportMonth($request->query('month'));
        $targetTeacher = $this->resolveReportTeacher(
            $user,
            $request->query('teacher_id')
        );

        [$attendanceSummary, $attendanceRecords, $attendanceRange] = $this->buildAttendanceReportPayload(
            $targetTeacher,
            $scope,
            $month
        );
        [$teachingSummary, $teachingRecords, $teachingRange] = $this->buildTeachingReportPayload(
            $targetTeacher,
            $scope,
            $month
        );

        return response()->json([
            'message' => 'OK',
            'data' => [
                'permissions' => [
                    'can_select_teacher' => $this->canManageIzinRequests($user),
                ],
                'filters' => [
                    'scope' => $scope,
                    'month' => $month->format('Y-m'),
                    'selected_teacher_id' => $targetTeacher->id,
                ],
                'selected_teacher' => [
                    'id' => $targetTeacher->id,
                    'name' => $targetTeacher->name,
                    'school_name' => $targetTeacher->madrasah?->name ?? '-',
                    'ketugasan' => $targetTeacher->ketugasan,
                ],
                'teacher_options' => $this->reportTeacherOptions($user),
                'attendance' => [
                    'summary' => $attendanceSummary,
                    'period_label' => $attendanceRange['label'],
                    'records' => $attendanceRecords,
                ],
                'teaching' => [
                    'summary' => $teachingSummary,
                    'period_label' => $teachingRange['label'],
                    'records' => $teachingRecords,
                ],
            ],
        ]);
    }

    public function exportAttendanceReport(Request $request)
    {
        $user = $this->resolveTeacher($request);
        $scope = $this->normalizeReportScope($request->query('scope'));
        $month = $this->resolveReportMonth($request->query('month'));
        $targetTeacher = $this->resolveReportTeacher(
            $user,
            $request->query('teacher_id')
        );

        [$summary, $records, $range] = $this->buildAttendanceReportPayload(
            $targetTeacher,
            $scope,
            $month,
            true
        );

        $filename = 'rekap-presensi-kehadiran-' . $targetTeacher->id . '-' . ($scope === 'all' ? 'keseluruhan' : $month->format('Y-m')) . '.pdf';

        $pdf = Pdf::loadView('pdf.mobile-presensi-rekap', [
            'user' => $targetTeacher,
            'type' => $scope,
            'summary' => $summary,
            'records' => collect($records)->map(function (array $item) {
                return (object) [
                    'tanggal' => Carbon::parse($item['date'], 'Asia/Jakarta'),
                    'model_type' => $item['record_type'] === 'izin' ? 'izin' : 'presensi',
                    'status' => $item['status'],
                    'waktu_masuk' => $item['check_in'],
                    'waktu_keluar' => $item['check_out'],
                    'deskripsi_tugas' => $item['description'],
                    'alasan' => $item['reason'],
                    'keterangan' => $item['note'],
                ];
            }),
            'startDate' => $range['start'],
            'endDate' => $range['end'],
        ])->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    public function exportTeachingReport(Request $request)
    {
        $user = $this->resolveTeacher($request);
        $scope = $this->normalizeReportScope($request->query('scope'));
        $month = $this->resolveReportMonth($request->query('month'));
        $targetTeacher = $this->resolveReportTeacher(
            $user,
            $request->query('teacher_id')
        );

        [$summary, $records, $range] = $this->buildTeachingReportPayload(
            $targetTeacher,
            $scope,
            $month
        );

        $filename = 'rekap-presensi-mengajar-' . $targetTeacher->id . '-' . ($scope === 'all' ? 'keseluruhan' : $month->format('Y-m')) . '.pdf';

        $pdf = Pdf::loadView('pdf.mobile-teaching-attendance-rekap', [
            'user' => $targetTeacher,
            'summary' => $summary,
            'records' => $records,
            'scope' => $scope,
            'startDate' => $range['start'],
            'endDate' => $range['end'],
            'periodLabel' => $range['label'],
        ])->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    public function staffAttendanceMonitor(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $this->ensureCanManageIzinRequests($user);

        $selectedDate = $request->filled('date')
            ? Carbon::parse((string) $request->query('date'), 'Asia/Jakarta')->startOfDay()
            : Carbon::today('Asia/Jakarta');

        $teachers = User::query()
            ->with(['madrasah'])
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->orderBy('name')
            ->get();

        $attendanceRecords = Presensi::query()
            ->whereIn('user_id', $teachers->pluck('id'))
            ->whereDate('tanggal', $selectedDate->toDateString())
            ->get()
            ->groupBy('user_id');

        $dayName = $selectedDate->locale('id')->dayName;
        $teachingSchedules = TeachingSchedule::query()
            ->whereIn('teacher_id', $teachers->pluck('id'))
            ->whereRaw('LOWER(day) = ?', [strtolower((string) $dayName)])
            ->get()
            ->groupBy('teacher_id');
        $teachingAttendances = TeachingAttendance::query()
            ->whereIn('user_id', $teachers->pluck('id'))
            ->whereDate('tanggal', $selectedDate->toDateString())
            ->get()
            ->groupBy('user_id');

        $items = $teachers->map(function (User $teacher) use (
            $attendanceRecords,
            $selectedDate,
            $teachingSchedules,
            $teachingAttendances
        ) {
            ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate(
                $teacher,
                $selectedDate
            );

            $records = collect($attendanceRecords->get($teacher->id, collect()));
            $hadir = $records->where('status', 'hadir');
            $izin = $records->where('status', 'izin')->where('status_izin', 'approved');
            $alpha = $records->where('status', 'alpha');
            $externalIzin = ExternalTeachingPermissionService::approvedRequestForDate($teacher, $selectedDate);

            $status = 'belum_presensi';
            $statusLabel = 'Belum presensi';
            if ($hadir->isNotEmpty()) {
                $status = 'hadir';
                $statusLabel = 'Hadir';
            } elseif ($izin->isNotEmpty() || $externalIzin) {
                $status = 'izin';
                $statusLabel = 'Izin';
            } elseif ($alpha->isNotEmpty()) {
                $status = 'alpha';
                $statusLabel = 'Alpha';
            }

            $todaySchedules = collect($teachingSchedules->get($teacher->id, collect()));
            $todayTeachingAttendances = collect($teachingAttendances->get($teacher->id, collect()));

            return [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->name,
                'ketugasan' => $teacher->ketugasan,
                'status' => $status,
                'status_label' => $statusLabel,
                'check_in' => $this->formatTimeValue($hadir->sortByDesc('waktu_masuk')->first()?->waktu_masuk),
                'check_out' => $this->formatTimeValue($hadir->sortByDesc('waktu_keluar')->first()?->waktu_keluar),
                'note' => $records->pluck('keterangan')->filter()->implode(' | '),
                'teaching_total' => $todaySchedules->count(),
                'teaching_completed' => $todayTeachingAttendances->count(),
            ];
        })->values();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'today_label' => $selectedDate->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'selected_date' => $selectedDate->format('Y-m-d'),
                'summary' => [
                    'total_teacher' => $items->count(),
                    'hadir' => $items->where('status', 'hadir')->count(),
                    'izin' => $items->where('status', 'izin')->count(),
                    'belum_presensi' => $items->where('status', 'belum_presensi')->count() + $items->where('status', 'alpha')->count(),
                ],
                'teacher_options' => $teachers->map(fn (User $teacher) => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'ketugasan' => $teacher->ketugasan,
                ])->values(),
                'items' => $items,
            ],
        ]);
    }

    public function manageIzin(Request $request): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $this->ensureCanManageIzinRequests($user);

        $status = strtolower(trim((string) $request->query('status', 'pending')));
        if (!in_array($status, ['pending', 'approved', 'rejected', 'all'], true)) {
            $status = 'pending';
        }

        $query = Izin::query()
            ->with('user')
            ->whereHas('user', function ($builder) use ($user) {
                $builder->where('madrasah_id', $user->madrasah_id);
            });

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $items = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'permissions' => [
                    'can_manage_izin' => true,
                ],
                'summary' => [
                    'pending' => Izin::query()
                        ->whereHas('user', function ($builder) use ($user) {
                            $builder->where('madrasah_id', $user->madrasah_id);
                        })
                        ->where('status', 'pending')
                        ->count(),
                    'approved' => Izin::query()
                        ->whereHas('user', function ($builder) use ($user) {
                            $builder->where('madrasah_id', $user->madrasah_id);
                        })
                        ->where('status', 'approved')
                        ->count(),
                    'rejected' => Izin::query()
                        ->whereHas('user', function ($builder) use ($user) {
                            $builder->where('madrasah_id', $user->madrasah_id);
                        })
                        ->where('status', 'rejected')
                        ->count(),
                ],
                'current_filter' => $status,
                'items' => $items->map(fn (Izin $izin) => $this->serializeManagedIzin($izin))->values(),
            ],
        ]);
    }

    public function approveIzin(Request $request, Izin $izin): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $this->ensureCanManageIzin($user, $izin);

        if ($izin->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan ini sudah diproses sebelumnya.',
            ]);
        }

        $validated = $request->validate([
            'approval_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $izin->status = 'approved';
        $izin->approved_by = $user->id;
        $izin->approved_at = now();
        $izin->approval_notes = trim((string) ($validated['approval_notes'] ?? '')) ?: null;
        $izin->save();

        $this->syncApprovedIzinAfterApproval($izin);

        $this->notifyUserIdWithPush(
            $izin->user_id,
            'izin_approved',
            'Izin Disetujui',
            'Pengajuan izin Anda pada tanggal ' . optional($izin->tanggal)->format('d F Y') . ' telah disetujui.',
            [
                'izin_id' => $izin->id,
                'tanggal' => $izin->tanggal,
                'approved_by' => $user->name,
            ]
        );

        return response()->json([
            'message' => 'Izin berhasil disetujui.',
            'data' => [
                'item' => $this->serializeManagedIzin($izin->fresh(['user', 'approver'])),
            ],
        ]);
    }

    public function rejectIzin(Request $request, Izin $izin): JsonResponse
    {
        $user = $this->resolveTeacher($request);
        $this->ensureCanManageIzin($user, $izin);

        if ($izin->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan ini sudah diproses sebelumnya.',
            ]);
        }

        $validated = $request->validate([
            'approval_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $izin->status = 'rejected';
        $izin->approved_by = $user->id;
        $izin->approved_at = now();
        $izin->approval_notes = trim((string) ($validated['approval_notes'] ?? '')) ?: null;
        $izin->save();

        $this->notifyUserIdWithPush(
            $izin->user_id,
            'izin_rejected',
            'Izin Ditolak',
            'Pengajuan izin Anda pada tanggal ' . optional($izin->tanggal)->format('d F Y') . ' telah ditolak.',
            [
                'izin_id' => $izin->id,
                'tanggal' => $izin->tanggal,
                'rejected_by' => $user->name,
            ]
        );

        return response()->json([
            'message' => 'Izin berhasil ditolak.',
            'data' => [
                'item' => $this->serializeManagedIzin($izin->fresh(['user', 'approver'])),
            ],
        ]);
    }

    private function resolveTeacher(Request $request): User
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($user && $user->role === 'tenaga_pendidik', 403);

        return $user->loadMissing(['madrasah', 'madrasahTambahan', 'statusKepegawaian']);
    }

    private function validateSchedulePayload(Request $request): array
    {
        $validated = $request->validate([
            'day' => ['required', Rule::in(self::SCHEDULE_DAYS)],
            'subject' => ['required', 'string', 'max:255'],
            'subject_new' => ['nullable', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'class_name_new' => ['nullable', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $validated['subject'] = trim((string) $validated['subject']);
        $validated['class_name'] = trim((string) $validated['class_name']);
        $validated['subject_new'] = trim((string) ($validated['subject_new'] ?? ''));
        $validated['class_name_new'] = trim((string) ($validated['class_name_new'] ?? ''));

        if ($validated['subject'] === self::SCHEDULE_NEW_VALUE) {
            if ($validated['subject_new'] === '') {
                throw ValidationException::withMessages([
                    'subject_new' => 'Mata pelajaran baru wajib diisi.',
                ]);
            }

            $validated['subject'] = $validated['subject_new'];
        }

        if ($validated['class_name'] === self::SCHEDULE_NEW_VALUE) {
            if ($validated['class_name_new'] === '') {
                throw ValidationException::withMessages([
                    'class_name_new' => 'Kelas baru wajib diisi.',
                ]);
            }

            $validated['class_name'] = $validated['class_name_new'];
        }

        return $validated;
    }

    private function ensureScheduleDoesNotOverlap(User $user, array $validated, ?int $exceptId = null): void
    {
        $query = TeachingSchedule::query()
            ->where('teacher_id', $user->id)
            ->where('day', $validated['day'])
            ->where(function ($builder) use ($validated) {
                $builder->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            });

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'overlap' => 'Jadwal bentrok dengan jadwal Anda sendiri pada hari yang sama.',
            ]);
        }
    }

    private function getScheduleClasses(int|string $schoolId)
    {
        return TeachingSchedule::query()
            ->where('school_id', $schoolId)
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->select('class_name')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');
    }

    private function getScheduleSubjects(int|string $schoolId)
    {
        return TeachingSchedule::query()
            ->where('school_id', $schoolId)
            ->whereNotNull('subject')
            ->where('subject', '!=', '')
            ->select('subject')
            ->distinct()
            ->orderBy('subject')
            ->pluck('subject');
    }

    private function serializeSchedule(TeachingSchedule $schedule): array
    {
        return [
            'id' => $schedule->id,
            'day' => $schedule->day,
            'subject' => $schedule->subject,
            'class_name' => $schedule->class_name,
            'school_name' => $schedule->school?->name,
            'start_time' => $this->formatTime($schedule->start_time),
            'end_time' => $this->formatTime($schedule->end_time),
        ];
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

    private function attachTeachingClassStudentCounts($schedules): void
    {
        $schoolIds = $schedules->pluck('school_id')->filter()->unique()->values();
        $classNames = $schedules->pluck('class_name')
            ->map(fn ($className) => trim((string) $className))
            ->filter()
            ->unique()
            ->values();

        if ($schoolIds->isEmpty() || $classNames->isEmpty()) {
            return;
        }

        $counts = TeachingClassStudentCount::query()
            ->whereIn('school_id', $schoolIds)
            ->whereIn('class_name', $classNames)
            ->get()
            ->keyBy(fn ($count) => $this->teachingClassStudentCountKey($count->school_id, $count->class_name));

        $schedules->each(function (TeachingSchedule $schedule) use ($counts) {
            $schedule->class_student_count = $counts->get(
                $this->teachingClassStudentCountKey($schedule->school_id, $schedule->class_name)
            );
        });
    }

    private function teachingClassStudentCountKey($schoolId, $className): string
    {
        return $schoolId . '|' . strtolower(trim((string) $className));
    }

    private function buildTeachingAttendanceTimeState(TeachingSchedule $schedule, Carbon $now): array
    {
        $startTime = Carbon::createFromTimeString((string) $schedule->start_time, 'Asia/Jakarta');
        $endTime = Carbon::createFromTimeString((string) $schedule->end_time, 'Asia/Jakarta');

        if ($now->between($startTime, $endTime)) {
            return [
                'state' => 'within',
                'message' => 'Presensi mengajar tersedia untuk jadwal ini.',
            ];
        }

        if ($now->lt($startTime)) {
            return [
                'state' => 'before',
                'message' => 'Presensi mengajar belum dapat dilakukan. Waktu mengajar dimulai pada ' . $this->formatTime($schedule->start_time) . '.',
            ];
        }

        return [
            'state' => 'after',
            'message' => 'Waktu presensi harus dilakukan dalam rentang waktu mengajar (' . $this->formatTime($schedule->start_time) . ' - ' . $this->formatTime($schedule->end_time) . ').',
        ];
    }

    private function ensureTeachingJournalScheduleAllowed(User $user, TeachingSchedule $schedule, Carbon $today): void
    {
        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $todayName = $today->copy()->locale('id')->dayName;
        if (strtolower((string) $schedule->day) !== strtolower((string) $todayName)) {
            throw ValidationException::withMessages([
                'teaching_schedule_id' => 'Presensi mengajar hanya dapat dilakukan untuk jadwal hari ini.',
            ]);
        }
    }

    private function checkTeachingAttendanceLocationAgainstSchool(
        TeachingSchedule $schedule,
        float $latitude,
        float $longitude,
    ): array {
        $madrasah = $schedule->school;
        $polygonsToCheck = [];

        if ($madrasah && $madrasah->polygon_koordinat) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat;
        }
        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat_2;
        }

        foreach ($polygonsToCheck as $polygonJson) {
            try {
                $polygonGeometry = json_decode($polygonJson, true);
                if (isset($polygonGeometry['coordinates'][0])) {
                    $polygon = $polygonGeometry['coordinates'][0];
                    if ($this->isPointInPolygon([$longitude, $latitude], $polygon)) {
                        return [
                            'is_within_polygon' => true,
                            'message' => 'Lokasi berada dalam area sekolah.',
                            'location_label' => $madrasah?->name ?? 'Presensi Mengajar',
                        ];
                    }
                }
            } catch (\Throwable $exception) {
                continue;
            }
        }

        return [
            'is_within_polygon' => false,
            'message' => empty($polygonsToCheck)
                ? 'Madrasah belum memiliki polygon koordinat yang ditentukan.'
                : 'Lokasi Anda berada di luar area sekolah yang telah ditentukan. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.',
            'location_label' => $madrasah?->name ?? 'Presensi Mengajar',
        ];
    }

    private function serializeTeachingAttendanceEntry(TeachingAttendance $item): array
    {
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
            'status' => $item->status ?? 'hadir',
            'status_label' => $item->display_status_label,
            'is_auto_generated' => $item->is_auto_generated,
            'attendance_source' => $item->attendance_source,
            'event_name' => $item->academicCalendarEvent?->name,
            'materi' => $item->materi ?: 'Belum ada materi tercatat.',
            'present_students' => $item->present_students,
            'class_total_students' => $item->class_total_students,
            'student_attendance_percentage' => $item->student_attendance_percentage,
            'location' => $item->lokasi,
        ];
    }

    private function calculateDashboardAttendanceStats(User $user, $items, Carbon $today): array
    {
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        $effectiveEnd = $today->copy()->min($monthEnd);
        $hariKbm = (string) ($user->madrasah?->hari_kbm ?? '6');
        $recordsByDate = collect($items)->groupBy(fn ($item) => $item->tanggal->toDateString());
        $workingDays = 0;
        $presentCount = 0;
        $izinCount = 0;
        $alphaCount = 0;

        foreach (CarbonPeriod::create($monthStart, $effectiveEnd) as $date) {
            if (!$this->isReportWorkingDay($date, $hariKbm)) {
                continue;
            }

            $workingDays++;
            $dayItems = $recordsByDate->get($date->toDateString(), collect());

            if ($dayItems->where('status', 'hadir')->isNotEmpty()) {
                $presentCount++;
            } elseif ($dayItems->where('status', 'izin')->isNotEmpty()) {
                $izinCount++;
            } elseif ($dayItems->where('status', 'alpha')->isNotEmpty()) {
                $alphaCount++;
            } else {
                $alphaCount++;
            }
        }

        return [
            'attendance_percent' => $workingDays > 0
                ? round(($presentCount / $workingDays) * 100, 1)
                : 0,
            'present_count' => $presentCount,
            'izin_count' => $izinCount,
            'alpha_count' => $alphaCount,
            'working_days' => $workingDays,
            'hari_kbm' => (int) $hariKbm,
            'attendance_basis_label' => $workingDays > 0
                ? 'Dihitung dari ' . $workingDays . ' hari kerja bulan ini • KBM ' . $hariKbm . ' hari'
                : 'Belum ada hari kerja bulan ini • KBM ' . $hariKbm . ' hari',
        ];
    }

    private function serializeTodayAttendance($items): array
    {
        $checkIn = $items->filter(fn ($item) => !empty($item->waktu_masuk))->sortBy('waktu_masuk')->first();
        $checkOut = $items->filter(fn ($item) => !empty($item->waktu_keluar))->sortByDesc('waktu_keluar')->first();
        $referenceItem = $items->first();
        $autoPresentIzin = $referenceItem
            ? $this->findApprovedAutoPresentIzin($referenceItem->user_id, $referenceItem->tanggal)
            : null;

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
            'status_label' => $this->attendanceSummaryStatusLabel($status, $autoPresentIzin !== null),
            'is_auto_present' => $autoPresentIzin !== null,
            'check_in' => $this->formatTimeValue($checkIn?->waktu_masuk),
            'check_out' => $this->formatTimeValue($checkOut?->waktu_keluar),
            'location' => $checkIn?->lokasi ?: $checkIn?->madrasah?->name,
            // 'location_out' => $checkOut?->lokasi_keluar,
            'entries' => $items->map(function (Presensi $item) {
                $entryAutoPresentIzin = $this->findApprovedAutoPresentIzin($item->user_id, $item->tanggal);

                return [
                    'id' => $item->id,
                    'status' => $item->status,
                    'status_label' => $this->presensiStatusLabel($item, $entryAutoPresentIzin),
                    'is_auto_present' => $entryAutoPresentIzin !== null,
                    'school_name' => $item->madrasah?->name,
                    'check_in' => $this->formatTimeValue($item->waktu_masuk),
                    'check_out' => $this->formatTimeValue($item->waktu_keluar),
                    'location' => $item->lokasi,
                    // 'location_out' => $item->lokasi_keluar,
                    'note' => $item->keterangan,
                    'selfie_in_url' => $item->selfie_masuk_path ? asset('storage/' . ltrim($item->selfie_masuk_path, '/')) : null,
                    'selfie_out_url' => $item->selfie_keluar_path ? asset('storage/' . ltrim($item->selfie_keluar_path, '/')) : null,
                ];
            })->values(),
        ];
    }

    private function buildTodayAttendanceState(
        $items,
        ?Izin $approvedBlockingIzin = null,
        ?Holiday $holiday = null,
        bool $isSunday = false,
    ): array {
        if ($items->isNotEmpty()) {
            return $this->serializeTodayAttendance($items);
        }

        if ($approvedBlockingIzin) {
            $izinLabel = ucfirst(str_replace('_', ' ', (string) $approvedBlockingIzin->type));

            return [
                'status' => 'izin',
                'status_label' => 'Tercatat izin',
                'check_in' => null,
                'check_out' => null,
                'location' => null,
                'location_out' => null,
                'entries' => [],
                'note' => $izinLabel . ' disetujui untuk hari ini.',
            ];
        }

        if ($holiday) {
            return [
                'status' => 'libur',
                'status_label' => 'Hari libur',
                'check_in' => null,
                'check_out' => null,
                'location' => null,
                'location_out' => null,
                'entries' => [],
                'note' => $holiday->name,
            ];
        }

        if ($isSunday) {
            return [
                'status' => 'libur',
                'status_label' => 'Hari Minggu',
                'check_in' => null,
                'check_out' => null,
                'location' => null,
                'location_out' => null,
                'entries' => [],
                'note' => 'Presensi tidak tersedia pada hari Minggu.',
            ];
        }

        return [
            'status' => 'belum_presensi',
            'status_label' => 'Belum presensi',
            'check_in' => null,
            'check_out' => null,
            'location' => null,
            'location_out' => null,
            'entries' => [],
            'note' => null,
        ];
    }

    private function buildAttendanceFormState(
        User $user,
        Carbon $today,
        $todayPresensi,
        ?Holiday $holiday,
        bool $isSunday,
        ?Izin $approvedBlockingIzin,
        bool $pendingLatePermit,
    ): array {
        $blockedMessage = null;
        $nextMode = null;
        $nextModeLabel = null;

        $hadirRecords = $todayPresensi->where('status', 'hadir');
        $openRecord = $hadirRecords
            ->filter(fn (Presensi $item) => !empty($item->waktu_masuk) && empty($item->waktu_keluar))
            ->sortByDesc('waktu_masuk')
            ->first();
        $completedToday = $hadirRecords
            ->contains(fn (Presensi $item) => !empty($item->waktu_masuk) && !empty($item->waktu_keluar));

        if ($holiday) {
            $blockedMessage = 'Hari ini libur: ' . $holiday->name . '.';
        } elseif ($isSunday) {
            $blockedMessage = 'Presensi tidak tersedia pada hari Minggu.';
        } elseif ($approvedBlockingIzin) {
            $blockedMessage = ucfirst(str_replace('_', ' ', (string) $approvedBlockingIzin->type)) . ' Anda sudah disetujui untuk hari ini.';
        } elseif ($pendingLatePermit) {
            $blockedMessage = 'Izin terlambat sedang menunggu persetujuan. Presensi belum dapat dilakukan.';
        } elseif ($completedToday) {
            $blockedMessage = 'Presensi hari ini sudah lengkap.';
        } elseif ($todayPresensi->where('status', 'alpha')->isNotEmpty()) {
            $blockedMessage = 'Hari ini sudah tercatat sebagai alpha.';
        } elseif ($todayPresensi->where('status', 'izin')->isNotEmpty() && $hadirRecords->isEmpty()) {
            $blockedMessage = 'Hari ini sudah tercatat sebagai izin.';
        } elseif ($openRecord) {
            $nextMode = 'keluar';
            $nextModeLabel = 'Presensi Keluar';
        } else {
            $nextMode = 'masuk';
            $nextModeLabel = 'Presensi Masuk';
        }

        return [
            'can_submit' => $nextMode !== null && $blockedMessage === null,
            'next_mode' => $nextMode,
            'next_mode_label' => $nextModeLabel,
            'blocked_message' => $blockedMessage,
            'is_holiday' => (bool) $holiday,
            'holiday_name' => $holiday?->name,
            'pending_late_permit' => $pendingLatePermit,
            'guidance' => [
                'Aktifkan GPS dan ambil lokasi terbaru sebelum submit.',
                'Ambil selfie langsung dari kamera depan saat presensi.',
                'Mode presensi akan mengikuti status hari ini secara otomatis.',
            ],
            'school_name' => $user->madrasah?->name ?? '-',
            'today_label' => $today->locale('id')->isoFormat('dddd, D MMMM YYYY'),
        ];
    }

    private function buildPresensiTimeRanges(User $user, Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek;
        $school = $user->madrasah;
        $masukStart = $school?->presensi_masuk_start ?? '00:01';
        $masukEnd = $school?->presensi_masuk_end;
        $pulangEnd = $school?->presensi_pulang_end ?? '23:59';

        if ($dayOfWeek === 5 && $school?->presensi_pulang_jumat) {
            $pulangStart = $school->presensi_pulang_jumat;
        } elseif ($dayOfWeek === 6 && $school?->presensi_pulang_sabtu) {
            $pulangStart = $school->presensi_pulang_sabtu;
        } elseif ($school?->presensi_pulang_start) {
            $pulangStart = $school->presensi_pulang_start;
        } else {
            $hariKbm = (string) ($school?->hari_kbm ?? '');
            if ($hariKbm === '5') {
                $pulangStart = $dayOfWeek === 5 ? '11:15' : '13:35';
            } elseif ($hariKbm === '6') {
                $pulangStart = $dayOfWeek === 5
                    ? '13:00'
                    : ($dayOfWeek === 6 ? '12:00' : '14:00');
            } else {
                $pulangStart = '15:00';
            }
        }

        return [
            'masuk_start' => $this->formatTimeValue($masukStart),
            'masuk_end' => $this->formatTimeValue($masukEnd),
            'pulang_start' => $this->formatTimeValue($pulangStart),
            'pulang_end' => $this->formatTimeValue($pulangEnd),
        ];
    }

    private function ensureAttendanceTimeAllowed(
        User $user,
        string $mode,
        Carbon $now,
        ?Presensi $existingPresensi,
        array $validated,
    ): void {
        if ($user->ketugasan !== 'penjaga sekolah') {
            $endOfDayCutoff = $this->normalizeTimeValue($user->madrasah?->presensi_pulang_end ?? '23:59:59');
            if ($mode === 'masuk' && $now->format('H:i:s') > $endOfDayCutoff) {
                if (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($user, $now)) {
                    ExternalTeachingPermissionService::createOrUpdateNoPresenceRecord($user, $now);
                }

                if (!$existingPresensi) {
                    Presensi::create($this->filterPresensiAttributes([
                        'user_id' => $user->id,
                        'tanggal' => $now->toDateString(),
                        'status' => 'alpha',
                        'keterangan' => 'Tidak masuk',
                        'latitude' => $validated['latitude'],
                        'longitude' => $validated['longitude'],
                        'lokasi' => $validated['lokasi'] ?? null,
                        'accuracy' => $validated['accuracy'] ?? null,
                        'altitude' => $validated['altitude'] ?? null,
                        'speed' => $validated['speed'] ?? null,
                        'device_info' => $validated['device_info'] ?? null,
                        'location_readings' => $this->normalizeLocationReadings($validated['location_readings'] ?? null),
                        'status_kepegawaian_id' => $user->status_kepegawaian_id,
                    ]));
                }

                throw ValidationException::withMessages([
                    'attendance' => 'Presensi setelah batas waktu otomatis dicatat sebagai tidak masuk.',
                ]);
            }
        }

        if ($mode !== 'masuk' || $user->ketugasan === 'penjaga sekolah') {
            return;
        }

        $minTimeMasuk = $this->normalizeTimeValue($user->madrasah?->presensi_masuk_start ?? '00:01:00');
        if ($now->format('H:i:s') < $minTimeMasuk) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi masuk belum dapat dilakukan. Waktu presensi dimulai pukul ' . substr($minTimeMasuk, 0, 5) . '.',
            ]);
        }
    }

    private function determineAttendanceMadrasahId(User $user, float $latitude, float $longitude): ?int
    {
        $candidates = [];

        if ($user->pemenuhan_beban_kerja_lain && $user->madrasahTambahan) {
            $candidates[] = ['id' => $user->madrasah_id_tambahan, 'school' => $user->madrasahTambahan];
        }

        if ($user->madrasah) {
            $candidates[] = ['id' => $user->madrasah_id, 'school' => $user->madrasah];
        }

        foreach ($candidates as $candidate) {
            $school = $candidate['school'];
            $polygons = [];

            if (!empty($school?->polygon_koordinat)) {
                $polygons[] = $school->polygon_koordinat;
            }

            if (!empty($school?->enable_dual_polygon) && !empty($school?->polygon_koordinat_2)) {
                $polygons[] = $school->polygon_koordinat_2;
            }

            foreach ($polygons as $polygonJson) {
                try {
                    $geometry = json_decode($polygonJson, true, 512, JSON_THROW_ON_ERROR);
                    $points = $geometry['coordinates'][0] ?? null;
                    if (is_array($points) && $this->isPointInPolygon([$longitude, $latitude], $points)) {
                        return (int) $candidate['id'];
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }

        return null;
    }

    private function buildCheckInNote(User $user, Carbon $tanggal, Carbon $now): string
    {
        $approvedLatePermit = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal->toDateString())
            ->where('status', 'izin')
            ->where('status_izin', 'approved')
            ->where('keterangan', 'like', '%terlambat%')
            ->first();

        if ($approvedLatePermit) {
            return 'terlambat sudah izin';
        }

        if ($now->format('H:i:s') > '07:00:00') {
            $batas = Carbon::createFromFormat('H:i:s', '07:00:00', 'Asia/Jakarta');
            $terlambatMenit = abs((int) round($now->floatDiffInMinutes($batas)));
            return 'Terlambat ' . $terlambatMenit . ' menit';
        }

        return 'tidak terlambat';
    }

    private function isEarlyCheckout(User $user, Carbon $now): bool
    {
        if ($user->ketugasan === 'penjaga sekolah' || $user->pemenuhan_beban_kerja_lain) {
            return false;
        }

        $pulangStart = $this->normalizeTimeValue(
            $this->buildPresensiTimeRanges($user, $now)['pulang_start'] ?? '15:00',
        );

        return $now->format('H:i:s') < $pulangStart;
    }

    private function normalizeTimeValue($value): string
    {
        $time = trim((string) $value);
        if ($time === '') {
            return '00:00:00';
        }

        return strlen($time) === 5 ? $time . ':00' : $time;
    }

    private function formatTimeValue($value): ?string
    {
        $time = trim((string) $value);
        if ($time === '') {
            return null;
        }

        return substr($this->normalizeTimeValue($time), 0, 5);
    }

    private function validateLocationForFakeGps(array $payload, User $user, bool $isPresensiMasuk): array
    {
        $analysis = [
            'accuracy_check' => false,
            'consistency_check' => false,
            'speed_check' => false,
            'location_history_check' => false,
            'suspicious_indicators' => [],
        ];

        $isFake = false;
        $messages = [];
        $accuracy = isset($payload['accuracy']) ? (float) $payload['accuracy'] : null;

        if ($accuracy !== null && $accuracy > 0 && $accuracy < 3) {
            $analysis['accuracy_check'] = true;
            $analysis['suspicious_indicators'][] = 'accuracy_too_perfect';
            $isFake = true;
            $messages[] = 'Akurasi GPS terlalu sempurna (Terindikasi Lokasi Palsu)';
        }

        if (!empty($payload['location_readings'])) {
            $consistency = $this->validateLocationConsistency($payload['location_readings']);
            if (!$consistency['valid']) {
                $analysis['consistency_check'] = true;
                $analysis['suspicious_indicators'][] = 'location_consistency';
                $isFake = true;
                $messages[] = $consistency['message'];
            }
        }

        $latitude = (float) $payload['latitude'];
        $longitude = (float) $payload['longitude'];

        if ($isPresensiMasuk) {
            $lastPresensi = Presensi::query()
                ->where('user_id', $user->id)
                ->where('status', 'hadir')
                ->whereDate('tanggal', '<', Carbon::today('Asia/Jakarta')->toDateString())
                ->latest('tanggal')
                ->first();

            if ($lastPresensi?->latitude && $lastPresensi?->longitude) {
                $distance = $this->calculateDistance(
                    (float) $lastPresensi->latitude,
                    (float) $lastPresensi->longitude,
                    $latitude,
                    $longitude,
                );
                $lastTime = $lastPresensi->waktu_keluar ?? $lastPresensi->waktu_masuk;
                $hours = $lastTime ? max(Carbon::now('Asia/Jakarta')->diffInHours($lastTime), 1) : 24;
                $speed = $distance / $hours;

                if ($speed > 200) {
                    $analysis['speed_check'] = true;
                    $analysis['suspicious_indicators'][] = 'abnormal_speed';
                    $isFake = true;
                    $messages[] = 'Deteksi pergerakan tidak wajar (kemungkinan teleportasi GPS)';
                }
            }
        } else {
            $openPresensi = Presensi::query()
                ->where('user_id', $user->id)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->latest('tanggal')
                ->first();

            if ($openPresensi?->latitude && $openPresensi?->longitude) {
                $distance = $this->calculateDistance(
                    (float) $openPresensi->latitude,
                    (float) $openPresensi->longitude,
                    $latitude,
                    $longitude,
                );

                if ($distance > 5) {
                    $analysis['location_history_check'] = true;
                    $analysis['suspicious_indicators'][] = 'location_jump';
                    $isFake = true;
                    $messages[] = 'Jarak lokasi masuk dan keluar terlalu jauh (kemungkinan fake GPS)';
                }
            }
        }

        $deviceInfo = strtolower(trim((string) ($payload['device_info'] ?? '')));
        if ($deviceInfo !== '') {
            foreach (['fake', 'mock', 'gps', 'location', 'spoof'] as $app) {
                if (str_contains($deviceInfo, $app)) {
                    $analysis['suspicious_indicators'][] = 'device_info_suspicious';
                    $isFake = true;
                    $messages[] = 'Informasi device menunjukkan penggunaan aplikasi GPS palsu';
                    break;
                }
            }
        }

        $latitudeRaw = (string) ($payload['latitude'] ?? '');
        $longitudeRaw = (string) ($payload['longitude'] ?? '');

        if ($latitudeRaw !== '' && $longitudeRaw !== '') {
            $latParts = explode('.', $latitudeRaw);
            $lngParts = explode('.', $longitudeRaw);
            $latDecimals = isset($latParts[1]) ? strlen($latParts[1]) : 0;
            $lngDecimals = isset($lngParts[1]) ? strlen($lngParts[1]) : 0;

            if ($latDecimals > 15 || $lngDecimals > 15) {
                $analysis['suspicious_indicators'][] = 'precision_too_high';
                $isFake = true;
                $messages[] = 'Presisi koordinat GPS tidak wajar';
            }

            if (fmod((float) $payload['latitude'], 1) == 0.0 || fmod((float) $payload['longitude'], 1) == 0.0) {
                $analysis['suspicious_indicators'][] = 'round_coordinates';
                $isFake = true;
                $messages[] = 'Koordinat GPS terlalu bulat (kemungkinan fake)';
            }
        }

        return [
            'is_fake' => $isFake,
            'message' => $messages === [] ? 'Lokasi GPS valid.' : implode(' ', $messages),
            'analysis' => $analysis,
        ];
    }

    private function validateLocationConsistency($locationReadings): array
    {
        try {
            $readings = $this->normalizeLocationReadings($locationReadings);
            if (!is_array($readings) || count($readings) < 2) {
                return ['valid' => true, 'message' => ''];
            }

            $suspiciousJumpCount = 0;
            $previous = null;

            foreach ($readings as $reading) {
                if (
                    !is_array($reading)
                    || !isset($reading['latitude'], $reading['longitude'])
                    || !is_numeric($reading['latitude'])
                    || !is_numeric($reading['longitude'])
                ) {
                    continue;
                }

                if ($previous !== null) {
                    $distance = $this->calculateDistance(
                        (float) ($previous['latitude'] ?? 0),
                        (float) ($previous['longitude'] ?? 0),
                        (float) ($reading['latitude'] ?? 0),
                        (float) ($reading['longitude'] ?? 0),
                    );

                    $timeDiffSeconds = null;
                    if (
                        isset($previous['timestamp'], $reading['timestamp'])
                        && is_numeric($previous['timestamp'])
                        && is_numeric($reading['timestamp'])
                    ) {
                        $timeDiffSeconds = max(
                            1,
                            abs(((int) $reading['timestamp']) - ((int) $previous['timestamp'])) / 1000,
                        );
                    }

                    if ($timeDiffSeconds !== null && $timeDiffSeconds <= 60 && $distance > 2) {
                        $suspiciousJumpCount++;
                    }
                }

                $previous = $reading;
            }

            if ($suspiciousJumpCount >= 2) {
                return [
                    'valid' => false,
                    'message' => 'Pembacaan lokasi terdeteksi berpindah sangat jauh dalam waktu singkat. Silakan pastikan GPS aktif dan coba kembali.',
                ];
            }

            return ['valid' => true, 'message' => ''];
        } catch (\Throwable $e) {
            return ['valid' => true, 'message' => ''];
        }
    }

    private function normalizeLocationReadings($locationReadings)
    {
        if (is_array($locationReadings)) {
            return $locationReadings;
        }

        if (is_string($locationReadings) && trim($locationReadings) !== '') {
            $decoded = json_decode($locationReadings, true);
            return is_array($decoded) ? $decoded : null;
        }

        return null;
    }

    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        $a = sin($latDelta / 2) * sin($latDelta / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function isValidBase64Image(string $data): bool
    {
        if (strlen($data) < 100) {
            return false;
        }

        if (!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $data)) {
            return false;
        }

        $base64Data = preg_replace('/^data:image\/(jpeg|jpg|png);base64,/', '', $data);
        if (!is_string($base64Data) || !preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64Data)) {
            return false;
        }

        $decoded = base64_decode($base64Data, true);
        if ($decoded === false) {
            return false;
        }

        return str_starts_with($decoded, "\xFF\xD8\xFF") || str_starts_with($decoded, "\x89\x50\x4E\x47");
    }

    private function processAndSaveSelfie(string $selfieData, int $userId, string $tanggal, bool $isMasuk): string
    {
        $path = storage_path('app/public/presensi-selfies');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $type = $isMasuk ? 'masuk' : 'keluar';
        $filename = 'selfie_' . $userId . '_' . $type . '_' . time() . '.jpg';

        try {
            $imageData = base64_decode((string) preg_replace('#^data:image/\w+;base64,#i', '', $selfieData), true);
            if ($imageData === false) {
                throw new \RuntimeException('Selfie tidak dapat diproses.');
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'selfie_');
            file_put_contents($tempFile, $imageData);

            $file = new \Illuminate\Http\UploadedFile(
                $tempFile,
                $filename,
                'image/jpeg',
                null,
                true,
            );

            $file->move($path, $filename);

            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan selfie presensi API.', [
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'message' => $e->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'selfie_data' => 'Gagal memproses foto selfie.',
            ]);
        }

        return 'presensi-selfies/' . $filename;
    }

    private function filterPresensiAttributes(array $attributes): array
    {
        $availableColumns = $this->getPresensiTableColumns();

        if ($availableColumns === []) {
            return $attributes;
        }

        return array_filter(
            $attributes,
            static fn ($key) => isset($availableColumns[$key]),
            ARRAY_FILTER_USE_KEY,
        );
    }

    private function getPresensiTableColumns(): array
    {
        static $columns = null;

        if ($columns !== null) {
            return $columns;
        }

        try {
            $columns = array_flip(Schema::getColumnListing('presensis'));
        } catch (\Throwable $e) {
            Log::warning('Gagal membaca kolom tabel presensis.', [
                'message' => $e->getMessage(),
            ]);
            $columns = [];
        }

        return $columns;
    }

    private function isPointInPolygon(array $point, array $polygon): bool
    {
        $pointLng = $point[0];
        $pointLat = $point[1];
        $inside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $j = $i++) {
            $vertexILat = $polygon[$i][1];
            $vertexILng = $polygon[$i][0];
            $vertexJLat = $polygon[$j][1];
            $vertexJLng = $polygon[$j][0];

            if ((($vertexILat > $pointLat) !== ($vertexJLat > $pointLat))
                && ($pointLng < ($vertexJLng - $vertexILng) * ($pointLat - $vertexILat) / ($vertexJLat - $vertexILat) + $vertexILng)
            ) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    private function findApprovedBlockingIzin(User $user, Carbon|string $date): ?Izin
    {
        return ApprovedIzinSyncService::approvedRequestForDate($user, $date);
    }

    private function findApprovedAutoPresentIzin(User|int $user, Carbon|string|null $date): ?Izin
    {
        if (!$date) {
            return null;
        }

        $userId = $user instanceof User ? $user->id : (int) $user;
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date, 'Asia/Jakarta');

        return Izin::query()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->where('type', 'tugas_luar')
            ->whereDate('tanggal', $date->toDateString())
            ->orderByDesc('approved_at')
            ->first();
    }

    private function attendanceSummaryStatusLabel(string $status, bool $isAutoPresent): string
    {
        if ($status === 'hadir' && $isAutoPresent) {
            return 'Hadir (Tugas Luar)';
        }

        return match ($status) {
            'hadir' => 'Sudah presensi',
            'izin' => 'Tercatat izin',
            'alpha' => 'Tercatat alpha',
            default => 'Belum presensi',
        };
    }

    private function presensiStatusLabel(Presensi $item, ?Izin $autoPresentIzin = null): string
    {
        if (($item->status ?? '') === 'hadir' && $autoPresentIzin) {
            return 'Hadir (Tugas Luar)';
        }

        return ucfirst(str_replace('_', ' ', (string) ($item->status ?? '-')));
    }

    private function findApprovedTeachingJournalIzin(User $user, Carbon|string $date): ?Izin
    {
        return ApprovedIzinSyncService::approvedTeachingJournalRequestForDate($user, $date)
            ?? ExternalTeachingPermissionService::approvedRequestForDate($user, $date);
    }

    private function teachingJournalIzinMessage(Izin $izin): string
    {
        return 'Anda tercatat izin (disetujui) hari ini, sehingga presensi mengajar ditandai sebagai izin.';
    }

    private function teachingJournalIzinNote(Izin $izin): ?string
    {
        if ($izin->type === ExternalTeachingPermissionService::TYPE) {
            return ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI;
        }

        return $izin->alasan ?: $izin->deskripsi_tugas;
    }

    private function buildAttendanceCalendar($items, $holidays, Carbon $today)
    {
        $monthStart = $today->copy()->startOfMonth();
        $daysInMonth = $monthStart->daysInMonth;
        $groupedItems = $items->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->toDateString();
        });
        $holidayMap = $holidays->keyBy(function (Holiday $holiday) {
            return $holiday->getRawOriginal('date');
        });

        return collect(range(1, $daysInMonth))->map(function (int $day) use ($groupedItems, $holidayMap, $monthStart, $today) {
            $date = $monthStart->copy()->day($day);
            $dayItems = collect($groupedItems->get($date->toDateString(), []));
            $holiday = $holidayMap->get($date->toDateString());
            $status = 'belum_tercatat';

            if ($holiday) {
                $status = 'tanggal_merah';
            } elseif ($dayItems->where('status', 'hadir')->isNotEmpty()) {
                $status = 'hadir';
            } elseif ($dayItems->where('status', 'izin')->isNotEmpty()) {
                $status = 'izin';
            } elseif ($dayItems->where('status', 'alpha')->isNotEmpty()) {
                $status = 'alpha';
            } elseif ($date->isFuture()) {
                $status = 'akan_datang';
            } elseif ($date->isWeekend()) {
                $status = 'libur';
            }

            return [
                'date' => $date->toDateString(),
                'day_number' => $day,
                'weekday_short' => $date->locale('id')->isoFormat('dd'),
                'status' => $status,
                'status_label' => match ($status) {
                    'tanggal_merah' => 'Tanggal Merah',
                    'hadir' => 'Hadir',
                    'izin' => 'Izin',
                    'alpha' => 'Alpha',
                    'libur' => 'Libur',
                    'akan_datang' => 'Akan Datang',
                    default => 'Belum Tercatat',
                },
                'holiday_name' => $holiday?->name,
                'is_today' => $date->isSameDay($today),
            ];
        })->values();
    }

    private function serializeHoliday(Holiday $holiday): array
    {
        $rawDate = $holiday->getRawOriginal('date');

        return [
            'date' => $rawDate,
            'date_label' => $rawDate
                ? Carbon::createFromFormat('Y-m-d', $rawDate, 'Asia/Jakarta')
                    ->locale('id')
                    ->isoFormat('D MMMM YYYY')
                : '-',
            'name' => $holiday->name ?? 'Tanggal merah',
            'description' => $holiday->description,
            'type' => $holiday->type,
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
            'location' => $izin->lokasi_tugas,
            'start_time' => $this->formatTimeValue($izin->waktu_masuk),
            'end_time' => $this->formatTimeValue($izin->waktu_keluar),
            'file_url' => $izin->file_path ? url('/api/mobile/app/teacher/izin/' . $izin->id . '/attachment') : null,
            'file_name' => $izin->file_name,
            'day_presence_labels' => $this->izinDayLabels($izin->hari_presensi),
            'day_no_presence_labels' => $this->izinDayLabels($izin->hari_tidak_presensi),
            'can_edit' => $izin->status === 'pending',
            'form_values' => $this->serializeEditableIzinValues($izin),
        ];
    }

    private function serializeEditableIzinValues(Izin $izin): array
    {
        return [
            'tanggal' => optional($izin->tanggal)->format('Y-m-d'),
            'tanggal_mulai' => optional($izin->tanggal)->format('Y-m-d'),
            'tanggal_selesai' => optional($izin->tanggal_selesai)->format('Y-m-d'),
            'alasan' => $this->editableIzinReason($izin),
            'keterangan' => $izin->type === 'sakit' ? $izin->alasan : null,
            'deskripsi_tugas' => $izin->deskripsi_tugas,
            'lokasi_tugas' => $izin->lokasi_tugas,
            'waktu_masuk' => $this->formatTimeValue($izin->waktu_masuk),
            'waktu_keluar' => $this->formatTimeValue($izin->waktu_keluar),
            'hari_presensi' => $izin->hari_presensi ?? [],
            'hari_tidak_presensi' => $izin->hari_tidak_presensi ?? [],
            'file_name' => $izin->file_name,
        ];
    }

    private function editableIzinReason(Izin $izin): ?string
    {
        $reason = trim((string) ($izin->alasan ?? ''));
        if ($reason === '') {
            return null;
        }

        if ($izin->type === 'cuti') {
            $suffix = ' (Tanggal: ' . optional($izin->tanggal)->format('Y-m-d') . ' sampai ' . optional($izin->tanggal_selesai)->format('Y-m-d') . ')';
            if (str_ends_with($reason, $suffix)) {
                return substr($reason, 0, -strlen($suffix));
            }
        }

        return $reason;
    }

    private function serializeManagedIzin(Izin $izin): array
    {
        $base = $this->serializeIzin($izin);
        $requester = $izin->relationLoaded('user') ? $izin->user : $izin->user()->first();
        $approver = $izin->relationLoaded('approver') ? $izin->approver : $izin->approver()->first();

        return array_merge($base, [
            'requester_name' => $requester?->name,
            'requester_email' => $requester?->email,
            'requester_avatar_url' => $requester?->avatar
                ? asset('storage/' . ltrim((string) $requester->avatar, '/'))
                : null,
            'requester_school_name' => $requester?->madrasah?->name,
            'approval_notes' => $izin->approval_notes,
            'approved_at_label' => $izin->approved_at
                ? Carbon::parse($izin->approved_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm')
                : null,
            'approver_name' => $approver?->name,
            'can_approve' => $izin->status === 'pending',
            'can_reject' => $izin->status === 'pending',
        ]);
    }

    private function izinMenu(User $user): array
    {
        $items = [
            [
                'type' => 'tidak_masuk',
                'title' => 'Izin Tidak Masuk',
                'subtitle' => 'Pengajuan tidak hadir pada tanggal tertentu.',
                'icon' => 'person_off',
            ],
            [
                'type' => 'sakit',
                'title' => 'Izin Sakit',
                'subtitle' => 'Lampirkan surat atau keterangan dokter.',
                'icon' => 'medical_information',
            ],
            [
                'type' => 'terlambat',
                'title' => 'Izin Terlambat',
                'subtitle' => 'Ajukan keterlambatan sebelum presensi masuk.',
                'icon' => 'schedule',
            ],
            [
                'type' => 'tugas_luar',
                'title' => 'Izin Tugas Diluar',
                'subtitle' => 'Tugas resmi di luar sekolah dengan rentang waktu.',
                'icon' => 'work_history',
            ],
            [
                'type' => 'cuti',
                'title' => 'Izin Cuti',
                'subtitle' => 'Pengajuan cuti beberapa hari.',
                'icon' => 'event_available',
            ],
        ];

        if (ExternalTeachingPermissionService::isEligibleUser($user)) {
            $items[] = [
                'type' => ExternalTeachingPermissionService::TYPE,
                'title' => 'Mengajar Sekolah Lain',
                'subtitle' => 'Atur hari presensi utama dan hari tanpa presensi.',
                'icon' => 'domain',
            ];
        }

        return $items;
    }

    private function normalizeReportScope(?string $scope): string
    {
        $normalized = strtolower(trim((string) $scope));

        return in_array($normalized, ['monthly', 'all'], true)
            ? $normalized
            : 'monthly';
    }

    private function resolveReportMonth(?string $value): Carbon
    {
        if (is_string($value) && preg_match('/^\d{4}-\d{2}$/', $value)) {
            return Carbon::createFromFormat('Y-m', $value, 'Asia/Jakarta')->startOfMonth();
        }

        return Carbon::today('Asia/Jakarta')->startOfMonth();
    }

    private function resolveReportTeacher(User $requestUser, mixed $teacherId): User
    {
        $selectedId = (int) $teacherId;

        if ($selectedId <= 0 || !$this->canManageIzinRequests($requestUser)) {
            return $requestUser;
        }

        $teacher = User::query()
            ->with(['madrasah'])
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $requestUser->madrasah_id)
            ->findOrFail($selectedId);

        return $teacher;
    }

    private function reportTeacherOptions(User $requestUser)
    {
        if (!$this->canManageIzinRequests($requestUser)) {
            return collect([
                [
                    'id' => $requestUser->id,
                    'name' => $requestUser->name,
                    'ketugasan' => $requestUser->ketugasan,
                ],
            ]);
        }

        return User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $requestUser->madrasah_id)
            ->orderBy('name')
            ->get(['id', 'name', 'ketugasan'])
            ->map(fn (User $teacher) => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'ketugasan' => $teacher->ketugasan,
            ])
            ->values();
    }

    private function buildAttendanceReportPayload(
        User $teacher,
        string $scope,
        Carbon $month,
        bool $forPdf = false,
    ): array {
        $today = Carbon::today('Asia/Jakarta');
        [$startDate, $endDate] = $this->resolveReportDateRange($teacher, $scope, $month, false);
        $summary = $this->buildAttendanceHistorySummary(
            $teacher,
            $startDate,
            $endDate,
            $today
        );
        $effectiveEndDate = $endDate->copy()->min($today);

        $presensiRecords = Presensi::query()
            ->with('madrasah')
            ->where('user_id', $teacher->id)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->get();

        $izinRecords = Izin::query()
            ->where('user_id', $teacher->id)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->get();

        $records = $presensiRecords
            ->map(function (Presensi $item) {
                return [
                    'record_type' => 'presensi',
                    'date' => $item->tanggal?->toDateString(),
                    'day_label' => $item->tanggal
                        ? ucfirst($item->tanggal->locale('id')->dayName)
                        : '-',
                    'status' => (string) ($item->status ?? '-'),
                    'status_label' => ucfirst(str_replace('_', ' ', (string) ($item->status ?? '-'))),
                    'check_in' => $this->formatTimeValue($item->waktu_masuk),
                    'check_out' => $this->formatTimeValue($item->waktu_keluar),
                    'note' => $item->keterangan,
                    'reason' => null,
                    'description' => null,
                ];
            })
            ->concat($izinRecords->map(function (Izin $item) {
                return [
                    'record_type' => 'izin',
                    'date' => $item->tanggal?->toDateString(),
                    'day_label' => $item->tanggal
                        ? ucfirst($item->tanggal->locale('id')->dayName)
                        : '-',
                    'status' => (string) ($item->status ?? '-'),
                    'status_label' => ucfirst(str_replace('_', ' ', (string) ($item->status ?? '-'))),
                    'check_in' => $this->formatTimeValue($item->waktu_masuk),
                    'check_out' => $this->formatTimeValue($item->waktu_keluar),
                    'note' => $item->alasan ?: $item->deskripsi_tugas,
                    'reason' => $item->alasan,
                    'description' => $item->deskripsi_tugas,
                ];
            }))
            ->sortByDesc('date')
            ->values()
            ->all();

        return [
            $summary,
            $forPdf ? $records : $records,
            [
                'start' => $startDate,
                'end' => $effectiveEndDate,
                'label' => $summary['periode_label'],
            ],
        ];
    }

    private function buildTeachingReportPayload(
        User $teacher,
        string $scope,
        Carbon $month,
    ): array {
        $today = Carbon::today('Asia/Jakarta');
        [$startDate, $endDate] = $this->resolveReportDateRange($teacher, $scope, $month, true);
        $effectiveEndDate = $endDate->copy()->min($today);

        $records = TeachingAttendance::query()
            ->with(['teachingSchedule.school'])
            ->where('user_id', $teacher->id)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->get()
            ->map(function (TeachingAttendance $item) {
                return [
                    'id' => $item->id,
                    'date' => Carbon::parse($item->tanggal)->toDateString(),
                    'date_label' => Carbon::parse($item->tanggal)->locale('id')->isoFormat('D MMMM YYYY'),
                    'subject' => $item->teachingSchedule?->subject ?? '-',
                    'class_name' => $item->teachingSchedule?->class_name ?? '-',
                    'school_name' => $item->teachingSchedule?->school?->name ?? '-',
                    'time' => $this->formatTimeValue($item->waktu),
                    'status' => $item->status ?? 'hadir',
                    'status_label' => ($item->status ?? 'hadir') === 'izin' ? 'Izin' : 'Hadir',
                    'materi' => $item->materi,
                    'present_students' => (int) ($item->present_students ?? 0),
                    'class_total_students' => (int) ($item->class_total_students ?? 0),
                    'student_attendance_percentage' => (float) ($item->student_attendance_percentage ?? 0),
                    'location' => $item->lokasi,
                ];
            })
            ->values()
            ->all();

        $totalEntries = count($records);
        $averageStudentAttendance = $totalEntries > 0
            ? round(collect($records)->avg('student_attendance_percentage') ?? 0, 1)
            : 0.0;

        $summary = [
            'total_entries' => $totalEntries,
            'total_present_students' => collect($records)->sum('present_students'),
            'total_class_students' => collect($records)->sum('class_total_students'),
            'average_student_attendance' => $averageStudentAttendance,
        ];

        return [
            $summary,
            $records,
            [
                'start' => $startDate,
                'end' => $effectiveEndDate,
                'label' => $startDate->locale('id')->isoFormat('D MMMM YYYY') . ' - ' . $effectiveEndDate->locale('id')->isoFormat('D MMMM YYYY'),
            ],
        ];
    }

    private function resolveReportDateRange(
        User $teacher,
        string $scope,
        Carbon $month,
        bool $teaching,
    ): array {
        $today = Carbon::today('Asia/Jakarta');

        if ($scope === 'all') {
            $firstDate = $teaching
                ? TeachingAttendance::query()
                    ->where('user_id', $teacher->id)
                    ->orderBy('tanggal')
                    ->value('tanggal')
                : collect([
                    Presensi::query()->where('user_id', $teacher->id)->orderBy('tanggal')->value('tanggal'),
                    Izin::query()->where('user_id', $teacher->id)->orderBy('tanggal')->value('tanggal'),
                ])->filter()->min();

            $start = $firstDate
                ? Carbon::parse((string) $firstDate, 'Asia/Jakarta')->startOfDay()
                : $today->copy()->startOfMonth();

            return [$start, $today->copy()];
        }

        return [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()];
    }

    private function buildAttendanceHistorySummary(
        User $teacher,
        Carbon $startDate,
        Carbon $endDate,
        Carbon $today,
    ): array {
        $effectiveEndDate = $endDate->copy()->min($today);

        if ($effectiveEndDate->lt($startDate)) {
            return [
                'periode_label' => $startDate->locale('id')->isoFormat('D MMMM YYYY') . ' - ' . $endDate->locale('id')->isoFormat('D MMMM YYYY'),
                'total_hari_kerja' => 0,
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_belum_hadir' => 0,
                'persentase_kehadiran' => 0,
            ];
        }

        ApprovedIzinSyncService::syncApprovedIzinPresensiInRange(
            $teacher,
            $startDate,
            $effectiveEndDate
        );

        $presensiByDate = Presensi::query()
            ->where('user_id', $teacher->id)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(fn (Presensi $item) => $item->tanggal->toDateString());

        $totalHariKerja = 0;
        $totalHadir = 0;
        $totalIzinApproved = 0;

        foreach (CarbonPeriod::create($startDate, $effectiveEndDate) as $date) {
            if (!$this->isReportWorkingDay($date, $teacher->madrasah?->hari_kbm)) {
                continue;
            }

            $records = collect($presensiByDate->get($date->toDateString(), []));
            $hadirRecords = $records->where('status', 'hadir');
            $izinApprovedRecords = $records
                ->where('status', 'izin')
                ->where('status_izin', 'approved');
            $externalTeachingIzin = $hadirRecords->isEmpty()
                ? ExternalTeachingPermissionService::approvedRequestForDate($teacher, $date)
                : null;

            $totalHariKerja++;
            if ($hadirRecords->isNotEmpty()) {
                $totalHadir++;
            } elseif ($izinApprovedRecords->isNotEmpty() || $externalTeachingIzin) {
                $totalIzinApproved++;
            }
        }

        $totalDasarPersentase = max($totalHariKerja - $totalIzinApproved, 0);

        return [
            'periode_label' => $startDate->locale('id')->isoFormat('D MMMM YYYY') . ' - ' . $effectiveEndDate->locale('id')->isoFormat('D MMMM YYYY'),
            'total_hari_kerja' => $totalHariKerja,
            'total_hadir' => $totalHadir,
            'total_izin' => $totalIzinApproved,
            'total_belum_hadir' => max($totalHariKerja - $totalHadir - $totalIzinApproved, 0),
            'persentase_kehadiran' => $totalDasarPersentase > 0
                ? round(($totalHadir / $totalDasarPersentase) * 100, 1)
                : 0,
        ];
    }

    private function isReportWorkingDay(Carbon $date, ?string $hariKbm): bool
    {
        if ($date->isSunday() || Holiday::isHoliday($date->toDateString())) {
            return false;
        }

        if ((string) $hariKbm === '5' && $date->isSaturday()) {
            return false;
        }

        return true;
    }

    private function canManageIzinRequests(User $user): bool
    {
        return $user->role === 'tenaga_pendidik'
            && $user->ketugasan === 'kepala madrasah/sekolah'
            && (int) $user->madrasah_id > 0;
    }

    private function ensureCanManageIzinRequests(User $user): void
    {
        if (!$this->canManageIzinRequests($user)) {
            abort(403, 'Unauthorized');
        }
    }

    private function ensureCanManageIzin(User $user, Izin $izin): void
    {
        $this->ensureCanManageIzinRequests($user);
        $izin->loadMissing(['user.madrasah', 'approver']);

        abort_unless((int) ($izin->user?->madrasah_id ?? 0) === (int) $user->madrasah_id, 403, 'Unauthorized');
    }

    private function syncApprovedIzinAfterApproval(Izin $izin): void
    {
        if ($izin->type === ExternalTeachingPermissionService::TYPE) {
            ExternalTeachingPermissionService::syncApprovedNoPresencePresensi(
                $izin,
                Carbon::today('Asia/Jakarta'),
            );

            return;
        }

        ApprovedIzinSyncService::syncApprovedIzinPresensi($izin);
    }

    private function izinDayOptions(): array
    {
        return [
            ['value' => 1, 'label' => 'Senin'],
            ['value' => 2, 'label' => 'Selasa'],
            ['value' => 3, 'label' => 'Rabu'],
            ['value' => 4, 'label' => 'Kamis'],
            ['value' => 5, 'label' => 'Jumat'],
            ['value' => 6, 'label' => 'Sabtu'],
        ];
    }

    private function izinDayLabels(array|string|null $days): array
    {
        $labelMap = collect($this->izinDayOptions())
            ->mapWithKeys(fn (array $item) => [$item['value'] => $item['label']]);

        $normalized = collect($this->normalizeIzinDays($days));

        return $normalized
            ->map(fn (int $day) => $labelMap->get($day))
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeIzinDays(array|string|null $days): array
    {
        if (is_string($days) && trim($days) !== '') {
            $decoded = json_decode($days, true);
            $days = is_array($decoded) ? $decoded : [];
        }

        return collect($days ?? [])
            ->map(fn ($day) => (int) $day)
            ->filter(fn (int $day) => $day >= 1 && $day <= 6)
            ->unique()
            ->values()
            ->all();
    }

    private function storeIzinAttachment(?UploadedFile $file): array
    {
        if (!$file) {
            return [
                'file_path' => null,
                'file_name' => null,
            ];
        }

        return [
            'file_path' => $this->storeUploadedFileForWeb($file, 'surat_izin'),
            'file_name' => $file->getClientOriginalName(),
        ];
    }

    private function mergeUpdatedIzinAttachment(Izin $izin, array $izinData): array
    {
        $hasNewAttachment = !empty($izinData['file_path']);
        if (!$hasNewAttachment) {
            $izinData['file_path'] = $izin->file_path;
            $izinData['file_name'] = $izin->file_name;
            return $izinData;
        }

        if ($izin->file_path) {
            $this->deletePublicStoredFile($izin->file_path);
        }

        return $izinData;
    }

    private function storeUploadedFileForWeb(UploadedFile $file, string $directory): string
    {
        $storageRoot = $this->webStorageRoot();
        $targetDirectory = $storageRoot . '/' . trim($directory, '/');

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($targetDirectory, $filename);

        return trim($directory, '/') . '/' . $filename;
    }

    private function deletePublicStoredFile(?string $relativePath): void
    {
        $relative = ltrim((string) $relativePath, '/');
        if ($relative === '') {
            return;
        }

        $publicFile = $this->webStorageRoot() . '/' . $relative;
        if (is_file($publicFile)) {
            @unlink($publicFile);
        }

        Storage::disk('public')->delete($relative);
    }

    private function resolvePublicStoredFilePath(?string $relativePath): ?string
    {
        $relative = ltrim((string) $relativePath, '/');
        if ($relative === '') {
            return null;
        }

        $publicFile = $this->webStorageRoot() . '/' . $relative;
        if (is_file($publicFile)) {
            return $publicFile;
        }

        $storageDiskFile = Storage::disk('public')->path($relative);
        if (is_file($storageDiskFile)) {
            return $storageDiskFile;
        }

        return null;
    }

    private function webStorageRoot(): string
    {
        $documentRoot = trim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
        if ($documentRoot !== '') {
            return rtrim($documentRoot, '/') . '/storage';
        }

        return public_path('storage');
    }

    private function ensureIzinCanBeSubmitted(User $user, string $type, ?Izin $currentIzin = null): void
    {
        $tanggal = request()->input('tanggal', request()->input('tanggal_mulai'));

        if ($tanggal && !in_array($type, ['tugas_luar', 'cuti', ExternalTeachingPermissionService::TYPE], true)) {
            $existingPresensi = Presensi::query()
                ->where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if ($existingPresensi) {
                throw ValidationException::withMessages([
                    'tanggal' => 'Anda sudah memiliki catatan kehadiran pada tanggal ini.',
                ]);
            }
        }

        $pendingIzin = Izin::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->when(
                $currentIzin,
                fn ($query) => $query->where('id', '!=', $currentIzin->id)
            )
            ->first();

        if ($pendingIzin) {
            throw ValidationException::withMessages([
                'pending' => 'Anda masih memiliki pengajuan izin yang belum disetujui. Harap tunggu persetujuan kepala sekolah terlebih dahulu.',
            ]);
        }

        $existingIzin = Izin::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->where('type', $type)
            ->when(
                $currentIzin,
                fn ($query) => $query->where('id', '!=', $currentIzin->id)
            )
            ->first();

        if ($existingIzin) {
            throw ValidationException::withMessages([
                'tanggal' => 'Anda sudah mengajukan izin untuk tanggal ini.',
            ]);
        }
    }

    private function buildIzinPayload(Request $request, User $user, string $type, ?Izin $existingIzin = null): array
    {
        return match ($type) {
            'sakit' => $this->buildSakitIzinPayload($request, $existingIzin),
            'tidak_masuk' => $this->buildTidakMasukIzinPayload($request, $existingIzin),
            'terlambat' => $this->buildTerlambatIzinPayload($request, $existingIzin),
            'tugas_luar' => $this->buildTugasLuarIzinPayload($request, $user, $existingIzin),
            'cuti' => $this->buildCutiIzinPayload($request),
            ExternalTeachingPermissionService::TYPE => $this->buildMengajarSekolahLainIzinPayload($request, $user, $existingIzin),
            default => throw ValidationException::withMessages([
                'type' => 'Tipe izin tidak dikenali.',
            ]),
        };
    }

    private function buildSakitIzinPayload(Request $request, ?Izin $existingIzin = null): array
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
            'surat_izin' => [
                $existingIzin?->file_path ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ], [
            'surat_izin.required' => 'File surat atau keterangan dokter wajib diunggah untuk izin sakit.',
            'surat_izin.file' => 'Berkas surat sakit tidak valid.',
            'surat_izin.mimes' => 'File surat sakit harus berformat PDF, JPG, JPEG, atau PNG.',
            'surat_izin.max' => 'Ukuran file surat sakit maksimal 5MB.',
        ]);

        return array_merge([
            'tanggal' => $validated['tanggal'],
            'alasan' => trim((string) $validated['keterangan']),
        ], $this->storeIzinAttachment($request->file('surat_izin')));
    }

    private function buildTidakMasukIzinPayload(Request $request, ?Izin $existingIzin = null): array
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'alasan' => ['required', 'string'],
            'file_izin' => [
                $existingIzin?->file_path ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        return array_merge([
            'tanggal' => $validated['tanggal'],
            'alasan' => trim((string) $validated['alasan']),
        ], $this->storeIzinAttachment($request->file('file_izin')));
    }

    private function buildTerlambatIzinPayload(Request $request, ?Izin $existingIzin = null): array
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'alasan' => ['required', 'string'],
            'waktu_masuk' => ['required'],
            'file_izin' => [
                $existingIzin?->file_path ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        return array_merge([
            'tanggal' => $validated['tanggal'],
            'alasan' => trim((string) $validated['alasan']),
            'waktu_masuk' => $validated['waktu_masuk'],
        ], $this->storeIzinAttachment($request->file('file_izin')));
    }

    private function buildTugasLuarIzinPayload(Request $request, User $user, ?Izin $existingIzin = null): array
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'deskripsi_tugas' => ['required', 'string'],
            'lokasi_tugas' => ['required', 'string'],
            'waktu_masuk' => ['required'],
            'waktu_keluar' => ['required'],
            'file_tugas' => [
                $existingIzin?->file_path ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $existing = Izin::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('type', 'tugas_luar')
            ->when(
                $existingIzin,
                fn ($query) => $query->where('id', '!=', $existingIzin->id)
            )
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'tanggal' => 'Anda sudah memiliki pengajuan izin tugas luar pada tanggal ini.',
            ]);
        }

        return array_merge([
            'tanggal' => $validated['tanggal'],
            'alasan' => trim((string) $validated['deskripsi_tugas']),
            'deskripsi_tugas' => trim((string) $validated['deskripsi_tugas']),
            'lokasi_tugas' => trim((string) $validated['lokasi_tugas']),
            'waktu_masuk' => $validated['waktu_masuk'],
            'waktu_keluar' => $validated['waktu_keluar'],
        ], $this->storeIzinAttachment($request->file('file_tugas')));
    }

    private function buildCutiIzinPayload(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string'],
            'file_izin' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        return array_merge([
            'tanggal' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => trim((string) $validated['alasan']) . ' (Tanggal: ' . $validated['tanggal_mulai'] . ' sampai ' . $validated['tanggal_selesai'] . ')',
        ], $this->storeIzinAttachment($request->file('file_izin')));
    }

    private function buildMengajarSekolahLainIzinPayload(Request $request, User $user, ?Izin $existingIzin = null): array
    {
        if (!ExternalTeachingPermissionService::isEligibleUser($user)) {
            throw ValidationException::withMessages([
                'type' => 'Pengajuan ini hanya untuk guru yang memiliki beban kerja di sekolah lain.',
            ]);
        }

        $validated = $request->validate([
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'hari_presensi' => ['required', 'array', 'min:1'],
            'hari_presensi.*' => ['integer', 'between:1,6'],
            'hari_tidak_presensi' => ['required', 'array', 'min:1'],
            'hari_tidak_presensi.*' => ['integer', 'between:1,6'],
            'alasan' => ['nullable', 'string'],
            'file_izin' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $hariPresensi = $this->normalizeIzinDays($validated['hari_presensi']);
        $hariTidakPresensi = $this->normalizeIzinDays($validated['hari_tidak_presensi']);

        if ($hariPresensi === [] || $hariTidakPresensi === []) {
            throw ValidationException::withMessages([
                'hari_presensi' => 'Pilih minimal satu hari aktif presensi dan satu hari izin tidak presensi.',
            ]);
        }

        if (!empty(array_intersect($hariPresensi, $hariTidakPresensi))) {
            throw ValidationException::withMessages([
                'hari_tidak_presensi' => 'Hari aktif presensi dan hari izin tidak presensi tidak boleh sama.',
            ]);
        }

        $overlappingSchedule = Izin::query()
            ->where('user_id', $user->id)
            ->where('type', ExternalTeachingPermissionService::TYPE)
            ->whereIn('status', ['pending', 'approved'])
            ->when(
                $existingIzin,
                fn ($query) => $query->where('id', '!=', $existingIzin->id)
            )
            ->whereDate('tanggal', '<=', $validated['tanggal_selesai'])
            ->where(function ($query) use ($validated) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $validated['tanggal_mulai']);
            })
            ->exists();

        if ($overlappingSchedule) {
            throw ValidationException::withMessages([
                'tanggal_mulai' => 'Anda sudah memiliki pengajuan jadwal mengajar di sekolah lain pada periode tersebut.',
            ]);
        }

        return array_merge([
            'tanggal' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => trim((string) ($validated['alasan'] ?? '')) ?: 'Pengaturan presensi karena mengajar di sekolah lain.',
            'deskripsi_tugas' => 'Mengajar di sekolah lain',
            'lokasi_tugas' => $user->madrasahTambahan?->name ?? 'Sekolah lain',
            'hari_presensi' => $hariPresensi,
            'hari_tidak_presensi' => $hariTidakPresensi,
        ], $this->storeIzinAttachment($request->file('file_izin')));
    }

    private function notifyUserWithPush(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): void {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        $pushData = array_merge($data, [
            'notification_id' => $notification->id,
            'type' => $type,
        ]);

        app(FcmPushService::class)->sendToUser($user, $title, $message, $pushData);
    }

    private function notifyUserIdWithPush(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): void {
        $targetUser = User::query()->find($userId);
        if (!$targetUser) {
            return;
        }

        $this->notifyUserWithPush($targetUser, $type, $title, $message, $data);
    }

    private function notifyApprovalManagersAboutIncomingIzin(User $requestUser, Izin $izin): void
    {
        if (!$requestUser->madrasah_id) {
            return;
        }

        $managers = User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $requestUser->madrasah_id)
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->where('id', '!=', $requestUser->id)
            ->get();

        if ($managers->isEmpty()) {
            return;
        }

        $title = 'Pengajuan Izin Masuk';
        $message = $requestUser->name . ' mengajukan ' . $this->izinTitle($izin->type) . ' dan menunggu persetujuan.';
        $data = [
            'izin_id' => $izin->id,
            'requester_id' => $requestUser->id,
            'requester_name' => $requestUser->name,
            'tanggal' => optional($izin->tanggal)->format('Y-m-d'),
            'type' => $izin->type,
        ];

        foreach ($managers as $manager) {
            $this->notifyUserWithPush($manager, 'izin_incoming', $title, $message, $data);
        }
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
