@extends('admin.bpppmnu.layout')
@section('bpp-content')
<style>
.bpp-login-qr-card{max-width:620px;margin:0 auto;border:0;border-radius:16px;box-shadow:0 4px 18px rgba(31,55,45,.08)}
.bpp-login-qr-card .card-body{padding:clamp(18px,5vw,36px)}
.bpp-login-qr-card h1{font-size:clamp(18px,4vw,24px)}
#mobile-login-qr{width:min(100%,360px);margin:24px auto;background:#fff}
#mobile-login-qr svg{display:block;width:100%;height:auto}
.bpp-login-qr-actions{display:flex;justify-content:center;gap:8px;flex-wrap:wrap}
.bpp-login-url{overflow-wrap:anywhere}
</style>
<div class="card bpp-login-qr-card"><div class="card-body text-center">
    <h1>Login NUIST Mobile</h1>
    <p class="text-muted">Pindai kode QR ini dengan kamera ponsel untuk membuka halaman login NUIST Mobile.</p>
    <div id="mobile-login-qr" role="img" aria-label="Kode QR menuju halaman login NUIST Mobile">{!! $svg !!}</div>
    <p class="bpp-login-url"><a href="{{ $loginUrl }}" target="_blank" rel="noopener">{{ $loginUrl }}</a></p>
    <div class="bpp-login-qr-actions">
        <button id="download-login-qr" type="button" class="btn btn-success">Unduh QR (SVG)</button>
        <a href="{{ route('admin.bpppmnu.events.show', $event) }}" class="btn btn-outline-secondary">Kembali ke Kegiatan</a>
    </div>
</div></div>
<script>
document.getElementById('download-login-qr').addEventListener('click', () => {
    const content = document.querySelector('#mobile-login-qr svg').outerHTML;
    const url = URL.createObjectURL(new Blob([content], {type: 'image/svg+xml'}));
    const link = document.createElement('a');
    link.href = url;
    link.download = 'qr-login-nuist-mobile.svg';
    link.click();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
});
</script>
@endsection
