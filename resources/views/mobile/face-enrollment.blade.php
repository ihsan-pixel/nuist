@extends('layouts.mobile')

@section('title', 'Daftar Wajah')
@section('subtitle', 'Aktifkan Scan Wajah')

@section('content')
<div class="container py-0 px-0" style="max-width: 100%; margin: auto;">
    <style>
        body {
            background: #000;
            font-family: 'Poppins', sans-serif;
        }

        .face-card {
            background: transparent;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
            border: 0;
            margin-bottom: 0;
            color: #f8fafc;
        }

        .face-stage {
            --scan-progress: 0%;
            position: relative;
            min-height: 100vh;
            background: #000;
            overflow: hidden;
        }

        .face-stage::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.34) 0%, rgba(0, 0, 0, 0.08) 30%, rgba(0, 0, 0, 0.2) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .face-scan-status {
            display: none;
        }

        .face-scan-status span {
            display: inline-block;
            min-height: 0;
            padding: 0;
            border-radius: 0;
            background: transparent;
            backdrop-filter: none;
            line-height: 1.4;
        }

        .face-camera-layer {
            position: relative;
            width: 100%;
            min-height: 100vh;
        }

        .face-placeholder,
        #face-video,
        #face-preview {
            width: 100%;
            height: 100%;
        }

        .face-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            color: rgba(255, 255, 255, 0.88);
            padding: 28px;
            background: radial-gradient(circle at top, rgba(71, 85, 105, 0.46) 0%, rgba(15, 23, 42, 0.92) 100%);
            z-index: 0;
        }

        .face-placeholder i {
            font-size: 44px;
            margin-bottom: 10px;
            color: rgba(134, 239, 172, 0.92);
        }

        #face-video {
            position: absolute;
            inset: 0;
            display: none;
            object-fit: cover;
            transform: scaleX(-1);
            background: #111827;
            z-index: 0;
        }

        #face-preview {
            position: absolute;
            inset: 0;
            display: none;
            object-fit: cover;
            background: #111827;
            z-index: 0;
        }

        .face-guide {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }

        .face-guide-oval {
            position: absolute;
            left: 50%;
            top: 13%;
            width: min(74vw, 332px);
            height: min(102vw, 448px);
            transform: translateX(-50%);
            border: 3px solid rgba(255, 255, 255, 0.92);
            border-radius: 48% 48% 44% 44% / 38% 38% 54% 54%;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, 0.42);
            transition: border-color 280ms cubic-bezier(0.22, 1, 0.36, 1), box-shadow 280ms cubic-bezier(0.22, 1, 0.36, 1), transform 280ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .face-guide-pill {
            display: none;
        }

        .face-guide-pill i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            font-size: 30px;
            flex: 0 0 auto;
            color: rgba(255, 255, 255, 0.94);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .face-guide-pill span {
            width: 100%;
            text-align: center;
            line-height: 1.45;
        }

        .face-stage[data-guide-state="searching"] .face-guide-oval,
        .face-stage[data-guide-state="too-far"] .face-guide-oval,
        .face-stage[data-guide-state="too-close"] .face-guide-oval,
        .face-stage[data-guide-state="tilted"] .face-guide-oval,
        .face-stage[data-guide-state="off-center"] .face-guide-oval,
        .face-stage[data-guide-state="warning"] .face-guide-oval {
            border-color: #f87171;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, 0.4), 0 0 34px rgba(248, 113, 113, 0.24);
        }

        .face-stage[data-guide-state="steady"] .face-guide-oval,
        .face-stage[data-guide-state="aligned"] .face-guide-oval,
        .face-stage[data-guide-state="success"] .face-guide-oval {
            border-color: #4ade80;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, 0.34), 0 0 34px rgba(74, 222, 128, 0.26);
        }

        .face-stage[data-guide-state="searching"] .face-guide-pill,
        .face-stage[data-guide-state="too-far"] .face-guide-pill,
        .face-stage[data-guide-state="too-close"] .face-guide-pill,
        .face-stage[data-guide-state="tilted"] .face-guide-pill,
        .face-stage[data-guide-state="off-center"] .face-guide-pill,
        .face-stage[data-guide-state="warning"] .face-guide-pill {
            border-color: rgba(248, 113, 113, 0.26);
            background: rgba(42, 15, 15, 0.52);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.22), 0 0 0 1px rgba(248, 113, 113, 0.08);
        }

        .face-stage[data-guide-state="steady"] .face-guide-pill,
        .face-stage[data-guide-state="aligned"] .face-guide-pill,
        .face-stage[data-guide-state="success"] .face-guide-pill {
            border-color: rgba(74, 222, 128, 0.22);
            background: rgba(6, 46, 26, 0.48);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.22), 0 0 0 1px rgba(74, 222, 128, 0.08);
        }

        .face-stage[data-guide-state="steady"] .face-guide-oval {
            transform: translateX(-50%) scale(0.996);
        }

        .face-stage[data-guide-state="success"] .face-guide-oval {
            animation: face-oval-pulse 700ms ease;
        }

        @keyframes face-oval-pulse {
            0% {
                transform: translateX(-50%) scale(0.98);
            }

            55% {
                transform: translateX(-50%) scale(1.02);
            }

            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        .face-action-panel {
            position: absolute;
            left: 16px;
            right: 16px;
            bottom: 22px;
            z-index: 3;
            padding: 0;
            background: transparent;
            backdrop-filter: none;
            border: 0;
            box-shadow: none;
        }

        .face-stage-copy {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .face-feedback {
            border: 0;
            background: transparent;
            color: #f8fafc;
            text-align: center;
            padding: 0;
            margin: 0;
        }

        .face-feedback strong {
            display: none;
        }

        .face-feedback span {
            display: block;
            font-size: 14px;
            line-height: 1.45;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.94);
        }

        #face-instruction {
            display: none;
        }

        .face-progress-orb {
            --progress-angle: 0deg;
            --progress-fill: #4ade80;
            --progress-track: rgba(255, 255, 255, 0.16);
            position: absolute;
            left: 50%;
            top: 73.5%;
            transform: translateX(-50%);
            z-index: 3;
            width: 74px;
            height: 74px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at center, rgba(255, 255, 255, 0.04) 52%, transparent 53%),
                conic-gradient(var(--progress-fill) 0deg, var(--progress-fill) var(--progress-angle), var(--progress-track) var(--progress-angle), var(--progress-track) 360deg);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.32);
            transition: transform 220ms ease, box-shadow 220ms ease;
        }

        .face-progress-orb::before {
            content: '';
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .face-progress-orb-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: transparent;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.01em;
        }

        .face-progress-value,
        .face-progress-success,
        .face-progress-error {
            transition: opacity 180ms ease, transform 220ms ease;
        }

        .face-progress-success,
        .face-progress-error {
            position: absolute;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.58);
            font-size: 34px;
        }

        .face-progress-success {
            color: #fff;
            text-shadow: 0 0 16px rgba(134, 239, 172, 0.38), 0 0 30px rgba(74, 222, 128, 0.28);
        }

        .face-progress-error {
            color: #fff;
            text-shadow: 0 0 16px rgba(248, 113, 113, 0.34), 0 0 30px rgba(239, 68, 68, 0.24);
        }

        .face-progress-orb.is-complete .face-progress-value {
            opacity: 0;
            transform: scale(0.72);
        }

        .face-progress-orb.is-complete .face-progress-success {
            opacity: 1;
            transform: scale(1);
            animation: face-progress-success-pop 820ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .face-progress-orb.is-error .face-progress-value {
            opacity: 0;
            transform: scale(0.72);
        }

        .face-progress-orb.is-error .face-progress-error {
            opacity: 1;
            transform: scale(1);
            animation: face-progress-error-pop 720ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .face-progress-orb.is-complete {
            animation: face-progress-orb-pulse 900ms cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 0 0 10px rgba(74, 222, 128, 0.14), 0 18px 40px rgba(74, 222, 128, 0.34);
        }

        .face-progress-orb.is-complete::before {
            border-color: rgba(134, 239, 172, 0.42);
            animation: face-progress-orb-ring 900ms ease-out;
        }

        .face-progress-orb.is-error {
            animation: face-progress-orb-error-pulse 760ms cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 0 0 10px rgba(248, 113, 113, 0.14), 0 18px 40px rgba(239, 68, 68, 0.28);
        }

        .face-progress-orb.is-error::before {
            border-color: rgba(248, 113, 113, 0.42);
            animation: face-progress-orb-ring 760ms ease-out;
        }

        @keyframes face-progress-orb-pulse {
            0% {
                transform: translateX(-50%) scale(0.92);
            }

            48% {
                transform: translateX(-50%) scale(1.14);
            }

            72% {
                transform: translateX(-50%) scale(0.98);
            }

            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        @keyframes face-progress-orb-ring {
            0% {
                transform: scale(0.86);
                opacity: 0;
            }

            35% {
                transform: scale(1.08);
                opacity: 1;
            }

            100% {
                transform: scale(1.18);
                opacity: 0;
            }
        }

        @keyframes face-progress-success-pop {
            0% {
                opacity: 0;
                transform: scale(0.3) rotate(-18deg);
            }

            42% {
                opacity: 1;
                transform: scale(1.34) rotate(8deg);
            }

            68% {
                opacity: 1;
                transform: scale(1.02) rotate(-4deg);
            }

            100% {
                opacity: 1;
                transform: scale(1.14) rotate(0deg);
            }
        }

        @keyframes face-progress-orb-error-pulse {
            0% {
                transform: translateX(-50%) scale(0.92);
            }

            32% {
                transform: translateX(calc(-50% - 4px)) scale(1.04);
            }

            58% {
                transform: translateX(calc(-50% + 4px)) scale(1.02);
            }

            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        @keyframes face-progress-error-pop {
            0% {
                opacity: 0;
                transform: scale(0.34) rotate(-24deg);
            }

            46% {
                opacity: 1;
                transform: scale(1.28) rotate(10deg);
            }

            72% {
                opacity: 1;
                transform: scale(0.96) rotate(-6deg);
            }

            100% {
                opacity: 1;
                transform: scale(1.08) rotate(0deg);
            }
        }

        .face-progress {
            display: none;
        }

        .face-meta-note {
            display: none;
        }

        .btn-face {
            background: linear-gradient(135deg, #10b981 0%, #16a34a 100%);
            border: none;
            border-radius: 16px;
            padding: 14px 18px;
            color: #fff;
            font-weight: 600;
            width: 100%;
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.28);
        }

        #btn-enroll-face {
            background: linear-gradient(135deg, #10b981 0%, #16a34a 100%);
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.28);
        }

        .btn-face:disabled {
            background: rgba(148, 163, 184, 0.6);
            box-shadow: none;
        }

        .btn-face-secondary {
            display: none !important;
        }

        .face-actions {
            margin-top: 14px;
        }

        .face-onboarding {
            position: fixed;
            inset: 0;
            z-index: 2200;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 18px;
            background: rgba(0, 0, 0, 0.72);
            backdrop-filter: blur(10px);
        }

        .face-onboarding[hidden] {
            display: none !important;
        }

        .face-onboarding-panel {
            width: min(100%, 420px);
            border-radius: 24px;
            background: #f8fafc;
            color: #0f172a;
            padding: 20px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.32);
        }

        .face-onboarding-title {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0;
        }

        .face-onboarding-subtitle {
            margin: 0 0 16px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .face-onboarding-steps {
            display: grid;
            gap: 10px;
            margin-bottom: 16px;
        }

        .face-onboarding-step {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            line-height: 1.45;
            color: #334155;
        }

        .face-onboarding-step i {
            margin-top: 1px;
            color: #059669;
            font-size: 18px;
            flex: 0 0 auto;
        }

        .face-example-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 18px;
        }

        .face-example {
            min-height: 132px;
            border-radius: 18px;
            padding: 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .face-example.is-good {
            border-color: rgba(16, 185, 129, 0.34);
            background: #ecfdf5;
        }

        .face-example.is-bad {
            border-color: rgba(248, 113, 113, 0.32);
            background: #fef2f2;
        }

        .face-example-oval {
            width: 58px;
            height: 78px;
            border-radius: 48% 48% 44% 44% / 38% 38% 54% 54%;
            border: 2px solid #10b981;
            position: relative;
        }

        .face-example.is-bad .face-example-oval {
            border-color: #ef4444;
        }

        .face-example-face {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 34px;
            height: 44px;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            background: #cbd5e1;
        }

        .face-example.is-bad .face-example-face {
            transform: translate(-22%, -64%) rotate(-12deg);
            opacity: 0.82;
        }

        .face-example-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
        }

        .face-example.is-good .face-example-label {
            color: #047857;
        }

        .face-example.is-bad .face-example-label {
            color: #b91c1c;
        }

        .face-onboarding-button {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 14px 18px;
            color: #fff;
            background: linear-gradient(135deg, #10b981 0%, #16a34a 100%);
            font-weight: 700;
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.24);
        }

        .face-help-button {
            position: absolute;
            right: 18px;
            bottom: 92px;
            z-index: 8;
            width: 44px;
            height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.58);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.24);
            backdrop-filter: blur(12px);
        }

        .face-help-button:active {
            transform: scale(0.96);
        }

        @media (max-width: 380px) {
            .face-stage {
                min-height: 100vh;
            }

            .face-feedback span {
                font-size: 16px;
            }

            .face-guide-oval {
                width: min(78vw, 286px);
                height: min(104vw, 396px);
                top: 15%;
            }

            .face-progress-orb {
                top: 76%;
                width: 68px;
                height: 68px;
            }

            .face-guide-pill {
                top: calc(76% + 84px);
                width: min(86vw, 314px);
                padding: 12px 16px 14px;
            }

            .face-guide-pill i {
                width: 38px;
                height: 38px;
                font-size: 26px;
            }

            .face-progress-orb-inner {
                width: 48px;
                height: 48px;
                font-size: 14px;
            }
        }
    </style>

    <div class="face-onboarding" id="face-onboarding" role="dialog" aria-modal="true" aria-labelledby="face-onboarding-title">
        <div class="face-onboarding-panel">
            <h2 class="face-onboarding-title" id="face-onboarding-title">Panduan Scan Wajah</h2>
            <p class="face-onboarding-subtitle">Ikuti panduan singkat ini agar wajah cepat terbaca dan data yang tersimpan jelas.</p>

            <div class="face-onboarding-steps">
                <div class="face-onboarding-step">
                    <i class="bx bx-check-circle"></i>
                    <span>Posisikan satu wajah tepat di dalam oval.</span>
                </div>
                <div class="face-onboarding-step">
                    <i class="bx bx-sun"></i>
                    <span>Gunakan cahaya cukup dan jangan terlalu gelap.</span>
                </div>
                <div class="face-onboarding-step">
                    <i class="bx bx-target-lock"></i>
                    <span>Tahan wajah sebentar sampai scan otomatis selesai.</span>
                </div>
            </div>

            <div class="face-example-grid" aria-label="Contoh scan wajah">
                <div class="face-example is-good">
                    <div class="face-example-oval">
                        <span class="face-example-face"></span>
                    </div>
                    <div class="face-example-label"><i class="bx bx-check"></i> Benar</div>
                </div>
                <div class="face-example is-bad">
                    <div class="face-example-oval">
                        <span class="face-example-face"></span>
                    </div>
                    <div class="face-example-label"><i class="bx bx-x"></i> Salah</div>
                </div>
            </div>

            <button type="button" class="face-onboarding-button" id="btn-face-onboarding-continue">
                Lanjutkan
            </button>
        </div>
    </div>

    <div class="face-card">
        <div class="face-stage" id="face-stage" data-guide-state="searching">
            <div id="face-status" class="face-scan-status">
                <span>Posisikan wajah di dalam oval.</span>
            </div>

            <div class="face-camera-layer">
                <div id="face-placeholder" class="face-placeholder"></div>
                <video id="face-video" autoplay playsinline muted></video>
                <img id="face-preview" alt="Preview scan wajah">
            </div>

            <div class="face-guide">
                <div class="face-guide-oval"></div>
                <div class="face-guide-pill" id="face-guide-pill">
                    <i class="bx bx-scan"></i>
                    <span id="face-guide-text">Pusatkan wajah di dalam oval.</span>
                </div>
                <div class="face-progress-orb" id="face-progress-orb" aria-hidden="true">
                    <div class="face-progress-orb-inner">
                        <span id="face-progress-value" class="face-progress-value">0%</span>
                        <span class="face-progress-success" aria-hidden="true">
                            <i class="bx bx-check"></i>
                        </span>
                        <span class="face-progress-error" aria-hidden="true">
                            <i class="bx bx-x"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="face-action-panel">
                <div class="face-stage-copy">
                    <div id="face-instruction" class="face-feedback">
                        <strong>Instruksi</strong>
                        <span>Tekan Mulai Scan lalu posisikan wajah tepat di dalam oval sampai diambil otomatis.</span>
                    </div>

                    <div class="face-progress">
                        <div class="face-progress-item" data-step="align">Posisikan</div>
                        <div class="face-progress-item" data-step="steady">Stabilkan</div>
                        <div class="face-progress-item" data-step="done">Selesai</div>
                    </div>
                </div>

                <div class="face-actions">
                    <button type="button" id="btn-start-face-camera" class="btn-face">
                        Mulai Scan
                    </button>
                    <button type="button" id="btn-enroll-face" class="btn-face" style="display:none;" disabled>
                        Simpan Wajah
                    </button>
                </div>
            </div>

            <button type="button" id="btn-face-help" class="face-help-button" aria-label="Buka panduan scan wajah">
                <i class="bx bx-info-circle"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('models/face-api.js') }}"></script>
<script src="{{ asset('js/face-recognition.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';
    const enrollUrl = '{{ route('mobile.face.enroll') }}';
    const userId = {{ (int) Auth::id() }};

    const placeholder = document.getElementById('face-placeholder');
    const video = document.getElementById('face-video');
    const preview = document.getElementById('face-preview');
    const status = document.getElementById('face-status');
    const instruction = document.getElementById('face-instruction');
    const startCameraButton = document.getElementById('btn-start-face-camera');
    const enrollButton = document.getElementById('btn-enroll-face');
    const progressItems = Array.from(document.querySelectorAll('.face-progress-item'));
    const faceStage = document.getElementById('face-stage');
    const faceGuideText = document.getElementById('face-guide-text');
    const faceProgressOrb = document.getElementById('face-progress-orb');
    const faceProgressValue = document.getElementById('face-progress-value');
    const onboarding = document.getElementById('face-onboarding');
    const onboardingContinueButton = document.getElementById('btn-face-onboarding-continue');
    const faceHelpButton = document.getElementById('btn-face-help');

    const faceRecognition = new window.FaceRecognition();
    let cameraReady = false;
    let enrollmentResult = null;
    let onboardingAccepted = false;
    let captureProgress = 0;
    let currentProgress = 0;
    let targetProgress = 0;
    let progressAnimationFrame = null;

    function setStatus(message, type = 'info') {
        status.className = 'face-scan-status';
        status.innerHTML = `<span>${message}</span>`;
    }

    function setInstruction(message) {
        instruction.innerHTML = `<strong>Instruksi</strong><span>${message}</span>`;
    }

    function showSimpleAlert(message, type = 'info') {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                icon: type,
                title: type === 'success' ? 'Berhasil' : type === 'error' ? 'Gagal' : 'Informasi',
                text: message,
                confirmButtonText: 'OK',
            });
            return;
        }

        window.alert(message);
    }

    function resetProgress() {
        progressItems.forEach((item) => {
            item.classList.remove('active', 'done');
        });
        captureProgress = 0;
        currentProgress = 0;
        targetProgress = 0;
        if (progressAnimationFrame !== null) {
            window.cancelAnimationFrame(progressAnimationFrame);
            progressAnimationFrame = null;
        }
        setProgressOrbState('default');
        if (faceProgressOrb && faceProgressValue) {
            renderProgressRing(0);
        }
    }

    function setCaptureProgress(progress = 0) {
        captureProgress = Math.max(0, Math.min(progress, 1));
        updateProgressRing();
    }

    function renderProgressRing(progress) {
        const normalized = Math.max(0, Math.min(progress, 1));
        const percent = Math.round(normalized * 100);
        faceProgressOrb.style.setProperty('--progress-angle', `${normalized * 360}deg`);
        faceProgressValue.textContent = `${percent}%`;

        if (faceProgressOrb.classList.contains('is-error')) {
            return;
        }

        setProgressOrbState(percent >= 100 ? 'success' : 'default');
    }

    function animateProgressRing() {
        const delta = targetProgress - currentProgress;

        if (Math.abs(delta) < 0.003) {
            currentProgress = targetProgress;
            renderProgressRing(currentProgress);
            progressAnimationFrame = null;
            return;
        }

        currentProgress += delta * 0.18;
        renderProgressRing(currentProgress);
        progressAnimationFrame = window.requestAnimationFrame(animateProgressRing);
    }

    function setProgressOrbState(state = 'default') {
        if (!faceProgressOrb) {
            return;
        }

        const palette = {
            default: '#4ade80',
            success: '#4ade80',
            error: '#f87171',
        };

        faceProgressOrb.style.setProperty('--progress-fill', palette[state] || palette.default);
        faceProgressOrb.classList.toggle('is-complete', state === 'success');
        faceProgressOrb.classList.toggle('is-error', state === 'error');
    }

    function updateProgressRing() {
        if (!faceProgressOrb || !faceProgressValue) {
            return;
        }

        const totalSteps = progressItems.length || 1;
        const doneCount = progressItems.filter((item) => item.classList.contains('done')).length;
        const activeIndex = progressItems.findIndex((item) => item.classList.contains('active'));
        let progress = doneCount / totalSteps;

        if (activeIndex === 1) {
            progress = Math.max(progress, (1 + (captureProgress * 2)) / totalSteps);
        } else if (activeIndex >= 0 && doneCount < totalSteps) {
            progress = Math.max(progress, (activeIndex + 0.5) / totalSteps);
        }

        if (faceStage?.dataset.guideState === 'success') {
            progress = 1;
        }

        targetProgress = Math.max(0, Math.min(progress, 1));

        if (progressAnimationFrame !== null) {
            return;
        }

        progressAnimationFrame = window.requestAnimationFrame(animateProgressRing);
    }

    function updateGuideTone(state) {
        const icon = faceStage?.dataset.guideState === 'success'
            ? 'bx-check-circle'
            : (state === 'steady' || state === 'aligned' ? 'bx-user-check' : 'bx-user-circle');
        const palette = {
            success: '#86efac',
            aligned: '#86efac',
            steady: '#86efac',
            warning: '#fca5a5',
            'too-far': '#fca5a5',
            'too-close': '#fca5a5',
            tilted: '#fca5a5',
            'off-center': '#fca5a5',
            searching: '#fca5a5',
        };

        const color = palette[state] || palette.searching;
        const iconEl = document.querySelector('#face-guide-pill i');
        if (iconEl) {
            iconEl.className = `bx ${icon}`;
            iconEl.style.color = color;
        }
    }

    function updateProgress(step, state) {
        const target = document.querySelector(`.face-progress-item[data-step="${step}"]`);
        if (!target) {
            return;
        }

        target.classList.remove('active', 'done');
        if (state === 'active') {
            target.classList.add('active');
        }
        if (state === 'done') {
            target.classList.add('done');
        }

        updateProgressRing();
    }

    function updateGuideState(payload = {}) {
        if (faceStage && payload.state) {
            faceStage.dataset.guideState = payload.state;
            updateGuideTone(payload.state);
            updateProgressRing();
        }

        if (faceGuideText && payload.message) {
            faceGuideText.textContent = payload.message;
        }
    }

    function resetEnrollmentState() {
        enrollmentResult = null;
        preview.src = '';
        preview.style.display = 'none';
        video.style.display = cameraReady ? 'block' : 'none';
        enrollButton.disabled = true;
        enrollButton.style.display = 'none';
        startCameraButton.style.display = 'block';
        resetProgress();
        if (faceStage) {
            faceStage.dataset.guideState = cameraReady ? 'steady' : 'searching';
        }
        updateGuideTone(cameraReady ? 'steady' : 'searching');
        updateProgressRing();
    }

    async function activateCamera() {
        try {
            setInstruction('Memuat kamera dan model wajah.');
            setStatus('Menyiapkan kamera.');
            await faceRecognition.loadModels();
            await faceRecognition.initializeCamera(video);

            cameraReady = true;
            placeholder.style.display = 'none';
            video.style.display = 'block';
            setInstruction('Posisikan wajah tepat di dalam oval. Saat sudah pas, gambar akan diambil otomatis.');
            setStatus('Kamera aktif. Pusatkan wajah tepat di dalam oval.');
            updateGuideState({
                state: 'steady',
                message: 'Pusatkan wajah tepat di dalam oval.',
            });
        } catch (error) {
            setStatus(error.message || 'Kamera tidak dapat diakses.');
            setInstruction('Izinkan akses kamera lalu coba lagi.');
            throw error;
        }
    }

    async function runEnrollment() {
        startCameraButton.style.display = 'block';
        enrollButton.style.display = 'none';
        startCameraButton.disabled = true;
        startCameraButton.textContent = 'Memindai...';

        if (!cameraReady) {
            await activateCamera();
        }

        enrollButton.disabled = true;
        resetProgress();
        setCaptureProgress(0);

        try {
            const result = await faceRecognition.performEnrollmentScan(video, {
                onInstruction: function (message) {
                    setInstruction(message);
                },
                onChallengeState: function (step, state) {
                    updateProgress(step, state);
                },
                onStatus: function (message) {
                    setStatus(message);
                },
                onGuideState: function (payload) {
                    updateGuideState(payload);
                },
                onCaptureProgress: function (progress) {
                    setCaptureProgress(progress);
                },
            });

            enrollmentResult = result;
            preview.src = result.captured_image;
            preview.style.display = 'block';
            video.style.display = 'none';
            faceRecognition.stopCamera(video);
            cameraReady = false;
            enrollButton.disabled = false;
            setInstruction('Wajah sudah diambil otomatis. Periksa hasilnya lalu tekan Simpan Wajah.');
            setStatus('Wajah berhasil diambil. Konfirmasi simpan wajah.');
            updateGuideState({
                state: 'success',
                message: 'Wajah berhasil diambil otomatis.',
            });
            setCaptureProgress(1);
            setProgressOrbState('success');
            startCameraButton.style.display = 'none';
            enrollButton.style.display = 'block';
        } catch (error) {
            faceRecognition.stopCamera(video);
            cameraReady = false;
            placeholder.style.display = 'flex';
            video.style.display = 'none';
            setStatus(error.message || 'Scan wajah gagal.');
            setInstruction('Ulangi scan wajah dan pastikan wajah tepat di dalam oval.');
            updateGuideState({
                state: 'searching',
                message: 'Pusatkan wajah tepat di dalam oval.',
            });
            setCaptureProgress(0);
            setProgressOrbState('error');
            startCameraButton.style.display = 'block';
            startCameraButton.textContent = 'Mulai Scan';
        } finally {
            startCameraButton.disabled = false;
            if (startCameraButton.style.display !== 'none') {
                startCameraButton.textContent = 'Mulai Scan';
            }
        }
    }

    async function submitEnrollment() {
        if (!enrollmentResult) {
            return;
        }

        enrollButton.disabled = true;
        enrollButton.textContent = 'Menyimpan...';

        try {
            const response = await fetch(enrollUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    face_data: enrollmentResult.face_descriptor,
                    liveness_score: enrollmentResult.liveness_score,
                    liveness_challenges: enrollmentResult.liveness_challenges,
                    device_info: navigator.userAgent || null,
                }),
            });

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Pendaftaran wajah gagal disimpan.');
            }

            setStatus('Wajah berhasil didaftarkan. Halaman akan dimuat ulang.', 'success');
            setInstruction('Pendaftaran wajah selesai.');
            showSimpleAlert(payload.message || 'Wajah berhasil didaftarkan.', 'success');
            window.setTimeout(function () {
                window.location.href = @json(route('mobile.presensi'));
            }, 1200);
        } catch (error) {
            enrollButton.disabled = false;
            enrollButton.textContent = 'Simpan Wajah';
            setStatus(error.message || 'Pendaftaran wajah gagal disimpan.', 'error');
            showSimpleAlert(error.message || 'Pendaftaran wajah gagal disimpan.', 'error');
        }
    }

    startCameraButton.addEventListener('click', function () {
        if (!onboardingAccepted) {
            return;
        }

        runEnrollment();
    });

    if (startCameraButton) {
        startCameraButton.disabled = true;
    }

    if (onboardingContinueButton) {
        onboardingContinueButton.focus();
    }

    if (onboardingContinueButton) {
        onboardingContinueButton.addEventListener('click', function () {
            onboardingAccepted = true;
            if (onboarding) {
                onboarding.hidden = true;
            }
            if (startCameraButton) {
                startCameraButton.disabled = false;
                startCameraButton.focus();
            }
        });
    }

    if (faceHelpButton) {
        faceHelpButton.addEventListener('click', function () {
            if (!onboarding) {
                return;
            }

            onboarding.hidden = false;
            if (onboardingContinueButton) {
                onboardingContinueButton.focus();
            }
        });
    }

    enrollButton.addEventListener('click', submitEnrollment);

    updateGuideTone('searching');
    updateProgressRing();
});
</script>
@endsection
