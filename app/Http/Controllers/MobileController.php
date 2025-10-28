<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use App\Models\TeachingSchedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MobileController extends Controller
{
    // Mobile dashboard for tenaga_pendidik
    public function dashboard()
    {
        $user = Auth::user();

        // simple role check (middleware already restricts but keep safe-guard)
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // Determine start date for attendance calculation (first presensi or account creation)
        $firstPresensiDate = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if ($firstPresensiDate) {
            $startDate = Carbon::parse($firstPresensiDate);
        } else {
            $startDate = Carbon::parse($user->created_at);
        }

        $today = Carbon::now();

        // Aggregate presensi counts for the user between startDate and today
        $presensiCounts = Presensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate, $today])
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $hadir = $presensiCounts['hadir'] ?? 0;
        $izin = $presensiCounts['izin'] ?? 0;
        $sakit = $presensiCounts['sakit'] ?? 0;
        $alpha = $presensiCounts['alpha'] ?? 0;

        $totalBasis = array_sum($presensiCounts);

        $kehadiranPercent = $totalBasis > 0 ? round(($hadir / $totalBasis) * 100, 2) : 0;

        // Prepare user info array expected by the view
        $userInfo = [
            'nuist_id' => $user->nuist_id ?? '-',
            'status_kepegawaian' => $user->statusKepegawaian?->name ?? '-',
            'ketugasan' => $user->ketugasan ?? '-',
            'tempat_lahir' => $user->tempat_lahir ?? '-',
            'tanggal_lahir' => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('d-m-Y') : '-',
            'tmt' => $user->tmt ? Carbon::parse($user->tmt)->format('d-m-Y') : '-',
            'nuptk' => $user->nuptk ?? '-',
            'npk' => $user->npk ?? '-',
            'kartanu' => $user->kartanu ?? '-',
            'nip' => $user->nip ?? '-',
            'pendidikan_terakhir' => $user->pendidikan_terakhir ?? '-',
            'program_studi' => $user->program_studi ?? '-',
        ];

        // Today's schedules for the teacher
        $todayName = Carbon::parse($today)->locale('id')->dayName; // e.g., 'Senin'
        $todaySchedules = TeachingSchedule::where('teacher_id', $user->id)
            ->where('day', $todayName)
            ->orderBy('start_time')
            ->get();

        return view('mobile.dashboard', compact('kehadiranPercent', 'totalBasis', 'izin', 'sakit', 'userInfo', 'todaySchedules'));
    }

    // Presensi view (mobile)
    public function presensi(Request $request)
    {
        $user = Auth::user();

        // Allow all tenaga_pendidik to access presensi form; kepala madrasah will see madrasah-level monitoring data
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // If kepala madrasah, fetch madrasah-level presensi lists; otherwise, leave empty (non-kepala see personal presensi only)
        $presensis = collect();
        $belumPresensi = collect();
        if ($user->ketugasan === 'kepala madrasah/sekolah') {
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
        }

        // Additional data expected by the mobile.presensi view
        $dateString = $selectedDate->toDateString();

        // Check holiday
        $isHoliday = \App\Models\Holiday::isHoliday($dateString);
        $holiday = $isHoliday ? \App\Models\Holiday::getHoliday($dateString) : null;

        // Presensi of the current user for the selected date
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $selectedDate)
            ->first();

        // Determine presensi time ranges based on madrasah hari_kbm (fallbacks included)
        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek; // 0=Sunday
            $hariKbm = $user->madrasah->hari_kbm;

            if ($hariKbm == '5') {
                $masukStart = '06:00';
                $masukEnd = '07:00';
                $pulangStart = ($dayOfWeek == 5) ? '14:00' : '14:30';
                $pulangEnd = '17:00';
            } elseif ($hariKbm == '6') {
                $masukStart = '06:00';
                $masukEnd = '07:00';
                $pulangStart = ($dayOfWeek == 6) ? '12:00' : '13:00';
                $pulangEnd = '17:00';
            } else {
                $masukStart = '06:00';
                $masukEnd = '07:00';
                $pulangStart = '13:00';
                $pulangEnd = '17:00';
            }

            $timeRanges = [
                'masuk_start' => $masukStart,
                'masuk_end' => $masukEnd,
                'pulang_start' => $pulangStart,
                'pulang_end' => $pulangEnd,
            ];

            // Adjust for users without pemenuhan_beban_kerja_lain
            if ($user->role === 'tenaga_pendidik' && !$user->pemenuhan_beban_kerja_lain) {
                // Mirror behavior from PresensiController: shorten masuk_end for special users
                $timeRanges['masuk_end'] = '08:00';
            }
        }

        return view('mobile.presensi', compact('presensis', 'belumPresensi', 'selectedDate', 'isHoliday', 'holiday', 'presensiHariIni', 'timeRanges'));
    }

    // Store presensi (stub)
    public function storePresensi(Request $request)
    {
        // Delegate to main PresensiController so we keep a single source of truth
        $presensiController = app(\App\Http\Controllers\PresensiController::class);
        return $presensiController->store($request);
    }

    // Riwayat presensi (stub)
    public function riwayatPresensi()
    {
        return view('mobile.riwayat-presensi');
    }

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

    // Profile and account management stubs
    public function profile()
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // pass the authenticated user to the view so blade can reference $user
        return view('mobile.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_hp = $request->input('phone');
        $user->tempat_lahir = $request->input('tempat_lahir');
        $user->tanggal_lahir = $request->input('tanggal_lahir') ?: null;
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui']);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('avatar')) {
            // delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Foto profil berhasil diperbarui', 'avatar' => $user->avatar]);
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Password lama tidak sesuai'], 422);
            }
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->password = Hash::make($validated['password']);
        // mark that the user has changed the password if such column exists
        if (isset($user->password_changed)) {
            $user->password_changed = true;
        }
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Password berhasil diperbarui']);
        }

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }

    // Izin (leave) stubs
    public function storeIzin(Request $request)
    {
        return redirect()->back();
    }

    public function izin()
    {
        return view('mobile.izin');
    }

    public function kelolaIzin()
    {
        $user = Auth::user();

        $status = request('status', 'pending');

        // Kepala madrasah: show requests for the whole madrasah
        if ($user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah') {
            $izinQuery = Presensi::with('user')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                })
                ->orderBy('tanggal', 'desc');

            if ($status !== 'all') {
                $izinQuery->where('status_izin', $status);
            }

            $izinRequests = $izinQuery->paginate(10);

            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Regular tenaga_pendidik: show only their own izin requests
        if ($user->role === 'tenaga_pendidik') {
            $izinQuery = Presensi::with('user')
                ->where('user_id', $user->id)
                ->orderBy('tanggal', 'desc');

            if ($status !== 'all') {
                $izinQuery->where('status_izin', $status);
            }

            $izinRequests = $izinQuery->paginate(10);
            return view('mobile.kelola-izin', compact('izinRequests'));
        }

        // Fallback: unauthorized for other roles
        abort(403, 'Unauthorized.');
    }

    // Laporan (reports) stubs
    public function laporan()
    {
        return view('mobile.laporan');
    }

    public function laporanMengajar()
    {
        return view('mobile.laporan-mengajar');
    }

    // Teaching attendances (mobile)
    public function teachingAttendances(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // allow optional date query param for browsing
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Build schedule query with today's teaching attendances
        $query = TeachingSchedule::with(['teacher', 'school', 'teachingAttendances' => function ($q) use ($selectedDate) {
            $q->whereDate('tanggal', $selectedDate);
        }]);

        // Kepala sees school schedules, ordinary teachers see their own
        if ($user->ketugasan === 'kepala madrasah/sekolah') {
            $query->where('school_id', $user->madrasah_id);
        } else {
            $query->where('teacher_id', $user->id);
        }

        $schedules = $query->orderBy('day')->orderBy('start_time')->get();

        // Normalize: attach shortcut `attendance` to each schedule (first attendance of the day or null)
        $schedules->each(function ($schedule) {
            $schedule->attendance = $schedule->teachingAttendances->first() ?? null;
        });

        $today = $selectedDate->toDateString();

        return view('mobile.teaching-attendances', compact('today', 'schedules'));
    }

    // Account change
    public function ubahAkun()
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        return view('mobile.ubah-akun', compact('user'));
    }

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
}
