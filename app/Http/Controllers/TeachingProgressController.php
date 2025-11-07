<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\User;
use App\Models\TeachingSchedule;
use App\Models\TeachingAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeachingProgressController extends Controller
{
    /**
     * Display the teaching progress for all madrasahs
     */
    public function index()
    {
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $madrasahs = Madrasah::orderByRaw("FIELD(kabupaten, '" . implode("','", $kabupatenOrder) . "')")
            ->get()
            ->groupBy('kabupaten')
            ->map(function ($group) use ($kabupatenOrder) {
                return $group->sortBy(function ($madrasah) {
                    return $madrasah->scod ?? PHP_INT_MAX;
                })->map(function ($madrasah) {
                    // Get all teachers for this madrasah
                    $teachers = User::where('madrasah_id', $madrasah->id)
                        ->where('role', 'tenaga_pendidik')
                        ->get();

                    $totalTeachers = $teachers->count();

                    if ($totalTeachers == 0) {
                        $madrasah->schedule_input_percentage = 0;
                        $madrasah->attendance_percentage = 0;
                        $madrasah->attendance_done_percentage = 0;
                        $madrasah->attendance_pending_percentage = 100;
                        return $madrasah;
                    }

                    // Count teachers with schedules
                    $teachersWithSchedules = TeachingSchedule::where('school_id', $madrasah->id)
                        ->distinct('teacher_id')
                        ->count('teacher_id');

                    $scheduleInputPercentage = round(($teachersWithSchedules / $totalTeachers) * 100);

                    // Get current month and year for attendance calculation
                    $currentMonth = Carbon::now()->month;
                    $currentYear = Carbon::now()->year;

                    // Count teachers who have done attendance this month
                    $teachersWithAttendance = TeachingAttendance::whereHas('teachingSchedule', function($query) use ($madrasah) {
                        $query->where('school_id', $madrasah->id);
                    })
                    ->whereMonth('tanggal', $currentMonth)
                    ->whereYear('tanggal', $currentYear)
                    ->distinct('user_id')
                    ->count('user_id');

                    $attendancePercentage = round(($teachersWithAttendance / $totalTeachers) * 100);
                    $attendanceDonePercentage = $attendancePercentage;
                    $attendancePendingPercentage = 100 - $attendancePercentage;

                    $madrasah->schedule_input_percentage = $scheduleInputPercentage;
                    $madrasah->attendance_percentage = $attendancePercentage;
                    $madrasah->attendance_done_percentage = $attendanceDonePercentage;
                    $madrasah->attendance_pending_percentage = $attendancePendingPercentage;

                    return $madrasah;
                });
            });

        return view('admin.teaching_progress', compact('madrasahs', 'kabupatenOrder'));
    }
}
