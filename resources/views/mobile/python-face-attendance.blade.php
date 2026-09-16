@extends('layouts.mobile')

@section('title', 'Verifikasi Wajah')
@section('subtitle', $mode === 'masuk' ? 'Presensi Masuk' : 'Presensi Keluar')

@section('content')
<style>
    .face-check-page {
        --face-ink: #17312c;
        --face-muted: #718087;
        --face-accent: #0b5b47;
        min-height: calc(100vh - 150px);
        display: grid;
        place-items: center;
        padding: 14px 12px 26px;
        background: linear-gradient(150deg, #f8fbfa 0%, #eef5f3 100%);
    }

    .face-check-panel {
        width: min(100%, 410px);
        text-align: center;
    }

    .face-check-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        text-align: left;
    }

    .face-check-eyebrow {
        margin: 0 0 2px;
        color: var(--face-accent);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .face-check-title {
        margin: 0;
        color: var(--face-ink);
        font-size: 19px;
        font-weight: 700;
    }

    .face-check-back {
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 12px;
    }

    .face-camera-shell {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        border-radius: 18px;
        background: #101827;
        box-shadow: 0 14px 32px rgba(23, 49, 44, .16);
    }

    .face-camera-shell::after {
        position: absolute;
        inset: 11%;
        border: 1px solid rgba(255, 255, 255, .72);
        border-radius: 46% 46% 42% 42%;
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
        right: 11px;
        bottom: 10px;
        z-index: 1;
        padding: 4px 8px;
        border-radius: 999px;
        color: #fff;
        background: rgba(16, 24, 39, .68);
        font-size: 10px;
    }

    .face-check-instruction {
        margin: 13px auto 11px;
        max-width: 330px;
        color: var(--face-muted);
        font-size: 12px;
        line-height: 1.45;
    }

    #python-face-status {
        min-height: 42px;
        display: grid;
        place-items: center;
        margin: 0 0 11px;
        padding: 9px 12px;
        border: 0;
        border-radius: 11px;
        font-size: 12px;
        line-height: 1.35;
    }

    #python-face-submit {
        width: 100%;
        min-height: 44px;
        border: 0;
        border-radius: 11px;
        background: var(--face-accent);
        font-size: 13px;
        font-weight: 600;
    }

    #python-face-submit:hover:not(:disabled) {
        background: #084737;
    }
</style>

<main class="face-check-page">
    <section class="face-check-panel" aria-labelledby="face-check-title">
        <header class="face-check-toolbar">
            <span>
                <p class="face-check-eyebrow">Presensi {{ $mode === 'masuk' ? 'Masuk' : 'Keluar' }}</p>
                <h1 id="face-check-title" class="face-check-title">Verifikasi wajah</h1>
            </span>
            <a href="{{ $presensiUrl }}" class="btn btn-sm btn-outline-secondary face-check-back">Batal</a>
        </header>

        <figure class="face-camera-shell m-0">
            <video id="python-face-video" autoplay muted playsinline aria-label="Preview kamera wajah"></video>
            <figcaption class="face-camera-label"><i class="bx bx-camera me-1"></i>Kamera depan</figcaption>
        </figure>
        <canvas id="python-face-canvas" hidden></canvas>

        <p class="face-check-instruction">Arahkan wajah ke tengah bingkai dan tetap diam sejenak.</p>
        <output id="python-face-status" class="alert alert-info" aria-live="polite">Menyiapkan kamera...</output>
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
        status.textContent = message;
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
