<?php

namespace App\Services;

use App\Models\Madrasah;
use App\Models\Presensi;
use App\Models\RegisteredAttendanceDevice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SchoolKioskAttendanceService
{
    public function __construct(
        private FaceVerificationService $faceVerificationService,
        private MobileAttendanceSettingsService $mobileAttendanceSettingsService,
        private AttendanceValidationService $attendanceValidationService,
        private AttendanceWorkflowService $attendanceWorkflowService,
    ) {
    }

    public function submit(
        User $operator,
        User $teacher,
        RegisteredAttendanceDevice $device,
        array $payload,
        ?string $requestIp = null,
    ): array {
        $school = $device->madrasah;

        if (!$school) {
            throw ValidationException::withMessages([
                'attendance' => 'Perangkat kiosk belum terhubung ke madrasah.',
            ]);
        }

        if (!$this->teacherCanAttendAtSchool($teacher, $school)) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Guru ini tidak terhubung dengan sekolah perangkat kiosk.',
            ]);
        }

        $mode = $this->attendanceWorkflowService->normalizeRequestedMode(
            $payload['presensi_mode'] ?? null,
            $this->findExistingPresensi($teacher, $school)
        );
        $today = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        ApprovedIzinSyncService::syncApprovedIzinPresensiForUserDate($teacher, $today);

        $this->ensureDayAllowed($teacher, $today);
        $this->ensureLocationInsideSchool($school, (float) $payload['latitude'], (float) $payload['longitude']);

        $existingPresensi = $this->findExistingPresensi($teacher, $school);
        $this->ensureNoPendingLatePermit($teacher, $today);
        $this->ensureModeAllowed($mode, $existingPresensi);
        $this->ensureAttendanceTimeAllowed($teacher, $school, $mode, $now, $existingPresensi, $payload);

        $locationValidation = $this->validateLocationForFakeGps($payload, $teacher, $mode === 'masuk', $existingPresensi);
        if ($locationValidation['is_fake']) {
            throw ValidationException::withMessages([
                'attendance' => $locationValidation['message'],
            ]);
        }

        $verificationMode = $this->mobileAttendanceSettingsService->currentMode();
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
            $externalVerification = $this->normalizeExternalFaceVerification(
                $payload['external_face_verification'] ?? null,
                $teacher
            );

            if ($externalVerification !== null) {
                $faceVerification = $externalVerification;
            } else {
                $faceVerification = $this->faceVerificationService->verifyForAttendance(
                    $teacher,
                    $payload['face_descriptor'] ?? null,
                    $payload['liveness_score'] ?? null,
                    $payload['liveness_challenges'] ?? [],
                    true,
                );
            }

            if (!$faceVerification['success']) {
                throw ValidationException::withMessages([
                    'attendance' => $faceVerification['message'],
                ]);
            }

            $faceVerification['verified'] = true;
        }

        $selfiePath = $this->processAndSaveSelfie(
            $payload['selfie_data'],
            $teacher->id,
            $today->toDateString(),
            $mode === 'masuk'
        );

        if ($mode === 'masuk') {
            $presensi = $this->createPresensiMasuk(
                $teacher,
                $operator,
                $school,
                $device,
                $payload,
                $requestIp,
                $now,
                $selfiePath,
                $locationValidation,
                $faceVerification
            );

            return [
                'success' => true,
                'message' => 'Presensi masuk berhasil dicatat.',
                'presensi' => $presensi->fresh('madrasah'),
                'mode' => 'masuk',
            ];
        }

        $presensi = $this->updatePresensiKeluar(
            $existingPresensi,
            $teacher,
            $operator,
            $school,
            $device,
            $payload,
            $requestIp,
            $now,
            $selfiePath,
            $locationValidation,
            $faceVerification
        );

        return [
            'success' => true,
            'message' => 'Presensi keluar berhasil dicatat.',
            'presensi' => $presensi->fresh('madrasah'),
            'mode' => 'keluar',
        ];
    }

    private function createPresensiMasuk(
        User $teacher,
        User $operator,
        Madrasah $school,
        RegisteredAttendanceDevice $device,
        array $payload,
        ?string $requestIp,
        Carbon $now,
        string $selfiePath,
        array $locationValidation,
        array $faceVerification,
    ): Presensi {
        $keterangan = $this->attendanceWorkflowService->determineMasukKeterangan($teacher, $now);

        return Presensi::create($this->filterPresensiAttributes([
            'user_id' => $teacher->id,
            'madrasah_id' => $school->id,
            'tanggal' => $now->toDateString(),
            'waktu_masuk' => $now,
            'status' => 'hadir',
            'keterangan' => $keterangan,
            'latitude' => $payload['latitude'],
            'longitude' => $payload['longitude'],
            'lokasi' => $payload['lokasi'] ?? null,
            'accuracy' => $payload['accuracy'] ?? null,
            'altitude' => $payload['altitude'] ?? null,
            'speed' => $payload['speed'] ?? null,
            'device_info' => $payload['device_info'] ?? null,
            'location_readings' => $payload['location_readings'] ?? null,
            'selfie_masuk_path' => $selfiePath,
            'status_kepegawaian_id' => $teacher->status_kepegawaian_id,
            'is_fake_location' => $locationValidation['is_fake'] ?? false,
            'fake_location_analysis' => $locationValidation['analysis'] ?? null,
            'face_id_used' => $faceVerification['face_id_used'] ?? null,
            'face_similarity_score' => $faceVerification['similarity'] ?? null,
            'liveness_score' => $faceVerification['liveness_score'] ?? null,
            'liveness_challenges' => $faceVerification['challenges'] ?? null,
            'face_verified' => $faceVerification['verified'] ?? false,
            'face_verification_notes' => $faceVerification['notes'] ?? null,
            'attendance_channel' => 'school_kiosk',
            'registered_device_id' => $device->id,
            'recorded_by_user_id' => $operator->id,
            'source_ip_address' => $requestIp,
        ]));
    }

    private function updatePresensiKeluar(
        Presensi $existingPresensi,
        User $teacher,
        User $operator,
        Madrasah $school,
        RegisteredAttendanceDevice $device,
        array $payload,
        ?string $requestIp,
        Carbon $now,
        string $selfiePath,
        array $locationValidation,
        array $faceVerification,
    ): Presensi {
        $newKeterangan = $this->attendanceWorkflowService->appendCheckoutNote(
            $existingPresensi->keterangan,
            $this->attendanceWorkflowService->isEarlyCheckout($teacher, $school, $now)
        );

        $existingPresensi->update($this->filterPresensiAttributes([
            'waktu_keluar' => $now,
            'latitude_keluar' => $payload['latitude'],
            'longitude_keluar' => $payload['longitude'],
            'lokasi_keluar' => $payload['lokasi'] ?? null,
            'accuracy_keluar' => $payload['accuracy'] ?? null,
            'altitude_keluar' => $payload['altitude'] ?? null,
            'speed_keluar' => $payload['speed'] ?? null,
            'device_info_keluar' => $payload['device_info'] ?? null,
            'location_readings_keluar' => $payload['location_readings'] ?? null,
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
            'attendance_channel' => 'school_kiosk',
            'registered_device_id' => $device->id,
            'recorded_by_user_id' => $operator->id,
            'source_ip_address' => $requestIp,
        ]));

        return $existingPresensi;
    }

    private function ensureModeAllowed(string $mode, ?Presensi $existingPresensi): void
    {
        if ($mode === 'masuk' && $existingPresensi && $existingPresensi->waktu_masuk && !$existingPresensi->waktu_keluar) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi masuk sudah tercatat. Lanjutkan presensi keluar.',
            ]);
        }

        if ($mode === 'masuk' && $existingPresensi && $existingPresensi->waktu_masuk && $existingPresensi->waktu_keluar) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi hari ini sudah lengkap.',
            ]);
        }

        if ($mode === 'keluar' && (!$existingPresensi || !$existingPresensi->waktu_masuk)) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi keluar belum dapat dilakukan karena presensi masuk belum tercatat.',
            ]);
        }

        if ($mode === 'keluar' && $existingPresensi && $existingPresensi->waktu_keluar) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi keluar hari ini sudah dicatat.',
            ]);
        }
    }

    private function ensureDayAllowed(User $teacher, Carbon $today): void
    {
        $reason = $this->attendanceWorkflowService->blockedAttendanceDayReason($teacher, $today);
        if ($reason) {
            throw ValidationException::withMessages([
                'attendance' => "Presensi tidak dapat dilakukan pada {$reason}.",
            ]);
        }

        $approvedBlockingIzin = $this->attendanceWorkflowService->findApprovedBlockingIzin($teacher, $today);
        if ($approvedBlockingIzin) {
            $label = ucfirst(str_replace('_', ' ', (string) $approvedBlockingIzin->type));
            throw ValidationException::withMessages([
                'attendance' => "{$label} guru ini sudah disetujui untuk hari ini.",
            ]);
        }
    }

    private function ensureNoPendingLatePermit(User $teacher, Carbon $today): void
    {
        if ($this->attendanceWorkflowService->findPendingLatePermit($teacher, $today)) {
            throw ValidationException::withMessages([
                'attendance' => 'Izin terlambat guru ini masih menunggu persetujuan.',
            ]);
        }
    }

    private function ensureAttendanceTimeAllowed(
        User $teacher,
        Madrasah $school,
        string $mode,
        Carbon $now,
        ?Presensi $existingPresensi,
        array $payload,
    ): void {
        $endOfDayCutoff = $this->attendanceWorkflowService->resolveEndOfDayCutoff($school);

        if ($teacher->ketugasan !== 'penjaga sekolah' && $mode === 'masuk' && $now->format('H:i:s') > $endOfDayCutoff) {
            if (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $now)) {
                ExternalTeachingPermissionService::createOrUpdateNoPresenceRecord($teacher, $now);
                throw ValidationException::withMessages([
                    'attendance' => 'Guru ini tercatat mengajar di sekolah lain dan tidak dihitung alpha.',
                ]);
            }

            if (!$existingPresensi) {
                Presensi::create($this->filterPresensiAttributes([
                    'user_id' => $teacher->id,
                    'madrasah_id' => $school->id,
                    'tanggal' => $now->toDateString(),
                    'status' => 'alpha',
                    'keterangan' => 'Tidak masuk',
                    'latitude' => $payload['latitude'],
                    'longitude' => $payload['longitude'],
                    'lokasi' => $payload['lokasi'] ?? null,
                    'accuracy' => $payload['accuracy'] ?? null,
                    'altitude' => $payload['altitude'] ?? null,
                    'speed' => $payload['speed'] ?? null,
                    'device_info' => $payload['device_info'] ?? null,
                    'location_readings' => $payload['location_readings'] ?? null,
                    'status_kepegawaian_id' => $teacher->status_kepegawaian_id,
                    'attendance_channel' => 'school_kiosk',
                    'registered_device_id' => $payload['registered_device_id'] ?? null,
                    'recorded_by_user_id' => $payload['recorded_by_user_id'] ?? null,
                    'source_ip_address' => $payload['source_ip_address'] ?? null,
                ]));
            }

            throw ValidationException::withMessages([
                'attendance' => 'Presensi setelah batas akhir otomatis dicatat sebagai tidak masuk.',
            ]);
        }

        if ($mode !== 'masuk' || $teacher->ketugasan === 'penjaga sekolah') {
            if ($mode !== 'keluar' || $teacher->ketugasan === 'penjaga sekolah' || $teacher->pemenuhan_beban_kerja_lain) {
                return;
            }

            $pulangStart = $this->attendanceWorkflowService->resolvePulangStart($school, $now);
            if ($now->format('H:i:s') < $pulangStart) {
                throw ValidationException::withMessages([
                    'attendance' => 'Presensi keluar belum dapat dilakukan. Waktu presensi keluar dimulai pukul ' . substr($pulangStart, 0, 5) . '.',
                ]);
            }

            return;
        }

        $minTimeMasuk = $this->attendanceWorkflowService->resolveMasukStart($school);
        if ($now->format('H:i:s') < $minTimeMasuk) {
            throw ValidationException::withMessages([
                'attendance' => 'Presensi masuk belum dapat dilakukan. Waktu presensi dimulai pukul ' . substr($minTimeMasuk, 0, 5) . '.',
            ]);
        }
    }

    private function ensureLocationInsideSchool(Madrasah $school, float $latitude, float $longitude): void
    {
        if ($this->attendanceValidationService->schoolContainsPoint($school, $latitude, $longitude)) {
            return;
        }

        throw ValidationException::withMessages([
            'attendance' => 'Lokasi perangkat berada di luar area sekolah yang diizinkan.',
        ]);
    }

    private function teacherCanAttendAtSchool(User $teacher, Madrasah $school): bool
    {
        return $teacher->role === 'tenaga_pendidik'
            && ((int) $teacher->madrasah_id === (int) $school->id || (int) $teacher->madrasah_id_tambahan === (int) $school->id);
    }

    private function findExistingPresensi(User $teacher, Madrasah $school): ?Presensi
    {
        return Presensi::query()
            ->where('user_id', $teacher->id)
            ->where('madrasah_id', $school->id)
            ->whereDate('tanggal', Carbon::today('Asia/Jakarta')->toDateString())
            ->first();
    }

    private function validateLocationForFakeGps(array $payload, User $teacher, bool $isPresensiMasuk, ?Presensi $existingPresensi): array
    {
        return $this->attendanceValidationService->validateLocationForFakeGps(
            $payload,
            $teacher,
            $isPresensiMasuk,
            $existingPresensi
        );
    }

    private function processAndSaveSelfie(string $selfieData, int $userId, string $tanggal, bool $isMasuk): string
    {
        try {
            return $this->attendanceValidationService->processAndSaveSelfie($selfieData, $userId, $tanggal, $isMasuk);
        } catch (\RuntimeException $e) {
            throw ValidationException::withMessages([
                'selfie_data' => $e->getMessage(),
            ]);
        }
    }

    private function filterPresensiAttributes(array $attributes): array
    {
        return $this->attendanceValidationService->filterPresensiAttributes($attributes);
    }

    private function normalizeExternalFaceVerification(mixed $payload, User $teacher): ?array
    {
        if (!is_array($payload)) {
            return null;
        }

        $matchedUserId = is_numeric($payload['user_id'] ?? null)
            ? (int) $payload['user_id']
            : null;

        if ($matchedUserId !== null && $matchedUserId !== $teacher->id) {
            return [
                'success' => false,
                'message' => 'Hasil verifikasi eksternal tidak cocok dengan guru yang akan diproses.',
                'face_id_used' => null,
                'similarity' => null,
                'liveness_score' => null,
                'challenges' => [],
                'notes' => 'external_user_mismatch',
                'verified' => false,
            ];
        }

        $success = (bool) ($payload['success'] ?? false);

        return [
            'success' => $success,
            'message' => (string) ($payload['message'] ?? ($success
                ? 'Verifikasi wajah eksternal berhasil.'
                : 'Verifikasi wajah eksternal gagal.')),
            'face_id_used' => isset($payload['face_id_used']) ? (string) $payload['face_id_used'] : null,
            'similarity' => is_numeric($payload['similarity'] ?? null) ? round((float) $payload['similarity'], 4) : null,
            'liveness_score' => is_numeric($payload['liveness_score'] ?? null) ? round((float) $payload['liveness_score'], 4) : null,
            'challenges' => $this->normalizeChallenges($payload['challenges'] ?? $payload['liveness_challenges'] ?? []),
            'notes' => (string) ($payload['notes'] ?? ($success ? 'face_verified_external' : 'face_verification_failed_external')),
            'verified' => $success,
        ];
    }

    private function normalizeChallenges(mixed $challenges): array
    {
        if (!is_array($challenges)) {
            return [];
        }

        return collect($challenges)
            ->filter(fn ($challenge) => is_array($challenge))
            ->map(function (array $challenge) {
                return [
                    'type' => (string) ($challenge['type'] ?? 'unknown'),
                    'passed' => (bool) ($challenge['passed'] ?? false),
                    'score' => is_numeric($challenge['score'] ?? null) ? round((float) $challenge['score'], 4) : null,
                    'detail' => isset($challenge['detail']) ? (string) $challenge['detail'] : null,
                    'timestamp' => is_numeric($challenge['timestamp'] ?? null)
                        ? (int) $challenge['timestamp']
                        : now()->timestamp,
                ];
            })
            ->values()
            ->all();
    }
}
