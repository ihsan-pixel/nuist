<?php

namespace App\Http\Controllers;

use App\Models\TeachingAttendance;
use App\Models\TeachingSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeachingAttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if tenaga_pendidik and password not changed
        if ($user->role === 'tenaga_pendidik' && !$user->password_changed) {
            return redirect()->route('dashboard')->with('error', 'Anda harus mengubah password terlebih dahulu sebelum mengakses menu presensi mengajar.');
        }

        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $dayOfWeek = Carbon::parse($today)->locale('id')->dayName; // e.g., 'Senin'

        // Get today's schedules for the teacher
        $schedules = TeachingSchedule::with(['school', 'teacher'])
            ->where('teacher_id', $user->id)
            ->where('day', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        // Attach attendance status for each schedule
        foreach ($schedules as $schedule) {
            $attendance = TeachingAttendance::where('teaching_schedule_id', $schedule->id)
                ->where('tanggal', $today)
                ->first();
            $schedule->attendance = $attendance;
        }

        return view('teaching-attendances.index', compact('schedules', 'today'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teaching_schedule_id' => 'required|exists:teaching_schedules,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
        ]);

        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta')->format('H:i:s');

        // Get the schedule
        $schedule = TeachingSchedule::findOrFail($request->teaching_schedule_id);

        // Check if the schedule belongs to the user
        if ($schedule->teacher_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak valid.'
            ], 403);
        }

        // Check if already attended today
        $existingAttendance = TeachingAttendance::where('teaching_schedule_id', $schedule->id)
            ->where('tanggal', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi untuk jadwal ini hari ini.'
            ], 400);
        }

        // Time validation: current time must be within schedule start_time to end_time
        $currentTime = Carbon::now('Asia/Jakarta');
        $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
        $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');

        // Strict time validation: only within schedule time
        if (!$currentTime->between($startTime, $endTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu presensi harus dilakukan dalam rentang waktu mengajar (' . $schedule->start_time . ' - ' . $schedule->end_time . '). Waktu sekarang: ' . $now
            ], 400);
        }

        // Location validation using polygon from madrasah
        $madrasah = $schedule->school;
        $isWithinPolygon = false;
        $polygonError = '';

        if ($madrasah && $madrasah->polygon_koordinat) {
            try {
                $polygonGeometry = json_decode($madrasah->polygon_koordinat, true);
                if (isset($polygonGeometry['coordinates'][0])) {
                    $polygon = $polygonGeometry['coordinates'][0];
                    if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                        $isWithinPolygon = true;
                    } else {
                        $polygonError = 'Lokasi Anda (' . $request->latitude . ', ' . $request->longitude . ') berada di luar area sekolah.';
                    }
                } else {
                    $polygonError = 'Format polygon koordinat tidak valid.';
                }
            } catch (\Exception $e) {
                $polygonError = 'Error memproses polygon: ' . $e->getMessage();
            }
        } else {
            $polygonError = 'Madrasah belum memiliki polygon koordinat yang ditentukan.';
        }

        // Strict polygon validation: must be within madrasah polygon
        if (!$isWithinPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area sekolah yang telah ditentukan. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.'
            ], 400);
        }

        // Create attendance
        $attendance = TeachingAttendance::create([
            'teaching_schedule_id' => $schedule->id,
            'user_id' => $user->id,
            'tanggal' => $today,
            'waktu' => $now,
            'status' => 'hadir',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'lokasi' => $request->lokasi,
        ]);

        $message = 'Presensi mengajar berhasil dicatat pada ' . $now . '.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $attendance
        ]);
    }

    /**
     * Checks if a point is inside a polygon using the ray-casting algorithm.
     * @param array $point The point to check, in [longitude, latitude] format.
     * @param array $polygon An array of polygon vertices, each in [longitude, latitude] format.
     * @return bool True if the point is inside the polygon, false otherwise.
     */
    private function isPointInPolygon(array $point, array $polygon): bool
    {
        $pointLng = $point[0];
        $pointLat = $point[1];
        $isInside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $j = $i++) {
            $vertexiLat = $polygon[$i][1];
            $vertexiLng = $polygon[$i][0];
            $vertexjLat = $polygon[$j][1];
            $vertexjLng = $polygon[$j][0];

            // This is the core of the ray-casting algorithm
            if ((($vertexiLat > $pointLat) != ($vertexjLat > $pointLat)) &&
                ($pointLng < ($vertexjLng - $vertexiLng) * ($pointLat - $vertexiLat) / ($vertexjLat - $vertexiLat) + $vertexiLng)) {
                $isInside = !$isInside;
            }
        }

        return $isInside;
    }
}
