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
    @elseif($presensiHariIni)
    @if($presensiHariIni->status === 'izin')
    <div class="status-card warning">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-calendar-minus"></i>
            </div>
            <div>
                <h6 class="mb-1">Izin Sudah Diajukan</h6>
                <p class="mb-0">{{ $presensiHariIni->keterangan }}</p>
                @if($presensiHariIni->status_izin === 'pending')
                <div class="alert-custom warning" style="margin-top: 6px; padding: 4px;">
                    <small><i class="bx bx-time me-1"></i> Menunggu approval</small>
                </div>
                @elseif($presensiHariIni->status_izin === 'approved')
                <div class="alert-custom success" style="margin-top: 6px; padding: 4px;">
                    <small><i class="bx bx-check me-1"></i> Izin disetujui</small>
                </div>
                @elseif($presensiHariIni->status_izin === 'rejected')
                <div class="alert-custom danger" style="margin-top: 6px; padding: 4px;">
                    <small><i class="bx bx-x me-1"></i> Izin ditolak</small>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="status-card success">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div>
                <h6 class="mb-1">Presensi Sudah Dicatat</h6>
                <p class="mb-1">Masuk: <strong>{{ $presensiHariIni->waktu_masuk ? $presensiHariIni->waktu_masuk->format('H:i') : '-' }}</strong></p>
                @if($presensiHariIni->waktu_keluar)
                <p class="mb-0">Keluar: <strong>{{ $presensiHariIni->waktu_keluar ? $presensiHariIni->waktu_keluar->format('H:i') : '-' }}</strong></p>
                <div class="alert-custom success" style="margin-top: 6px; padding: 4px;">
                    <small><i class="bx bx-check me-1"></i> Presensi hari ini lengkap!</small>
                </div>
                @else
                <p class="mb-0 text-muted">Lakukan presensi keluar jika sudah selesai.</p>
                @endif
            </div>
        </div>
    </div>
    @endif
    @endif

    <!-- Presensi Form -->
    <div class="presensi-form">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }}"></i>
            </div>
            <h6 class="section-title mb-0">{{ $presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
        </div>

        <!-- Location Status -->
        <div class="form-section">
            <div id="location-info" class="location-info info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-1"></i>
                    <div>
                        <strong>Mendapatkan lokasi...</strong>
                        <br><small class="text-muted">Pastikan GPS aktif</small>
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
                disabled>
            <i class="bx bx-{{ $isHoliday ? 'calendar-x' : 'check-circle' }} me-1"></i>
            {{ $isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk') }}
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
                <p>{{ $timeRanges['masuk_start'] }} - {{ $timeRanges['masuk_end'] }}</p>
            </div>
            <div class="schedule-item pulang">
                <i class="bx bx-log-out-circle text-success"></i>
                <h6 class="text-success">Pulang</h6>
                <p>{{ $timeRanges['pulang_start'] }} - {{ $timeRanges['pulang_end'] }}</p>
            </div>
        </div>
        @if(auth()->user()->madrasah && auth()->user()->madrasah->hari_kbm == '6')
        <div class="alert-custom info" style="margin-top: 6px;">
            <small>
                <i class="bx bx-info-circle me-1"></i>
                <strong>Catatan:</strong> Sabtu pulang mulai 12:00, hari lain 13:00.
            </small>
        </div>
        @endif
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
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi;
    let readingsComplete = false;
    let currentReading = 1;
    let totalReadings = 3;
    let locationHistory = [];

    // Disable presensi button initially
    $('#btn-presensi').prop('disabled', true);

    // Get location when page loads (reading1)
    if (navigator.geolocation) {
        $('#location-info').html(`
            <div class="location-info info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-2"></i>
                    <div>
                        <strong class="small">Mendapatkan lokasi Anda...</strong>
                        <br><small class="text-muted">Proses ini akan selesai dalam beberapa detik</small>
                    </div>
                </div>
            </div>
        `);

        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            // Store reading1 in sessionStorage and history
            const reading1Data = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                timestamp: Date.now(),
                accuracy: position.coords.accuracy,
                altitude: position.coords.altitude,
                speed: position.coords.speed
            };

            sessionStorage.setItem('reading1_latitude', position.coords.latitude);
            sessionStorage.setItem('reading1_longitude', position.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());
            sessionStorage.setItem('reading1_accuracy', position.coords.accuracy);
            sessionStorage.setItem('reading1_altitude', position.coords.altitude);
            sessionStorage.setItem('reading1_speed', position.coords.speed);

            locationHistory.push(reading1Data);

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            // Get address
            getAddressFromCoordinates(latitude, longitude);

            $('#location-info').html(`
                <div class="location-info success">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle me-2"></i>
                        <div>
                            <strong class="small">Pembacaan 1/${totalReadings} selesai!</strong>
                            <br><small class="text-muted">Menunggu pembacaan berikutnya...</small>
                        </div>
                    </div>
                </div>
            `);

            // Start automatic readings sequence
            startNextReadingCountdown();

        }, function(error) {
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
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 30000
        });
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

    // Function to start countdown for next reading
    function startNextReadingCountdown() {
        currentReading++;
        if (currentReading > totalReadings) {
            // All readings complete
            readingsComplete = true;
            $('#btn-presensi').prop('disabled', false);
            $('#location-info').html(`
                <div class="location-info success">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle me-2"></i>
                        <div>
                            <strong class="small">Semua pembacaan lokasi selesai!</strong>
                            <br><small class="text-muted">Siap untuk presensi</small>
                        </div>
                    </div>
                </div>
            `);
            return;
        }

        let countdown = 5;
        const countdownInterval = setInterval(() => {
            $('#location-info').html(`
                <div class="location-info info">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-time me-2"></i>
                        <div>
                            <strong class="small">Pembacaan ${currentReading}/${totalReadings} dalam ${countdown} detik...</strong>
                            <br><small class="text-muted">Jangan pindah posisi</small>
                        </div>
                    </div>
                </div>
            `);
            countdown--;

            if (countdown < 0) {
                clearInterval(countdownInterval);
                getNextReading();
            }
        }, 1000);
    }

    // Function to get next reading automatically
    function getNextReading() {
        $('#location-info').html(`
            <div class="location-info info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-2"></i>
                    <div>
                        <strong class="small">Mendapatkan pembacaan ${currentReading}...</strong>
                        <br><small class="text-muted">Proses ini akan selesai dalam beberapa detik</small>
                    </div>
                </div>
            </div>
        `);

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const readingData = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    timestamp: Date.now(),
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed
                };

                // Store reading in sessionStorage
                sessionStorage.setItem(`reading${currentReading}_latitude`, position.coords.latitude);
                sessionStorage.setItem(`reading${currentReading}_longitude`, position.coords.longitude);
                sessionStorage.setItem(`reading${currentReading}_timestamp`, Date.now());
                sessionStorage.setItem(`reading${currentReading}_accuracy`, position.coords.accuracy);
                sessionStorage.setItem(`reading${currentReading}_altitude`, position.coords.altitude);
                sessionStorage.setItem(`reading${currentReading}_speed`, position.coords.speed);

                locationHistory.push(readingData);

                // Update UI with latest location data
                $('#latitude').val(position.coords.latitude.toFixed(6));
                $('#longitude').val(position.coords.longitude.toFixed(6));

                // Get address for latest reading
                getAddressFromCoordinates(position.coords.latitude, position.coords.longitude);

                $('#location-info').html(`
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-2"></i>
                            <div>
                                <strong class="small">Pembacaan ${currentReading}/${totalReadings} selesai!</strong>
                                <br><small class="text-muted">Menunggu pembacaan berikutnya...</small>
                            </div>
                        </div>
                    </div>
                `);

                // Continue to next reading
                startNextReadingCountdown();
            },
            function(error) {
                $('#location-info').html(`
                    <div class="location-info error">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error-circle me-2"></i>
                            <div>
                                <strong class="small">Gagal mendapatkan pembacaan ${currentReading}</strong>
                                <br><small class="text-muted">${error.message}</small>
                            </div>
                        </div>
                    </div>
                `);
                // Retry current reading after 2 seconds
                setTimeout(() => {
                    getNextReading();
                }, 2000);
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 10000
            }
        );
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
    $('#btn-presensi').click(function() {
        if (!readingsComplete) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Pembacaan lokasi belum selesai. Tunggu hingga semua pembacaan otomatis selesai.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Build location readings array from all stored readings
        let locationReadings = [];
        for (let i = 1; i <= totalReadings; i++) {
            let lat = sessionStorage.getItem(`reading${i}_latitude`);
            let lng = sessionStorage.getItem(`reading${i}_longitude`);
            let timestamp = sessionStorage.getItem(`reading${i}_timestamp`);

            if (lat && lng && timestamp) {
                locationReadings.push({
                    latitude: parseFloat(lat),
                    longitude: parseFloat(lng),
                    timestamp: parseInt(timestamp)
                });
            }
        }

        // Use the latest reading for main coordinates
        let latestReading = locationReadings[locationReadings.length - 1];

        // Get speed value and ensure it's a number or null
        let speedValue = sessionStorage.getItem(`reading${totalReadings}_speed`);
        speedValue = speedValue && speedValue !== 'null' && speedValue !== 'undefined' ? parseFloat(speedValue) : null;

        let postData = {
            _token: '{{ csrf_token() }}',
            latitude: latestReading.latitude,
            longitude: latestReading.longitude,
            lokasi: lokasi,
            accuracy: sessionStorage.getItem(`reading${totalReadings}_accuracy`) ? parseFloat(sessionStorage.getItem(`reading${totalReadings}_accuracy`)) : null,
            altitude: sessionStorage.getItem(`reading${totalReadings}_altitude`) ? parseFloat(sessionStorage.getItem(`reading${totalReadings}_altitude`)) : null,
            speed: speedValue,
            device_info: navigator.userAgent,
            location_readings: JSON.stringify(locationReadings)
        };

        $.ajax({
            url: '{{ route("mobile.presensi.store") }}',
            method: 'POST',
            data: postData,
            timeout: 30000, // 30 detik timeout
            success: function(response) {
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: response.message,
                        confirmButtonText: 'Oke'
                    });
                    $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Terjadi kesalahan tidak diketahui';

                if (status === 'timeout') {
                    errorMessage = 'Waktu koneksi habis. Silakan coba lagi.';
                } else if (xhr.status === 0) {
                    errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: errorMessage,
                    confirmButtonText: 'Oke'
                });
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
            }
        });
    });
});


</script>
@endsection
