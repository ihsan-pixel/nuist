<?php

namespace App\Http\Controllers;

use App\Models\TeachingSchedulePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TeachingSchedulePeriodController extends Controller
{
    private const SEMESTERS = [
        TeachingSchedulePeriod::SEMESTER_GANJIL,
        TeachingSchedulePeriod::SEMESTER_GENAP,
    ];

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);
        $this->authorizeSchool((int) $validated['school_id']);
        $this->ensureNoOverlap(
            (int) $validated['school_id'],
            $validated['start_date'],
            $validated['end_date']
        );

        TeachingSchedulePeriod::create([
            'school_id' => $validated['school_id'],
            'title' => $validated['title'],
            'school_year' => $validated['school_year'],
            'semester' => $validated['semester'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Periode jadwal mengajar berhasil ditambahkan.');
    }

    public function update(Request $request, TeachingSchedulePeriod $teachingSchedulePeriod)
    {
        $this->authorizeSchool((int) $teachingSchedulePeriod->school_id);

        $validated = $this->validatePayload($request, $teachingSchedulePeriod->school_id);
        $this->ensureNoOverlap(
            (int) $teachingSchedulePeriod->school_id,
            $validated['start_date'],
            $validated['end_date'],
            $teachingSchedulePeriod->id
        );

        $teachingSchedulePeriod->update([
            'title' => $validated['title'],
            'school_year' => $validated['school_year'],
            'semester' => $validated['semester'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Periode jadwal mengajar berhasil diperbarui.');
    }

    private function validatePayload(Request $request, ?int $lockedSchoolId = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'school_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', Rule::in(self::SEMESTERS)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];

        if ($lockedSchoolId === null) {
            $rules['school_id'] = ['required', 'exists:madrasahs,id'];
        }

        $validated = $request->validate($rules);

        if ($lockedSchoolId !== null) {
            $validated['school_id'] = $lockedSchoolId;
        }

        return $validated;
    }

    private function authorizeSchool(int $schoolId): void
    {
        $user = Auth::user();

        if ($user->role === 'admin' && (int) $user->madrasah_id !== $schoolId) {
            abort(403);
        }

        if (!in_array($user->role, ['admin', 'super_admin'], true)) {
            abort(403);
        }
    }

    private function ensureNoOverlap(int $schoolId, string $startDate, string $endDate, ?int $exceptId = null): void
    {
        $query = TeachingSchedulePeriod::query()
            ->where('school_id', $schoolId)
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'period_overlap' => 'Periode jadwal mengajar tidak boleh bertumpang tindih dengan periode lain pada sekolah yang sama.',
            ]);
        }
    }
}
