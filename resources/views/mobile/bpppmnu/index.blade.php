@extends('mobile.bpppmnu.layout')
@section('title', 'Presensi')
@section('bpp-content')
<p class="bpp-meta">Agenda kegiatan yang mengundang Anda. Seluruh waktu dalam WIB.</p>
@forelse($events->groupBy(fn($event) => $event->isOpen() ? 'Presensi sedang dibuka' : 'Agenda mendatang / berlangsung') as $heading => $group)
<h2 class="mt-3 mb-3">{{ $heading }}</h2>
@foreach($group as $event)
@php($present = $event->attendances->isNotEmpty())
<article class="card"><div class="card-body">
<span class="badge bg-{{ $present ? 'success' : ($event->isOpen() ? 'primary' : 'secondary') }} mb-2">{{ $present ? 'Sudah Hadir' : ($event->isOpen() ? 'Presensi Dibuka' : (now()->lt($event->attendance_open_at) ? 'Akan Datang' : 'Presensi Ditutup')) }}</span>
<h3>{{ $event->name }}</h3><p class="bpp-meta">{{ Str::limit($event->description, 140) }}</p>
<p class="bpp-meta mb-1"><i class="bx bx-calendar-event"></i> {{ $event->start_at->format('d-m-Y H:i') }}–{{ $event->end_at->format('d-m-Y H:i') }} WIB</p>
<p class="bpp-meta mb-1"><i class="bx bx-map"></i> {{ $event->location_name }}</p>
<p class="bpp-meta mb-1">Penyelenggara: {{ $event->organizer }}</p><p class="bpp-meta">Penanggung jawab: {{ $event->person_in_charge }}</p>
<div class="d-flex gap-2 flex-wrap"><a class="btn btn-outline-secondary btn-sm" href="{{ route('mobile.bpppmnu.events.show', $event) }}">Detail Kegiatan</a>
@if($event->isOpen() && !$present)<a class="btn btn-primary btn-sm" href="{{ route('mobile.bpppmnu.events.show', $event) }}#scanner">Scan QR</a>@endif</div>
</div></article>
@endforeach
@empty
<div class="card"><div class="card-body text-center py-4"><i class="bx bx-calendar-check fs-2"></i><p class="mt-2 mb-0">Belum ada agenda kegiatan untuk Anda.</p></div></div>
@endforelse
{{ $events->links() }}
@endsection
