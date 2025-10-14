@extends('layouts.app')

@section('title', 'Presensi Mengajar')

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
                                    <td>{{ $schedule->school->nama_sekolah ?? 'N/A' }}</td>
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

@section('scripts')
<script>
let currentScheduleId = null;
let userLocation = null;

function markAttendance(scheduleId) {
    currentScheduleId = scheduleId;
    $('#attendanceModal').modal('show');
    $('#locationStatus').html('<i class="bx bx-loader-alt bx-spin"></i> Mendapatkan lokasi Anda...');
    $('#confirmAttendanceBtn').prop('disabled', true);

    // Get user location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userLocation = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            };
            $('#locationStatus').removeClass('alert-info').addClass('alert-success').html('<i class="bx bx-check-circle"></i> Lokasi berhasil didapatkan.');
            $('#confirmAttendanceBtn').prop('disabled', false);
        }, function(error) {
            $('#locationStatus').removeClass('alert-info').addClass('alert-danger').html('<i class="bx bx-error"></i> Gagal mendapatkan lokasi. Pastikan GPS aktif.');
        });
    } else {
        $('#locationStatus').removeClass('alert-info').addClass('alert-danger').html('<i class="bx bx-error"></i> Browser tidak mendukung geolokasi.');
    }
}

$('#confirmAttendanceBtn').click(function() {
    if (!userLocation || !currentScheduleId) {
        alert('Lokasi belum didapatkan atau jadwal tidak valid.');
        return;
    }

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
        error: function(xhr) {
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
