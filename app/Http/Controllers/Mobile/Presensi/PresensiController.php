<?php

namespace App\Http\Controllers\Mobile\Presensi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Holiday;
use App\Services\ApprovedIzinSyncService;
use App\Services\AttendanceObligationService;
use App\Services\AttendanceValidationService;
use App\Services\AttendanceWorkflowService;
use App\Services\FaceVerificationService;
use App\Services\ExternalTeachingPermissionService;
use App\Services\MobileAttendanceSettingsService;
use App\Services\PicketScheduleApprovalService;
use Barryvdh\DomPDF\Facade\Pdf;

class PresensiController extends \App\Http\Controllers\Controller
{
    public function __construct(
        private PicketScheduleApprovalService $picketScheduleApprovalService,
        private AttendanceObligationService $attendanceObligationService,
        private FaceVerificationService $faceVerificationService,
        private MobileAttendanceSettingsService $mobileAttendanceSettingsService,
        private AttendanceValidationService $attendanceValidationService,
        private AttendanceWorkflowService $attendanceWorkflowService,
    )
    {
        $this->middleware(['web', 'auth']);
    }

    // Presensi view (mobile)
    public function presensi(Request $request)
    {
        $user = Auth::user();

        // Allow all tenaga_pendidik to access presensi form; kepala madrasah will see madrasah-level monitoring data
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // Check if user has pending izin terlambat for today - block access to presensi menu
        if ($this->attendanceWorkflowService->findPendingLatePermit($user, Carbon::today('Asia/Jakarta'))) {
            return redirect()->back()->with('error', 'Izin terlambat Anda sedang menunggu persetujuan kepala sekolah. Presensi akan dapat dilakukan setelah izin disetujui.');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // If kepala madrasah, fetch madrasah-level presensi lists; otherwise, leave empty (non-kepala see personal presensi only)
        $presensis = collect();
        $belumPresensi = collect();
        $mapData = [];
        if ($user->ketugasan === 'kepala madrasah/sekolah') {
            // Get presensi data for the madrasah (including users with dual presensi)
            $presensis = Presensi::with(['user', 'statusKepegawaian', 'madrasah'])
                ->where('madrasah_id', $user->madrasah_id)
                ->whereDate('tanggal', $selectedDate)
                ->orderBy('waktu_masuk', 'desc')
                ->get();

            // Get users who haven't done presensi
            $belumPresensi = User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                    $q->whereDate('tanggal', $selectedDate);
                })
                ->get()
                ->reject(function ($teacher) use ($selectedDate) {
                    return ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $selectedDate)
                        || !$this->attendanceObligationService->hasAttendanceObligation($teacher, $selectedDate);
                })
                ->values();

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
        $isHoliday = Holiday::isHoliday($dateString);
        $holiday = $isHoliday ? Holiday::getHoliday($dateString) : null;
        $approvedPicketSubmission = $this->picketScheduleApprovalService->approvedSubmissionForDate($user, $selectedDate);

        ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate($user, $selectedDate);

        // Presensi of the current user for the selected date (all madrasahs for dual presensi)
        // Only get actual presensi records (status = 'hadir'), not izin records
        $presensiHariIni = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $selectedDate)
            ->where('status', 'hadir')
            ->get();

        $approvedBlockingIzin = $this->attendanceWorkflowService->findApprovedBlockingIzin($user, $selectedDate);

        // For penjaga sekolah, if no presensi for today, show the last presensi to display status
        if ($user->ketugasan === 'penjaga sekolah' && $presensiHariIni->isEmpty()) {
            $lastPresensi = Presensi::with('madrasah')
                ->where('user_id', $user->id)
                ->where('status', 'hadir')
                ->orderBy('tanggal', 'desc')
                ->first();
            if ($lastPresensi) {
                $presensiHariIni = collect([$lastPresensi]);
            }
        }

        $faceVerificationState = $this->mobileAttendanceSettingsService->runtimeStateForUser($user);
        if ($faceVerificationState['requires_face_scan']) {
            $faceVerificationState = array_merge(
                $faceVerificationState,
                $this->faceVerificationService->requirementState($user)
            );
        } else {
            $faceVerificationState['required'] = false;
            $faceVerificationState['enrolled'] = true;
            $faceVerificationState['message'] = 'Presensi mobile saat ini menggunakan selfie.';
        }


        $timeRanges = null;
        if ($user->madrasah) {
            $timeRanges = [
                'masuk_start' => substr($this->attendanceWorkflowService->resolveMasukStart($user->madrasah), 0, 5),
                'masuk_end' => null,
                'pulang_start' => substr($this->attendanceWorkflowService->resolvePulangStart($user->madrasah, $selectedDate), 0, 5),
                'pulang_end' => substr($this->attendanceWorkflowService->resolveEndOfDayCutoff($user->madrasah, '22:00:00'), 0, 5),
            ];
        }

        return view('mobile.presensi', compact('presensis', 'belumPresensi', 'selectedDate', 'isHoliday', 'holiday', 'approvedPicketSubmission', 'presensiHariIni', 'approvedBlockingIzin', 'timeRanges', 'mapData', 'user', 'faceVerificationState'));
    }

    // Store presensi (mobile)
    public function storePresensi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'presensi_mode' => 'nullable|in:masuk,keluar',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
            'accuracy' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'device_info' => 'nullable|string',
            'location_readings' => 'nullable|string',
            'selfie_data' => 'required|string|min:100',

        ]);

        $verificationMode = $this->mobileAttendanceSettingsService->currentMode();

        if ($verificationMode === MobileAttendanceSettingsService::MODE_FACE_SCAN) {
            $request->validate([
                'face_descriptor' => 'required|array|min:32',
                'liveness_score' => 'required|numeric|min:0|max:1',
                'liveness_challenges' => 'required|array|min:1',
            ]);
        }

        if (!$this->isValidBase64Image($request->selfie_data)) {
            return response()->json([
                'success' => false,
                'message' => $verificationMode === MobileAttendanceSettingsService::MODE_FACE_SCAN
                    ? 'Foto hasil scan wajah tidak valid. Silakan ulangi scan wajah.'
                    : 'Foto selfie tidak valid. Silakan ambil ulang selfie.'
            ], 400);
        }

        $tanggal = Carbon::today()->toDateString();
        $now = Carbon::now('Asia/Jakarta');
    // default flag to mark early checkout; will be set later if checkout-before-pulang_start
    $isPulangAwal = false;

        ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate($user, $tanggal);

        $blockedDayReason = $this->attendanceWorkflowService->blockedAttendanceDayReason($user, $tanggal);
        if ($blockedDayReason) {
            return response()->json([
                'success' => false,
                'message' => "Presensi tidak dapat dilakukan pada {$blockedDayReason}."
            ], 400);
        }

        $approvedBlockingIzin = $this->attendanceWorkflowService->findApprovedBlockingIzin($user, $tanggal);
        if ($approvedBlockingIzin) {
            $izinLabel = ucfirst(str_replace('_', ' ', $approvedBlockingIzin->type));
            $periodLabel = $approvedBlockingIzin->tanggal_selesai
                ? ' untuk periode ' . $approvedBlockingIzin->tanggal->format('d/m/Y') . ' - ' . $approvedBlockingIzin->tanggal_selesai->format('d/m/Y')
                : '';

            return response()->json([
                'success' => false,
                'message' => "{$izinLabel} Anda sudah disetujui{$periodLabel}. Hari ini otomatis tercatat sebagai izin."
            ], 400);
        }

        // Special handling for penjaga sekolah - no time restrictions
        if ($user->ketugasan !== 'penjaga sekolah') {
            $endOfDayCutoff = $this->attendanceWorkflowService->resolveEndOfDayCutoff($user->madrasah);

            // Check if time is after endOfDayCutoff - mark as alpha (only for non-penjaga sekolah)
            if ($now->format('H:i:s') > $endOfDayCutoff) {
                if (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($user, $tanggal)) {
                    ExternalTeachingPermissionService::createOrUpdateNoPresenceRecord($user, $tanggal);

                    return response()->json([
                        'success' => false,
                        'message' => 'Hari ini Anda tercatat mengajar di sekolah lain dan tidak dihitung alpha.'
                    ], 400);
                }

                // Check if user already has presensi for today
                $existingPresensi = Presensi::where('user_id', $user->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();

                if (!$existingPresensi) {
                    // Create alpha record without saving selfie
                    Presensi::create($this->filterPresensiAttributes([
                        'user_id' => $user->id,
                        'tanggal' => $tanggal,
                        'status' => 'alpha',
                        'keterangan' => 'Tidak masuk',
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'lokasi' => $request->lokasi,
                        'accuracy' => $request->accuracy,
                        'altitude' => $request->altitude,
                        'speed' => $request->speed,
                        'device_info' => $request->device_info,
                        'location_readings' => $request->location_readings,
                        'selfie_masuk_path' => null, // Don't save selfie for failed presensi
                        'status_kepegawaian_id' => $user->status_kepegawaian_id,
                    ]));

                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi setelah pukul 22:00 otomatis dicatat sebagai tidak masuk.'
                    ], 400);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi hari ini sudah dicatat.'
                    ], 400);
                }
            }
        }

        // Determine madrasah based on location for users with additional madrasah
        $determinedMadrasahId = null;
        $isWithinAnyPolygon = false;

        // If user has pemenuhan_beban_kerja_lain and madrasah_id_tambahan, check additional madrasah first
        if ($user->pemenuhan_beban_kerja_lain && $user->madrasah_id_tambahan) {
            $additionalMadrasah = $user->madrasahTambahan;
            $polygonsToCheck = [];
            if ($additionalMadrasah && $additionalMadrasah->polygon_koordinat) {
                $polygonsToCheck[] = $additionalMadrasah->polygon_koordinat;
            }
            if ($additionalMadrasah && $additionalMadrasah->enable_dual_polygon && $additionalMadrasah->polygon_koordinat_2) {
                $polygonsToCheck[] = $additionalMadrasah->polygon_koordinat_2;
            }

            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinAnyPolygon = true;
                            $determinedMadrasahId = $user->madrasah_id_tambahan;
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        // If not within additional madrasah (or no additional), check main madrasah
        if (!$isWithinAnyPolygon) {
            $mainMadrasah = $user->madrasah;
            $polygonsToCheck = [];
            if ($mainMadrasah && $mainMadrasah->polygon_koordinat) {
                $polygonsToCheck[] = $mainMadrasah->polygon_koordinat;
            }
            if ($mainMadrasah && $mainMadrasah->enable_dual_polygon && $mainMadrasah->polygon_koordinat_2) {
                $polygonsToCheck[] = $mainMadrasah->polygon_koordinat_2;
            }

            foreach ($polygonsToCheck as $polygonJson) {
                try {
                    $polygonGeometry = json_decode($polygonJson, true);
                    if (isset($polygonGeometry['coordinates'][0])) {
                        $polygon = $polygonGeometry['coordinates'][0];
                        if ($this->isPointInPolygon([$request->longitude, $request->latitude], $polygon)) {
                            $isWithinAnyPolygon = true;
                            $determinedMadrasahId = $user->madrasah_id;
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        // If not within any polygon, reject presensi
        if (!$isWithinAnyPolygon) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Anda berada di luar area sekolah yang telah ditentukan. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.'
            ], 400);
        }

        // Check existing presensi - different logic for penjaga sekolah
        if ($user->ketugasan === 'penjaga sekolah') {
            // For penjaga sekolah: check for any open presensi (masuk without keluar) regardless of date
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->orderBy('tanggal', 'desc')
                ->first();
        } else {
            // For other users: check presensi for today only
            $existingPresensi = Presensi::where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->first();
        }

        // Check if user has pending izin terlambat for today - block presensi if pending
        if ($this->attendanceWorkflowService->findPendingLatePermit($user, $tanggal)) {
            return response()->json([
                'success' => false,
                'message' => 'Izin terlambat Anda sedang menunggu persetujuan kepala sekolah. Presensi akan dapat dilakukan setelah izin disetujui.'
            ], 400);
        }

        $requestedMode = $request->input('presensi_mode');

        if ($user->ketugasan !== 'penjaga sekolah' && !in_array($requestedMode, ['masuk', 'keluar'], true)) {
            if ($existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar) {
                return response()->json([
                    'success' => true,
                    'message' => 'Presensi masuk sudah tercatat. Muat ulang halaman terlebih dahulu sebelum melakukan presensi keluar.',
                    'presensi' => $existingPresensi,
                    'madrasah_name' => $existingPresensi->madrasah?->name ?? $user->madrasah?->name ?? 'Madrasah',
                    'waktu_masuk' => $existingPresensi->waktu_masuk ? $existingPresensi->waktu_masuk->format('H:i') : now()->format('H:i')
                ]);
            }

            $requestedMode = 'masuk';
        }

        if ($requestedMode === 'masuk') {
            if ($existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar) {
                return response()->json([
                    'success' => true,
                    'message' => 'Presensi masuk sudah tercatat.',
                    'presensi' => $existingPresensi,
                    'madrasah_name' => $existingPresensi->madrasah?->name ?? $user->madrasah?->name ?? 'Madrasah',
                    'waktu_masuk' => $existingPresensi->waktu_masuk ? $existingPresensi->waktu_masuk->format('H:i') : now()->format('H:i')
                ]);
            }

            if ($existingPresensi && $existingPresensi->waktu_masuk && $existingPresensi->waktu_keluar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi hari ini sudah lengkap.'
                ], 400);
            }
        }

        if ($requestedMode === 'keluar') {
            if (!$existingPresensi || !$existingPresensi->waktu_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi keluar belum dapat dilakukan karena presensi masuk belum tercatat.'
                ], 400);
            }

            if ($existingPresensi->waktu_keluar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi keluar hari ini sudah dicatat.'
                ], 400);
            }
        }



        // Determine presensi type with additional checks
        if ($requestedMode === 'masuk') {
            $isPresensiMasuk = true;
            $isPresensiKeluar = false;
        } elseif ($requestedMode === 'keluar') {
            $isPresensiMasuk = false;
            $isPresensiKeluar = true;
        } elseif ($user->ketugasan === 'penjaga sekolah') {
            // For penjaga sekolah: if there's an open presensi, this is keluar; otherwise masuk
            $isPresensiMasuk = !$existingPresensi;
            $isPresensiKeluar = $existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;
        } else {
            // For other users: standard logic
            $isPresensiMasuk = !$existingPresensi || (!$existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar);
            $isPresensiKeluar = $existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar;
        }

        // Check if entry presensi is attempted before allowed time
        if ($isPresensiMasuk) {
            if ($user->ketugasan === 'penjaga sekolah') {
                // Penjaga sekolah: no time restrictions for presensi masuk
                // Can presensi anytime 24 hours
            } elseif ($user->pemenuhan_beban_kerja_lain) {
                $minTimeMasuk = $this->attendanceWorkflowService->resolveMasukStart($user->madrasah);
                if ($now->format('H:i:s') < $minTimeMasuk) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi masuk belum dapat dilakukan. Waktu presensi masuk dimulai pukul ' . substr($minTimeMasuk, 0, 5) . '.'
                    ], 400);
                }
            } else {
                $minTimeMasuk = $this->attendanceWorkflowService->resolveMasukStart($user->madrasah);
                if ($now->format('H:i:s') < $minTimeMasuk) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Presensi masuk belum dapat dilakukan. Waktu presensi masuk dimulai pukul ' . substr($minTimeMasuk, 0, 5) . '.'
                    ], 400);
                }
            }
        }

        // Validate location for fake GPS detection
        $locationValidation = $this->validateLocationForFakeGPS($request, $user, $isPresensiMasuk);

        if ($locationValidation['is_fake']) {
            return response()->json([
                'success' => false,
                'message' => $locationValidation['message']
            ], 400);
        }

        $faceVerification = [
            'success' => true,
            'face_id_used' => null,
            'similarity' => null,
            'liveness_score' => null,
            'challenges' => null,
            'notes' => 'selfie_only',
            'verified' => false,
        ];

        if ($verificationMode === MobileAttendanceSettingsService::MODE_FACE_SCAN) {
            $faceVerification = $this->faceVerificationService->verifyForAttendance(
                $user,
                $request->input('face_descriptor'),
                $request->input('liveness_score'),
                $request->input('liveness_challenges', []),
                true,
            );

            if (!$faceVerification['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $faceVerification['message'],
                    'notes' => $faceVerification['notes'] ?? null,
                    'similarity' => $faceVerification['similarity'] ?? null,
                ], 422);
            }

            $faceVerification['verified'] = true;
        }

        $selfiePath = $this->processAndSaveSelfie($request->selfie_data, $user->id, $tanggal, $isPresensiMasuk);

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

        // Determine if this is presensi masuk or keluar
        if ($isPresensiMasuk) {
            // Presensi Masuk
            $status = 'hadir';
            $waktuMasuk = $now;
            $waktuKeluar = null;

            // For penjaga sekolah, use current date for tanggal
            if ($user->ketugasan === 'penjaga sekolah') {
                $tanggal = Carbon::today()->toDateString();
            }

            $keterangan = $this->attendanceWorkflowService->determineMasukKeterangan($user, $now);

            // Special handling for early presensi (between 01:00 and 00:01)
            if ($now->format('H:i:s') >= '01:00:00' && $now->format('H:i:s') < '00:01:00') {
                $keterangan = "Presensi dini";
            }



            // Create new presensi record
            $presensi = Presensi::create($this->filterPresensiAttributes([
                'user_id' => $user->id,
                'madrasah_id' => $determinedMadrasahId,
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
                'selfie_masuk_path' => $selfiePath,
                'status_kepegawaian_id' => $user->status_kepegawaian_id,
                'is_fake_location' => $locationValidation['is_fake'] ?? false,
                'fake_location_analysis' => $locationValidation['analysis'] ?? null,
                'face_id_used' => $faceVerification['face_id_used'] ?? null,
                'face_similarity_score' => $faceVerification['similarity'] ?? null,
                'liveness_score' => $faceVerification['liveness_score'] ?? null,
                'liveness_challenges' => $faceVerification['challenges'] ?? null,
                'face_verified' => $faceVerification['verified'] ?? false,
                'face_verification_notes' => $faceVerification['notes'] ?? null,
            ]));

            $message = 'Presensi masuk berhasil dicatat!';

        } elseif ($isPresensiKeluar) {
            // Check if it's time to go home (after pulang_start time)
            if ($user->ketugasan === 'penjaga sekolah') {
                // Penjaga sekolah: no time restrictions for presensi keluar
                // Can presensi anytime 24 hours
            } elseif (!$user->pemenuhan_beban_kerja_lain) {
                $isPulangAwal = $this->attendanceWorkflowService->isEarlyCheckout($user, $user->madrasah, $now);
            }
            // User with beban kerja lain: no time restriction for presensi keluar

            // Presensi Keluar - update existing record
            // For penjaga sekolah, update the existing open presensi record
            // If this checkout is before official pulang_start, append 'pulang awal' to keterangan
            $newKeterangan = $this->attendanceWorkflowService->appendCheckoutNote(
                $existingPresensi->keterangan,
                !empty($isPulangAwal) && $isPulangAwal
            );

            $existingPresensi->update($this->filterPresensiAttributes([
                'waktu_keluar' => $now,
                'latitude_keluar' => $request->latitude,
                'longitude_keluar' => $request->longitude,
                'lokasi_keluar' => $request->lokasi,
                'accuracy_keluar' => $request->accuracy,
                'altitude_keluar' => $request->altitude,
                'speed_keluar' => $request->speed,
                'device_info_keluar' => $request->device_info,
                'location_readings_keluar' => $request->location_readings,
                'selfie_keluar_path' => $selfiePath,
                'keterangan' => $newKeterangan,
                'is_fake_location_keluar' => $locationValidation['is_fake'] ?? false,
                'fake_location_analysis_keluar' => $locationValidation['analysis'] ?? null,
                'face_id_used' => $faceVerification['face_id_used'] ?? null,
                'face_similarity_score' => $faceVerification['similarity'] ?? null,
                'liveness_score' => $faceVerification['liveness_score'] ?? null,
                'liveness_challenges' => $faceVerification['challenges'] ?? null,
                'face_verified' => $faceVerification['verified'] ?? false,
                'face_verification_notes' => $faceVerification['notes'] ?? null,
            ]));

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
                'presensi' => $presensi,
                'madrasah_name' => $user->madrasah?->name ?? 'Madrasah',
                'waktu_masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : now()->format('H:i')
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

        $today = Carbon::today('Asia/Jakarta');
        $selectedMonth = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', $request->month, 'Asia/Jakarta')->startOfMonth()
            : $today->copy()->startOfMonth();
        $selectedWeek = $request->filled('week') && preg_match('/^\d{4}-W\d{2}$/', $request->week)
            ? Carbon::now('Asia/Jakarta')->setISODate(
                (int) substr($request->week, 0, 4),
                (int) substr($request->week, 6, 2)
            )->startOfWeek(Carbon::MONDAY)
            : $today->copy()->startOfWeek(Carbon::MONDAY);

        // Fetch presensi for the selected month for the authenticated user
        $presensiRecords = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->get()
            ->map(function ($item) {
                $item->model_type = 'presensi';
                return $item;
            });

        // Fetch izin records for the selected month for the authenticated user
        $izinRecords = \App\Models\Izin::with('user')
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->get()
            ->map(function ($item) {
                $item->model_type = 'izin';
                return $item;
            });

        // Combine presensi and izin records, sort by tanggal desc
        $presensiHistory = $presensiRecords->concat($izinRecords)->sortByDesc('tanggal');

        $weeklySummary = $this->buildAttendanceSummary(
            $user->id,
            $user->madrasah?->hari_kbm,
            $selectedWeek->copy()->startOfWeek(Carbon::MONDAY),
            $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY),
            $today
        );

        $monthlySummary = $this->buildAttendanceSummary(
            $user->id,
            $user->madrasah?->hari_kbm,
            $selectedMonth->copy()->startOfMonth(),
            $selectedMonth->copy()->endOfMonth(),
            $today
        );

        return view('mobile.riwayat-presensi', [
            'presensiHistory' => $presensiHistory,
            'selectedWeekValue' => $selectedWeek->format('o-\WW'),
            'selectedMonthValue' => $selectedMonth->format('Y-m'),
            'selectedMonthLabel' => $selectedMonth->translatedFormat('F Y'),
            'weeklySummary' => $weeklySummary,
            'monthlySummary' => $monthlySummary,
        ]);
    }

    // Riwayat presensi alpha
    public function riwayatPresensiAlpha(Request $request)
    {
        $user = Auth::user();

        // only tenaga_pendidik may access mobile pages
        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        // allow optional month navigation via ?month=2025-10-01
        $selectedMonth = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();

        // Fetch only alpha presensi for the selected month for the authenticated user
        $presensiHistory = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->where('status', 'alpha')
            ->whereYear('tanggal', $selectedMonth->year)
            ->whereMonth('tanggal', $selectedMonth->month)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->reject(fn (Presensi $presensi) => $this->attendanceObligationService->isExcludedByApprovedPicketPeriod($user, $presensi->tanggal))
            ->values();

        return view('mobile.riwayat-presensi-alpha', compact('presensiHistory'));
    }

    public function downloadRekapPresensi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'tenaga_pendidik') {
            abort(403, 'Unauthorized.');
        }

        $type = $request->input('type', 'monthly');
        $today = Carbon::today('Asia/Jakarta');

        if ($type === 'weekly') {
            $selectedWeek = $request->filled('week') && preg_match('/^\d{4}-W\d{2}$/', $request->week)
                ? Carbon::now('Asia/Jakarta')->setISODate(
                    (int) substr($request->week, 0, 4),
                    (int) substr($request->week, 6, 2)
                )->startOfWeek(Carbon::MONDAY)
                : $today->copy()->startOfWeek(Carbon::MONDAY);

            $startDate = $selectedWeek->copy()->startOfWeek(Carbon::MONDAY);
            $endDate = $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY);
            $summary = $this->buildAttendanceSummary($user->id, $user->madrasah?->hari_kbm, $startDate, $endDate, $today);
            $filename = 'rekap-presensi-mingguan-' . $user->id . '-' . $selectedWeek->format('o-\WW') . '.xlsx';
        } elseif ($type === 'all') {
            $firstPresensiDate = Presensi::where('user_id', $user->id)->orderBy('tanggal')->value('tanggal');
            $firstIzinDate = \App\Models\Izin::where('user_id', $user->id)->orderBy('tanggal')->value('tanggal');
            $candidateDates = collect([$firstPresensiDate, $firstIzinDate])->filter();

            $startDate = $candidateDates->isNotEmpty()
                ? Carbon::parse($candidateDates->min(), 'Asia/Jakarta')->startOfDay()
                : $today->copy()->startOfMonth();
            $endDate = $today->copy();
            $summary = $this->buildAttendanceSummary($user->id, $user->madrasah?->hari_kbm, $startDate, $endDate, $today);
            $filename = 'rekap-presensi-keseluruhan-' . $user->id . '.xlsx';
        } else {
            $selectedMonth = $request->filled('month')
                ? Carbon::createFromFormat('Y-m', $request->month, 'Asia/Jakarta')->startOfMonth()
                : $today->copy()->startOfMonth();

            $startDate = $selectedMonth->copy()->startOfMonth();
            $endDate = $selectedMonth->copy()->endOfMonth();
            $summary = $this->buildAttendanceSummary($user->id, $user->madrasah?->hari_kbm, $startDate, $endDate, $today);
            $filename = 'rekap-presensi-bulanan-' . $user->id . '-' . $selectedMonth->format('Y-m') . '.xlsx';
        }

        $effectiveEndDate = $endDate->copy()->min($today);

        $presensiRecords = Presensi::with('madrasah')
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->get()
            ->map(function ($item) {
                $item->model_type = 'presensi';
                return $item;
            });

        $izinRecords = \App\Models\Izin::query()
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->get()
            ->map(function ($item) {
                $item->model_type = 'izin';
                return $item;
            });

        $records = $presensiRecords
            ->concat($izinRecords)
            ->sortBy([
                ['tanggal', 'asc'],
                ['model_type', 'asc'],
            ])
            ->values();

        $filename = str_replace('.xlsx', '.pdf', $filename);

        $pdf = Pdf::loadView('pdf.mobile-presensi-rekap', [
            'user' => $user,
            'type' => $type,
            'summary' => $summary,
            'records' => $records,
            'startDate' => $startDate,
            'endDate' => $effectiveEndDate,
        ])->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Validates location readings to catch impossible location jumps.
     * Stable readings in the same place are considered valid.
     *
     * @param string $locationReadingsJson JSON string containing location readings array
     * @return array ['valid' => bool, 'message' => string]
     */
    private function validateLocationConsistency(string $locationReadingsJson): array
    {
        return $this->attendanceValidationService->validateLocationConsistency($locationReadingsJson);
    }

    /**
     * Comprehensive fake GPS detection validation
     * @param Request $request
     * @param User $user
     * @param bool $isPresensiMasuk
     * @return array ['is_fake' => bool, 'message' => string, 'analysis' => array]
     */
    private function validateLocationForFakeGPS(Request $request, $user, bool $isPresensiMasuk): array
    {
        return $this->attendanceValidationService->validateLocationForFakeGps([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'location_readings' => $request->location_readings,
            'device_info' => $request->device_info,
        ], $user, $isPresensiMasuk);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float Distance in kilometers
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Validates if the given string is a valid base64 encoded image
     * @param string $data Base64 string to validate
     * @return bool True if valid base64 image
     */
    private function isValidBase64Image(string $data): bool
    {
        return $this->attendanceValidationService->isValidBase64Image($data);
    }

    /**
     * Process and save selfie image
     * @param string $selfieData Base64 image data
     * @param int $userId User ID
     * @param string $tanggal Date string
     * @param bool $isMasuk Whether this is presensi masuk
     * @return string Path to saved image
     */
    private function processAndSaveSelfie(string $selfieData, int $userId, string $tanggal, bool $isMasuk): string
    {
        return $this->attendanceValidationService->processAndSaveSelfie($selfieData, $userId, $tanggal, $isMasuk);
    }

    private function filterPresensiAttributes(array $attributes): array
    {
        return $this->attendanceValidationService->filterPresensiAttributes($attributes);
    }

    private function getPresensiTableColumns(): array
    {
        static $columns = null;

        if ($columns !== null) {
            return $columns;
        }

        try {
            $columns = array_flip(Schema::getColumnListing('presensis'));
        } catch (\Throwable $e) {
            Log::warning('Gagal membaca struktur tabel presensis.', [
                'message' => $e->getMessage(),
            ]);
            $columns = [];
        }

        return $columns;
    }

    /**
     * Checks if a point is inside a polygon using the ray-casting algorithm.
     * @param array $point The point to check, in [longitude, latitude] format.
     * @param array $polygon An array of polygon vertices, each in [longitude, latitude] format.
     * @return bool True if the point is inside the polygon, false otherwise.
     */
    private function isPointInPolygon(array $point, array $polygon): bool
    {
        return $this->attendanceValidationService->isPointInPolygon($point, $polygon);
    }

    private function buildAttendanceSummary(int $userId, ?string $hariKbm, Carbon $startDate, Carbon $endDate, Carbon $today): array
    {
        $effectiveEndDate = $endDate->copy()->min($today);

        if ($effectiveEndDate->lt($startDate)) {
            return [
                'periode_label' => $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y'),
                'total_hari_kerja' => 0,
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_belum_hadir' => 0,
                'persentase_kehadiran' => 0,
                'details' => collect(),
            ];
        }

        $summaryUser = User::with('madrasah')->find($userId);
        if ($summaryUser) {
            ApprovedIzinSyncService::syncApprovedIzinPresensiInRange($summaryUser, $startDate, $effectiveEndDate);
        }

        $presensiByDate = Presensi::query()
            ->where('user_id', $userId)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(fn ($item) => $item->tanggal->toDateString());

        $details = collect();
        $totalHariKerja = 0;
        $totalHadir = 0;
        $totalIzinApproved = 0;

        foreach (CarbonPeriod::create($startDate, $effectiveEndDate) as $date) {
            $obligationStatus = $summaryUser
                ? $this->attendanceObligationService->statusForDate($summaryUser, $date)
                : ($this->isWorkingDay($date, $hariKbm)
                    ? AttendanceObligationService::STATUS_REQUIRED
                    : AttendanceObligationService::STATUS_OFF);

            if ($obligationStatus === AttendanceObligationService::STATUS_OFF) {
                continue;
            }

            $records = $presensiByDate->get($date->toDateString(), collect());
            $hadirRecords = $records->whereIn('status', ['hadir', 'terlambat']);
            $izinRecords = $records->where('status', 'izin');
            $izinApprovedRecords = $izinRecords->where('status_izin', 'approved');
            $alphaRecords = $records->where('status', 'alpha');

            $isHadir = $hadirRecords->isNotEmpty();
            $externalTeachingIzin = (!$isHadir && $summaryUser)
                ? ExternalTeachingPermissionService::approvedRequestForDate($summaryUser, $date)
                : null;
            $isIzinApproved = !$isHadir && $izinApprovedRecords->isNotEmpty();
            $statusLabel = $isHadir
                ? 'Hadir'
                : (($isIzinApproved || $externalTeachingIzin)
                    ? 'Izin Disetujui'
                    : ($izinRecords->isNotEmpty() ? 'Izin Belum Disetujui' : ($alphaRecords->isNotEmpty() ? 'Alpha' : 'Belum Presensi')));

            if ($obligationStatus === AttendanceObligationService::STATUS_NOT_REQUIRED_PICKET_PERIOD) {
                $details->push([
                    'tanggal' => $date->copy(),
                    'hari' => ucfirst($date->locale('id')->dayName),
                    'status' => 'Di luar jadwal piket',
                    'keterangan' => AttendanceObligationService::NOTE_NOT_REQUIRED_PICKET_PERIOD,
                    'is_excluded' => true,
                ]);

                continue;
            }

            $details->push([
                'tanggal' => $date->copy(),
                'hari' => ucfirst($date->locale('id')->dayName),
                'status' => $statusLabel,
                'keterangan' => $externalTeachingIzin
                    ? ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI
                    : $records->pluck('keterangan')->filter()->implode(' | '),
                'is_excluded' => false,
            ]);

            $totalHariKerja++;
            if ($isHadir) {
                $totalHadir++;
            } elseif ($isIzinApproved || $externalTeachingIzin) {
                $totalIzinApproved++;
            }
        }

        $totalDasarPersentase = max($totalHariKerja - $totalIzinApproved, 0);

        return [
            'periode_label' => $startDate->translatedFormat('d M Y') . ' - ' . $effectiveEndDate->translatedFormat('d M Y'),
            'total_hari_kerja' => $totalHariKerja,
            'total_hadir' => $totalHadir,
            'total_izin' => $totalIzinApproved,
            'total_belum_hadir' => max($totalHariKerja - $totalHadir - $totalIzinApproved, 0),
            'persentase_kehadiran' => $totalDasarPersentase > 0 ? round(($totalHadir / $totalDasarPersentase) * 100, 1) : 0,
            'details' => $details,
        ];
    }

    private function isWorkingDay(Carbon $date, ?string $hariKbm): bool
    {
        if ($date->isSunday() || Holiday::isHoliday($date->toDateString())) {
            return false;
        }

        if ((string) $hariKbm === '5' && $date->isSaturday()) {
            return false;
        }

        return true;
    }

}
