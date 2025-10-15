@extends('layouts.master')

@section('title', 'Presensi Mengajar')

@section('vendor-script')
<!-- SweetAlert2 -->
<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Leaflet CSS -->
<link href="{{ asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Presensi Mengajar @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-book me-2"></i>Presensi Mengajar Hari Ini
                </h4>
                <p class="card-title-desc mb-0">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="card-body">
                @if($schedules->isEmpty())
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bx bx-info-circle bx-lg me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Tidak ada jadwal mengajar hari ini</h5>
                            <p class="mb-0">Anda tidak memiliki jadwal mengajar yang terjadwal untuk hari ini.</p>
                        </div>
                    </div>
                @else
                    <div class="row">
                        @foreach($schedules as $schedule)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border {{ $schedule->attendance ? 'border-success' : 'border-warning' }}">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-book-open me-2"></i>{{ $schedule->subject }}
                                        </h6>
                                        @if($schedule->attendance)
                                            <span class="badge bg-success">
                                                <i class="bx bx-check"></i> Sudah Presensi
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bx bx-time"></i> Belum Presensi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx bx-building-house text-primary me-2"></i>
                                            <strong>{{ $schedule->school->name ?? 'N/A' }}</strong>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx bx-group text-info me-2"></i>
                                            <span>Kelas: {{ $schedule->class_name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-time text-warning me-2"></i>
                                            <span>{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                        </div>
                                    </div>

                                    @if($schedule->attendance)
                                        <div class="alert alert-success p-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-check-circle bx-lg me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">Presensi Berhasil</h6>
                                                    <small class="text-muted">Waktu: {{ $schedule->attendance->waktu }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-primary w-100" onclick="markAttendance({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}')">
                                            <i class="bx bx-check-circle me-2"></i> Lakukan Presensi
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modal for Attendance -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="attendanceModalLabel">
                    <i class="bx bx-check-circle me-2"></i>Konfirmasi Presensi Mengajar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column - Schedule Info and Location -->
                    <div class="col-md-6">
                        <!-- Schedule Info -->
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="bx bx-info-circle me-2"></i>Detail Jadwal Mengajar
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Mata Pelajaran</label>
                                    <input type="text" id="modal-subject" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kelas</label>
                                    <input type="text" id="modal-class" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sekolah</label>
                                    <input type="text" id="modal-school" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Waktu</label>
                                    <input type="text" id="modal-time" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bx bx-map me-2"></i>Verifikasi Lokasi
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshLocation()">
                                        <i class="bx bx-refresh me-1"></i> Refresh Lokasi
                                    </button>
                                </div>

                                <div id="locationStatus" class="alert alert-info mb-3">
                                    <i class="bx bx-loader-alt bx-spin me-2"></i> Mendapatkan lokasi Anda...
                                </div>

                                <!-- Mini Map -->
                                <div class="mb-3">
                                    <label class="form-label">Peta Lokasi Anda</label>
                                    <div id="miniMap" style="height: 200px; width: 100%; border-radius: 5px; border: 1px solid #ddd; position: relative; overflow: hidden;"></div>
                                </div>

                                <!-- Coordinates -->
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label">Latitude</label>
                                        <input type="text" id="currentLatitude" class="form-control" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Longitude</label>
                                        <input type="text" id="currentLongitude" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - User Info -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bx bx-user me-2"></i>Informasi Pengguna
                                </h6>
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
                                    <input type="text" id="current-time-modal" class="form-control" readonly>
                                </div>

                                <!-- Warning -->
                                <div class="alert alert-warning">
                                    <i class="bx bx-error-circle me-2"></i>
                                    <strong>Penting!</strong><br>
                                    Pastikan Anda berada di dalam area sekolah yang telah ditentukan untuk melakukan presensi mengajar.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="confirmAttendanceBtn" disabled>
                    <i class="bx bx-check-circle me-2"></i> Ya, Lakukan Presensi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('build/libs/leaflet/leaflet.js') }}"></script>
<script>
let currentScheduleId = null;
let userLocation = null;
let miniMap = null;
let userMarker = null;

function getUserLocation() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject('Browser tidak mendukung geolokasi.');
            return;
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 15000, // 15 seconds
            maximumAge: 300000 // 5 minutes
        };

        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                });
            },
            (error) => {
                let errorMessage = '';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Akses lokasi ditolak. Pastikan Anda mengizinkan akses lokasi di browser dan GPS aktif.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Lokasi tidak tersedia. Pastikan GPS aktif dan sinyal GPS cukup kuat.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Waktu habis mendapatkan lokasi. Coba lagi atau pastikan koneksi internet stabil.';
                        break;
                    default:
                        errorMessage = 'Gagal mendapatkan lokasi. Pastikan GPS aktif dan browser diizinkan akses lokasi.';
                        break;
                }
                reject(errorMessage);
            },
            options
        );
    });
}

function updateLocationStatus(status, message, isSuccess = false) {
    $('#locationStatus').removeClass('alert-info alert-success alert-danger');
    if (isSuccess) {
        $('#locationStatus').addClass('alert-success').html('<i class="bx bx-check-circle"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', false);
    } else if (status === 'loading') {
        $('#locationStatus').addClass('alert-info').html('<i class="bx bx-loader-alt bx-spin"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', true);
    } else {
        $('#locationStatus').addClass('alert-danger').html('<i class="bx bx-error"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', true);
    }
}

function initializeMiniMap(lat = -6.2088, lng = 106.8456) {
    if (miniMap) {
        miniMap.remove();
    }

    miniMap = L.map('miniMap').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(miniMap);

    userMarker = L.marker([lat, lng]).addTo(miniMap)
        .bindPopup('Lokasi Anda saat ini')
        .openPopup();
}

function markAttendance(scheduleId, subject, className, schoolName) {
    currentScheduleId = scheduleId;
    userLocation = null;

    // Update modal content
    $('#modal-subject').val(subject);
    $('#modal-class').val(className);
    $('#modal-school').val(schoolName);
    $('#modal-time').val('{{ \Carbon\Carbon::now()->format("H:i:s") }}');

    // Update current time in modal
    function updateModalTime() {
        const now = new Date();
        $('#current-time-modal').val(now.toLocaleTimeString('id-ID'));
    }
    updateModalTime();
    setInterval(updateModalTime, 1000);

    $('#attendanceModal').modal('show');
    updateLocationStatus('loading', 'Mendapatkan lokasi Anda...');

    // Initialize mini map immediately with default location
    initializeMiniMap();

    // Get user location
    getUserLocation().then(location => {
        userLocation = location;
        $('#currentLatitude').val(location.latitude.toFixed(6));
        $('#currentLongitude').val(location.longitude.toFixed(6));

        // Update mini map with actual location
        if (miniMap && userMarker) {
            userMarker.setLatLng([location.latitude, location.longitude]);
            miniMap.setView([location.latitude, location.longitude], 16);
        }

        updateLocationStatus('success', 'Lokasi berhasil didapatkan dan berada dalam area sekolah.', true);
    }).catch(error => {
        updateLocationStatus('error', error);
    });
}

function refreshLocation() {
    updateLocationStatus('loading', 'Memperbarui lokasi Anda...');

    getUserLocation().then(location => {
        userLocation = location;
        $('#currentLatitude').val(location.latitude.toFixed(6));
        $('#currentLongitude').val(location.longitude.toFixed(6));

        // Update mini map
        if (miniMap && userMarker) {
            userMarker.setLatLng([location.latitude, location.longitude]);
            miniMap.setView([location.latitude, location.longitude], 16);
        } else {
            initializeMiniMap(location.latitude, location.longitude);
        }

        updateLocationStatus('success', 'Lokasi berhasil diperbarui dan berada dalam area sekolah.', true);
    }).catch(error => {
        updateLocationStatus('error', error);
    });
}

$('#confirmAttendanceBtn').click(function() {
    if (!userLocation || !currentScheduleId) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Lokasi belum didapatkan atau jadwal tidak valid. Pastikan lokasi sudah berhasil didapatkan terlebih dahulu.'
        });
        return;
    }

    // Disable button to prevent double submission
    $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i> Memproses...');

    // Send AJAX request
    $.ajax({
        url: '{{ route("teaching-attendances.store") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            teaching_schedule_id: currentScheduleId,
            latitude: userLocation.latitude,
            longitude: userLocation.longitude,
            lokasi: 'Presensi Mengajar'
        },
        success: function(response) {
            $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> Ya, Lakukan Presensi');

            if (response.success) {
                $('#attendanceModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> Ya, Lakukan Presensi');

            let message = 'Terjadi kesalahan saat melakukan presensi.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message
            });
        }
    });
});

// Cleanup map when modal is hidden
$('#attendanceModal').on('hidden.bs.modal', function () {
    if (miniMap) {
        miniMap.remove();
        miniMap = null;
        userMarker = null;
    }
});
</script>
@endsection
