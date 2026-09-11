@extends('mobile.bpppmnu.layout')
@section('title', 'Detail Kegiatan')
@section('bpp-shell-class', 'bpp-detail-shell')
@section('bpp-header')
@endsection
@section('bpp-content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.bpp-detail-scanner{display:none!important}.bpp-recap{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px}.bpp-recap-item{background:#fff;border:1px solid #e6eae8;border-radius:10px;padding:12px 8px;text-align:center}.bpp-recap-value{display:block;font-size:17px;font-weight:600;color:#00553f}.bpp-recap-label{display:block;font-size:9px;color:#68736e;margin-top:3px}
.bpp-detail-shell.bpp-scanner-only .bpp-detail-back,.bpp-detail-shell.bpp-scanner-only .bpp-detail-overview{display:none!important}.bpp-detail-shell.bpp-scanner-only .bpp-detail-scanner{margin-top:8px;min-height:calc(100vh - 150px);display:flex;flex-direction:column;justify-content:center}
.bpp-detail-shell > .bpp-header { display:none !important; }
.bpp-detail-shell{background:#f6f7f6}.bpp-detail-back{display:inline-flex;align-items:center;gap:6px;min-height:40px;color:#00553f;text-decoration:none;font-size:11px;margin-bottom:14px}.bpp-detail-card{background:#fff;border:1px solid #e6eae8;border-radius:13px;padding:18px;margin-bottom:12px}.bpp-detail-title{font-size:16px;font-weight:600;line-height:1.5;margin:10px 0 16px;overflow-wrap:anywhere}.bpp-detail-status{display:inline-block;padding:4px 8px;border-radius:5px;font-size:10px;background:#eef3ef;color:#286248}.bpp-detail-shell dl{margin:0}.bpp-detail-shell dt{font-size:10px;color:#68736e;margin-top:14px;border-top:1px solid #edf0ed;padding-top:10px}.bpp-detail-shell dd{font-size:11px;color:#344e40;margin:3px 0 0;line-height:1.7}.bpp-detail-shell .bpp-detail{white-space:pre-wrap}.bpp-detail-attachment{display:inline-flex;min-height:40px;align-items:center;color:#00553f;font-size:11px;text-decoration:none;font-weight:600}.bpp-detail-scanner{border-color:#bdd8c7}.bpp-detail-scanner h2{font-size:13px;font-weight:600;margin:0}.bpp-detail-scanner .bpp-scanner-heading{margin-bottom:10px}.bpp-detail-scanner p{font-size:11px}.bpp-detail-scanner video{margin-top:10px}.bpp-detail-shell .alert{font-size:11px;border-radius:9px}
</style>
<a class="bpp-detail-back" href="{{ route('mobile.bpppmnu.presensi') }}"><i class="bx bx-arrow-back" aria-hidden="true"></i>Kembali ke Agenda</a>
<div class="bpp-detail-card bpp-detail-overview"><span class="bpp-detail-status">{{ $event->status === 'cancelled' ? 'Dibatalkan' : ($event->isOpen() ? 'Presensi Dibuka' : (now()->lt($event->attendance_open_at) ? 'Akan Datang' : 'Presensi Ditutup')) }}</span><h1 class="bpp-detail-title">{{ $event->name }}</h1>
@if($event->status === 'cancelled')<div class="alert alert-warning">Kegiatan telah dibatalkan.</div>@endif
@include('bpppmnu.event-details')
@if($event->attachment)<a href="{{ route('mobile.bpppmnu.events.attachment', $event) }}" class="bpp-detail-attachment"><i class="bx bx-download" aria-hidden="true"></i>&nbsp;Unduh lampiran undangan</a>@endif
</div>
<div class="bpp-recap" id="bpp-recap" data-url="{{ route('mobile.bpppmnu.events.recap', $event) }}"><div class="bpp-recap-item"><span class="bpp-recap-value" data-recap="present">{{ $recap['present'] }}</span><span class="bpp-recap-label">Sudah hadir</span></div><div class="bpp-recap-item"><span class="bpp-recap-value" data-recap="remaining">{{ $recap['remaining'] }}</span><span class="bpp-recap-label">Belum hadir</span></div><div class="bpp-recap-item"><span class="bpp-recap-value" data-recap="percentage">{{ $recap['percentage'] }}%</span><span class="bpp-recap-label">Kehadiran</span></div></div>
<script>setInterval(()=>{const r=document.getElementById('bpp-recap');if(!r)return;fetch(r.dataset.url,{headers:{Accept:'application/json'},credentials:'same-origin'}).then(x=>x.json()).then(d=>{r.querySelector('[data-recap="present"]').textContent=d.present;r.querySelector('[data-recap="remaining"]').textContent=d.remaining;r.querySelector('[data-recap="percentage"]').textContent=d.percentage+'%'}).catch(()=>{});},10000);</script>
@if($attendance)
<div class="alert alert-success">✓ Sudah hadir · {{ $attendance->attended_at->format('d-m-Y H:i:s') }} WIB</div>
@elseif($event->isOpen())
<section id="scanner" class="bpp-detail-card bpp-detail-scanner" data-event-id="{{ $event->id }}" data-scan-url="{{ route('mobile.bpppmnu.events.scan', $event) }}"><div>
<div class="bpp-scanner-heading"><i class="bx bx-qr-scan" aria-hidden="true"></i><h2>Scan Barcode Kehadiran</h2></div>
<video id="qr-video" muted playsinline hidden></video>
<div id="qr-status" class="my-3" role="status" aria-live="polite"></div>
<button id="qr-start" type="button" class="btn btn-primary">Buka Kamera</button>
<button id="qr-stop" type="button" class="btn btn-outline-secondary" hidden>Tutup Kamera</button>
</div></section>
<script src="{{ asset('vendor/jsqr/jsQR.js') }}" defer></script>
<script src="{{ asset('js/bpppmnu-scanner.js') }}" defer></script>
@elseif($event->status === 'published')
<div class="alert alert-secondary">{{ now()->lt($event->attendance_open_at) ? 'Presensi kegiatan belum dibuka.' : 'Waktu presensi kegiatan telah berakhir.' }}</div>
@endif
@endsection
