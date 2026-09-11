(() => {
    'use strict';
    const root = document.getElementById('scanner');
    if (!root) return;
    const video = document.getElementById('qr-video');
    const status = document.getElementById('qr-status');
    const start = document.getElementById('qr-start');
    const stopButton = document.getElementById('qr-stop');
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d', {willReadFrequently: true});
    let stream, timer, busy = false, completed = false, generation = 0;
    const stop = () => {
        generation++;
        clearTimeout(timer);
        stream?.getTracks().forEach(track => track.stop());
        stream = null;
        video.srcObject = null;
        video.hidden = true;
        stopButton.hidden = true;
        start.disabled = busy || completed;
    };
    const message = (text, success = false) => {
        status.textContent = text;
        status.className = 'my-3 alert ' + (success ? 'alert-success' : 'alert-secondary');
    };
    async function submit(raw) {
        let data;
        try { data = JSON.parse(raw); } catch (_) { message('QR presensi tidak valid atau sudah tidak berlaku.'); stop(); return; }
        if (data.type !== 'bpppmnu' || String(data.event_id) !== root.dataset.eventId || !/^[a-f0-9]{64}$/.test(data.token || '')) {
            message('QR tidak sesuai dengan kegiatan ini.'); stop(); return;
        }
        busy = true;
        stop();
        message('Memvalidasi presensi…');
        try {
            const response = await fetch(root.dataset.scanUrl, {
                method: 'POST', credentials: 'same-origin',
                headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                body: JSON.stringify({qr_token: data.token})
            });
            const body = await response.json();
            if (!response.ok) throw new Error(body.message || 'Presensi belum berhasil. Silakan coba lagi.');
            completed = true;
            message(body.message + ' ' + body.event_name + ' · ' + body.attended_at, true);
            if (window.Swal) {
                Swal.fire({icon: 'success', title: 'Presensi berhasil', text: body.message, confirmButtonText: 'OK', confirmButtonColor: '#00553f'});
            }
            start.textContent = 'Sudah Hadir';
        } catch (error) {
            message(error instanceof TypeError ? 'Koneksi gagal. Presensi belum terkonfirmasi. Periksa koneksi lalu coba lagi.' : error.message);
        } finally { busy = false; start.disabled = completed; }
    }
    function tick() {
        if (!stream || busy) return;
        if (video.readyState >= 2) {
            canvas.width = Math.min(video.videoWidth, 800);
            canvas.height = Math.round(video.videoHeight * canvas.width / video.videoWidth);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const pixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = window.jsQR(pixels.data, pixels.width, pixels.height, {inversionAttempts: 'dontInvert'});
            if (code) { submit(code.data); return; }
        }
        timer = setTimeout(tick, 180);
    }
    start.addEventListener('click', async () => {
        if (busy || completed) return;
        stop();
        const attempt = generation;
        start.disabled = true;
        try {
            if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) throw new Error('Kamera memerlukan HTTPS dan browser yang mendukung akses kamera.');
            if (!window.jsQR) throw new Error('Pemindai belum termuat. Muat ulang halaman.');
            const candidate = await navigator.mediaDevices.getUserMedia({video: {facingMode: {ideal: 'environment'}}, audio: false});
            if (attempt !== generation) { candidate.getTracks().forEach(t => t.stop()); return; }
            stream = candidate;
            video.srcObject = stream; video.hidden = false; stopButton.hidden = false;
            await video.play();
            message('Arahkan kamera ke QR kegiatan.');
            tick();
        } catch (error) {
            stop();
            message(error.name === 'NotAllowedError' ? 'Izin kamera ditolak. Aktifkan izin kamera di pengaturan browser.' : error.message);
        }
    });
    stopButton.addEventListener('click', stop);
    window.addEventListener('pagehide', stop);
    document.addEventListener('visibilitychange', () => { if (document.hidden) stop(); });
})();
