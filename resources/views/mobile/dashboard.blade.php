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
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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

            // Auto hide after 5 seconds
            setTimeout(function() {
                welcomeModal.hide();
            }, 5000);
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
    <div class="info-section" style="margin-bottom: 12px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <a href="{{ route('mobile.kelola-izin') }}" class="action-button" style="display: block; text-align: center; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 500;">
                <i class="bx bx-edit" style="font-size: 20px; margin-bottom: 4px;"></i>
                <span>Kelola Izin</span>
            </a>
            <a href="{{ route('mobile.monitor-presensi') }}" class="action-button" style="display: block; text-align: center; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 500;">
                <i class="bx bx-calendar-check" style="font-size: 20px; margin-bottom: 4px;"></i>
                <span>Data Presensi</span>
            </a>
        </div>
        {{-- <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
            <a href="{{ route('mobile.monitor-jadwal-mengajar') }}" class="action-button" style="display: block; text-align: center; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 500;">
                <i class="bx bx-calendar-event" style="font-size: 20px; margin-bottom: 4px;"></i>
                <span>Jadwal Mengajar</span>
            </a>
            <a href="{{ route('mobile.data-jadwal') }}" class="action-button" style="display: block; text-align: center; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: white; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 500;">
                <i class="bx bx-chalkboard" style="font-size: 20px; margin-bottom: 4px;"></i>
                <span>Data Kelas & Jadwal</span>
            </a>
        </div> --}}
    </div>
    @endif

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
@endsection
