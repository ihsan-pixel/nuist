@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

@section('content')
<!-- Status Card -->
@if($isHoliday)
<div class="alert alert-warning border-0 rounded-3 mb-4">
    <div class="d-flex align-items-center">
        <i class="bx bx-calendar-x fs-3 me-3"></i>
        <div>
            <h6 class="mb-1">Hari Libur</h6>
            <p class="mb-0 small">{{ $holiday->name ?? 'Hari ini adalah hari libur' }}</p>
        </div>
    </div>
</div>
@elseif($presensiHariIni)
<div class="card mb-4 shadow-sm border-success">
    <div class="card-body text-center py-4">
        <div class="avatar-lg mx-auto mb-3">
            <div class="avatar-title bg-success text-white rounded-circle">
                <i class="bx bx-check-circle fs-1"></i>
            </div>
        </div>
        <h5 class="text-success mb-2">Presensi Sudah Dicatat</h5>
        <p class="mb-2">Waktu Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong></p>
        @if($presensiHariIni->waktu_keluar)
        <p class="mb-0">Waktu Keluar: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i') }}</strong></p>
        <div class="alert alert-success mt-3">
            <i class="bx bx-check me-1"></i> Presensi hari ini sudah lengkap!
        </div>
        @else
        <p class="text-muted small">Silakan lakukan presensi keluar jika sudah selesai bekerja.</p>
        @endif
    </div>
</div>
@endif

<!-- Presensi Form -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-gradient-primary text-white">
        <div class="d-flex align-items-center">
            <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }} me-2"></i>
            <h6 class="mb-0">{{ $presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
        </div>
    </div>
    <div class="card-body">
        <!-- Location Status -->
        <div id="location-info" class="mb-4">
            <div class="alert alert-info border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                    <div>
                        <strong>Mendapatkan lokasi...</strong>
                        <br><small class="text-muted">Pastikan GPS aktif</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bx bx-map text-primary me-2"></i>
                <label class="form-label fw-semibold mb-0">Lokasi Anda Saat Ini</label>
            </div>
            <div class="map-container-mobile rounded-3 overflow-hidden shadow-sm position-relative">
                <div id="map" class="mobile-map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                <!-- Loading overlay -->
                <div id="map-loading" class="position-absolute top-0 start-0 w-100 h-100 bg-white d-flex align-items-center justify-content-center" style="z-index: 1000; border-radius: 8px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-muted small">Memuat peta...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordinates -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bx bx-target-lock text-success me-2"></i>
                <label class="form-label fw-semibold mb-0">Koordinat Lokasi</label>
            </div>
            <div class="row g-3">
                <div class="col-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bx bx-up text-muted"></i>
                        </span>
                        <input type="text" id="latitude" class="form-control form-control-lg border-start-0 ps-0"
                               placeholder="Latitude" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bx bx-right text-muted"></i>
                        </span>
                        <input type="text" id="longitude" class="form-control form-control-lg border-start-0 ps-0"
                               placeholder="Longitude" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bx bx-home text-info me-2"></i>
                <label class="form-label fw-semibold mb-0">Alamat Lokasi</label>
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light">
                    <i class="bx bx-map-pin text-muted"></i>
                </span>
                <input type="text" id="lokasi" class="form-control form-control-lg"
                       placeholder="Alamat akan muncul otomatis" readonly>
            </div>
        </div>

        <!-- Presensi Button -->
        <div class="d-grid">
            <button type="button" id="btn-presensi"
                    class="btn btn-{{ $isHoliday ? 'secondary' : 'primary' }} btn-lg py-3 rounded-3 shadow-sm fw-semibold"
                    {{ ($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : '' }}>
                <i class="bx bx-{{ $isHoliday ? 'calendar-x' : 'check-circle' }} me-2 fs-5"></i>
                {{ $isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk') }}
            </button>
        </div>
    </div>
</div>

        <!-- Time Information -->
@if(isset($timeRanges) && $timeRanges)
<div class="card shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-3">
            <i class="bx bx-calendar-check text-warning me-2 fs-4"></i>
            <h6 class="card-title mb-0 fw-semibold">Jadwal Presensi</h6>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="schedule-card bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-3 text-center">
                    <i class="bx bx-log-in-circle text-primary fs-3 mb-2"></i>
                    <h6 class="text-primary mb-2">Presensi Masuk</h6>
                    <p class="mb-1 fw-semibold">{{ $timeRanges['masuk_start'] }} - {{ $timeRanges['masuk_end'] }}</p>
                    <small class="text-muted">Terlambat setelah 07:00</small>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="schedule-card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3 text-center">
                    <i class="bx bx-log-out-circle text-success fs-3 mb-2"></i>
                    <h6 class="text-success mb-2">Presensi Pulang</h6>
                    <p class="mb-1 fw-semibold">{{ $timeRanges['pulang_start'] }} - {{ $timeRanges['pulang_end'] }}</p>
                    <small class="text-muted">Waktu pulang normal</small>
                </div>
            </div>
        </div>
        @if(auth()->user()->madrasah && auth()->user()->madrasah->hari_kbm == '6')
        <div class="mt-3 p-2 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2">
            <small class="text-info">
                <i class="bx bx-info-circle me-1"></i>
                <strong>Catatan:</strong> Untuk hari Sabtu, waktu mulai presensi pulang adalah 12:00. Hari lainnya mulai pukul 13:00.
            </small>
        </div>
        @endif
    </div>
</div>
@else
<div class="alert alert-warning border-0 rounded-3 mb-4">
    <i class="bx bx-info-circle me-2"></i>
    <strong>Pengaturan Presensi:</strong> Hari KBM madrasah Anda belum diatur. Silakan hubungi administrator untuk mengaturnya.
</div>
@endif

<!-- Important Notice -->
<div class="alert alert-danger border-0 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
    <div class="d-flex">
        <i class="bx bx-error-circle text-danger me-3 fs-4"></i>
        <div>
            <strong class="text-danger">Penting!</strong>
            <p class="mb-0 text-muted small">Pastikan Anda berada dalam lingkungan Madrasah/Sekolah untuk melakukan presensi.</p>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
/* Mobile Map Styles */
.map-container-mobile {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: relative;
    background: #f8f9fa;
    border-radius: 8px;
}

.mobile-map {
    border-radius: 8px;
    background: #f8f9fa;
}

/* Leaflet Controls for Mobile */
.map-container-mobile .leaflet-control-container {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.map-container-mobile .leaflet-control-zoom {
    border: none !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
    border-radius: 8px !important;
    overflow: hidden;
}

.map-container-mobile .leaflet-control-zoom a {
    width: 40px !important;
    height: 40px !important;
    line-height: 38px !important;
    font-size: 20px !important;
    font-weight: bold !important;
    background-color: #007bff !important;
    color: white !important;
    border: none !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.map-container-mobile .leaflet-control-zoom a:hover {
    background-color: #0056b3 !important;
    transform: scale(1.05);
}

.map-container-mobile .leaflet-control-zoom a:first-child {
    border-bottom: 1px solid rgba(255,255,255,0.2) !important;
}

.map-container-mobile .leaflet-control-attribution {
    background-color: rgba(255, 255, 255, 0.9) !important;
    font-size: 9px !important;
    padding: 2px 6px !important;
    border-radius: 4px !important;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important;
    margin-bottom: 5px !important;
    margin-right: 5px !important;
}

/* Leaflet Popup Styles */
.map-container-mobile .leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.15);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
}

.map-container-mobile .leaflet-popup-tip {
    background-color: white;
}

/* Loading Overlay */
#map-loading {
    backdrop-filter: blur(2px);
    background: rgba(255, 255, 255, 0.95) !important;
}

/* Ensure map tiles load properly on mobile */
.map-container-mobile .leaflet-tile {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}

/* Mobile specific adjustments */
@media (max-width: 576px) {
    .mobile-map {
        height: 280px !important;
    }

    .map-container-mobile .leaflet-control-zoom a {
        width: 36px !important;
        height: 36px !important;
        line-height: 34px !important;
        font-size: 18px !important;
    }
}
.info-item {
    transition: all 0.2s ease;
}
.info-item:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
}
.schedule-card {
    transition: all 0.3s ease;
}
.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('build/libs/leaflet/leaflet.js') }}"></script>
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi, map;

    // Get location when page loads (reading1)
    if (navigator.geolocation) {
        $('#location-info').html(`
            <div class="alert alert-info border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                    <div>
                        <strong>Mendapatkan lokasi Anda...</strong>
                        <br><small class="text-muted">Proses ini akan selesai dalam beberapa detik</small>
                    </div>
                </div>
            </div>
        `);

        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            // Store reading1 in sessionStorage (menu entry reading)
            sessionStorage.setItem('reading1_latitude', position.coords.latitude);
            sessionStorage.setItem('reading1_longitude', position.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            // Get address
            getAddressFromCoordinates(latitude, longitude);

            $('#location-info').html(`
                <div class="alert alert-success border-0 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle me-3 fs-4"></i>
                        <div>
                            <strong>Lokasi berhasil didapatkan!</strong>
                        </div>
                    </div>
                </div>
            `);

            // Initialize Leaflet map untuk mobile
            map = L.map('map', {
                center: [latitude, longitude],
                zoom: 16,
                zoomControl: true,
                zoomControlOptions: {
                    position: 'topright'
                },
                scrollWheelZoom: true,
                doubleClickZoom: true,
                boxZoom: true,
                keyboard: true,
                fadeAnimation: true,
                zoomAnimation: true,
                markerZoomAnimation: true,
                preferCanvas: false
            });

            // Gunakan tile layer yang dioptimasi untuk mobile
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                minZoom: 3,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                updateWhenIdle: false,
                updateWhenZooming: false,
                keepBuffer: 2
            }).addTo(map);

            // Tambahkan marker dengan custom icon
            var userIcon = L.divIcon({
                className: 'mobile-user-marker',
                html: '<div style="background-color: #007bff; width: 20px; height: 20px; border-radius: 50% 50% 50% 0; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); transform: rotate(-45deg);"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 20]
            });

            var marker = L.marker([latitude, longitude], {icon: userIcon}).addTo(map)
                .bindPopup('<div class="text-center"><strong>Lokasi Anda</strong><br/>' + latitude.toFixed(6) + ', ' + longitude.toFixed(6) + '</div>')
                .openPopup();

            // Tambahkan circle untuk akurasi jika tersedia
            if (position.coords.accuracy && position.coords.accuracy < 100) {
                L.circle([latitude, longitude], {
                    color: '#007bff',
                    fillColor: '#007bff',
                    fillOpacity: 0.1,
                    radius: position.coords.accuracy,
                    weight: 1
                }).addTo(map);
            }

            // Sembunyikan loading overlay dan tampilkan map
            setTimeout(function() {
                $('#map-loading').fadeOut(300, function() {
                    map.invalidateSize();
                });
            }, 500);

        }, function(error) {
            $('#location-info').html(`
                <div class="alert alert-danger border-0 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle me-3 fs-4"></i>
                        <div>
                            <strong>Gagal mendapatkan lokasi</strong>
                            <br><small class="text-muted">${error.message}</small>
                        </div>
                    </div>
                </div>
            `);

            // Initialize map with default location on error
            map = L.map('map', {
                center: [-7.7956, 110.3695],
                zoom: 10,
                zoomControl: true,
                zoomControlOptions: {
                    position: 'topright'
                },
                scrollWheelZoom: true,
                doubleClickZoom: true,
                boxZoom: true,
                keyboard: true,
                fadeAnimation: true,
                zoomAnimation: true,
                markerZoomAnimation: true,
                preferCanvas: false
            });

            // Gunakan tile layer yang dioptimasi untuk mobile
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                minZoom: 3,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                updateWhenIdle: false,
                updateWhenZooming: false,
                keepBuffer: 2
            }).addTo(map);

            var marker = L.marker([-7.7956, 110.3695]).addTo(map)
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 30000
        });
    } else {
        $('#location-info').html(`
            <div class="alert alert-danger border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-3 fs-4"></i>
                    <div>
                        <strong>Browser tidak mendukung GPS</strong>
                        <br><small class="text-muted">Silakan gunakan browser modern dengan dukungan GPS</small>
                    </div>
                </div>
            </div>
        `);

        // Initialize map with default location
        map = L.map('map', {
            center: [-7.7956, 110.3695],
            zoom: 10,
            zoomControl: true,
            zoomControlOptions: {
                position: 'topright'
            },
            scrollWheelZoom: true,
            doubleClickZoom: true,
            boxZoom: true,
            keyboard: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
            updateWhenIdle: true
        }).addTo(map);

        var marker = L.marker([-7.7956, 110.3695]).addTo(map)
            .bindPopup('<div class="text-center"><strong>Browser Tidak Mendukung GPS</strong><br/>Gunakan browser modern</div>')
            .openPopup();

        setTimeout(function() {
            map.invalidateSize();
        }, 100);
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
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Lokasi belum didapatkan. Pastikan GPS aktif dan izinkan akses lokasi.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Get second location reading (button click)
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let reading2Lat = position.coords.latitude;
                let reading2Lng = position.coords.longitude;

                // Store reading 2 in sessionStorage
                sessionStorage.setItem('reading2_latitude', position.coords.latitude);
                sessionStorage.setItem('reading2_longitude', position.coords.longitude);
                sessionStorage.setItem('reading2_timestamp', Date.now());

                let reading1Lat = sessionStorage.getItem('reading1_latitude');
                let reading1Lng = sessionStorage.getItem('reading1_longitude');
                let reading1Timestamp = sessionStorage.getItem('reading1_timestamp');

                let reading2Timestamp = Date.now();

                let postData = {
                    _token: '{{ csrf_token() }}',
                    latitude: reading2Lat,
                    longitude: reading2Lng,
                    lokasi: lokasi,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed,
                    device_info: navigator.userAgent,
                    location_readings: JSON.stringify([
                        {
                            latitude: parseFloat(reading1Lat),
                            longitude: parseFloat(reading1Lng),
                            timestamp: parseInt(reading1Timestamp)
                        },
                        {
                            latitude: reading2Lat,
                            longitude: reading2Lng,
                            timestamp: reading2Timestamp
                        }
                    ])
                };

                // Update UI with location data
                $('#latitude').val(reading2Lat.toFixed(6));
                $('#longitude').val(reading2Lng.toFixed(6));

                // Get address
                getAddressFromCoordinates(reading2Lat, reading2Lng);

                $('#location-info').html(`
                    <div class="alert alert-success border-0 rounded-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-3 fs-4"></i>
                            <div>
                                <strong>Lokasi berhasil didapatkan!</strong>
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
            },
            function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Gagal mendapatkan lokasi: ' + error.message,
                    confirmButtonText: 'Oke'
                });
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 10000
            }
        );
    });
});


</script>
@endsection
