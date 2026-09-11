@extends('mobile.bpppmnu.layout')
@section('title', 'Presensi')
@section('bpp-header')
<header class="bpp-header">
    <div class="bpp-banner">
        <small>NUIST · Pengurus BPPPMNU</small>
        <h1 style="font-weight:600;overflow-wrap:anywhere">{{ auth()->user()->name }}</h1>
    </div>
</header>
@endsection
@section('bpp-content')
<div class="bpp-intro"><div><h2>Agenda Anda</h2></div><span class="bpp-timezone">WIB</span></div>
@forelse($events->groupBy(fn($event) => $event->isOpen() ? 'Presensi sedang dibuka' : 'Agenda mendatang / berlangsung') as $heading => $group)
<div class="bpp-section-heading"><h2>{{ $heading }}</h2><span>{{ $group->count() }}</span></div>
@foreach($group as $event)
@php($present = $event->attendances->isNotEmpty())
<article class="card bpp-event"><div class="card-body">
<div class="bpp-event-top"><div class="bpp-date"><strong>{{ $event->start_at->format('d') }}</strong><span>{{ $event->start_at->locale('id')->translatedFormat('M Y') }}</span></div><div class="bpp-event-title">
<span class="badge bg-{{ $present ? 'success' : ($event->isOpen() ? 'primary' : 'secondary') }} mb-2">{{ $present ? 'Sudah Hadir' : ($event->isOpen() ? 'Presensi Dibuka' : (now()->lt($event->attendance_open_at) ? 'Akan Datang' : 'Presensi Ditutup')) }}</span>
<h3>{{ $event->name }}</h3></div></div>
@if($event->description)<p class="bpp-meta bpp-description">{{ Str::limit($event->description, 140) }}</p>@endif
<div class="bpp-event-info">
<p><i class="bx bx-time-five" aria-hidden="true"></i><span>{{ $event->start_at->format('H:i') }} – {{ $event->end_at->format('H:i') }} WIB @if(!$event->start_at->isSameDay($event->end_at))<small>Sampai {{ $event->end_at->locale('id')->translatedFormat('d M Y') }}</small>@endif</span></p>
<p><i class="bx bx-map" aria-hidden="true"></i><span>{{ $event->location_name }}</span></p>
<p><i class="bx bx-buildings" aria-hidden="true"></i><span>{{ $event->organizer }}</span></p>
</div>
<div class="bpp-event-actions"><a class="btn btn-outline-secondary" href="{{ route('mobile.bpppmnu.events.show', $event) }}">Detail Kegiatan</a>
@if($event->isOpen() && !$present)<a class="btn btn-primary" href="{{ route('mobile.bpppmnu.events.show', $event) }}#scanner"><i class="bx bx-qr-scan" aria-hidden="true"></i> Scan QR</a>@endif</div>
</div></article>
@endforeach
@empty
<div class="card"><div class="card-body bpp-empty"><div class="bpp-empty-icon"><i class="bx bx-calendar-check" aria-hidden="true"></i></div><h2>Belum ada agenda</h2><p class="bpp-meta mb-0">Kegiatan yang mengundang Anda akan tampil di sini.</p></div></div>
@endforelse
{{ $events->links() }}
@endsection
