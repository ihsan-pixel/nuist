<?php

namespace App\Http\Controllers;

use App\Models\TeachingAttendance;
use App\Models\TeachingClassStudentCount;
use App\Models\TeachingSchedule;
use App\Models\Presensi;
use App\Models\DayMarker;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeachingAttendanceController extends Controller
{
    private function scheduleDayOfWeek(?string $day): ?int
    {
        $day = trim((string) $day);
        if ($day === '') return null;

        if (is_numeric($day)) {
            $int = (int) $day;
            if ($int >= 0 && $int <= 6) return $int; // Carbon: 0=Sun..6=Sat
            if ($int >= 1 && $int <= 7) return $int === 7 ? 0 : $int; // ISO: 1=Mon..7=Sun
            return null;
        }

        $key = strtolower($day);
        return match ($key) {
            'senin' => Carbon::MONDAY,
            'selasa' => Carbon::TUESDAY,
            'rabu' => Carbon::WEDNESDAY,
            'kamis' => Carbon::THURSDAY,
            'jumat' => Carbon::FRIDAY,
            'sabtu' => Carbon::SATURDAY,
            'minggu' => Carbon::SUNDAY,
            default => null,
        };
    }

    private function scheduleAppliesToDate(TeachingSchedule $schedule, Carbon $date): bool
    {
        $scheduleDow = $this->scheduleDayOfWeek((string) $schedule->day);
        if ($scheduleDow !== null) {
            return $scheduleDow === $date->dayOfWeek;
        }

        $todayName = strtolower($date->locale('id')->dayName);
        return strtolower(trim((string) $schedule->day)) === $todayName;
    }

    public function index()
    {
        $user = Auth::user();

        // Check if tenaga_pendidik and password not changed
        if ($user->role === 'tenaga_pendidik' && !$user->password_changed) {
            return redirect()->route('dashboard')->with('error', 'Anda harus mengubah password terlebih dahulu sebelum mengakses menu presensi mengajar.');
        }

        $todayCarbon = Carbon::now('Asia/Jakarta');
        $today = $todayCarbon->toDateString();
        $dayOfWeek = $todayCarbon->locale('id')->dayName; // e.g., 'Senin'
        $todayNameLower = strtolower($dayOfWeek);
        $todayDow = $todayCarbon->dayOfWeek; // 0..6
        $todayIsoDow = $todayCarbon->isoWeekday(); // 1..7

        $approvedIzinPresensi = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->where('status_izin', 'approved')
            ->first();

        // Get today's schedules for the teacher
        $schedules = TeachingSchedule::with(['school', 'teacher'])
            ->where('teacher_id', $user->id)
            ->where(function ($q) use ($todayNameLower, $todayDow, $todayIsoDow) {
                $q->whereRaw('LOWER(day) = ?', [$todayNameLower])
                    ->orWhere('day', (string) $todayDow)
                    ->orWhere('day', (string) $todayIsoDow);
            })
            ->orderBy('start_time')
            ->get();

        // Show all schedules for the day, including past ones (UI will handle disabling buttons)
        // Removed time filter to allow viewing all schedules for the day

        // Attach attendance status for each schedule
        foreach ($schedules as $schedule) {
            $attendance = TeachingAttendance::where('teaching_schedule_id', $schedule->id)
                ->where('tanggal', $today)
                ->first();
            $schedule->attendance = $attendance;

            $marker = DayMarker::resolveMarker($today, (int) $schedule->school_id, (string) $schedule->class_name);
            $schedule->day_marker = $marker['marker'];
            $schedule->day_marker_label = DayMarker::markerLabel($marker['marker']);
        }

        $this->attachClassStudentCounts($schedules);

        return view('teaching-attendances.index', compact('schedules', 'today', 'approvedIzinPresensi'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'materi' => trim((string) $request->input('materi', '')),
        ]);

        $request->validate([
            'teaching_schedule_id' => 'required|exists:teaching_schedules,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
            'materi' => 'required|string|max:1000',
            'present_students' => 'required|integer|min:0|max:10000',
            'class_total_students' => 'nullable|integer|min:1|max:10000',
            'force' => 'nullable|boolean',
            'force_reason' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta')->format('H:i:s');
        $force = (bool) $request->boolean('force');
        $forceReason = trim((string) $request->input('force_reason', ''));

        $hasApprovedIzinToday = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->where('status_izin', 'approved')
            ->exists();

        if ($hasApprovedIzinToday) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tercatat izin (disetujui) hari ini, sehingga tidak dapat melakukan presensi mengajar.'
            ], 400);
        }

        // Get the schedule
        $schedule = TeachingSchedule::findOrFail($request->teaching_schedule_id);

        // Check if the schedule belongs to the user
        if ($schedule->teacher_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak valid.'
            ], 403);
        }

        $dayMarker = DayMarker::resolveMarker($today, (int) $schedule->school_id, (string) $schedule->class_name);
        $marker = $dayMarker['marker'];

        if ($marker === 'libur') {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini ditandai sebagai ' . DayMarker::markerLabel($marker) . ', presensi mengajar tidak dapat dilakukan.'
            ], 400);
        }

        $isKegiatanKhusus = $marker === 'kegiatan_khusus';
        if ($isKegiatanKhusus && !$force) {
            $force = true;
            if ($forceReason === '') $forceReason = 'kegiatan_khusus';
        }

        // Ensure the attendance can only be submitted for today's schedule.
        if (!$this->scheduleAppliesToDate($schedule, Carbon::today('Asia/Jakarta')) && !$force) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi mengajar hanya dapat dilakukan untuk jadwal hari ini.'
            ], 400);
        }

        // Check if already attended today
        $existingAttendance = TeachingAttendance::where('teaching_schedule_id', $schedule->id)
            ->where('tanggal', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi untuk jadwal ini hari ini.'
            ], 400);
        }

        // Time validation: current time must be within schedule start_time to end_time
        $currentTime = Carbon::now('Asia/Jakarta');
        $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
        $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');

        // Strict time validation: only within schedule time
        if (!$force && !$currentTime->between($startTime, $endTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu presensi harus dilakukan dalam rentang waktu mengajar (' . $schedule->start_time . ' - ' . $schedule->end_time . '). Waktu sekarang: ' . $now
            ], 400);
        }

        // Location validation using polygon from madrasah
        $madrasah = $schedule->school;
        $isWithinPolygon = false;
        $polygonError = '';

        $polygonsToCheck = [];
        if ($madrasah && $madrasah->polygon_koordinat) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat;
        }
        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat_2;
        }

        if (!empty($polygonsToCheck)) {
            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinPolygon = true;
                            break; // Jika sudah ada yang valid, tidak perlu cek yang lain
                        }
                    }
                } catch (\Exception $e) {
                    continue; // Skip invalid polygon
                }
            }
            if (!$isWithinPolygon) {
                $polygonError = 'Lokasi Anda (' . $request->latitude . ', ' . $request->longitude . ') berada di luar area sekolah.';
            }
        } else {
            $polygonError = 'Madrasah belum memiliki polygon koordinat yang ditentukan.';
        }

        $skipPolygonValidation = $isKegiatanKhusus
            || ($force && in_array($forceReason, ['kegiatan_khusus', 'outside_area'], true));

        // Strict polygon validation: must be within madrasah polygon (except special day/forced reasons)
        if (!$skipPolygonValidation && !$isWithinPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area sekolah yang telah ditentukan. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.'
            ], 400);
        }

        $classStudentCount = TeachingClassStudentCount::where('school_id', $schedule->school_id)
            ->where('class_name', trim((string) $schedule->class_name))
            ->first();

        if (!$classStudentCount && !$request->filled('class_total_students')) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah siswa di kelas wajib diisi untuk jadwal ini.'
            ], 422);
        }

        $presentStudents = (int) $request->present_students;
        $classTotalStudents = $classStudentCount
            ? (int) $classStudentCount->total_students
            : (int) $request->class_total_students;

        if ($presentStudents > $classTotalStudents) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.'
            ], 422);
        }

        $studentAttendancePercentage = $classTotalStudents > 0
            ? round(($presentStudents / $classTotalStudents) * 100, 2)
            : 0;

        // Create attendance
        $attendance = DB::transaction(function () use (
            $classStudentCount,
            $classTotalStudents,
            $presentStudents,
            $studentAttendancePercentage,
            $request,
            $schedule,
            $today,
            $now,
            $user
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

            return TeachingAttendance::create([
                'teaching_schedule_id' => $schedule->id,
                'user_id' => $user->id,
                'tanggal' => $today,
                'waktu' => $now,
                'status' => 'hadir',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'materi' => $request->materi,
                'class_total_students' => $classTotalStudents,
                'present_students' => $presentStudents,
                'student_attendance_percentage' => $studentAttendancePercentage,
            ]);
        });

        $message = 'Presensi mengajar berhasil dicatat pada ' . $now . '.';

        // Create success notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'teaching_success',
            'title' => 'Presensi Mengajar Berhasil',
            'message' => $message,
            'data' => [
                'attendance_id' => $attendance->id,
                'schedule_id' => $schedule->id,
                'tanggal' => $today,
                'waktu' => $now,
                'school_name' => $schedule->school->name ?? 'N/A',
                'materi' => $attendance->materi,
                'class_total_students' => $attendance->class_total_students,
                'present_students' => $attendance->present_students,
                'student_attendance_percentage' => $attendance->student_attendance_percentage,
                'force' => $force,
                'force_reason' => $forceReason,
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $attendance
        ]);
    }

    public function update(Request $request, TeachingAttendance $attendance)
    {
        $user = Auth::user();

        if ((int) $attendance->user_id !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak berhak mengubah presensi ini.'
            ], 403);
        }

        $request->merge([
            'materi' => trim((string) $request->input('materi', '')),
        ]);

        $request->validate([
            'materi' => 'required|string|max:1000',
            'present_students' => 'required|integer|min:0|max:10000',
            'class_total_students' => 'nullable|integer|min:1|max:10000',
        ]);

        $schedule = TeachingSchedule::find($attendance->teaching_schedule_id);
        $classTotalStudents = $attendance->class_total_students;
        if (is_null($classTotalStudents)) {
            $classTotalStudents = $request->filled('class_total_students')
                ? (int) $request->input('class_total_students')
                : null;
        } elseif ($request->filled('class_total_students')) {
            $classTotalStudents = (int) $request->input('class_total_students');
        }

        if (!$classTotalStudents || $classTotalStudents < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah siswa di kelas wajib diisi.'
            ], 422);
        }

        $presentStudents = (int) $request->input('present_students');
        if ($presentStudents > $classTotalStudents) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.'
            ], 422);
        }

        $studentAttendancePercentage = $classTotalStudents > 0
            ? round(($presentStudents / $classTotalStudents) * 100, 2)
            : 0;

        DB::transaction(function () use (
            $attendance,
            $request,
            $presentStudents,
            $classTotalStudents,
            $studentAttendancePercentage,
            $schedule,
            $user
        ) {
            $attendance->update([
                'materi' => $request->input('materi'),
                'present_students' => $presentStudents,
                'class_total_students' => $classTotalStudents,
                'student_attendance_percentage' => $studentAttendancePercentage,
            ]);

            if ($schedule) {
                $count = TeachingClassStudentCount::where('school_id', $schedule->school_id)
                    ->where('class_name', trim((string) $schedule->class_name))
                    ->first();

                if ($count) {
                    $count->update([
                        'total_students' => $classTotalStudents,
                        'updated_by' => $user->id,
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Presensi mengajar berhasil diperbarui.',
            'data' => $attendance->fresh()
        ]);
    }

    public function checkLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'teaching_schedule_id' => 'required|exists:teaching_schedules,id',
        ]);

        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $hasApprovedIzinToday = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->where('status_izin', 'approved')
            ->exists();

        if ($hasApprovedIzinToday) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tercatat izin (disetujui) hari ini, sehingga tidak dapat melakukan presensi mengajar.'
            ], 400);
        }

        $user = Auth::user();

        // Get the schedule
        $schedule = TeachingSchedule::findOrFail($request->teaching_schedule_id);

        // Check if the schedule belongs to the user
        if ($schedule->teacher_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak valid.'
            ], 403);
        }

        if (!$this->scheduleAppliesToDate($schedule, Carbon::today('Asia/Jakarta'))) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi mengajar hanya dapat dilakukan untuk jadwal hari ini.'
            ], 400);
        }

        $dayMarker = DayMarker::resolveMarker($today, (int) $schedule->school_id, (string) $schedule->class_name);
        $marker = $dayMarker['marker'];

        if ($marker === 'libur') {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini ditandai sebagai ' . DayMarker::markerLabel($marker) . ', presensi mengajar tidak dapat dilakukan.'
            ], 400);
        }

        if ($marker === 'kegiatan_khusus') {
            return response()->json([
                'success' => true,
                'is_within_polygon' => true,
            ]);
        }

        // Location validation using polygon from madrasah
        $madrasah = $schedule->school;
        $isWithinPolygon = false;

        $polygonsToCheck = [];
        if ($madrasah && $madrasah->polygon_koordinat) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat;
        }
        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat_2;
        }

        if (!empty($polygonsToCheck)) {
            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinPolygon = true;
                            break; // Jika sudah ada yang valid, tidak perlu cek yang lain
                        }
                    }
                } catch (\Exception $e) {
                    continue; // Skip invalid polygon
                }
            }
        }

        return response()->json([
            'success' => true,
            'is_within_polygon' => $isWithinPolygon
        ]);
    }

    private function attachClassStudentCounts($schedules): void
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

        $counts = TeachingClassStudentCount::whereIn('school_id', $schoolIds)
            ->whereIn('class_name', $classNames)
            ->get()
            ->keyBy(fn ($count) => $this->classStudentCountKey($count->school_id, $count->class_name));

        $schedules->each(function ($schedule) use ($counts) {
            $schedule->class_student_count = $counts->get(
                $this->classStudentCountKey($schedule->school_id, $schedule->class_name)
            );
        });
    }

    private function classStudentCountKey($schoolId, $className): string
    {
        return $schoolId . '|' . strtolower(trim((string) $className));
    }

    /**
     * Checks if a point is inside a polygon using the ray-casting algorithm.
     * @param array $point The point to check, in [longitude, latitude] format.
     * @param array $polygon An array of polygon vertices, each in [longitude, latitude] format.
     * @return bool True if the point is inside the polygon, false otherwise.
     */
    private function isPointInPolygon(array $point, array $polygon): bool
    {
        $pointLng = $point[0];
        $pointLat = $point[1];
        $isInside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $j = $i++) {
            $vertexiLat = $polygon[$i][1];
            $vertexiLng = $polygon[$i][0];
            $vertexjLat = $polygon[$j][1];
            $vertexjLng = $polygon[$j][0];

            // This is the core of the ray-casting algorithm
            if ((($vertexiLat > $pointLat) != ($vertexjLat > $pointLat)) &&
                ($pointLng < ($vertexjLng - $vertexiLng) * ($pointLat - $vertexiLat) / ($vertexjLat - $vertexiLat) + $vertexiLng)) {
                $isInside = !$isInside;
            }
        }

        return $isInside;
    }
}
