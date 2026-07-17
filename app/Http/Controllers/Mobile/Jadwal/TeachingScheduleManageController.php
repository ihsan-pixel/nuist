<?php

namespace App\Http\Controllers\Mobile\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\TeachingSchedule;
use App\Models\TeachingSchedulePeriod;
use Carbon\Carbon;
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

        $activePeriod = $this->activePeriodForSchool($schoolId);
        if (!$activePeriod) {
            return redirect()->route('mobile.jadwal')->with('error', 'Periode jadwal mengajar belum tersedia. Hubungi admin sekolah.');
        }

        $classes = $this->getSchoolClassOptions($schoolId, $activePeriod->id);
        $subjects = $this->getSchoolSubjectOptions($schoolId, $activePeriod->id);

        return view('mobile.jadwal-form', [
            'isEditing' => false,
            'schedule' => null,
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedPeriod' => $activePeriod,
            'activePeriod' => $activePeriod,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->madrasah_id;

        if (!$schoolId) {
            return redirect()->route('mobile.jadwal')->with('error', 'Akun Anda belum terhubung ke madrasah, sehingga tidak bisa membuat jadwal.');
        }

        $activePeriod = $this->activePeriodForSchool($schoolId);
        if (!$activePeriod) {
            throw ValidationException::withMessages([
                'period_id' => 'Periode jadwal mengajar belum tersedia.',
            ]);
        }
        $this->ensureRequestUsesActivePeriod($activePeriod, $request->integer('period_id'));

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
            ->with('teacher:id,name')
            ->where('teacher_id', $user->id)
            ->where('teaching_schedule_period_id', $activePeriod->id)
            ->where('day', $validated['day'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->first();

        if ($teacherOverlap) {
            return back()
                ->withErrors(['overlap' => $this->teacherOverlapMessage($teacherOverlap, $validated['day'])])
                ->withInput();
        }

        if (!in_array((int) $schoolId, [8, 9], true)) {
            $classOverlap = TeachingSchedule::query()
                ->with('teacher:id,name')
                ->where('school_id', $schoolId)
                ->where('teaching_schedule_period_id', $activePeriod->id)
                ->where('class_name', $validated['class_name'])
                ->where('day', $validated['day'])
                ->where(function ($query) use ($validated) {
                    $query->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                })
                ->first();

            if ($classOverlap) {
                return back()
                    ->withErrors(['overlap' => $this->classOverlapMessage($classOverlap, $validated['day'])])
                    ->withInput();
            }
        }

        TeachingSchedule::create([
            'school_id' => $schoolId,
            'teaching_schedule_period_id' => $activePeriod->id,
            'teacher_id' => $user->id,
            'day' => $validated['day'],
            'subject' => $validated['subject'],
            'class_name' => $validated['class_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'created_by' => $user->id,
        ]);

        return redirect()->route('mobile.jadwal', ['period_id' => $activePeriod->id])->with('success', 'Jadwal mengajar berhasil ditambahkan.');
    }

    public function edit(TeachingSchedule $schedule)
    {
        $user = Auth::user();

        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $schoolId = $schedule->school_id;
        $activePeriod = $this->activePeriodForSchool($schoolId);
        if (!$activePeriod) {
            return redirect()->route('mobile.jadwal')->with('error', 'Periode jadwal mengajar aktif tidak tersedia.');
        }

        if ((int) $schedule->teaching_schedule_period_id !== (int) $activePeriod->id) {
            return redirect()->route('mobile.jadwal', ['period_id' => $schedule->teaching_schedule_period_id])->with('error', 'Jadwal ini berada di luar periode aktif, sehingga tidak bisa diubah dari mobile.');
        }

        $classes = $this->getSchoolClassOptions($schoolId, $activePeriod->id);
        $subjects = $this->getSchoolSubjectOptions($schoolId, $activePeriod->id);

        return view('mobile.jadwal-form', [
            'isEditing' => true,
            'schedule' => $schedule,
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedPeriod' => $activePeriod,
            'activePeriod' => $activePeriod,
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
        $activePeriod = $this->activePeriodForSchool($schoolId);
        if (!$activePeriod) {
            throw ValidationException::withMessages([
                'period_id' => 'Periode jadwal mengajar pada data ini tidak ditemukan.',
            ]);
        }
        $this->ensureRequestUsesActivePeriod($activePeriod, $request->integer('period_id'));

        if ((int) $schedule->teaching_schedule_period_id !== (int) $activePeriod->id) {
            return redirect()->route('mobile.jadwal', ['period_id' => $schedule->teaching_schedule_period_id])->with('error', 'Jadwal ini berada di luar periode aktif, sehingga tidak bisa diubah dari mobile.');
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
            ->with('teacher:id,name')
            ->where('teacher_id', $user->id)
            ->where('teaching_schedule_period_id', $activePeriod->id)
            ->where('day', $validated['day'])
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->first();

        if ($teacherOverlap) {
            return back()
                ->withErrors(['overlap' => $this->teacherOverlapMessage($teacherOverlap, $validated['day'])])
                ->withInput();
        }

        if (!in_array((int) $schoolId, [8, 9], true)) {
            $classOverlap = TeachingSchedule::query()
                ->with('teacher:id,name')
                ->where('school_id', $schoolId)
                ->where('teaching_schedule_period_id', $activePeriod->id)
                ->where('class_name', $validated['class_name'])
                ->where('day', $validated['day'])
                ->where('id', '!=', $schedule->id)
                ->where(function ($query) use ($validated) {
                    $query->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                })
                ->first();

            if ($classOverlap) {
                return back()
                    ->withErrors(['overlap' => $this->classOverlapMessage($classOverlap, $validated['day'])])
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

        return redirect()->route('mobile.jadwal', ['period_id' => $activePeriod->id])->with('success', 'Jadwal mengajar berhasil diperbarui.');
    }

    public function destroy(TeachingSchedule $schedule)
    {
        $user = Auth::user();

        if ($schedule->teacher_id !== $user->id) {
            abort(403);
        }

        $activePeriod = $this->activePeriodForSchool($schedule->school_id ?: $user->madrasah_id);
        if (!$activePeriod || (int) $schedule->teaching_schedule_period_id !== (int) $activePeriod->id) {
            return redirect()->route('mobile.jadwal', ['period_id' => $schedule->teaching_schedule_period_id])->with('error', 'Jadwal ini berada di luar periode aktif, sehingga tidak bisa dihapus dari mobile.');
        }

        $schedule->delete();

        return redirect()->route('mobile.jadwal', ['period_id' => $activePeriod->id])->with('success', 'Jadwal mengajar berhasil dihapus.');
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

    private function activePeriodForSchool(int|string|null $schoolId): ?TeachingSchedulePeriod
    {
        if (!$schoolId) {
            return null;
        }

        return TeachingSchedulePeriod::activeForSchool($schoolId, Carbon::today('Asia/Jakarta'));
    }

    private function ensureRequestUsesActivePeriod(TeachingSchedulePeriod $activePeriod, ?int $requestedPeriodId = null): void
    {
        if ($requestedPeriodId && (int) $requestedPeriodId !== (int) $activePeriod->id) {
            throw ValidationException::withMessages([
                'period_id' => 'Guru hanya bisa menginput jadwal mandiri pada periode yang sedang aktif saat ini.',
            ]);
        }
    }

    private function teacherOverlapMessage(TeachingSchedule $overlap, string $day): string
    {
        return sprintf(
            'Jadwal bentrok dengan jadwal Anda sendiri: %s kelas %s pada hari %s jam %s-%s.',
            trim((string) $overlap->subject),
            trim((string) $overlap->class_name),
            $day,
            substr((string) $overlap->start_time, 0, 5),
            substr((string) $overlap->end_time, 0, 5),
        );
    }

    private function classOverlapMessage(TeachingSchedule $overlap, string $day): string
    {
        $teacherName = trim((string) optional($overlap->teacher)->name);

        return sprintf(
            'Jadwal bentrok pada kelas %s: %s dengan %s pada hari %s jam %s-%s.',
            trim((string) $overlap->class_name),
            trim((string) $overlap->subject),
            $teacherName !== '' ? 'guru '.$teacherName : 'guru lain',
            $day,
            substr((string) $overlap->start_time, 0, 5),
            substr((string) $overlap->end_time, 0, 5),
        );
    }
}
