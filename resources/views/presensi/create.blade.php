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

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bx bx-log-in-circle me-2"></i>
                                    {{ $presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk' }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="location-info" class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Mendapatkan lokasi Anda...
                                    </div>
                                </div>

                                <!-- Map Section -->
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Anda Saat Ini</label>
                                    <div id="map" style="height: 300px; border-radius: 5px;"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Koordinat Lokasi</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" id="latitude" class="form-control" placeholder="Latitude" readonly>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" id="longitude" class="form-control" placeholder="Longitude" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat Lokasi</label>
                                    <input type="text" id="lokasi" class="form-control" placeholder="Alamat akan muncul otomatis" readonly>
                                </div>

                                <button type="button" id="btn-presensi" class="btn btn-primary btn-lg w-100" {{ ($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : '' }}>
                                    <i class="bx bx-check-circle me-2"></i>
                                    {{ $isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Presensi</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Madrasah</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->madrasah?->name ?? 'Tidak ada data' }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status Kepegawaian</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->statusKepegawaian?->name ?? 'Belum diatur' }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->format('d F Y') }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Waktu Sekarang</label>
                                    <input type="text" id="current-time" class="form-control" readonly>
                                </div>

                                @if(isset($timeRanges) && $timeRanges)
                                <div class="mb-3">
                                    <label class="form-label">Jadwal Presensi Berdasarkan Hari KBM Madrasah</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary"><i class="bx bx-log-in-circle me-2"></i>Presensi Masuk</h6>
                                                    <p class="mb-1"><strong>Mulai:</strong> {{ $timeRanges['masuk_start'] }}</p>
                                                    <p class="mb-0"><strong>Akhir:</strong> {{ $timeRanges['masuk_end'] }} (Terlambat setelah ini)</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-success"><i class="bx bx-log-out-circle me-2"></i>Presensi Pulang</h6>
                                                    <p class="mb-1"><strong>Mulai:</strong> {{ $timeRanges['pulang_start'] }}</p>
                                                    <p class="mb-0"><strong>Akhir:</strong> {{ $timeRanges['pulang_end'] }}</p>
                                                </div>
                                            </div>
                                            @if(auth()->user()->madrasah && auth()->user()->madrasah->hari_kbm == '6')
                                            <div class="mt-2">
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
                                <div class="alert alert-warning mb-3">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Pengaturan Presensi:</strong> Hari KBM madrasah Anda belum diatur. Silakan hubungi administrator untuk mengaturnya.
                                </div>
                                @endif

                                <div class="alert alert-warning">
                                    <i class="bx bx-error-circle me-2"></i>
                                    <strong>Penting!</strong><br>
                                    Pastikan Anda berada dalam Lingkungan Madrasah/Sekolah untuk melakukan presensi.
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
        $('#current-time').val(now.toLocaleTimeString('id-ID'));
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

        // Prepare data for AJAX
        let postData = {
            _token: '{{ csrf_token() }}',
            latitude: latitude,
            longitude: longitude,
            lokasi: lokasi
        };

        // Disable button and show loading
        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

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
    });
});
</script>
@endsection

