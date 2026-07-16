<?php $__env->startSection('title', 'Mode Kiosk Presensi'); ?>

<?php $__env->startSection('body'); ?>
<body class="kiosk-fullscreen-page">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    body.kiosk-fullscreen-page {
        margin: 0;
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(14, 165, 233, 0.16), transparent 28%),
            radial-gradient(circle at top right, rgba(34, 197, 94, 0.14), transparent 22%),
            linear-gradient(180deg, #f8fbff 0%, #eef4ff 100%);
    }

    .kiosk-page {
        min-height: 100vh;
        padding: 18px;
    }

    .kiosk-shell {
        border: 0;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.1);
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(18px);
    }

    .kiosk-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
    }

    .kiosk-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
    }

    .kiosk-header p {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 14px;
        max-width: 760px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pill.success {
        background: rgba(34, 197, 94, 0.14);
        color: #15803d;
    }

    .status-pill.danger {
        background: rgba(239, 68, 68, 0.14);
        color: #b91c1c;
    }

    .panel-box {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 18px;
        background: rgba(255, 255, 255, 0.96);
        height: 100%;
    }

    .automation-intro {
        margin-bottom: 18px;
    }

    .automation-intro-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .automation-intro-copy {
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .automation-summary {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .summary-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
    }

    .status-board {
        display: grid;
        gap: 10px;
        margin-bottom: 18px;
    }

    .status-step {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .status-step-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        margin-top: 5px;
        flex: 0 0 auto;
        background: #cbd5e1;
        box-shadow: 0 0 0 4px rgba(203, 213, 225, 0.4);
    }

    .status-step-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
    }

    .status-step-copy {
        color: #64748b;
        font-size: 12px;
        line-height: 1.45;
    }

    .status-step.is-active {
        background: #ecfeff;
        border-color: #67e8f9;
    }

    .status-step.is-active .status-step-dot {
        background: #0891b2;
        box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.22);
    }

    .status-step.is-done {
        background: #f0fdf4;
        border-color: #86efac;
    }

    .status-step.is-done .status-step-dot {
        background: #16a34a;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18);
    }

    .status-step.is-error {
        background: #fef2f2;
        border-color: #fca5a5;
    }

    .status-step.is-error .status-step-dot {
        background: #dc2626;
        box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.18);
    }

    .primary-notice {
        border-radius: 18px;
        padding: 14px 16px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #e2e8f0;
        font-size: 13px;
        line-height: 1.55;
        margin-bottom: 18px;
    }

    .primary-notice strong {
        display: block;
        font-size: 14px;
        color: #fff;
        margin-bottom: 4px;
    }

    .enrollment-banner {
        border-radius: 18px;
        border: 1px solid #fcd34d;
        background: #fffbeb;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .enrollment-banner-title {
        font-size: 13px;
        font-weight: 700;
        color: #92400e;
        margin-bottom: 4px;
    }

    .enrollment-banner-copy {
        color: #a16207;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .enrollment-actions,
    .system-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .attendance-result {
        border-radius: 18px;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        padding: 14px 16px;
        margin-top: 18px;
    }

    .attendance-result[hidden] {
        display: none !important;
    }

    .attendance-result-title {
        font-size: 13px;
        font-weight: 700;
        color: #1d4ed8;
        margin-bottom: 6px;
    }

    .attendance-result-copy {
        color: #1e3a8a;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .camera-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 14px;
    }

    .camera-panel-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .camera-panel-copy {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }

    .scan-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #0f766e;
        background: #ecfeff;
        border: 1px solid #99f6e4;
    }

    .camera-shell {
        position: relative;
        border-radius: 22px;
        overflow: hidden;
        background:
            radial-gradient(circle at top, rgba(56, 189, 248, 0.22), transparent 40%),
            linear-gradient(180deg, #020617 0%, #111827 100%);
        width: 100%;
        aspect-ratio: 16 / 9;
        min-height: clamp(380px, 68vh, 860px);
    }

    .camera-shell video,
    .camera-shell img,
    .camera-shell canvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scaleX(-1);
        background: #020617;
    }

    .camera-shell canvas,
    .camera-preview {
        display: none;
    }

    .camera-preview.show {
        display: block;
    }

    .camera-video.hide {
        display: none;
    }

    .camera-placeholder,
    .camera-overlay {
        position: absolute;
        inset: 0;
    }

    .camera-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 12px;
        color: rgba(226, 232, 240, 0.88);
        text-align: center;
        padding: 24px;
        z-index: 2;
        background: linear-gradient(180deg, rgba(2, 6, 23, 0.28), rgba(2, 6, 23, 0.58));
    }

    .camera-placeholder i {
        font-size: 44px;
    }

    .camera-placeholder strong {
        font-size: 18px;
        color: #fff;
    }

    .camera-placeholder span {
        font-size: 13px;
        line-height: 1.55;
        max-width: 400px;
    }

    .camera-overlay {
        pointer-events: none;
        z-index: 3;
    }

    .camera-grid {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
        background-size: 44px 44px;
        opacity: 0.18;
    }

    .camera-oval {
        position: absolute;
        inset: 12% 28%;
        border-radius: 999px;
        border: 2px solid rgba(255, 255, 255, 0.82);
        box-shadow:
            0 0 0 100vmax rgba(2, 6, 23, 0.24),
            inset 0 0 30px rgba(255, 255, 255, 0.12);
    }

    .camera-guide-pill {
        position: absolute;
        left: 50%;
        bottom: 24px;
        transform: translateX(-50%);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.8);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        backdrop-filter: blur(12px);
        max-width: min(90%, 620px);
        text-align: center;
    }

    .camera-guide-pill i {
        font-size: 18px;
        flex: 0 0 auto;
    }

    .face-modal .modal-content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    .face-modal .modal-header {
        padding: 20px 24px 0;
        border-bottom: 0;
    }

    .face-modal .modal-body {
        padding: 0 24px 24px;
    }

    .enroll-guide-banner {
        margin-top: 12px;
        margin-bottom: 18px;
        padding: 16px 18px;
        border-radius: 18px;
        background: linear-gradient(135deg, #eff6ff 0%, #ecfeff 100%);
        border: 1px solid #bfdbfe;
    }

    .enroll-guide-title {
        font-size: 14px;
        font-weight: 800;
        color: #1d4ed8;
        margin-bottom: 6px;
    }

    .enroll-guide-copy {
        margin: 0;
        color: #1e3a8a;
        font-size: 13px;
        line-height: 1.55;
    }

    .enroll-guide-steps {
        display: grid;
        gap: 8px;
        margin-top: 14px;
    }

    .enroll-guide-step {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: #1e40af;
        font-size: 12px;
        line-height: 1.5;
    }

    .enroll-guide-step i {
        font-size: 16px;
        margin-top: 1px;
        color: #2563eb;
    }

    .enroll-controls {
        display: grid;
        grid-template-columns: minmax(260px, 320px) 1fr;
        gap: 18px;
        align-items: start;
    }

    .teacher-picker-card,
    .enroll-stage-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px;
        background: #fff;
    }

    .teacher-picker-card h6,
    .enroll-stage-card h6 {
        margin-bottom: 6px;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .teacher-picker-card p,
    .enroll-stage-card p {
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }

    .teacher-search-note,
    .teacher-state-copy {
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }

    .teacher-state-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        margin-bottom: 10px;
    }

    .teacher-state-chip.is-ready {
        background: #f0fdf4;
        border-color: #86efac;
        color: #166534;
    }

    .teacher-state-chip.is-missing {
        background: #fff7ed;
        border-color: #fdba74;
        color: #9a3412;
    }

    .enroll-camera-shell {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        background: linear-gradient(180deg, #020617 0%, #111827 100%);
        aspect-ratio: 4 / 5;
        min-height: 420px;
    }

    .enroll-camera-shell video,
    .enroll-camera-shell img,
    .enroll-camera-shell canvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scaleX(-1);
    }

    .enroll-camera-shell canvas,
    .enroll-preview {
        display: none;
    }

    .enroll-preview.show {
        display: block;
    }

    .enroll-camera-shell .camera-oval {
        inset: 10% 22%;
    }

    .enroll-status-box {
        margin-top: 12px;
        border-radius: 16px;
        background: #0f172a;
        color: #e2e8f0;
        padding: 14px;
        min-height: 96px;
    }

    .enroll-status-title {
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .enroll-status-copy {
        font-size: 12px;
        line-height: 1.6;
    }

    .enroll-actions {
        margin-top: 12px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 1199px) {
        .kiosk-page {
            padding: 12px;
        }

        .camera-shell {
            min-height: 340px;
        }

        .enroll-controls {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .kiosk-header {
            flex-direction: column;
        }

        .camera-shell {
            min-height: 260px;
        }

        .camera-oval {
            inset: 12% 18%;
        }

        .camera-guide-pill {
            width: calc(100% - 24px);
            bottom: 14px;
            border-radius: 18px;
        }

        .face-modal .modal-header,
        .face-modal .modal-body {
            padding-left: 16px;
            padding-right: 16px;
        }

        .enroll-camera-shell {
            min-height: 320px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="kiosk-page">
    <div class="card kiosk-shell">
        <div class="card-body p-4 p-lg-4">
            <div class="kiosk-header">
                <div>
                    <h1>School Kiosk Otomatis</h1>
                    <p>
                        Lokasi divalidasi otomatis, kamera aktif otomatis, wajah dikenali secara realtime, lalu presensi langsung diproses
                        memakai aturan presensi sekolah yang sudah berjalan.
                    </p>
                </div>
                <span class="status-pill <?php echo e($accessGranted ? 'success' : 'danger'); ?>">
                    <i class="bx <?php echo e($accessGranted ? 'bx-check-circle' : 'bx-x-circle'); ?>"></i>
                    <?php echo e($accessGranted ? 'Perangkat Tervalidasi' : 'Akses Ditolak'); ?>

                </span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($accessGranted)): ?>
                <div class="alert alert-danger mb-0">
                    <i class="bx bx-error-circle me-2"></i><?php echo e($accessMessage); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accessGranted): ?>
                <div class="row g-4">
                    <div class="col-xl-3">
                        <div class="panel-box">
                            <div class="automation-intro">
                                <div class="automation-intro-title">Status Otomatis</div>
                                <p class="automation-intro-copy">
                                    Guru cukup berada di lokasi sekolah dan menghadapkan wajah ke kamera. Kiosk akan mendeteksi, mengenali,
                                    memverifikasi liveness, lalu memproses presensi secara otomatis.
                                </p>
                            </div>

                            <div class="automation-summary">
                                <span class="summary-chip">
                                    <i class="bx bx-group"></i><?php echo e($teacherCount); ?> guru terhubung
                                </span>
                                <span class="summary-chip">
                                    <i class="bx bx-scan"></i>Face recognition aktif
                                </span>
                            </div>

                            <div class="status-board">
                                <div class="status-step" data-stage="camera_permission">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Meminta izin kamera</div>
                                        <div class="status-step-copy">Menunggu akses kamera perangkat.</div>
                                    </div>
                                </div>
                                <div class="status-step" data-stage="location_permission">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Mengambil lokasi</div>
                                        <div class="status-step-copy">Koordinat GPS diminta otomatis saat halaman dibuka.</div>
                                    </div>
                                </div>
                                <div class="status-step" data-stage="location_validation">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Memvalidasi lokasi</div>
                                        <div class="status-step-copy">Sistem memeriksa apakah perangkat berada di area sekolah.</div>
                                    </div>
                                </div>
                                <div class="status-step" data-stage="waiting_user">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Menunggu pengguna</div>
                                        <div class="status-step-copy">Kamera siap dan menunggu wajah masuk ke bingkai.</div>
                                    </div>
                                </div>
                                <div class="status-step" data-stage="detecting_face">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Mendeteksi wajah</div>
                                        <div class="status-step-copy">Kiosk menjalankan challenge liveness secara realtime.</div>
                                    </div>
                                </div>
                                <div class="status-step" data-stage="verifying_identity">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Memverifikasi identitas</div>
                                        <div class="status-step-copy">Descriptor wajah dicocokkan dengan data guru terdaftar.</div>
                                    </div>
                                </div>
                                <div class="status-step" data-stage="processing_attendance">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Memproses presensi</div>
                                        <div class="status-step-copy">Mode masuk atau keluar ditentukan otomatis oleh sistem.</div>
                                    </div>
                                </div>
                                <div class="status-step" data-stage="attendance_success">
                                    <div class="status-step-dot"></div>
                                    <div>
                                        <div class="status-step-title">Presensi berhasil</div>
                                        <div class="status-step-copy">Hasil presensi dan status harian ditampilkan di sini.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="primary-notice" id="primaryNotice">
                                <strong>Menyiapkan School Kiosk</strong>
                                <span>Meminta izin lokasi dan kamera, lalu sistem akan masuk ke mode siaga otomatis.</span>
                            </div>

                            <div class="enrollment-banner" id="enrollmentBanner" <?php if($teachersWithoutFaceCount === 0): ?> hidden <?php endif; ?>>
                                <div class="enrollment-banner-title">Registrasi wajah guru tersedia</div>
                                <div class="enrollment-banner-copy" id="enrollmentBannerCopy">
                                    Terdapat <?php echo e($teachersWithoutFaceCount); ?> guru yang belum memiliki data wajah. Daftarkan wajah sekali saja,
                                    lalu guru bisa langsung presensi dari kiosk ini tanpa pindah halaman.
                                </div>
                                <div class="enrollment-actions">
                                    <button type="button" class="btn btn-warning btn-sm" id="openEnrollmentModalButton">
                                        <i class="bx bx-user-plus me-1"></i>Registrasi Wajah Guru
                                    </button>
                                </div>
                            </div>

                            <div class="system-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="retryLocationButton" hidden>
                                    <i class="bx bx-current-location me-1"></i>Coba Lokasi Lagi
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="restartScannerButton" hidden>
                                    <i class="bx bx-refresh me-1"></i>Mulai Ulang Scanner
                                </button>
                            </div>

                            <div class="attendance-result" id="attendanceResultCard" hidden>
                                <div class="attendance-result-title" id="attendanceResultTitle">Hasil Presensi</div>
                                <p class="attendance-result-copy" id="attendanceResultCopy"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9">
                        <div class="panel-box">
                            <div class="camera-panel-header">
                                <div>
                                    <div class="camera-panel-title">Kamera Kiosk</div>
                                    <p class="camera-panel-copy" id="cameraPanelCopy">
                                        Setelah lokasi valid, kamera aktif otomatis. Guru cukup berdiri di depan kamera dan mengikuti instruksi singkat.
                                    </p>
                                </div>
                                <div class="scan-badge" id="scanBadge">
                                    <i class="bx bx-loader-circle"></i>
                                    <span>Menyiapkan</span>
                                </div>
                            </div>

                            <div class="camera-shell">
                                <video id="cameraVideo" class="camera-video" autoplay playsinline muted></video>
                                <img id="cameraPreview" class="camera-preview" alt="Preview scan wajah">
                                <canvas id="cameraCanvas"></canvas>

                                <div class="camera-placeholder" id="cameraPlaceholder">
                                    <i class="bx bx-camera-off"></i>
                                    <strong>Kamera akan aktif otomatis</strong>
                                    <span>
                                        Sistem sedang mempersiapkan lokasi dan kamera. Jika semua izin diberikan, kiosk akan langsung masuk ke mode siaga.
                                    </span>
                                </div>

                                <div class="camera-overlay">
                                    <div class="camera-grid"></div>
                                    <div class="camera-oval"></div>
                                    <div class="camera-guide-pill" id="cameraGuidePill">
                                        <i class="bx bx-scan"></i>
                                        <span id="cameraGuideText">Menyiapkan School Kiosk.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade face-modal" id="faceEnrollmentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <h5 class="modal-title mb-1">Registrasi Wajah Guru</h5>
                                    <div class="text-muted small">Pendaftaran wajah dilakukan langsung di kiosk ini tanpa berpindah halaman.</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="enroll-guide-banner">
                                    <div class="enroll-guide-title">Panduan singkat registrasi wajah</div>
                                    <p class="enroll-guide-copy">
                                        Pilih guru, aktifkan kamera pendaftaran, lalu minta guru menatap kamera dengan cahaya cukup.
                                        Sistem akan mengambil frame terbaik secara otomatis dan langsung menyimpan data wajah.
                                    </p>
                                    <div class="enroll-guide-steps">
                                        <div class="enroll-guide-step">
                                            <i class="bx bx-check-circle"></i>
                                            <span>Posisikan satu wajah tepat di dalam oval.</span>
                                        </div>
                                        <div class="enroll-guide-step">
                                            <i class="bx bx-sun"></i>
                                            <span>Gunakan pencahayaan cukup dan hindari backlight berlebihan.</span>
                                        </div>
                                        <div class="enroll-guide-step">
                                            <i class="bx bx-target-lock"></i>
                                            <span>Tahan wajah sejenak sampai proses auto capture selesai.</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="enroll-controls">
                                    <div class="teacher-picker-card">
                                        <h6>Pilih Guru</h6>
                                        <p>Gunakan pencarian untuk menemukan guru yang ingin didaftarkan atau diperbarui data wajahnya.</p>

                                        <div class="mb-3">
                                            <label class="form-label">Cari guru</label>
                                            <input type="text" class="form-control" id="enrollmentTeacherSearchInput" placeholder="Cari nama, NIP, atau NUPTK">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Daftar guru</label>
                                            <select class="form-select" id="enrollmentTeacherSelect" size="10">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                    <option
                                                        value="<?php echo e($teacher->id); ?>"
                                                        data-name="<?php echo e($teacher->name); ?>"
                                                        data-nip="<?php echo e($teacher->nip); ?>"
                                                        data-nuptk="<?php echo e($teacher->nuptk); ?>"
                                                        data-face="<?php echo e($teacher->face_registered_at ? '1' : '0'); ?>"
                                                    >
                                                        <?php echo e($teacher->name); ?>

                                                    </option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="teacher-state-chip" id="teacherStateChip">
                                            <i class="bx bx-user"></i>
                                            <span>Pilih guru terlebih dahulu.</span>
                                        </div>
                                        <div class="teacher-state-copy" id="teacherStateCopy">
                                            Pilih guru dari daftar di atas untuk melihat status wajah dan memulai pendaftaran langsung dari kiosk.
                                        </div>
                                    </div>

                                    <div class="enroll-stage-card">
                                        <h6>Kamera Registrasi</h6>
                                        <p>
                                            Setelah dimulai, sistem akan mengambil wajah secara otomatis dan langsung menyimpannya ke data guru yang dipilih.
                                        </p>

                                        <div class="enroll-camera-shell">
                                            <video id="enrollmentVideo" autoplay playsinline muted></video>
                                            <img id="enrollmentPreview" class="enroll-preview" alt="Preview hasil registrasi wajah">
                                            <canvas id="enrollmentCanvas"></canvas>

                                            <div class="camera-placeholder" id="enrollmentPlaceholder">
                                                <i class="bx bx-user-voice"></i>
                                                <strong>Siap untuk pendaftaran wajah</strong>
                                                <span>Pilih guru lalu mulai registrasi. Kamera akan mengambil wajah terbaik secara otomatis.</span>
                                            </div>

                                            <div class="camera-overlay">
                                                <div class="camera-grid"></div>
                                                <div class="camera-oval"></div>
                                                <div class="camera-guide-pill" id="enrollmentGuidePill">
                                                    <i class="bx bx-scan"></i>
                                                    <span id="enrollmentGuideText">Pilih guru lalu mulai registrasi wajah.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="enroll-status-box">
                                            <div class="enroll-status-title" id="enrollmentStatusTitle">Status Registrasi</div>
                                            <div class="enroll-status-copy" id="enrollmentStatusCopy">
                                                Sistem siap mengambil wajah. Setelah guru dipilih, tekan mulai registrasi untuk menyalakan kamera.
                                            </div>
                                        </div>

                                        <div class="enroll-actions">
                                            <button type="button" class="btn btn-primary" id="startEnrollmentButton">
                                                <i class="bx bx-camera me-1"></i>Mulai Registrasi
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="closeEnrollmentButton" data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accessGranted): ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$faceEngineUsesPython): ?>
<script src="<?php echo e(asset('models/face-api.js')); ?>"></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<script src="<?php echo e(asset('js/face-recognition.js')); ?>"></script>
<script>
    (function () {
        const teachers = <?php echo json_encode(
            $teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->id, 'name' => $teacher->name, 'nip' => $teacher->nip) ?>;
        const verificationMode = <?php echo json_encode($verificationMode, 15, 512) ?>;
        const faceEngineDriver = <?php echo json_encode($faceEngineDriver, 15, 512) ?>;
        const faceEngineLabel = <?php echo json_encode($faceEngineLabel, 15, 512) ?>;
        const faceEngineUsesPython = <?php echo json_encode($faceEngineUsesPython, 15, 512) ?>;
        const locationCheckUrl = <?php echo json_encode(route('school-kiosk.check-location'), 15, 512) ?>;
        const autoSubmitUrl = <?php echo json_encode(route('school-kiosk.auto-submit'), 15, 512) ?>;
        const enrollFaceUrl = <?php echo json_encode(route('school-kiosk.enroll-face'), 15, 512) ?>;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const video = document.getElementById('cameraVideo');
        const preview = document.getElementById('cameraPreview');
        const canvas = document.getElementById('cameraCanvas');
        const placeholder = document.getElementById('cameraPlaceholder');
        const scanBadge = document.getElementById('scanBadge');
        const cameraPanelCopy = document.getElementById('cameraPanelCopy');
        const primaryNotice = document.getElementById('primaryNotice');
        const cameraGuideText = document.getElementById('cameraGuideText');
        const attendanceResultCard = document.getElementById('attendanceResultCard');
        const attendanceResultTitle = document.getElementById('attendanceResultTitle');
        const attendanceResultCopy = document.getElementById('attendanceResultCopy');
        const retryLocationButton = document.getElementById('retryLocationButton');
        const restartScannerButton = document.getElementById('restartScannerButton');
        const enrollmentBanner = document.getElementById('enrollmentBanner');
        const enrollmentBannerCopy = document.getElementById('enrollmentBannerCopy');
        const openEnrollmentModalButton = document.getElementById('openEnrollmentModalButton');

        const enrollmentTeacherSearchInput = document.getElementById('enrollmentTeacherSearchInput');
        const enrollmentTeacherSelect = document.getElementById('enrollmentTeacherSelect');
        const teacherStateChip = document.getElementById('teacherStateChip');
        const teacherStateCopy = document.getElementById('teacherStateCopy');
        const faceEnrollmentModalEl = document.getElementById('faceEnrollmentModal');
        const faceEnrollmentModal = window.bootstrap ? new bootstrap.Modal(faceEnrollmentModalEl) : null;
        const startEnrollmentButton = document.getElementById('startEnrollmentButton');
        const enrollmentVideo = document.getElementById('enrollmentVideo');
        const enrollmentPreview = document.getElementById('enrollmentPreview');
        const enrollmentCanvas = document.getElementById('enrollmentCanvas');
        const enrollmentPlaceholder = document.getElementById('enrollmentPlaceholder');
        const enrollmentGuideText = document.getElementById('enrollmentGuideText');
        const enrollmentStatusTitle = document.getElementById('enrollmentStatusTitle');
        const enrollmentStatusCopy = document.getElementById('enrollmentStatusCopy');

        const faceRecognition = new window.FaceRecognition();
        const statusSteps = Array.from(document.querySelectorAll('.status-step'));

        let activeLocation = null;
        let locationReadings = [];
        let cameraReady = false;
        let scanInProgress = false;
        let scanTimer = null;
        let cameraMode = 'attendance';
        let selectedEnrollmentTeacherId = null;
        let selectedEnrollmentTeacher = null;
        let enrollmentBusy = false;

        const stageOrder = [
            'camera_permission',
            'location_permission',
            'location_validation',
            'waiting_user',
            'detecting_face',
            'verifying_identity',
            'processing_attendance',
            'attendance_success',
        ];

        function setPrimaryNotice(title, message) {
            primaryNotice.innerHTML = `<strong>${title}</strong><span>${message}</span>`;
        }

        function setScanBadge(label, tone = 'info') {
            scanBadge.innerHTML = `<i class="bx ${tone === 'success' ? 'bx-check-circle' : tone === 'danger' ? 'bx-error-circle' : tone === 'warning' ? 'bx-time' : 'bx-loader-circle'}"></i><span>${label}</span>`;
            scanBadge.style.color = tone === 'success' ? '#166534' : tone === 'danger' ? '#b91c1c' : tone === 'warning' ? '#92400e' : '#0f766e';
            scanBadge.style.background = tone === 'success' ? '#f0fdf4' : tone === 'danger' ? '#fef2f2' : tone === 'warning' ? '#fffbeb' : '#ecfeff';
            scanBadge.style.borderColor = tone === 'success' ? '#86efac' : tone === 'danger' ? '#fca5a5' : tone === 'warning' ? '#fcd34d' : '#99f6e4';
        }

        function setCameraGuide(message, icon = 'bx-scan') {
            cameraGuideText.parentElement.querySelector('i').className = `bx ${icon}`;
            cameraGuideText.textContent = message;
        }

        function setStageState(stage, state = 'idle', message = null) {
            const target = document.querySelector(`.status-step[data-stage="${stage}"]`);
            if (!target) {
                return;
            }

            target.classList.remove('is-active', 'is-done', 'is-error');
            if (state === 'active') {
                target.classList.add('is-active');
            } else if (state === 'done') {
                target.classList.add('is-done');
            } else if (state === 'error') {
                target.classList.add('is-error');
            }

            if (message) {
                const copy = target.querySelector('.status-step-copy');
                if (copy) {
                    copy.textContent = message;
                }
            }
        }

        function resetStagesAfter(stage) {
            const index = stageOrder.indexOf(stage);
            stageOrder.forEach((item, itemIndex) => {
                if (itemIndex > index) {
                    setStageState(item, 'idle');
                }
            });
        }

        function showAttendanceResult(title, message) {
            attendanceResultCard.hidden = false;
            attendanceResultTitle.textContent = title;
            attendanceResultCopy.textContent = message;
        }

        function hideAttendanceResult() {
            attendanceResultCard.hidden = true;
        }

        function updateEnrollmentBanner() {
            const remaining = teachers.filter((teacher) => !teacher.has_face).length;
            if (remaining <= 0) {
                enrollmentBanner.hidden = true;
                return;
            }

            enrollmentBanner.hidden = false;
            enrollmentBannerCopy.textContent = `Terdapat ${remaining} guru yang belum memiliki data wajah. Daftarkan wajah sekali saja, lalu guru bisa langsung presensi dari kiosk ini tanpa pindah halaman.`;
        }

        function setTeacherState(teacher) {
            if (!teacher) {
                teacherStateChip.className = 'teacher-state-chip';
                teacherStateChip.innerHTML = '<i class="bx bx-user"></i><span>Pilih guru terlebih dahulu.</span>';
                teacherStateCopy.textContent = 'Pilih guru dari daftar di atas untuk melihat status wajah dan memulai pendaftaran langsung dari kiosk.';
                return;
            }

            const identity = [teacher.nip || '-', teacher.nuptk || '-'].join(' • ');
            if (teacher.has_face) {
                teacherStateChip.className = 'teacher-state-chip is-ready';
                teacherStateChip.innerHTML = '<i class="bx bx-check-shield"></i><span>Data wajah sudah tersedia</span>';
                teacherStateCopy.textContent = `${teacher.name} sudah memiliki data wajah. Anda tetap bisa memperbarui data wajah jika diperlukan. Identitas: ${identity}.`;
                return;
            }

            teacherStateChip.className = 'teacher-state-chip is-missing';
            teacherStateChip.innerHTML = '<i class="bx bx-error-circle"></i><span>Data wajah belum tersedia</span>';
            teacherStateCopy.textContent = `${teacher.name} belum memiliki data wajah. Lanjutkan registrasi dari kiosk ini agar guru bisa langsung presensi otomatis. Identitas: ${identity}.`;
        }

        function updateEnrollmentTeacherSelection() {
            const teacherId = Number(enrollmentTeacherSelect.value || 0);
            selectedEnrollmentTeacherId = teacherId || null;
            selectedEnrollmentTeacher = teachers.find((teacher) => teacher.id === teacherId) || null;
            setTeacherState(selectedEnrollmentTeacher);
        }

        function filterEnrollmentTeachers() {
            const keyword = (enrollmentTeacherSearchInput.value || '').trim().toLowerCase();
            Array.from(enrollmentTeacherSelect.options).forEach((option) => {
                const teacher = teachers.find((item) => item.id === Number(option.value));
                if (!teacher) {
                    option.hidden = false;
                    return;
                }

                const haystack = [teacher.name, teacher.nip, teacher.nuptk].join(' ').toLowerCase();
                option.hidden = keyword !== '' && !haystack.includes(keyword);
            });
        }

        function stopCurrentCamera() {
            faceRecognition.stopCamera(cameraMode === 'enrollment' ? enrollmentVideo : video);
            cameraReady = false;
            if (cameraMode === 'attendance') {
                video.classList.remove('hide');
                preview.classList.remove('show');
            } else {
                enrollmentPreview.classList.remove('show');
            }
        }

        function clearScanTimer() {
            if (scanTimer) {
                window.clearTimeout(scanTimer);
                scanTimer = null;
            }
        }

        function wait(ms) {
            return new Promise((resolve) => window.setTimeout(resolve, ms));
        }

        function scheduleNextScan(delay = 1000) {
            clearScanTimer();
            scanTimer = window.setTimeout(runAutomaticFaceScan, delay);
        }

        async function getLocationReading() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 12000,
                    maximumAge: 0,
                });
            });
        }

        async function requestLocationAndValidate() {
            retryLocationButton.hidden = true;
            setStageState('location_permission', 'active', 'Meminta izin lokasi perangkat.');
            setPrimaryNotice('Mengambil lokasi', 'Sistem sedang meminta izin GPS dan mengambil koordinat perangkat.');
            setScanBadge('Mengambil lokasi', 'info');

            try {
                const first = await getLocationReading();
                const second = await getLocationReading();
                const active = second || first;

                locationReadings = [first, second]
                    .filter(Boolean)
                    .map((position) => ({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                        timestamp: Date.now(),
                    }));

                activeLocation = {
                    latitude: active.coords.latitude,
                    longitude: active.coords.longitude,
                    accuracy: active.coords.accuracy || null,
                    altitude: active.coords.altitude || null,
                    speed: active.coords.speed || null,
                };

                setStageState('location_permission', 'done', 'Koordinat GPS berhasil diambil dari perangkat ini.');
                setStageState('location_validation', 'active', 'Memeriksa apakah lokasi berada di dalam area sekolah.');
                setPrimaryNotice('Memvalidasi lokasi', 'Koordinat GPS sedang dibandingkan dengan area sekolah yang diizinkan.');
                setScanBadge('Validasi lokasi', 'info');

                const response = await fetch(locationCheckUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        latitude: activeLocation.latitude,
                        longitude: activeLocation.longitude,
                        accuracy: activeLocation.accuracy,
                        location_readings: JSON.stringify(locationReadings),
                    }),
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Lokasi tidak valid.');
                }

                setStageState('location_validation', 'done', payload.message || 'Lokasi tervalidasi berada di area sekolah.');
                setPrimaryNotice('Lokasi valid', 'Perangkat berada di area sekolah. Kamera akan diaktifkan otomatis.');
                setScanBadge('Lokasi valid', 'success');
                cameraPanelCopy.textContent = 'Lokasi sudah valid. Kamera akan tetap siaga untuk mengenali guru berikutnya secara otomatis.';
                return true;
            } catch (error) {
                setStageState('location_permission', 'error', error.message || 'Akses lokasi tidak tersedia.');
                setStageState('location_validation', 'error', error.message || 'Lokasi belum bisa divalidasi.');
                resetStagesAfter('location_permission');
                setPrimaryNotice('Lokasi belum siap', error.message || 'Presensi tidak dapat dilanjutkan sampai lokasi valid.');
                setScanBadge('Lokasi gagal', 'danger');
                setCameraGuide('Presensi belum dapat dilanjutkan sampai lokasi valid.', 'bx-map-pin');
                retryLocationButton.hidden = false;
                showAttendanceResult('Lokasi belum valid', error.message || 'Presensi tidak dapat dilakukan karena lokasi belum tervalidasi.');
                throw error;
            }
        }

        async function startAttendanceCamera() {
            cameraMode = 'attendance';
            restartScannerButton.hidden = true;
            setStageState('camera_permission', 'active', 'Meminta izin kamera untuk memulai scan realtime.');
            setPrimaryNotice(
                'Mengaktifkan kamera',
                faceEngineUsesPython
                    ? `Sistem sedang menyalakan kamera kiosk dan menyiapkan engine ${faceEngineLabel}.`
                    : 'Sistem sedang menyalakan kamera kiosk dan memuat model scan wajah.'
            );
            setScanBadge('Menyalakan kamera', 'info');
            setCameraGuide(
                faceEngineUsesPython
                    ? `Meminta izin kamera dan menyiapkan ${faceEngineLabel}.`
                    : 'Meminta izin kamera dan memuat model wajah.',
                'bx-camera'
            );

            try {
                if (!faceEngineUsesPython) {
                    await faceRecognition.loadModels();
                }
                await faceRecognition.initializeCamera(video);
                placeholder.style.display = 'none';
                preview.classList.remove('show');
                video.classList.remove('hide');
                cameraReady = true;
                setStageState('camera_permission', 'done', 'Kamera aktif dan siap dipakai untuk presensi otomatis.');
                setPrimaryNotice(
                    'Kamera aktif',
                    faceEngineUsesPython
                        ? `Guru cukup berdiri di depan kamera. Deteksi wajah, verifikasi identitas, dan liveness akan diproses otomatis oleh ${faceEngineLabel}.`
                        : 'Guru cukup berdiri di depan kamera dan mengikuti instruksi liveness singkat.'
                );
                setScanBadge('Menunggu pengguna', 'warning');
                setStageState('waiting_user', 'active', 'Kamera siaga dan menunggu wajah masuk ke bingkai.');
                setCameraGuide('Arahkan satu wajah ke dalam oval untuk memulai presensi otomatis.', 'bx-user-check');
            } catch (error) {
                placeholder.style.display = 'flex';
                setStageState('camera_permission', 'error', error.message || 'Kamera tidak dapat diakses.');
                setPrimaryNotice('Kamera gagal diakses', error.message || 'Izinkan kamera agar School Kiosk dapat berjalan otomatis.');
                setScanBadge('Kamera gagal', 'danger');
                setCameraGuide('Izinkan kamera lalu mulai ulang scanner.', 'bx-camera-off');
                restartScannerButton.hidden = false;
                throw error;
            }
        }

        async function submitAutomaticAttendance(scanResult) {
            setStageState(
                'detecting_face',
                'done',
                faceEngineUsesPython
                    ? 'Burst frame berhasil diambil dan dikirim ke engine Python.'
                    : 'Challenge liveness selesai dan frame terbaik berhasil diambil.'
            );
            setStageState('verifying_identity', 'active', 'Mencocokkan wajah dengan data guru terdaftar.');
            setPrimaryNotice(
                'Memverifikasi identitas',
                faceEngineUsesPython
                    ? `Frame wajah sedang dianalisis oleh ${faceEngineLabel} untuk deteksi, liveness, dan pengenalan identitas.`
                    : 'Descriptor wajah sedang dicocokkan dengan data guru yang terdaftar di sekolah ini.'
            );
            setScanBadge('Verifikasi identitas', 'info');
            setCameraGuide('Memverifikasi identitas guru.', 'bx-shield-quarter');

            const payload = {
                latitude: activeLocation.latitude,
                longitude: activeLocation.longitude,
                lokasi: `${activeLocation.latitude}, ${activeLocation.longitude}`,
                accuracy: activeLocation.accuracy,
                altitude: activeLocation.altitude,
                speed: activeLocation.speed,
                device_info: navigator.userAgent || '',
                location_readings: JSON.stringify(locationReadings),
                selfie_data: scanResult.captured_image,
                selfie_frames: scanResult.selfie_frames || [],
                face_descriptor: scanResult.face_descriptor || [],
                liveness_score: scanResult.liveness_score ?? 0,
                liveness_challenges: scanResult.liveness_challenges || [],
            };

            const response = await fetch(autoSubmitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Presensi otomatis gagal diproses.');
            }

            setStageState('verifying_identity', 'done', `Guru dikenali sebagai ${result.teacher?.name || 'guru terdaftar'}.`);
            setStageState('processing_attendance', 'active', 'Aturan presensi sekolah sedang diproses.');
            setPrimaryNotice('Memproses presensi', 'Mode presensi masuk atau keluar ditentukan otomatis sesuai data hari ini.');
            setScanBadge('Memproses presensi', 'info');
            setCameraGuide('Memproses presensi otomatis.', 'bx-loader-circle');

            setStageState('processing_attendance', 'done', result.message || 'Presensi berhasil diproses.');
            setStageState('attendance_success', 'done', result.message || 'Presensi berhasil direkam.');
            setPrimaryNotice('Presensi berhasil', `${result.teacher?.name || 'Guru'} berhasil diproses secara otomatis sebagai presensi ${result.mode || '-'}.`);
            setScanBadge('Presensi berhasil', 'success');
            setCameraGuide('Presensi berhasil. Kiosk kembali ke mode siaga otomatis.', 'bx-check-circle');
            showAttendanceResult(
                result.teacher?.name || 'Presensi berhasil',
                `${result.message || 'Presensi berhasil direkam.'} ${result.presensi?.waktu_masuk ? 'Masuk: ' + result.presensi.waktu_masuk + '.' : ''} ${result.presensi?.waktu_keluar ? 'Keluar: ' + result.presensi.waktu_keluar + '.' : ''}`.trim()
            );
        }

        async function performPythonAttendanceCapture() {
            setPrimaryNotice('Mendeteksi wajah', `Mengambil beberapa frame otomatis untuk dianalisis oleh ${faceEngineLabel}.`);
            setScanBadge('Mengambil frame', 'info');
            setCameraGuide('Tatap kamera, kedip alami, dan tahan posisi sebentar.', 'bx-scan');

            const burst = await faceRecognition.captureBurstFrames(video, {
                count: 6,
                intervalMs: 170,
                warmupMs: 260,
                onProgress: function (current, total) {
                    setStageState('detecting_face', 'active', `Mengambil frame ${current} dari ${total} untuk scan otomatis.`);
                },
            });

            if (!burst.best_frame || !Array.isArray(burst.frames) || burst.frames.length === 0) {
                throw new Error('Frame kamera belum berhasil diambil. Ulangi scan wajah.');
            }

            setStageState('detecting_face', 'active', 'Frame scan berhasil diambil. Sistem sedang menyiapkan verifikasi.');

            return {
                captured_image: burst.best_frame,
                selfie_frames: burst.frames,
                face_descriptor: [],
                liveness_score: null,
                liveness_challenges: [],
            };
        }

        async function runAutomaticFaceScan() {
            if (!cameraReady || scanInProgress || enrollmentBusy || !activeLocation) {
                return;
            }

            scanInProgress = true;
            hideAttendanceResult();
            setStageState('waiting_user', 'active', 'Kamera siaga dan menunggu wajah masuk ke bingkai.');
            setStageState('detecting_face', 'active', 'Sistem mulai membaca posisi wajah dan challenge liveness.');
            setStageState('verifying_identity', 'idle');
            setStageState('processing_attendance', 'idle');
            setStageState('attendance_success', 'idle');
            setPrimaryNotice('Menunggu pengguna', 'Guru cukup berdiri di depan kamera. Begitu wajah stabil, verifikasi akan berjalan otomatis.');
            setScanBadge('Menunggu pengguna', 'warning');
            setCameraGuide('Arahkan satu wajah ke dalam oval untuk memulai scan.', 'bx-user-voice');

            try {
                const result = faceEngineUsesPython
                    ? await performPythonAttendanceCapture()
                    : await faceRecognition.performAttendanceScan(video, {
                        onInstruction: function (message) {
                            setCameraGuide(message, 'bx-scan');
                        },
                        onStatus: function (message) {
                            setPrimaryNotice('Mendeteksi wajah', message);
                            setStageState('detecting_face', 'active', message);
                            setScanBadge('Mendeteksi wajah', 'info');
                        },
                        onGuideState: function (payload) {
                            if (payload?.message) {
                                setCameraGuide(payload.message, payload.state === 'success' ? 'bx-check-circle' : 'bx-scan');
                            }
                        },
                    });

                await submitAutomaticAttendance(result);
                scheduleNextScan(5000);
            } catch (error) {
                const message = error.message || 'Scan wajah belum berhasil.';
                setStageState('detecting_face', 'error', message);
                setPrimaryNotice('Scan belum berhasil', message);
                setScanBadge('Scan diulang', 'warning');
                setCameraGuide(message, 'bx-refresh');
                showAttendanceResult('Scan diulang', message);
                scheduleNextScan(1800);
            } finally {
                scanInProgress = false;
            }
        }

        async function bootstrapAutomation() {
            clearScanTimer();
            try {
                await requestLocationAndValidate();
                await startAttendanceCamera();
                scheduleNextScan(1000);
            } catch (error) {
                // error state already rendered in helpers
            }
        }

        async function startEnrollmentFlow() {
            if (enrollmentBusy) {
                return;
            }

            if (!selectedEnrollmentTeacher) {
                setTeacherState(null);
                enrollmentStatusTitle.textContent = 'Pilih guru terlebih dahulu';
                enrollmentStatusCopy.textContent = 'Tentukan guru yang akan didaftarkan, lalu mulai registrasi kembali.';
                return;
            }

            enrollmentBusy = true;
            startEnrollmentButton.disabled = true;
            startEnrollmentButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Memproses...';
            enrollmentPreview.classList.remove('show');
            enrollmentPlaceholder.style.display = 'none';
            enrollmentStatusTitle.textContent = 'Menyalakan kamera pendaftaran';
            enrollmentStatusCopy.textContent = 'Sistem sedang memuat kamera dan model wajah untuk registrasi.';
            enrollmentGuideText.textContent = 'Memuat kamera pendaftaran wajah.';

            try {
                stopCurrentCamera();
                cameraMode = 'enrollment';
                if (!faceEngineUsesPython) {
                    await faceRecognition.loadModels();
                }
                await faceRecognition.initializeCamera(enrollmentVideo);
                enrollmentStatusTitle.textContent = 'Registrasi berjalan';
                enrollmentStatusCopy.textContent = faceEngineUsesPython
                    ? `Minta guru menatap kamera dan tahan posisi sebentar. Beberapa frame akan diambil otomatis untuk registrasi melalui ${faceEngineLabel}.`
                    : 'Minta guru menatap kamera dan tahan posisi sampai sistem mengambil frame terbaik secara otomatis.';
                enrollmentGuideText.textContent = 'Posisikan satu wajah tepat di dalam oval.';

                const enrollmentResult = faceEngineUsesPython
                    ? await (async function () {
                        await wait(420);

                        const burst = await faceRecognition.captureBurstFrames(enrollmentVideo, {
                            count: 6,
                            intervalMs: 170,
                            warmupMs: 260,
                            onProgress: function (current, total) {
                                enrollmentStatusCopy.textContent = `Mengambil frame ${current} dari ${total} untuk registrasi wajah otomatis.`;
                                enrollmentGuideText.textContent = 'Tahan posisi wajah dan kedip alami sebentar.';
                            },
                        });

                        if (!burst.best_frame || !Array.isArray(burst.frames) || burst.frames.length === 0) {
                            throw new Error('Frame registrasi belum berhasil diambil. Ulangi proses registrasi.');
                        }

                        return {
                            captured_image: burst.best_frame,
                            selfie_frames: burst.frames,
                            face_descriptor: [],
                            liveness_score: null,
                            liveness_challenges: [],
                        };
                    })()
                    : await faceRecognition.performEnrollmentScan(enrollmentVideo, {
                        onInstruction: function (message) {
                            enrollmentGuideText.textContent = message;
                        },
                        onStatus: function (message) {
                            enrollmentStatusCopy.textContent = message;
                        },
                        onGuideState: function (payload) {
                            if (payload?.message) {
                                enrollmentGuideText.textContent = payload.message;
                            }
                        },
                    });

                enrollmentPreview.src = enrollmentResult.captured_image;
                enrollmentPreview.classList.add('show');
                faceRecognition.stopCamera(enrollmentVideo);
                enrollmentStatusTitle.textContent = 'Menyimpan data wajah';
                enrollmentStatusCopy.textContent = 'Frame terbaik berhasil diambil. Sistem sedang menyimpan data wajah guru.';
                enrollmentGuideText.textContent = 'Menyimpan data wajah ke server.';

                const response = await fetch(enrollFaceUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        teacher_id: selectedEnrollmentTeacher.id,
                        selfie_data: enrollmentResult.captured_image,
                        selfie_frames: enrollmentResult.selfie_frames || [],
                        face_descriptor: enrollmentResult.face_descriptor,
                        liveness_score: enrollmentResult.liveness_score,
                        liveness_challenges: enrollmentResult.liveness_challenges,
                        device_info: navigator.userAgent || '',
                    }),
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Registrasi wajah gagal disimpan.');
                }

                const updated = payload.teacher || null;
                const teacher = teachers.find((item) => item.id === selectedEnrollmentTeacher.id);
                if (teacher) {
                    teacher.has_face = true;
                    teacher.face_registered_at = updated?.face_registered_at || new Date().toISOString();
                }

                Array.from(enrollmentTeacherSelect.options).forEach((option) => {
                    if (Number(option.value) === selectedEnrollmentTeacher.id) {
                        option.dataset.face = '1';
                    }
                });

                selectedEnrollmentTeacher.has_face = true;
                selectedEnrollmentTeacher.face_registered_at = updated?.face_registered_at || new Date().toISOString();
                setTeacherState(selectedEnrollmentTeacher);
                updateEnrollmentBanner();

                enrollmentStatusTitle.textContent = 'Registrasi berhasil';
                enrollmentStatusCopy.textContent = payload.message || 'Data wajah berhasil disimpan. Kiosk akan kembali ke mode presensi otomatis.';
                enrollmentGuideText.textContent = 'Registrasi selesai. Menutup modal dan kembali ke mode presensi.';

                window.setTimeout(function () {
                    if (faceEnrollmentModal) {
                        faceEnrollmentModal.hide();
                    }
                }, 1200);
            } catch (error) {
                faceRecognition.stopCamera(enrollmentVideo);
                enrollmentPlaceholder.style.display = 'flex';
                enrollmentStatusTitle.textContent = 'Registrasi gagal';
                enrollmentStatusCopy.textContent = error.message || 'Registrasi wajah belum berhasil. Ulangi proses dan pastikan wajah berada di dalam oval.';
                enrollmentGuideText.textContent = 'Registrasi gagal. Ulangi proses scan wajah.';
            } finally {
                enrollmentBusy = false;
                startEnrollmentButton.disabled = false;
                startEnrollmentButton.innerHTML = '<i class="bx bx-camera me-1"></i>Mulai Registrasi';
            }
        }

        openEnrollmentModalButton?.addEventListener('click', function () {
            clearScanTimer();
            stopCurrentCamera();
            cameraMode = 'enrollment';
            enrollmentPreview.classList.remove('show');
            enrollmentPlaceholder.style.display = 'flex';
            enrollmentStatusTitle.textContent = 'Status Registrasi';
            enrollmentStatusCopy.textContent = 'Sistem siap mengambil wajah. Setelah guru dipilih, tekan mulai registrasi untuk menyalakan kamera.';
            enrollmentGuideText.textContent = 'Pilih guru lalu mulai registrasi wajah.';

            if (faceEnrollmentModal) {
                faceEnrollmentModal.show();
            }
        });

        faceEnrollmentModalEl?.addEventListener('hidden.bs.modal', function () {
            faceRecognition.stopCamera(enrollmentVideo);
            enrollmentPreview.classList.remove('show');
            enrollmentPlaceholder.style.display = 'flex';
            cameraMode = 'attendance';
            bootstrapAutomation();
        });

        enrollmentTeacherSearchInput?.addEventListener('input', filterEnrollmentTeachers);
        enrollmentTeacherSelect?.addEventListener('change', updateEnrollmentTeacherSelection);
        startEnrollmentButton?.addEventListener('click', startEnrollmentFlow);

        retryLocationButton?.addEventListener('click', function () {
            bootstrapAutomation();
        });

        restartScannerButton?.addEventListener('click', function () {
            bootstrapAutomation();
        });

        updateEnrollmentBanner();
        updateEnrollmentTeacherSelection();
        bootstrapAutomation();
    })();
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/kiosk/school-kiosk.blade.php ENDPATH**/ ?>