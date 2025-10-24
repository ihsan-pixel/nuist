@extends('layouts.mobile')

@section('title', 'Presensi Mengajar')
@section('subtitle', 'Presensi Mengajar Hari Ini')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Presensi Mengajar @endslot
@endcomponent

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3 d-flex align-items-center">
        <div class="avatar-lg me-3">
            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                <i class="bx bx-book fs-2"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-0">Presensi Mengajar Hari Ini</h6>
            <small class="text-muted">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</small>
        </div>
        <div class="text-end">
            <div class="fs-5 fw-bold text-primary">{{ $schedules->count() }}</div>
            <small class="text-muted">Jadwal Hari Ini</small>
        </div>
    </div>
</div>

@if($schedules->isEmpty())
    <div class="card empty-state shadow-sm border-0">
        <div class="card-body text-center py-5">
            <div class="avatar-xl mx-auto mb-4">
                <div class="avatar-title bg-light rounded-circle">
                    <i class="bx bx-calendar-x fs-1 text-muted"></i>
                </div>
            </div>
            <h5 class="text-muted mb-2">Tidak ada jadwal mengajar hari ini</h5>
            <p class="text-muted mb-0">Anda tidak memiliki jadwal mengajar yang terjadwal untuk hari ini.</p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($schedules as $schedule)
        <div class="col-12">
            <div class="card position-relative p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <h6 class="mb-1"><i class="bx bx-book-open text-primary me-2"></i>{{ $schedule->subject }}</h6>
                        <div class="text-muted small"><i class="bx bx-building-house"></i> {{ $schedule->school->name ?? 'N/A' }}</div>
                    </div>
                    <div class="text-end">
                        @if($schedule->attendance)
                            <span class="badge bg-success">Hadir</span>
                        @else
                            <span class="badge bg-warning text-dark">Belum</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-2">
                    <div>
                        <small class="text-muted d-block">Kelas</small>
                        <div class="fw-medium">{{ $schedule->class_name }}</div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Waktu</small>
                        <div class="fw-medium">{{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                    </div>
                </div>

                <div class="mt-3">
                    @if($schedule->attendance)
                        <div class="alert alert-success mb-0">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle fs-4 me-2"></i>
                                <div>
                                    <div class="fw-semibold">Presensi Berhasil</div>
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
                            <button class="btn btn-primary btn-lg w-100" onclick="markAttendance({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}', '{{ $schedule->start_time }}', '{{ $schedule->end_time }}')"> 
                                <i class="bx bx-check-circle me-1"></i> Lakukan Presensi
                            </button>
                        @else
                            <button class="btn btn-outline-secondary btn-lg w-100" disabled>
                                <i class="bx bx-time me-1"></i> Diluar Waktu Mengajar
                            </button>
                            <div class="text-center mt-2">
                                <small class="text-muted bg-light px-2 py-1 rounded-pill">
                                    <i class="bx bx-info-circle me-1"></i>Waktu mengajar: {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                </small>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<!-- Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h6 class="mb-0">Konfirmasi Presensi Mengajar</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="fw-semibold" id="modal-subject"></div>
                    <div class="text-muted small" id="modal-class"></div>
                    <div class="text-muted small" id="modal-school"></div>
                    <div class="text-muted small" id="modal-time"></div>
                </div>

                <div id="locationStatus" class="alert alert-info">
                    <i class="bx bx-loader-alt bx-spin me-2"></i> Mendapatkan lokasi Anda...
                </div>

                <div class="alert alert-warning">
                    <i class="bx bx-error-circle me-2"></i>
                    Pastikan Anda berada di dalam area sekolah yang ditentukan. Presensi hanya bisa dilakukan sesuai jam mengajar.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmAttendanceBtn" disabled>Ya, Lakukan Presensi</button>
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
        navigator.geolocation.getCurrentPosition((position) => {
            resolve({
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            });
        }, (error) => {
            let errorMessage = 'Gagal mendapatkan lokasi.';
            if (error.code === error.PERMISSION_DENIED) {
                errorMessage = 'Akses lokasi ditolak. Pastikan Anda mengizinkan akses lokasi.';
            }
            reject(errorMessage);
        }, {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 300000
        });
    });
}

function updateLocationStatus(status, message, isSuccess = false) {
    const el = document.getElementById('locationStatus');
    el.className = 'alert';
    if (isSuccess) {
        el.classList.add('alert-success');
        el.innerHTML = '<i class="bx bx-check-circle me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = false;
    } else if (status === 'loading') {
        el.classList.add('alert-info');
        el.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = true;
    } else if (status === 'warning') {
        el.classList.add('alert-warning');
        el.innerHTML = '<i class="bx bx-error-circle me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = true;
    } else {
        el.classList.add('alert-danger');
        el.innerHTML = '<i class="bx bx-error me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = true;
    }
}

function markAttendance(scheduleId, subject, className, schoolName, startTime, endTime) {
    currentScheduleId = scheduleId;
    userLocation = null;

    document.getElementById('modal-subject').innerText = subject;
    document.getElementById('modal-class').innerText = className;
    document.getElementById('modal-school').innerText = schoolName;
    document.getElementById('modal-time').innerText = startTime + ' - ' + endTime;

    const modal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    modal.show();
    updateLocationStatus('loading', 'Mendapatkan lokasi Anda...');

    getUserLocation().then(location => {
        userLocation = location;
        checkLocationInPolygon(location.latitude, location.longitude, currentScheduleId).then(isValid => {
            if (isValid) {
                updateLocationStatus('success', 'Lokasi berada dalam area sekolah.', true);
            } else {
                updateLocationStatus('warning', 'Lokasi Anda berada di luar area sekolah.');
            }
        }).catch(err => updateLocationStatus('error', 'Gagal memverifikasi lokasi: ' + err));
    }).catch(err => updateLocationStatus('error', err));
}

function checkLocationInPolygon(lat, lng, scheduleId) {
    return new Promise((resolve, reject) => {
        fetch('{{ route('teaching-attendances.check-location') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ latitude: lat, longitude: lng, teaching_schedule_id: scheduleId })
        }).then(res => res.json()).then(json => {
            if (json.success) resolve(json.is_within_polygon);
            else reject(json.message || 'Gagal verifikasi');
        }).catch(err => reject(err));
    });
}

document.getElementById('confirmAttendanceBtn').addEventListener('click', function() {
    if (!userLocation || !currentScheduleId) {
        alert('Lokasi belum didapatkan atau jadwal tidak valid.');
        return;
    }

    checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
        if (!isValid) {
            alert('Lokasi Anda berada di luar area sekolah.');
            return;
        }

        const btn = document.getElementById('confirmAttendanceBtn');
        btn.disabled = true; btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Memproses...';

        fetch('{{ route('teaching-attendances.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ teaching_schedule_id: currentScheduleId, latitude: userLocation.latitude, longitude: userLocation.longitude, lokasi: 'Presensi Mengajar' })
        }).then(res => res.json()).then(json => {
            btn.disabled = false; btn.innerHTML = 'Ya, Lakukan Presensi';
            if (json.success) {
                alert('Presensi mengajar berhasil dicatat!');
                location.reload();
            } else {
                alert('Gagal: ' + (json.message || 'Terjadi kesalahan'));
            }
        }).catch(err => {
            btn.disabled = false; btn.innerHTML = 'Ya, Lakukan Presensi';
            alert('Error: ' + err);
        });

    }).catch(err => alert('Error: ' + err));
});
</script>
@endsection
