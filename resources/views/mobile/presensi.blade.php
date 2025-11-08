@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .presensi-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .presensi-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .presensi-header h5 {
            font-size: 14px;
        }

        .status-card {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .status-card.success {
            border-left: 4px solid #0e8549;
        }

        .status-card.warning {
            border-left: 4px solid #ffc107;
        }

        .status-icon {
            width: 28px;
            height: 28px;
            background: rgba(14, 133, 73, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .status-icon i {
            color: #0e8549;
            font-size: 14px;
        }

        .presensi-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .face-verification-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .face-camera-container {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
            margin: 8px 0;
        }

        .face-camera-preview {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .face-instruction {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        .challenge-progress {
            display: flex;
            justify-content: center;
            margin: 8px 0;
        }

        .challenge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #dee2e6;
            margin: 0 2px;
        }

        .challenge-dot.active {
            background: #0e8549;
        }

        .challenge-dot.completed {
            background: #0e8549;
        }

        .user-location-map-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }

        .map-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 1;
        }

        .map-placeholder i {
            font-size: 32px;
            color: #adb5bd;
            margin-bottom: 8px;
        }

        .map-placeholder span {
            font-size: 11px;
            color: #6c757d;
            text-align: center;
        }

        .izin-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .izin-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        /* Responsive fallback: on narrow screens keep two-column layout */
        @media (max-width: 420px) {
            .izin-buttons {
                grid-template-columns: 1fr 1fr;
            }
        }

        .izin-btn {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            font-size: 11px;
            font-weight: 500;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.2s;
            min-height: 60px;
        }

        .izin-btn:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }

        .izin-btn i {
            font-size: 18px;
            margin-bottom: 4px;
            color: #0e8549;
        }

        .izin-terlambat {
            border-color: rgba(255, 193, 7, 0.3);
        }

        .izin-terlambat:hover {
            background: rgba(255, 193, 7, 0.1);
        }

        .izin-tugas-luar {
            border-color: rgba(0, 123, 255, 0.3);
        }

        .izin-tugas-luar:hover {
            background: rgba(0, 123, 255, 0.1);
        }

        .form-section {
            margin-bottom: 10px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 6px;
            color: #333;
        }

        .location-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 6px;
            margin-bottom: 6px;
            word-wrap: break-word;
        }

        .location-info.success {
            background: rgba(14, 133, 73, 0.1);
            border: 1px solid rgba(14, 133, 73, 0.2);
        }

        .location-info.error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .location-info.info {
            background: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.2);
        }

        .coordinate-input {
            background: #fff;
            border-radius: 4px;
            padding: 4px 6px;
            border: 1px solid #e9ecef;
            font-size: 11px;
            width: 100%;
        }

        .address-input {
            background: #fff;
            border-radius: 4px;
            padding: 4px 6px;
            border: 1px solid #e9ecef;
            font-size: 11px;
            width: 100%;
            word-wrap: break-word;
        }

        .presensi-btn {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 6px;
            padding: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
            width: 100%;
            margin-top: 6px;
        }

        .presensi-btn:disabled {
            background: #6c757d;
        }

        .schedule-section {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .schedule-item {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 6px;
            text-align: center;
        }

        .schedule-item.masuk {
            border-left: 2px solid #0d6efd;
        }

        .schedule-item.pulang {
            border-left: 2px solid #0e8549;
        }

        .schedule-item i {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .schedule-item h6 {
            font-size: 11px;
            margin-bottom: 1px;
            font-weight: 600;
        }

        .schedule-item p {
            font-size: 10px;
            margin-bottom: 1px;
        }

        .schedule-item small {
            font-size: 9px;
            color: #6c757d;
        }

        .alert-custom {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .alert-custom.warning {
            border-left: 4px solid #ffc107;
        }

        .alert-custom.danger {
            border-left: 4px solid #dc3545;
        }

        .alert-custom.info {
            border-left: 4px solid #0dcaf0;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary-custom:hover {
            color: #fff;
            background: linear-gradient(135deg, #003d3e 0%, #0c6a42 100%);
        }
    </style>

    <!-- Header -->
    <div class="presensi-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Presensi Digital</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- User Location Map -->
    <div class="presensi-form">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-map-pin"></i>
            </div>
            <h6 class="section-title mb-0">Lokasi Anda Saat Ini</h6>
        </div>
        <div class="user-location-map-container" style="height: 220px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 2px solid rgba(14, 133, 73, 0.1);">
            <div id="map-placeholder" class="map-placeholder">
                <i class="bx bx-map"></i>
                <span>Menunggu data lokasi...<br>Peta akan muncul setelah GPS aktif</span>
            </div>
            <div id="user-location-map" style="height: 100%; width: 100%;"></div>
        </div>
        <div class="mt-2 text-center">
            <small class="text-muted" style="font-size: 10px;">
                <i class="bx bx-info-circle me-1"></i>
                Titik hijau menunjukkan lokasi Anda saat ini
            </small>
        </div>
    </div>

    <!-- Status Card -->
    @if($isHoliday)
    <div class="alert-custom warning">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-calendar-x"></i>
            </div>
            <div>
                <h6 class="mb-0">Hari Libur</h6>
                <p class="mb-0">{{ $holiday->name ?? 'Hari ini libur' }}</p>
            </div>
        </div>
    </div>
    @elseif($presensiHariIni && $presensiHariIni->count() > 0)
    <div class="status-card success">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div>
                <h6 class="mb-1">Presensi Sudah Dicatat</h6>
                @foreach($presensiHariIni as $presensi)
                <div class="mb-2" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 4px;">
                    <small class="text-white-50">{{ $presensi->madrasah?->name ?? 'Madrasah' }}</small>
                    <p class="mb-1">Masuk: <strong>{{ $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : '-' }}</strong></p>
                    @if($presensi->waktu_keluar)
                    <p class="mb-0">Keluar: <strong>{{ $presensi->waktu_keluar->format('H:i') }}</strong></p>
                    @else
                    <p class="mb-0 text-muted">Belum presensi keluar</p>
                    @endif
                </div>
                @endforeach
                @if($presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count())
                <div class="alert-custom success" style="margin-top: 6px; padding: 4px;">
                    <small><i class="bx bx-check me-1"></i> Semua presensi hari ini lengkap!</small>
                </div>
                @else
                <p class="mb-0 text-muted">Lakukan presensi keluar jika sudah selesai.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Presensi Form -->
    <div class="presensi-form">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }}"></i>
            </div>
        <h6 class="section-title mb-0">{{ ($presensiHariIni && $presensiHariIni->count() > 0) ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
        </div>

    <!-- Location Status -->
        <div class="form-section">
            <div id="location-info" class="location-info info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-1"></i>
                    <div>
                        <strong>Mengumpulkan data lokasi...</strong>
                        <br><small class="text-muted">Reading 1/3 - Pastikan GPS aktif</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordinates -->
        <div class="form-section">
            <div class="d-flex align-items-center mb-1">
                <i class="bx bx-target-lock text-success me-1"></i>
                <label class="section-title mb-0">Koordinat Lokasi</label>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                <input type="text" id="latitude" class="coordinate-input" placeholder="Latitude" readonly>
                <input type="text" id="longitude" class="coordinate-input" placeholder="Longitude" readonly>
            </div>
        </div>

        <!-- Address -->
        <div class="form-section">
            <div class="d-flex align-items-center mb-1">
                <i class="bx bx-home text-info me-1"></i>
                <label class="section-title mb-0">Alamat Lokasi</label>
            </div>
            <input type="text" id="lokasi" class="address-input" placeholder="Alamat akan muncul otomatis" readonly>
        </div>

        <!-- Presensi Button -->
        <button type="button" id="btn-presensi"
                class="presensi-btn"
                disabled
                {{ (($presensiHariIni && $presensiHariIni->count() > 0) && $presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count()) || $isHoliday ? 'disabled' : '' }}>
            <i class="bx bx-{{ $isHoliday ? 'calendar-x' : 'check-circle' }} me-1"></i>
            {{ $isHoliday ? 'Hari Libur - Presensi Ditutup' : 'Mengumpulkan data lokasi...' }}
        </button>
    </div>

    <!-- Time Information -->
    @if(isset($timeRanges) && $timeRanges)
    <div class="schedule-section">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-calendar-check"></i>
            </div>
            <h6 class="section-title mb-0">Jadwal Presensi</h6>
        </div>
        <div class="schedule-grid">
            <div class="schedule-item masuk">
                <i class="bx bx-log-in-circle text-primary"></i>
                <h6 class="text-primary">Masuk</h6>
                <p>{{ $timeRanges['masuk_start'] }} - 07:00</p>
                <small>Terlambat setelah 07:00</small>
            </div>
            <div class="schedule-item pulang">
                <i class="bx bx-log-out-circle text-success"></i>
                <h6 class="text-success">Pulang</h6>
                <p>{{ $timeRanges['pulang_start'] }} - {{ $timeRanges['pulang_end'] }}</p>
                @if($user->madrasah && $user->madrasah->hari_kbm == '6' && \Carbon\Carbon::parse($selectedDate)->dayOfWeek == 5)
                <small>Jumat khusus: mulai 14:30</small>
                @else
                <small>Mulai pukul {{ $timeRanges['pulang_start'] }}</small>
                @endif
            </div>
        </div>
        <div class="alert-custom info" style="margin-top: 6px;">
            <small>
                <i class="bx bx-info-circle me-1"></i>
                <strong>Catatan:</strong>
                @if($user->madrasah && $user->madrasah->hari_kbm == '6' && \Carbon\Carbon::parse($selectedDate)->dayOfWeek == 5)
                Pulang dapat dilakukan mulai pukul 14:30 hingga 22:00 (khusus Jumat).
                @else
                Pulang dapat dilakukan mulai pukul 15:00 hingga 22:00.
                @endif
            </small>
        </div>
    </div>
    @else
    <div class="alert-custom warning">
        <i class="bx bx-info-circle me-1"></i>
        <strong>Pengaturan Presensi:</strong> Hari KBM belum diatur. Hubungi admin.
    </div>
    @endif

    <!-- Face Verification Section -->
    @if(Auth::user()->face_verification_required ?? true)
    <div class="face-verification-section">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-face"></i>
            </div>
            <h6 class="section-title mb-0">Verifikasi Wajah</h6>
        </div>

        <div id="face-verification-container">
            <!-- Face enrollment prompt if not enrolled -->
            @if(empty(Auth::user()->face_data))
            <div id="face-enrollment-prompt" class="text-center">
                <i class="bx bx-face text-warning" style="font-size: 48px;"></i>
                <h6 class="mt-2">Pendaftaran Wajah Diperlukan</h6>
                <p class="text-muted small">Anda perlu mendaftarkan wajah terlebih dahulu untuk dapat melakukan presensi.</p>
                <button id="start-face-enrollment" class="btn btn-primary-custom">
                    <i class="bx bx-plus me-1"></i>Daftar Wajah Sekarang
                </button>
            </div>

            <!-- Face enrollment interface (hidden initially) -->
            <div id="face-enrollment-interface" style="display: none;">
                <div class="face-camera-container">
                    <video id="face-camera" class="face-camera-preview" autoplay playsinline muted></video>
                    <div id="face-instruction-overlay" class="face-instruction">
                        <div id="face-instruction-text">Memuat kamera...</div>
                    </div>
                </div>

                <div class="challenge-progress">
                    <div class="challenge-dot" id="challenge-1"></div>
                    <div class="challenge-dot" id="challenge-2"></div>
                    <div class="challenge-dot" id="challenge-3"></div>
                </div>

                <button id="start-enrollment-process" class="btn btn-primary-custom" disabled>
                    <i class="bx bx-play me-1"></i>Mulai Pendaftaran
                </button>

                <div id="face-enrollment-status" class="mt-2 text-center" style="display: none;">
                    <small class="text-muted">Status pendaftaran akan muncul di sini</small>
                </div>
            </div>
            @else
            <!-- Face verification interface -->
            <div id="face-verification-interface">
                <div class="face-camera-container">
                    <video id="face-camera" class="face-camera-preview" autoplay playsinline muted></video>
                    <div id="face-instruction-overlay" class="face-instruction">
                        <div id="face-instruction-text">Memuat kamera...</div>
                    </div>
                </div>

                <div class="challenge-progress">
                    <div class="challenge-dot" id="challenge-1"></div>
                    <div class="challenge-dot" id="challenge-2"></div>
                    <div class="challenge-dot" id="challenge-3"></div>
                </div>

                <button id="start-face-verification" class="btn btn-primary-custom" disabled>
                    <i class="bx bx-play me-1"></i>Mulai Verifikasi
                </button>

                <div id="face-verification-status" class="mt-2 text-center" style="display: none;">
                    <small class="text-muted">Status verifikasi akan muncul di sini</small>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Important Notice -->
    <div class="alert-custom danger">
        <div class="d-flex">
            <i class="bx bx-error-circle text-danger me-1"></i>
            <div>
                <strong class="text-danger">Penting!</strong>
                <p class="mb-0 text-muted">Pastikan berada di lingkungan madrasah untuk presensi.</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Presensi Button -->
    <div class="presensi-form">
        <a href="{{ route('mobile.riwayat-presensi') }}" class="presensi-btn" style="display: block; text-decoration: none; color: #fff; text-align: center;">
            <i class="bx bx-history me-1"></i>
            Riwayat Presensi
        </a>
    </div>

    <!-- Izin: single button to mobile izin menu -->
    <div class="presensi-form">
        <a href="{{ route('mobile.izin') }}" class="presensi-btn" style="display: block; text-decoration: none; color: #fff; text-align: center;">
            <i class="bx bx-calendar-minus me-1"></i>
            Izin
        </a>
    </div>

    <!-- Monitor Map: dedicated button for kepala madrasah -->
    @if(Auth::user()->ketugasan === 'kepala madrasah/sekolah')
    <div class="presensi-form">
        <a href="{{ route('mobile.monitor-map') }}" class="presensi-btn" style="display: block; text-decoration: none; color: #fff; text-align: center;">
            <i class="bx bx-map me-1"></i>
            Monitor Map Presensi
        </a>
    </div>
    @endif

    <!-- Monitoring Presensi: Map View -->
    @if(Auth::user()->ketugasan === 'kepala madrasah/sekolah')
    <div class="presensi-form">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-map"></i>
            </div>
            <h6 class="section-title mb-0">Monitoring Lokasi Presensi</h6>
        </div>

        <!-- Map Container -->
        <div id="presensi-map" style="height: 300px; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>

        <!-- Legend -->
        <div class="d-flex justify-content-center mt-2" style="gap: 12px;">
            <div class="d-flex align-items-center">
                <div style="width: 12px; height: 12px; background: #0e8549; border-radius: 50%; margin-right: 4px;"></div>
                <small style="font-size: 10px;">Sudah Presensi</small>
            </div>
            <div class="d-flex align-items-center">
                <div style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%; margin-right: 4px;"></div>
                <small style="font-size: 10px;">Belum Presensi</small>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="mt-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
            <div style="background: rgba(14, 133, 73, 0.1); padding: 6px; border-radius: 6px; text-align: center;">
                <div style="font-weight: 600; font-size: 12px; color: #0e8549;">{{ $presensis->count() }}</div>
                <small style="font-size: 10px; color: #0e8549;">Sudah Presensi</small>
            </div>
            <div style="background: rgba(220, 53, 69, 0.1); padding: 6px; border-radius: 6px; text-align: center;">
                <div style="font-weight: 600; font-size: 12px; color: #dc3545;">{{ $belumPresensi->count() }}</div>
                <small style="font-size: 10px; color: #dc3545;">Belum Presensi</small>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/face-recognition.js') }}"></script>
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi;
    let locationReadings = [];
    let readingCount = 0;
    const totalReadings = 3;
    const readingInterval = 5000; // 5 seconds

    // Face recognition variables
    let faceRecognition = null;
    let currentChallengeIndex = 0;
    let challengeSequence = [];
    let isVerificationRunning = false;

    // Map variables
    let userLocationMap = null;
    let userLocationMarker = null;

    // Function to collect location readings
    function collectLocationReading(readingNumber) {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const reading = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        timestamp: Date.now(),
                        accuracy: position.coords.accuracy,
                        altitude: position.coords.altitude,
                        speed: position.coords.speed
                    };

                    // Store in sessionStorage
                    sessionStorage.setItem(`reading${readingNumber}_latitude`, reading.latitude);
                    sessionStorage.setItem(`reading${readingNumber}_longitude`, reading.longitude);
                    sessionStorage.setItem(`reading${readingNumber}_timestamp`, reading.timestamp);
                    sessionStorage.setItem(`reading${readingNumber}_accuracy`, reading.accuracy);
                    sessionStorage.setItem(`reading${readingNumber}_altitude`, reading.altitude || null);
                    sessionStorage.setItem(`reading${readingNumber}_speed`, reading.speed || null);

                    locationReadings.push(reading);
                    readingCount++;

                    // Update UI
                    $('#location-info').html(`
                        <div class="location-info info">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-loader-alt bx-spin me-2"></i>
                                <div>
                                    <strong class="small">Mengumpulkan data lokasi...</strong>
                                    <br><small class="text-muted">Reading ${readingCount}/${totalReadings} - ${readingCount < totalReadings ? 'Tunggu sebentar...' : 'Selesai!'}</small>
                                </div>
                            </div>
                        </div>
                    `);

                    // Update coordinates display with latest reading
                    $('#latitude').val(reading.latitude.toFixed(6));
                    $('#longitude').val(reading.longitude.toFixed(6));

                    // Update user location map
                    updateUserLocationMap(reading.latitude, reading.longitude);

                    // Get address from latest reading
                    getAddressFromCoordinates(reading.latitude, reading.longitude);

                    resolve(reading);
                },
                function(error) {
                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 30000
                }
            );
        });
    }

    // Start collecting multiple readings
    async function startLocationCollection() {
        try {
            for (let i = 1; i <= totalReadings; i++) {
                await collectLocationReading(i);

                // Wait 5 seconds between readings (except for the last one)
                if (i < totalReadings) {
                    await new Promise(resolve => setTimeout(resolve, readingInterval));
                }
            }

            // All readings collected successfully
            latitude = locationReadings[locationReadings.length - 1].latitude;
            longitude = locationReadings[locationReadings.length - 1].longitude;

            $('#location-info').html(`
                <div class="location-info success">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle me-2"></i>
                        <div>
                            <strong class="small">Data lokasi lengkap!</strong>
                            <br><small class="text-muted">Siap untuk presensi</small>
                        </div>
                    </div>
                </div>
            `);

            // Enable presensi button - check if all presensi records have waktu_keluar
            var hasPresensi = {{ $presensiHariIni && $presensiHariIni->count() > 0 ? 'true' : 'false' }};
            var allPresensiComplete = {{ ($presensiHariIni && $presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count()) ? 'true' : 'false' }};
            var buttonText = hasPresensi && !allPresensiComplete ? "Presensi Keluar" : "Presensi Masuk";
            $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-1"></i>' + buttonText);

        } catch (error) {
            $('#location-info').html(`
                <div class="location-info error">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle me-2"></i>
                        <div>
                            <strong class="small">Gagal mendapatkan lokasi</strong>
                            <br><small class="text-muted">${error.message}</small>
                        </div>
                    </div>
                </div>
            `);
        }
    }

    // Initialize user location map
    function initializeUserLocationMap() {
        if (userLocationMap) return; // Already initialized

        userLocationMap = L.map('user-location-map').setView([-6.2, 106.816666], 13); // Default to Jakarta

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(userLocationMap);

        // Hide placeholder
        $('#map-placeholder').hide();
    }

    // Update user location map
    function updateUserLocationMap(lat, lng) {
        if (!userLocationMap) {
            initializeUserLocationMap();
        }

        // Remove existing marker
        if (userLocationMarker) {
            userLocationMap.removeLayer(userLocationMarker);
        }

        // Add new marker
        userLocationMarker = L.marker([lat, lng]).addTo(userLocationMap)
            .bindPopup('Lokasi Anda saat ini')
            .openPopup();

        // Center map on location
        userLocationMap.setView([lat, lng], 16);
    }

    // Check if geolocation is supported
    if (navigator.geolocation) {
        startLocationCollection();
    } else {
        $('#location-info').html(`
            <div class="location-info error">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-2"></i>
                    <div>
                        <strong class="small">Browser tidak mendukung GPS</strong>
                        <br><small class="text-muted">Silakan gunakan browser modern dengan dukungan GPS</small>
                    </div>
                </div>
            </div>
        `);
    }

    // Get address from coordinates
    function getAddressFromCoordinates(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    lokasi = data.display_name;
                    $('#lokasi').val(lokasi);
                }
            })
            .catch(error => {
                console.error('Error getting address:', error);
                $('#lokasi').val('Tidak dapat mendapatkan alamat');
            });
    }

    // Initialize face recognition if required
    const faceRequired = {{ Auth::user()->face_verification_required ? 'true' : 'false' }};
    const hasFaceData = {{ !empty(Auth::user()->face_data) ? 'true' : 'false' }};

    if (faceRequired && hasFaceData) {
        initializeFaceRecognition();
    } else if (faceRequired && !hasFaceData) {
        initializeFaceEnrollment();
    }

    async function initializeFaceRecognition() {
        try {
            faceRecognition = new FaceRecognition();

            // Load models
            const modelsLoaded = await faceRecognition.loadModels();
            if (!modelsLoaded) {
                throw new Error('Gagal memuat model pengenalan wajah');
            }

            // Initialize camera
            const videoElement = document.getElementById('face-camera');
            await faceRecognition.initializeCamera(videoElement);

            // Update UI
            document.getElementById('face-instruction-text').innerText = 'Siap untuk verifikasi wajah';
            $('#start-face-verification').prop('disabled', false);

        } catch (error) {
            console.error('Face recognition initialization error:', error);
            document.getElementById('face-instruction-text').innerText = 'Gagal menginisialisasi verifikasi wajah: ' + error.message;
        }
    }

    // Handle start face verification
    $('#start-face-verification').click(async function() {
        if (isVerificationRunning) return;

        isVerificationRunning = true;
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Memulai...');

        try {
            if (!faceRecognition) {
                throw new Error('Face recognition belum diinisialisasi');
            }

            // Generate challenge sequence
            challengeSequence = faceRecognition.generateChallengeSequence();
            currentChallengeIndex = 0;

            // Reset progress dots
            $('.challenge-dot').removeClass('active completed');

            // Start verification process
            await runVerificationSequence();

        } catch (error) {
            console.error('Verification start error:', error);
            document.getElementById('face-instruction-text').innerText = 'Error: ' + error.message;
            $('#face-verification-status').show().html(`<small class="text-danger">${error.message}</small>`);
        } finally {
            isVerificationRunning = false;
            btn.prop('disabled', false).html('<i class="bx bx-play me-1"></i>Mulai Verifikasi');
        }
    });

    // Handle start face enrollment
    $('#start-face-enrollment').click(function() {
        $('#face-enrollment-prompt').hide();
        $('#face-enrollment-interface').show();
        initializeFaceEnrollment();
    });

    async function initializeFaceEnrollment() {
        try {
            faceRecognition = new FaceRecognition();

            // Load models
            const modelsLoaded = await faceRecognition.loadModels();
            if (!modelsLoaded) {
                throw new Error('Gagal memuat model pengenalan wajah');
            }

            // Initialize camera
            const videoElement = document.getElementById('face-camera');
            await faceRecognition.initializeCamera(videoElement);

            // Update UI
            document.getElementById('face-instruction-text').innerText = 'Siap untuk pendaftaran wajah';
            $('#start-enrollment-process').prop('disabled', false);

        } catch (error) {
            console.error('Face enrollment initialization error:', error);
            document.getElementById('face-instruction-text').innerText = 'Gagal menginisialisasi pendaftaran wajah: ' + error.message;
        }
    }

    // Handle start enrollment process
    $('#start-enrollment-process').click(async function() {
        if (isVerificationRunning) return;

        isVerificationRunning = true;
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Mendaftarkan...');

        try {
            if (!faceRecognition) {
                throw new Error('Face recognition belum diinisialisasi');
            }

            // Generate challenge sequence for enrollment
            challengeSequence = faceRecognition.generateChallengeSequence();
            currentChallengeIndex = 0;

            // Reset progress dots
            $('.challenge-dot').removeClass('active completed');

            // Start enrollment process
            await runEnrollmentSequence();

        } catch (error) {
            console.error('Enrollment start error:', error);
            document.getElementById('face-instruction-text').innerText = 'Error: ' + error.message;
            $('#face-enrollment-status').show().html(`<small class="text-danger">${error.message}</small>`);
        } finally {
            isVerificationRunning = false;
            btn.prop('disabled', false).html('<i class="bx bx-play me-1"></i>Mulai Pendaftaran');
        }
    });

    async function runEnrollmentSequence() {
        try {
            // Run full enrollment with liveness challenges
            const result = await faceRecognition.performFullEnrollment(
                document.getElementById('face-camera')
            );

            // Build payload for enrollment
            const payload = {
                user_id: {{ Auth::user()->id }},
                face_data: result.faceDescriptor,
                liveness_score: result.livenessScore,
                liveness_challenges: result.challenges,
                device_info: navigator.userAgent
            };

            // Send enrollment data to server
            const response = await fetch('{{ route("mobile.face.enroll") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            });

            const serverResult = await response.json();

            if (serverResult.success) {
                document.getElementById('face-instruction-text').innerText = 'Pendaftaran berhasil ✓';
                $('#face-enrollment-status').show().html(`
                    <small class="text-success">
                        Wajah berhasil didaftarkan! Liveness: ${result.livenessScore.toFixed(2)}
                    </small>
                `);

                // Mark all challenge dots as completed
                $('.challenge-dot').addClass('completed');

                // Reload page after success
                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                throw new Error(serverResult.message || 'Pendaftaran gagal');
            }

        } catch (error) {
            console.error('Enrollment sequence error:', error);
            document.getElementById('face-instruction-text').innerText = 'Pendaftaran gagal ✗';
            $('#face-enrollment-status').show().html(`<small class="text-danger">${error.message}</small>`);
        }
    }

    async function runVerificationSequence() {
        const registeredFaceData = {{ json_encode(Auth::user()->face_data) }};

        try {
            // Run full verification with liveness challenges
            const result = await faceRecognition.performFullVerification(
                document.getElementById('face-camera'),
                registeredFaceData
            );

            // Store result globally for presensi submission
            window.lastFaceVerificationResult = {
                face_verified: result.faceVerified,
                face_id: result.faceId,
                similarity_score: result.faceSimilarity,
                liveness_score: result.livenessScore,
                liveness_challenges: result.challenges,
                timestamp: result.timestamp
            };

            // Update UI based on result
            if (result.faceVerified && result.livenessScore >= 0.7) {
                document.getElementById('face-instruction-text').innerText = 'Verifikasi berhasil ✓';
                $('#face-verification-status').show().html(`
                    <small class="text-success">
                        Wajah cocok (${(result.faceSimilarity * 100).toFixed(1)}%) •
                        Liveness: ${result.livenessScore.toFixed(2)}
                    </small>
                `);

                // Mark all challenge dots as completed
                $('.challenge-dot').addClass('completed');

            } else {
                let reason = '';
                if (!result.faceVerified) reason += 'Wajah tidak cocok. ';
                if (result.livenessScore < 0.7) reason += 'Liveness check gagal.';

                document.getElementById('face-instruction-text').innerText = 'Verifikasi gagal ✗';
                $('#face-verification-status').show().html(`<small class="text-danger">${reason}</small>`);
            }

        } catch (error) {
            console.error('Verification sequence error:', error);
            document.getElementById('face-instruction-text').innerText = 'Error: ' + error.message;
            $('#face-verification-status').show().html(`<small class="text-danger">${error.message}</small>`);
        }
    }

    // Handle presensi button
    $('#btn-presensi').click(function() {
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Data lokasi belum lengkap. Pastikan GPS aktif dan tunggu proses pengumpulan data selesai.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        // Check face verification if required
        if (faceRequired) {
            if (!window.lastFaceVerificationResult || !window.lastFaceVerificationResult.face_verified) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verifikasi Wajah Diperlukan',
                    text: 'Silakan jalankan verifikasi wajah sebelum melakukan presensi.',
                    confirmButtonText: 'Oke'
                });
                return;
            }
        }

        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Get final location reading (button click) as reading4
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let reading4Lat = position.coords.latitude;
                let reading4Lng = position.coords.longitude;
                let reading4Timestamp = Date.now();

                // Build location readings array from all stored readings
                let allReadings = [];

                // Add readings 1-3 from sessionStorage
                for (let i = 1; i <= 3; i++) {
                    let lat = sessionStorage.getItem(`reading${i}_latitude`);
                    let lng = sessionStorage.getItem(`reading${i}_longitude`);
                    let timestamp = sessionStorage.getItem(`reading${i}_timestamp`);
                    let accuracy = sessionStorage.getItem(`reading${i}_accuracy`);
                    let altitude = sessionStorage.getItem(`reading${i}_altitude`);
                    let speed = sessionStorage.getItem(`reading${i}_speed`);

                    if (lat && lng && timestamp) {
                        allReadings.push({
                            latitude: parseFloat(lat),
                            longitude: parseFloat(lng),
                            timestamp: parseInt(timestamp),
                            accuracy: parseFloat(accuracy) || null,
                            altitude: altitude ? parseFloat(altitude) : null,
                            speed: speed ? parseFloat(speed) : null
                        });
                    }
                }

                // Add reading 4 (button click)
                allReadings.push({
                    latitude: reading4Lat,
                    longitude: reading4Lng,
                    timestamp: reading4Timestamp,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed
                });

                let postData = {
                    _token: '{{ csrf_token() }}',
                    latitude: reading4Lat,
                    longitude: reading4Lng,
                    lokasi: lokasi,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed,
                    device_info: navigator.userAgent,
                    location_readings: JSON.stringify(allReadings)
                };

                // Include face verification result if available
                if (window.lastFaceVerificationResult) {
                    const fv = window.lastFaceVerificationResult;
                    postData.face_id_used = fv.face_id;
                    postData.face_similarity_score = fv.similarity_score;
                    postData.liveness_score = fv.liveness_score;
                    postData.liveness_challenges = JSON.stringify(fv.liveness_challenges);
                    postData.face_verified = fv.face_verified ? 1 : 0;
                }

                // Update UI with final location data
                $('#latitude').val(reading4Lat.toFixed(6));
                $('#longitude').val(reading4Lng.toFixed(6));

                // Update user location map with final position
                updateUserLocationMap(reading4Lat, reading4Lng);

                // Get address
                getAddressFromCoordinates(reading4Lat, reading4Lng);

                $('#location-info').html(`
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-2"></i>
                            <div>
                                <strong class="small">Lokasi berhasil didapatkan!</strong>
                            </div>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: '{{ route("mobile.presensi.store") }}',
                    method: 'POST',
                    data: postData,
                    timeout: 30000,
                    success: function(resp) {
                        if (resp && resp.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: resp.message || 'Presensi berhasil dicatat',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: resp.message || 'Gagal melakukan presensi. Coba lagi.',
                            });
                            $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-1"></i>Presensi');
                        }
                    },
                    error: function(xhr, status, err) {
                        let message = 'Gagal menghubungi server.';
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: message
                        });
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-1"></i>Presensi');
                    }
                });

            },
            function(err){
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan GPS',
                    text: err.message || 'Tidak dapat mengambil lokasi terakhir.'
                });
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-1"></i>Presensi');
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 });
    });
});

// Initialize map for kepala madrasah monitoring
@if(Auth::user()->ketugasan === 'kepala madrasah/sekolah' && !empty($mapData))
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('presensi-map').setView([-6.2088, 106.8456], 13); // Default Jakarta

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Custom icons
    const presensiIcon = L.divIcon({
        html: '<div style="background: #0e8549; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.3);"></div>',
        className: 'custom-marker',
        iconSize: [12, 12],
        iconAnchor: [6, 6]
    });

    const belumPresensiIcon = L.divIcon({
        html: '<div style="background: #dc3545; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.3);"></div>',
        className: 'custom-marker',
        iconSize: [12, 12],
        iconAnchor: [6, 6]
    });

    // Add markers
    const mapData = @json($mapData);
    let bounds = [];

    mapData.forEach(function(user) {
        const lat = parseFloat(user.latitude);
        const lng = parseFloat(user.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            bounds.push([lat, lng]);

            const icon = user.marker_type === 'presensi' ? presensiIcon : belumPresensiIcon;

            const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

            // Create popup content
            let popupContent = `
                <div style="font-family: 'Poppins', sans-serif; font-size: 12px; max-width: 200px;">
                    <strong>${user.name}</strong><br>
                    <small style="color: #666;">${user.status_kepegawaian}</small><br>
                    <small><strong>Status:</strong> ${user.marker_type === 'presensi' ? 'Sudah Presensi' : 'Belum Presensi'}</small><br>
            `;

            if (user.marker_type === 'presensi') {
                popupContent += `
                    <small><strong>Masuk:</strong> ${user.waktu_masuk || '-'}</small><br>
                    <small><strong>Keluar:</strong> ${user.waktu_keluar || '-'}</small><br>
                `;
            }

            popupContent += `
                    <small><strong>Lokasi:</strong> ${user.lokasi}</small>
                </div>
            `;

            marker.bindPopup(popupContent);
        }
    });

    // Fit map to show all markers
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [20, 20] });
    }

    // Set minimum zoom level
    map.setMinZoom(10);
    map.setMaxZoom(18);
});
@endif
</script>
@endsection
