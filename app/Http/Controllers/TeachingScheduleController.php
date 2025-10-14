<?php

namespace App\Http\Controllers;

use App\Models\TeachingSchedule;
use App\Models\User;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeachingScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = TeachingSchedule::with(['teacher', 'school', 'creator']);

        if ($user->role === 'super_admin') {
            // See all
        } elseif ($user->role === 'admin') {
            // Only their school
            $query->where('school_id', $user->madrasah_id);
        } elseif ($user->role === 'tenaga_pendidik') {
            // Only their own
            $query->where('teacher_id', $user->id);
        }

        $schedules = $query->orderBy('day')->orderBy('start_time')->get();

        if ($user->role === 'tenaga_pendidik') {
            // Group by day for teacher view
            $grouped = $schedules->groupBy('day');
            return view('teaching-schedules.teacher-index', compact('grouped'));
        } else {
            // Admin view: group by teacher
            $grouped = $schedules->groupBy('teacher.name');
            return view('teaching-schedules.index', compact('grouped'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $schools = Madrasah::where('id', $user->madrasah_id)->get();
            $teachers = User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->get();
        } elseif ($user->role === 'super_admin') {
            $schools = Madrasah::all();
            $teachers = collect(); // Load via AJAX
        } else {
            abort(403);
        }

        return view('teaching-schedules.create', compact('teachers', 'schools'));
    }

    public function getTeachersBySchool($schoolId)
    {
        $teachers = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $schoolId)
            ->get(['id', 'name']);

        // Debug log to check data
        \Illuminate\Support\Facades\Log::info('getTeachersBySchool: schoolId=' . $schoolId . ', teachers=' . $teachers->count());

        return response()->json($teachers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:madrasahs,id',
            'teacher_id' => 'required|exists:users,id',
            'schedules' => 'required|array',
            'schedules.*.day' => ['required', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])],
            'schedules.*.subject' => 'nullable|string|max:255',
            'schedules.*.class_name' => 'nullable|string|max:255',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i',
        ]);

        $user = Auth::user();

        // Check role access
        if ($user->role === 'admin' && $request->school_id != $user->madrasah_id) {
            abort(403);
        }

        $createdCount = 0;

        foreach ($request->schedules as $scheduleData) {
            // Skip if subject is empty
            if (empty($scheduleData['subject'])) {
                continue;
            }

            // Validate required fields for this schedule
            if (empty($scheduleData['class_name']) || empty($scheduleData['start_time']) || empty($scheduleData['end_time'])) {
                return back()->withErrors(['incomplete' => 'Semua field harus diisi untuk jadwal yang memiliki mata pelajaran.'])->withInput();
            }

            if ($scheduleData['start_time'] >= $scheduleData['end_time']) {
                return back()->withErrors(['time' => 'Jam selesai harus setelah jam mulai.'])->withInput();
            }

            // Check overlap for teacher schedule (same teacher, same day, overlapping time)
            $teacherOverlap = TeachingSchedule::where('teacher_id', $request->teacher_id)
                ->where('day', $scheduleData['day'])
                ->where(function ($query) use ($scheduleData) {
                    $query->where('start_time', '<', $scheduleData['end_time'])
                          ->where('end_time', '>', $scheduleData['start_time']);
                })
                ->exists();

            if ($teacherOverlap) {
                return back()->withErrors(['overlap' => 'Jadwal bentrok dengan jadwal lain guru yang sama pada hari ' . $scheduleData['day'] . '.'])->withInput()->with('alert', 'Jam mengajar guru sudah terisi.');
            }

            // Check if class schedule already exists for the same school, day, and time
            $classOverlap = TeachingSchedule::where('school_id', $request->school_id)
                ->where('class_name', $scheduleData['class_name'])
                ->where('day', $scheduleData['day'])
                ->where(function ($query) use ($scheduleData) {
                    $query->where('start_time', '<', $scheduleData['end_time'])
                          ->where('end_time', '>', $scheduleData['start_time']);
                })
                ->exists();

            if ($classOverlap) {
                return back()->withErrors(['class_overlap' => 'Jadwal bentrok dengan jadwal lain pada kelas yang sama di hari ' . $scheduleData['day'] . '.'])->withInput()->with('alert', 'Jam mengajar pada kelas sudah terisi.');
            }

            TeachingSchedule::create([
                'school_id' => $request->school_id,
                'teacher_id' => $request->teacher_id,
                'day' => $scheduleData['day'],
                'subject' => $scheduleData['subject'],
                'class_name' => $scheduleData['class_name'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'created_by' => $user->id,
            ]);

            $createdCount++;
        }

        if ($createdCount == 0) {
            return back()->withErrors(['none' => 'Tidak ada jadwal yang ditambahkan.'])->withInput();
        }

        return redirect()->route('teaching-schedules.index')->with('success', $createdCount . ' jadwal mengajar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = TeachingSchedule::findOrFail($id);
        $user = Auth::user();

        // Check access
        if ($user->role === 'admin' && $schedule->school_id != $user->madrasah_id) {
            abort(403);
        } elseif ($user->role === 'tenaga_pendidik' && $schedule->teacher_id != $user->id) {
            abort(403);
        }

        if ($user->role === 'admin') {
            $teachers = User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->get();
        } elseif ($user->role === 'super_admin') {
            $teachers = User::where('role', 'tenaga_pendidik')->get();
        } else {
            abort(403);
        }

        $schools = Madrasah::all();

        return view('teaching-schedules.edit', compact('schedule', 'teachers', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = TeachingSchedule::findOrFail($id);

        $request->validate([
            'school_id' => 'required|exists:madrasahs,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => ['required', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])],
            'subject' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $user = Auth::user();

        // Check role access
        if ($user->role === 'admin' && $request->school_id != $user->madrasah_id) {
            abort(403);
        } elseif ($user->role === 'tenaga_pendidik' && $schedule->teacher_id != $user->id) {
            abort(403);
        }

        // Check overlap, excluding current
        $overlap = TeachingSchedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['overlap' => 'Jadwal bentrok dengan jadwal lain pada hari yang sama.'])->withInput();
        }

        $schedule->update($request->only([
            'school_id', 'teacher_id', 'day', 'subject', 'class_name', 'start_time', 'end_time'
        ]));

        return redirect()->route('teaching-schedules.index')->with('success', 'Jadwal mengajar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = TeachingSchedule::findOrFail($id);
        $user = Auth::user();

        // Check access
        if ($user->role === 'admin' && $schedule->school_id != $user->madrasah_id) {
            abort(403);
        } elseif ($user->role === 'tenaga_pendidik' && $schedule->teacher_id != $user->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('teaching-schedules.index')->with('success', 'Jadwal mengajar berhasil dihapus.');
    }
}
