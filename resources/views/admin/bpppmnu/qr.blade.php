@extends('admin.bpppmnu.layout')
@section('bpp-content')
<style>
.bpp-qr-card{max-width:620px;margin:0 auto;border:0;border-radius:16px;box-shadow:0 4px 18px rgba(31,55,45,.08)}
.bpp-qr-card .card-body{padding:clamp(18px,5vw,36px)}
.bpp-qr-title{font-size:clamp(16px,4vw,22px);word-break:break-word}
#event-qr{width:min(100%,360px);aspect-ratio:1/1;margin:20px auto;padding:12px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e4ece7;border-radius:14px}
#event-qr svg{display:block;width:100%;height:100%;max-width:100%;max-height:100%}
.bpp-qr-actions{display:flex;justify-content:center;gap:8px;flex-wrap:wrap}.bpp-qr-actions .btn{min-width:150px}
</style>
<div class="card bpp-qr-card"><div class="card-body text-center"><h2 class="bpp-qr-title">{{ $event->name }}</h2><p>QR berlaku sampai {{ $event->attendance_close_at->format('d-m-Y H:i') }} WIB.</p><p class="text-muted">QR sebelumnya sudah dicabut. Simpan QR ini sebelum meninggalkan halaman.</p>
<div id="event-qr">{!! $svg !!}</div>
<div class="bpp-qr-actions"><button id="download-qr" type="button" class="btn btn-primary">Unduh QR (SVG)</button>
<a href="{{ route('admin.bpppmnu.events.show', $event) }}" class="btn btn-outline-secondary">Kembali ke Rekap</a></div>
</div></div>
<script>
document.getElementById('download-qr').addEventListener('click', () => {
    const content = document.getElementById('event-qr').innerHTML;
    const url = URL.createObjectURL(new Blob([content], {type:'image/svg+xml'}));
    const link = document.createElement('a'); link.href = url; link.download = 'qr-bpppmnu-{{ $event->id }}.svg'; link.click(); setTimeout(() => URL.revokeObjectURL(url), 1000);
});
</script>
@endsection
