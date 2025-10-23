@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<!-- Welcome Section -->
<div class="card mb-4 shadow-sm">
    <div class="card-body text-center py-4">
        <div class="avatar-lg mx-auto mb-3">
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 alt="" class="img-thumbnail rounded-circle">
        </div>
        <h5 class="mb-1">Selamat datang, {{ Auth::user()->name }}!</h5>
        <p class="text-muted mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2">
                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bx bx-check-circle fs-4"></i>
                    </div>
                </div>
                <h4 class="mb-1">{{ $attendanceData['kehadiran'] }}%</h4>
                <small class="text-muted">Kehadiran</small>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="avatar-sm mx-auto mb-2">
                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bx bx-calendar fs-4"></i>
                    </div>
                </div>
                <h4 class="mb-1">{{ $attendanceData['total_presensi'] }}</h4>
                <small class="text-muted">Total Presensi</small>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Stats -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bx bx-bar-chart me-2"></i>Detail Kehadiran</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <div class="avatar-title bg-success rounded-circle">
                            <i class="bx bx-check text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $attendanceData['hadir'] }}</h6>
                        <small class="text-muted">Hadir</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <div class="avatar-title bg-warning rounded-circle">
                            <i class="bx bx-time text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $attendanceData['izin'] }}</h6>
                        <small class="text-muted">Izin</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <div class="avatar-title bg-danger rounded-circle">
                            <i class="bx bx-plus-medical text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $attendanceData['sakit'] }}</h6>
                        <small class="text-muted">Sakit</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <div class="avatar-title bg-secondary rounded-circle">
                            <i class="bx bx-x text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $attendanceData['alpha'] }}</h6>
                        <small class="text-muted">Alpha</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule -->
@if($todaySchedule->count() > 0)
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bx bx-calendar-today me-2"></i>Jadwal Hari Ini</h6>
    </div>
    <div class="card-body">
        @foreach($todaySchedule as $schedule)
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
        </div>
        @endforeach
    </div>
</div>
@else
<div class="card mb-4 shadow-sm">
    <div class="card-body text-center py-4">
        <div class="avatar-lg mx-auto mb-3">
            <div class="avatar-title bg-light text-muted rounded-circle">
                <i class="bx bx-calendar-x fs-1"></i>
            </div>
        </div>
        <h6 class="text-muted">Tidak ada jadwal mengajar hari ini</h6>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bx bx-flash me-2"></i>Aksi Cepat</h6>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ route('mobile.presensi') }}" class="btn btn-primary w-100 py-3">
                    <i class="bx bx-check-square d-block fs-4 mb-1"></i>
                    <small>Presensi</small>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('mobile.jadwal') }}" class="btn btn-info w-100 py-3">
                    <i class="bx bx-calendar d-block fs-4 mb-1"></i>
                    <small>Jadwal</small>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
