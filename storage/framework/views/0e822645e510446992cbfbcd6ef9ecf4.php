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

    .camera-shell > video,
    .camera-shell > .camera-preview,
    .camera-shell > canvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scaleX(-1);
        background: #020617;
    }

    .camera-video,
    .enroll-video {
        z-index: 1;
    }

    .camera-shell > canvas,
    .camera-shell > .camera-preview {
        display: none;
    }

    .camera-shell > canvas.is-live,
    .enroll-camera-shell canvas.is-live {
        display: block;
        z-index: 1;
    }

    .camera-shell > .camera-preview.show {
        display: block;
        z-index: 2;
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
        z-index: 4;
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
        inset: 9% 33% 11% 33%;
        border-radius: 44% / 50%;
        border: 2px solid rgba(255, 255, 255, 0.82);
        box-shadow:
            0 0 0 100vmax rgba(2, 6, 23, 0.24),
            inset 0 0 30px rgba(255, 255, 255, 0.12);
    }

    .camera-guide-pill {
        position: absolute;
        left: 50%;
        top: 24px;
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

    .camera-activity-panel {
        position: absolute;
        top: 20px;
        right: 20px;
        bottom: 20px;
        width: min(320px, 28%);
        display: flex;
        flex-direction: column;
        border-radius: 22px;
        border: 1px solid rgba(255, 255, 255, 0.16);
        background: rgba(15, 23, 42, 0.42);
        backdrop-filter: blur(16px);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.22);
        z-index: 5;
        overflow: hidden;
    }

    .camera-activity-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 16px 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .camera-activity-title {
        font-size: 13px;
        font-weight: 800;
        color: #f8fafc;
        margin: 0;
    }

    .camera-activity-subtitle {
        margin: 4px 0 0;
        font-size: 11px;
        color: rgba(226, 232, 240, 0.78);
    }

    .camera-activity-count {
        flex: 0 0 auto;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(56, 189, 248, 0.16);
        color: #bae6fd;
        font-size: 12px;
        font-weight: 800;
    }

    .camera-activity-list {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .camera-activity-list::-webkit-scrollbar {
        width: 6px;
    }

    .camera-activity-list::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.42);
        border-radius: 999px;
    }

    .camera-activity-empty {
        padding: 16px;
        border-radius: 16px;
        background: rgba(15, 23, 42, 0.42);
        color: rgba(226, 232, 240, 0.8);
        text-align: center;
        font-size: 12px;
        line-height: 1.6;
    }

    .camera-activity-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-radius: 18px;
        padding: 12px 12px 11px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.08);
        color: #f8fafc;
    }

    .camera-activity-avatar {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        overflow: hidden;
        flex: 0 0 auto;
        background: rgba(255, 255, 255, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e2e8f0;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .camera-activity-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .camera-activity-avatar i {
        font-size: 20px;
    }

    .camera-activity-body {
        min-width: 0;
        flex: 1 1 auto;
    }

    .camera-activity-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .camera-activity-name {
        font-size: 13px;
        font-weight: 800;
        color: #fff;
        line-height: 1.4;
        min-width: 0;
    }

    .camera-activity-time {
        font-size: 12px;
        font-weight: 700;
        color: #cbd5e1;
        white-space: nowrap;
    }

    .camera-activity-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 8px;
    }

    .camera-activity-stat {
        border-radius: 14px;
        padding: 10px 10px 9px;
        background: rgba(15, 23, 42, 0.26);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .camera-activity-stat-label {
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: rgba(226, 232, 240, 0.72);
        margin-bottom: 5px;
        white-space: nowrap;
    }

    .camera-activity-stat-time {
        font-size: 13px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .camera-activity-note {
        font-size: 11px;
        line-height: 1.55;
        color: rgba(226, 232, 240, 0.84);
    }

    .kiosk-flash-modal {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(2, 6, 23, 0.28);
        backdrop-filter: blur(4px);
        z-index: 2100;
    }

    .kiosk-flash-modal[hidden] {
        display: none !important;
    }

    .kiosk-flash-card {
        width: min(520px, calc(100vw - 32px));
        border-radius: 28px;
        padding: 26px 24px 24px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
        text-align: center;
    }

    .kiosk-flash-card.is-success {
        border: 1px solid #86efac;
    }

    .kiosk-flash-card.is-warning {
        border: 1px solid #fcd34d;
    }

    .kiosk-flash-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 14px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .kiosk-flash-card.is-success .kiosk-flash-icon {
        background: #dcfce7;
        color: #15803d;
    }

    .kiosk-flash-card.is-warning .kiosk-flash-icon {
        background: #fef3c7;
        color: #b45309;
    }

    .kiosk-flash-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .kiosk-flash-copy {
        font-size: 14px;
        color: #334155;
        line-height: 1.7;
        margin-bottom: 10px;
    }

    .kiosk-flash-meta {
        font-size: 13px;
        font-weight: 700;
        color: #0f766e;
    }

    .face-modal .modal-content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    .face-modal {
        z-index: 2055 !important;
    }

    .face-modal .modal-dialog {
        max-width: min(1320px, calc(100vw - 40px));
        margin: 1rem auto;
        pointer-events: auto;
    }

    .face-modal-backdrop {
        z-index: 2050 !important;
        background: rgba(15, 23, 42, 0.24) !important;
        opacity: 1 !important;
    }

    .face-modal .modal-header {
        padding: 18px 22px 14px;
        border-bottom: 0;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .face-modal .modal-body {
        padding: 18px 22px 22px;
    }

    .enroll-controls {
        display: grid;
        grid-template-columns: minmax(280px, 320px) minmax(0, 1fr);
        gap: 16px;
        align-items: start;
    }

    .teacher-picker-card,
    .enroll-stage-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px 16px 14px;
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

    .enroll-compact-note {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        padding: 10px 12px;
        border-radius: 14px;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        line-height: 1.45;
    }

    .enroll-compact-note i {
        font-size: 16px;
        color: #2563eb;
        flex: 0 0 auto;
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
        aspect-ratio: 16 / 10;
        min-height: 500px;
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
        z-index: 2;
    }

    .enroll-camera-shell .camera-oval {
        inset: 9% 34%;
    }

    .enroll-status-box {
        margin-top: 10px;
        border-radius: 16px;
        background: #0f172a;
        color: #e2e8f0;
        padding: 12px 14px;
        min-height: 82px;
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
        margin-top: 10px;
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

        .camera-activity-panel {
            width: min(290px, 34%);
        }

        .enroll-controls {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .camera-shell {
            min-height: 260px;
        }

        .camera-oval {
            inset: 12% 22% 14%;
        }

        .camera-guide-pill {
            width: calc(100% - 24px);
            top: 12px;
            border-radius: 18px;
        }

        .camera-activity-panel {
            top: auto;
            right: 12px;
            left: 12px;
            bottom: 12px;
            width: auto;
            max-height: 38%;
        }

        .face-modal .modal-header,
        .face-modal .modal-body {
            padding-left: 16px;
            padding-right: 16px;
        }

        .face-modal .modal-dialog {
            max-width: calc(100vw - 18px);
            margin: 0.5rem auto;
        }

        .enroll-camera-shell {
            min-height: 320px;
            aspect-ratio: 4 / 5;
        }

        .enroll-camera-shell .camera-oval {
            inset: 10% 22%;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="kiosk-page">
    <div class="card kiosk-shell">
        <div class="card-body p-4 p-lg-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($accessGranted)): ?>
                <div class="alert alert-danger mb-0">
                    <i class="bx bx-error-circle me-2"></i><?php echo e($accessMessage); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accessGranted): ?>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="panel-box">
                            <div class="camera-panel-header">
                                <div>
                                    <div class="camera-panel-title">Kiosk Kamera Presensi Kehadiran</div>
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

                                <aside class="camera-activity-panel">
                                    <div class="camera-activity-header">
                                        <div>
                                            <div class="camera-activity-title">Data Presensi Hari Ini</div>
                                            <p class="camera-activity-subtitle">Guru yang sudah berhasil presensi masuk atau pulang.</p>
                                        </div>
                                        <div class="camera-activity-count" id="cameraActivityCount">0</div>
                                    </div>
                                    <div class="camera-activity-list" id="cameraActivityList"></div>
                                </aside>
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

                            <div class="d-none" aria-hidden="true">
                                <div class="primary-notice mt-3" id="primaryNotice">
                                    <strong>Menyiapkan School Kiosk</strong>
                                    <span>Meminta izin lokasi dan kamera, lalu sistem akan masuk ke mode siaga otomatis.</span>
                                </div>

                                <div class="attendance-result" id="attendanceResultCard" hidden>
                                    <div class="attendance-result-title" id="attendanceResultTitle">Hasil Presensi</div>
                                    <p class="attendance-result-copy" id="attendanceResultCopy"></p>
                                </div>

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
                        </div>
                    </div>
                </div>

                <div class="kiosk-flash-modal" id="kioskFlashModal" hidden>
                    <div class="kiosk-flash-card is-success" id="kioskFlashCard">
                        <div class="kiosk-flash-icon" id="kioskFlashIcon">
                            <i class="bx bx-check-circle"></i>
                        </div>
                        <div class="kiosk-flash-title" id="kioskFlashTitle">Presensi Berhasil</div>
                        <div class="kiosk-flash-copy" id="kioskFlashCopy"></div>
                        <div class="kiosk-flash-meta" id="kioskFlashMeta"></div>
                    </div>
                </div>

                <div class="modal fade face-modal" id="faceEnrollmentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <h5 class="modal-title mb-1">Registrasi Wajah Guru</h5>
                                    <div class="text-muted small">Pilih guru, lihat kamera, lalu scan dan simpan wajah.</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="enroll-controls">
                                    <div class="teacher-picker-card">
                                        <h6>Pilih Guru</h6>
                                        <p>Pilih guru yang akan didaftarkan atau diperbarui wajahnya.</p>

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

                                        <div class="enroll-compact-note">
                                            <i class="bx bx-info-circle"></i>
                                            <span>Pastikan satu wajah saja terlihat, cahaya cukup, dan wajah berada di dalam oval.</span>
                                        </div>
                                    </div>

                                    <div class="enroll-stage-card">
                                        <h6>Kamera Registrasi</h6>
                                        <p>Kamera aktif otomatis. Setelah guru dipilih, klik sekali untuk scan dan simpan.</p>

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
                                                Kamera pendaftaran sedang disiapkan. Pilih guru lalu mulai scan wajah.
                                            </div>
                                        </div>

                                        <div class="enroll-actions">
                                            <button type="button" class="btn btn-primary" id="startEnrollmentButton">
                                                <i class="bx bx-camera me-1"></i>Scan dan Simpan
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
        const teachers = <?php echo json_encode($teachersPayload, 15, 512) ?>;
        const initialAttendanceActivities = <?php echo json_encode($attendanceActivities, 15, 512) ?>;
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
        const cameraActivityList = document.getElementById('cameraActivityList');
        const cameraActivityCount = document.getElementById('cameraActivityCount');
        const retryLocationButton = document.getElementById('retryLocationButton');
        const restartScannerButton = document.getElementById('restartScannerButton');
        const enrollmentBanner = document.getElementById('enrollmentBanner');
        const enrollmentBannerCopy = document.getElementById('enrollmentBannerCopy');
        const openEnrollmentModalButton = document.getElementById('openEnrollmentModalButton');
        const kioskFlashModal = document.getElementById('kioskFlashModal');
        const kioskFlashCard = document.getElementById('kioskFlashCard');
        const kioskFlashTitle = document.getElementById('kioskFlashTitle');
        const kioskFlashCopy = document.getElementById('kioskFlashCopy');
        const kioskFlashMeta = document.getElementById('kioskFlashMeta');
        const kioskFlashIcon = document.getElementById('kioskFlashIcon');

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

        if (faceEnrollmentModalEl && faceEnrollmentModalEl.parentElement !== document.body) {
            document.body.appendChild(faceEnrollmentModalEl);
        }

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
        let enrollmentCameraReady = false;
        let attendancePreviewFrame = null;
        let enrollmentPreviewFrame = null;
        let flashTimer = null;
        let attendanceActivities = Array.isArray(initialAttendanceActivities) ? initialAttendanceActivities.slice() : [];

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
            updateEnrollmentActionState();
        }

        function updateEnrollmentActionState() {
            if (!startEnrollmentButton) {
                return;
            }

            startEnrollmentButton.disabled = enrollmentBusy || !selectedEnrollmentTeacher;
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

            const currentOption = enrollmentTeacherSelect.options[enrollmentTeacherSelect.selectedIndex];
            if (!currentOption || currentOption.hidden) {
                const firstVisible = Array.from(enrollmentTeacherSelect.options).find((option) => !option.hidden);
                if (firstVisible) {
                    enrollmentTeacherSelect.value = firstVisible.value;
                }
            }

            updateEnrollmentTeacherSelection();
        }

        function stopCurrentCamera() {
            faceRecognition.stopCamera(cameraMode === 'enrollment' ? enrollmentVideo : video);
            stopLivePreview(cameraMode === 'enrollment' ? 'enrollment' : 'attendance');
            cameraReady = false;
            if (cameraMode === 'enrollment') {
                enrollmentCameraReady = false;
            }
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

        function stopLivePreview(mode = 'attendance') {
            const isEnrollment = mode === 'enrollment';
            const canvasElement = isEnrollment ? enrollmentCanvas : canvas;
            const frameId = isEnrollment ? enrollmentPreviewFrame : attendancePreviewFrame;

            if (frameId) {
                window.cancelAnimationFrame(frameId);
            }

            if (isEnrollment) {
                enrollmentPreviewFrame = null;
            } else {
                attendancePreviewFrame = null;
            }

            if (!canvasElement) {
                return;
            }

            canvasElement.classList.remove('is-live');

            const context = canvasElement.getContext('2d');
            if (context) {
                context.clearRect(0, 0, canvasElement.width, canvasElement.height);
            }
        }

        function startLivePreview(videoElement, canvasElement, mode = 'attendance') {
            stopLivePreview(mode);

            if (!videoElement || !canvasElement) {
                return;
            }

            const context = canvasElement.getContext('2d', { alpha: false });
            if (!context) {
                return;
            }

            const render = function () {
                if (!videoElement.srcObject || videoElement.readyState < 2) {
                    const pendingFrame = window.requestAnimationFrame(render);
                    if (mode === 'enrollment') {
                        enrollmentPreviewFrame = pendingFrame;
                    } else {
                        attendancePreviewFrame = pendingFrame;
                    }
                    return;
                }

                const width = videoElement.videoWidth || canvasElement.clientWidth || 1280;
                const height = videoElement.videoHeight || canvasElement.clientHeight || 720;

                if (canvasElement.width !== width || canvasElement.height !== height) {
                    canvasElement.width = width;
                    canvasElement.height = height;
                }

                context.drawImage(videoElement, 0, 0, width, height);
                canvasElement.classList.add('is-live');

                const nextFrame = window.requestAnimationFrame(render);
                if (mode === 'enrollment') {
                    enrollmentPreviewFrame = nextFrame;
                } else {
                    attendancePreviewFrame = nextFrame;
                }
            };

            render();
        }

        function wait(ms) {
            return new Promise((resolve) => window.setTimeout(resolve, ms));
        }

        function modeLabel(mode) {
            return mode === 'keluar' ? 'Presensi Pulang' : 'Presensi Masuk';
        }

        function updateActivityCount() {
            if (!cameraActivityCount) {
                return;
            }

            cameraActivityCount.textContent = String(attendanceActivities.length);
        }

        function renderAttendanceActivities() {
            if (!cameraActivityList) {
                return;
            }

            if (!attendanceActivities.length) {
                cameraActivityList.innerHTML = '<div class="camera-activity-empty">Belum ada guru yang berhasil melakukan presensi hari ini.</div>';
                updateActivityCount();
                return;
            }

            cameraActivityList.innerHTML = attendanceActivities
                .map(function (item) {
                    const avatar = item.avatar_url
                        ? `<img src="${item.avatar_url}" alt="${item.teacher_name || 'Guru'}" loading="lazy" decoding="async">`
                        : '<i class="bx bx-user"></i>';
                    const latestTime = item.latest_mode === 'keluar'
                        ? (item.pulang?.time || '--:--')
                        : (item.masuk?.time || '--:--');

                    return `
                        <article class="camera-activity-item" data-mode="${item.latest_mode || 'masuk'}">
                            <div class="camera-activity-avatar">${avatar}</div>
                            <div class="camera-activity-body">
                                <div class="camera-activity-topline">
                                    <div class="camera-activity-name">${item.teacher_name || 'Guru'}</div>
                                    <div class="camera-activity-time">${latestTime}</div>
                                </div>
                                <div class="camera-activity-grid">
                                    <div class="camera-activity-stat">
                                        <div class="camera-activity-stat-label">${item.masuk?.label || 'Presensi Masuk'}</div>
                                        <div class="camera-activity-stat-time">${item.masuk?.time || '--:--'}</div>
                                    </div>
                                    <div class="camera-activity-stat">
                                        <div class="camera-activity-stat-label">${item.pulang?.label || 'Presensi Pulang'}</div>
                                        <div class="camera-activity-stat-time">${item.pulang?.time || '--:--'}</div>
                                    </div>
                                </div>
                                <div class="camera-activity-note">${item.note || ''}</div>
                            </div>
                        </article>
                    `;
                })
                .join('');

            updateActivityCount();
        }

        function upsertAttendanceActivity(activity) {
            if (!activity || !activity.id) {
                return;
            }

            attendanceActivities = [
                activity,
                ...attendanceActivities.filter(function (item) {
                    return item.id !== activity.id;
                }),
            ].slice(0, 14);

            renderAttendanceActivities();
        }

        function hideFlashModal() {
            if (flashTimer) {
                window.clearTimeout(flashTimer);
                flashTimer = null;
            }

            if (kioskFlashModal) {
                kioskFlashModal.hidden = true;
            }
        }

        function showFlashModal(options) {
            if (!kioskFlashModal || !kioskFlashCard) {
                return;
            }

            const tone = options?.tone === 'warning' ? 'warning' : 'success';
            kioskFlashCard.classList.remove('is-success', 'is-warning');
            kioskFlashCard.classList.add(tone === 'warning' ? 'is-warning' : 'is-success');
            kioskFlashIcon.innerHTML = `<i class="bx ${tone === 'warning' ? 'bx-error-circle' : 'bx-check-circle'}"></i>`;
            kioskFlashTitle.textContent = options?.title || 'Informasi Presensi';
            kioskFlashCopy.textContent = options?.copy || '';
            kioskFlashMeta.textContent = options?.meta || '';
            kioskFlashModal.hidden = false;

            if (flashTimer) {
                window.clearTimeout(flashTimer);
            }

            flashTimer = window.setTimeout(hideFlashModal, options?.duration ?? 2400);
        }

        function createRequestError(payload, fallbackMessage) {
            const error = new Error(payload?.message || fallbackMessage);
            error.statusCode = payload?.status_code || 'request_failed';
            error.payload = payload || {};

            return error;
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
                startLivePreview(video, canvas, 'attendance');
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
                throw createRequestError(result, 'Presensi otomatis gagal diproses.');
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
            if (result.attendance_activity) {
                upsertAttendanceActivity(result.attendance_activity);
            }

            const note = result.attendance_note || '';
            const modeLabelText = result.mode === 'keluar' ? 'presensi pulang' : 'presensi masuk';
            showFlashModal({
                tone: 'success',
                title: 'Presensi Berhasil',
                copy: `${result.teacher?.name || 'Guru'} berhasil melakukan ${modeLabelText}.`,
                meta: note !== '' ? note : (result.message || 'Presensi berhasil direkam.'),
                duration: 2600,
            });

            showAttendanceResult(
                result.teacher?.name || 'Presensi berhasil',
                `${result.message || 'Presensi berhasil direkam.'} ${note ? note + '. ' : ''}${result.presensi?.waktu_masuk ? 'Masuk: ' + result.presensi.waktu_masuk + '.' : ''} ${result.presensi?.waktu_keluar ? 'Keluar: ' + result.presensi.waktu_keluar + '.' : ''}`.trim()
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
                if (error.statusCode === 'attendance_checkout_too_early') {
                    showFlashModal({
                        tone: 'warning',
                        title: 'Presensi Pulang Belum Bisa',
                        copy: message,
                        meta: 'Silakan lakukan presensi pulang sesuai jam sekolah yang sudah ditentukan.',
                        duration: 3200,
                    });
                }
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

        async function startEnrollmentCameraPreview() {
            if (enrollmentCameraReady) {
                return;
            }

            stopCurrentCamera();
            cameraMode = 'enrollment';
            enrollmentPreview.classList.remove('show');
            enrollmentPlaceholder.style.display = 'none';
            enrollmentStatusTitle.textContent = 'Menyalakan kamera';
            enrollmentStatusCopy.textContent = 'Kamera registrasi sedang diaktifkan.';
            enrollmentGuideText.textContent = 'Siapkan wajah di dalam oval.';

            if (!faceEngineUsesPython) {
                await faceRecognition.loadModels();
            }

            await faceRecognition.initializeCamera(enrollmentVideo);
            startLivePreview(enrollmentVideo, enrollmentCanvas, 'enrollment');
            enrollmentCameraReady = true;
            enrollmentStatusTitle.textContent = 'Kamera siap';
            enrollmentStatusCopy.textContent = selectedEnrollmentTeacher
                ? `Kamera aktif. Klik scan untuk menyimpan wajah ${selectedEnrollmentTeacher.name}.`
                : 'Kamera aktif. Pilih guru lalu klik scan dan simpan.';
            enrollmentGuideText.textContent = 'Posisikan satu wajah tepat di dalam oval.';
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
            updateEnrollmentActionState();
            startEnrollmentButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Memproses...';
            enrollmentPreview.classList.remove('show');
            enrollmentPlaceholder.style.display = 'none';
            enrollmentStatusTitle.textContent = 'Memulai scan';
            enrollmentStatusCopy.textContent = 'Sistem sedang menyiapkan pengambilan wajah.';
            enrollmentGuideText.textContent = 'Memulai scan wajah.';

            try {
                await startEnrollmentCameraPreview();
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
                stopLivePreview('enrollment');
                enrollmentCameraReady = false;
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
                stopLivePreview('enrollment');
                enrollmentCameraReady = false;
                enrollmentPlaceholder.style.display = 'flex';
                enrollmentStatusTitle.textContent = 'Registrasi gagal';
                enrollmentStatusCopy.textContent = error.message || 'Registrasi wajah belum berhasil. Ulangi proses dan pastikan wajah berada di dalam oval.';
                enrollmentGuideText.textContent = 'Registrasi gagal. Ulangi proses scan wajah.';
            } finally {
                enrollmentBusy = false;
                updateEnrollmentActionState();
                startEnrollmentButton.innerHTML = '<i class="bx bx-camera me-1"></i>Scan dan Simpan';
            }
        }

        openEnrollmentModalButton?.addEventListener('click', function () {
            clearScanTimer();
            stopCurrentCamera();
            cameraMode = 'enrollment';
            enrollmentPreview.classList.remove('show');
            enrollmentPlaceholder.style.display = 'flex';
            enrollmentStatusTitle.textContent = 'Status Registrasi';
            enrollmentStatusCopy.textContent = 'Modal registrasi dibuka. Kamera akan disiapkan otomatis.';
            enrollmentGuideText.textContent = 'Menyiapkan kamera registrasi.';

            const firstVisible = Array.from(enrollmentTeacherSelect.options).find((option) => !option.hidden);
            if (firstVisible) {
                enrollmentTeacherSelect.value = firstVisible.value;
            }

            updateEnrollmentTeacherSelection();
            updateEnrollmentActionState();

            if (faceEnrollmentModal) {
                faceEnrollmentModal.show();
            }
        });

        faceEnrollmentModalEl?.addEventListener('shown.bs.modal', function () {
            document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                backdrop.classList.add('face-modal-backdrop');
            });

            startEnrollmentCameraPreview().catch(function (error) {
                enrollmentPlaceholder.style.display = 'flex';
                enrollmentStatusTitle.textContent = 'Kamera gagal';
                enrollmentStatusCopy.textContent = error.message || 'Kamera registrasi belum bisa diaktifkan.';
                enrollmentGuideText.textContent = 'Izinkan kamera lalu coba lagi.';
                enrollmentCameraReady = false;
            });
        });

        faceEnrollmentModalEl?.addEventListener('hidden.bs.modal', function () {
            faceRecognition.stopCamera(enrollmentVideo);
            stopLivePreview('enrollment');
            enrollmentCameraReady = false;
            enrollmentPreview.classList.remove('show');
            enrollmentPlaceholder.style.display = 'flex';
            document.querySelectorAll('.face-modal-backdrop').forEach(function (backdrop) {
                backdrop.classList.remove('face-modal-backdrop');
            });
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

        kioskFlashModal?.addEventListener('click', function (event) {
            if (event.target === kioskFlashModal) {
                hideFlashModal();
            }
        });

        renderAttendanceActivities();
        updateEnrollmentBanner();
        updateEnrollmentTeacherSelection();
        bootstrapAutomation();
    })();
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/kiosk/school-kiosk.blade.php ENDPATH**/ ?>