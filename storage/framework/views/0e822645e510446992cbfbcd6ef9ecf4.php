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
        border: 0;
        border-radius: 0;
        padding: 0;
        background: transparent;
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
        position: absolute;
        top: 18px;
        left: 18px;
        z-index: 6;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        width: var(--camera-side-panel-width);
        pointer-events: none;
    }

    .camera-panel-copy-wrap {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        padding: 14px;
        border-radius: 22px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(15, 23, 42, 0.42);
        backdrop-filter: blur(16px);
        box-shadow: 0 18px 38px rgba(2, 6, 23, 0.18);
        pointer-events: auto;
    }

    .camera-panel-actions {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        flex-wrap: wrap;
        pointer-events: auto;
    }

    .camera-panel-footer {
        position: absolute;
        left: 18px;
        bottom: 18px;
        z-index: 6;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        pointer-events: none;
    }

    .camera-panel-footer > * {
        pointer-events: auto;
    }

    .camera-panel-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        border: 1px solid rgba(250, 204, 21, 0.34);
        background: rgba(15, 23, 42, 0.62);
        color: #fde68a;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        backdrop-filter: blur(14px);
        box-shadow: 0 14px 28px rgba(2, 6, 23, 0.2);
        transition: background 0.18s ease, border-color 0.18s ease, transform 0.18s ease, color 0.18s ease;
    }

    .camera-panel-button:hover,
    .camera-panel-button:focus {
        background: rgba(30, 41, 59, 0.84);
        border-color: rgba(250, 204, 21, 0.5);
        color: #fef3c7;
        transform: translateY(-1px);
    }

    .camera-panel-button i {
        font-size: 15px;
    }

    .camera-panel-title {
        font-size: 22px;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 4px;
        line-height: 1.1;
    }

    .camera-panel-copy {
        color: rgba(226, 232, 240, 0.92);
        font-size: 13px;
        margin-bottom: 0;
        max-width: 100%;
        line-height: 1.5;
        text-shadow: 0 1px 12px rgba(2, 6, 23, 0.32);
    }

    .camera-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        width: 100%;
        pointer-events: auto;
    }

    .camera-summary-lottie-wrap {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 2px;
        pointer-events: auto;
    }

    .camera-summary-lottie {
        width: 168px;
        height: 168px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.94;
        filter: drop-shadow(0 12px 24px rgba(2, 6, 23, 0.18));
    }

    .camera-summary-brand {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 2px;
        text-align: center;
    }

    .camera-summary-logo {
        width: 104px;
        height: 104px;
        border-radius: 26px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 12px 30px rgba(2, 6, 23, 0.12);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f8fafc;
    }

    .camera-summary-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .camera-summary-logo i {
        font-size: 42px;
        opacity: 0.9;
    }

    .camera-summary-card {
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 12px 12px 11px;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        box-shadow: 0 14px 30px rgba(2, 6, 23, 0.12);
    }

    .camera-summary-label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
        color: rgba(226, 232, 240, 0.78);
        line-height: 1.35;
        text-align: center;
        margin-bottom: 0;
    }

    .camera-summary-value {
        display: block;
        font-size: 13px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.4;
        text-align: center;
        word-break: break-word;
    }

    .camera-summary-card.is-text .camera-summary-value {
        font-size: 13px;
        font-weight: 800;
        color: #ffffff;
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
        background: rgba(236, 254, 255, 0.92);
        border: 1px solid rgba(153, 246, 228, 0.96);
        box-shadow: 0 14px 30px rgba(2, 6, 23, 0.22);
    }

    .camera-shell {
        --camera-side-panel-width: min(320px, 28%);
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

    .camera-match-hud {
        position: absolute;
        left: 50%;
        top: 56%;
        transform: translate(-50%, -50%);
        width: 176px;
        height: 176px;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 5;
    }

    .camera-match-hud.is-visible {
        display: flex;
    }

    .camera-match-ring {
        --match-progress: 0%;
        --match-color: #38bdf8;
        width: 100%;
        height: 100%;
        border-radius: 999px;
        padding: 10px;
        background:
            radial-gradient(circle at center, rgba(15, 23, 42, 0.16) 0 58%, transparent 59%),
            conic-gradient(var(--match-color) var(--match-progress), rgba(255, 255, 255, 0.14) 0);
        box-shadow:
            0 18px 44px rgba(2, 6, 23, 0.34),
            inset 0 0 24px rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
    }

    .camera-match-ring[data-tone="success"] {
        --match-color: #22c55e;
    }

    .camera-match-ring[data-tone="danger"] {
        --match-color: #f97316;
    }

    .camera-match-core {
        width: 100%;
        height: 100%;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.78);
        border: 1px solid rgba(255, 255, 255, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        color: #fff;
        padding: 18px;
    }

    .camera-match-percent {
        font-size: 34px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-bottom: 8px;
    }

    .camera-match-label {
        font-size: 12px;
        line-height: 1.45;
        font-weight: 700;
        color: rgba(226, 232, 240, 0.92);
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

    .camera-guide-copy {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        min-width: 0;
    }

    .camera-guide-label {
        font-size: 10px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(186, 230, 253, 0.92);
    }

    .camera-guide-text {
        line-height: 1.45;
    }

    .camera-activity-panel {
        position: absolute;
        top: 20px;
        right: 20px;
        bottom: 20px;
        width: var(--camera-side-panel-width);
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
        align-items: center;
        gap: 12px;
        border-radius: 18px;
        padding: 12px 12px 11px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.08);
        color: #f8fafc;
        cursor: pointer;
        transition: transform 0.18s ease, background 0.18s ease, border-color 0.18s ease;
    }

    .camera-activity-item:hover {
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.16);
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
        text-align: center;
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

    .attendance-detail-modal .modal-content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    .attendance-detail-modal {
        z-index: 2055 !important;
    }

    .attendance-detail-modal .modal-dialog {
        max-width: min(1040px, calc(100vw - 40px));
        margin: 1rem auto;
        pointer-events: auto;
    }

    .attendance-detail-backdrop {
        z-index: 2050 !important;
        background: rgba(15, 23, 42, 0.24) !important;
        opacity: 1 !important;
    }

    .attendance-detail-modal .modal-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .attendance-detail-modal .modal-body {
        padding: 18px 22px 22px;
    }

    .attendance-detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 14px;
    }

    .attendance-detail-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 14px;
        background: #fff;
    }

    .attendance-detail-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 8px;
    }

    .attendance-detail-time {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .attendance-detail-photo {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 16px;
        overflow: hidden;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .attendance-detail-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .attendance-detail-empty {
        text-align: center;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
        padding: 16px;
    }

    .attendance-detail-summary {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0 18px;
    }

    .attendance-detail-avatar {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        overflow: hidden;
        background: #eff6ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        flex: 0 0 auto;
    }

    .attendance-detail-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .attendance-detail-avatar i {
        font-size: 24px;
    }

    .attendance-detail-name {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .attendance-detail-meta {
        color: #64748b;
        font-size: 13px;
    }

    .attendance-detail-keterangan {
        margin-top: 14px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        color: #334155;
        font-size: 13px;
        line-height: 1.6;
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

    .enroll-quality-box {
        margin-top: 10px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 12px 14px;
    }

    .enroll-progress {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 12px;
    }

    .enroll-progress-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 10px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-align: center;
        transition: all 0.18s ease;
    }

    .enroll-progress-item.is-active {
        border-color: #67e8f9;
        background: #ecfeff;
        color: #0f766e;
    }

    .enroll-progress-item.is-done {
        border-color: #86efac;
        background: #f0fdf4;
        color: #166534;
    }

    .enroll-quality-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }

    .enroll-quality-title {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
    }

    .enroll-quality-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 800;
    }

    .enroll-quality-chip[data-tone="warning"] {
        background: #fef3c7;
        color: #92400e;
    }

    .enroll-quality-chip[data-tone="success"] {
        background: #dcfce7;
        color: #166534;
    }

    .enroll-quality-bar {
        height: 10px;
        border-radius: 999px;
        overflow: hidden;
        background: #e2e8f0;
        margin-bottom: 8px;
    }

    .enroll-quality-fill {
        width: 0%;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #f97316 0%, #facc15 52%, #22c55e 100%);
        transition: width 0.18s ease;
    }

    .enroll-quality-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .enroll-quality-value {
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
    }

    .enroll-quality-copy {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
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
            --camera-side-panel-width: min(290px, 34%);
            min-height: 340px;
        }

        .enroll-controls {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .camera-shell {
            min-height: 260px;
        }

        .camera-panel-header {
            top: 12px;
            left: 12px;
            gap: 10px;
            width: calc(100% - 24px);
            max-width: 320px;
        }

        .camera-panel-footer {
            left: 12px;
            right: 12px;
            bottom: calc(38% + 22px);
            gap: 8px;
        }

        .camera-panel-actions {
            width: 100%;
        }

        .camera-panel-title {
            font-size: 17px;
        }

        .camera-panel-copy {
            max-width: none;
            font-size: 12px;
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

        .camera-summary-grid {
            gap: 8px;
        }

        .camera-summary-card {
            padding: 10px 12px;
            border-radius: 16px;
        }

        .camera-summary-logo {
            width: 84px;
            height: 84px;
            border-radius: 22px;
        }

        .camera-summary-lottie {
            width: 132px;
            height: 132px;
        }

        .camera-panel-copy-wrap {
            padding: 12px;
            border-radius: 18px;
        }

        .camera-summary-value {
            font-size: 14px;
        }

        .scan-badge,
        .camera-panel-button {
            width: auto;
            max-width: 100%;
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

        .attendance-detail-grid {
            grid-template-columns: 1fr;
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
                            <div class="camera-shell">
                                <video id="cameraVideo" class="camera-video" autoplay playsinline muted></video>
                                <img id="cameraPreview" class="camera-preview" alt="Preview scan wajah">
                                <canvas id="cameraCanvas"></canvas>

                                <div class="camera-panel-header">
                                    <div class="camera-panel-copy-wrap">
                                        <div class="camera-panel-title">Kiosk Kamera Presensi Kehadiran</div>
                                        <p class="camera-panel-copy" id="cameraPanelCopy">
                                            Setelah lokasi valid, kamera aktif otomatis. Guru cukup berdiri di depan kamera dan mengikuti instruksi singkat.
                                        </p>
                                        <div class="camera-summary-brand">
                                            <div class="camera-summary-logo">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($attendanceSummary['school_logo_url'])): ?>
                                                    <img src="<?php echo e($attendanceSummary['school_logo_url']); ?>" alt="<?php echo e($attendanceSummary['school_name'] ?? 'Sekolah'); ?>" loading="lazy" decoding="async">
                                                <?php else: ?>
                                                    <i class="bx bxs-school"></i>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="camera-summary-grid">
                                            <div class="camera-summary-card is-text">
                                                <span class="camera-summary-label">Nama Sekolah</span>
                                                <span class="camera-summary-value" id="summarySchoolName"><?php echo e($attendanceSummary['school_name'] ?? '-'); ?></span>
                                            </div>
                                            <div class="camera-summary-card">
                                                <span class="camera-summary-label">Guru & Pegawai</span>
                                                <span class="camera-summary-value" id="summaryTotalPeople"><?php echo e($attendanceSummary['total_people'] ?? 0); ?></span>
                                            </div>
                                            <div class="camera-summary-card">
                                                <span class="camera-summary-label">Sudah Presensi</span>
                                                <span class="camera-summary-value" id="summaryPresentCount"><?php echo e($attendanceSummary['present_count'] ?? 0); ?></span>
                                            </div>
                                            <div class="camera-summary-card">
                                                <span class="camera-summary-label">Belum Presensi</span>
                                                <span class="camera-summary-value" id="summaryNotPresentCount"><?php echo e($attendanceSummary['not_present_count'] ?? 0); ?></span>
                                            </div>
                                            <div class="camera-summary-card">
                                                <span class="camera-summary-label">Izin</span>
                                                <span class="camera-summary-value" id="summaryIzinCount"><?php echo e($attendanceSummary['izin_count'] ?? 0); ?></span>
                                            </div>
                                            <div class="camera-summary-card">
                                                <span class="camera-summary-label">Persentase Kehadiran</span>
                                                <span class="camera-summary-value" id="summaryAttendancePercentage"><?php echo e(number_format((float) ($attendanceSummary['attendance_percentage'] ?? 0), 1)); ?>%</span>
                                            </div>
                                        </div>
                                        <div class="camera-summary-lottie-wrap">
                                            <div class="camera-summary-lottie" id="cameraSummaryLottie" aria-hidden="true"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="camera-panel-footer">
                                    <div class="camera-panel-actions">
                                        <button type="button" class="camera-panel-button" id="openEnrollmentRefreshButton">
                                            <i class="bx bx-refresh"></i>Registrasi Ulang
                                        </button>
                                        <div class="scan-badge" id="scanBadge">
                                            <i class="bx bx-loader-circle"></i>
                                            <span>Menyiapkan</span>
                                        </div>
                                    </div>
                                </div>

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
                                    <div class="camera-match-hud" id="cameraMatchHud" aria-hidden="true">
                                        <div class="camera-match-ring" id="cameraMatchRing" data-tone="info">
                                            <div class="camera-match-core">
                                                <div class="camera-match-percent" id="cameraMatchPercent">0%</div>
                                                <div class="camera-match-label" id="cameraMatchLabel">Menunggu wajah</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="camera-guide-pill" id="cameraGuidePill">
                                        <i class="bx bx-scan"></i>
                                        <div class="camera-guide-copy">
                                            <span class="camera-guide-label" id="cameraGuideLabel">Instruksi</span>
                                            <span class="camera-guide-text" id="cameraGuideText">Menyiapkan School Kiosk.</span>
                                        </div>
                                    </div>
                                </div>

                                <aside class="camera-activity-panel">
                                    <div class="camera-activity-header">
                                        <div>
                                            <div class="camera-activity-title">Data Presensi Hari Ini</div>
                                            
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

                <div class="modal fade attendance-detail-modal" id="attendanceDetailModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <h5 class="modal-title mb-1">Detail Presensi Hari Ini</h5>
                                    <div class="text-muted small">Data presensi guru dan foto scan yang berhasil diambil.</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="attendance-detail-summary">
                                    <div class="attendance-detail-avatar" id="attendanceDetailAvatar">
                                        <i class="bx bx-user"></i>
                                    </div>
                                    <div>
                                        <div class="attendance-detail-name" id="attendanceDetailName">Guru</div>
                                        <div class="attendance-detail-meta" id="attendanceDetailMeta">Presensi hari ini</div>
                                    </div>
                                </div>

                                <div class="attendance-detail-grid">
                                    <div class="attendance-detail-card">
                                        <div class="attendance-detail-label">Presensi Masuk</div>
                                        <div class="attendance-detail-time" id="attendanceDetailMasukTime">--:--</div>
                                        <div class="attendance-detail-photo" id="attendanceDetailMasukPhoto"></div>
                                    </div>
                                    <div class="attendance-detail-card">
                                        <div class="attendance-detail-label">Presensi Pulang</div>
                                        <div class="attendance-detail-time" id="attendanceDetailPulangTime">--:--</div>
                                        <div class="attendance-detail-photo" id="attendanceDetailPulangPhoto"></div>
                                    </div>
                                </div>

                                <div class="attendance-detail-keterangan" id="attendanceDetailKeterangan"></div>
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
                                        <p>Kamera aktif otomatis. Setelah guru dipilih, mulai scan wajah, periksa hasilnya, lalu simpan seperti pada face enrollment mobile.</p>

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

                                        <div class="enroll-quality-box">
                                            <div class="enroll-progress" id="enrollmentProgress">
                                                <div class="enroll-progress-item" data-step="align">Posisikan</div>
                                                <div class="enroll-progress-item" data-step="steady">Stabilkan</div>
                                                <div class="enroll-progress-item" data-step="done">Selesai</div>
                                            </div>
                                            <div class="enroll-quality-head">
                                                <div class="enroll-quality-title">Level Kejelasan Wajah</div>
                                                <div class="enroll-quality-chip" id="enrollmentQualityChip" data-tone="idle">Belum jelas</div>
                                            </div>
                                            <div class="enroll-quality-bar" aria-hidden="true">
                                                <div class="enroll-quality-fill" id="enrollmentQualityFill"></div>
                                            </div>
                                            <div class="enroll-quality-meta">
                                                <div class="enroll-quality-value" id="enrollmentQualityValue">0%</div>
                                                <div class="enroll-quality-copy" id="enrollmentQualityCopy">Posisikan wajah di dalam oval. Saat wajah sudah jelas dan stabil, sistem akan otomatis mengambil dan menyimpan data.</div>
                                            </div>
                                        </div>

                                        <div class="enroll-actions">
                                            <button type="button" class="btn btn-primary" id="startEnrollmentButton">
                                                <i class="bx bx-camera me-1"></i>Mulai Scan
                                            </button>
                                            <button type="button" class="btn btn-success" id="saveEnrollmentButton" hidden disabled>
                                                <i class="bx bx-save me-1"></i>Simpan Wajah
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
<script src="<?php echo e(asset('js/vendor/lottie.min.js')); ?>"></script>
<script src="<?php echo e(asset('models/face-api.js')); ?>"></script>
<script src="<?php echo e(asset('js/face-recognition.js')); ?>"></script>
<script>
    (function () {
        const teachers = <?php echo json_encode($teachersPayload, 15, 512) ?>;
        const initialAttendanceActivities = <?php echo json_encode($attendanceActivities, 15, 512) ?>;
        const initialAttendanceSummary = <?php echo json_encode($attendanceSummary, 15, 512) ?>;
        const summaryLottieData = <?php echo json_encode(json_decode(file_get_contents(public_path('animations/school-kiosk-face-scan.json')), true), 512) ?>;
        const verificationMode = <?php echo json_encode($verificationMode, 15, 512) ?>;
        const faceEngineDriver = <?php echo json_encode($faceEngineDriver, 15, 512) ?>;
        const faceEngineLabel = <?php echo json_encode($faceEngineLabel, 15, 512) ?>;
        const faceEngineUsesPython = <?php echo json_encode($faceEngineUsesPython, 15, 512) ?>;
        const initialScanDelayMs = 420;
        const retryScanDelayMs = 1100;
        const successScanDelayMs = 3600;
        const locationCheckUrl = <?php echo json_encode(route('school-kiosk.check-location'), 15, 512) ?>;
        const verifyFaceMatchUrl = <?php echo json_encode(route('school-kiosk.verify-face-match'), 15, 512) ?>;
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
        const cameraGuideIcon = document.querySelector('#cameraGuidePill i');
        const cameraGuideLabel = document.getElementById('cameraGuideLabel');
        const cameraGuideText = document.getElementById('cameraGuideText');
        const cameraMatchHud = document.getElementById('cameraMatchHud');
        const cameraMatchRing = document.getElementById('cameraMatchRing');
        const cameraMatchPercent = document.getElementById('cameraMatchPercent');
        const cameraMatchLabel = document.getElementById('cameraMatchLabel');
        const attendanceResultCard = document.getElementById('attendanceResultCard');
        const attendanceResultTitle = document.getElementById('attendanceResultTitle');
        const attendanceResultCopy = document.getElementById('attendanceResultCopy');
        const cameraActivityList = document.getElementById('cameraActivityList');
        const cameraActivityCount = document.getElementById('cameraActivityCount');
        const cameraSummaryLottie = document.getElementById('cameraSummaryLottie');
        const summarySchoolName = document.getElementById('summarySchoolName');
        const summaryTotalPeople = document.getElementById('summaryTotalPeople');
        const summaryPresentCount = document.getElementById('summaryPresentCount');
        const summaryNotPresentCount = document.getElementById('summaryNotPresentCount');
        const summaryIzinCount = document.getElementById('summaryIzinCount');
        const summaryAttendancePercentage = document.getElementById('summaryAttendancePercentage');
        const retryLocationButton = document.getElementById('retryLocationButton');
        const restartScannerButton = document.getElementById('restartScannerButton');
        const enrollmentBanner = document.getElementById('enrollmentBanner');
        const enrollmentBannerCopy = document.getElementById('enrollmentBannerCopy');
        const openEnrollmentModalButton = document.getElementById('openEnrollmentModalButton');
        const openEnrollmentRefreshButton = document.getElementById('openEnrollmentRefreshButton');
        const kioskFlashModal = document.getElementById('kioskFlashModal');
        const kioskFlashCard = document.getElementById('kioskFlashCard');
        const kioskFlashTitle = document.getElementById('kioskFlashTitle');
        const kioskFlashCopy = document.getElementById('kioskFlashCopy');
        const kioskFlashMeta = document.getElementById('kioskFlashMeta');
        const kioskFlashIcon = document.getElementById('kioskFlashIcon');
        const attendanceDetailModalEl = document.getElementById('attendanceDetailModal');
        const attendanceDetailModal = window.bootstrap ? new bootstrap.Modal(attendanceDetailModalEl) : null;
        const attendanceDetailAvatar = document.getElementById('attendanceDetailAvatar');
        const attendanceDetailName = document.getElementById('attendanceDetailName');
        const attendanceDetailMeta = document.getElementById('attendanceDetailMeta');
        const attendanceDetailMasukTime = document.getElementById('attendanceDetailMasukTime');
        const attendanceDetailPulangTime = document.getElementById('attendanceDetailPulangTime');
        const attendanceDetailMasukPhoto = document.getElementById('attendanceDetailMasukPhoto');
        const attendanceDetailPulangPhoto = document.getElementById('attendanceDetailPulangPhoto');
        const attendanceDetailKeterangan = document.getElementById('attendanceDetailKeterangan');

        const enrollmentTeacherSearchInput = document.getElementById('enrollmentTeacherSearchInput');
        const enrollmentTeacherSelect = document.getElementById('enrollmentTeacherSelect');
        const teacherStateChip = document.getElementById('teacherStateChip');
        const teacherStateCopy = document.getElementById('teacherStateCopy');
        const faceEnrollmentModalEl = document.getElementById('faceEnrollmentModal');
        const faceEnrollmentModal = window.bootstrap ? new bootstrap.Modal(faceEnrollmentModalEl) : null;
        const startEnrollmentButton = document.getElementById('startEnrollmentButton');
        const saveEnrollmentButton = document.getElementById('saveEnrollmentButton');
        const enrollmentVideo = document.getElementById('enrollmentVideo');
        const enrollmentPreview = document.getElementById('enrollmentPreview');
        const enrollmentCanvas = document.getElementById('enrollmentCanvas');
        const enrollmentPlaceholder = document.getElementById('enrollmentPlaceholder');
        const enrollmentGuideText = document.getElementById('enrollmentGuideText');
        const enrollmentStatusTitle = document.getElementById('enrollmentStatusTitle');
        const enrollmentStatusCopy = document.getElementById('enrollmentStatusCopy');
        const enrollmentProgressItems = Array.from(document.querySelectorAll('#enrollmentProgress .enroll-progress-item'));
        const enrollmentQualityChip = document.getElementById('enrollmentQualityChip');
        const enrollmentQualityFill = document.getElementById('enrollmentQualityFill');
        const enrollmentQualityValue = document.getElementById('enrollmentQualityValue');
        const enrollmentQualityCopy = document.getElementById('enrollmentQualityCopy');

        if (faceEnrollmentModalEl && faceEnrollmentModalEl.parentElement !== document.body) {
            document.body.appendChild(faceEnrollmentModalEl);
        }

        if (attendanceDetailModalEl && attendanceDetailModalEl.parentElement !== document.body) {
            document.body.appendChild(attendanceDetailModalEl);
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
        let matchHudTimer = null;
        let browserFaceScanAvailable = false;
        let faceMustExitBeforeNextMatch = false;
        let cameraGuideLock = null;
        let matchedTeacherCandidate = null;
        let pendingEnrollmentResult = null;
        let attendanceActivities = Array.isArray(initialAttendanceActivities) ? initialAttendanceActivities.slice() : [];
        let attendanceSummary = initialAttendanceSummary && typeof initialAttendanceSummary === 'object'
            ? { ...initialAttendanceSummary }
            : {
                school_name: '-',
                total_people: 0,
                present_count: 0,
                not_present_count: 0,
                izin_count: 0,
                attendance_percentage: 0,
            };

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

        function clearMatchHudTimer() {
            if (matchHudTimer) {
                window.clearInterval(matchHudTimer);
                matchHudTimer = null;
            }
        }

        function updateMatchHud(progress, label, tone = 'info') {
            if (!cameraMatchHud || !cameraMatchRing || !cameraMatchPercent || !cameraMatchLabel) {
                return;
            }

            const normalized = Math.max(0, Math.min(Math.round(progress), 100));
            cameraMatchHud.classList.add('is-visible');
            cameraMatchRing.dataset.tone = tone;
            cameraMatchRing.style.setProperty('--match-progress', `${normalized}%`);
            cameraMatchPercent.textContent = `${normalized}%`;
            cameraMatchLabel.textContent = label;
        }

        function hideMatchHud() {
            clearMatchHudTimer();
            cameraMatchHud?.classList.remove('is-visible');
        }

        function startMatchHud(label = 'Mencocokkan data') {
            clearMatchHudTimer();
            let progress = 18;
            updateMatchHud(progress, label, 'info');

            matchHudTimer = window.setInterval(function () {
                progress = Math.min(progress + (progress < 54 ? 9 : 4), 88);
                updateMatchHud(progress, label, 'info');

                if (progress >= 88) {
                    clearMatchHudTimer();
                }
            }, 120);
        }

        function finishMatchHud(success, label) {
            clearMatchHudTimer();
            updateMatchHud(100, label, success ? 'success' : 'danger');
        }

        function isChallengeInstruction(message) {
            const normalized = String(message || '').trim().toLowerCase();

            return normalized.includes('kedip')
                || normalized.includes('kiri')
                || normalized.includes('kanan')
                || normalized.includes('atas')
                || normalized.includes('bawah')
                || normalized.includes('mulut');
        }

        function resolveCameraGuidePresentation(message, fallbackIcon = 'bx-scan') {
            const text = String(message || '').trim();
            const normalized = text.toLowerCase();

            if (normalized.includes('kedip')) {
                return { label: 'Challenge Kedip', icon: 'bx-show-alt' };
            }

            if (normalized.includes('kiri')) {
                return { label: 'Challenge Arah', icon: 'bx-left-arrow-alt' };
            }

            if (normalized.includes('kanan')) {
                return { label: 'Challenge Arah', icon: 'bx-right-arrow-alt' };
            }

            if (normalized.includes('atas')) {
                return { label: 'Challenge Arah', icon: 'bx-up-arrow-alt' };
            }

            if (normalized.includes('bawah')) {
                return { label: 'Challenge Arah', icon: 'bx-down-arrow-alt' };
            }

            if (normalized.includes('mulut')) {
                return { label: 'Challenge Mulut', icon: 'bx-user-voice' };
            }

            if (normalized.includes('cocok') || normalized.includes('verifikasi')) {
                return { label: 'Verifikasi', icon: 'bx-check-shield' };
            }

            if (normalized.includes('mengambil gambar') || normalized.includes('diambil')) {
                return { label: 'Pengambilan', icon: 'bx-camera' };
            }

            if (normalized.includes('selesai') || normalized.includes('berhasil')) {
                return { label: 'Selesai', icon: 'bx-check-circle' };
            }

            if (normalized.includes('posisikan') || normalized.includes('oval') || normalized.includes('wajah')) {
                return { label: 'Posisikan', icon: 'bx-scan' };
            }

            return { label: 'Instruksi', icon: fallbackIcon };
        }

        function setCameraGuide(message, icon = 'bx-scan', options = {}) {
            if (cameraGuideLock && options.force !== true) {
                const normalizedMessage = String(message || '').trim();
                if (normalizedMessage !== cameraGuideLock.message) {
                    return;
                }
            }

            const presentation = resolveCameraGuidePresentation(message, icon);
            if (cameraGuideIcon) {
                cameraGuideIcon.className = `bx ${presentation.icon}`;
            }
            if (cameraGuideLabel) {
                cameraGuideLabel.textContent = presentation.label;
            }
            cameraGuideText.textContent = message;
        }

        function lockCameraGuide(message, icon = 'bx-scan') {
            cameraGuideLock = {
                message: String(message || '').trim(),
                icon,
            };

            setCameraGuide(cameraGuideLock.message, icon, { force: true });
        }

        function clearCameraGuideLock() {
            cameraGuideLock = null;
        }

        function setEnrollmentQualityState(level = 0, tone = 'idle', label = 'Belum jelas', copy = 'Posisikan wajah di dalam oval. Saat wajah sudah jelas dan stabil, sistem akan otomatis mengambil dan menyimpan data.') {
            const normalized = Math.max(0, Math.min(Math.round(level), 100));

            if (enrollmentQualityFill) {
                enrollmentQualityFill.style.width = `${normalized}%`;
            }

            if (enrollmentQualityValue) {
                enrollmentQualityValue.textContent = `${normalized}%`;
            }

            if (enrollmentQualityChip) {
                enrollmentQualityChip.dataset.tone = tone;
                enrollmentQualityChip.textContent = label;
            }

            if (enrollmentQualityCopy) {
                enrollmentQualityCopy.textContent = copy;
            }
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

        function resetEnrollmentProgress() {
            enrollmentProgressItems.forEach((item) => item.classList.remove('is-active', 'is-done'));
        }

        function updateEnrollmentProgress(step, state) {
            const item = enrollmentProgressItems.find((entry) => entry.dataset.step === step);
            if (!item) {
                return;
            }

            item.classList.remove('is-active', 'is-done');
            if (state === 'active') {
                item.classList.add('is-active');
            } else if (state === 'done') {
                item.classList.add('is-done');
            }
        }

        function resetEnrollmentCaptureState() {
            pendingEnrollmentResult = null;
            enrollmentPreview.src = '';
            enrollmentPreview.classList.remove('show');
            resetEnrollmentProgress();

            if (saveEnrollmentButton) {
                saveEnrollmentButton.hidden = true;
                saveEnrollmentButton.disabled = true;
                saveEnrollmentButton.innerHTML = '<i class="bx bx-save me-1"></i>Simpan Wajah';
            }
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
            resetEnrollmentCaptureState();
            setTeacherState(selectedEnrollmentTeacher);
            updateEnrollmentActionState();
        }

        function updateEnrollmentActionState() {
            if (!startEnrollmentButton) {
                return;
            }

            startEnrollmentButton.disabled = enrollmentBusy || !selectedEnrollmentTeacher;
            if (!enrollmentBusy) {
                startEnrollmentButton.innerHTML = pendingEnrollmentResult
                    ? '<i class="bx bx-refresh me-1"></i>Scan Ulang'
                    : '<i class="bx bx-camera me-1"></i>Mulai Scan';
            }

            if (saveEnrollmentButton) {
                saveEnrollmentButton.hidden = !pendingEnrollmentResult;
                saveEnrollmentButton.disabled = enrollmentBusy || !selectedEnrollmentTeacher || !pendingEnrollmentResult;
            }
        }

        function setEnrollmentStatus(title, copy) {
            if (enrollmentStatusTitle) {
                enrollmentStatusTitle.textContent = title;
            }

            if (enrollmentStatusCopy) {
                enrollmentStatusCopy.textContent = copy;
            }
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

        function initSummaryLottie() {
            const lottiePlayer = window.lottie || window.bodymovin;
            if (!cameraSummaryLottie || !lottiePlayer || !summaryLottieData) {
                return;
            }

            cameraSummaryLottie.innerHTML = '';

            lottiePlayer.loadAnimation({
                container: cameraSummaryLottie,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                animationData: summaryLottieData,
                rendererSettings: {
                    preserveAspectRatio: 'xMidYMid meet',
                },
            });
        }

        function renderAttendanceSummary() {
            if (!summarySchoolName) {
                return;
            }

            summarySchoolName.textContent = attendanceSummary.school_name || '-';
            summaryTotalPeople.textContent = String(attendanceSummary.total_people || 0);
            summaryPresentCount.textContent = String(attendanceSummary.present_count || 0);
            summaryNotPresentCount.textContent = String(attendanceSummary.not_present_count || 0);
            summaryIzinCount.textContent = String(attendanceSummary.izin_count || 0);

            const percentage = Number(attendanceSummary.attendance_percentage || 0);
            summaryAttendancePercentage.textContent = `${percentage.toFixed(1)}%`;
        }

        function recalculateAttendanceSummary() {
            const totalPeople = Number(attendanceSummary.total_people || 0);
            const izinCount = Number(attendanceSummary.izin_count || 0);
            const presentCount = new Set(
                attendanceActivities
                    .map(function (item) {
                        return item?.teacher_id || null;
                    })
                    .filter(Boolean)
            ).size;
            const notPresentCount = Math.max(totalPeople - presentCount - izinCount, 0);
            const attendancePercentage = totalPeople > 0
                ? Number(((presentCount / totalPeople) * 100).toFixed(1))
                : 0;

            attendanceSummary = {
                ...attendanceSummary,
                present_count: presentCount,
                not_present_count: notPresentCount,
                attendance_percentage: attendancePercentage,
            };

            renderAttendanceSummary();
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
                        <article class="camera-activity-item" data-mode="${item.latest_mode || 'masuk'}" data-presensi-id="${item.presensi_id}">
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
            recalculateAttendanceSummary();
        }

        function buildAttendancePhotoMarkup(url, label) {
            if (!url) {
                return `<div class="attendance-detail-empty">${label} belum tersedia.</div>`;
            }

            return `<img src="${url}" alt="${label}" loading="eager" decoding="async" referrerpolicy="no-referrer">`;
        }

        function openAttendanceDetail(activity) {
            if (!activity || !attendanceDetailModal) {
                return;
            }

            attendanceDetailName.textContent = activity.teacher_name || 'Guru';
            attendanceDetailMeta.textContent = activity.tanggal
                ? `Tanggal ${activity.tanggal}`
                : 'Presensi hari ini';
            attendanceDetailMasukTime.textContent = activity.masuk?.time || '--:--';
            attendanceDetailPulangTime.textContent = activity.pulang?.time || '--:--';
            attendanceDetailMasukPhoto.innerHTML = buildAttendancePhotoMarkup(activity.masuk?.selfie_url || null, 'Foto scan masuk');
            attendanceDetailPulangPhoto.innerHTML = buildAttendancePhotoMarkup(activity.pulang?.selfie_url || null, 'Foto scan pulang');
            attendanceDetailKeterangan.textContent = activity.keterangan && activity.keterangan !== ''
                ? activity.keterangan
                : 'Tidak ada keterangan tambahan untuk presensi hari ini.';

            if (activity.avatar_url) {
                attendanceDetailAvatar.innerHTML = `<img src="${activity.avatar_url}" alt="${activity.teacher_name || 'Guru'}" loading="lazy" decoding="async">`;
            } else {
                attendanceDetailAvatar.innerHTML = '<i class="bx bx-user"></i>';
            }

            attendanceDetailModal.show();
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

        async function prepareBrowserFaceScan() {
            try {
                await faceRecognition.loadModels();
                browserFaceScanAvailable = true;
                return true;
            } catch (error) {
                browserFaceScanAvailable = false;
                throw new Error(error?.message || 'Model scan wajah browser belum dapat dimuat.');
            }
        }

        async function requestKioskFaceMatchVerification(descriptor) {
            const response = await fetch(verifyFaceMatchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    face_descriptor: descriptor,
                }),
            });

            let payload = null;
            try {
                payload = await response.json();
            } catch (error) {
                payload = null;
            }

            if (!response.ok || !payload?.face_verified) {
                return {
                    face_verified: false,
                    message: payload?.message || 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                    notes: payload?.notes || 'face_similarity_below_threshold',
                    similarity: payload?.similarity ?? null,
                };
            }

            return payload;
        }

        function scheduleNextScan(delay = initialScanDelayMs) {
            clearScanTimer();
            scanTimer = window.setTimeout(
                browserFaceScanAvailable ? waitForFaceInOval : runAutomaticFaceScan,
                delay
            );
        }

        async function waitForFaceInOval() {
            if (!cameraReady || scanInProgress || enrollmentBusy || !activeLocation) {
                return;
            }

            if (!browserFaceScanAvailable) {
                runAutomaticFaceScan();
                return;
            }

            const watcherCallbacks = {
                onGuideState: function (payload) {
                    if (payload?.message) {
                        setCameraGuide(payload.message, payload.state === 'success' ? 'bx-check-circle' : 'bx-scan');
                    }
                },
            };

            try {
                const detection = await faceRecognition.detectSingleFaceGeometry(video, watcherCallbacks, {
                    strict: true,
                    allowFallback: false,
                });

                if (faceMustExitBeforeNextMatch) {
                    if (detection) {
                        hideMatchHud();
                        setPrimaryNotice('Menunggu wajah berikutnya', 'Presensi sebelumnya sudah diproses. Geser wajah keluar dari oval sebentar untuk memulai scan berikutnya.');
                        setScanBadge('Menunggu wajah baru', 'warning');
                        setCameraGuide('Geser wajah keluar oval sebentar untuk scan berikutnya.', 'bx-user-x');
                        scheduleNextScan(180);
                        return;
                    }

                    faceMustExitBeforeNextMatch = false;
                }

                if (!detection) {
                    hideMatchHud();
                    setStageState('waiting_user', 'active', 'Kamera siaga dan menunggu wajah masuk ke bingkai.');
                    setPrimaryNotice('Menunggu pengguna', 'Kiosk siaga. Pencocokan baru dimulai saat wajah masuk ke dalam oval.');
                    setScanBadge('Menunggu pengguna', 'warning');
                    scheduleNextScan(120);
                    return;
                }

                setStageState('waiting_user', 'done', 'Wajah terdeteksi di dalam oval. Menyiapkan scan wajah.');
                setStageState('detecting_face', 'active', 'Wajah sudah masuk bingkai. Scan wajah dimulai.');
                setPrimaryNotice('Wajah terdeteksi', 'Wajah sudah masuk ke oval. Sistem langsung memulai scan dan pencocokan data.');
                setScanBadge('Wajah terdeteksi', 'info');
                updateMatchHud(12, 'Wajah terdeteksi', 'info');
                setCameraGuide('Wajah terdeteksi. Memulai scan wajah.', 'bx-check-circle');
                runAutomaticFaceScan({ startedFromOval: true });
            } catch (error) {
                scheduleNextScan(220);
            }
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
                'Sistem sedang menyalakan kamera kiosk dan memuat model scan wajah.'
            );
            setScanBadge('Menyalakan kamera', 'info');
            setCameraGuide('Meminta izin kamera dan memuat model wajah.', 'bx-camera');

            try {
                await faceRecognition.initializeCamera(video);
                await prepareBrowserFaceScan();
                placeholder.style.display = 'none';
                preview.classList.remove('show');
                video.classList.remove('hide');
                startLivePreview(video, canvas, 'attendance');
                cameraReady = true;
                setStageState('camera_permission', 'done', 'Kamera aktif dan siap dipakai untuk presensi otomatis.');
                setPrimaryNotice('Kamera aktif', 'Guru cukup berdiri di depan kamera lalu mengikuti scan wajah singkat seperti pada presensi mobile.');
                setScanBadge('Menunggu pengguna', 'warning');
                setStageState('waiting_user', 'active', 'Kamera siaga dan menunggu wajah masuk ke bingkai.');
                setCameraGuide('Arahkan satu wajah ke dalam oval untuk memulai scan wajah otomatis.', 'bx-user-check');
            } catch (error) {
                faceRecognition.stopCamera(video);
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
            startMatchHud('Mencocokkan data guru');
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

            finishMatchHud(true, 'Diterima');
            faceMustExitBeforeNextMatch = true;

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

        async function runAutomaticFaceScan(options = {}) {
            if (!cameraReady || scanInProgress || enrollmentBusy || !activeLocation) {
                return;
            }

            const startedFromOval = options.startedFromOval === true;
            matchedTeacherCandidate = null;
            clearCameraGuideLock();
            scanInProgress = true;
            if (!startedFromOval) {
                hideMatchHud();
            }
            hideAttendanceResult();
            setStageState('waiting_user', startedFromOval ? 'done' : 'active', startedFromOval
                ? 'Wajah sudah masuk ke bingkai dan scan wajah dimulai.'
                : 'Kamera siaga dan menunggu wajah masuk ke bingkai.');
            setStageState('detecting_face', 'active', 'Sistem mulai membaca posisi wajah dan challenge liveness.');
            setStageState('verifying_identity', 'idle');
            setStageState('processing_attendance', 'idle');
            setStageState('attendance_success', 'idle');
            setPrimaryNotice(
                startedFromOval ? 'Memulai scan wajah' : 'Menunggu pengguna',
                startedFromOval
                    ? 'Wajah sudah sesuai. Sistem langsung menjalankan scan wajah dan verifikasi.'
                    : 'Guru cukup berdiri di depan kamera. Begitu wajah stabil, verifikasi akan berjalan otomatis.'
            );
            setScanBadge(startedFromOval ? 'Memulai scan' : 'Menunggu pengguna', startedFromOval ? 'info' : 'warning');
            setCameraGuide(
                startedFromOval ? 'Scan wajah dimulai. Ikuti arahan singkat.' : 'Arahkan satu wajah ke dalam oval untuk memulai scan.',
                startedFromOval ? 'bx-scan' : 'bx-user-voice'
            );

            try {
                const scanCallbacks = {
                    onInstruction: function (message) {
                        if (isChallengeInstruction(message)) {
                            lockCameraGuide(message, 'bx-scan');
                        } else {
                            clearCameraGuideLock();
                            setCameraGuide(message, 'bx-scan', { force: true });
                        }
                    },
                    onStatus: function (message) {
                        setPrimaryNotice('Mendeteksi wajah', message);
                        setStageState('detecting_face', 'active', message);
                        setScanBadge('Mendeteksi wajah', 'info');
                        if (isChallengeInstruction(message)) {
                            lockCameraGuide(message, 'bx-scan');
                        }
                    },
                    onGuideState: function (payload) {
                        if (payload?.message) {
                            if (isChallengeInstruction(payload.message)) {
                                lockCameraGuide(payload.message, payload.state === 'success' ? 'bx-check-circle' : 'bx-scan');
                            } else {
                                setCameraGuide(payload.message, payload.state === 'success' ? 'bx-check-circle' : 'bx-scan');
                            }
                        }
                    },
                    onChallengeState: function (step, state) {
                        const progressMap = {
                            align: [18, 'Menstabilkan posisi wajah'],
                            blink: [44, 'Membaca kedipan'],
                            challenge: [72, 'Menjalankan challenge wajah'],
                            done: [92, 'Menyelesaikan verifikasi wajah'],
                        };

                        const current = progressMap[step];
                        if (!current) {
                            return;
                        }

                        if (state === 'active') {
                            updateMatchHud(current[0], current[1], 'info');
                            if (step === 'blink') {
                                lockCameraGuide('Kedip satu kali untuk verifikasi.', 'bx-show-alt');
                            } else if (step === 'challenge') {
                                lockCameraGuide('Ikuti challenge wajah di layar.', 'bx-directions');
                            }
                        } else if (state === 'done') {
                            updateMatchHud(Math.min(current[0] + 10, 96), current[1], 'success');
                            if (step === 'blink' || step === 'challenge') {
                                clearCameraGuideLock();
                            }
                        }
                    },
                    onFaceMatchCheck: async function (descriptor) {
                        setStageState('verifying_identity', 'active', 'Memeriksa kecocokan awal wajah dengan data guru terdaftar.');
                        setPrimaryNotice('Memeriksa kecocokan wajah', 'Sistem memastikan wajah cocok dengan data guru sebelum challenge scan wajah dilanjutkan.');
                        setScanBadge('Cocokkan awal', 'info');
                        updateMatchHud(28, 'Memeriksa wajah awal', 'info');

                        const verification = await requestKioskFaceMatchVerification(descriptor);
                        if (verification?.face_verified) {
                            matchedTeacherCandidate = verification.teacher || null;
                            const teacherName = matchedTeacherCandidate?.name || 'guru terdaftar';
                            setStageState('verifying_identity', 'active', `Wajah awal cocok sebagai ${teacherName}. Lanjut ke challenge scan.`);
                            setPrimaryNotice('Wajah cocok', `${teacherName} terdeteksi. Sistem melanjutkan challenge liveness seperti pada presensi mobile.`);
                            setScanBadge('Wajah cocok', 'success');
                            updateMatchHud(38, `Cocok: ${teacherName}`, 'success');
                        }

                        return verification;
                    },
                };

                const result = await faceRecognition.performAttendanceScan(video, scanCallbacks);

                await submitAutomaticAttendance(result);
                scheduleNextScan(successScanDelayMs);
            } catch (error) {
                const message = error.message || 'Scan wajah belum berhasil.';
                if (error.statusCode === 'attendance_completed') {
                    const teacherName = matchedTeacherCandidate?.name || 'Guru';
                    finishMatchHud(true, 'Selesai');
                    faceMustExitBeforeNextMatch = true;
                    clearCameraGuideLock();
                    setStageState('detecting_face', 'done', 'Wajah berhasil dikenali oleh sistem.');
                    setStageState('verifying_identity', 'done', `Identitas ${teacherName} berhasil dicocokkan.`);
                    setStageState('processing_attendance', 'done', 'Sistem mengecek data presensi hari ini.');
                    setStageState('attendance_success', 'done', message);
                    setPrimaryNotice('Presensi hari ini selesai', `${teacherName} sudah memiliki presensi masuk dan pulang untuk hari ini.`);
                    setScanBadge('Sudah lengkap', 'success');
                    setCameraGuide('Presensi hari ini sudah lengkap. Tunggu wajah berikutnya.', 'bx-check-double');
                    showFlashModal({
                        tone: 'success',
                        title: 'Presensi Hari Ini Sudah Lengkap',
                        copy: `${teacherName} sudah menyelesaikan presensi hari ini.`,
                        meta: message,
                        duration: 2800,
                    });
                    showAttendanceResult('Presensi sudah lengkap', `${teacherName}: ${message}`);
                    scheduleNextScan(successScanDelayMs);
                    return;
                }
                if (cameraMatchHud?.classList.contains('is-visible')) {
                    finishMatchHud(false, 'Gagal');
                }
                clearCameraGuideLock();
                setStageState('detecting_face', 'error', message);
                setPrimaryNotice('Scan belum berhasil', message);
                setScanBadge('Scan diulang', 'warning');
                setCameraGuide(message, 'bx-refresh', { force: true });
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
                scheduleNextScan(retryScanDelayMs);
            } finally {
                clearCameraGuideLock();
                scanInProgress = false;
            }
        }

        async function bootstrapAutomation() {
            clearScanTimer();
            try {
                await requestLocationAndValidate();
                await startAttendanceCamera();
                scheduleNextScan(initialScanDelayMs);
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
            setEnrollmentStatus('Menyalakan kamera', 'Kamera registrasi sedang diaktifkan.');
            enrollmentGuideText.textContent = 'Siapkan wajah di dalam oval.';
            setEnrollmentQualityState(0, 'idle', 'Belum jelas', 'Posisikan satu wajah di dalam oval dan pastikan cahaya cukup.');

            await faceRecognition.initializeCamera(enrollmentVideo);
            await prepareBrowserFaceScan();
            startLivePreview(enrollmentVideo, enrollmentCanvas, 'enrollment');
            enrollmentCameraReady = true;
            setEnrollmentStatus('Kamera siap', selectedEnrollmentTeacher
                ? `Kamera aktif. Mulai scan wajah ${selectedEnrollmentTeacher.name}, lalu simpan hasilnya.`
                : 'Kamera aktif. Pilih guru lalu mulai scan wajah.');
            enrollmentGuideText.textContent = 'Posisikan satu wajah tepat di dalam oval.';
            setEnrollmentQualityState(0, 'idle', 'Belum jelas', 'Wajah akan dinilai dari ketajaman dan kestabilannya sebelum disimpan.');
        }

        async function startEnrollmentFlow() {
            if (enrollmentBusy) {
                return;
            }

            if (!selectedEnrollmentTeacher) {
                setTeacherState(null);
                setEnrollmentStatus('Pilih guru terlebih dahulu', 'Tentukan guru yang akan didaftarkan, lalu mulai registrasi kembali.');
                return;
            }

            enrollmentBusy = true;
            updateEnrollmentActionState();
            startEnrollmentButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Memindai...';
            resetEnrollmentCaptureState();
            enrollmentPlaceholder.style.display = 'none';
            setEnrollmentStatus('Memulai scan', 'Sistem sedang menyiapkan pengambilan wajah.');
            enrollmentGuideText.textContent = 'Memulai scan wajah.';
            setEnrollmentQualityState(8, 'idle', 'Menyiapkan', 'Sistem mulai membaca kualitas wajah dari kamera.');
            resetEnrollmentProgress();

            try {
                await startEnrollmentCameraPreview();
                setEnrollmentStatus('Registrasi berjalan', 'Minta guru menatap kamera dan tahan posisi sampai sistem mengambil frame terbaik secara otomatis.');
                enrollmentGuideText.textContent = 'Posisikan satu wajah tepat di dalam oval.';

                const enrollmentResult = await faceRecognition.performEnrollmentScan(enrollmentVideo, {
                    onInstruction: function (message) {
                        enrollmentGuideText.textContent = message;
                    },
                    onChallengeState: function (step, state) {
                        updateEnrollmentProgress(step, state);
                    },
                    onStatus: function (message) {
                        setEnrollmentStatus('Registrasi berjalan', message);
                    },
                    onCaptureProgress: function (progress) {
                        const level = Math.max(12, Math.min(Math.round(progress * 100), 100));
                        setEnrollmentQualityState(
                            level,
                            progress >= 1 ? 'success' : (progress >= 0.55 ? 'warning' : 'idle'),
                            progress >= 1 ? 'Siap simpan' : (progress >= 0.55 ? 'Hampir jelas' : 'Belum jelas'),
                            progress >= 1
                                ? 'Wajah sudah jelas dan stabil. Sistem langsung mengambil lalu menyimpan data.'
                                : 'Tahan posisi wajah. Sistem sedang memastikan gambar cukup jelas dan stabil.'
                        );
                    },
                    onEnrollmentQuality: function (payload) {
                        const sharpnessScore = Math.max(0, Math.min(Math.round((payload?.sharpness || 0) * 300), 100));
                        const stabilityScore = payload?.stableEnough ? 100 : 42;
                        const level = payload?.ready
                            ? Math.max(70, Math.round((payload?.progress || 0) * 100))
                            : Math.round((sharpnessScore * 0.62) + (stabilityScore * 0.38));
                        const tone = payload?.ready ? 'success' : ((payload?.sharpEnough || payload?.stableEnough) ? 'warning' : 'idle');
                        const label = payload?.ready
                            ? 'Siap simpan'
                            : (!payload?.sharpEnough
                                ? 'Belum jelas'
                                : (!payload?.stableEnough ? 'Belum stabil' : 'Membaca wajah'));
                        const copy = payload?.ready
                            ? 'Gambar sudah jelas dan stabil. Sistem akan otomatis mengambil lalu menyimpan data wajah.'
                            : (!payload?.sharpEnough
                                ? 'Wajah belum cukup tajam. Perbaiki cahaya dan tahan posisi sebentar.'
                                : (!payload?.stableEnough
                                    ? 'Gambar sudah cukup jelas, tetapi wajah masih bergerak. Tahan posisi lebih tenang.'
                                    : 'Sistem sedang menilai kualitas gambar wajah.'));

                        setEnrollmentQualityState(level, tone, label, copy);
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
                pendingEnrollmentResult = enrollmentResult;
                setEnrollmentStatus('Hasil scan siap', 'Wajah berhasil diambil. Periksa hasilnya lalu simpan data wajah guru.');
                enrollmentGuideText.textContent = 'Hasil scan siap disimpan.';
                setEnrollmentQualityState(100, 'success', 'Siap simpan', 'Gambar wajah sudah jelas. Lanjutkan dengan menyimpan data wajah seperti pada face enrollment mobile.');
            } catch (error) {
                faceRecognition.stopCamera(enrollmentVideo);
                stopLivePreview('enrollment');
                enrollmentCameraReady = false;
                enrollmentPlaceholder.style.display = 'flex';
                setEnrollmentStatus('Registrasi gagal', error.message || 'Registrasi wajah belum berhasil. Ulangi proses dan pastikan wajah berada di dalam oval.');
                enrollmentGuideText.textContent = 'Registrasi gagal. Ulangi proses scan wajah.';
                setEnrollmentQualityState(26, 'warning', 'Belum jelas', 'Gambar belum memenuhi syarat atau belum stabil. Ulangi registrasi sampai indikator menunjukkan wajah siap disimpan.');
            } finally {
                enrollmentBusy = false;
                updateEnrollmentActionState();
            }
        }

        async function submitEnrollmentResult() {
            if (enrollmentBusy || !selectedEnrollmentTeacher || !pendingEnrollmentResult) {
                return;
            }

            enrollmentBusy = true;
            updateEnrollmentActionState();
            saveEnrollmentButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Menyimpan...';
            setEnrollmentStatus('Menyimpan data wajah', 'Frame terbaik berhasil diambil. Sistem sedang menyimpan data wajah guru.');
            enrollmentGuideText.textContent = 'Menyimpan data wajah ke server.';
            setEnrollmentQualityState(100, 'success', 'Berhasil diambil', 'Gambar wajah sudah jelas. Sistem sedang menyimpan hasil registrasi ke database.');

            try {
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
                        selfie_data: pendingEnrollmentResult.captured_image,
                        selfie_frames: pendingEnrollmentResult.selfie_frames || [],
                        face_descriptor: pendingEnrollmentResult.face_descriptor,
                        liveness_score: pendingEnrollmentResult.liveness_score,
                        liveness_challenges: pendingEnrollmentResult.liveness_challenges,
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
                pendingEnrollmentResult = null;

                setEnrollmentStatus('Registrasi berhasil', payload.message || 'Data wajah berhasil disimpan. Kiosk akan kembali ke mode presensi otomatis.');
                enrollmentGuideText.textContent = 'Registrasi selesai. Menutup modal dan kembali ke mode presensi.';
                setEnrollmentQualityState(100, 'success', 'Tersimpan', 'Data wajah sudah berhasil disimpan dan siap dipakai untuk presensi.');

                window.setTimeout(function () {
                    if (faceEnrollmentModal) {
                        faceEnrollmentModal.hide();
                    }
                }, 1200);
            } catch (error) {
                setEnrollmentStatus('Registrasi gagal', error.message || 'Registrasi wajah gagal disimpan.');
                enrollmentGuideText.textContent = 'Penyimpanan gagal. Periksa hasil scan lalu coba simpan kembali.';
                setEnrollmentQualityState(100, 'warning', 'Siap simpan', 'Hasil scan masih tersedia. Anda bisa mencoba menyimpan kembali tanpa scan ulang.');
            } finally {
                enrollmentBusy = false;
                updateEnrollmentActionState();
            }
        }

        function openEnrollmentModal() {
            clearScanTimer();
            stopCurrentCamera();
            cameraMode = 'enrollment';
            resetEnrollmentCaptureState();
            enrollmentPlaceholder.style.display = 'flex';
            setEnrollmentStatus('Status Registrasi', 'Modal registrasi dibuka. Kamera akan disiapkan otomatis.');
            enrollmentGuideText.textContent = 'Menyiapkan kamera registrasi.';
            setEnrollmentQualityState(0, 'idle', 'Belum jelas', 'Sistem akan menilai apakah wajah sudah cukup jelas sebelum wajah disimpan.');

            const firstVisible = Array.from(enrollmentTeacherSelect.options).find((option) => !option.hidden);
            if (firstVisible) {
                enrollmentTeacherSelect.value = firstVisible.value;
            }

            updateEnrollmentTeacherSelection();
            updateEnrollmentActionState();

            if (faceEnrollmentModal) {
                faceEnrollmentModal.show();
            }
        }

        openEnrollmentModalButton?.addEventListener('click', openEnrollmentModal);
        openEnrollmentRefreshButton?.addEventListener('click', openEnrollmentModal);

        faceEnrollmentModalEl?.addEventListener('shown.bs.modal', function () {
            document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                backdrop.classList.add('face-modal-backdrop');
            });

            startEnrollmentCameraPreview().catch(function (error) {
                enrollmentPlaceholder.style.display = 'flex';
                setEnrollmentStatus('Kamera gagal', error.message || 'Kamera registrasi belum bisa diaktifkan.');
                enrollmentGuideText.textContent = 'Izinkan kamera lalu coba lagi.';
                enrollmentCameraReady = false;
            });
        });

        faceEnrollmentModalEl?.addEventListener('hidden.bs.modal', function () {
            faceRecognition.stopCamera(enrollmentVideo);
            stopLivePreview('enrollment');
            enrollmentCameraReady = false;
            resetEnrollmentCaptureState();
            enrollmentPlaceholder.style.display = 'flex';
            document.querySelectorAll('.face-modal-backdrop').forEach(function (backdrop) {
                backdrop.classList.remove('face-modal-backdrop');
            });
            cameraMode = 'attendance';
            bootstrapAutomation();
        });

        attendanceDetailModalEl?.addEventListener('shown.bs.modal', function () {
            document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                backdrop.classList.add('attendance-detail-backdrop');
            });
        });

        attendanceDetailModalEl?.addEventListener('hidden.bs.modal', function () {
            document.querySelectorAll('.attendance-detail-backdrop').forEach(function (backdrop) {
                backdrop.classList.remove('attendance-detail-backdrop');
            });
        });

        enrollmentTeacherSearchInput?.addEventListener('input', filterEnrollmentTeachers);
        enrollmentTeacherSelect?.addEventListener('change', updateEnrollmentTeacherSelection);
        startEnrollmentButton?.addEventListener('click', startEnrollmentFlow);
        saveEnrollmentButton?.addEventListener('click', submitEnrollmentResult);

        retryLocationButton?.addEventListener('click', function () {
            bootstrapAutomation();
        });

        restartScannerButton?.addEventListener('click', function () {
            bootstrapAutomation();
        });

        cameraActivityList?.addEventListener('click', function (event) {
            const card = event.target.closest('.camera-activity-item');
            if (!card) {
                return;
            }

            const presensiId = String(card.dataset.presensiId || '');
            const activity = attendanceActivities.find(function (item) {
                return String(item.presensi_id || '') === presensiId;
            });

            if (activity) {
                openAttendanceDetail(activity);
            }
        });

        kioskFlashModal?.addEventListener('click', function (event) {
            if (event.target === kioskFlashModal) {
                hideFlashModal();
            }
        });

        initSummaryLottie();
        recalculateAttendanceSummary();
        renderAttendanceActivities();
        updateEnrollmentBanner();
        updateEnrollmentTeacherSelection();
        bootstrapAutomation();
    })();
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/kiosk/school-kiosk.blade.php ENDPATH**/ ?>