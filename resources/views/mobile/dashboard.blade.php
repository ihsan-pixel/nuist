@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .dashboard-header img {
            border: 2px solid #fff;
        }

        .dashboard-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .dashboard-header h5 {
            font-size: 14px;
        }

        .stats-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .stat-item {
            text-align: center;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .stat-item .icon-container {
            margin-bottom: 4px;
        }

        .stat-item i {
            font-size: 18px;
        }

        .stat-item h6 {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .stat-item small {
            font-size: 9px;
            color: #6c757d;
        }

        .info-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .info-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-item small {
            color: #6c757d;
            font-size: 10px;
        }

        .info-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-section {
            margin-bottom: 12px;
        }

        .schedule-card {
            background: #fff;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
        }

        .schedule-icon {
            width: 32px;
            height: 32px;
            background: rgba(14, 133, 73, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .schedule-icon i {
            color: #0e8549;
            font-size: 14px;
        }

        .schedule-card h6 {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .schedule-card p {
            font-size: 12px;
            margin-bottom: 2px;
        }

        .schedule-card small {
            font-size: 11px;
        }

        .quick-actions {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .quick-actions-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .quick-actions-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .quick-actions-content {
            padding: 12px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border-radius: 8px;
            padding: 12px 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }

        .action-button:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
            transform: translateY(-1px);
        }

        .action-button i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .section-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }

        .no-schedule {
            text-align: center;
            padding: 16px;
            color: #999;
        }

        .no-schedule i {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }
    </style>

    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Halo, {{ Auth::user()->name }} 👋</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- Stats Form -->
    <div class="stats-form">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-check-circle text-success"></i>
                </div>
                <h6>{{ $kehadiranPercent }}%</h6>
                <small>Kehadiran</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-calendar text-primary"></i>
                </div>
                <h6>{{ $totalBasis }}</h6>
                <small>Presensi</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-time text-warning"></i>
                </div>
                <h6>{{ $izin }}</h6>
                <small>Izin</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-plus-medical text-danger"></i>
                </div>
                <h6>{{ $sakit }}</h6>
                <small>Sakit</small>
            </div>
        </div>
    </div>

    <!-- Teacher Info -->
    <div class="info-section">
        <div class="info-header">
            <h6><i class="bx bx-user me-2"></i>Informasi Tenaga Pendidik</h6>
        </div>
        <div class="info-content">
            <div class="info-item">
                <span class="info-label">NUIST ID</span>
                <span class="info-value">{{ $userInfo['nuist_id'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status Kepegawaian</span>
                <span class="info-value">{{ $userInfo['status_kepegawaian'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Ketugasan</span>
                <span class="info-value">{{ $userInfo['ketugasan'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tempat Lahir</span>
                <span class="info-value">{{ $userInfo['tempat_lahir'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Lahir</span>
                <span class="info-value">{{ $userInfo['tanggal_lahir'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">TMT</span>
                <span class="info-value">{{ $userInfo['tmt'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NUPTK</span>
                <span class="info-value">{{ $userInfo['nuptk'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NPK</span>
                <span class="info-value">{{ $userInfo['npk'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kartanu</span>
                <span class="info-value">{{ $userInfo['kartanu'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NIP Ma'arif</span>
                <span class="info-value">{{ $userInfo['nip'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Pendidikan Terakhir</span>
                <span class="info-value">{{ $userInfo['pendidikan_terakhir'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Program Studi</span>
                <span class="info-value">{{ $userInfo['program_studi'] }}</span>
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
        <div class="quick-actions-header">
            <h6><i class="bx bx-flash me-2"></i>Aksi Cepat</h6>
        </div>
        <div class="quick-actions-content">
            <div class="action-grid">
            <a href="{{ route('mobile.presensi') }}" class="action-button">
                <i class="bx bx-check-square"></i>
                <span>Presensi</span>
            </a>
            <a href="{{ route('mobile.jadwal') }}" class="action-button">
                <i class="bx bx-calendar"></i>
                <span>Jadwal</span>
            </a>
            <a href="{{ route('mobile.teaching-attendances') }}" class="action-button">
                <i class="bx bx-chalkboard"></i>
                <span>Mengajar</span>
            </a>
            <a href="{{ route('mobile.ubah-akun') }}" class="action-button">
                <i class="bx bx-cog"></i>
                <span>Pengaturan</span>
            </a>
        </div>
    </div>
</div>
@endsection
