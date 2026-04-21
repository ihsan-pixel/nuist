<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\TeachingClassActivity;
use App\Models\TeachingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeachingClassActivityController extends Controller
{
    private const TYPES = ['Ujian', 'PKL', 'Libur Sekolah', 'Kegiatan Lainnya'];

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = TeachingClassActivity::with(['school', 'creator'])->orderByDesc('start_date');

        if ($user->role === 'admin') {
            $query->where('school_id', $user->madrasah_id);
        } elseif ($user->role === 'super_admin') {
            if ($request->filled('school_id')) {
                $query->where('school_id', $request->input('school_id'));
            }
        } else {
            abort(403);
        }

        $activities = $query->paginate(30)->withQueryString();

        $schools = $user->role === 'super_admin'
            ? Madrasah::orderBy('kabupaten')->orderBy('scod')->get()
            : Madrasah::where('id', $user->madrasah_id)->get();

        return view('teaching-class-activities.index', compact('activities', 'schools'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $schools = $user->role === 'super_admin'
            ? Madrasah::orderBy('kabupaten')->orderBy('scod')->get()
            : Madrasah::where('id', $user->madrasah_id)->get();

        $schoolId = $user->role === 'admin'
            ? $user->madrasah_id
            : (int) $request->input('school_id', 0);

        $classes = $schoolId
            ? $this->getClassOptionsForSchool($schoolId)
            : collect();

        $types = self::TYPES;

        return view('teaching-class-activities.create', compact('schools', 'schoolId', 'classes', 'types'));
    }

    public function getClassesBySchool($schoolId)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && (int) $user->madrasah_id !== (int) $schoolId) {
            abort(403);
        }
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            abort(403);
        }

        return response()->json($this->getClassOptionsForSchool((int) $schoolId)->values());
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'school_id' => ['required', 'exists:madrasahs,id'],
            'class_name' => ['required', 'string', 'max:255'],
            'class_name_new' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'activity_type' => ['required', Rule::in(self::TYPES)],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($user->role === 'admin') {
            $validated['school_id'] = $user->madrasah_id;
        } elseif ($user->role !== 'super_admin') {
            abort(403);
        }

        $validated['class_name'] = trim((string) $validated['class_name']);
        $validated['class_name_new'] = trim((string) ($validated['class_name_new'] ?? ''));

        if ($validated['class_name'] === '__new__') {
            if ($validated['class_name_new'] === '') {
                return back()->withErrors(['class_name_new' => 'Kelas baru wajib diisi.'])->withInput();
            }
            $validated['class_name'] = $validated['class_name_new'];
        }

        TeachingClassActivity::create([
            'school_id' => $validated['school_id'],
            'class_name' => $validated['class_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'activity_type' => $validated['activity_type'],
            'description' => $validated['description'] ?? null,
            'created_by' => $user->id,
        ]);

        return redirect()->route('teaching-class-activities.index')->with('success', 'Kegiatan kelas berhasil ditambahkan.');
    }

    public function edit(TeachingClassActivity $teaching_class_activity)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && (int) $teaching_class_activity->school_id !== (int) $user->madrasah_id) {
            abort(403);
        }
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            abort(403);
        }

        $schools = $user->role === 'super_admin'
            ? Madrasah::orderBy('kabupaten')->orderBy('scod')->get()
            : Madrasah::where('id', $user->madrasah_id)->get();

        $schoolId = (int) $teaching_class_activity->school_id;
        $classes = $this->getClassOptionsForSchool($schoolId);
        $types = self::TYPES;

        return view('teaching-class-activities.edit', [
            'activity' => $teaching_class_activity,
            'schools' => $schools,
            'schoolId' => $schoolId,
            'classes' => $classes,
            'types' => $types,
        ]);
    }

    public function update(Request $request, TeachingClassActivity $teaching_class_activity)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && (int) $teaching_class_activity->school_id !== (int) $user->madrasah_id) {
            abort(403);
        }
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'school_id' => ['required', 'exists:madrasahs,id'],
            'class_name' => ['required', 'string', 'max:255'],
            'class_name_new' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'activity_type' => ['required', Rule::in(self::TYPES)],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($user->role === 'admin') {
            $validated['school_id'] = $user->madrasah_id;
        }

        $validated['class_name'] = trim((string) $validated['class_name']);
        $validated['class_name_new'] = trim((string) ($validated['class_name_new'] ?? ''));

        if ($validated['class_name'] === '__new__') {
            if ($validated['class_name_new'] === '') {
                return back()->withErrors(['class_name_new' => 'Kelas baru wajib diisi.'])->withInput();
            }
            $validated['class_name'] = $validated['class_name_new'];
        }

        $teaching_class_activity->update([
            'school_id' => $validated['school_id'],
            'class_name' => $validated['class_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'activity_type' => $validated['activity_type'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('teaching-class-activities.index')->with('success', 'Kegiatan kelas berhasil diperbarui.');
    }

    public function destroy(TeachingClassActivity $teaching_class_activity)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && (int) $teaching_class_activity->school_id !== (int) $user->madrasah_id) {
            abort(403);
        }
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            abort(403);
        }

        $teaching_class_activity->delete();

        return redirect()->route('teaching-class-activities.index')->with('success', 'Kegiatan kelas berhasil dihapus.');
    }

    private function getClassOptionsForSchool(int $schoolId)
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
}
