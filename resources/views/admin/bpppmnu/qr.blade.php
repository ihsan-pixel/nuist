@extends('admin.bpppmnu.layout')
@section('bpp-content')
<style>
.bpp-qr-card{max-width:620px;margin:0 auto;border:0;border-radius:16px;box-shadow:0 4px 18px rgba(31,55,45,.08)}
.bpp-qr-card .card-body{padding:clamp(18px,5vw,36px)}
.bpp-qr-title{font-size:clamp(16px,4vw,22px);word-break:break-word}
#event-qr{position:relative;width:min(100%,360px);aspect-ratio:1/1;margin:20px auto;padding:12px;display:flex;align-items:center;justify-content:center;background:#fff;border:3px solid #0b6b3a;border-radius:16px;box-shadow:0 0 0 6px #e8f3ec}
#event-qr-code,#event-qr-code svg{display:block;width:100%;height:100%;max-width:100%;max-height:100%}
#event-qr-logo{position:absolute;width:18%;height:18%;object-fit:contain;padding:5px;background:#fff;border:2px solid #0b6b3a;border-radius:8px;z-index:2}
.bpp-qr-actions{display:flex;justify-content:center;gap:8px;flex-wrap:wrap}.bpp-qr-actions .btn{min-width:150px}
</style>
<div class="card bpp-qr-card"><div class="card-body text-center"><h2 class="bpp-qr-title">{{ $event->name }}</h2><p>QR berlaku sampai {{ $event->attendance_close_at->format('d-m-Y H:i') }} WIB.</p><p id="qr-status" class="text-muted">Setiap QR hanya berlaku untuk satu perangkat. QR akan berubah otomatis setelah dipindai.</p>
<div id="event-qr"><div id="event-qr-code">{!! $svg !!}</div><img id="event-qr-logo" src="{{ asset('images/logo-maarif-nu.png') }}" alt="Logo LP Ma’arif NU"></div>
<a id="public-qr-url" class="d-block small text-break mb-3" href="{{ $publicUrl }}" target="_blank" rel="noopener">{{ $publicUrl }}</a>
<div class="bpp-qr-actions"><button id="download-qr" type="button" class="btn btn-primary">Unduh QR (SVG)</button>
<a href="{{ route('admin.agenda.show', $event) }}" class="btn btn-outline-secondary">Kembali ke Rekap</a></div>
</div></div>
<script>
let qrVersion = @json($version);
let polling = false;

document.getElementById('download-qr').addEventListener('click', () => {
    const content = document.querySelector('#event-qr-code svg').outerHTML;
    const url = URL.createObjectURL(new Blob([content], {type:'image/svg+xml'}));
    const link = document.createElement('a'); link.href = url; link.download = 'qr-agenda-{{ $event->id }}.svg'; link.click(); setTimeout(() => URL.revokeObjectURL(url), 1000);
});

async function refreshQr() {
    if (polling || document.hidden) return;
    polling = true;
    try {
        const response = await fetch(@json(route('admin.agenda.qr-status', $event)), {
            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            cache: 'no-store'
        });
        if (!response.ok) return;
        const data = await response.json();
        if (String(data.version) !== String(qrVersion)) {
            qrVersion = String(data.version);
            document.getElementById('event-qr-code').innerHTML = data.svg;
            const publicUrl = document.getElementById('public-qr-url');
            publicUrl.href = data.url;
            publicUrl.textContent = data.url;
            const status = document.getElementById('qr-status');
            status.textContent = 'QR telah diperbarui otomatis dan siap untuk peserta berikutnya.';
            status.classList.remove('text-muted');
            status.classList.add('text-success');
        }
    } catch (_) {
        // Poll berikutnya akan mencoba kembali bila koneksi sempat terputus.
    } finally {
        polling = false;
    }
}

setInterval(refreshQr, 1500);
document.addEventListener('visibilitychange', () => { if (!document.hidden) refreshQr(); });
</script>
@endsection
