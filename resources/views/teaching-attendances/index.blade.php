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
                                        @php
                                            $currentTime = \Carbon\Carbon::now('Asia/Jakarta');
                                            $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
                                            $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                                            $isWithinTime = $currentTime->between($startTime, $endTime);
                                        @endphp
                                        @if($isWithinTime)
                                            <button type="button" class="btn btn-primary w-100" onclick="markAttendance({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}')">
                                                <i class="bx bx-check-circle me-2"></i> Lakukan Presensi
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary w-100" disabled>
                                                <i class="bx bx-time me-2"></i> Diluar Waktu Mengajar
                                            </button>
                                            <small class="text-muted d-block text-center mt-1">Waktu mengajar: {{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
                                        @endif
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

<!-- Simple Modal for Attendance -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="attendanceModalLabel">
                    <i class="bx bx-check-circle me-2"></i>Konfirmasi Presensi Mengajar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Schedule Info -->
                <div class="card border-primary mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Mata Pelajaran:</strong></p>
                                <p id="modal-subject" class="text-primary mb-2"></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Kelas:</strong></p>
                                <p id="modal-class" class="text-primary mb-2"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Sekolah:</strong></p>
                                <p id="modal-school" class="text-primary mb-2"></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Waktu:</strong></p>
                                <p id="modal-time" class="text-primary mb-2"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="card-title mb-0">
                                    <i class="bx bx-map-pin me-2"></i>Lokasi Anda Saat Ini
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
                                    <div id="miniMap" style="height: 200px; width: 100%; border-radius: 5px; border: 1px solid #ddd; position: relative; overflow: hidden;"></div>
                                </div>

                                <!-- Coordinates -->
                                <div class="mb-3">
                                    <label class="form-label">Koordinat Lokasi</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" id="currentLatitude" class="form-control form-control-sm" placeholder="Latitude" readonly>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" id="currentLongitude" class="form-control form-control-sm" placeholder="Longitude" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat Lokasi</label>
                                    <input type="text" id="currentAddress" class="form-control form-control-sm" placeholder="Alamat akan muncul otomatis" readonly>
                                </div>

                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Akurasi: <span id="currentAccuracy">Menunggu...</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Informasi Presensi</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ auth()->user()->name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Madrasah</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ auth()->user()->madrasah?->name ?? 'Tidak ada data' }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ \Carbon\Carbon::now()->format('d F Y') }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Waktu Sekarang</label>
                                    <input type="text" id="current-time" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="bx bx-error-circle me-2"></i>
                                    <strong>Penting!</strong> Pastikan Anda berada di dalam area sekolah yang telah ditentukan untuk melakukan presensi mengajar.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning -->
                <div class="alert alert-warning">
                    <i class="bx bx-error-circle me-2"></i>
                    <strong>Penting!</strong> Pastikan Anda berada di dalam area sekolah yang telah ditentukan untuk melakukan presensi mengajar.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="confirmAttendanceBtn" disabled>
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
let currentAddress = null;

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
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy
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
    $('#locationStatus').removeClass('alert-info alert-success alert-danger alert-warning');
    if (isSuccess) {
        $('#locationStatus').addClass('alert-success').html('<i class="bx bx-check-circle"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', false);
    } else if (status === 'loading') {
        $('#locationStatus').addClass('alert-info').html('<i class="bx bx-loader-alt bx-spin"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', true);
    } else if (status === 'warning') {
        $('#locationStatus').addClass('alert-warning').html('<i class="bx bx-error-circle"></i> ' + message);
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

    // Add accuracy circle if available
    if (userLocation && userLocation.accuracy) {
        L.circle([lat, lng], {
            color: 'red',
            fillColor: 'red',
            fillOpacity: 0.1,
            radius: userLocation.accuracy
        }).addTo(miniMap);
    }
}

function markAttendance(scheduleId, subject, className, schoolName) {
    currentScheduleId = scheduleId;
    userLocation = null;

    // Update modal content
    $('#modal-subject').text(subject);
    $('#modal-class').text(className);
    $('#modal-school').text(schoolName);
    $('#modal-time').text('{{ \Carbon\Carbon::now()->format("H:i:s") }}');

    $('#attendanceModal').modal('show');
    updateLocationStatus('loading', 'Mendapatkan lokasi Anda...');

    // Initialize mini map immediately with default location
    initializeMiniMap();

    // Get user location
    getUserLocation().then(location => {
        userLocation = location;
        $('#currentLatitude').val(location.latitude.toFixed(6));
        $('#currentLongitude').val(location.longitude.toFixed(6));
        $('#currentAccuracy').text(location.accuracy ? location.accuracy.toFixed(0) + ' meter' : 'Tidak tersedia');

        // Get address from coordinates
        getAddressFromCoordinates(location.latitude, location.longitude);

        // Update mini map with actual location
        if (miniMap && userMarker) {
            userMarker.setLatLng([location.latitude, location.longitude]);
            miniMap.setView([location.latitude, location.longitude], 16);
        }

        // Check if location is within school polygon
        checkLocationInPolygon(location.latitude, location.longitude, currentScheduleId).then(isValid => {
            if (isValid) {
                updateLocationStatus('success', 'Lokasi berhasil didapatkan dan berada dalam area sekolah.', true);
            } else {
                updateLocationStatus('warning', 'Lokasi Anda berada di luar area sekolah. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.', false);
            }
        }).catch(error => {
            updateLocationStatus('error', 'Gagal memverifikasi lokasi dalam area sekolah: ' + error, false);
        });
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
        $('#currentAccuracy').text(location.accuracy ? location.accuracy.toFixed(0) + ' meter' : 'Tidak tersedia');

        // Get address from coordinates
        getAddressFromCoordinates(location.latitude, location.longitude);

        // Update mini map
        if (miniMap && userMarker) {
            userMarker.setLatLng([location.latitude, location.longitude]);
            miniMap.setView([location.latitude, location.longitude], 16);
        } else {
            initializeMiniMap(location.latitude, location.longitude);
        }

        // Check if location is within school polygon
        checkLocationInPolygon(location.latitude, location.longitude, currentScheduleId).then(isValid => {
            if (isValid) {
                updateLocationStatus('success', 'Lokasi berhasil diperbarui dan berada dalam area sekolah.', true);
            } else {
                updateLocationStatus('warning', 'Lokasi Anda berada di luar area sekolah. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.', false);
            }
        }).catch(error => {
            updateLocationStatus('error', 'Gagal memverifikasi lokasi dalam area sekolah: ' + error, false);
        });
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

    // Check location in polygon before submitting
    checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Lokasi Tidak Valid!',
                text: 'Lokasi Anda berada di luar area sekolah. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.'
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
    }).catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal memverifikasi lokasi: ' + error
        });
    });
});

// Function to check location in polygon via AJAX
function checkLocationInPolygon(lat, lng, scheduleId) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '{{ route("teaching-attendances.check-location") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                latitude: lat,
                longitude: lng,
                teaching_schedule_id: scheduleId
            },
            success: function(response) {
                if (response.success) {
                    resolve(response.is_within_polygon);
                } else {
                    reject(response.message || 'Gagal memverifikasi lokasi');
                }
            },
            error: function(xhr, status, error) {
                reject('Gagal memverifikasi lokasi: ' + error);
            }
        });
    });
}

// Function to get address from coordinates
function getAddressFromCoordinates(lat, lng) {
    return fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                currentAddress = data.display_name;
                $('#currentAddress').val(currentAddress);
            } else {
                $('#currentAddress').val('Tidak dapat mendapatkan alamat');
            }
        })
        .catch(error => {
            console.error('Error getting address:', error);
            $('#currentAddress').val('Tidak dapat mendapatkan alamat');
        });
}

// Update waktu sekarang setiap detik
function updateTime() {
    const now = new Date();
    $('#current-time').val(now.toLocaleTimeString('id-ID'));
}
updateTime();
setInterval(updateTime, 1000);

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
