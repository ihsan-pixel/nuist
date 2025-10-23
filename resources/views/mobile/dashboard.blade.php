@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<style>
    /* Gaya MyTelkomsel-like */
    body {
        background: #f8f9fb;
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #e60012, #ff4b4b);
        color: #fff;
        border-radius: 20px;
        padding: 20px 15px;
        box-shadow: 0 4px 10px rgba(230, 0, 18, 0.3);
    }

    .dashboard-header img {
        border: 2px solid #fff;
    }

    .dashboard-header h6 {
        font-weight: 600;
    }

    .dashboard-stats .card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .dashboard-stats .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 16px;
        border: none;
        padding: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .quick-action-btn:hover {
        background: #ffecec;
        transform: translateY(-2px);
    }

    .quick-action-btn i {
        font-size: 1.7rem;
        color: #e60012;
    }

    .quick-action-btn small {
        margin-top: 4px;
        font-size: 12px;
        color: #333;
    }

    .section-title {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 6px;
    }

    .no-schedule {
        color: #999;
    }
</style>

<!-- Header -->
<div class="dashboard-header text-center mb-4">
    <div class="avatar-md mx-auto mb-2">
        <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
             alt="" class="img-thumbnail rounded-circle" width="80" height="80">
    </div>
    <h6>Halo, {{ Auth::user()->name }} 👋</h6>
    <p class="small mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</p>
</div>

<!-- Statistik Kehadiran -->
<div class="dashboard-stats mb-4">
    <div class="row g-3">
        <div class="col-6">
            <div class="card text-center py-3">
                <div class="card-body">
                    <i class="bx bx-check-circle text-success fs-3 mb-1"></i>
                    <h5 class="mb-0">{{ $kehadiranPercent }}%</h5>
                    <small class="text-muted">Kehadiran</small>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center py-3">
                <div class="card-body">
                    <i class="bx bx-calendar text-primary fs-3 mb-1"></i>
                    <h5 class="mb-0">{{ $totalBasis }}</h5>
                    <small class="text-muted">Total Presensi</small>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center py-3">
                <div class="card-body">
                    <i class="bx bx-time text-warning fs-3 mb-1"></i>
                    <h5 class="mb-0">{{ $izin }}</h5>
                    <small class="text-muted">Total Izin</small>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center py-3">
                <div class="card-body">
                    <i class="bx bx-plus-medical text-danger fs-3 mb-1"></i>
                    <h5 class="mb-0">{{ $sakit }}</h5>
                    <small class="text-muted">Total Sakit</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jadwal Hari Ini -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="section-title"><i class="bx bx-calendar-today me-1 text-danger"></i> Jadwal Hari Ini</span>
    </div>

    @if($todaySchedules->count() > 0)
        @foreach($todaySchedules as $schedule)
            <div class="card mb-2 border-0 shadow-sm">
                <div class="card-body py-2 d-flex align-items-center">
                    <div class="avatar-sm me-3">
                        <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle">
                            <i class="bx bx-book fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $schedule->subject }}</h6>
                        <p class="mb-0 text-muted small">{{ $schedule->class_name }}</p>
                        <small class="text-muted"><i class="bx bx-time-five"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-4">
            <i class="bx bx-calendar-x fs-2 text-muted mb-2"></i>
            <p class="no-schedule">Tidak ada jadwal mengajar hari ini</p>
        </div>
    @endif
</div>

<!-- Aksi Cepat -->
<div class="mb-3">
    <span class="section-title"><i class="bx bx-flash me-1 text-danger"></i> Aksi Cepat</span>
    <div class="row g-3 mt-1 text-center">
        <div class="col-4">
            <a href="{{ route('mobile.presensi') }}" class="quick-action-btn">
                <i class="bx bx-check-square"></i>
                <small>Presensi</small>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('mobile.jadwal') }}" class="quick-action-btn">
                <i class="bx bx-calendar"></i>
                <small>Jadwal</small>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('mobile.profile') }}" class="quick-action-btn">
                <i class="bx bx-user"></i>
                <small>Profil</small>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('mobile.laporan') }}" class="quick-action-btn">
                <i class="bx bx-file"></i>
                <small>Laporan</small>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('mobile.izin') }}" class="quick-action-btn">
                <i class="bx bx-time"></i>
                <small>Izin</small>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('mobile.pengaturan') }}" class="quick-action-btn">
                <i class="bx bx-cog"></i>
                <small>Pengaturan</small>
            </a>
        </div>
    </div>
</div>
@endsection
