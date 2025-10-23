@extends('layouts.mobile')

@section('title', 'Jadwal Mengajar')
@section('subtitle', 'Jadwal Mengajar Saya')

@section('content')
@if(session('success'))
<div class="alert alert-success border-0 rounded-3 mb-4">
    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
</div>
@endif

@php
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
@endphp

@foreach($days as $day)
<div class="card mb-3 shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0 d-flex align-items-center">
            <i class="bx bx-calendar me-2"></i>{{ $day }}
        </h6>
    </div>
    <div class="card-body">
        @if(isset($grouped[$day]) && $grouped[$day]->count() > 0)
        @foreach($grouped[$day] as $schedule)
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
            <div class="avatar-sm me-3">
                <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                    <i class="bx bx-book"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1">{{ $schedule->subject }}</h6>
                <p class="mb-1 text-muted small">{{ $schedule->class_name }}</p>
                <small class="text-muted">{{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
            </div>
            <div class="text-end">
                <small class="badge bg-primary">{{ $schedule->school->name ?? 'N/A' }}</small>
            </div>
        </div>
        @endforeach
        @else
        <div class="text-center py-3">
            <div class="avatar-lg mx-auto mb-3">
                <div class="avatar-title bg-light text-muted rounded-circle">
                    <i class="bx bx-calendar-x fs-1"></i>
                </div>
            </div>
            <p class="text-muted mb-0">Tidak ada jadwal mengajar</p>
        </div>
        @endif
    </div>
</div>
@endforeach

@endsection
