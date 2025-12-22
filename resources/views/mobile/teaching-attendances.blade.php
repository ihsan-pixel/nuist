@extends('layouts.mobile')

@section('title', 'Presensi Mengajar')
@section('subtitle', 'Presensi Mengajar Saya')

@section('content')
    <div class="container py-3" style="max-width: 600px; margin: auto;">
    <div class="sticky-header">
        <div class="text-center mb-4">
            <h5 class="fw-bold text-dark mb-1" style="font-size: 18px;">Presensi Mengajar</h5>
            <small class="text-muted" style="font-size: 12px;">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</small>
        </div>

        @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-3" style="background: rgba(25, 135, 84, 0.1); color: #198754; border-radius: 12px; padding: 10px;">
            <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
        </div>
        @endif
    </div>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        body {
            background-color: transparent !important;
        }



        .schedule-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
            width: 100%;
            margin-bottom: 8px;
        }

        .schedule-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .schedule-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #0e8549, #0f9d58);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(14, 133, 73, 0.3);
        }

        .schedule-icon i {
            color: #fff;
            font-size: 14px;
        }

        .schedule-info {
            flex: 1;
            min-width: 0;
        }

        .schedule-info strong {
            font-size: 14px;
            color: #2d3748;
            display: block;
            margin-bottom: 3px;
            font-weight: 600;
            line-height: 1.2;
        }

        .schedule-info small {
            font-size: 12px;
            color: #718096;
            display: block;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .schedule-time {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 0;
            font-weight: 500;
        }

        .school-badge {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .presensi-btn { background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border: none; border-radius: 8px; padding: 10px; color: #fff; font-weight: 600; font-size: 14px; width: 100%; }
        .presensi-btn.outline { background: transparent; border:1px solid #e9ecef; color:#333; }
        .small-muted { font-size: 12px; color: #6c757d; }

        .day-card {
            max-width: 520px;
            margin: 0 auto;
            background: linear-gradient(135deg, #fdbd57 0%, #f89a3c 50%, #e67e22 100%);
            border-radius: 0;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 16px;
            box-shadow: none;
            border: none;
        }

        .schedule-list {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .no-schedule {
            text-align: center;
            padding: 20px;
            color: #999;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .no-schedule i {
            font-size: 32px;
            margin-bottom: 8px;
            opacity: 0.7;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #f8f9fb;
            padding-bottom: 16px;
        }
    </style>

    <!-- Header -->
    {{-- <div class="presensi-header d-flex align-items-center">
        <div class="me-2">
            <h6 class="mb-1">Presensi-Mengajar</h6>
            <h5 class="fw-bold mb-0">{{ Auth::user()->name ?? Auth::user()->username ?? 'User' }}</h5>
        </div>
        <div class="ms-auto">
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg') }}" class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div> --}}

    <!-- Date below header (smaller font) -->
    {{-- <div class="text-center mt-2">
        <small class="small-muted presensi-date">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</small>
    </div> --}}

    <div class="day-card">
        <div class="schedule-list">
            @if($schedules->isEmpty())
                <div class="no-schedule">
                    <i class="bx bx-calendar-x"></i>
                    <p>Tidak ada jadwal mengajar hari ini</p>
                </div>
            @else
                @foreach($schedules as $schedule)
                    <div class="schedule-item">
                        <div class="schedule-icon">
                            <i class="bx bx-book"></i>
                        </div>
                        <div class="schedule-info">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <strong>{{ $schedule->subject }}</strong>
                                    <small>{{ $schedule->class_name }}</small>
                                    <div class="schedule-time">
                                        <i class="bx bx-time-five"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                    </div>
                                    <div class="school-badge">
                                        {{ Str::limit($schedule->school->name ?? 'N/A', 100) }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($schedule->attendance)
                                        <div class="badge bg-success">Hadir</div>
                                    @else
                                        <div class="badge bg-warning text-dark">Belum</div>
                                    @endif
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
                                    <div class="time-status-container" data-schedule-id="{{ $schedule->id }}" data-start-time="{{ $schedule->start_time }}" data-end-time="{{ $schedule->end_time }}">
                                        @php
                                            $currentTime = \Carbon\Carbon::now('Asia/Jakarta');
                                            $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
                                            $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                                            $isWithinTime = $currentTime->between($startTime, $endTime);
                                            $isBeforeStart = $currentTime->lt($startTime);
                                            $isAfterEnd = $currentTime->gt($endTime);
                                        @endphp

                                        @if($isWithinTime)
                                            <button class="presensi-btn attendance-btn" data-schedule-id="{{ $schedule->id }}" onclick="openAttendanceModal({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}', '{{ $schedule->start_time }}', '{{ $schedule->end_time }}')">
                                                <i class="bx bx-check-circle me-1"></i> Lakukan Presensi
                                            </button>
                                        @elseif($isBeforeStart)
                                            <button class="presensi-btn outline countdown-btn" disabled data-schedule-id="{{ $schedule->id }}">
                                                <i class="bx bx-time me-1"></i> <span class="countdown-text">Menunggu Waktu Mengajar</span>
                                            </button>
                                            <div class="text-center mt-2">
                                                <small class="small-muted bg-light px-2 py-1 rounded-pill countdown-info" data-schedule-id="{{ $schedule->id }}">
                                                    <i class="bx bx-info-circle me-1"></i>Waktu mengajar: {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                </small>
                                            </div>
                                        @else
                                            <button class="presensi-btn outline" disabled>
                                                <i class="bx bx-time me-1"></i> Waktu Mengajar Berakhir
                                            </button>
                                            <div class="text-center mt-2">
                                                <small class="small-muted bg-light px-2 py-1 rounded-pill">
                                                    <i class="bx bx-info-circle me-1"></i>Waktu mengajar: {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

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
// Real-time time-based attendance functionality
let timeCheckInterval;
let scheduleData = {};

// Initialize schedule data from DOM
function initializeScheduleData() {
    document.querySelectorAll('.time-status-container').forEach(container => {
        const scheduleId = container.dataset.scheduleId;
        const startTime = container.dataset.startTime;
        const endTime = container.dataset.endTime;

        scheduleData[scheduleId] = {
            startTime: startTime,
            endTime: endTime,
            container: container
        };
    });
}

// Format time difference to readable format
function formatTimeDifference(minutes) {
    if (minutes < 1) {
        return 'Kurang dari 1 menit';
    } else if (minutes < 60) {
        return `${Math.floor(minutes)} menit lagi`;
    } else {
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = Math.floor(minutes % 60);
        if (remainingMinutes === 0) {
            return `${hours} jam lagi`;
        } else {
            return `${hours} jam ${remainingMinutes} menit lagi`;
        }
    }
}

// Check current time and update UI accordingly
function checkTimeAndUpdateUI() {
    const now = new Date();
    const jakartaTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Jakarta"}));
    const currentTime = jakartaTime.getHours() * 60 + jakartaTime.getMinutes(); // minutes since midnight

    Object.keys(scheduleData).forEach(scheduleId => {
        const data = scheduleData[scheduleId];
        const [startHour, startMinute] = data.startTime.split(':').map(Number);
        const [endHour, endMinute] = data.endTime.split(':').map(Number);

        const startMinutes = startHour * 60 + startMinute;
        const endMinutes = endHour * 60 + endMinute;

        const container = data.container;
        const countdownBtn = container.querySelector('.countdown-btn');
        const countdownText = container.querySelector('.countdown-text');
        const countdownInfo = container.querySelector('.countdown-info');
        const attendanceBtn = container.querySelector('.attendance-btn');

        if (currentTime >= startMinutes && currentTime <= endMinutes) {
            // Within teaching time - show attendance button
            if (countdownBtn) countdownBtn.style.display = 'none';
            if (countdownInfo) countdownInfo.style.display = 'none';
            if (attendanceBtn) attendanceBtn.style.display = 'block';
        } else if (currentTime < startMinutes) {
            // Before teaching time - show countdown
            const minutesUntilStart = startMinutes - currentTime;
            if (countdownBtn) {
                countdownBtn.style.display = 'block';
                if (countdownText) {
                    countdownText.textContent = `Mulai dalam ${formatTimeDifference(minutesUntilStart)}`;
                }
            }
            if (countdownInfo) countdownInfo.style.display = 'block';
            if (attendanceBtn) attendanceBtn.style.display = 'none';
        } else {
            // After teaching time - show ended state
            if (countdownBtn) countdownBtn.style.display = 'none';
            if (countdownInfo) countdownInfo.style.display = 'none';
            if (attendanceBtn) attendanceBtn.style.display = 'none';
        }
    });
}

// Start real-time time checking
function startTimeChecking() {
    // Check immediately
    checkTimeAndUpdateUI();

    // Then check every 30 seconds
    timeCheckInterval = setInterval(checkTimeAndUpdateUI, 30000);
}

// Stop time checking
function stopTimeChecking() {
    if (timeCheckInterval) {
        clearInterval(timeCheckInterval);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeScheduleData();
    startTimeChecking();
});

// Clean up when page unloads
window.addEventListener('beforeunload', function() {
    stopTimeChecking();
});

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
