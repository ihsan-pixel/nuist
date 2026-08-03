<?php

namespace App\Http\Controllers;

use App\Exports\MadrasahProfileSummaryExport;
use App\Models\Madrasah;
use App\Models\SkYayasanRequest;
use App\Models\Siswa;
use App\Models\TeachingAttendance;
use App\Models\TeachingClassStudentCount;
use App\Models\TeachingSchedule;
use App\Models\TeachingSchedulePeriod;
use App\Models\User;
use App\Models\Yayasan;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
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
                'schools_with_students' => $schoolSummaryRows->where('total_students', '>', 0)->count(),
            ],
        ];
    }

    private function buildSchoolSummaryRows(Collection $madrasahs): Collection
    {
        if ($madrasahs->isEmpty()) {
            return collect();
        }

        $schoolIds = $madrasahs->pluck('id')->filter()->values();
        $today = now('Asia/Jakarta')->toDateString();

        $teacherEmployeeCounts = User::query()
            ->where('role', 'tenaga_pendidik')
            ->whereIn('madrasah_id', $schoolIds)
            ->selectRaw('madrasah_id')
            ->selectRaw('SUM(CASE WHEN status_kepegawaian_id IN (3, 4, 5, 6) THEN 1 ELSE 0 END) as total_teachers')
            ->selectRaw('SUM(CASE WHEN status_kepegawaian_id IN (7, 8) THEN 1 ELSE 0 END) as total_employees')
            ->selectRaw('COUNT(*) as total_teacher_employees')
            ->groupBy('madrasah_id')
            ->get()
            ->keyBy('madrasah_id');

        $periodCounts = TeachingSchedulePeriod::query()
            ->whereIn('school_id', $schoolIds)
            ->selectRaw('school_id, COUNT(*) as total_periods')
            ->groupBy('school_id')
            ->pluck('total_periods', 'school_id');

        $selectedPeriods = TeachingSchedulePeriod::query()
            ->whereIn('school_id', $schoolIds)
            ->orderBy('school_id')
            ->orderByRaw(
                "CASE WHEN start_date <= ? AND end_date >= ? THEN 1 ELSE 0 END DESC",
                [$today, $today]
            )
            ->orderByDesc('end_date')
            ->orderByDesc('start_date')
            ->get()
            ->unique('school_id')
            ->keyBy('school_id');

        $selectedPeriodIds = $selectedPeriods->pluck('id')->filter()->values();

        $scheduleCountsByPeriod = $selectedPeriodIds->isEmpty()
            ? collect()
            : TeachingSchedule::query()
                ->whereIn('teaching_schedule_period_id', $selectedPeriodIds)
                ->selectRaw('teaching_schedule_period_id, COUNT(*) as total_schedules')
                ->groupBy('teaching_schedule_period_id')
                ->pluck('total_schedules', 'teaching_schedule_period_id');

        $teachingAttendanceCountsByPeriod = $selectedPeriodIds->isEmpty()
            ? collect()
            : TeachingAttendance::query()
                ->join('teaching_schedules', 'teaching_schedules.id', '=', 'teaching_attendances.teaching_schedule_id')
                ->whereIn('teaching_schedules.teaching_schedule_period_id', $selectedPeriodIds)
                ->selectRaw('teaching_schedules.teaching_schedule_period_id as period_id, COUNT(teaching_attendances.id) as total_attendances')
                ->groupBy('teaching_schedules.teaching_schedule_period_id')
                ->pluck('total_attendances', 'period_id');

        $classStudentCountsByPeriod = $selectedPeriodIds->isEmpty()
            ? collect()
            : TeachingClassStudentCount::query()
                ->whereIn('teaching_schedule_period_id', $selectedPeriodIds)
                ->selectRaw('teaching_schedule_period_id, COUNT(*) as total_class_records, COALESCE(SUM(total_students), 0) as total_class_students')
                ->groupBy('teaching_schedule_period_id')
                ->get()
                ->keyBy('teaching_schedule_period_id');

        $studentCounts = Siswa::query()
            ->whereIn('madrasah_id', $schoolIds)
            ->selectRaw('madrasah_id, COUNT(*) as total_students')
            ->groupBy('madrasah_id')
            ->pluck('total_students', 'madrasah_id');

        $skSubmissionCounts = SkYayasanRequest::query()
            ->whereIn('madrasah_id', $schoolIds)
            ->selectRaw('madrasah_id, COUNT(*) as total_submissions')
            ->groupBy('madrasah_id')
            ->pluck('total_submissions', 'madrasah_id');

        return $madrasahs
            ->map(function (Madrasah $madrasah) use (
                $teacherEmployeeCounts,
                $periodCounts,
                $selectedPeriods,
                $scheduleCountsByPeriod,
                $teachingAttendanceCountsByPeriod,
                $classStudentCountsByPeriod,
                $studentCounts,
                $skSubmissionCounts,
                $today
            ) {
                $staffCounts = $teacherEmployeeCounts->get($madrasah->id);
                $selectedPeriod = $selectedPeriods->get($madrasah->id);
                $selectedPeriodId = $selectedPeriod?->id;
                $classStudentSummary = $selectedPeriodId ? $classStudentCountsByPeriod->get($selectedPeriodId) : null;
                $presensiConfigFilled = collect([
                    $madrasah->presensi_masuk_start,
                    $madrasah->presensi_masuk_end,
                    $madrasah->presensi_pulang_start,
                    $madrasah->presensi_pulang_end,
                    $madrasah->presensi_pulang_jumat,
                    $madrasah->presensi_pulang_sabtu,
                ])->filter(fn ($value) => trim((string) $value) !== '')->count();

                $presensiConfigTotal = 6;
                $presensiConfigPercentage = (int) round(($presensiConfigFilled / $presensiConfigTotal) * 100);
                $totalTeachers = (int) ($staffCounts->total_teachers ?? 0);
                $totalEmployees = (int) ($staffCounts->total_employees ?? 0);
                $totalTeacherEmployees = (int) ($staffCounts->total_teacher_employees ?? 0);
                $totalPeriods = (int) ($periodCounts[$madrasah->id] ?? 0);
                $totalSchedules = (int) ($selectedPeriodId ? ($scheduleCountsByPeriod[$selectedPeriodId] ?? 0) : 0);
                $totalTeachingAttendances = (int) ($selectedPeriodId ? ($teachingAttendanceCountsByPeriod[$selectedPeriodId] ?? 0) : 0);
                $totalClassStudentRecords = (int) ($classStudentSummary->total_class_records ?? 0);
                $totalClassStudents = (int) ($classStudentSummary->total_class_students ?? 0);
                $totalStudents = (int) ($studentCounts[$madrasah->id] ?? 0);
                $totalSkSubmissions = (int) ($skSubmissionCounts[$madrasah->id] ?? 0);
                $isSelectedPeriodActive = $selectedPeriod
                    && $selectedPeriod->start_date
                    && $selectedPeriod->end_date
                    && $selectedPeriod->start_date->toDateString() <= $today
                    && $selectedPeriod->end_date->toDateString() >= $today;

                $teacherPercentage = $totalTeachers > 0 ? 100 : 0;
                $employeePercentage = $totalEmployees > 0 ? 100 : 0;
                $teacherEmployeePercentage = (int) round(($teacherPercentage + $employeePercentage) / 2);
                $skSubmissionPercentage = $totalSkSubmissions > 0 ? 100 : 0;
                $studentPercentage = $totalStudents > 0 ? 100 : 0;
                $categoryScores = [
                    'guru' => $teacherPercentage,
                    'pegawai' => $employeePercentage,
                    'guru_pegawai' => $teacherEmployeePercentage,
                    'pengajuan_sk' => $skSubmissionPercentage,
                    'siswa' => $studentPercentage,
                ];

                return [
                    'school_id' => $madrasah->id,
                    'scod' => $madrasah->scod ?: '-',
                    'school_name' => $madrasah->name,
                    'yayasan_name' => $madrasah->yayasan?->name ?: '-',
                    'kabupaten' => $madrasah->kabupaten ?: '-',
                    'total_teachers' => $totalTeachers,
                    'total_teachers_percentage' => $teacherPercentage,
                    'total_employees' => $totalEmployees,
                    'total_employees_percentage' => $employeePercentage,
                    'total_teacher_employees' => $totalTeacherEmployees,
                    'total_teacher_employees_percentage' => $teacherEmployeePercentage,
                    'presensi_config_filled' => $presensiConfigFilled,
                    'presensi_config_total' => $presensiConfigTotal,
                    'presensi_config_percentage' => $presensiConfigPercentage,
                    'total_periods' => $totalPeriods,
                    'selected_period_label' => $selectedPeriod?->summary_label ?: '-',
                    'selected_period_scope' => $selectedPeriod ? ($isSelectedPeriodActive ? 'Aktif' : 'Terakhir') : '-',
                    'total_teaching_schedules' => $totalSchedules,
                    'total_teaching_attendances' => $totalTeachingAttendances,
                    'total_class_student_records' => $totalClassStudentRecords,
                    'total_class_students' => $totalClassStudents,
                    'total_sk_submissions' => $totalSkSubmissions,
                    'total_sk_submissions_percentage' => $skSubmissionPercentage,
                    'total_students' => $totalStudents,
                    'total_students_percentage' => $studentPercentage,
                    'filled_indicator_count' => collect($categoryScores)->filter(fn ($score) => (int) $score >= 100)->count(),
                    'overall_completion_percentage' => (int) round(collect($categoryScores)->avg()),
                ];
            })
            ->sortByDesc(function (array $row) {
                return sprintf(
                    '%03d-%06d-%06d-%06d',
                    $row['overall_completion_percentage'],
                    $row['total_teacher_employees'],
                    $row['total_students'],
                    $row['total_teaching_schedules']
                );
            })
            ->values()
            ->map(function (array $row, int $index) {
                $row['rank'] = $index + 1;

                return $row;
            });
    }
}
