<?php

namespace App\Http\Controllers\Mobile\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\TeachingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeachingScheduleManageController extends Controller
{
    private const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function create()
    {
        $user = Auth::user();
        $schoolId = $user->madrasah_id;

        if (!$schoolId) {
            return redirect()->route('mobile.jadwal')->with('error', 'Akun Anda belum terhubung ke madrasah, sehingga tidak bisa membuat jadwal.');
        }

        $classes = $this->getSchoolClassOptions($schoolId);
        $subjects = $this->getSchoolSubjectOptions($schoolId);

        return view('mobile.jadwal-form', [
            'isEditing' => false,
            'schedule' => null,
            'classes' => $classes,
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->madrasah_id;

        if (!$schoolId) {
            return redirect()->route('mobile.jadwal')->with('error', 'Akun Anda belum terhubung ke madrasah, sehingga tidak bisa membuat jadwal.');
        }

        $validated = $request->validate([
            'day' => ['required', Rule::in(self::DAYS)],
            'subject' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $validated['subject'] = trim((string) $validated['subject']);
        $validated['class_name'] = trim((string) $validated['class_name']);

        // Check overlap for teacher schedule (same teacher, same day, overlapping time)
        $teacherOverlap = TeachingSchedule::query()
            ->where('teacher_id', $user->id)
            ->where('day', $validated['day'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($teacherOverlap) {
            return back()
                ->withErrors(['overlap' => 'Jadwal bentrok dengan jadwal lain pada hari yang sama.'])
                ->withInput();
        }

        // Check class overlap (skip for madrasah ID 8 and 9)
        if (!in_array((int) $schoolId, [8, 9], true)) {
            $classOverlap = TeachingSchedule::query()
                ->where('school_id', $schoolId)
                ->where('class_name', $validated['class_name'])
                ->where('day', $validated['day'])
                ->where(function ($query) use ($validated) {
                    $query->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                })
                ->exists();

            if ($classOverlap) {
                return back()
                    ->withErrors(['class_overlap' => 'Jadwal bentrok dengan jadwal lain pada kelas yang sama di hari ' . $validated['day'] . '.'])
                    ->withInput();
            }
        }

        TeachingSchedule::create([
            'school_id' => $schoolId,
            'teacher_id' => $user->id,
            'day' => $validated['day'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'created_by' => $user->id,
        ]);

        return redirect()->route('mobile.jadwal')->with('success', 'Jadwal mengajar berhasil ditambahkan.');
    }

    public function edit(TeachingSchedule $schedule)
    {
        $user = Auth::user();

        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $schoolId = $schedule->school_id;
        $classes = $schoolId ? $this->getSchoolClassOptions($schoolId) : collect();
        $subjects = $schoolId ? $this->getSchoolSubjectOptions($schoolId) : collect();

        return view('mobile.jadwal-form', [
            'isEditing' => true,
            'schedule' => $schedule,
            'classes' => $classes,
            'subjects' => $subjects,
        ]);
    }

    public function update(Request $request, TeachingSchedule $schedule)
    {
        $user = Auth::user();

        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $schoolId = $schedule->school_id ?: $user->madrasah_id;
        if (!$schoolId) {
            return redirect()->route('mobile.jadwal')->with('error', 'Data sekolah pada jadwal tidak ditemukan, sehingga tidak bisa mengubah jadwal.');
        }

        $validated = $request->validate([
            'day' => ['required', Rule::in(self::DAYS)],
            'subject' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $validated['subject'] = trim((string) $validated['subject']);
        $validated['class_name'] = trim((string) $validated['class_name']);

        // Check overlap, excluding current
        $teacherOverlap = TeachingSchedule::query()
            ->where('teacher_id', $user->id)
            ->where('day', $validated['day'])
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($teacherOverlap) {
            return back()
                ->withErrors(['overlap' => 'Jadwal bentrok dengan jadwal lain pada hari yang sama.'])
                ->withInput();
        }

        // Check class overlap, excluding current (skip for madrasah ID 8 and 9)
        if (!in_array((int) $schoolId, [8, 9], true)) {
            $classOverlap = TeachingSchedule::query()
                ->where('school_id', $schoolId)
                ->where('class_name', $validated['class_name'])
                ->where('day', $validated['day'])
                ->where('id', '!=', $schedule->id)
                ->where(function ($query) use ($validated) {
                    $query->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                })
                ->exists();

            if ($classOverlap) {
                return back()
                    ->withErrors(['class_overlap' => 'Jadwal bentrok dengan jadwal lain pada kelas yang sama di hari ' . $validated['day'] . '.'])
                    ->withInput();
            }
        }

        $schedule->update([
            'day' => $validated['day'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return redirect()->route('mobile.jadwal')->with('success', 'Jadwal mengajar berhasil diperbarui.');
    }

    public function destroy(TeachingSchedule $schedule)
    {
        $user = Auth::user();

        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('mobile.jadwal')->with('success', 'Jadwal mengajar berhasil dihapus.');
    }

    private function getSchoolClassOptions(int|string $schoolId)
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

    private function getSchoolSubjectOptions(int|string $schoolId)
    {
        return TeachingSchedule::query()
            ->where('school_id', $schoolId)
            ->whereNotNull('subject')
            ->where('subject', '!=', '')
            ->select('subject')
            ->distinct()
            ->orderBy('subject')
            ->pluck('subject');
    }
}
