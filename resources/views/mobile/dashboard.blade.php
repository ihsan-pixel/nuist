@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
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

        .trip-type-buttons {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 4px;
        }

        .trip-type-buttons .btn {
            border-radius: 20px;
            font-size: 12px;
            padding: 6px 12px;
        }

        .stats-form {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-item {
            text-align: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .stat-item i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .info-section {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .info-item {
            padding: 8px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .info-item small {
            color: #6c757d;
            font-size: 11px;
        }

        .info-item strong {
            font-size: 12px;
            color: #333;
        }

        .schedule-section {
            margin-bottom: 16px;
        }

        .schedule-card {
            background: #fff;
            border-radius: 16px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .schedule-icon {
            width: 40px;
            height: 40px;
            background: rgba(14, 133, 73, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .schedule-icon i {
            color: #0e8549;
            font-size: 18px;
        }

        .quick-actions {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 80px;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .quick-action-btn:hover {
            background: #e8f5f5;
            transform: translateY(-2px);
        }

        .quick-action-btn i {
            font-size: 20px;
            color: #0e8549;
            margin-bottom: 4px;
        }

        .quick-action-btn small {
            font-size: 11px;
            color: #333;
            text-align: center;
        }

        .section-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 12px;
            color: #333;
        }

        .no-schedule {
            text-align: center;
            padding: 20px;
            color: #999;
        }

        .no-schedule i {
            font-size: 32px;
            margin-bottom: 8px;
        }
    </style>

    <!-- Header -->
    <div class="dashboard-header text-white text-start mb-4 rounded-4 p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-1">Halo, {{ Auth::user()->name }} 👋</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="48" height="48" alt="User">
        </div>

        <!-- Quick Action Buttons -->
        <div class="d-flex justify-content-between bg-white rounded-pill p-1">
            <a href="{{ route('mobile.presensi') }}" class="btn btn-sm btn-success rounded-pill w-100 me-1 text-white">
                <i class="bx bx-check-square me-1"></i>Presensi
            </a>
            <a href="{{ route('mobile.jadwal') }}" class="btn btn-sm btn-light rounded-pill w-100 me-1">
                <i class="bx bx-calendar me-1"></i>Jadwal
            </a>
            <a href="{{ route('mobile.profile') }}" class="btn btn-sm btn-light rounded-pill w-100">
                <i class="bx bx-user me-1"></i>Profil
            </a>
        </div>
    </div>

    <!-- Stats Form -->
    <div class="stats-form">
        <div class="stats-grid">
            <div class="stat-item">
                <i class="bx bx-check-circle text-success"></i>
                <h6 class="mb-0">{{ $kehadiranPercent }}%</h6>
                <small class="text-muted">Kehadiran</small>
            </div>
            <div class="stat-item">
                <i class="bx bx-calendar text-primary"></i>
                <h6 class="mb-0">{{ $totalBasis }}</h6>
                <small class="text-muted">Presensi</small>
            </div>
            <div class="stat-item">
                <i class="bx bx-time text-warning"></i>
                <h6 class="mb-0">{{ $izin }}</h6>
                <small class="text-muted">Izin</small>
            </div>
            <div class="stat-item">
                <i class="bx bx-plus-medical text-danger"></i>
                <h6 class="mb-0">{{ $sakit }}</h6>
                <small class="text-muted">Sakit</small>
            </div>
        </div>
    </div>

    <!-- Teacher Info -->
    <div class="info-section">
        <h6 class="section-title">Informasi Tenaga Pendidik</h6>
        <div class="info-grid">
            <div class="info-item">
                <small class="d-block">NUIST ID</small>
                <strong>{{ $userInfo['nuist_id'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Status Kepegawaian</small>
                <strong>{{ $userInfo['status_kepegawaian'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Ketugasan</small>
                <strong>{{ $userInfo['ketugasan'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Tempat Lahir</small>
                <strong>{{ $userInfo['tempat_lahir'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Tanggal Lahir</small>
                <strong>{{ $userInfo['tanggal_lahir'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">TMT</small>
                <strong>{{ $userInfo['tmt'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">NUPTK</small>
                <strong>{{ $userInfo['nuptk'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">NPK</small>
                <strong>{{ $userInfo['npk'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Kartanu</small>
                <strong>{{ $userInfo['kartanu'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">NIP Ma'arif</small>
                <strong>{{ $userInfo['nip'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Pendidikan Terakhir</small>
                <strong>{{ $userInfo['pendidikan_terakhir'] }}</strong>
            </div>
            <div class="info-item">
                <small class="d-block">Program Studi</small>
                <strong>{{ $userInfo['program_studi'] }}</strong>
            </div>
        </div>
    </div>

    <!-- Schedule Section -->
    <div class="schedule-section">
        <h6 class="section-title">Jadwal Hari Ini</h6>
        @if($todaySchedules->count() > 0)
            @foreach($todaySchedules as $schedule)
                <div class="schedule-card">
                    <div class="schedule-icon">
                        <i class="bx bx-book"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $schedule->subject }}</h6>
                        <p class="mb-0 text-muted small">{{ $schedule->class_name }}</p>
                        <small class="text-muted"><i class="bx bx-time-five"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-schedule">
                <i class="bx bx-calendar-x"></i>
                <p>Tidak ada jadwal mengajar hari ini</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h6 class="section-title">Aksi Cepat</h6>
        <div class="quick-actions-grid">
            <a href="{{ route('mobile.laporan') }}" class="quick-action-btn">
                <i class="bx bx-file"></i>
                <small>Laporan</small>
            </a>
            <a href="{{ route('mobile.izin') }}" class="quick-action-btn">
                <i class="bx bx-time"></i>
                <small>Izin</small>
            </a>
            <a href="{{ route('mobile.pengaturan') }}" class="quick-action-btn">
                <i class="bx bx-cog"></i>
                <small>Pengaturan</small>
            </a>
        </div>
    </div>
</div>
@endsection
