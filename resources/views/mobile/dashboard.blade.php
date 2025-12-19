@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<?php

date_default_timezone_set('Asia/Jakarta');

$b = time();
$hour = date('G', $b);

if ($hour >= 0 && $hour <= 11) {
    $congrat = 'Selamat Pagi';
} elseif ($hour >= 12 && $hour <= 14) {
    $congrat = 'Selamat Siang ';
} elseif ($hour >= 15 && $hour <= 17) {
    $congrat = 'Selamat Sore ';
} elseif ($hour >= 17 && $hour <= 18) {
    $congrat = 'Selamat Petang ';
} elseif ($hour >= 19 && $hour <= 23) {
    $congrat = 'Selamat Malam ';
}

?>
<header class="mobile-header d-md-none" style="position: sticky; top: 0; z-index: 1050;">
    <div class="container-fluid px-0 py-0" style="background: transparent;">
        <div class="d-flex align-items-center justify-content-between">
            <!-- User Avatar (Left) -->
            <div class="avatar-sm me-3 ms-3">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                    class="avatar-img rounded-circle"
                    alt="User"
                >
            </div>

            <!-- Welcome Text (Right-aligned) -->
            <div class="text-start flex-grow-1">
                <small class="text-dark fw-medium" style="font-size: 11px;">{{ $congrat }}</small>
                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 14px;">{{ Auth::user()->name }}</h6>
            </div>

            <!-- Notification and Menu Buttons (Right) -->
            <div class="d-flex align-items-center">
                <!-- Notification Bell -->
                <a href="{{ route('mobile.notifications') }}" class="btn btn-link text-decoration-none p-0 me-2 position-relative">
                    <i class="bx bx-bell" style="font-size: 22px; color: #db3434;"></i>
                    <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 9px; padding: 2px 5px; top: -4px; right: -4px; display: none;">0</span>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded" style="font-size: 22px; color: #000000;"></i>
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
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh; /* 🔥 minimal tinggi layar */
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        @media (max-width: 768px) {
            body {
                background-size: 100% auto;
                background-attachment: scroll; /* 🔥 hindari bug mobile */
            }
        }

        .dashboard-header {
            background: #f8f9fb url('{{ asset("images/qwe1.png") }}') no-repeat center center;
            background-size: cover;
            border-radius: 14px;
            padding: 12px;
            color: #004b4c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
        }

        .id-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .id-card-photo {
            width: 56px;
            height: 76px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            /* border: 2px solid rgba(255,255,255,0.4); */
        }

        .id-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .id-card-details {
            flex: 1;
        }

        .row-item {
            display: grid;
            grid-template-columns: 70px 10px 1fr;
            align-items: center;
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .row-item:last-child {
            margin-bottom: 0;
        }

        .label {
            color: #004b4c;
            text-align: left;
        }

        .colon {
            color: #004b4c;
            text-align: center;
        }

        .value {
            font-weight: 600;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #004b4c;
        }

        .badge-status {
            background: rgba(0,75,76,0.1);
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 10px;
            color: #004b4c;
            border: 1px solid rgba(0,75,76,0.2);
        }

        .id-card-title {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #004b4c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(0,75,76,0.3);
            padding-bottom: 4px;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
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
            gap: 16px;
            text-align: center;
        }

        .service-wrapper {
            text-align: center;
        }

        .service-item {
            background: linear-gradient(rgba(248,249,251,0.8), rgba(248,249,251,0.8)), url('{{ asset("images/qwe1.png") }}') no-repeat center center;
            background-size: cover;
            border-radius: 8px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
            height: 64px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid rgba(0,75,76,0.2);
        }

        .extra-service {
            visibility: hidden;
            height: 0;
            overflow: hidden;
        }

        .extra-service.show {
            visibility: visible;
            height: auto;
        }

        .service-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .service-item i {
            font-size: 30px;
            color: #003d3d;
        }

        .service-label {
            font-size: 10px;
            font-weight: 600;
            margin-top: 6px;
            color: #333;
        }

        .service-item i {
            font-size: 24px;
            color: #003d3d;
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

        .avatar-sm {
            width: 40px;
            height: 40px;
            overflow: hidden;
            border-radius: 50%;
        }

        .avatar-sm .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;      /* 🔥 kunci anti gepeng */
            object-position: center;
            display: block;
        }

    </style>

    <!-- Show banner modal on page load -->
    @if($showBanner)
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
    <div class="dashboard-header mb-4">
        <div class="id-card-title">Kartu Identitas Digital</div>
        <div class="id-card">
            <!-- Foto -->
            <div class="id-card-photo">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                    alt="User"
                >
            </div>

            <!-- Detail -->
            <div class="id-card-details">
                <div class="row-item">
                    <span class="label">Nama</span>
                    <span class="colon">:</span>
                    <span class="value">{{ Auth::user()->name }}</span>
                </div>
                <div class="row-item">
                    <span class="label">TTL</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $userInfo['tempat_lahir'] }}, {{ $userInfo['tanggal_lahir'] }}</span>
                </div>
                <div class="row-item">
                    <span class="label">NIPM</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $userInfo['nip'] }}</span>
                </div>
                <div class="row-item">
                    <span class="label">NUIST ID</span>
                    <span class="colon">:</span>
                    <span class="value">{{ $userInfo['nuist_id'] }}</span>
                </div>
                <div class="row-item">
                    <span class="label">Asal Sekolah</span>
                    <span class="colon">:</span>
                    <span class="value text-truncate">
                        {{ Auth::user()->madrasah?->name ?? 'Belum diatur' }}
                    </span>
                </div>
                <div class="row-item">
                    <span class="label">Status</span>
                    <span class="colon">:</span>
                    <span class="value badge-status">
                        {{ $userInfo['status_kepegawaian'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <small class="stats-form">Aktifitas Presensi</small>

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

    <small>Layanan</small>

    <!-- Services Form -->
    <div class="services-form">
        <div class="services-grid" id="servicesGrid">
            <div class="service-wrapper">
                <a href="{{ route('mobile.presensi') }}" class="service-item">
                    <i class="bx bx-fingerprint"></i>
                </a>
                <div class="service-label">Presensi</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.teaching-attendances') }}" class="service-item">
                    <i class="bx bx-chalkboard"></i>
                </a>
                <div class="service-label">Presensi Mengajar</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'cuti']) }}" class="service-item">
                    <i class="bx bx-calendar-star"></i>
                </a>
                <div class="service-label">Izin Cuti</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'terlambat']) }}" class="service-item">
                    <i class="bx bx-time-five"></i>
                </a>
                <div class="service-label">Izin Terlambat</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'sakit']) }}" class="service-item">
                    <i class="bx bx-plus-medical"></i>
                </a>
                <div class="service-label">Izin Sakit</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.izin', ['type' => 'tugas_luar']) }}" class="service-item">
                    <i class="bx bx-briefcase"></i>
                </a>
                <div class="service-label">Izin Dinas Luar</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('mobile.jadwal') }}" class="service-item">
                    <i class="bx bx-calendar"></i>
                </a>
                <div class="service-label">Jadwal Mengajar</div>
            </div>
            <div id="viewAllBtn" class="service-wrapper">
                <a href="#" class="service-item" onclick="return toggleServices(event)">
                    <i class="bx bx-plus"></i>
                </a>
                <div class="service-label">Lihat Semua</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.profile') }}" class="service-item">
                    <i class="bx bx-user"></i>
                </a>
                <div class="service-label">Profile</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.ubah-akun') }}" class="service-item">
                    <i class="bx bx-cog"></i>
                </a>
                <div class="service-label">Pengaturan</div>
            </div>

            @if(Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah')
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.kelola-izin') }}" class="service-item">
                    <i class="bx bx-edit"></i>
                </a>
                <div class="service-label">Kelola Izin</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.monitor-presensi') }}" class="service-item">
                    <i class="bx bx-calendar-check"></i>
                </a>
                <div class="service-label">Data Presensi</div>
            </div>
            <div class="extra-service service-wrapper">
                <a href="{{ route('mobile.laporan') }}" class="service-item">
                    <i class="bx bx-file"></i>
                </a>
                <div class="service-label">Laporan</div>
            </div>

            @endif
        </div>
    </div>

    <script>
        function toggleServices(event) {
            event.preventDefault();

            const extraServices = document.querySelectorAll('.extra-service');
            const viewAllBtn = document.getElementById('viewAllBtn');
            const servicesGrid = document.getElementById('servicesGrid');
            const icon = viewAllBtn.querySelector('i');
            const label = viewAllBtn.querySelector('.service-label');

            const isHidden = !extraServices[0].classList.contains('show');

            extraServices.forEach(service => {
                service.classList.toggle('show', isHidden);
            });

            if (isHidden) {
                // Move button to the end
                servicesGrid.appendChild(viewAllBtn);
                icon.className = 'bx bx-minus';
                label.textContent = 'Tutup';
            } else {
                // Move button back to original position (before first extra-service)
                const firstExtra = extraServices[0];
                servicesGrid.insertBefore(viewAllBtn, firstExtra);
                icon.className = 'bx bx-plus';
                label.textContent = 'Lihat Lainnya';
            }
        }
    </script>


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
