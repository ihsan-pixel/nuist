@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

@section('content')
<!-- Status Card -->
@if($isHoliday)
<div class="alert alert-warning border-0 rounded-3 mb-3">
    <div class="d-flex align-items-center">
        <i class="bx bx-calendar-x fs-5 me-2"></i>
        <div>
            <h6 class="mb-1 fs-6">Hari Libur</h6>
            <p class="mb-0 small">{{ $holiday->name ?? 'Hari ini adalah hari libur' }}</p>
        </div>
    </div>
</div>
@elseif($presensiHariIni)
<div class="card mb-3 shadow-sm border-success">
    <div class="card-body text-center py-3">
        <div class="avatar-sm mx-auto mb-2">
            <div class="avatar-title bg-success text-white rounded-circle">
                <i class="bx bx-check-circle fs-4"></i>
            </div>
        </div>
        <h6 class="text-success mb-2 fs-6">Presensi Sudah Dicatat</h6>
        <p class="mb-2 small">Waktu Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong></p>
        @if($presensiHariIni->waktu_keluar)
        <p class="mb-0 small">Waktu Keluar: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i') }}</strong></p>
        <div class="alert alert-success mt-2 small">
            <i class="bx bx-check me-1"></i> Presensi hari ini sudah lengkap!
        </div>
        @else
        <p class="text-muted small">Silakan lakukan presensi keluar jika sudah selesai bekerja.</p>
        @endif
    </div>
</div>
@endif

<!-- Presensi Form -->
<div class="card shadow-sm mb-3">
    <div class="card-header bg-gradient-primary text-white py-2">
        <div class="d-flex align-items-center">
            <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }} me-2 fs-6"></i>
            <h6 class="mb-0 fs-6">{{ $presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
        </div>
    </div>
    <div class="card-body p-3">
        <!-- Location Status -->
        <div id="location-info" class="mb-3">
            <div class="alert alert-info border-0 rounded-3 p-2">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-2 fs-5"></i>
                    <div>
                        <strong class="small">Mendapatkan lokasi...</strong>
                        <br><small class="text-muted">Pastikan GPS aktif</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordinates -->
        <div class="mb-3">
            <div class="d-flex align-items-center mb-2">
                <i class="bx bx-target-lock text-success me-2 fs-6"></i>
                <label class="form-label fw-semibold mb-0 small">Koordinat Lokasi</label>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 p-1">
                            <i class="bx bx-up text-muted fs-6"></i>
                        </span>
                        <input type="text" id="latitude" class="form-control border-start-0 ps-0 small"
                               placeholder="Latitude" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 p-1">
                            <i class="bx bx-right text-muted fs-6"></i>
                        </span>
                        <input type="text" id="longitude" class="form-control border-start-0 ps-0 small"
                               placeholder="Longitude" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="mb-3">
            <div class="d-flex align-items-center mb-2">
                <i class="bx bx-home text-info me-2 fs-6"></i>
                <label class="form-label fw-semibold mb-0 small">Alamat Lokasi</label>
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light p-1">
                    <i class="bx bx-map-pin text-muted fs-6"></i>
                </span>
                <input type="text" id="lokasi" class="form-control small"
                       placeholder="Alamat akan muncul otomatis" readonly>
            </div>
        </div>

        <!-- Presensi Button -->
        <div class="d-grid">
            <button type="button" id="btn-presensi"
                    class="btn btn-{{ $isHoliday ? 'secondary' : 'primary' }} py-2 rounded-3 shadow-sm fw-semibold small"
                    {{ ($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : '' }}>
                <i class="bx bx-{{ $isHoliday ? 'calendar-x' : 'check-circle' }} me-2 fs-6"></i>
                {{ $isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk') }}
            </button>
        </div>
    </div>
</div>

        <!-- Time Information -->
@if(isset($timeRanges) && $timeRanges)
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <div class="d-flex align-items-center mb-2">
            <i class="bx bx-calendar-check text-warning me-2 fs-5"></i>
            <h6 class="card-title mb-0 fw-semibold small">Jadwal Presensi</h6>
        </div>
        <div class="row g-2">
            <div class="col-12 col-md-6">
                <div class="schedule-card bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-2 text-center">
                    <i class="bx bx-log-in-circle text-primary fs-4 mb-1"></i>
                    <h6 class="text-primary mb-1 small">Presensi Masuk</h6>
                    <p class="mb-1 fw-semibold small">{{ $timeRanges['masuk_start'] }} - {{ $timeRanges['masuk_end'] }}</p>
                    <small class="text-muted">Terlambat setelah 07:00</small>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="schedule-card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-2 text-center">
                    <i class="bx bx-log-out-circle text-success fs-4 mb-1"></i>
                    <h6 class="text-success mb-1 small">Presensi Pulang</h6>
                    <p class="mb-1 fw-semibold small">{{ $timeRanges['pulang_start'] }} - {{ $timeRanges['pulang_end'] }}</p>
                    <small class="text-muted">Waktu pulang normal</small>
                </div>
            </div>
        </div>
        @if(auth()->user()->madrasah && auth()->user()->madrasah->hari_kbm == '6')
        <div class="mt-2 p-2 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2">
            <small class="text-info">
                <i class="bx bx-info-circle me-1"></i>
                <strong>Catatan:</strong> Untuk hari Sabtu, waktu mulai presensi pulang adalah 12:00. Hari lainnya mulai pukul 13:00.
            </small>
        </div>
        @endif
    </div>
</div>
@else
<div class="alert alert-warning border-0 rounded-3 mb-3">
    <i class="bx bx-info-circle me-2 fs-6"></i>
    <strong class="small">Pengaturan Presensi:</strong> Hari KBM madrasah Anda belum diatur. Silakan hubungi administrator untuk mengaturnya.
</div>
@endif

<!-- Important Notice -->
<div class="alert alert-danger border-0 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 mb-3">
    <div class="d-flex">
        <i class="bx bx-error-circle text-danger me-2 fs-5"></i>
        <div>
            <strong class="text-danger small">Penting!</strong>
            <p class="mb-0 text-muted small">Pastikan Anda berada dalam lingkungan Madrasah/Sekolah untuk melakukan presensi.</p>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
/* Mobile-specific styles for presensi page */
body {
    font-size: 13px;
    line-height: 1.3;
}

.card {
    border-radius: 10px;
    margin-bottom: 12px;
}

.card-header {
    padding: 8px 12px;
    font-size: 14px;
}

.card-body {
    padding: 12px;
}

.alert {
    padding: 8px 12px;
    margin-bottom: 12px;
    font-size: 13px;
}

.btn {
    font-size: 14px;
    padding: 8px 12px;
}

.form-control {
    font-size: 13px;
    padding: 8px 12px;
}

.input-group-text {
    padding: 8px 10px;
}

.schedule-card {
    margin-bottom: 8px;
}

.schedule-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Ensure proper spacing for mobile */
.mb-3 {
    margin-bottom: 12px !important;
}

.mb-2 {
    margin-bottom: 8px !important;
}

.p-2 {
    padding: 8px !important;
}

.p-3 {
    padding: 12px !important;
}
</style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi;

    // Get location when page loads (reading1)
    if (navigator.geolocation) {
            $('#location-info').html(`
                <div class="alert alert-info border-0 rounded-3 p-2">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-loader-alt bx-spin me-2 fs-5"></i>
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

            // Store reading1 in sessionStorage (menu entry reading)
            sessionStorage.setItem('reading1_latitude', position.coords.latitude);
            sessionStorage.setItem('reading1_longitude', position.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            // Get address
            getAddressFromCoordinates(latitude, longitude);

            $('#location-info').html(`
                <div class="alert alert-success border-0 rounded-3 p-2">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle me-2 fs-5"></i>
                        <div>
                            <strong class="small">Lokasi berhasil didapatkan!</strong>
                        </div>
                    </div>
                </div>
            `);



        }, function(error) {
            $('#location-info').html(`
                <div class="alert alert-danger border-0 rounded-3 p-2">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle me-2 fs-5"></i>
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
            <div class="alert alert-danger border-0 rounded-3 p-2">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-2 fs-5"></i>
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
                    <div class="alert alert-success border-0 rounded-3 p-2">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-2 fs-5"></i>
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
