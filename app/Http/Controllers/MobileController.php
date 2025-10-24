<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Presensi;
use App\Models\TeachingSchedule;
use Carbon\Carbon;

class MobileController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Hitung data presensi bulan ini
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $firstPresensiDate = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if ($firstPresensiDate) {
            $startDate = Carbon::parse($firstPresensiDate);
        } else {
            $startDate = Carbon::parse($user->created_at);
        }

        $today = now();
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

        $kehadiranPercent = $totalBasis > 0 ? round(($hadir / $totalBasis) * 100, 1) : 0;

        // Cek presensi hari ini
        $todayPresensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today->toDateString())
            ->first();

        // Jadwal hari ini
        $todaySchedules = TeachingSchedule::with(['school'])
            ->where('teacher_id', $user->id)
            ->where('day', $today->locale('id')->dayName)
            ->orderBy('start_time')
            ->get();

        // Get user information for display
        $userInfo = [
            'nuist_id' => $user->nuist_id ?? '-',
            'status_kepegawaian' => $user->statusKepegawaian ? $user->statusKepegawaian->name : '-',
            'ketugasan' => $user->ketugasan ?? '-',
            'tempat_lahir' => $user->tempat_lahir ?? '-',
            'tanggal_lahir' => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('d F Y') : '-',
            'tmt' => $user->tmt ? Carbon::parse($user->tmt)->format('d F Y') : '-',
            'nuptk' => $user->nuptk ?? '-',
            'npk' => $user->npk ?? '-',
            'kartanu' => $user->kartanu ?? '-',
            'nip' => $user->nip ?? '-',
            'pendidikan_terakhir' => $user->pendidikan_terakhir ?? '-',
            'program_studi' => $user->program_studi ?? '-',
        ];

        return view('mobile.dashboard', compact(
            'kehadiranPercent',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'totalBasis',
            'todayPresensi',
            'todaySchedules',
            'userInfo'
        ));
    }

    public function presensi()
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Cek apakah sudah presensi hari ini
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Cek apakah hari ini libur
        $isHoliday = \App\Models\Holiday::where('date', $today)
            ->where('is_active', true)
            ->exists();

        // Ambil pengaturan waktu berdasarkan hari_kbm madrasah user
        $timeRanges = null;
        if ($user->madrasah && $user->madrasah->hari_kbm) {
            $timeRanges = $this->getPresensiTimeRanges($user->madrasah->hari_kbm, $today);
        }

        return view('mobile.presensi', compact('presensiHariIni', 'isHoliday', 'timeRanges'));
    }

    public function jadwal()
    {
        $user = Auth::user();

        // Group jadwal by day
        $schedules = TeachingSchedule::with(['school'])
            ->where('teacher_id', $user->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return view('mobile.jadwal', compact('schedules'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('mobile.profile', compact('user'));
    }

    public function ubahAkun()
    {
        $user = Auth::user();
        return view('mobile.ubah-akun', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        // Update only the fields that were provided
        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('phone')) {
            $updateData['no_hp'] = $request->phone;
        }

        if (!empty($updateData)) {
            $user->update($updateData);

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui.'
                ]);
            }

            return redirect()->route('ubah-akun')->with('success', 'Profil berhasil diperbarui.');
        }

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada perubahan yang dilakukan.'
            ]);
        }

        return redirect()->route('ubah-akun')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }

    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = Auth::user();

                Log::info('updateAvatar called for user ' . $user->id . ', hasFile: ' . ($request->hasFile('avatar') ? 'yes' : 'no'));

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                Log::info('avatar info: originalName=' . $file->getClientOriginalName() . ', size=' . $file->getSize());

                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // Store new avatar
                $avatarPath = $file->store('avatars', 'public');

                if (!$avatarPath) {
                    Log::error('Failed to store avatar for user ' . $user->id);
                    return redirect()->route('mobile.profile')->with('error', 'Gagal menyimpan foto.');
                }

                // Verify file exists
                if (!Storage::disk('public')->exists($avatarPath)) {
                    Log::error('Stored avatar not found on disk for user ' . $user->id . ': ' . $avatarPath);
                    return redirect()->route('mobile.profile')->with('error', 'Terjadi kesalahan saat menyimpan file.');
                }

                $user->update(['avatar' => $avatarPath]);
            }

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui.'
                ]);
            }

            return redirect()->route('ubah-akun')->with('success', 'Foto profil berhasil diubah.');
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::warning('Avatar validation failed for user ' . (Auth::id() ?? 'guest') . ': ' . json_encode($e->errors()));
            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
                ]);
            }

            return redirect()->route('ubah-akun')->withErrors($e->errors());
            } catch (\Exception $e) {
                Log::error('Exception in updateAvatar for user ' . (Auth::id() ?? 'guest') . ': ' . $e->getMessage());
                // Return JSON response for AJAX requests
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terjadi kesalahan saat mengunggah foto.'
                    ]);
                }

                return redirect()->route('ubah-akun')->with('error', 'Terjadi kesalahan saat mengunggah foto.');
            }
    }



    public function laporan()
    {
        $user = Auth::user();
        // Add logic for laporan if needed
        return view('mobile.laporan', compact('user'));
    }

    public function teachingAttendances()
    {
        $user = Auth::user();

        // Similar restriction as desktop controller: force password change if required
        if ($user->role === 'tenaga_pendidik' && !$user->password_changed) {
            return redirect()->route('mobile.dashboard')->with('error', 'Anda harus mengubah password terlebih dahulu sebelum mengakses menu presensi mengajar.');
        }

        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $dayOfWeek = Carbon::parse($today)->locale('id')->dayName;

        $schedules = TeachingSchedule::with(['school', 'teacher'])
            ->where('teacher_id', $user->id)
            ->where('day', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        // Attach today's attendance if exists
        foreach ($schedules as $schedule) {
            $attendance = \App\Models\TeachingAttendance::where('teaching_schedule_id', $schedule->id)
                ->where('tanggal', $today)
                ->first();
            $schedule->attendance = $attendance;
        }

        return view('mobile.teaching-attendances', compact('schedules', 'today'));
    }

    public function laporanMengajar()
    {
        $user = Auth::user();

        // Riwayat presensi mengajar untuk bulan ini
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $history = \App\Models\TeachingAttendance::with(['teachingSchedule.school'])
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('mobile.laporan-mengajar', compact('history'));
    }

    public function izin(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();

        // If a specific izin type is requested, return the corresponding mobile form view
        $type = $request->query('type');
        switch ($type) {
            case 'tidak_masuk':
                return view('mobile.izin-tidak-masuk', compact('user'));
            case 'sakit':
                return view('mobile.izin-sakit', compact('user'));
            case 'terlambat':
                return view('mobile.izin-terlambat', compact('user'));
            case 'tugas_luar':
                return view('mobile.izin-tugas-luar', compact('user'));
            default:
                return view('mobile.izin', compact('user'));
        }
    }





    public function storeIzin(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:tidak_masuk,sakit,terlambat,tugas_luar',
                // optional file inputs - validate if present
                'file_izin' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120',
                'surat_izin' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120',
                'file_tugas' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120',
                // time fields
                'waktu_masuk' => 'nullable|date_format:H:i',
                'waktu_keluar' => 'nullable|date_format:H:i',
            ]);

            $user = Auth::user();
            $today = Carbon::now('Asia/Jakarta')->toDateString();

            // Prevent duplicate pending izin for the day
            $existingIzin = Presensi::where('user_id', $user->id)
                ->where('tanggal', $today)
                ->where('status', 'izin')
                ->where('status_izin', 'pending')
                ->first();

            if ($existingIzin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengajukan izin untuk hari ini.'
                ], 400);
            }

            // Check existing presensi for today
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->where('tanggal', $today)
                ->first();

            if ($existingPresensi && $existingPresensi->status !== 'alpha') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki catatan presensi untuk hari ini.'
                ], 400);
            }

            // Determine uploaded file (accept several input names)
            $file = $request->file('file_izin') ?? $request->file('surat_izin') ?? $request->file('file_tugas');
            $filePath = null;
            if ($file) {
                $fileName = time() . '_izin_' . ($request->type ?? 'unknown') . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('surat_izin', $fileName, 'public');
            }

            // Compose keterangan based on type
            $keterangan = '';
            $type = $request->input('type');
            if ($type === 'tidak_masuk') {
                $reason = $request->input('alasan') ?? $request->input('keterangan');
                $keterangan = 'Izin Tidak Masuk: ' . ($reason ?? '-');
            } elseif ($type === 'sakit') {
                $reason = $request->input('keterangan') ?? $request->input('alasan');
                $keterangan = 'Izin Sakit: ' . ($reason ?? '-');
            } elseif ($type === 'terlambat') {
                $reason = $request->input('alasan') ?? $request->input('keterangan');
                $keterangan = 'Izin Terlambat: ' . ($reason ?? '-');
                if ($request->waktu_masuk) {
                    $keterangan .= ' - Waktu Masuk: ' . $request->waktu_masuk;
                }
            } elseif ($type === 'tugas_luar') {
                $desc = $request->input('deskripsi_tugas') ?? $request->input('keterangan');
                $keterangan = 'Izin Tugas Luar: ' . ($desc ?? '-');
                if ($request->input('lokasi_tugas')) {
                    $keterangan .= ' - Lokasi: ' . $request->input('lokasi_tugas');
                }
                if ($request->waktu_keluar) {
                    $keterangan .= ' - Waktu Keluar: ' . $request->waktu_keluar;
                }
            }

            // Persist presensi record (update alpha or create new)
            if ($existingPresensi && $existingPresensi->status === 'alpha') {
                $existingPresensi->update([
                    'status' => 'izin',
                    'keterangan' => $keterangan,
                    'surat_izin_path' => $filePath,
                    'status_izin' => 'pending',
                    'approved_by' => null,
                ]);
                $presensi = $existingPresensi;
            } else {
                $presensi = Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $today,
                    'status' => 'izin',
                    'keterangan' => $keterangan,
                    'surat_izin_path' => $filePath,
                    'status_izin' => 'pending',
                    'approved_by' => null,
                    'status_kepegawaian_id' => $user->status_kepegawaian_id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil dikirim dan menunggu approval.',
                'data' => $presensi
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing izin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan izin. Silakan coba lagi.'
            ], 500);
        }
    }

    public function riwayatPresensi()
    {
        $user = Auth::user();

        // Get presensi history for the current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $presensiHistory = Presensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('mobile.riwayat-presensi', compact('presensiHistory'));
    }

    public function storePresensi(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
            'accuracy' => 'nullable|numeric|min:0|max:999.99',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric|min:0',
            'device_info' => 'nullable|string|max:255',
            'location_readings' => 'nullable|string',
        ]);

        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Deteksi fake location berdasarkan location_readings
        $isFakeLocation = false;
        $fakeLocationAnalysis = null;

        if ($request->location_readings) {
            try {
                $locationReadings = json_decode($request->location_readings, true);
                if (is_array($locationReadings) && count($locationReadings) >= 2) {
                    $reading1 = $locationReadings[0];
                    $reading2 = $locationReadings[1];

                    // Jika latitude dan longitude sama persis antara reading1 dan reading2, maka fake GPS
                    if ($reading1['latitude'] === $reading2['latitude'] && $reading1['longitude'] === $reading2['longitude']) {
                        $isFakeLocation = true;
                        $fakeLocationAnalysis = [
                            'reason' => 'Identical coordinates between reading1 and reading2',
                            'reading1' => $reading1,
                            'reading2' => $reading2,
                            'detected_at' => Carbon::now('Asia/Jakarta')->toISOString()
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Jika parsing gagal, lanjutkan tanpa deteksi
            }
        }

        // Check if today is a holiday
        $isHoliday = \App\Models\Holiday::isHoliday($today);
        if ($isHoliday) {
            $holiday = \App\Models\Holiday::getHoliday($today);
            return response()->json([
                'success' => false,
                'message' => 'Hari ini adalah hari libur: ' . $holiday['name']
            ], 400);
        }

        // Cek apakah sudah presensi hari ini
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Ambil data madrasah dari user yang sedang login
        $madrasah = $user->madrasah;
        $madrasahTambahan = $user->madrasahTambahan;

        // Validasi lokasi user berada di dalam poligon madrasah utama atau tambahan jika berlaku
        $isWithinPolygon = false;
        $validMadrasah = null;

        $madrasahsToCheck = [];
        if ($madrasah && $madrasah->polygon_koordinat) {
            $madrasahsToCheck[] = $madrasah;
        }
        if ($user->pemenuhan_beban_kerja_lain && $madrasahTambahan && $madrasahTambahan->polygon_koordinat) {
            $madrasahsToCheck[] = $madrasahTambahan;
        }

        // Jika madrasah utama mengaktifkan dual polygon, tambahkan polygon kedua
        if ($madrasah && $madrasah->enable_dual_polygon && $madrasah->polygon_koordinat_2) {
            $madrasahsToCheck[] = (object)['polygon_koordinat' => $madrasah->polygon_koordinat_2];
        }

        if (empty($madrasahsToCheck)) {
            return response()->json([
                'success' => false,
                'message' => 'Area presensi (poligon) untuk madrasah Anda belum diatur. Silakan hubungi administrator.'
            ], 400);
        }

        foreach ($madrasahsToCheck as $m) {
            try {
                $polygonGeometry = json_decode($m->polygon_koordinat, true);
                $polygon = $polygonGeometry['coordinates'][0];
                if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                    $isWithinPolygon = true;
                    $validMadrasah = $m;
                    break;
                }
            } catch (\Exception $e) {
                continue; // Skip invalid polygon
            }
        }

        if (!$isWithinPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area presensi yang telah ditentukan.'
            ], 400);
        }

        // Jika user memiliki pemenuhan beban kerja lain, lewati validasi waktu
        if ($user->pemenuhan_beban_kerja_lain) {
            $batasAwalMasuk = null;
            $batasAkhirMasuk = null;
            $batasPulang = null;
        } else {
            // Ambil pengaturan waktu berdasarkan hari_kbm madrasah yang valid
            $hariKbm = $validMadrasah ? $validMadrasah->hari_kbm : null;
            $timeRanges = $this->getPresensiTimeRanges($hariKbm, $today);
            $batasAwalMasuk = $timeRanges['masuk_start'];
            $batasAkhirMasuk = $timeRanges['masuk_end'];
            $batasPulang = $timeRanges['pulang_start'];
            // Adjust for special users
            if ($user->role === 'tenaga_pendidik' && !$user->pemenuhan_beban_kerja_lain) {
                $batasAkhirMasuk = '08:00';
            }
        }

        $now = Carbon::now('Asia/Jakarta')->format('H:i:s');

        if (!$presensi) {
            // Validasi batas awal presensi masuk
            if ($batasAwalMasuk && $now < $batasAwalMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum waktunya presensi masuk.'
                ], 400);
            }

            // Validasi batas akhir presensi masuk
            if ($batasAkhirMasuk && $now > $batasAkhirMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Waktu presensi masuk telah berakhir.'
                ], 400);
            }

            $waktuMasuk = $request->input('waktu_masuk') ?? $now;
            $keterangan = null;

            // Jika waktu presensi setelah 07:00, hitung keterlambatan
            if ($now > '07:00:00') {
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

            // Presensi masuk
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'waktu_masuk' => $waktuMasuk,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'is_fake_location' => $isFakeLocation,
                'fake_location_analysis' => $fakeLocationAnalysis,
                'accuracy' => $request->accuracy,
                'altitude' => $request->altitude,
                'speed' => $request->speed,
                'device_info' => $request->device_info,
                'location_readings' => $request->location_readings,
                'status' => 'hadir',
                'keterangan' => $keterangan,
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
            ]);

            $message = $keterangan === "tidak terlambat" ? 'Presensi masuk berhasil dicatat.' : "Presensi masuk berhasil dicatat dengan {$keterangan}.";

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $presensi
            ]);
        } else {
            if ($presensi->status === 'alpha') {
                // Update alpha to hadir, set waktu_masuk
                $waktuMasuk = $request->input('waktu_masuk') ?? $now;
                $keterangan = null;

                // Jika waktu presensi setelah 07:00, hitung keterlambatan
                if ($now > '07:00:00') {
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

                $presensi->update([
                    'status' => 'hadir',
                    'waktu_masuk' => $waktuMasuk,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'lokasi' => $request->lokasi,
                    'is_fake_location' => $isFakeLocation,
                    'fake_location_analysis' => $fakeLocationAnalysis,
                    'accuracy' => $request->accuracy,
                    'altitude' => $request->altitude,
                    'speed' => $request->speed,
                    'device_info' => $request->device_info,
                    'location_readings' => $request->location_readings,
                    'keterangan' => $keterangan,
                ]);

                $message = $keterangan === "tidak terlambat" ? 'Presensi masuk berhasil dicatat.' : "Presensi masuk berhasil dicatat dengan {$keterangan}.";

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $presensi
                ]);
            } else {
                // Validasi batas diperbolehkan presensi pulang
                if ($batasPulang && $now < $batasPulang) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum waktunya presensi pulang.'
                    ], 400);
                }

                // Validasi tanggal presensi keluar harus sama dengan tanggal presensi masuk
                $tanggalSekarang = Carbon::now('Asia/Jakarta')->toDateString();
                if ($tanggalSekarang !== $presensi->tanggal->toDateString()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi keluar harus dilakukan pada tanggal yang sama dengan presensi masuk.'
                    ], 400);
                }

                // Presensi keluar
                $presensi->update([
                    'waktu_keluar' => $now,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Presensi keluar berhasil dicatat.',
                    'data' => $presensi
                ]);
            }
        }
    }



    /**
     * Get presensi time ranges based on madrasah hari_kbm and current day.
     * @param string|null $hariKbm
     * @param string $today
     * @return array
     */
    private function getPresensiTimeRanges($hariKbm, $today)
    {
        $dayOfWeek = Carbon::parse($today)->dayOfWeek; // 0=Sunday, 5=Friday

        if ($hariKbm == '5') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 5) ? '14:00' : '14:30'; // Friday starts at 14:00
            $pulangEnd = '17:00';
        } elseif ($hariKbm == '6') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 6) ? '12:00' : '13:00'; // Saturday starts at 12:00, other days at 13:00
            $pulangEnd = '17:00';
        } else {
            // Default or fallback
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = '13:00';
            $pulangEnd = '17:00';
        }

        return [
            'masuk_start' => $masukStart,
            'masuk_end' => $masukEnd,
            'pulang_start' => $pulangStart,
            'pulang_end' => $pulangEnd,
        ];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta/2) * sin($lonDelta/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
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
