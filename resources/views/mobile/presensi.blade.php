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
            </div>
        </div>
        <div class="alert-custom info" style="margin-top: 6px;">
            <small>
                <i class="bx bx-info-circle me-1"></i>
                <strong>Catatan:</strong> Pulang dapat dilakukan mulai pukul 15:00 hingga 23:55.
            </small>
        </div>
    </div>
    @else
    <div class="alert-custom warning">
        <i class="bx bx-info-circle me-1"></i>
        <strong>Pengaturan Presensi:</strong> Hari KBM belum diatur. Hubungi admin.
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
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi;
    let locationReadings = [];
    let readingCount = 0;
    const totalReadings = 1; // Reduced to 1 reading for faster loading
    const readingInterval = 1000; // Reduced to 1 second

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
                    timeout: 5000, // Reduced timeout for faster response
                    maximumAge: 10000 // Allow cached location for faster loading
                }
            );
        });
    }

    // Start collecting multiple readings
    async function startLocationCollection() {
        try {
            for (let i = 1; i <= totalReadings; i++) {
                await collectLocationReading(i);

                // Wait between readings (except for the last one)
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

    // Handle presensi button
    let isProcessing = false;
    $('#btn-presensi').click(function() {
        if (isProcessing) {
            return; // Prevent double clicks
        }

        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Data lokasi belum lengkap. Pastikan GPS aktif dan tunggu proses pengumpulan data selesai.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        isProcessing = true;
        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Get final location reading (button click) as reading4
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let reading4Lat = position.coords.latitude;
                let reading4Lng = position.coords.longitude;
                let reading4Timestamp = Date.now();

                // Build location readings array from all stored readings
                let allReadings = [];

                // Add readings 1-1 from sessionStorage
                for (let i = 1; i <= 1; i++) {
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
                    timeout: 30000, // 30 detik timeout
                    success: function(response) {
                        isProcessing = false;
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'Oke',
                                timer: 3000,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            // Map common backend messages to clearer explanations
                            let title = 'Peringatan';
                            let text = response.message || 'Presensi gagal.';

                            const msg = (response.message || '').toLowerCase();
                            if (msg.includes('luar area') || msg.includes('di luar area')) {
                                title = 'Diluar Area Sekolah';
                                text = 'Lokasi Anda berada di luar area presensi madrasah. Pastikan GPS aktif dan Anda berada di lingkungan sekolah.';
                            } else if (msg.includes('waktu presensi masuk telah berakhir') || msg.includes('waktu presensi masuk')) {
                                title = 'Waktu Masuk Habis';
                                text = 'Batas waktu untuk presensi masuk telah lewat. Jika Anda terlambat, silakan ajukan izin atau hubungi admin.';
                            } else if (msg.includes('belum waktunya presensi masuk')) {
                                title = 'Belum Waktunya Masuk';
                                text = 'Masih belum waktunya untuk melakukan presensi masuk. Silakan coba kembali pada jam yang ditentukan.';
                            } else if (msg.includes('belum waktunya presensi pulang') || msg.includes('presensi keluar harus')) {
                                title = 'Belum Waktunya Pulang';
                                text = 'Belum waktunya melakukan presensi pulang. Tunggu hingga jam pulang yang ditentukan.';
                            }

                            Swal.fire({
                                icon: 'warning',
                                title: title,
                                text: text,
                                confirmButtonText: 'Oke'
                            });
                            $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ ($presensiHariIni && $presensiHariIni->count() > 0) ? "Presensi Keluar" : "Presensi Masuk" }}');
                        }
                    },
                    error: function(xhr, status, error) {
                        isProcessing = false;
                        let errorMessage = 'Terjadi kesalahan tidak diketahui';
                        let title = 'Kesalahan';

                        if (status === 'timeout') {
                            errorMessage = 'Waktu koneksi habis. Silakan coba lagi.';
                        } else if (xhr.status === 0) {
                            errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        const msg = (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : errorMessage).toLowerCase();
                        if (msg.includes('luar area') || msg.includes('di luar area')) {
                            title = 'Diluar Area Sekolah';
                            errorMessage = 'Lokasi Anda berada di luar area presensi madrasah. Periksa lokasi dan coba lagi.';
                        } else if (msg.includes('waktu presensi masuk telah berakhir') || msg.includes('waktu presensi masuk')) {
                            title = 'Waktu Masuk Habis';
                            errorMessage = 'Batas waktu presensi masuk telah lewat.';
                        } else if (msg.includes('belum waktunya presensi masuk')) {
                            title = 'Belum Waktunya Masuk';
                            errorMessage = 'Masih belum waktunya untuk presensi masuk.';
                        } else if (msg.includes('belum waktunya presensi pulang') || msg.includes('presensi keluar harus')) {
                            title = 'Belum Waktunya Pulang';
                            errorMessage = 'Belum waktunya melakukan presensi pulang.';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: title,
                            text: errorMessage,
                            confirmButtonText: 'Oke'
                        });
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ ($presensiHariIni && $presensiHariIni->count() > 0) ? "Presensi Keluar" : "Presensi Masuk" }}');
                    }
            },
            function(error) {
                isProcessing = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Gagal mendapatkan lokasi: ' + error.message,
                    confirmButtonText: 'Oke'
                });
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ ($presensiHariIni && $presensiHariIni->count() > 0) ? "Presensi Keluar" : "Presensi Masuk" }}');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 10000
            }
        );
    });
});

// Initialize user location map
let userLocationMap;
let userLocationMarker;

function initializeUserLocationMap(lat, lng) {
    if (userLocationMap) {
        userLocationMap.remove();
    }

    userLocationMap = L.map('user-location-map').setView([lat, lng], 18);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(userLocationMap);

    // Add user location marker with custom icon (animated pulsing effect)
    const userIcon = L.divIcon({
        html: '<div style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 8px rgba(0,0,0,0.4); position: relative; animation: pulse 2s infinite;"><div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 6px solid transparent; border-right: 6px solid transparent; border-bottom: 8px solid #004b4c;"></div></div><style>@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }</style>',
        className: 'user-location-marker',
        iconSize: [20, 20],
        iconAnchor: [10, 20]
    });

    userLocationMarker = L.marker([lat, lng], { icon: userIcon }).addTo(userLocationMap);

    // Add accuracy circle if available
    if (locationReadings.length > 0) {
        const latestReading = locationReadings[locationReadings.length - 1];
        if (latestReading.accuracy) {
            L.circle([lat, lng], {
                color: 'rgba(14, 133, 73, 0.4)',
                fillColor: 'rgba(14, 133, 73, 0.15)',
                fillOpacity: 0.4,
                radius: latestReading.accuracy,
                weight: 2
            }).addTo(userLocationMap);
        }
    }

    // Add popup with location info
    const popupContent = `
        <div style="font-family: 'Poppins', sans-serif; font-size: 12px; text-align: center;">
            <strong style="color: #004b4c;">📍 Lokasi Anda</strong><br>
            <small style="color: #666;">${lat.toFixed(6)}, ${lng.toFixed(6)}</small><br>
            <small style="color: #666;">Akurasi: ${locationReadings.length > 0 ? Math.round(locationReadings[locationReadings.length - 1].accuracy) + 'm' : 'N/A'}</small>
        </div>
    `;
    userLocationMarker.bindPopup(popupContent).openPopup();

    // Set zoom limits
    userLocationMap.setMinZoom(15);
    userLocationMap.setMaxZoom(20);

    // Disable scroll wheel zoom to prevent accidental zooming
    userLocationMap.scrollWheelZoom.disable();
}

// Update user location map when coordinates are collected
function updateUserLocationMap(lat, lng) {
    // Hide placeholder and show map
    $('#map-placeholder').fadeOut(300, function() {
        if (userLocationMap && userLocationMarker) {
            userLocationMap.setView([lat, lng], 18);
            userLocationMarker.setLatLng([lat, lng]);
        } else {
            initializeUserLocationMap(lat, lng);
        }
    });
}

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
