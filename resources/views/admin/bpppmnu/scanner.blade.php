@extends('admin.bpppmnu.layout')
@section('bpp-content')
<style>.scanner-shell{max-width:620px;margin:auto}.scanner-video{width:100%;min-height:300px;object-fit:cover;border-radius:16px;background:#10271f}.scanner-shell .card{border-radius:16px}</style>
<div class="scanner-shell"><div class="card"><div class="card-body"><a href="{{ route('admin.bpppmnu.events.show', $event) }}" class="btn btn-light mb-3">← Kembali</a><h2 class="h5">Scan Peserta</h2><p class="text-muted small">Arahkan kamera ke barcode peserta agenda ini.</p><video id="scanner-video" class="scanner-video" muted playsinline></video><div id="scanner-status" class="alert alert-secondary mt-3">Membuka kamera…</div><button id="scanner-stop" class="btn btn-outline-secondary">Tutup Kamera</button></div></div></div>
<script src="{{ asset('vendor/jsqr/jsQR.js') }}"></script><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(() => {
    const video = document.getElementById('scanner-video');
    const status = document.getElementById('scanner-status');
    const stopButton = document.getElementById('scanner-stop');
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d', { willReadFrequently: true });
    let stream = null;
    let timer = null;
    let busy = false;

    function stopCamera() {
        clearTimeout(timer);
        if (stream) stream.getTracks().forEach(track => track.stop());
        stream = null;
    }

    async function scanFrame() {
        if (!stream || busy) return;
        if (video.readyState >= 2 && video.videoWidth > 0) {
            canvas.width = Math.min(video.videoWidth, 800);
            canvas.height = Math.round(video.videoHeight * canvas.width / video.videoWidth);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const result = window.jsQR(context.getImageData(0, 0, canvas.width, canvas.height).data, canvas.width, canvas.height);
            if (result) {
                busy = true;
                stopCamera();
                try {
                    const response = await fetch('{{ route('admin.bpppmnu.events.scan-member', $event) }}', {method:'POST', headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify({nuist_id:result.data.trim()})});
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Barcode tidak dikenali.');
                    await Swal.fire({icon:'success', title:'Presensi berhasil', text:data.name, confirmButtonColor:'#00553f'});
                    window.location.reload();
                    return;
                } catch (error) {
                    status.textContent = error.message;
                    busy = false;
                    startCamera();
                    return;
                }
            }
        }
        timer = setTimeout(scanFrame, 180);
    }

    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            status.className = 'alert alert-danger mt-3';
            status.textContent = 'Browser tidak mendukung kamera. Gunakan HTTPS dan browser terbaru.';
            return;
        }
        try {
            stream = await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}}, audio:false});
            video.srcObject = stream;
            await video.play();
            status.className = 'alert alert-success mt-3';
            status.textContent = 'Kamera aktif. Arahkan ke barcode peserta.';
            scanFrame();
        } catch (error) {
            status.className = 'alert alert-danger mt-3';
            status.textContent = error.name === 'NotAllowedError' ? 'Izin kamera ditolak. Izinkan kamera pada pengaturan browser.' : 'Kamera tidak dapat dibuka: ' + error.message;
        }
    }

    stopButton.addEventListener('click', () => { stopCamera(); window.location.href = '{{ route('admin.bpppmnu.events.show', $event) }}'; });
    window.addEventListener('load', startCamera);
})();
</script>
@endsection
