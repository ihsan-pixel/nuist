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
        <output id="python-face-status" class="alert alert-info" aria-live="polite">Menyiapkan kamera dan lokasi...</output>
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
    }

    function readLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Lokasi tidak didukung oleh browser.'));
                return;
            }
            navigator.geolocation.getCurrentPosition(
                position => resolve(position),
                () => reject(new Error('Izin lokasi diperlukan untuk presensi.')),
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
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
                await new Promise(resolve => setTimeout(resolve, 280));
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

    submit.addEventListener('click', verifyFace);
    window.addEventListener('pagehide', stopCamera);

    Promise.all([
        startCamera(),
        readLocation().then(position => { location = position; }),
    ]).then(() => {
        setStatus('Kamera siap. Tetap diam, verifikasi dimulai otomatis.', 'success');
        // Let auto-exposure settle before collecting the recognition burst.
        window.setTimeout(verifyFace, 900);
    }).catch(error => {
        showRetry(error.message || 'Kamera atau lokasi belum siap.');
    });
})();
</script>
@endsection
