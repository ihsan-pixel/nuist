<?php

namespace App\Http\Controllers\Mobile\Monitoring;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use App\Models\TeachingSchedule;

class MonitoringController extends \App\Http\Controllers\Controller
{
    /**
     * Monitoring presensi page for kepala madrasah
     */
    public function monitorPresensi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala madrasah can access this page.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $presensis = Presensi::with(['user', 'statusKepegawaian'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            })
            ->whereDate('tanggal', $selectedDate)
            ->orderBy('waktu_masuk', 'desc')
            ->paginate(15);

        $belumPresensi = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            })
            ->paginate(15);

        return view('mobile.monitor-presensi', compact('presensis', 'belumPresensi', 'selectedDate'));
    }

    /**
     * Monitoring jadwal mengajar page for kepala madrasah
     */
    public function monitorJadwalMengajar(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala madrasah can access this page.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Get day name in Indonesian for the selected date
        $dayName = $selectedDate->locale('id')->dayName;

        // Fetch teaching schedules for the madrasah on the selected day
        $schedules = TeachingSchedule::with(['teacher', 'teachingAttendances' => function ($q) use ($selectedDate) {
            $q->whereDate('tanggal', $selectedDate);
        }])
        ->where('school_id', $user->madrasah_id)
        ->where('day', $dayName)
        ->orderBy('start_time')
        ->get();

        // Attach attendance status to each schedule
        $schedules->each(function ($schedule) {
            $schedule->attendance_status = $schedule->teachingAttendances->first() ? 'hadir' : 'belum';
            $schedule->attendance_time = $schedule->teachingAttendances->first() ? $schedule->teachingAttendances->first()->waktu : null;
        });

        return view('mobile.monitor-jadwal-mengajar', compact('schedules', 'selectedDate'));
    }

    /**
     * Dedicated map monitoring page for kepala madrasah
     */
    public function monitorMap(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala madrasah can access this page.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Get presensi data for the madrasah
        $presensis = Presensi::with(['user', 'statusKepegawaian'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            })
            ->whereDate('tanggal', $selectedDate)
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Get users who haven't done presensi
        $belumPresensi = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            })
            ->get();

        // Prepare map data
        $madrasahLat = $user->madrasah->latitude ?? -6.2088; // Default Jakarta coordinates
        $madrasahLng = $user->madrasah->longitude ?? 106.8456;
        $mapData = [];

        // Add markers for users who have done presensi
        foreach ($presensis as $presensi) {
            $mapData[] = [
                'id' => $presensi->user->id,
                'name' => $presensi->user->name,
                'status' => $presensi->status,
                'latitude' => $presensi->latitude ?? $madrasahLat,
                'longitude' => $presensi->longitude ?? $madrasahLng,
                'waktu_masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : null,
                'waktu_keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : null,
                'lokasi' => $presensi->lokasi ?? 'Lokasi tidak tersedia',
                'marker_type' => 'presensi',
                'status_kepegawaian' => $presensi->user->statusKepegawaian?->name ?? '-'
            ];
        }

        // Add markers for users who haven't done presensi (at madrasah location)
        foreach ($belumPresensi as $userBelum) {
            $mapData[] = [
                'id' => $userBelum->id,
                'name' => $userBelum->name,
                'status' => 'belum_presensi',
                'latitude' => $madrasahLat,
                'longitude' => $madrasahLng,
                'waktu_masuk' => null,
                'waktu_keluar' => null,
                'lokasi' => $user->madrasah->alamat ?? 'Alamat madrasah',
                'marker_type' => 'belum_presensi',
                'status_kepegawaian' => $userBelum->statusKepegawaian?->name ?? '-'
            ];
        }

        return view('mobile.monitor-map', compact('mapData', 'selectedDate', 'presensis', 'belumPresensi'));
    }
}
