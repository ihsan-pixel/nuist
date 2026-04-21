<?php

namespace App\Http\Controllers;

use App\Models\DayMarker;
use App\Models\Madrasah;
use App\Models\TeachingClassStudentCount;
use App\Models\TeachingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DayMarkerController extends Controller
{
    private function canManage(): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        if (in_array($user->role, ['super_admin', 'admin', 'pengurus'], true)) {
            return true;
        }

        return $user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah';
    }

    private function allowedMadrasahIds(): array
    {
        $user = Auth::user();
        if (!$user) return [];

        if (in_array($user->role, ['super_admin', 'pengurus'], true)) {
            return Madrasah::query()->pluck('id')->all();
        }

        if (in_array($user->role, ['admin', 'tenaga_pendidik'], true)) {
            return $user->madrasah_id ? [(int) $user->madrasah_id] : [];
        }

        return [];
    }

    public function index(Request $request)
    {
        if (!$this->canManage()) {
            abort(403, 'Unauthorized.');
        }

        $user = Auth::user();
        $allowedMadrasahIds = $this->allowedMadrasahIds();
        $madrasahs = Madrasah::query()
            ->when(!empty($allowedMadrasahIds), fn ($q) => $q->whereIn('id', $allowedMadrasahIds))
            ->orderBy('name')
            ->get(['id', 'name', 'scod']);

        $month = $request->input('month', Carbon::now('Asia/Jakarta')->format('Y-m'));
        $monthCarbon = preg_match('/^\d{4}-\d{2}$/', $month)
            ? Carbon::createFromFormat('Y-m', $month, 'Asia/Jakarta')
            : Carbon::now('Asia/Jakarta');

        $start = $monthCarbon->copy()->startOfMonth();
        $end = $monthCarbon->copy()->endOfMonth();

        $selectedMadrasahId = (int) ($request->input('madrasah_id') ?: ($madrasahs->first()->id ?? 0));
        if (!empty($allowedMadrasahIds) && $selectedMadrasahId && !in_array($selectedMadrasahId, $allowedMadrasahIds, true)) {
            $selectedMadrasahId = (int) ($madrasahs->first()->id ?? 0);
        }

        $markersQuery = DayMarker::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->orderBy('scope_key');

        $canSeeAll = $user && in_array($user->role, ['super_admin', 'pengurus'], true);
        if (!$canSeeAll) {
            // admin/kepala: only global + allowed madrasah scopes
            $markersQuery->where(function ($q) use ($allowedMadrasahIds) {
                $q->where('scope_key', DayMarker::scopeKeyGlobal())
                    ->orWhereIn('madrasah_id', $allowedMadrasahIds);
            });
        }

        $markers = $markersQuery->get();

        $classNames = collect();
        if ($selectedMadrasahId) {
            $scheduleClasses = TeachingSchedule::query()
                ->where('school_id', $selectedMadrasahId)
                ->select('class_name')
                ->distinct()
                ->pluck('class_name');

            $countClasses = TeachingClassStudentCount::query()
                ->where('school_id', $selectedMadrasahId)
                ->select('class_name')
                ->distinct()
                ->pluck('class_name');

            $classNames = $scheduleClasses
                ->merge($countClasses)
                ->map(fn ($v) => trim((string) $v))
                ->filter()
                ->unique()
                ->sort()
                ->values();
        }

        return view('day-markers.index', [
            'madrasahs' => $madrasahs,
            'selectedMadrasahId' => $selectedMadrasahId,
            'month' => $monthCarbon->format('Y-m'),
            'markers' => $markers,
            'classNames' => $classNames,
        ]);
    }

    public function store(Request $request)
    {
        if (!$this->canManage()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'date' => 'required|date',
            'scope_type' => 'required|in:global,school,class',
            'madrasah_id' => 'nullable|integer',
            'class_name' => 'nullable|string|max:255',
            'marker' => 'required|in:normal,libur,ujian,kegiatan_khusus',
            'notes' => 'nullable|string|max:1000',
        ]);

        $scopeType = $request->input('scope_type');
        $date = Carbon::parse($request->input('date'))->toDateString();
        $madrasahId = $request->integer('madrasah_id') ?: null;
        $className = trim((string) $request->input('class_name', ''));

        if (in_array($scopeType, ['school', 'class'], true) && !$madrasahId) {
            return back()->with('error', 'Madrasah wajib dipilih untuk scope sekolah/kelas.');
        }

        if ($scopeType === 'class' && $className === '') {
            return back()->with('error', 'Nama kelas wajib diisi untuk scope kelas.');
        }

        $allowedMadrasahIds = $this->allowedMadrasahIds();
        if (!empty($allowedMadrasahIds) && $madrasahId && !in_array($madrasahId, $allowedMadrasahIds, true) && !in_array(Auth::user()->role, ['super_admin', 'pengurus'], true)) {
            abort(403, 'Unauthorized.');
        }

        $scopeKey = match ($scopeType) {
            'global' => DayMarker::scopeKeyGlobal(),
            'school' => DayMarker::scopeKeySchool((int) $madrasahId),
            'class' => DayMarker::scopeKeyClass((int) $madrasahId, $className),
        };

        DayMarker::updateOrCreate(
            ['scope_key' => $scopeKey, 'date' => $date],
            [
                'madrasah_id' => $scopeType === 'global' ? null : $madrasahId,
                'class_name' => $scopeType === 'class' ? $className : null,
                'marker' => $request->input('marker'),
                'notes' => $request->input('notes'),
                'updated_by' => Auth::id(),
                'created_by' => Auth::id(),
            ]
        );

        return back()->with('success', 'Penanda hari berhasil disimpan.');
    }

    public function update(Request $request, DayMarker $dayMarker)
    {
        if (!$this->canManage()) {
            abort(403, 'Unauthorized.');
        }

        $allowedMadrasahIds = $this->allowedMadrasahIds();
        if (!empty($allowedMadrasahIds) && $dayMarker->madrasah_id && !in_array((int) $dayMarker->madrasah_id, $allowedMadrasahIds, true) && !in_array(Auth::user()->role, ['super_admin', 'pengurus'], true)) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'marker' => 'required|in:normal,libur,ujian,kegiatan_khusus',
            'notes' => 'nullable|string|max:1000',
        ]);

        $dayMarker->update([
            'marker' => $request->input('marker'),
            'notes' => $request->input('notes'),
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Penanda hari berhasil diperbarui.');
    }

    public function destroy(DayMarker $dayMarker)
    {
        if (!$this->canManage()) {
            abort(403, 'Unauthorized.');
        }

        $allowedMadrasahIds = $this->allowedMadrasahIds();
        if (!empty($allowedMadrasahIds) && $dayMarker->madrasah_id && !in_array((int) $dayMarker->madrasah_id, $allowedMadrasahIds, true) && !in_array(Auth::user()->role, ['super_admin', 'pengurus'], true)) {
            abort(403, 'Unauthorized.');
        }

        $dayMarker->delete();
        return back()->with('success', 'Penanda hari berhasil dihapus.');
    }
}
