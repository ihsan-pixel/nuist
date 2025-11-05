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

        // Get banner image from app settings
        $appSettings = \App\Models\AppSetting::getSettings();
        $bannerImage = $appSettings->banner_image_url;

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

        return view('mobile.dashboard', compact('kehadiranPercent', 'totalBasis', 'izin', 'sakit', 'userInfo', 'todaySchedules', 'bannerImage'));
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
        $mapData = [];
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

            // Prepare map data
            $madrasahLat = $user->madrasah->latitude ?? -6.2088; // Default Jakarta coordinates
            $madrasahLng = $user->madrasah->longitude ?? 106.8456;

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
        }

        // Additional data expected by the mobile.presensi view
        $dateString = $selectedDate->toDateString();

        // Check holiday
        $isHoliday = \App\Models\Holiday::isHoliday($dateString);
        $holiday = $isHoliday ? \App\Models\Holiday::getHoliday($dateString) : null;

        // Presensi of the current user for the selected date (all madrasahs for dual presensi)
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $selectedDate)
            ->get();

        // Determine presensi time ranges based on madrasah hari_kbm (fallbacks included)
        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $hariKbm = $user->madrasah->hari_kbm;
            $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek; // 0=Sunday, 5=Friday

            if ($hariKbm == '5') {
                $masukStart = '05:00';
                $masukEnd = '07:00';
                $pulangStart = '15:00';
                $pulangEnd = '22:00';
            } elseif ($hariKbm == '6') {
                $masukStart = '05:00';
                $masukEnd = '07:00';
                // Khusus hari Jumat untuk 6 hari KBM, presensi pulang mulai pukul 14:30
                $pulangStart = ($dayOfWeek == 5) ? '14:30' : '15:00';
                $pulangEnd = '22:00';
            } else {
                $masukStart = '05:00';
                $masukEnd = '07:00';
                $pulangStart = '15:00';
                $pulangEnd = '22:00';
            }

            $timeRanges = [
                'masuk_start' => $masukStart,
                'masuk_end' => $masukEnd,
                'pulang_start' => $pulangStart,
                'pulang_end' => $pulangEnd,
            ];

            // Remove masuk_end to indicate no time limit for presensi entry
            $timeRanges['masuk_end'] = null;
        }

        return view('mobile.presensi', compact('presensis', 'belumPresensi', 'selectedDate', 'isHoliday', 'holiday', 'presensiHariIni', 'timeRanges', 'mapData'));
    }

    // Store presensi (mobile)
    public function storePresensi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
            'accuracy' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'device_info' => 'nullable|string',
            'location_readings' => 'nullable|string',
        ]);

        $tanggal = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        // Check if user already has presensi for today with stricter validation
        $existingPresensi = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        // Determine presensi type with additional checks
        $isPresensiMasuk = !$existingPresensi || (!$existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar);
        $isPresensiKeluar = $existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;

        // Prevent double submission for masuk if already exists
        if ($isPresensiMasuk && $existingPresensi && $existingPresensi->waktu_masuk) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi masuk hari ini sudah dicatat. Silakan lakukan presensi keluar jika belum.'
            ], 400);
        }

        // Prevent double submission for keluar if already exists
        if ($isPresensiKeluar && $existingPresensi && $existingPresensi->waktu_keluar) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi keluar hari ini sudah dicatat. Presensi hari ini sudah lengkap.'
            ], 400);
        }

        // Location validation using polygon from madrasah
        $madrasah = $user->madrasah;
        $isWithinPolygon = false;

        $polygonsToCheck = [];
        if ($madrasah && $madrasah->polygon_koordinat) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat;
        }
        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $polygonsToCheck[] = $madrasah->polygon_koordinat_2;
        }

        if (!empty($polygonsToCheck)) {
            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinPolygon = true;
                            break; // Jika sudah ada yang valid, tidak perlu cek yang lain
                        }
                    }
                } catch (\Exception $e) {
                    continue; // Skip invalid polygon
                }
            }
        }

        // Strict polygon validation: must be within madrasah polygon
        if (!$isWithinPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area sekolah yang telah ditentukan. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.'
            ], 400);
        }

        // Determine if this is presensi masuk or keluar
        if ($isPresensiMasuk) {
            // Presensi Masuk
            $status = 'hadir';
            $waktuMasuk = $now;
            $waktuKeluar = null;

            // Calculate lateness - only set keterangan if late (after 07:00)
            $keterangan = "";
            if ($user->pemenuhan_beban_kerja_lain) {
                $keterangan = "tidak terlambat";
            } else {
                // Jika waktu presensi setelah 07:00, hitung keterlambatan
                if ($now->format('H:i:s') > '07:00:00') {
                    $batas = Carbon::createFromFormat('H:i:s', '07:00:00', 'Asia/Jakarta');
                    $sekarang = Carbon::now('Asia/Jakarta');
                    $terlambatMenit = $sekarang->floatDiffInMinutes($batas);

                    // Pastikan keterlambatan tidak negatif dan bulatkan angkanya
                    if ($sekarang->lessThan($batas)) {
                        $terlambatMenit = 0;
                    } else {
                        $terlambatMenit = abs(round($terlambatMenit));
                    }

                    $keterangan = "Terlambat {$terlambatMenit} menit";
                } else {
                    $keterangan = "tidak terlambat";
                }
            }

            // Create new presensi record
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'waktu_masuk' => $waktuMasuk,
                'waktu_keluar' => $waktuKeluar,
                'status' => $status,
                'keterangan' => $keterangan,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'accuracy' => $request->accuracy,
                'altitude' => $request->altitude,
                'speed' => $request->speed,
                'device_info' => $request->device_info,
                'location_readings' => $request->location_readings,
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
            ]);

            $message = 'Presensi masuk berhasil dicatat!';

        } elseif ($isPresensiKeluar) {
            // Check if it's time to go home (after pulang_start time)
            $pulangStart = '15:00:00'; // Default pulang start time
            if ($user->madrasah && $user->madrasah->hari_kbm) {
                $hariKbm = $user->madrasah->hari_kbm;
                if ($hariKbm == '5' || $hariKbm == '6') {
                    $pulangStart = '15:00:00';
                } else {
                    $pulangStart = '15:00:00';
                }
            }

            if ($now->format('H:i:s') < $pulangStart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi keluar belum dapat dilakukan. Waktu presensi keluar dimulai pukul ' . substr($pulangStart, 0, 5) . '.'
                ], 400);
            }

            // Presensi Keluar - update existing record
            $existingPresensi->update([
                'waktu_keluar' => $now,
                'latitude_keluar' => $request->latitude,
                'longitude_keluar' => $request->longitude,
                'lokasi_keluar' => $request->lokasi,
                'accuracy_keluar' => $request->accuracy,
                'altitude_keluar' => $request->altitude,
                'speed_keluar' => $request->speed,
                'device_info_keluar' => $request->device_info,
                'location_readings_keluar' => $request->location_readings,
            ]);

            $presensi = $existingPresensi;
            $message = 'Presensi keluar berhasil dicatat!';

        } else {
            // Both masuk and keluar already done or invalid state
            return response()->json([
                'success' => false,
                'message' => 'Presensi hari ini sudah lengkap atau dalam keadaan tidak valid.'
            ], 400);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'presensi' => $presensi
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    // Riwayat presensi
    public function riwayatPresensi(Request $request)
    {
        $user = Auth::user();

        // only tenaga_pendidik may access mobile pages
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // allow optional month navigation via ?month=2025-10-01
        $selectedMonth = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();

        // Fetch presensi for the selected month for the authenticated user
        $presensiHistory = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('mobile.riwayat-presensi', compact('presensiHistory'));
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
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $type = $request->input('type');

        // Common: tanggal presence
        if (empty($type)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tipe izin tidak diketahui.'], 422);
            }
            return redirect()->back()->with('error', 'Tipe izin tidak diketahui.');
        }

        // Normalize type
        $type = strtolower($type);

        // Validate and map input per type
        $filePath = null;
        $keterangan = '';
        $tanggal = $request->input('tanggal');

        // Prevent duplicate presensi records on same date
        if ($tanggal) {
            $existing = Presensi::where('user_id', $user->id)->where('tanggal', $tanggal)->first();
            if ($existing) {
                $msg = 'Anda sudah memiliki catatan kehadiran pada tanggal ini.';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                return redirect()->back()->with('error', $msg);
            }
        }

        switch ($type) {
            case 'sakit':
                $request->validate([
                    'tanggal' => 'required|date',
                    'keterangan' => 'required|string',
                    'surat_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('keterangan');
                if ($request->hasFile('surat_izin')) {
                    $filePath = $request->file('surat_izin')->store('surat_izin', 'public');
                }
                break;

            case 'tidak_masuk':
                $request->validate([
                    'tanggal' => 'required|date',
                    'alasan' => 'required|string',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('alasan');
                if ($request->hasFile('file_izin')) {
                    $filePath = $request->file('file_izin')->store('surat_izin', 'public');
                }
                break;

            case 'terlambat':
                $request->validate([
                    'tanggal' => 'required|date',
                    'alasan' => 'required|string',
                    'waktu_masuk' => 'required',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('alasan') . ' (Waktu masuk: ' . $request->input('waktu_masuk') . ')';
                if ($request->hasFile('file_izin')) {
                    $filePath = $request->file('file_izin')->store('surat_izin', 'public');
                }
                break;

            case 'tugas_luar':
                $request->validate([
                    'tanggal' => 'required|date',
                    'deskripsi_tugas' => 'required|string',
                    'lokasi_tugas' => 'required|string',
                    'waktu_masuk' => 'required',
                    'waktu_keluar' => 'required',
                    'file_tugas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $keterangan = $request->input('deskripsi_tugas') . '\nLokasi: ' . $request->input('lokasi_tugas') . '\n' . 'Waktu: ' . $request->input('waktu_masuk') . ' - ' . $request->input('waktu_keluar');
                if ($request->hasFile('file_tugas')) {
                    $filePath = $request->file('file_tugas')->store('surat_izin', 'public');
                }
                break;

            case 'cuti':
                $request->validate([
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                    'alasan' => 'required|string',
                    'file_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);

                $tanggal = $request->input('tanggal_mulai');
                $keterangan = $request->input('alasan') . '\nTanggal: ' . $request->input('tanggal_mulai') . ' sampai ' . $request->input('tanggal_selesai');
                if ($request->hasFile('file_izin')) {
                    $filePath = $request->file('file_izin')->store('surat_izin', 'public');
                }
                break;

            default:
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Tipe izin tidak dikenali.'], 422);
                }
                return redirect()->back()->with('error', 'Tipe izin tidak dikenali.');
        }

        // Create Presensi record with status 'izin'
        $presensi = Presensi::create([
            'user_id' => $user->id,
            'tanggal' => $tanggal,
            'status' => 'izin',
            'keterangan' => $keterangan,
            'surat_izin_path' => $filePath,
            'status_izin' => 'pending',
            'status_kepegawaian_id' => $user->status_kepegawaian_id,
        ]);

        // Notify user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'izin_submitted',
            'title' => 'Izin Diajukan',
            'message' => 'Pengajuan izin Anda telah dikirim dan menunggu persetujuan.',
            'data' => [
                'presensi_id' => $presensi->id,
                'tanggal' => $tanggal,
                'type' => $type,
            ]
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Izin berhasil diajukan dan menunggu persetujuan.']);
        }

        return redirect()->route('mobile.riwayat-presensi')->with('success', 'Izin berhasil diajukan dan menunggu persetujuan.');
    }

    public function izin()
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $type = request('type');

        // If no specific type requested, show menu
        if (!$type) {
            return view('mobile.izin');
        }

        // Normalize
        $type = strtolower($type);

        switch ($type) {
            case 'sakit':
                return view('mobile.izin-sakit');
            case 'terlambat':
                return view('mobile.izin-terlambat');
            case 'tidak_masuk':
            case 'tidak-masuk':
                return view('mobile.izin-tidak-masuk');
            case 'tugas_luar':
            case 'tugas-luar':
                return view('mobile.izin-tugas-luar');
            case 'cuti':
                return view('mobile.izin-cuti');
            default:
                // Unknown type -> show menu with flash
                return redirect()->route('mobile.izin')->with('error', 'Tipe izin tidak dikenali.');
        }
    }

    public function kelolaIzin()
    {
        $user = Auth::user();

        $status = request('status', 'pending');

        // Kepala madrasah: show requests for the whole madrasah
        if ((($user->role === 'tenaga_pendidik') || ($user->role === 'admin')) && $user->ketugasan === 'kepala madrasah/sekolah') {
            // Only show presensi records that are izin submissions
            $izinQuery = Presensi::with('user')
                ->where('status', 'izin')
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
            // Regular tenaga_pendidik should only see their own izin submissions
            $izinQuery = Presensi::with('user')
                ->where('user_id', $user->id)
                ->where('status', 'izin')
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
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $selectedMonth = Carbon::now();

        $history = \App\Models\TeachingAttendance::with(['teachingSchedule.school'])
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('mobile.laporan-mengajar', compact('history'));
    }

    // Teaching attendances (mobile)
    public function teachingAttendances(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // Always use today's date, ignore any date param to show only current day data
        $selectedDate = Carbon::today('Asia/Jakarta');
        $todayName = $selectedDate->locale('id')->dayName;

        // Build schedule query with today's teaching attendances, filtered by current day
        $query = TeachingSchedule::with(['teacher', 'school', 'teachingAttendances' => function ($q) use ($selectedDate) {
            $q->whereDate('tanggal', $selectedDate);
        }]);

        // Always show only the user's own teaching schedules
        $query->where('teacher_id', $user->id);

        // Filter by current day's name
        $query->where('day', $todayName);

        $schedules = $query->orderBy('start_time')->get();

        // Filter to show only schedules that are not past (end_time >= current time)
        $currentTime = Carbon::now('Asia/Jakarta')->format('H:i:s');
        $schedules = $schedules->filter(function ($schedule) use ($currentTime) {
            return $schedule->end_time >= $currentTime;
        });

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
