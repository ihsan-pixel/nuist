@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<div class="container py-3 position-relative" style="max-width: 420px; margin: auto;">
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
            position: relative;
            z-index: 2;
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

        /* === BACKGROUND DASHBOARD MELENGKUNG (VERSI HALUS) === */
        .dashboard-bg-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 220px;
            overflow: hidden;
            z-index: 0;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .dashboard-bg-svg svg {
            width: 100%;
            height: 100%;
            display: block;
            transform: translateY(-10px);
        }

        /* Cards & section styles */
        .stats-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
            position: relative;
            z-index: 2;
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

        .info-section,
        .schedule-section,
        .quick-actions {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
            position: relative;
            z-index: 2;
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

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .schedule-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .schedule-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-item small {
            color: #6c757d;
            font-size: 10px;
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

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>

    <!-- Background SVG Halus -->
    <div class="dashboard-bg-svg">
        <svg viewBox="0 0 375 200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <defs>
                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#004b4c" />
                    <stop offset="100%" stop-color="#0e8549" />
                </linearGradient>
            </defs>
            <path d="M0,0 L375,0 L375,130 Q187.5,200 0,130 Z" fill="url(#grad)" />
        </svg>
    </div>

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
                    <i class="bx bx-x text-danger"></i>
                </div>
                <h6>{{ $alpha }}</h6>
                <small>
                    <a href="{{ route('mobile.riwayat-presensi-alpha') }}" class="text-decoration-none text-danger" style="font-size: 9px;">Tidak Hadir</a>
                </small>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah')
    <div class="info-section">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <a href="{{ route('mobile.kelola-izin') }}" class="action-button">
                <i class="bx bx-edit"></i>
                <span>Kelola Izin</span>
            </a>
            <a href="{{ route('mobile.monitor-presensi') }}" class="action-button">
                <i class="bx bx-calendar-check"></i>
                <span>Data Presensi</span>
            </a>
        </div>
    </div>
    @endif

    <!-- Teacher Info -->
    <div class="info-section">
        <h6 class="section-title">Informasi Tenaga Pendidik</h6>
        <div class="info-grid">
            @foreach($userInfo as $key => $value)
                <div class="info-item">
                    <small class="d-block">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                    <strong>{{ $value ?: '-' }}</strong>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Schedule Section -->
    <div class="schedule-section">
        <h6 class="section-title">Jadwal Hari Ini</h6>
        @if($todaySchedules->count() > 0)
            <div class="schedule-grid">
                @foreach($todaySchedules as $schedule)
                    <div class="schedule-item">
                        <strong class="d-block">{{ $schedule->subject }}</strong>
                        <small class="d-block text-muted">{{ $schedule->class_name }}</small>
                        <small class="d-block text-muted"><i class="bx bx-time-five"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
                    </div>
                @endforeach
            </div>
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
                <a href="{{ route('mobile.laporan') }}" class="action-button">
                    <i class="bx bx-file"></i>
                    <span>Laporan</span>
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
</div>
@endsection
