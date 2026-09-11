@extends('mobile.bpppmnu.layout')
@section('title', 'Presensi')
@section('bpp-shell-class', 'bpp-home-shell')
@section('bpp-header')
<header class="bpp-home-header">
    <div class="bpp-home-top">
        <small>NUIST · BPPPMNU</small>
        <div class="dropdown">
            <button type="button" class="bpp-home-menu" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu akun">
                <i class="bx bx-dots-vertical-rounded" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end bpp-home-dropdown">
                <li>
                    <form method="post" action="{{ route('mobile.bpppmnu.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <p>Selamat datang,</p>
    <h1>{{ auth()->user()->name }}</h1>
</header>
@endsection
@section('bpp-content')
<style>
.bpp-shell.bpp-home-shell { background:#f6f7f6; color:#18201d; font-size:11px; }
.bpp-home-header { padding:0 0 20px; border-bottom:1px solid #e6eae8; margin-bottom:20px; }
.bpp-home-top { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:10px; }
.bpp-home-top small { color:#00553f; font-size:10px; font-weight:600; letter-spacing:.5px; }
.bpp-home-header p { margin:0 0 3px; color:#68736e; font-size:11px; }
.bpp-home-header h1 { margin:0; font-size:16px; font-weight:600; line-height:1.5; overflow-wrap:anywhere; }
.bpp-home-menu { display:grid; place-items:center; width:44px; height:44px; border:0; border-radius:10px; background:transparent; color:#18201d; font-size:22px; }
.bpp-home-menu:hover,.bpp-home-menu:active { background:#e9eeeb; }
.bpp-home-dropdown { min-width:130px; padding:4px; border:1px solid #e6eae8; border-radius:10px; box-shadow:0 4px 16px #18201d10; }
.bpp-home-dropdown .dropdown-item { min-height:44px; font-size:12px; border-radius:6px; }
.bpp-home-heading { margin-bottom:16px; }
.bpp-home-heading h2 { margin:0 0 4px; font-size:13px; font-weight:600; }
.bpp-home-heading p { margin:0; color:#68736e; font-size:11px; }
.bpp-agenda-card { display:grid; grid-template-columns:48px minmax(0,1fr) 16px; gap:12px; width:100%; padding:16px 14px; margin:0 0 10px; border:1px solid #e6eae8; border-radius:13px; background:#fff; color:#18201d; text-align:left; font:inherit; box-shadow:0 2px 5px #18201d03; }
.bpp-agenda-card:hover { border-color:#b7cec4; }
.bpp-agenda-card:active { background:#f0f5f2; }
.bpp-agenda-card:focus-visible,.bpp-home-menu:focus-visible,.bpp-sheet-close:focus-visible,.bpp-sheet-link:focus-visible { outline:2px solid #00866a; outline-offset:3px; }
.bpp-agenda-date { text-align:center; border-right:1px solid #e6eae8; padding-right:10px; color:#00553f; }
.bpp-agenda-date strong { display:block; font-size:16px; font-weight:600; line-height:1.3; }
.bpp-agenda-date small { display:block; font-size:10px; margin-top:3px; text-transform:uppercase; }
.bpp-agenda-info { min-width:0; }
.bpp-agenda-name { display:block; font-size:12px; font-weight:600; line-height:1.5; margin-bottom:5px; overflow-wrap:anywhere; }
.bpp-agenda-meta { display:block; color:#68736e; font-size:10px; line-height:1.6; overflow-wrap:anywhere; }
.bpp-agenda-chevron { align-self:center; color:#68736e; font-size:18px; }
.bpp-status { display:inline-block; padding:3px 7px; border-radius:5px; font-size:10px; line-height:1.5; font-weight:500; }
.bpp-agenda-info .bpp-status { margin-top:9px; }
.bpp-status--open { color:#00553f; background:#e6f3ed; }
.bpp-status--present { color:#286248; background:#edf5ef; }
.bpp-status--upcoming { color:#626b66; background:#f1f3f2; }
.bpp-status--closed { color:#737973; background:#f0f1f0; }
.bpp-home-empty { text-align:center; padding:26px 16px; border:1px solid #e6eae8; border-radius:12px; background:#fff; }
.bpp-home-empty>i { font-size:22px; color:#68736e; }
.bpp-home-empty h2 { font-size:12px; font-weight:600; margin:10px 0 5px; }
.bpp-home-empty p { margin:0; font-size:11px; color:#68736e; }
.bpp-home-pagination { margin-top:20px; }
.bpp-sheet { color:#18201d; font-family:Poppins,sans-serif; }
.bpp-sheet .modal-dialog { display:flex; align-items:flex-end; min-height:100%; margin:0 auto; max-width:560px; padding-top:24px; }
.bpp-sheet .modal-content { border:0; border-radius:16px 16px 0 0; background:#fff; max-height:calc(100vh - 24px); max-height:calc(100dvh - 24px); overflow:hidden; }
.bpp-sheet-bar { width:32px; height:4px; border-radius:4px; background:#d9dfdb; margin:10px auto 0; flex-shrink:0; }
.bpp-sheet-top { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:2px 18px 0; flex-shrink:0; }
.bpp-sheet-close { display:grid; place-items:center; width:44px; height:44px; border:0; border-radius:8px; background:transparent; color:#68736e; font-size:22px; }
.bpp-sheet .modal-body { padding:0 18px 18px; overflow-y:auto; overscroll-behavior:contain; -webkit-overflow-scrolling:touch; }
.bpp-sheet-title { font-size:14px!important; font-weight:600; line-height:1.5; margin:0 0 16px; overflow-wrap:anywhere; }
.bpp-sheet .bpp-sheet-details { margin:0; }
.bpp-sheet .bpp-sheet-details dt { margin:12px 0 3px; padding:0; border:0; font-size:10px; font-weight:400; color:#68736e; }
.bpp-sheet .bpp-sheet-details dd { margin:0; font-size:11px; line-height:1.7; overflow-wrap:anywhere; }
.bpp-sheet-description { white-space:pre-wrap; }
.bpp-sheet-footer { padding:12px 18px calc(12px + env(safe-area-inset-bottom, 0px)); border-top:1px solid #e6eae8; flex-shrink:0; }
.bpp-sheet-scan { display:flex; justify-content:center; align-items:center; min-height:44px; padding:10px; border-radius:9px; background:#00553f; color:#fff; font-size:11px; font-weight:600; text-decoration:none; }
.bpp-sheet-scan:hover { background:#004331; color:#fff; }
.bpp-sheet-scan:focus-visible { outline:2px solid #00866a; outline-offset:3px; }
.bpp-sheet-link { display:flex; align-items:center; justify-content:center; min-height:44px; color:#00553f; font-size:11px; text-decoration:none; }
.bpp-sheet-confirmation { color:#286248; font-size:11px; text-align:center; margin:0 0 6px; }
@media(min-width:768px) { .bpp-sheet .modal-dialog { align-items:center; padding:24px 0; } .bpp-sheet .modal-content { border-radius:14px; max-height:calc(100vh - 48px); } }
</style>

<div class="bpp-home-heading">
    <h2>Agenda Anda</h2>
    <p>Kegiatan yang perlu Anda hadiri</p>
</div>
@forelse($events as $event)
    @php
        $present = $event->attendances->isNotEmpty();
        $open = $event->isOpen();
        $status = $present ? 'present' : ($open ? 'open' : (now()->lt($event->attendance_open_at) ? 'upcoming' : 'closed'));
        $statusLabel = ['present' => 'Sudah Hadir', 'open' => 'Presensi Dibuka', 'upcoming' => 'Akan Datang', 'closed' => 'Presensi Ditutup'][$status];
    @endphp
    <button type="button" class="bpp-agenda-card" data-bs-toggle="modal" data-bs-target="#bpp-sheet-{{ $event->id }}" aria-haspopup="dialog" aria-controls="bpp-sheet-{{ $event->id }}">
        <span class="bpp-agenda-date">
            <strong>{{ $event->start_at->format('d') }}</strong>
            <small>{{ $event->start_at->locale('id')->translatedFormat('M') }}</small>
        </span>
        <span class="bpp-agenda-info">
            <span class="bpp-agenda-name">{{ $event->name }}</span>
            <span class="bpp-agenda-meta">{{ $event->start_at->format('H:i') }} – {{ $event->end_at->format('H:i') }} WIB</span>
            @if(!$event->start_at->isSameDay($event->end_at))
                <span class="bpp-agenda-meta">Sampai {{ $event->end_at->locale('id')->translatedFormat('d M Y') }}</span>
            @endif
            <span class="bpp-agenda-meta">{{ $event->location_name }}</span>
            <span class="bpp-status bpp-status--{{ $status }}">{{ $statusLabel }}</span>
        </span>
        <i class="bx bx-chevron-right bpp-agenda-chevron" aria-hidden="true"></i>
    </button>

    <div class="modal bpp-sheet" id="bpp-sheet-{{ $event->id }}" tabindex="-1" aria-labelledby="bpp-sheet-title-{{ $event->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="bpp-sheet-bar" aria-hidden="true"></div>
                <div class="bpp-sheet-top">
                    <span class="bpp-status bpp-status--{{ $status }}">{{ $statusLabel }}</span>
                    <button type="button" class="bpp-sheet-close" data-bs-dismiss="modal" aria-label="Tutup detail kegiatan"><i class="bx bx-x" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <h2 class="bpp-sheet-title" id="bpp-sheet-title-{{ $event->id }}">{{ $event->name }}</h2>
                    <dl class="bpp-sheet-details">
                        <dt>Tanggal</dt>
                        <dd>{{ $event->start_at->locale('id')->translatedFormat('l, d F Y') }}@if(!$event->start_at->isSameDay($event->end_at)) – {{ $event->end_at->locale('id')->translatedFormat('l, d F Y') }}@endif</dd>
                        <dt>Waktu</dt>
                        <dd>{{ $event->start_at->format('H:i') }} – {{ $event->end_at->format('H:i') }} WIB</dd>
                        <dt>Lokasi</dt>
                        <dd>{{ $event->location_name }}</dd>
                        <dt>Penyelenggara</dt>
                        <dd>{{ $event->organizer }}</dd>
                        @if($event->description)
                            <dt>Tentang kegiatan</dt>
                            <dd class="bpp-sheet-description">{{ $event->description }}</dd>
                        @endif
                    </dl>
                </div>
                <div class="bpp-sheet-footer">
                    @if($open && !$present)
                        <a class="bpp-sheet-scan" href="{{ route('mobile.bpppmnu.events.show', $event) }}#scanner">Scan QR Presensi</a>
                    @elseif($present)
                        <p class="bpp-sheet-confirmation"><span aria-hidden="true">✓</span> Presensi berhasil tercatat</p>
                    @endif
                    <a class="bpp-sheet-link" href="{{ route('mobile.bpppmnu.events.show', $event) }}">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="bpp-home-empty">
        <i class="bx bx-calendar-check" aria-hidden="true"></i>
        <h2>Belum ada agenda</h2>
        <p>Kegiatan yang mengundang Anda akan tampil di sini.</p>
    </div>
@endforelse
<div class="bpp-home-pagination">
    {{ $events->links() }}
</div>
@endsection
