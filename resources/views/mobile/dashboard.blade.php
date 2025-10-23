@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<!-- Welcome Section -->
<div class="card mb-2 shadow-sm">
    <div class="card-body text-center py-2">
        <div class="avatar-md mx-auto mb-2">
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 alt="" class="img-thumbnail rounded-circle">
        </div>
        <h6 class="mb-1">Selamat datang, {{ Auth::user()->name }}!</h6>
        <p class="text-muted small mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-2">
                <div class="avatar-xs mx-auto mb-1">
                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bx bx-check-circle fs-3"></i>
                    </div>
                </div>
                <h5 class="mb-0">{{ $kehadiranPercent }}%</h5>
                <small class="text-muted">Kehadiran</small>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-2">
                <div class="avatar-xs mx-auto mb-1">
                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bx bx-calendar fs-3"></i>
                    </div>
                </div>
                <h5 class="mb-0">{{ $totalBasis }}</h5>
                <small class="text-muted">Total Presensi</small>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-2">
                <div class="avatar-xs mx-auto mb-1">
                    <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                        <i class="bx bx-time fs-3"></i>
                    </div>
                </div>
                <h5 class="mb-0">{{ $izin }}</h5>
                <small class="text-muted">Total Izin</small>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-2">
                <div class="avatar-xs mx-auto mb-1">
                    <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle">
                        <i class="bx bx-plus-medical fs-3"></i>
                    </div>
                </div>
                <h5 class="mb-0">{{ $sakit }}</h5>
                <small class="text-muted">Total Sakit</small>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Stats -->
<div class="card mb-3 shadow-sm">
    <div class="card-header bg-light py-2">
        <h6 class="mb-0"><i class="bx bx-bar-chart me-2"></i>Detail Kehadiran</h6>
    </div>
    <div class="card-body py-2">
        <div class="row g-2">
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-2">
                        <div class="avatar-title bg-success rounded-circle">
                            <i class="bx bx-check text-white fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $hadir }}</h6>
                        <small class="text-muted">Hadir</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-2">
                        <div class="avatar-title bg-warning rounded-circle">
                            <i class="bx bx-time text-white fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $izin }}</h6>
                        <small class="text-muted">Izin</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-2">
                        <div class="avatar-title bg-danger rounded-circle">
                            <i class="bx bx-plus-medical text-white fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $sakit }}</h6>
                        <small class="text-muted">Sakit</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-2">
                        <div class="avatar-title bg-secondary rounded-circle">
                            <i class="bx bx-x text-white fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $alpha }}</h6>
                        <small class="text-muted">Alpha</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule -->
@if($todaySchedules->count() > 0)
<div class="card mb-3 shadow-sm">
    <div class="card-header bg-light py-2">
        <h6 class="mb-0"><i class="bx bx-calendar-today me-2"></i>Jadwal Hari Ini</h6>
    </div>
    <div class="card-body py-2">
        @foreach($todaySchedules as $schedule)
        <div class="d-flex align-items-center mb-2 pb-2 border-bottom border-light">
            <div class="avatar-sm me-2">
                <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                    <i class="bx bx-book fs-2"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0">{{ $schedule->subject }}</h6>
                <p class="mb-0 text-muted small">{{ $schedule->class_name }}</p>
                <small class="text-muted">{{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="card mb-3 shadow-sm">
    <div class="card-body text-center py-3">
        <div class="avatar-md mx-auto mb-2">
            <div class="avatar-title bg-light text-muted rounded-circle">
                <i class="bx bx-calendar-x fs-2"></i>
            </div>
        </div>
        <h6 class="text-muted">Tidak ada jadwal mengajar hari ini</h6>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="card shadow-sm">
    <div class="card-header bg-light py-2">
        <h6 class="mb-0"><i class="bx bx-flash me-2"></i>Aksi Cepat</h6>
    </div>
    <div class="card-body py-2">
        <div class="row g-2">
            <div class="col-4">
                <a href="{{ route('mobile.presensi') }}" class="btn btn-primary w-100 py-2">
                    <i class="bx bx-check-square d-block fs-4 mb-1"></i>
                    <small>Presensi</small>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('mobile.jadwal') }}" class="btn btn-info w-100 py-2">
                    <i class="bx bx-calendar d-block fs-4 mb-1"></i>
                    <small>Jadwal</small>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('mobile.profile') }}" class="btn btn-success w-100 py-2">
                    <i class="bx bx-user d-block fs-4 mb-1"></i>
                    <small>Profile</small>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('mobile.laporan') }}" class="btn btn-warning w-100 py-2">
                    <i class="bx bx-file d-block fs-4 mb-1"></i>
                    <small>Laporan</small>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('mobile.izin') }}" class="btn btn-secondary w-100 py-2">
                    <i class="bx bx-time d-block fs-4 mb-1"></i>
                    <small>Izin</small>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('mobile.pengaturan') }}" class="btn btn-dark w-100 py-2">
                    <i class="bx bx-cog d-block fs-4 mb-1"></i>
                    <small>Pengaturan</small>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
