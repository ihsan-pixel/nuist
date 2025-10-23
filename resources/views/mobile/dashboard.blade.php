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
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: #fff;
        border-radius: 20px;
        padding: 20px 15px;
        box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
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
        background: #e8f5f5;
        transform: translateY(-2px);
    }

    .quick-action-btn i {
        font-size: 1.7rem;
        color: #004b4c;
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
<div class="dashboard-header mb-4">
    <div class="d-flex align-items-center">
        <div class="avatar-sm me-3">
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 alt="" class="img-thumbnail rounded-circle" width="60" height="60">
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-0">Halo, {{ Auth::user()->name }} 👋</h6>
            <p class="small mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</p>
        </div>
    </div>
</div>

<!-- Statistik Kehadiran -->
<div class="dashboard-stats mb-0">
    <div class="row g-2">
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="card-body px-1">
                    <i class="bx bx-check-circle text-success fs-4 mb-1"></i>
                    <h6 class="mb-0 fs-6">{{ $kehadiranPercent }}%</h6>
                    <small class="text-muted">Kehadiran</small>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="card-body px-1">
                    <i class="bx bx-calendar text-primary fs-4 mb-1"></i>
                    <h6 class="mb-0 fs-6">{{ $totalBasis }}</h6>
                    <small class="text-muted">Presensi</small>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="card-body px-1">
                    <i class="bx bx-time text-warning fs-4 mb-1"></i>
                    <h6 class="mb-0 fs-6">{{ $izin }}</h6>
                    <small class="text-muted">Izin</small>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="card-body px-1">
                    <i class="bx bx-plus-medical text-danger fs-4 mb-1"></i>
                    <h6 class="mb-0 fs-6">{{ $sakit }}</h6>
                    <small class="text-muted">Sakit</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Tenaga Pendidik -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="section-title"><i class="bx bx-user me-1 text-danger"></i> Informasi Tenaga Pendidik</span>
    </div>
    <div class="row g-2">
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">NUIST ID</small>
                <strong class="text-truncate d-block">{{ $userInfo['nuist_id'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">Status Kepegawaian</small>
                <strong class="text-truncate d-block">{{ $userInfo['status_kepegawaian'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">Ketugasan</small>
                <strong class="text-truncate d-block">{{ $userInfo['ketugasan'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">Tempat Lahir</small>
                <strong class="text-truncate d-block">{{ $userInfo['tempat_lahir'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">Tanggal Lahir</small>
                <strong class="text-truncate d-block">{{ $userInfo['tanggal_lahir'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">TMT</small>
                <strong class="text-truncate d-block">{{ $userInfo['tmt'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">NUPTK</small>
                <strong class="text-truncate d-block">{{ $userInfo['nuptk'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">NPK</small>
                <strong class="text-truncate d-block">{{ $userInfo['npk'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">Kartanu</small>
                <strong class="text-truncate d-block">{{ $userInfo['kartanu'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">NIP Ma'arif</small>
                <strong class="text-truncate d-block">{{ $userInfo['nip'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">Pendidikan Terakhir</small>
                <strong class="text-truncate d-block">{{ $userInfo['pendidikan_terakhir'] }}</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="p-2 bg-light rounded">
                <small class="text-muted d-block">Program Studi</small>
                <strong class="text-truncate d-block">{{ $userInfo['program_studi'] }}</strong>
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
