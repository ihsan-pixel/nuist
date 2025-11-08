<?php

namespace App\Http\Controllers\Mobile\Jadwal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\TeachingSchedule;

class JadwalController extends \App\Http\Controllers\Controller
{
    // Jadwal view (mobile)
    public function jadwal()
    {
        $user = Auth::user();

        // Allow all tenaga_pendidik to access jadwal
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // If kepala madrasah, show madrasah-level schedules; otherwise show personal schedules for the teacher
        if ($user->ketugasan === 'kepala madrasah/sekolah') {
            $schedules = TeachingSchedule::with(['teacher', 'school'])
                ->where('school_id', $user->madrasah_id)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');

            $classes = TeachingSchedule::where('school_id', $user->madrasah_id)
                ->select('class_name')
                ->distinct()
                ->pluck('class_name')
                ->sort();

            $subjects = TeachingSchedule::where('school_id', $user->madrasah_id)
                ->select('subject')
                ->distinct()
                ->pluck('subject')
                ->sort();
        } else {
            // Personal schedules for non-kepala teachers
            $schedules = TeachingSchedule::with(['teacher', 'school'])
                ->where('teacher_id', $user->id)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');

            $classes = TeachingSchedule::where('teacher_id', $user->id)
                ->select('class_name')
                ->distinct()
                ->pluck('class_name')
                ->sort();

            $subjects = TeachingSchedule::where('teacher_id', $user->id)
                ->select('subject')
                ->distinct()
                ->pluck('subject')
                ->sort();
        }

        return view('mobile.jadwal', compact('schedules', 'classes', 'subjects'));
    }
}
