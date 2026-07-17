<?php

namespace App\Http\Controllers\Mobile\Jadwal;

use App\Models\TeachingSchedule;
use App\Models\TeachingSchedulePeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends \App\Http\Controllers\Controller
{
    // Jadwal view (mobile)
    public function jadwal(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->madrasah_id;
        $activePeriod = $schoolId
            ? TeachingSchedulePeriod::activeForSchool($schoolId, Carbon::today('Asia/Jakarta'))
            : null;
        $selectedPeriod = $activePeriod;

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
        $canManageSelectedPeriod = (bool) $activePeriod;

        return view('mobile.jadwal', compact(
            'schedules',
            'classes',
            'subjects',
            'selectedPeriod',
            'activePeriod',
            'canManageSelectedPeriod',
        ));
    }
}
