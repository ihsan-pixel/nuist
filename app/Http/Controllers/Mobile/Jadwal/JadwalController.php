<?php

namespace App\Http\Controllers\Mobile\Jadwal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\TeachingSchedule;
use App\Models\TeachingSchedulePeriod;

class JadwalController extends \App\Http\Controllers\Controller
{
    // Jadwal view (mobile)
    public function jadwal(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->madrasah_id;
        $periods = $schoolId
            ? TeachingSchedulePeriod::query()
                ->where('school_id', $schoolId)
                ->orderByDesc('end_date')
                ->orderByDesc('start_date')
                ->get()
            : collect();
        $selectedPeriod = $schoolId
            ? $this->resolveSelectedPeriod($schoolId, $request->integer('period_id'))
            : null;

        // Allow all tenaga_pendidik to access jadwal
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // If kepala madrasah, show madrasah-level schedules; otherwise show personal schedules for the teacher
        if ($user->ketugasan === 'kepala madrasah/sekolah') {
            $scheduleQuery = TeachingSchedule::with(['teacher', 'school', 'period'])
                ->where('school_id', $user->madrasah_id);
        } else {
            $scheduleQuery = TeachingSchedule::with(['teacher', 'school', 'period'])
                ->where('teacher_id', $user->id);
        }

        $scheduleQuery->when(
            $selectedPeriod,
            fn ($query) => $query->where('teaching_schedule_period_id', $selectedPeriod->id),
            fn ($query) => $query->whereRaw('1 = 0')
        );

        $baseSchedules = $scheduleQuery
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $schedules = $baseSchedules->groupBy('day');
        $classes = $baseSchedules->pluck('class_name')->filter()->unique()->sort()->values();
        $subjects = $baseSchedules->pluck('subject')->filter()->unique()->sort()->values();

        return view('mobile.jadwal', compact('schedules', 'classes', 'subjects', 'periods', 'selectedPeriod'));
    }

    private function resolveSelectedPeriod(int $schoolId, ?int $periodId = null): ?TeachingSchedulePeriod
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

        return TeachingSchedulePeriod::activeForSchool($schoolId, Carbon::today('Asia/Jakarta'))
            ?? TeachingSchedulePeriod::latestForSchool($schoolId);
    }
}
