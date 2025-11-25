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
                    // Get all teachers for this madrasah excluding status_kepegawaian_id 7 and 8
                    $teachers = User::where('madrasah_id', $madrasah->id)
                        ->where('role', 'tenaga_pendidik')
                        ->whereNotIn('status_kepegawaian_id', [7, 8])
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
                    $attendancePendingPercentage = 100 - $attendancePercentage;

                    // Calculate details
                    $teachersWithoutSchedule = $totalTeachers - $teachersWithSchedules;
                    $teachersWithoutAttendance = $totalTeachers - $teachersWithAttendance;

                    $madrasah->schedule_input_percentage = $scheduleInputPercentage;
                    $madrasah->attendance_percentage = $attendancePercentage;
                    $madrasah->attendance_pending_percentage = $attendancePendingPercentage;

                    // Add details for tooltips/rich display
                    $madrasah->total_teachers = $totalTeachers;
                    $madrasah->teachers_with_schedule = $teachersWithSchedules;
                    $madrasah->teachers_without_schedule = $teachersWithoutSchedule;
                    $madrasah->teachers_with_attendance = $teachersWithAttendance;
                    $madrasah->teachers_without_attendance = $teachersWithoutAttendance;

                    return $madrasah;
                });
            });

        return view('admin.teaching_progress', compact('madrasahs', 'kabupatenOrder'));
    }


    /**
     * Get teacher detail data for a madrasah.
     */
    public function getMadrasahTeachers($madrasahId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $teachers = User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->whereNotIn('status_kepegawaian_id', [7, 8])
            ->get()
            ->map(function ($teacher) use ($currentMonth, $currentYear) {
                $hasAttendance = \App\Models\TeachingAttendance::whereHas('teachingSchedule', function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->exists();

                return [
                    'name' => $teacher->name,
                    'status_kepegawaian' => $teacher->statusKepegawaian->name ?? '-',
                    'presensi_status' => $hasAttendance ? 'Sudah Presensi' : 'Belum Presensi',
                ];
            });

        return response()->json([
            'teachers' => $teachers
        ]);
    }
}
