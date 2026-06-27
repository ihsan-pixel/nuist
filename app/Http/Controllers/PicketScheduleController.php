<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\PicketSchedulePeriod;
use App\Models\PicketScheduleSubmission;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
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
        $selectedSchoolId = $this->resolveSelectedSchoolId($user, request(), false);
        $school = $selectedSchoolId ? Madrasah::findOrFail($selectedSchoolId) : null;
        $schools = $user->role === 'super_admin'
            ? Madrasah::orderBy('name')->get(['id', 'name'])
            : collect();

        return view('picket-schedules.form', [
            'period' => new PicketSchedulePeriod([
                'school_id' => $selectedSchoolId,
                'start_date' => now('Asia/Jakarta')->toDateString(),
                'end_date' => now('Asia/Jakarta')->addWeek()->toDateString(),
                'is_active' => true,
            ]),
            'school' => $school,
            'schools' => $schools,
            'teachers' => $selectedSchoolId ? $this->getTeachersForSchool((int) $selectedSchoolId) : collect(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $this->validateAdminPayload($request);
        $validated['school_id'] = $this->resolveSelectedSchoolId($user, $request);

        $this->ensureNoConflict($validated['school_id'], $validated);

        PicketSchedulePeriod::create(array_merge($validated, [
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]));

        return redirect()
            ->route('picket-schedule-periods.index')
            ->with('success', 'Periode izin jadwal piket berhasil ditambahkan.');
    }

    public function edit(PicketSchedulePeriod $picketSchedulePeriod)
    {
        $this->authorizePeriod($picketSchedulePeriod);

        return view('picket-schedules.form', [
            'period' => $picketSchedulePeriod,
            'school' => $picketSchedulePeriod->school,
            'schools' => Auth::user()->role === 'super_admin'
                ? Madrasah::orderBy('name')->get(['id', 'name'])
                : collect(),
            'teachers' => $this->getTeachersForSchool((int) $picketSchedulePeriod->school_id),
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

        $hasExistingSubmissions = $picketSchedulePeriod->submissions()->exists();
        $rangeChanged = $validated['school_id'] !== (int) $picketSchedulePeriod->school_id
            || $validated['start_date'] !== $picketSchedulePeriod->getRawOriginal('start_date')
            || $validated['end_date'] !== $picketSchedulePeriod->getRawOriginal('end_date');

        if ($hasExistingSubmissions && $rangeChanged) {
            throw ValidationException::withMessages([
                'start_date' => 'Rentang tanggal atau sekolah tidak dapat diubah karena sudah ada pengajuan guru pada periode ini.',
            ]);
        }

        $this->ensureNoConflict($validated['school_id'], $validated, $picketSchedulePeriod->id);

        $picketSchedulePeriod->update(array_merge($validated, [
            'updated_by' => Auth::id(),
        ]));

        return redirect()
            ->route('picket-schedule-periods.index')
            ->with('success', 'Periode izin jadwal piket berhasil diperbarui.');
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
        $user = Auth::user();
        if (
            !$user ||
            $user->role !== 'tenaga_pendidik' ||
            (int) $user->madrasah_id !== (int) $picketSchedulePeriod->school_id ||
            !$picketSchedulePeriod->is_active
        ) {
            abort(403);
        }

        $selectedDates = collect($request->input('selected_dates', []))
            ->map(fn ($date) => trim((string) $date))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($selectedDates->isEmpty()) {
            throw ValidationException::withMessages([
                'selected_dates' => 'Pilih minimal satu hari jadwal piket.',
            ]);
        }

        foreach ($selectedDates as $date) {
            try {
                $carbonDate = Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')->startOfDay();
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages([
                    'selected_dates' => 'Format tanggal jadwal piket tidak valid.',
                ]);
            }

            if (!$picketSchedulePeriod->containsDate($carbonDate)) {
                throw ValidationException::withMessages([
                    'selected_dates' => 'Terdapat tanggal di luar rentang periode libur semester.',
                ]);
            }

            if ($carbonDate->isSunday()) {
                throw ValidationException::withMessages([
                    'selected_dates' => 'Hari Minggu tidak dapat dipilih sebagai jadwal piket.',
                ]);
            }
        }

        $submission = PicketScheduleSubmission::query()->firstOrNew([
            'picket_schedule_period_id' => $picketSchedulePeriod->id,
            'user_id' => $user->id,
        ]);

        $submission->fill([
            'selected_dates' => $selectedDates->values()->all(),
            'approval_status' => PicketScheduleSubmission::APPROVAL_PENDING,
            'submitted_at' => now('Asia/Jakarta'),
            'approved_by' => null,
            'approved_at' => null,
            'approval_notes' => null,
        ]);
        $submission->save();

        return redirect()
            ->route('mobile.picket-schedules.index')
            ->with('success', 'Pengajuan izin jadwal piket berhasil dikirim dan menunggu persetujuan kepala sekolah.');
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
            ->get(['id', 'name', 'ketugasan', 'jabatan']);
    }

    private function buildDateChoices(PicketSchedulePeriod $period): array
    {
        $choices = [];

        foreach (CarbonPeriod::create($period->start_date, $period->end_date) as $date) {
            $choices[] = [
                'date' => $date->toDateString(),
                'label' => $date->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                'is_sunday' => $date->isSunday(),
            ];
        }

        return $choices;
    }
}
