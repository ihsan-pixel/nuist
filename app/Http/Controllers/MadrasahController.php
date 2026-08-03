<?php

namespace App\Http\Controllers;

use App\Exports\MadrasahProfileSummaryExport;
use App\Models\Holiday;
use App\Models\Madrasah;
use App\Models\Presensi;
use App\Models\SkYayasanImportBatch;
use App\Models\SkYayasanRequest;
use App\Models\Siswa;
use App\Models\TeachingAttendance;
use App\Models\TeachingClassStudentCount;
use App\Models\TeachingSchedule;
use App\Models\TeachingSchedulePeriod;
use App\Models\User;
use App\Models\Yayasan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Imports\MadrasahImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MadrasahController extends Controller
{
    /**
     * Tampilkan daftar madrasah
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $madrasahs = Madrasah::where('id', $user->madrasah_id)->orderBy('kabupaten')->get();
        } elseif ($user->role === 'pengurus' || $user->role === 'super_admin') {
            $madrasahs = Madrasah::orderBy('kabupaten')->get();
        } else {
            abort(403, 'Unauthorized access');
        }
        return view('masterdata.madrasah.index', compact('madrasahs'));
    }

    /**
     * Simpan madrasah baru
     */
    public function store(Request $request)
    {
        if ($request->input('polygon_koordinat') === '') {
            $request->merge(['polygon_koordinat' => null]);
        }
        if ($request->input('polygon_koordinat_2') === '') {
            $request->merge(['polygon_koordinat_2' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kabupaten' => 'nullable|in:Kabupaten Bantul,Kabupaten Gunungkidul,Kabupaten Kulon Progo,Kabupaten Sleman,Kota Yogyakarta',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_link' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // opsional
            'polygon_koordinat' => 'nullable|json',
            'polygon_koordinat_2' => 'nullable|json',
            'enable_dual_polygon' => 'boolean',
            'hari_kbm' => 'nullable|in:5,6',
            // Presensi schedule fields (time format HH:MM)
            'presensi_masuk_start' => 'nullable|date_format:H:i',
            'presensi_masuk_end' => 'nullable|date_format:H:i',
            'presensi_pulang_start' => 'nullable|date_format:H:i',
            'presensi_pulang_end' => 'nullable|date_format:H:i',
            'presensi_pulang_jumat' => 'nullable|date_format:H:i',
            'presensi_pulang_sabtu' => 'nullable|date_format:H:i',
        ]);

        // Restrict dual polygon to specific madrasah IDs (only for store method)
        $allowedMadrasahIds = [24, 26, 33];
        if ($request->input('enable_dual_polygon') && !in_array($request->input('id') ?? null, $allowedMadrasahIds)) {
            return redirect()->back()->with('error', 'Fitur dual polygon hanya tersedia untuk madrasah tertentu (ID: 24, 26, 33).');
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            // Validasi ukuran file (maksimal 2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran file logo terlalu besar. Maksimal 2MB.');
            }

            // Generate nama file yang unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $logoPath = $file->storeAs('madrasah', $filename, 'public');

            // Debug logging
            \Log::info('Logo uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $logoPath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
        }

        $madrasah = new Madrasah();
        $madrasah->name = $validated['name'];
        $madrasah->kabupaten = $validated['kabupaten'] ?? null;
        $madrasah->alamat = $validated['alamat'] ?? null;
        $madrasah->latitude = $validated['latitude'] ?? null;
        $madrasah->longitude = $validated['longitude'] ?? null;
        $madrasah->map_link = $validated['map_link'] ?? null;
        $madrasah->logo = $logoPath;
        $madrasah->polygon_koordinat = $validated['polygon_koordinat'] ?? null;
        $madrasah->polygon_koordinat_2 = $validated['polygon_koordinat_2'] ?? null;
        $madrasah->enable_dual_polygon = $validated['enable_dual_polygon'] ?? false;
        $madrasah->hari_kbm = $validated['hari_kbm'] ?? null;
    $madrasah->presensi_masuk_start = $validated['presensi_masuk_start'] ?? null;
    $madrasah->presensi_masuk_end = $validated['presensi_masuk_end'] ?? null;
    $madrasah->presensi_pulang_start = $validated['presensi_pulang_start'] ?? null;
    $madrasah->presensi_pulang_end = $validated['presensi_pulang_end'] ?? null;
    $madrasah->presensi_pulang_jumat = $validated['presensi_pulang_jumat'] ?? null;
    $madrasah->presensi_pulang_sabtu = $validated['presensi_pulang_sabtu'] ?? null;
        $madrasah->save();

        return redirect()->route('madrasah.index')->with('success', 'Madrasah berhasil ditambahkan.');
    }

    /**
     * Update data madrasah
     */
    public function update(Request $request, $id)
    {
        $madrasah = Madrasah::findOrFail($id);

        if ($request->input('polygon_koordinat') === '') {
            $request->merge(['polygon_koordinat' => null]);
        }
        if ($request->input('polygon_koordinat_2') === '') {
            $request->merge(['polygon_koordinat_2' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kabupaten' => 'nullable|in:Kabupaten Bantul,Kabupaten Gunungkidul,Kabupaten Kulon Progo,Kabupaten Sleman,Kota Yogyakarta',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'map_link' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // opsional
            'polygon_koordinat' => 'nullable|json',
            'polygon_koordinat_2' => 'nullable|json',
            'enable_dual_polygon' => 'boolean',
            'hari_kbm' => 'nullable|in:5,6',
            // Presensi schedule fields (time format HH:MM)
            'presensi_masuk_start' => 'nullable|date_format:H:i',
            'presensi_masuk_end' => 'nullable|date_format:H:i',
            'presensi_pulang_start' => 'nullable|date_format:H:i',
            'presensi_pulang_end' => 'nullable|date_format:H:i',
            'presensi_pulang_jumat' => 'nullable|date_format:H:i',
            'presensi_pulang_sabtu' => 'nullable|date_format:H:i',
        ]);

        // Restrict dual polygon to specific madrasah IDs
        $allowedMadrasahIds = [24, 26, 33];
        if ($request->input('enable_dual_polygon') && !in_array($madrasah->id, $allowedMadrasahIds)) {
            return redirect()->back()->with('error', 'Fitur dual polygon hanya tersedia untuk madrasah tertentu (ID: 24, 26, 33).');
        }

        // Jika ada file logo baru, hapus logo lama
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            // Validasi ukuran file (maksimal 2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran file logo terlalu besar. Maksimal 2MB.');
            }

            // Hapus logo lama jika ada
            if ($madrasah->logo && Storage::disk('public')->exists($madrasah->logo)) {
                Storage::disk('public')->delete($madrasah->logo);
                \Log::info('Old logo deleted', ['path' => $madrasah->logo]);
            }

            // Generate nama file yang unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $madrasah->logo = $file->storeAs('madrasah', $filename, 'public');

            // Debug logging
            \Log::info('Logo updated successfully', [
                'madrasah_id' => $madrasah->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $madrasah->logo,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
        }

        $madrasah->name = $validated['name'];
        $madrasah->kabupaten = $validated['kabupaten'];
        $madrasah->alamat = $validated['alamat'];
        $madrasah->latitude = $validated['latitude'];
        $madrasah->longitude = $validated['longitude'];
        $madrasah->map_link = $validated['map_link'];
        $madrasah->polygon_koordinat = $validated['polygon_koordinat'] ?? null;
        $madrasah->polygon_koordinat_2 = $validated['polygon_koordinat_2'] ?? null;
        $madrasah->enable_dual_polygon = $validated['enable_dual_polygon'] ?? false;
        $madrasah->hari_kbm = $validated['hari_kbm'] ?? null;
    $madrasah->presensi_masuk_start = $validated['presensi_masuk_start'] ?? null;
    $madrasah->presensi_masuk_end = $validated['presensi_masuk_end'] ?? null;
    $madrasah->presensi_pulang_start = $validated['presensi_pulang_start'] ?? null;
    $madrasah->presensi_pulang_end = $validated['presensi_pulang_end'] ?? null;
    $madrasah->presensi_pulang_jumat = $validated['presensi_pulang_jumat'] ?? null;
    $madrasah->presensi_pulang_sabtu = $validated['presensi_pulang_sabtu'] ?? null;
        $madrasah->save();

        return redirect()->route('madrasah.index')->with('success', 'Madrasah berhasil diperbarui.');
    }

    /**
     * Hapus madrasah
     */
    public function destroy($id)
    {
        $madrasah = Madrasah::findOrFail($id);

        if ($madrasah->logo && Storage::disk('public')->exists($madrasah->logo)) {
            Storage::disk('public')->delete($madrasah->logo);
        }

        $madrasah->delete();

        return redirect()->route('madrasah.index')->with('success', 'Madrasah berhasil dihapus.');
    }

    /**
     * Import data madrasah dari Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new MadrasahImport, $request->file('file'));
            return redirect()->route('madrasah.index')->with('success', 'Data madrasah berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: '.$e->getMessage());
        }
    }

    /**
     * Tampilkan profile madrasah dengan data tenaga pendidik
     */
    public function profile(Request $request)
    {
        $this->ensureProfileAccess();

        return view('masterdata.madrasah.profile', $this->prepareProfilePayload($request));
    }

    public function exportProfileSummary(Request $request)
    {
        $this->ensureProfileAccess();

        $payload = $this->prepareProfilePayload($request);
        $fileName = 'ringkasan-profile-madrasah-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new MadrasahProfileSummaryExport($payload['schoolSummaryRows']),
            $fileName
        );
    }

    /**
     * Tampilkan detail profile madrasah lengkap
     */
    public function detail($id)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        $madrasah = Madrasah::findOrFail($id);

        // Cari kepala sekolah berdasarkan ketugasan 'kepala_madrasah'
        $kepalaSekolah = \App\Models\User::where('madrasah_id', $id)
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->first();

        // Hitung jumlah TP berdasarkan status kepegawaian
        $tpByStatus = $madrasah->tenagaPendidikUsers->groupBy('statusKepegawaian.name')->map->count();

        // Data untuk edit modal
        $madrasahs = \App\Models\Madrasah::all();
        $statusKepegawaian = \App\Models\StatusKepegawaian::all();

        return view('masterdata.madrasah.detail', compact('madrasah', 'kepalaSekolah', 'tpByStatus', 'madrasahs', 'statusKepegawaian'));
    }

    private function ensureProfileAccess(): void
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }
    }

    private function prepareProfilePayload(Request $request): array
    {
        $search = trim((string) $request->input('search', ''));
        $yayasan_id = $request->integer('yayasan_id') ?: null;
        $kabupaten = trim((string) $request->input('kabupaten', ''));
        $kabupaten = $kabupaten !== '' ? $kabupaten : null;

        $madrasahs = Madrasah::query()
            ->with(['yayasan:id,name'])
            ->withCount('tenagaPendidikUsers')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->when($yayasan_id, fn ($query) => $query->where('yayasan_id', $yayasan_id))
            ->when($kabupaten, fn ($query) => $query->where('kabupaten', $kabupaten))
            ->orderByRaw("CASE WHEN scod IS NULL OR scod = '' THEN 1 ELSE 0 END")
            ->orderBy('scod')
            ->orderBy('name')
            ->get();

        $schoolSummaryRows = $this->buildSchoolSummaryRows($madrasahs);
        $topCompleteSchools = $schoolSummaryRows->take(3)->values();
        $yayasans = Yayasan::query()
            ->has('madrasahs')
            ->orderBy('name')
            ->get(['id', 'name']);

        return [
            'madrasahs' => $madrasahs,
            'yayasans' => $yayasans,
            'search' => $search,
            'yayasan_id' => $yayasan_id,
            'kabupaten' => $kabupaten,
            'schoolSummaryRows' => $schoolSummaryRows,
            'topCompleteSchools' => $topCompleteSchools,
            'summaryStats' => [
                'total_schools' => $schoolSummaryRows->count(),
                'average_completion_percentage' => (int) round((float) $schoolSummaryRows->avg('overall_completion_percentage')),
                'fully_complete_schools' => $schoolSummaryRows->where('overall_completion_percentage', 100)->count(),
                'schools_with_active_period' => $schoolSummaryRows->where('has_active_period', true)->count(),
            ],
        ];
    }

    private function buildSchoolSummaryRows(Collection $madrasahs): Collection
    {
        if ($madrasahs->isEmpty()) {
            return collect();
        }

        $schoolIds = $madrasahs->pluck('id')->filter()->values();
        $today = now('Asia/Jakarta')->startOfDay();
        $monthStart = $today->copy()->startOfMonth();

        $schoolUsers = User::query()
            ->where('role', 'tenaga_pendidik')
            ->whereIn('madrasah_id', $schoolIds)
            ->get()
            ->groupBy('madrasah_id');

        $periodCounts = TeachingSchedulePeriod::query()
            ->whereIn('school_id', $schoolIds)
            ->selectRaw('school_id, COUNT(*) as total_periods')
            ->groupBy('school_id')
            ->pluck('total_periods', 'school_id');

        $activePeriods = TeachingSchedulePeriod::query()
            ->whereIn('school_id', $schoolIds)
            ->whereDate('start_date', '<=', $today->toDateString())
            ->whereDate('end_date', '>=', $today->toDateString())
            ->get()
            ->keyBy('school_id');

        $latestPeriods = TeachingSchedulePeriod::query()
            ->whereIn('school_id', $schoolIds)
            ->orderBy('school_id')
            ->orderByDesc('end_date')
            ->orderByDesc('start_date')
            ->get()
            ->unique('school_id')
            ->keyBy('school_id');

        $activePeriodIds = $activePeriods->pluck('id')->filter()->values();
        $activeSchedules = $activePeriodIds->isEmpty()
            ? collect()
            : TeachingSchedule::query()
                ->whereIn('teaching_schedule_period_id', $activePeriodIds)
                ->get(['id', 'school_id', 'teaching_schedule_period_id', 'teacher_id', 'day']);

        $schedulesBySchool = $activeSchedules->groupBy('school_id');
        $scheduleCountsByPeriod = $activeSchedules->groupBy('teaching_schedule_period_id')->map->count();

        $journalCountsBySchool = $activePeriodIds->isEmpty()
            ? collect()
            : TeachingAttendance::query()
                ->join('teaching_schedules', 'teaching_schedules.id', '=', 'teaching_attendances.teaching_schedule_id')
                ->whereIn('teaching_schedules.teaching_schedule_period_id', $activePeriodIds)
                ->whereDate('teaching_attendances.tanggal', '<=', $today->toDateString())
                ->selectRaw('teaching_schedules.school_id, COUNT(teaching_attendances.id) as total_journals')
                ->groupBy('teaching_schedules.school_id')
                ->pluck('total_journals', 'teaching_schedules.school_id');

        $attendanceCountsBySchool = Presensi::query()
            ->whereIn('madrasah_id', $schoolIds)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $today->toDateString()])
            ->selectRaw('madrasah_id, COUNT(*) as total_presensi')
            ->groupBy('madrasah_id')
            ->pluck('total_presensi', 'madrasah_id');

        $latestImportBatches = SkYayasanImportBatch::query()
            ->whereIn('madrasah_id', $schoolIds)
            ->orderByDesc('uploaded_at')
            ->orderByDesc('id')
            ->get()
            ->unique('madrasah_id')
            ->keyBy('madrasah_id');

        $studentsBySchool = Siswa::query()
            ->with('madrasah:id,scod,name')
            ->whereIn('madrasah_id', $schoolIds)
            ->get()
            ->groupBy('madrasah_id');

        $skSubmissionCounts = SkYayasanRequest::query()
            ->whereIn('madrasah_id', $schoolIds)
            ->selectRaw('madrasah_id, COUNT(*) as total_submissions')
            ->groupBy('madrasah_id')
            ->pluck('total_submissions', 'madrasah_id');

        return $madrasahs
            ->map(function (Madrasah $madrasah) use (
                $schoolUsers,
                $periodCounts,
                $activePeriods,
                $latestPeriods,
                $schedulesBySchool,
                $scheduleCountsByPeriod,
                $journalCountsBySchool,
                $attendanceCountsBySchool,
                $latestImportBatches,
                $studentsBySchool,
                $skSubmissionCounts,
                $today,
                $monthStart
            ) {
                $users = $schoolUsers->get($madrasah->id, collect());
                $teachers = $users->filter(fn (User $user) => !in_array((int) $user->status_kepegawaian_id, [7, 8], true))->values();
                $employees = $users->filter(fn (User $user) => in_array((int) $user->status_kepegawaian_id, [7, 8], true))->values();
                $eligibleTeachers = $teachers->filter(function (User $user) {
                    $ketugasan = mb_strtolower(trim((string) $user->ketugasan));

                    return $ketugasan === '' || !str_contains($ketugasan, 'kepala');
                })->values();

                $activePeriod = $activePeriods->get($madrasah->id);
                $latestPeriod = $latestPeriods->get($madrasah->id);
                $activePeriodId = $activePeriod?->id;
                $schoolSchedules = $schedulesBySchool->get($madrasah->id, collect());
                $teachersWithSchedule = $schoolSchedules
                    ->pluck('teacher_id')
                    ->filter()
                    ->unique()
                    ->intersect($eligibleTeachers->pluck('id'))
                    ->values();

                $totalTeacherEmployees = $users->count();
                $totalTeachers = $teachers->count();
                $totalEmployees = $employees->count();
                $userCompletionPercentage = $totalTeacherEmployees > 0
                    ? (float) round($users->avg(fn (User $user) => $this->calculateUserCompletionStats($user)['percentage']), 1)
                    : 0.0;

                $attendanceWorkingDays = $this->countWorkingDaysBetween(
                    $monthStart->copy(),
                    $today->copy(),
                    (int) ($madrasah->hari_kbm ?: 5)
                );
                $expectedAttendance = $totalTeacherEmployees * $attendanceWorkingDays;
                $actualAttendance = (int) ($attendanceCountsBySchool[$madrasah->id] ?? 0);
                $attendanceDisciplinePercentage = $expectedAttendance > 0
                    ? round(min(100, ($actualAttendance / $expectedAttendance) * 100), 1)
                    : 0.0;

                $totalPeriods = (int) ($periodCounts[$madrasah->id] ?? 0);
                $totalTeachersWithSchedule = $teachersWithSchedule->count();
                $totalTeachersWithoutSchedule = max(0, $eligibleTeachers->count() - $totalTeachersWithSchedule);
                $scheduleCoveragePercentage = $eligibleTeachers->count() > 0
                    ? round(min(100, ($totalTeachersWithSchedule / $eligibleTeachers->count()) * 100), 1)
                    : 0.0;

                $journalExpectedMeetings = 0;
                if ($activePeriod) {
                    $periodStart = $activePeriod->start_date->copy()->startOfDay();
                    $periodEnd = $activePeriod->end_date->copy()->startOfDay()->min($today->copy());

                    foreach ($schoolSchedules as $schedule) {
                        $journalExpectedMeetings += $this->countScheduleOccurrences(
                            (string) $schedule->day,
                            $periodStart->copy(),
                            $periodEnd->copy()
                        );
                    }
                }

                $totalTeachingAttendances = (int) ($journalCountsBySchool[$madrasah->id] ?? 0);
                $journalDisciplinePercentage = $journalExpectedMeetings > 0
                    ? round(min(100, ($totalTeachingAttendances / $journalExpectedMeetings) * 100), 1)
                    : 0.0;

                $latestBatch = $latestImportBatches->get($madrasah->id);
                $totalSkSubmissions = (int) ($skSubmissionCounts[$madrasah->id] ?? 0);
                $skCompletenessPercentage = $latestBatch && (int) $latestBatch->total_rows > 0
                    ? round(min(100, (((int) $latestBatch->valid_rows) / ((int) $latestBatch->total_rows)) * 100), 1)
                    : ($totalTeacherEmployees > 0
                        ? round(min(100, ($totalSkSubmissions / $totalTeacherEmployees) * 100), 1)
                        : 0.0);

                $students = $studentsBySchool->get($madrasah->id, collect());
                $totalStudents = $students->count();
                $studentCompletionPercentage = $totalStudents > 0
                    ? (float) round($students->avg(fn (Siswa $siswa) => $this->calculateStudentCompletionStats($siswa)['percentage']), 1)
                    : 0.0;

                $categoryScores = [
                    'users' => $userCompletionPercentage,
                    'presensi' => $attendanceDisciplinePercentage,
                    'jadwal' => $scheduleCoveragePercentage,
                    'jurnal' => $journalDisciplinePercentage,
                    'sk' => $skCompletenessPercentage,
                    'siswa' => $studentCompletionPercentage,
                ];

                return [
                    'school_id' => $madrasah->id,
                    'scod' => $madrasah->scod ?: '-',
                    'school_name' => $madrasah->name,
                    'yayasan_name' => $madrasah->yayasan?->name ?: '-',
                    'kabupaten' => $madrasah->kabupaten ?: '-',
                    'total_teacher_employees' => $totalTeacherEmployees,
                    'total_teachers' => $totalTeachers,
                    'total_employees' => $totalEmployees,
                    'user_completion_percentage' => $userCompletionPercentage,
                    'attendance_month_label' => $monthStart->translatedFormat('F Y'),
                    'actual_attendance' => $actualAttendance,
                    'expected_attendance' => $expectedAttendance,
                    'attendance_discipline_percentage' => $attendanceDisciplinePercentage,
                    'has_active_period' => (bool) $activePeriod,
                    'total_periods' => $totalPeriods,
                    'active_period_label' => $activePeriod?->summary_label ?: '-',
                    'latest_period_label' => $latestPeriod?->summary_label ?: '-',
                    'active_period_status' => $activePeriod ? 'Aktif' : 'Belum ada periode aktif',
                    'eligible_teacher_total' => $eligibleTeachers->count(),
                    'total_teachers_with_schedule' => $totalTeachersWithSchedule,
                    'total_teachers_without_schedule' => $totalTeachersWithoutSchedule,
                    'schedule_coverage_percentage' => $scheduleCoveragePercentage,
                    'total_teaching_schedules' => (int) ($activePeriodId ? ($scheduleCountsByPeriod[$activePeriodId] ?? 0) : 0),
                    'total_teaching_attendances' => $totalTeachingAttendances,
                    'journal_expected_meetings' => $journalExpectedMeetings,
                    'journal_discipline_percentage' => $journalDisciplinePercentage,
                    'total_sk_submissions' => $totalSkSubmissions,
                    'sk_completeness_percentage' => $skCompletenessPercentage,
                    'sk_latest_batch_total_rows' => (int) ($latestBatch->total_rows ?? 0),
                    'sk_latest_batch_valid_rows' => (int) ($latestBatch->valid_rows ?? 0),
                    'sk_latest_batch_status' => $latestBatch?->status ?: '-',
                    'total_students' => $totalStudents,
                    'student_completion_percentage' => $studentCompletionPercentage,
                    'filled_indicator_count' => collect($categoryScores)->filter(fn ($score) => (float) $score >= 100)->count(),
                    'overall_completion_percentage' => (float) round(collect($categoryScores)->avg(), 1),
                ];
            })
            ->sortByDesc(function (array $row) {
                return sprintf(
                    '%06.1f-%06.1f-%06.1f-%06.1f-%06d',
                    $row['overall_completion_percentage'],
                    $row['attendance_discipline_percentage'],
                    $row['schedule_coverage_percentage'],
                    $row['journal_discipline_percentage'],
                    $row['total_students']
                );
            })
            ->values()
            ->map(function (array $row, int $index) {
                $row['rank'] = $index + 1;

                return $row;
            });
    }

    private function calculateUserCompletionStats(User $user): array
    {
        $fields = [
            $user->name,
            $user->email,
            $user->no_hp,
            $user->tempat_lahir,
            $user->tanggal_lahir,
            $user->status_kepegawaian_id,
            $user->ketugasan,
            $user->madrasah_id,
        ];

        $filled = collect($fields)->filter(fn ($value) => $this->fieldHasValue($value))->count();
        $total = count($fields);

        return [
            'filled' => $filled,
            'total' => $total,
            'percentage' => $total > 0 ? (int) round(($filled / $total) * 100) : 0,
        ];
    }

    private function calculateStudentCompletionStats(Siswa $siswa): array
    {
        $fields = [
            $siswa->scod ?: $siswa->madrasah?->scod,
            $siswa->nama_madrasah ?: $siswa->madrasah?->name,
            $siswa->nis,
            $siswa->nisn,
            $siswa->nik,
            $siswa->no_kk,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir,
            $siswa->agama,
            $siswa->kelas,
            $siswa->jurusan,
            $siswa->alamat,
            $siswa->dusun,
            $siswa->kelurahan,
            $siswa->kecamatan,
            $siswa->no_hp,
            $siswa->email,
            $siswa->nama_ayah,
            $siswa->nama_ibu,
            $siswa->no_hp_orang_tua_wali,
        ];

        $filled = collect($fields)->filter(fn ($value) => $this->fieldHasValue($value))->count();
        $total = count($fields);

        return [
            'filled' => $filled,
            'total' => $total,
            'percentage' => $total > 0 ? (int) round(($filled / $total) * 100) : 0,
        ];
    }

    private function fieldHasValue(mixed $value): bool
    {
        if ($value instanceof \DateTimeInterface) {
            return true;
        }

        return trim((string) $value) !== '';
    }

    private function countWorkingDaysBetween(Carbon $startDate, Carbon $endDate, int $hariKbm = 5): int
    {
        if ($endDate->lt($startDate)) {
            return 0;
        }

        $holidayDates = Holiday::query()
            ->where('is_active', true)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        $workingDays = 0;
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $dayOfWeek = $cursor->dayOfWeek;
            $isWorkingDay = $hariKbm === 6
                ? ($dayOfWeek >= 1 && $dayOfWeek <= 6)
                : ($dayOfWeek >= 1 && $dayOfWeek <= 5);

            if ($isWorkingDay && !$holidayDates->has($cursor->toDateString())) {
                $workingDays++;
            }

            $cursor->addDay();
        }

        return $workingDays;
    }

    private function countScheduleOccurrences(string $dayLabel, Carbon $startDate, Carbon $endDate): int
    {
        if ($endDate->lt($startDate)) {
            return 0;
        }

        $dayMap = [
            'minggu' => 0,
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
        ];

        $targetDay = $dayMap[mb_strtolower(trim($dayLabel))] ?? null;
        if ($targetDay === null) {
            return 0;
        }

        $holidayDates = Holiday::query()
            ->where('is_active', true)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        $count = 0;
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            if ($cursor->dayOfWeek === $targetDay && !$holidayDates->has($cursor->toDateString())) {
                $count++;
            }

            $cursor->addDay();
        }

        return $count;
    }
}
