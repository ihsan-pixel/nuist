@extends('layouts.master')

@section('title') Presensi Hari Ini @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi @endslot
    @slot('title') Presensi Hari Ini @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
<!-- leaflet Css -->
<link href="{{ asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
<!-- SweetAlert2 Css -->
<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.map-container {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Presensi Hari Ini - {{ \Carbon\Carbon::now()->format('d F Y') }}</h4>
            </div>
            <div class="card-body">

                @if($isHoliday)
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">
                        <i class="bx bx-calendar-x me-2"></i>
                        Hari Libur: {{ $holiday->name }}
                    </h4>
                    <p>{{ $holiday->description ?? 'Hari ini adalah hari libur nasional atau tanggal merah.' }}</p>
                    <p class="mb-0">Presensi tidak diperlukan pada hari libur.</p>
                </div>
                @endif

                @if($presensiHariIni)
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Presensi Masuk Sudah Dicatat!</h4>
                    <p>Waktu Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i:s') }}</strong></p>
                    <p>Lokasi: <strong>{{ $presensiHariIni->lokasi ?? 'Tidak tercatat' }}</strong></p>
                    @if($presensiHariIni->waktu_keluar)
                    <p>Waktu Keluar: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i:s') }}</strong></p>
                    <div class="alert alert-info mt-3">
                        <strong>Presensi hari ini sudah lengkap!</strong>
                    </div>
                    @else
                    <hr>
                    <p class="mb-0">Silakan lakukan presensi keluar jika sudah selesai bekerja.</p>
                    @endif
                </div>
                @endif

                <!-- Elegant Mobile-First Layout -->
                <div class="row g-4">
                    <!-- Main Presensi Card - Full width on mobile -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-gradient-primary text-white border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-white bg-opacity-25 rounded-circle">
                                            <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }} fs-4"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">
                                            {{ $presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk' }}
                                        </h5>
                                        <small class="opacity-75">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <!-- Location Status -->
                                <div id="location-info" class="mb-4">
                                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-center">
                                        <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                                        <div>
                                            <strong>Mendapatkan lokasi Anda...</strong>
                                            <br><small class="text-muted">Pastikan GPS aktif dan izinkan akses lokasi</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Container -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bx bx-map text-primary me-2"></i>
                                        <label class="form-label fw-semibold mb-0">Lokasi Anda Saat Ini</label>
                                    </div>
                                    <div class="map-container rounded-3 overflow-hidden shadow-sm">
                                        <div id="map" style="height: 280px; width: 100%;"></div>
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
                    </div>

                    <!-- Information Cards - Stacked on mobile -->
                    <div class="col-12">
                        <div class="row g-3">
                            <!-- User Info Card -->
                            <div class="col-12">
                                <div class="card shadow-sm border-0 bg-light">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-user-circle text-primary me-2 fs-4"></i>
                                            <h6 class="card-title mb-0 fw-semibold">Informasi Personal</h6>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Nama</span>
                                                    <span class="fw-medium">{{ auth()->user()->name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Madrasah</span>
                                                    <span class="fw-medium">{{ auth()->user()->madrasah?->name ?? 'Tidak ada data' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Status Kepegawaian</span>
                                                    <span class="fw-medium">{{ auth()->user()->statusKepegawaian?->name ?? 'Belum diatur' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Time Info Card -->
                            <div class="col-12">
                                <div class="card shadow-sm border-0 bg-light">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-time text-success me-2 fs-4"></i>
                                            <h6 class="card-title mb-0 fw-semibold">Informasi Waktu</h6>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Tanggal</span>
                                                    <span class="fw-medium">{{ \Carbon\Carbon::now()->format('d F Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Waktu Sekarang</span>
                                                    <span class="fw-medium" id="current-time">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Info Card -->
                            @if(isset($timeRanges) && $timeRanges)
                            <div class="col-12">
                                <div class="card shadow-sm border-0">
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
                            </div>
                            @else
                            <div class="col-12">
                                <div class="alert alert-warning border-0 rounded-3">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Pengaturan Presensi:</strong> Hari KBM madrasah Anda belum diatur. Silakan hubungi administrator untuk mengaturnya.
                                </div>
                            </div>
                            @endif

                            <!-- Important Notice -->
                            <div class="col-12">
                                <div class="alert alert-danger border-0 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                    <div class="d-flex">
                                        <i class="bx bx-error-circle text-danger me-3 fs-4"></i>
                                        <div>
                                            <strong class="text-danger">Penting!</strong>
                                            <p class="mb-0 text-muted small">Pastikan Anda berada dalam lingkungan Madrasah/Sekolah untuk melakukan presensi.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for warning -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="warningModalLabel">Peringatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="warningMessage">
        <!-- Warning message will be injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('build/libs/leaflet/leaflet.js') }}"></script>
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(document).ready(function() {
    let latitude, longitude, lokasi;

    // Update waktu sekarang setiap detik
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        $('#current-time').text(timeString);
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Mendapatkan lokasi pengguna
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            // Mendapatkan alamat dari koordinat
            getAddressFromCoordinates(latitude, longitude);

            $('#location-info').html(`
                <div class="alert alert-success">
                    <i class="bx bx-check-circle me-2"></i>
                    Lokasi berhasil didapatkan!
                </div>
            `);

            // Initialize Leaflet map
            var map = L.map('map').setView([latitude, longitude], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([latitude, longitude]).addTo(map)
                .bindPopup('Lokasi Anda saat ini')
                .openPopup();

        }, function(error) {
            $('#location-info').html(`
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle me-2"></i>
                    Gagal mendapatkan lokasi: ${error.message}
                </div>
            `);
        });
    } else {
        $('#location-info').html(`
            <div class="alert alert-danger">
                <i class="bx bx-error-circle me-2"></i>
                Browser Anda tidak mendukung geolocation.
            </div>
        `);
    }

    // Fungsi untuk mendapatkan alamat dari koordinat
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

    // Handle tombol presensi
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

        // Disable button and show loading
        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memverifikasi lokasi...');

        // Get single location reading for faster processing
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Prepare data for AJAX
                let postData = {
                    _token: '{{ csrf_token() }}',
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    lokasi: lokasi,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed,
                    device_info: navigator.userAgent,
                    location_readings: JSON.stringify([{
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                        altitude: position.coords.altitude,
                        speed: position.coords.speed,
                        timestamp: Date.now()
                    }]) // Send single reading for server-side analysis
                };

                $('#btn-presensi').html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

                $.ajax({
                    url: '{{ route("presensi.store") }}',
                    method: 'POST',
                    data: postData,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: response.message,
                                confirmButtonText: 'Oke'
                            });
                            // Re-enable button and reset text
                            $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> {{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan tidak diketahui',
                            confirmButtonText: 'Oke'
                        });
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> {{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
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
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> {{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000, // 5 second timeout
                maximumAge: 0
            }
        );
    });


});
</script>
@endsection

