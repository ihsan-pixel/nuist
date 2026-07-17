<?php

namespace App\Http\Controllers\Mobile\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\TeachingSchedule;
use App\Models\TeachingSchedulePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TeachingScheduleManageController extends Controller
{
    private const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    private const NEW_VALUE = '__new__';

    public function create()
    {
        $user = Auth::user();
        $schoolId = $user->madrasah_id;

        if (!$schoolId) {
            return redirect()->route('mobile.jadwal')->with('error', 'Akun Anda belum terhubung ke madrasah, sehingga tidak bisa membuat jadwal.');
        }

        $selectedPeriod = $this->resolveSelectedPeriod($schoolId, request()->integer('period_id'));
        if (!$selectedPeriod) {
            return redirect()->route('mobile.jadwal')->with('error', 'Periode jadwal mengajar belum tersedia. Hubungi admin sekolah.');
        }

        $classes = $this->getSchoolClassOptions($schoolId, $selectedPeriod->id);
        $subjects = $this->getSchoolSubjectOptions($schoolId, $selectedPeriod->id);

        return view('mobile.jadwal-form', [
            'isEditing' => false,
            'schedule' => null,
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedPeriod' => $selectedPeriod,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->madrasah_id;

        if (!$schoolId) {
            return redirect()->route('mobile.jadwal')->with('error', 'Akun Anda belum terhubung ke madrasah, sehingga tidak bisa membuat jadwal.');
        }

        $selectedPeriod = $this->resolveSelectedPeriod($schoolId, $request->integer('period_id'));
        if (!$selectedPeriod) {
            throw ValidationException::withMessages([
                'period_id' => 'Periode jadwal mengajar belum tersedia.',
            ]);
        }

        $validated = $request->validate([
            'day' => ['required', Rule::in(self::DAYS)],
            'subject' => ['required', 'string', 'max:255'],
            'subject_new' => ['nullable', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'class_name_new' => ['nullable', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $validated['subject'] = trim((string) $validated['subject']);
        $validated['class_name'] = trim((string) $validated['class_name']);
        $validated['subject_new'] = trim((string) ($validated['subject_new'] ?? ''));
        $validated['class_name_new'] = trim((string) ($validated['class_name_new'] ?? ''));

        if ($validated['subject'] === self::NEW_VALUE) {
            if ($validated['subject_new'] === '') {
                return back()->withErrors(['subject_new' => 'Mata pelajaran baru wajib diisi.'])->withInput();
            }
            $validated['subject'] = $validated['subject_new'];
        }

        if ($validated['class_name'] === self::NEW_VALUE) {
            if ($validated['class_name_new'] === '') {
                return back()->withErrors(['class_name_new' => 'Kelas baru wajib diisi.'])->withInput();
            }
            $validated['class_name'] = $validated['class_name_new'];
        }

        // Check overlap for teacher schedule (same teacher, same day, overlapping time)
        $teacherOverlap = TeachingSchedule::query()
            ->where('teacher_id', $user->id)
            ->where('teaching_schedule_period_id', $selectedPeriod->id)
            ->where('day', $validated['day'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($teacherOverlap) {
            return back()
                ->withErrors(['overlap' => 'Jadwal bentrok dengan jadwal Anda sendiri pada hari yang sama.'])
                ->withInput();
        }

        TeachingSchedule::create([
            'school_id' => $schoolId,
            'teaching_schedule_period_id' => $selectedPeriod->id,
            'teacher_id' => $user->id,
            'day' => $validated['day'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'created_by' => $user->id,
        ]);

        return redirect()->route('mobile.jadwal', ['period_id' => $selectedPeriod->id])->with('success', 'Jadwal mengajar berhasil ditambahkan.');
    }

    public function edit(TeachingSchedule $schedule)
    {
        $user = Auth::user();

        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $schoolId = $schedule->school_id;
        $selectedPeriod = $schedule->period;
        $classes = $schoolId && $selectedPeriod ? $this->getSchoolClassOptions($schoolId, $selectedPeriod->id) : collect();
        $subjects = $schoolId && $selectedPeriod ? $this->getSchoolSubjectOptions($schoolId, $selectedPeriod->id) : collect();

        return view('mobile.jadwal-form', [
            'isEditing' => true,
            'schedule' => $schedule,
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedPeriod' => $selectedPeriod,
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
        $selectedPeriod = $schedule->period ?: $this->resolveSelectedPeriod($schoolId, $request->integer('period_id'));
        if (!$selectedPeriod) {
            throw ValidationException::withMessages([
                'period_id' => 'Periode jadwal mengajar pada data ini tidak ditemukan.',
            ]);
        }

        $validated = $request->validate([
            'day' => ['required', Rule::in(self::DAYS)],
            'subject' => ['required', 'string', 'max:255'],
            'subject_new' => ['nullable', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'class_name_new' => ['nullable', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $validated['subject'] = trim((string) $validated['subject']);
        $validated['class_name'] = trim((string) $validated['class_name']);
        $validated['subject_new'] = trim((string) ($validated['subject_new'] ?? ''));
        $validated['class_name_new'] = trim((string) ($validated['class_name_new'] ?? ''));

        if ($validated['subject'] === self::NEW_VALUE) {
            if ($validated['subject_new'] === '') {
                return back()->withErrors(['subject_new' => 'Mata pelajaran baru wajib diisi.'])->withInput();
            }
            $validated['subject'] = $validated['subject_new'];
        }

        if ($validated['class_name'] === self::NEW_VALUE) {
            if ($validated['class_name_new'] === '') {
                return back()->withErrors(['class_name_new' => 'Kelas baru wajib diisi.'])->withInput();
            }
            $validated['class_name'] = $validated['class_name_new'];
        }

        // Check overlap, excluding current
        $teacherOverlap = TeachingSchedule::query()
            ->where('teacher_id', $user->id)
            ->where('teaching_schedule_period_id', $selectedPeriod->id)
            ->where('day', $validated['day'])
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($teacherOverlap) {
            return back()
                ->withErrors(['overlap' => 'Jadwal bentrok dengan jadwal Anda sendiri pada hari yang sama.'])
                ->withInput();
        }

        $schedule->update([
            'day' => $validated['day'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return redirect()->route('mobile.jadwal', ['period_id' => $selectedPeriod->id])->with('success', 'Jadwal mengajar berhasil diperbarui.');
    }

    public function destroy(TeachingSchedule $schedule)
    {
        $user = Auth::user();

        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('mobile.jadwal', ['period_id' => $schedule->teaching_schedule_period_id])->with('success', 'Jadwal mengajar berhasil dihapus.');
    }

    private function getSchoolClassOptions(int|string $schoolId, int|string $periodId)
    {
        return TeachingSchedule::query()
            ->where('school_id', $schoolId)
            ->where('teaching_schedule_period_id', $periodId)
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->select('class_name')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');
    }

    private function getSchoolSubjectOptions(int|string $schoolId, int|string $periodId)
    {
        return TeachingSchedule::query()
            ->where('school_id', $schoolId)
            ->where('teaching_schedule_period_id', $periodId)
            ->whereNotNull('subject')
            ->where('subject', '!=', '')
            ->select('subject')
            ->distinct()
            ->orderBy('subject')
            ->pluck('subject');
    }

    private function resolveSelectedPeriod(int|string $schoolId, ?int $periodId = null): ?TeachingSchedulePeriod
    {
        if ($periodId) {
            $selected = TeachingSchedulePeriod::query()
                ->where('school_id', $schoolId)
                ->whereKey($periodId)
                ->first();

            if ($selected) {
                return $selected;
            }
        }

        return TeachingSchedulePeriod::activeForSchool($schoolId)
            ?? TeachingSchedulePeriod::latestForSchool($schoolId);
    }
}
