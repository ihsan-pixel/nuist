@extends('layouts.mobile')

@section('title', 'Verifikasi Wajah')
@section('subtitle', $mode === 'masuk' ? 'Presensi Masuk' : 'Presensi Keluar')

@section('content')
<style>
    .face-check-page {
        --face-ink: #17312c;
        --face-muted: #62746d;
        --face-accent: #0b5b47;
        padding: 24px 16px 28px;
        background: #f7faf8;
    }

    .face-check-panel {
        width: min(100%, 420px);
        margin: 0 auto;
        text-align: center;
    }

    .face-check-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        text-align: left;
    }

    .face-check-eyebrow {
        margin: 0 0 5px;
        color: var(--face-muted);
        font-size: 12px;
        font-weight: 500;
    }

    .face-check-title {
        margin: 0;
        color: var(--face-ink);
        font-size: 23px;
        font-weight: 700;
        letter-spacing: -.5px;
    }

    .face-check-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 10px 14px;
        border: 1px solid #dce5df;
        border-radius: 12px;
        color: var(--face-ink);
        background: #fff;
        font-size: 13px;
        text-decoration: none;
    }

    .face-check-back:hover { background: #edf3ef; color: var(--face-ink); }
    .face-check-back:focus-visible,
    #python-face-submit:focus-visible { outline: 3px solid #78bba6; outline-offset: 3px; }

    .face-camera-shell {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border: 1px solid #dce5df;
        border-radius: 24px;
        background: #172822;
    }

    .face-camera-shell::after {
        position: absolute;
        inset: 12% 21%;
        border: 2px solid rgba(255, 255, 255, .85);
        border-radius: 50%;
        box-shadow: 0 0 0 200px rgba(9, 24, 18, .22);
        content: '';
        pointer-events: none;
    }

    #python-face-video {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }

    .face-camera-label {
        position: absolute;
        left: 50%;
        bottom: 16px;
        transform: translateX(-50%);
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 8px;
        color: #fff;
        background: rgba(9, 24, 18, .65);
        font-size: 11px;
        white-space: nowrap;
    }

    .face-check-instruction {
        margin: 20px 0 6px;
        color: var(--face-ink);
        font-size: 16px;
        font-weight: 600;
    }

    .face-check-hint {
        margin: 0 auto 20px;
        max-width: 300px;
        color: var(--face-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    #python-face-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 48px;
        margin: 0;
        padding: 12px 14px;
        border: 1px solid #dce5df;
        border-radius: 12px;
        background: #fff;
        color: var(--face-muted);
        font-size: 13px;
        line-height: 1.5;
        text-align: left;
        overflow-wrap: anywhere;
    }

    .face-status-icon { flex-shrink: 0; font-size: 20px; }
    #python-face-status.alert-success { background: #edf7f1; border-color: #cce7d7; color: #22613d; }
    #python-face-status.alert-danger { background: #fff3f1; border-color: #f2d6d0; color: #a13c2f; }

    #python-face-submit {
        width: 100%;
        min-height: 48px;
        margin-top: 12px;
        border: 0;
        border-radius: 12px;
        background: var(--face-accent);
        font-size: 14px;
        font-weight: 600;
    }

    #python-face-submit:hover:not(:disabled) { background: #084737; }
    #python-face-submit[hidden] { display: none; }

    @media (max-width: 360px) {
        .face-check-page { padding: 18px 12px 24px; }
        .face-check-title { font-size: 21px; }
        .face-check-toolbar { margin-bottom: 18px; }
    }
</style>

<main class="face-check-page">
    <section class="face-check-panel" aria-labelledby="face-check-title">
        <header class="face-check-toolbar">
            <div>
                <p class="face-check-eyebrow">Presensi {{ $mode === 'masuk' ? 'Masuk' : 'Keluar' }}</p>
                <h1 id="face-check-title" class="face-check-title">Verifikasi wajah</h1>
            </div>
            <a href="{{ $presensiUrl }}" class="face-check-back">Batal</a>
        </header>

        <figure class="face-camera-shell m-0">
            <video id="python-face-video" autoplay muted playsinline aria-label="Preview kamera wajah"></video>
            <figcaption class="face-camera-label"><i class="bx bx-camera me-1"></i>Kamera depan</figcaption>
        </figure>
        <canvas id="python-face-canvas" hidden></canvas>

        <p class="face-check-instruction">Posisikan wajah di dalam bingkai</p>
        <p class="face-check-hint">Pastikan wajah cukup terang dan ponsel stabil. Scan akan dimulai otomatis.</p>
        <output id="python-face-status" class="alert alert-info" aria-live="polite" aria-atomic="true">
            <i class="bx bx-camera face-status-icon" aria-hidden="true"></i>
            <span class="face-status-message">Menyiapkan kamera...</span>
        </output>
        <button id="python-face-submit" type="button" class="btn btn-success" hidden>
            <span class="button-label">Scan ulang</span>
            <span class="spinner-border spinner-border-sm ms-1" hidden role="status" aria-hidden="true"></span>
        </button>
    </section>
</main>
@endsection

@section('script')
<script>
(() => {
    const video = document.getElementById('python-face-video');
    const canvas = document.getElementById('python-face-canvas');
    const status = document.getElementById('python-face-status');
    const statusMessage = status.querySelector('.face-status-message');
    const statusIcon = status.querySelector('.face-status-icon');
    const submit = document.getElementById('python-face-submit');
    const label = submit.querySelector('.button-label');
    const spinner = submit.querySelector('.spinner-border');
    const mode = @json($mode);
    const endpoint = @json($presensiStoreUrl);
    const presensiUrl = @json($presensiUrl);
    const csrf = @json(csrf_token());
    const userId = @json($user->id);
    let stream = null;
    let location = null;
    let verificationInFlight = false;

    function setStatus(message, type = 'info') {
        status.className = `alert alert-${type}`;
        statusMessage.textContent = message;
        const icon = type === 'success' ? 'bx-check-circle' : type === 'danger' ? 'bx-error-circle' : 'bx-camera';
        statusIcon.className = `bx ${icon} face-status-icon`;
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
    }

    async function startCamera() {
        if (!navigator.mediaDevices?.getUserMedia) {
            throw new Error('Kamera tidak tersedia pada browser ini.');
        }
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 720 }, height: { ideal: 540 } },
            audio: false,
        });
        video.srcObject = stream;
        await video.play();
        await new Promise((resolve, reject) => {
            const deadline = Date.now() + 10000;
            function check() {
                if (video.readyState >= 2 && video.videoWidth > 0 && video.videoHeight > 0) {
                    resolve();
                } else if (Date.now() >= deadline) {
                    reject(new Error('Gambar kamera belum siap. Silakan coba kembali.'));
                } else {
                    window.setTimeout(check, 100);
                }
            }
            check();
        });
    }

    function readLocation() {
        let saved;
        try {
            saved = JSON.parse(sessionStorage.getItem('python-presensi-location') || 'null');
        } catch (_) {
            saved = null;
        }
        const reading = saved?.reading;
        if (saved?.userId !== userId || !reading
            || !Number.isFinite(reading.latitude) || Math.abs(reading.latitude) > 90
            || !Number.isFinite(reading.longitude) || Math.abs(reading.longitude) > 180
            || !Number.isFinite(reading.timestamp)
            || Date.now() - reading.timestamp < 0 || Date.now() - reading.timestamp > 120000) {
            throw new Error('Lokasi dari halaman presensi sudah tidak tersedia atau kedaluwarsa. Kembali ke halaman presensi untuk memperbarui lokasi.');
        }
        return {
            coords: reading,
            lokasi: typeof saved.lokasi === 'string' && saved.lokasi.trim()
                ? saved.lokasi.trim()
                : `${reading.latitude.toFixed(6)}, ${reading.longitude.toFixed(6)}`,
            readings: Array.isArray(saved.readings) ? saved.readings : [reading],
        };
    }

    function captureFrame() {
        const width = video.videoWidth || 720;
        const height = video.videoHeight || 540;
        const targetWidth = Math.min(width, 720);
        const targetHeight = Math.round(targetWidth * height / width);
        canvas.width = targetWidth;
        canvas.height = targetHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, targetWidth, targetHeight);
        return canvas.toDataURL('image/jpeg', 0.82);
    }

    async function captureFrames() {
        const frames = [];
        for (let index = 0; index < 5; index++) {
            frames.push(captureFrame());
            if (index < 4) {
                await new Promise(resolve => setTimeout(resolve, 180));
            }
        }
        return frames;
    }

    function showRetry(message) {
        setStatus(message, 'danger');
        label.textContent = 'Scan ulang';
        spinner.hidden = true;
        submit.hidden = false;
        submit.disabled = false;
    }

    async function verifyFace() {
        if (verificationInFlight || !location || !stream) {
            return;
        }

        verificationInFlight = true;
        submit.hidden = true;
        spinner.hidden = false;
        setStatus('Memeriksa wajah...', 'info');

        try {
            location = readLocation();
            const frames = await captureFrames();
            const response = await fetch(endpoint, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    face_engine: 'python',
                    face_frames: frames,
                    selfie_data: frames[frames.length - 1],
                    presensi_mode: mode,
                    latitude: location.coords.latitude,
                    longitude: location.coords.longitude,
                    lokasi: location.lokasi,
                    location_readings: JSON.stringify(location.readings),
                    accuracy: location.coords.accuracy,
                    altitude: location.coords.altitude,
                    speed: location.coords.speed,
                    device_info: `mobile_web_${navigator.platform || 'browser'}`,
                }),
            });
            const payload = await response.json().catch(() => ({}));
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Wajah belum cocok.');
            }

            setStatus('Wajah cocok. Presensi berhasil dicatat.', 'success');
            setTimeout(() => window.location.assign(presensiUrl), 600);
        } catch (error) {
            verificationInFlight = false;
            showRetry(error.message || 'Wajah belum cocok. Silakan scan ulang.');
        }
    }

    submit.addEventListener('click', () => {
        if (!stream || video.readyState < 2) {
            initialize();
        } else {
            verifyFace();
        }
    });
    window.addEventListener('pagehide', stopCamera);

    let initializing = false;
    async function initialize() {
        if (initializing || verificationInFlight) return;
        initializing = true;
        submit.hidden = true;
        setStatus('Menyiapkan kamera...', 'info');
        try {
            location = readLocation();
            stopCamera();
            await startCamera();
            setStatus('Kamera siap. Pegang ponsel stabil dan pastikan wajah cukup terang.', 'success');
            await new Promise(resolve => window.setTimeout(resolve, 600));
            initializing = false;
            await verifyFace();
        } catch (error) {
            stopCamera();
            showRetry(error.message || 'Kamera belum siap.');
        } finally {
            initializing = false;
        }
    }
    initialize();
})();
</script>
@endsection
