@extends('admin.bpppmnu.layout')
@section('bpp-content')
<div class="card"><div class="card-body text-center"><h2>{{ $event->name }}</h2><p>QR berlaku sampai {{ $event->attendance_close_at->format('d-m-Y H:i') }} WIB.</p><p class="text-muted">QR sebelumnya sudah dicabut. Simpan QR ini sebelum meninggalkan halaman.</p>
<div id="event-qr" style="max-width:360px;margin:auto">{!! $svg !!}</div>
<button id="download-qr" type="button" class="btn btn-primary mt-3">Unduh QR (SVG)</button>
<a href="{{ route('admin.bpppmnu.events.show', $event) }}" class="btn btn-outline-secondary mt-3">Kembali ke Rekap</a>
</div></div>
<script>
document.getElementById('download-qr').addEventListener('click', () => {
    const content = document.getElementById('event-qr').innerHTML;
    const url = URL.createObjectURL(new Blob([content], {type:'image/svg+xml'}));
    const link = document.createElement('a'); link.href = url; link.download = 'qr-bpppmnu-{{ $event->id }}.svg'; link.click(); setTimeout(() => URL.revokeObjectURL(url), 1000);
});
</script>
@endsection
