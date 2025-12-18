@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<header class="mobile-header d-md-none">
    <div class="container-fluid px-1 py-3" style="background: transparent;">
        <div class="d-flex align-items-center justify-content-between">
            <!-- User Avatar (Left) -->
            <div class="avatar-sm">
                <img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                class="rounded-circle" width="40" height="40" alt="User" style="border-width: 2px !important;">
            </div>

            <!-- Welcome Text (Right-aligned) -->
            <div class="text-start flex-grow-1">
                <small class="text-light fw-medium" style="font-size: 11px;">Selamat Datang</small>
                <h6 class="mb-0 fw-semibold text-light" style="font-size: 14px;">{{ Auth::user()->name }}</h6>
            </div>

            <!-- Notification and Menu Buttons (Right) -->
            <div class="d-flex align-items-center">
                <!-- Notification Bell -->
                <a href="{{ route('mobile.notifications') }}" class="btn btn-link text-decoration-none p-0 me-2 position-relative">
                    <i class="bx bx-bell" style="font-size: 22px; color: #ffffff;"></i>
                    <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 9px; padding: 2px 5px; top: -4px; right: -4px; display: none;">0</span>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded" style="font-size: 22px; color: #ffffff;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item py-2" href="{{ route('mobile.notifications') }}"><i class="bx bx-bell me-2"></i>Notifikasi</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="bx bx-home me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out me-2"></i>Logout
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb url('{{ asset("images/bg.png") }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            /* box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3); */
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

        .dashboard-header .welcome-text {
            color: #fff !important;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        body {
            background-color: transparent !important;
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

        .services-form {
            /* background: #fff; */
            border-radius: 12px;
            padding: 12px;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.05); */
            margin-bottom: 12px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            text-align: center;
        }

        .service-item {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 10px;
            padding: 14px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
        }

        .service-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .service-item i {
            font-size: 20px;
            color: #fff;
        }

        .service-label {
            font-size: 10px;
            font-weight: 600;
            margin-top: 6px;
            color: #333;
        }

        .service-item i {
            font-size: 24px;
            color: #ffffff;
        }

        .service-item h6 {
            font-size: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #ffffff;
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
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.05); */
            margin-bottom: 12px;
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

        /* Banner Modal Styles */
        .modal-content {
            border-radius: 15px;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }

    </style>

    <!-- Show banner modal on page load -->
    @if($bannerImage)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeBannerModal'), {
                backdrop: 'static',
                keyboard: false
            });
            welcomeModal.show();

            // Auto hide after 3 seconds
            setTimeout(function() {
                welcomeModal.hide();
            }, 3000);
        });
    </script>
    @endif

    <!-- Header -->
    {{-- <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Halo, {{ Auth::user()->name }} 👋</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div> --}}

    <!-- Banner Modal -->
    @if($bannerImage)
    <div class="modal fade" id="welcomeBannerModal" tabindex="-1" aria-labelledby="welcomeBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="background: transparent;">
                <div class="modal-body p-0">
                    <div class="text-center">
                        <img src="{{ $bannerImage }}" alt="Welcome Banner" class="img-fluid rounded" style="max-height: 60vh; width: auto;">
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-transparent">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Form -->
    <div class="stats-form">
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="welcome-text mb-1">Asal Madrasah/Sekolah</small>
                    <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah belum diatur' }}</h5>
                </div>
            </div>
        </div>
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

    <small>Layanan</small>

    <!-- Services Form -->
    <div class="services-form">
        <div class="services-grid">
            <div>
                <a href="{{ route('mobile.presensi') }}" class="service-item">
                    <i class="bx bx-fingerprint"></i>
                </a>
                <div class="service-label">Presensi</div>
            </div>
            <div>
                <a href="{{ route('mobile.teaching-attendances') }}" class="service-item">
                    <i class="bx bx-chalkboard"></i>
                </a>
                <div class="service-label">Presensi Mengajar</div>
            </div>
            <div>
                <a href="{{ route('mobile.izin', ['type' => 'cuti']) }}" class="service-item">
                    <i class="bx bx-calendar-star"></i>
                </a>
                <div class="service-label">Izin Cuti</div>
            </div>
            <div>
                <a href="{{ route('mobile.izin', ['type' => 'terlambat']) }}" class="service-item">
                    <i class="bx bx-time-five"></i>
                </a>
                <div class="service-label">Izin Terlambat</div>
            </div>
            <div>
                <a href="{{ route('mobile.izin', ['type' => 'sakit']) }}" class="service-item">
                    <i class="bx bx-plus-medical"></i>
                </a>
                <div class="service-label">Izin Sakit</div>
            </div>
            <div>
                <a href="{{ route('mobile.izin', ['type' => 'tugas_luar']) }}" class="service-item">
                    <i class="bx bx-briefcase"></i>
                </a>
                <div class="service-label">Izin Dinas Luar</div>
            </div>
            <div>
                <a href="{{ route('mobile.jadwal') }}" class="service-item">
                    <i class="bx bx-calendar"></i>
                </a>
                <div class="service-label">Jadwal Mengajar</div>
            </div>
            <div>
                <a href="{{ route('mobile.laporan') }}" class="service-item">
                    <i class="bx bx-file"></i>
                </a>
                <div class="service-label">Laporan</div>
            </div>
            <div>
                <a href="{{ route('mobile.profile') }}" class="service-item">
                    <i class="bx bx-user"></i>
                </a>
                <div class="service-label">Profile</div>
            </div>
            <div>
                <a href="{{ route('mobile.ubah-akun') }}" class="service-item">
                    <i class="bx bx-cog"></i>
                </a>
                <div class="service-label">Pengaturan</div>
            </div>

            @if(Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah')
            <div>
                <a href="{{ route('mobile.kelola-izin') }}" class="service-item">
                    <i class="bx bx-edit"></i>
                </a>
                <div class="service-label">Kelola Izin</div>
            </div>
            <div>
                <a href="{{ route('mobile.monitor-presensi') }}" class="service-item">
                    <i class="bx bx-calendar-check"></i>
                </a>
                <div class="service-label">Data Presensi</div>
            </div>
            @endif
        </div>
    </div>


    <!-- Teacher Info -->
    {{-- <div class="info-section">
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
    </div> --}}

    <small>Jadwal Hari Ini</small>

    <!-- Schedule Section -->
    <div class="schedule-section">
        {{-- <h6 class="section-title">Jadwal Hari Ini</h6> --}}
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
</div>
@endsection
