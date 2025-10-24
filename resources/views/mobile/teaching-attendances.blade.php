@extends('layouts.mobile')

@section('title', 'Presensi Mengajar')
@section('subtitle', 'Presensi Mengajar Hari Ini')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        /* reuse mobile presensi styles for consistency */
        .presensi-header { background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); color: #fff; border-radius: 12px; padding: 12px 10px; box-shadow: 0 4px 10px rgba(0,75,76,0.3); margin-bottom: 10px; }
        .presensi-header h6 { font-weight: 600; font-size: 12px; }
        .presensi-header h5 { font-size: 14px; }
        .schedule-card { background: #fff; border-radius: 12px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 10px; }
        .status-icon { width: 36px; height: 36px; border-radius: 50%; display:flex; align-items:center; justify-content:center; margin-right:10px; }
        .presensi-btn { background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border: none; border-radius: 8px; padding: 10px; color: #fff; font-weight: 600; font-size: 14px; width: 100%; }
        .presensi-btn.outline { background: transparent; border:1px solid #e9ecef; color:#333; }
        .small-muted { font-size: 12px; color: #6c757d; }
    </style>

    <!-- Header -->
    <div class="presensi-header d-flex align-items-center">
        <div class="me-2">
            <h6 class="mb-0">Presensi Mengajar</h6>
            <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
            <small class="small-muted">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</small>
        </div>
        <div class="ms-auto">
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}" class="rounded-circle border border-white" width="40" height="40" alt="User">
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
        @foreach($schedules as $schedule)
            <div class="schedule-card d-flex align-items-start">
                <div class="status-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bx bx-book-open"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="fw-semibold">{{ $schedule->subject }}</div>
                            <div class="small-muted">{{ $schedule->school->name ?? 'N/A' }}</div>
                        </div>
                        <div class="text-end">
                            @if($schedule->attendance)
                                <div class="badge bg-success">Hadir</div>
                            @else
                                <div class="badge bg-warning text-dark">Belum</div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div>
                            <small class="small-muted">Kelas</small>
                            <div class="fw-medium">{{ $schedule->class_name }}</div>
                        </div>
                        <div class="text-end">
                            <small class="small-muted">Waktu</small>
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
                                        <small class="small-muted">Waktu: {{ $schedule->attendance->waktu }}</small>
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
                                <button class="presensi-btn" onclick="openAttendanceModal({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}', '{{ $schedule->start_time }}', '{{ $schedule->end_time }}')">
                                    <i class="bx bx-check-circle me-1"></i> Lakukan Presensi
                                </button>
                            @else
                                <button class="presensi-btn outline" disabled>
                                    <i class="bx bx-time me-1"></i> Diluar Waktu Mengajar
                                </button>
                                <div class="text-center mt-2">
                                    <small class="small-muted bg-light px-2 py-1 rounded-pill">
                                        <i class="bx bx-info-circle me-1"></i>Waktu mengajar: {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                    </small>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
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

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentScheduleId = null;
let userLocation = null;

function openAttendanceModal(scheduleId, subject, className, schoolName, startTime, endTime) {
    currentScheduleId = scheduleId;
    userLocation = null;

    document.getElementById('modal-subject').innerText = subject;
    document.getElementById('modal-class').innerText = className;
    document.getElementById('modal-school').innerText = schoolName;
    document.getElementById('modal-time').innerText = startTime + ' - ' + endTime;

    const modal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    modal.show();
    updateLocationStatus('loading', 'Mendapatkan lokasi Anda...');

    // get two readings like presensi page
    getReadingAndVerify().then(() => {
        // nothing
    }).catch(err => {
        updateLocationStatus('error', err);
    });
}

function getReadingAndVerify() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject('Browser tidak mendukung geolokasi.');
            return;
        }

        // first reading: quick
        navigator.geolocation.getCurrentPosition((pos1) => {
            // store reading1
            sessionStorage.setItem('reading1_latitude', pos1.coords.latitude);
            sessionStorage.setItem('reading1_longitude', pos1.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());

            // second reading for verification
            navigator.geolocation.getCurrentPosition((pos2) => {
                userLocation = { latitude: pos2.coords.latitude, longitude: pos2.coords.longitude };
                // check location in polygon
                checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
                    if (isValid) {
                        updateLocationStatus('success', 'Lokasi berada dalam area sekolah.', true);
                    } else {
                        updateLocationStatus('warning', 'Lokasi Anda berada di luar area sekolah.');
                    }
                    resolve();
                }).catch(err => reject('Gagal memverifikasi lokasi: ' + err));
            }, (err2) => {
                reject('Gagal mendapatkan lokasi kedua: ' + err2.message);
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });

        }, (err1) => {
            reject('Gagal mendapatkan lokasi awal: ' + err1.message);
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
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

function checkLocationInPolygon(lat, lng, scheduleId) {
    return new Promise((resolve, reject) => {
        fetch('{{ route('teaching-attendances.check-location') }}', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ latitude: lat, longitude: lng, teaching_schedule_id: scheduleId })
        }).then(res => res.json()).then(json => {
            if (json.success) resolve(json.is_within_polygon);
            else reject(json.message || 'Gagal verifikasi');
        }).catch(err => reject(err));
    });
}

document.addEventListener('click', function (e) {
    if (e.target && e.target.id === 'confirmAttendanceBtn') {
        if (!userLocation || !currentScheduleId) {
            Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Lokasi belum didapatkan atau jadwal tidak valid.' });
            return;
        }

        checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
            if (!isValid) { Swal.fire({ icon: 'warning', title: 'Diluar Area', text: 'Lokasi Anda berada di luar area sekolah.' }); return; }

            const btn = document.getElementById('confirmAttendanceBtn');
            btn.disabled = true; btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Memproses...';

            fetch('{{ route('teaching-attendances.store') }}', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ teaching_schedule_id: currentScheduleId, latitude: userLocation.latitude, longitude: userLocation.longitude, lokasi: 'Presensi Mengajar' })
            }).then(res => res.json()).then(json => {
                btn.disabled = false; btn.innerHTML = 'Ya, Lakukan Presensi';
                if (json.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, timer: 2000 }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: json.message || 'Terjadi kesalahan' });
                }
            }).catch(err => { btn.disabled = false; btn.innerHTML = 'Ya, Lakukan Presensi'; Swal.fire({ icon: 'error', title: 'Error', text: err }); });

        }).catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err }));
    }
});
</script>
@endsection
