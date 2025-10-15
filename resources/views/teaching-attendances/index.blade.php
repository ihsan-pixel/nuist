@extends('layouts.master')

@section('title', 'Presensi Mengajar')

@section('vendor-script')
<!-- SweetAlert2 -->
<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
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
                                            <button type="button" class="btn btn-primary w-100" onclick="markAttendance({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}', '{{ $schedule->start_time }}', '{{ $schedule->end_time }}')">
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



@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
function markAttendance(scheduleId, subject, className, schoolName, startTime, endTime) {
    Swal.fire({
        title: 'Konfirmasi Presensi Mengajar',
        html: `
            <div class="text-left">
                <div class="mb-3">
                    <strong>Mata Pelajaran:</strong> ${subject}<br>
                    <strong>Kelas:</strong> ${className}<br>
                    <strong>Sekolah:</strong> ${schoolName}<br>
                    <strong>Waktu Mengajar:</strong> ${startTime} - ${endTime}
                </div>
                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-2"></i>
                    Sistem sedang memverifikasi lokasi Anda...
                </div>
                <div class="alert alert-warning">
                    <i class="bx bx-error-circle me-2"></i>
                    <strong>Penting!</strong> Pastikan Anda berada di dalam area sekolah yang telah ditentukan untuk melakukan presensi mengajar.
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ya, Lakukan Presensi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        preConfirm: () => {
            return new Promise((resolve, reject) => {
                // Get user location
                if (!navigator.geolocation) {
                    reject('Browser tidak mendukung geolokasi.');
                    return;
                }

                const options = {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 300000
                };

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const location = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                            accuracy: position.coords.accuracy
                        };

                        // Check if location is within school polygon
                        $.ajax({
                            url: '{{ route("teaching-attendances.check-location") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                latitude: location.latitude,
                                longitude: location.longitude,
                                teaching_schedule_id: scheduleId
                            },
                            success: function(response) {
                                if (response.success && response.is_within_polygon) {
                                    resolve(location);
                                } else {
                                    reject('Lokasi Anda berada di luar area sekolah. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.');
                                }
                            },
                            error: function(xhr, status, error) {
                                reject('Gagal memverifikasi lokasi: ' + error);
                            }
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
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const location = result.value;

            // Show processing message
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan presensi mengajar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send AJAX request to save attendance
            $.ajax({
                url: '{{ route("teaching-attendances.store") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    teaching_schedule_id: scheduleId,
                    latitude: location.latitude,
                    longitude: location.longitude,
                    lokasi: 'Presensi Mengajar'
                },
                success: function(response) {
                    if (response.success) {
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
        }
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error
        });
    });
}




</script>
@endsection
