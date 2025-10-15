@extends('layouts.master')

@section('title', 'Presensi Mengajar')

@section('vendor-script')
<!-- SweetAlert2 -->
<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Presensi Mengajar Hari Ini ({{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }})</h4>
            </div>
            <div class="card-body">
                @if($schedules->isEmpty())
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle"></i> Tidak ada jadwal mengajar hari ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Sekolah</th>
                                    <th>Waktu</th>
                                    <th>Status Presensi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->subject }}</td>
                                    <td>{{ $schedule->class_name }}</td>
                            <td>{{ $schedule->school->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                    <td>
                                        @if($schedule->attendance)
                                            <span class="badge bg-success">Hadir ({{ $schedule->attendance->waktu }})</span>
                                        @else
                                            <span class="badge bg-warning">Belum Presensi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$schedule->attendance)
                                            <button type="button" class="btn btn-primary btn-sm" onclick="markAttendance({{ $schedule->id }})">
                                                <i class="bx bx-check"></i> Presensi
                                            </button>
                                        @else
                                            <span class="text-muted">Sudah Presensi</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Attendance -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Konfirmasi Presensi Mengajar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin melakukan presensi mengajar untuk jadwal ini?</p>
                <p><strong>Pastikan Anda berada di lokasi sekolah dan dalam waktu mengajar.</strong></p>
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshLocation()">
                        <i class="bx bx-refresh"></i> Refresh Lokasi
                    </button>
                </div>
                <div id="locationStatus" class="alert alert-info">
                    <i class="bx bx-loader-alt bx-spin"></i> Mendapatkan lokasi Anda...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmAttendanceBtn" disabled>
                    <i class="bx bx-check"></i> Ya, Presensi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
let currentScheduleId = null;
let userLocation = null;

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
                        errorMessage = 'Akses lokasi ditolak. Pastikan Anda mengizinkan akses lokasi di browser.';
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
        console.log('Location obtained successfully:', userLocation);
    } else if (status === 'loading') {
        $('#locationStatus').addClass('alert-info').html('<i class="bx bx-loader-alt bx-spin"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', true);
    } else {
        $('#locationStatus').addClass('alert-danger').html('<i class="bx bx-error"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', true);
    }
    console.log('Button disabled state:', $('#confirmAttendanceBtn').prop('disabled'));
}

function markAttendance(scheduleId) {
    currentScheduleId = scheduleId;
    userLocation = null;
    $('#attendanceModal').modal('show');
    updateLocationStatus('loading', 'Mendapatkan lokasi Anda...');

    // Get user location
    getUserLocation().then(location => {
        userLocation = location;
        updateLocationStatus('success', 'Lokasi berhasil didapatkan: ' + location.latitude.toFixed(6) + ', ' + location.longitude.toFixed(6), true);
    }).catch(error => {
        updateLocationStatus('error', error);
    });
}

function refreshLocation() {
    updateLocationStatus('loading', 'Memperbarui lokasi Anda...');

    getUserLocation().then(location => {
        userLocation = location;
        updateLocationStatus('success', 'Lokasi berhasil diperbarui: ' + location.latitude.toFixed(6) + ', ' + location.longitude.toFixed(6), true);
    }).catch(error => {
        updateLocationStatus('error', error);
    });
}

$('#confirmAttendanceBtn').click(function() {
    console.log('Confirm button clicked');
    console.log('userLocation:', userLocation);
    console.log('currentScheduleId:', currentScheduleId);

    if (!userLocation || !currentScheduleId) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Lokasi belum didapatkan atau jadwal tidak valid. Pastikan lokasi sudah berhasil didapatkan terlebih dahulu.'
        });
        return;
    }

    // Disable button to prevent double submission
    $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Memproses...');

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
            console.log('AJAX success response:', response);
            $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check"></i> Ya, Presensi');

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
            console.log('AJAX error:', xhr, status, error);
            $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check"></i> Ya, Presensi');

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
</script>
@endsection
