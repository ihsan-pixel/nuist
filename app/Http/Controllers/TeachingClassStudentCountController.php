<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\TeachingAttendance;
use App\Models\TeachingClassStudentCount;
use App\Models\TeachingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeachingClassStudentCountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $schoolsQuery = Madrasah::query()
            ->orderBy('kabupaten')
            ->orderBy('scod')
            ->orderBy('name');

        if ($user->role === 'admin') {
            $schoolsQuery->where('id', $user->madrasah_id);
        }

        $schools = $schoolsQuery->get(['id', 'name', 'kabupaten', 'scod']);
        $allowedSchoolIds = $schools->pluck('id');
        $selectedSchoolId = $request->integer('school_id') ?: null;

        if ($selectedSchoolId && ! $allowedSchoolIds->contains($selectedSchoolId)) {
            abort(403);
        }

        $activeSchoolIds = $selectedSchoolId ? collect([$selectedSchoolId]) : $allowedSchoolIds;

        $rows = collect();
        $schoolsById = $schools->keyBy('id');

        if ($activeSchoolIds->isNotEmpty()) {
            $this->mergeScheduleClasses($rows, $activeSchoolIds, $schoolsById);
            $this->mergeLatestAttendanceTotals($rows, $activeSchoolIds, $schoolsById);
            $this->mergeSavedCounts($rows, $activeSchoolIds, $schoolsById);
        }

        $rows = $rows->values()->sort(function ($first, $second) {
            return [$first['school_name'], $first['class_name']]
                <=> [$second['school_name'], $second['class_name']];
        })->values();

        $stats = [
            'total_classes' => $rows->count(),
            'saved_counts' => $rows->whereNotNull('count_id')->count(),
            'missing_counts' => $rows->whereNull('count_id')->count(),
            'attendance_references' => $rows->whereNotNull('latest_attendance_total')->count(),
        ];

        return view('masterdata.class-student-counts.index', compact(
            'rows',
            'schools',
            'selectedSchoolId',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'class_name' => trim((string) $request->input('class_name', '')),
        ]);

        $validated = $request->validate([
            'school_id' => ['required', 'exists:madrasahs,id'],
            'class_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teaching_class_student_counts', 'class_name')
                    ->where(fn ($query) => $query->where('school_id', $request->input('school_id'))),
            ],
            'total_students' => ['required', 'integer', 'min:1', 'max:10000'],
        ], [
            'class_name.unique' => 'Jumlah siswa untuk kelas ini sudah ada. Gunakan tombol edit pada baris kelas tersebut.',
        ]);

        $this->authorizeSchool((int) $validated['school_id']);

        TeachingClassStudentCount::create([
            'school_id' => $validated['school_id'],
            'class_name' => $validated['class_name'],
            'total_students' => $validated['total_students'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('class-student-counts.index', $this->redirectFilter($validated['school_id']))
            ->with('success', 'Jumlah siswa kelas berhasil ditambahkan.');
    }

    public function update(Request $request, TeachingClassStudentCount $classStudentCount)
    {
        $this->authorizeSchool((int) $classStudentCount->school_id);

        $request->merge([
            'class_name' => trim((string) $request->input('class_name', '')),
        ]);

        $validated = $request->validate([
            'class_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teaching_class_student_counts', 'class_name')
                    ->where(fn ($query) => $query->where('school_id', $classStudentCount->school_id))
                    ->ignore($classStudentCount->id),
            ],
            'total_students' => ['required', 'integer', 'min:1', 'max:10000'],
        ], [
            'class_name.unique' => 'Jumlah siswa untuk kelas ini sudah ada.',
        ]);

        $classStudentCount->update([
            'class_name' => $validated['class_name'],
            'total_students' => $validated['total_students'],
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('class-student-counts.index', $this->redirectFilter($classStudentCount->school_id))
            ->with('success', 'Jumlah siswa kelas berhasil diperbarui.');
    }

    private function mergeScheduleClasses($rows, $activeSchoolIds, $schoolsById): void
    {
        TeachingSchedule::query()
            ->whereIn('school_id', $activeSchoolIds)
            ->whereNotNull('class_name')
            ->get(['school_id', 'class_name'])
            ->filter(fn ($schedule) => trim((string) $schedule->class_name) !== '')
            ->groupBy(fn ($schedule) => $this->rowKey($schedule->school_id, $schedule->class_name))
            ->each(function ($classes) use ($rows, $schoolsById) {
                $schedule = $classes->first();
                $row = $this->baseRow($schedule->school_id, $schedule->class_name, $schoolsById);
                $row['schedule_count'] = $classes->count();
                $rows->put($this->rowKey($schedule->school_id, $schedule->class_name), $row);
            });
    }

    private function mergeLatestAttendanceTotals($rows, $activeSchoolIds, $schoolsById): void
    {
        TeachingAttendance::query()
            ->join('teaching_schedules', 'teaching_schedules.id', '=', 'teaching_attendances.teaching_schedule_id')
            ->whereIn('teaching_schedules.school_id', $activeSchoolIds)
            ->whereNotNull('teaching_schedules.class_name')
            ->whereNotNull('teaching_attendances.class_total_students')
            ->orderByDesc('teaching_attendances.tanggal')
            ->orderByDesc('teaching_attendances.waktu')
            ->orderByDesc('teaching_attendances.id')
            ->get([
                'teaching_schedules.school_id',
                'teaching_schedules.class_name',
                'teaching_attendances.class_total_students',
                'teaching_attendances.tanggal',
                'teaching_attendances.waktu',
            ])
            ->filter(fn ($attendance) => trim((string) $attendance->class_name) !== '')
            ->each(function ($attendance) use ($rows, $schoolsById) {
                $key = $this->rowKey($attendance->school_id, $attendance->class_name);

                if ($rows->has($key) && ! is_null($rows->get($key)['latest_attendance_total'])) {
                    return;
                }

                $row = $rows->get($key, $this->baseRow($attendance->school_id, $attendance->class_name, $schoolsById));
                $row['latest_attendance_total'] = (int) $attendance->class_total_students;
                $row['latest_attendance_date'] = $attendance->tanggal;
                $row['latest_attendance_time'] = $attendance->waktu;
                $rows->put($key, $row);
            });
    }

    private function mergeSavedCounts($rows, $activeSchoolIds, $schoolsById): void
    {
        TeachingClassStudentCount::with(['updater:id,name'])
            ->whereIn('school_id', $activeSchoolIds)
            ->get()
            ->each(function ($count) use ($rows, $schoolsById) {
                $key = $this->rowKey($count->school_id, $count->class_name);
                $row = $rows->get($key, $this->baseRow($count->school_id, $count->class_name, $schoolsById));

                $row['count_id'] = $count->id;
                $row['class_name'] = $count->class_name;
                $row['total_students'] = (int) $count->total_students;
                $row['updated_at'] = $count->updated_at;
                $row['updated_by'] = optional($count->updater)->name;

                $rows->put($key, $row);
            });
    }

    private function baseRow($schoolId, $className, $schoolsById): array
    {
        $school = $schoolsById->get($schoolId);

        return [
            'school_id' => $schoolId,
            'school_name' => $school->name ?? '-',
            'school_kabupaten' => $school->kabupaten ?? '-',
            'class_name' => trim((string) $className),
            'schedule_count' => 0,
            'count_id' => null,
            'total_students' => null,
            'latest_attendance_total' => null,
            'latest_attendance_date' => null,
            'latest_attendance_time' => null,
            'updated_at' => null,
            'updated_by' => null,
        ];
    }

    private function rowKey($schoolId, $className): string
    {
        return $schoolId.'|'.strtolower(trim((string) $className));
    }

    private function authorizeSchool(int $schoolId): void
    {
        $user = Auth::user();

        if ($user->role === 'admin' && (int) $user->madrasah_id !== $schoolId) {
            abort(403);
        }
    }

    private function redirectFilter(int $schoolId): array
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return [];
        }

        return ['school_id' => $schoolId];
    }
}
