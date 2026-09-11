@extends('mobile.bpppmnu.layout')
@section('title', 'Detail Kegiatan')
@section('bpp-content')
<a class="btn btn-sm btn-outline-secondary mb-3" href="{{ route('mobile.bpppmnu.presensi') }}">Kembali ke Presensi</a>
<div class="card"><div class="card-body"><h2>{{ $event->name }}</h2>
@if($event->status === 'cancelled')<div class="alert alert-warning">Kegiatan telah dibatalkan.</div>@endif
@include('bpppmnu.event-details')
@if($event->attachment)<a href="{{ route('mobile.bpppmnu.events.attachment', $event) }}" class="btn btn-outline-secondary btn-sm">Unduh lampiran undangan</a>@endif
</div></div>
@if($attendance)
<div class="alert alert-success">Sudah Hadir · {{ $attendance->attended_at->format('d-m-Y H:i:s') }} WIB</div>
@elseif($event->isOpen())
<section id="scanner" class="card" data-event-id="{{ $event->id }}" data-scan-url="{{ route('mobile.bpppmnu.events.scan', $event) }}"><div class="card-body">
<h2>Scan QR</h2><p class="bpp-meta">Arahkan kamera ke QR kegiatan yang ditampilkan oleh admin.</p>
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
