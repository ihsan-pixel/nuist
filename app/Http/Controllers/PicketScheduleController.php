<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\PicketSchedulePeriod;
use App\Models\PicketScheduleSubmission;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PicketScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $selectedSchoolId = $this->resolveSelectedSchoolId($user, request(), false);
        $schools = $user->role === 'super_admin'
            ? Madrasah::orderBy('name')->get(['id', 'name'])
            : collect();
        $school = $selectedSchoolId ? Madrasah::findOrFail($selectedSchoolId) : null;

        $periods = PicketSchedulePeriod::query()
            ->with(['school'])
            ->withCount([
                'submissions',
                'submissions as submissions_pending_count' => fn ($query) => $query->where('approval_status', PicketScheduleSubmission::APPROVAL_PENDING),
                'submissions as submissions_approved_count' => fn ($query) => $query->where('approval_status', PicketScheduleSubmission::APPROVAL_APPROVED),
                'submissions as submissions_rejected_count' => fn ($query) => $query->where('approval_status', PicketScheduleSubmission::APPROVAL_REJECTED),
            ])
            ->when($selectedSchoolId, fn ($query) => $query->where('school_id', $selectedSchoolId))
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        return view('picket-schedules.index', compact('periods', 'school', 'schools', 'selectedSchoolId'));
    }

    public function create()
    {
        $user = Auth::user();
        $selectedSchoolId = old('school_id');
        $selectedSchoolId = $selectedSchoolId !== null && $selectedSchoolId !== ''
            ? (int) $selectedSchoolId
            : $this->resolveSelectedSchoolId($user, request(), false);
        $startDate = old('start_date', now('Asia/Jakarta')->toDateString());
        $endDate = old('end_date', now('Asia/Jakarta')->addWeek()->toDateString());
        $school = $selectedSchoolId ? Madrasah::findOrFail($selectedSchoolId) : null;
        $schools = $user->role === 'super_admin'
            ? Madrasah::orderBy('name')->get(['id', 'name'])
            : collect();

        return view('picket-schedules.form', [
            'period' => new PicketSchedulePeriod([
                'school_id' => $selectedSchoolId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => true,
            ]),
            'school' => $school,
            'schools' => $schools,
            'teachers' => $selectedSchoolId ? $this->getTeachersForSchool((int) $selectedSchoolId) : collect(),
            'dateChoices' => $selectedSchoolId ? $this->buildDateChoicesFromRange(
                $startDate,
                $endDate,
            ) : [],
            'existingSelections' => [],
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $this->validateAdminPayload($request);
        $validated['school_id'] = $this->resolveSelectedSchoolId($user, $request);

        $this->ensureNoConflict($validated['school_id'], $validated);

        DB::transaction(function () use ($validated, $user, $request) {
            $period = PicketSchedulePeriod::create(array_merge($validated, [
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]));

            $this->syncAdminSubmissions($period, $request->input('teacher_dates', []));
        });

        return redirect()
            ->route('picket-schedule-periods.index')
            ->with('success', 'Periode izin jadwal piket berhasil ditambahkan dan disusun oleh admin sekolah.');
    }

    public function edit(PicketSchedulePeriod $picketSchedulePeriod)
    {
        $this->authorizePeriod($picketSchedulePeriod);
        $selectedSchoolId = Auth::user()->role === 'super_admin'
            ? (int) old('school_id', $picketSchedulePeriod->school_id)
            : (int) $picketSchedulePeriod->school_id;
        $startDate = old('start_date', optional($picketSchedulePeriod->start_date)->format('Y-m-d') ?: $picketSchedulePeriod->getRawOriginal('start_date'));
        $endDate = old('end_date', optional($picketSchedulePeriod->end_date)->format('Y-m-d') ?: $picketSchedulePeriod->getRawOriginal('end_date'));

        return view('picket-schedules.form', [
            'period' => $picketSchedulePeriod,
            'school' => Madrasah::find($selectedSchoolId) ?: $picketSchedulePeriod->school,
            'schools' => Auth::user()->role === 'super_admin'
                ? Madrasah::orderBy('name')->get(['id', 'name'])
                : collect(),
            'teachers' => $this->getTeachersForSchool($selectedSchoolId),
            'dateChoices' => $this->buildDateChoicesFromRange($startDate, $endDate),
            'existingSelections' => $picketSchedulePeriod->submissions()
                ->get(['user_id', 'selected_dates'])
                ->mapWithKeys(fn (PicketScheduleSubmission $submission) => [
                    $submission->user_id => collect($submission->selected_dates ?? [])->values()->all(),
                ])
                ->all(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, PicketSchedulePeriod $picketSchedulePeriod)
    {
        $this->authorizePeriod($picketSchedulePeriod);

        $validated = $this->validateAdminPayload($request);
        $validated['school_id'] = Auth::user()->role === 'super_admin'
            ? $this->resolveSelectedSchoolId(Auth::user(), $request)
            : (int) $picketSchedulePeriod->school_id;

        $this->ensureNoConflict($validated['school_id'], $validated, $picketSchedulePeriod->id);

        DB::transaction(function () use ($picketSchedulePeriod, $validated, $request) {
            $picketSchedulePeriod->update(array_merge($validated, [
                'updated_by' => Auth::id(),
            ]));

            $this->syncAdminSubmissions($picketSchedulePeriod->fresh(), $request->input('teacher_dates', []));
        });

        return redirect()
            ->route('picket-schedule-periods.index')
            ->with('success', 'Periode izin jadwal piket berhasil diperbarui dan diajukan ulang oleh admin sekolah.');
    }

    public function destroy(PicketSchedulePeriod $picketSchedulePeriod)
    {
        $this->authorizePeriod($picketSchedulePeriod);
        $picketSchedulePeriod->delete();

        return redirect()
            ->route('picket-schedule-periods.index')
            ->with('success', 'Periode izin jadwal piket berhasil dihapus.');
    }

    public function mobileIndex()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'tenaga_pendidik' || !$user->madrasah_id) {
            abort(403);
        }

        $periods = PicketSchedulePeriod::query()
            ->with([
                'school',
                'submissions' => fn ($query) => $query->where('user_id', $user->id)->with('approver'),
            ])
            ->where('school_id', $user->madrasah_id)
            ->where('is_active', true)
            ->orderBy('start_date')
            ->orderBy('id')
            ->get();

        $periods->each(function (PicketSchedulePeriod $period) {
            $period->setAttribute('date_choices', $this->buildDateChoices($period));
            $period->setRelation('currentSubmission', $period->submissions->first());
        });

        return view('mobile.picket-schedules.index', compact('periods'));
    }

    public function mobileSubmit(Request $request, PicketSchedulePeriod $picketSchedulePeriod)
    {
        abort(403, 'Pengajuan izin jadwal piket hanya dapat disusun oleh admin sekolah.');
    }

    public function principalApprove(Request $request, PicketScheduleSubmission $picketScheduleSubmission)
    {
        $schoolId = $this->resolvePrincipalSchoolId(Auth::user());

        if ((int) $picketScheduleSubmission->period->school_id !== $schoolId) {
            abort(403);
        }

        $validated = $request->validate([
            'approval_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $picketScheduleSubmission->update([
            'approval_status' => PicketScheduleSubmission::APPROVAL_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now('Asia/Jakarta'),
            'approval_notes' => trim((string) ($validated['approval_notes'] ?? '')) ?: null,
        ]);

        return redirect()
            ->route('mobile.academic-calendar-approvals')
            ->with('success', 'Pengajuan izin jadwal piket berhasil disetujui.');
    }

    public function principalReject(Request $request, PicketScheduleSubmission $picketScheduleSubmission)
    {
        $schoolId = $this->resolvePrincipalSchoolId(Auth::user());

        if ((int) $picketScheduleSubmission->period->school_id !== $schoolId) {
            abort(403);
        }

        $validated = $request->validate([
            'approval_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $picketScheduleSubmission->update([
            'approval_status' => PicketScheduleSubmission::APPROVAL_REJECTED,
            'approved_by' => Auth::id(),
            'approved_at' => now('Asia/Jakarta'),
            'approval_notes' => trim((string) ($validated['approval_notes'] ?? '')) ?: null,
        ]);

        return redirect()
            ->route('mobile.academic-calendar-approvals')
            ->with('success', 'Pengajuan izin jadwal piket berhasil ditolak.');
    }

    private function validateAdminPayload(Request $request): array
    {
        $validated = $request->validate([
            'school_id' => ['nullable', 'exists:madrasahs,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function ensureNoConflict(int $schoolId, array $validated, ?int $ignoreId = null): void
    {
        $startDate = Carbon::parse($validated['start_date'], 'Asia/Jakarta')->startOfDay();
        $endDate = Carbon::parse($validated['end_date'], 'Asia/Jakarta')->startOfDay();

        $conflict = PicketSchedulePeriod::query()
            ->where('school_id', $schoolId)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->first();

        if ($conflict) {
            throw ValidationException::withMessages([
                'start_date' => 'Periode bentrok dengan "' . $conflict->name . '" pada rentang ' . $conflict->date_range_label . '.',
            ]);
        }
    }

    private function authorizePeriod(PicketSchedulePeriod $picketSchedulePeriod): void
    {
        $user = Auth::user();
        if ($user?->role === 'super_admin') {
            return;
        }

        $schoolId = $this->resolveSelectedSchoolId($user);
        if ((int) $picketSchedulePeriod->school_id !== $schoolId) {
            abort(403);
        }
    }

    private function resolveSelectedSchoolId($user, ?Request $request = null, bool $requiredForSuperAdmin = true): ?int
    {
        if (!$user || !in_array($user->role, ['admin', 'super_admin'], true)) {
            abort(403);
        }

        if ($user->role === 'admin') {
            if (!$user->madrasah_id) {
                abort(403);
            }

            return (int) $user->madrasah_id;
        }

        $schoolId = $request?->input('school_id');
        if ($schoolId !== null && $schoolId !== '') {
            return (int) $schoolId;
        }

        if ($requiredForSuperAdmin) {
            throw ValidationException::withMessages([
                'school_id' => 'Sekolah wajib dipilih untuk super admin.',
            ]);
        }

        return null;
    }

    private function resolvePrincipalSchoolId($user): int
    {
        if (
            !$user ||
            !$user->madrasah_id ||
            !in_array($user->role, ['tenaga_pendidik', 'admin'], true) ||
            $user->ketugasan !== 'kepala madrasah/sekolah'
        ) {
            abort(403);
        }

        return (int) $user->madrasah_id;
    }

    private function getTeachersForSchool(int $schoolId)
    {
        return User::query()
            ->where('madrasah_id', $schoolId)
            ->where('role', 'tenaga_pendidik')
            ->orderByRaw("CASE WHEN ketugasan = 'kepala madrasah/sekolah' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get(['id', 'name', 'ketugasan']);
    }

    private function syncAdminSubmissions(PicketSchedulePeriod $period, array $teacherDates): void
    {
        $teacherIds = $this->getTeachersForSchool((int) $period->school_id)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $validTeacherIds = array_flip($teacherIds);
        $payload = [];

        foreach ($teacherDates as $teacherId => $dates) {
            $teacherId = (int) $teacherId;

            if (!isset($validTeacherIds[$teacherId])) {
                continue;
            }

            $normalizedDates = collect(is_array($dates) ? $dates : [])
                ->map(fn ($date) => trim((string) $date))
                ->filter()
                ->unique()
                ->sort()
                ->values();

            if ($normalizedDates->isEmpty()) {
                continue;
            }

            foreach ($normalizedDates as $date) {
                try {
                    $carbonDate = Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')->startOfDay();
                } catch (\Throwable $exception) {
                    throw ValidationException::withMessages([
                        'teacher_dates' => 'Terdapat format tanggal jadwal piket yang tidak valid.',
                    ]);
                }

                if (!$period->containsDate($carbonDate)) {
                    throw ValidationException::withMessages([
                        'teacher_dates' => 'Terdapat tanggal jadwal piket di luar rentang periode yang dipilih.',
                    ]);
                }

                if ($carbonDate->isSunday()) {
                    throw ValidationException::withMessages([
                        'teacher_dates' => 'Hari Minggu tidak dapat dijadikan jadwal piket.',
                    ]);
                }
            }

            $payload[] = [
                'user_id' => $teacherId,
                'selected_dates' => $normalizedDates->all(),
                'approval_status' => PicketScheduleSubmission::APPROVAL_PENDING,
                'submitted_at' => now('Asia/Jakarta'),
                'approved_by' => null,
                'approved_at' => null,
                'approval_notes' => null,
            ];
        }

        if (empty($payload)) {
            throw ValidationException::withMessages([
                'teacher_dates' => 'Pilih minimal satu hari piket untuk minimal satu tenaga pendidik.',
            ]);
        }

        $period->submissions()->delete();

        foreach ($payload as $submission) {
            $period->submissions()->create($submission);
        }
    }

    private function buildDateChoices(PicketSchedulePeriod $period): array
    {
        return $this->buildDateChoicesFromRange($period->start_date, $period->end_date);
    }

    private function buildDateChoicesFromRange(Carbon|string $startDate, Carbon|string $endDate): array
    {
        $startDate = $startDate instanceof Carbon ? $startDate->copy()->startOfDay() : Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay();
        $endDate = $endDate instanceof Carbon ? $endDate->copy()->startOfDay() : Carbon::parse($endDate, 'Asia/Jakarta')->startOfDay();
        $choices = [];

        if ($endDate->lt($startDate)) {
            return [];
        }

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $choices[] = [
                'date' => $date->toDateString(),
                'label' => $date->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'is_sunday' => $date->isSunday(),
            ];
        }

        return $choices;
    }
}
