@extends('layouts.master')

@section('title', 'Presensi Mengajar')

@section('vendor-script')
<!-- SweetAlert2 -->
<link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection

@section('css')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.schedule-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}
.status-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.attendance-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
.attendance-pending {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}
.attendance-izin {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
}
.time-indicator {
    background: rgba(255,255,255,0.9);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.875rem;
    font-weight: 500;
}
.location-indicator {
    background: rgba(0,123,255,0.1);
    border: 1px solid rgba(0,123,255,0.2);
    border-radius: 15px;
}
.empty-state {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Presensi Mengajar @endslot
@endcomponent

@php
    $isIzinApprovedToday = !empty($approvedIzinPresensi);
@endphp

<!-- Header Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg">
                            <div class="avatar-title bg-gradient-primary rounded-circle">
                                <i class="bx bx-book fs-1"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="card-title mb-1">Presensi Mengajar Hari Ini</h4>
                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                    <div class="col-auto">
                        <div class="text-end">
                            <div class="fs-4 fw-bold text-primary">{{ $schedules->count() }}</div>
                            <small class="text-muted">Jadwal Hari Ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($isIzinApprovedToday)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <i class="bx bx-info-circle me-1"></i>
                Anda tercatat <strong>izin (disetujui)</strong> hari ini, sehingga presensi mengajar ditandai sebagai izin.
                @if(!empty($approvedIzinPresensi->keterangan))
                    <div class="small mt-1">{{ \Illuminate\Support\Str::limit((string) $approvedIzinPresensi->keterangan, 180) }}</div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Main Content -->
<div class="row">
    <div class="col-12">
        @if($schedules->isEmpty())
            <!-- Empty State -->
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
            <!-- Schedule Cards - Mobile Optimized -->
            <div class="row g-3">
                @foreach($schedules as $schedule)
                <div class="col-12">
                    @php
                        $scheduleAttendanceStatus = $schedule->attendance->status ?? null;
                        $cardStateClass = $scheduleAttendanceStatus === 'izin'
                            ? 'attendance-izin'
                            : ($schedule->attendance ? 'attendance-success' : ($isIzinApprovedToday ? 'attendance-izin' : 'attendance-pending'));
                        $badgeClass = $scheduleAttendanceStatus === 'izin'
                            ? 'bg-info'
                            : ($schedule->attendance ? 'bg-success' : ($isIzinApprovedToday ? 'bg-info' : 'bg-warning'));
                        $badgeIcon = $scheduleAttendanceStatus === 'izin'
                            ? 'bx-info'
                            : ($schedule->attendance ? 'bx-check' : ($isIzinApprovedToday ? 'bx-info' : 'bx-time'));
                    @endphp
                    <div class="card schedule-card h-100 position-relative {{ $cardStateClass }}">
                        <!-- Status Badge -->
                        <div class="status-badge {{ $badgeClass }}">
                            <i class="bx {{ $badgeIcon }} text-white"></i>
                        </div>

                        <div class="card-body p-4">
                            <!-- Subject Header -->
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">
                                        <i class="bx bx-book-open text-primary me-2"></i>{{ $schedule->subject }}
                                    </h5>
                                    <div class="d-flex align-items-center text-muted small mb-2">
                                        <i class="bx bx-building-house me-1"></i>
                                        {{ $schedule->school->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Class and Time Info -->
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                                <i class="bx bx-group"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Kelas</small>
                                            <span class="fw-medium">{{ $schedule->class_name }}</span>
                                            @if(!empty($schedule->day_marker) && $schedule->day_marker !== 'normal')
                                                <div class="mt-1">
                                                    <span class="badge bg-info text-dark">{{ $schedule->day_marker_label ?? 'Keterangan Hari' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                                <i class="bx bx-time"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Waktu</small>
                                            <span class="fw-medium">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Attendance Status -->
                            @if($schedule->attendance)
                                <div class="alert {{ (($schedule->attendance->status ?? 'hadir') === 'izin') ? 'alert-info' : 'alert-success' }} border-0 rounded-3 p-3 mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bx {{ (($schedule->attendance->status ?? 'hadir') === 'izin') ? 'bx-info-circle' : 'bx-check-circle' }} fs-4 me-3"></i>
                                        <div class="flex-grow-1">
                                            @if(($schedule->attendance->status ?? 'hadir') === 'izin')
                                                <h6 class="mb-1">Izin</h6>
                                            @else
                                                <h6 class="mb-1 text-success">Presensi Berhasil</h6>
                                                <small class="text-muted">Waktu: {{ $schedule->attendance->waktu }}</small>
                                            @endif
                                            @if($schedule->attendance->materi)
                                                <div class="text-muted small mt-1">
                                                    <i class="bx bx-note me-1"></i>Materi: {{ $schedule->attendance->materi }}
                                                </div>
                                            @endif
                                            @if(!is_null($schedule->attendance->present_students) && !is_null($schedule->attendance->class_total_students))
                                                <div class="text-muted small mt-1">
                                                    <i class="bx bx-user-check me-1"></i>Siswa hadir:
                                                    {{ $schedule->attendance->present_students }}/{{ $schedule->attendance->class_total_students }}
                                                    ({{ number_format($schedule->attendance->student_attendance_percentage, 1) }}%)
                                                </div>
                                            @endif
                                        </div>
                                        @if(($schedule->attendance->status ?? 'hadir') !== 'izin')
                                            <div class="ms-2">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary edit-attendance-btn"
                                                    data-attendance='@json([
                                                        "id" => $schedule->attendance->id,
                                                        "schedule_id" => $schedule->id,
                                                        "subject" => $schedule->subject,
                                                        "class_name" => $schedule->class_name,
                                                        "school_name" => $schedule->school->name ?? "N/A",
                                                        "start_time" => $schedule->start_time,
                                                        "end_time" => $schedule->end_time,
                                                        "materi" => $schedule->attendance->materi,
                                                        "present_students" => $schedule->attendance->present_students,
                                                        "class_total_students" => $schedule->attendance->class_total_students,
                                                    ])'
                                                    title="Edit presensi"
                                                >
                                                    <i class="bx bx-edit-alt"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                @if($isIzinApprovedToday)
                                    <div class="alert alert-info border-0 rounded-3 p-3 mb-0">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-info-circle fs-4 me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Izin (Disetujui)</h6>
                                                <small class="text-muted">Anda tidak dapat melakukan presensi mengajar hari ini.</small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                @if(($schedule->day_marker ?? 'normal') === 'libur')
                                    <div class="alert alert-info border-0 rounded-3 p-3 mb-0">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-calendar-x fs-4 me-3"></i>
                                            <div>
                                                <h6 class="mb-1">{{ $schedule->day_marker_label ?? 'Hari Libur' }}</h6>
                                                <small class="text-muted">Presensi mengajar dinonaktifkan untuk kelas ini hari ini.</small>
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
                                    <button type="button" class="btn btn-primary btn-lg w-100 rounded-3 py-3 fw-semibold"
                                            onclick="markAttendance({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}', '{{ $schedule->start_time }}', '{{ $schedule->end_time }}', '{{ $schedule->class_student_count->total_students ?? '' }}', 0)">
                                        <i class="bx bx-check-circle me-2 fs-5"></i> Lakukan Presensi
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-warning btn-lg w-100 rounded-3 py-3 fw-semibold"
                                            onclick="markAttendance({{ $schedule->id }}, '{{ addslashes($schedule->subject) }}', '{{ addslashes($schedule->class_name) }}', '{{ addslashes($schedule->school->name ?? 'N/A') }}', '{{ $schedule->start_time }}', '{{ $schedule->end_time }}', '{{ $schedule->class_student_count->total_students ?? '' }}', 1)">
                                        <i class="bx bx-error-circle me-2 fs-5"></i> Input Manual
                                    </button>
                                    <div class="text-center mt-2">
                                        <small class="text-muted bg-light px-2 py-1 rounded-pill">
                                            <i class="bx bx-info-circle me-1"></i>Waktu mengajar: {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </small>
                                    </div>
                                @endif
                                @endif
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



<!-- Attendance Modal - Mobile Optimized -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-3">
                        <div class="avatar-title bg-white bg-opacity-25 rounded-circle">
                            <i class="bx bx-check-circle fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="attendanceModalLabel">Konfirmasi Presensi Mengajar</h5>
                        <small class="opacity-75">Verifikasi lokasi sebelum presensi</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Schedule Info Card -->
                <div class="card border-primary border-opacity-25 mb-4">
                    <div class="card-header bg-primary bg-opacity-10 border-primary border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-book-open text-primary me-2"></i>
                            <h6 class="mb-0 text-primary">Detail Jadwal Mengajar</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-2">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="bx bx-book"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">Mata Pelajaran</small>
                                        <span id="modal-subject" class="fw-medium"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-2">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                            <i class="bx bx-group"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">Kelas</small>
                                        <span id="modal-class" class="fw-medium"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-2">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                            <i class="bx bx-building-house"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">Sekolah</small>
                                        <span id="modal-school" class="fw-medium"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-2">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                            <i class="bx bx-time"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">Waktu</small>
                                        <span id="modal-time" class="fw-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-primary border-opacity-25 mb-4">
                    <div class="card-header bg-primary bg-opacity-10 border-primary border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-note text-primary me-2"></i>
                            <h6 class="mb-0 text-primary">Materi Mengajar</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <label for="attendanceMateri" class="form-label fw-semibold">Materi atau Topik yang Disampaikan</label>
                        <textarea
                            class="form-control"
                            id="attendanceMateri"
                            rows="3"
                            maxlength="1000"
                            placeholder="Contoh: Persamaan linear satu variabel"
                            required
                        ></textarea>
                        <div class="form-text">Wajib diisi sebelum presensi dikirim.</div>
                    </div>
                </div>

                <div class="card border-primary border-opacity-25 mb-4">
                    <div class="card-header bg-primary bg-opacity-10 border-primary border-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-group text-primary me-2"></i>
                            <h6 class="mb-0 text-primary">Kehadiran Siswa</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div id="classTotalInfo" class="alert alert-light border mb-3"></div>
                        <div class="mb-3" id="classTotalInputGroup">
                            <label for="classTotalStudents" class="form-label fw-semibold">Jumlah siswa di kelas</label>
                            <input type="number" class="form-control" id="classTotalStudents" min="1" max="10000" placeholder="Contoh: 32">
                            <div class="form-text">Diisi sekali jika data jumlah siswa kelas belum tersimpan.</div>
                        </div>
                        <div class="mb-3">
                            <label for="presentStudents" class="form-label fw-semibold">Jumlah siswa hadir</label>
                            <input type="number" class="form-control" id="presentStudents" min="0" max="10000" placeholder="Contoh: 30" required>
                        </div>
                        <div id="studentAttendancePreview" class="alert alert-info mb-0">
                            Isi jumlah siswa hadir untuk melihat persentase.
                        </div>
                    </div>
                </div>

                <!-- Location Status Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-map text-primary me-2"></i>
                                <h6 class="mb-0 text-primary">Verifikasi Lokasi</h6>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="refreshLocation()">
                                <i class="bx bx-refresh me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="locationStatus" class="alert alert-info border-0 rounded-3 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                                <div>
                                    <strong>Mendapatkan lokasi Anda...</strong>
                                    <br><small class="text-muted">Pastikan GPS aktif dan izinkan akses lokasi</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning border-0 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                            <div class="d-flex">
                                <i class="bx bx-error-circle text-warning me-3 fs-4"></i>
                                <div>
                                    <strong class="text-warning">Penting!</strong>
                                    <p class="mb-0 text-muted small">Pastikan Anda berada di dalam area sekolah yang telah ditentukan untuk melakukan presensi mengajar. Presensi hanya bisa dilakukan sesuai jam mengajar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bx bx-x me-2"></i> Batal
                </button>
                <button type="button" class="btn btn-primary btn-lg rounded-pill px-4 fw-semibold" id="confirmAttendanceBtn" disabled>
                    <i class="bx bx-check-circle me-2 fs-5"></i> Ya, Lakukan Presensi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
let currentScheduleId = null;
let currentAttendanceId = null;
let userLocation = null;
let currentClassTotalStudents = null;
let isEditMode = false;
let requiresLocationValidation = true;
let currentForce = false;
let currentForceReason = '';

const allSchedules = @json(
    $schedules->map(fn ($s) => [
        'id' => $s->id,
        'subject' => $s->subject,
        'start_time' => $s->start_time,
        'end_time' => $s->end_time,
    ])->values()
);

function parseTimeToMinutes(value) {
    const parts = String(value ?? '').split(':').map(v => Number(v));
    const hours = Number.isFinite(parts[0]) ? parts[0] : 0;
    const minutes = Number.isFinite(parts[1]) ? parts[1] : 0;
    return (hours * 60) + minutes;
}

function getOverlaps(scheduleId) {
    const base = allSchedules.find(s => String(s.id) === String(scheduleId));
    if (!base) return [];
    const baseStart = parseTimeToMinutes(base.start_time);
    const baseEnd = parseTimeToMinutes(base.end_time);
    return allSchedules.filter(s => {
        if (String(s.id) === String(scheduleId)) return false;
        const start = parseTimeToMinutes(s.start_time);
        const end = parseTimeToMinutes(s.end_time);
        return baseStart < end && baseEnd > start;
    });
}

function getStudentAttendanceNumbers() {
    const total = currentClassTotalStudents || Number($('#classTotalStudents').val() || 0);
    const present = Number($('#presentStudents').val() || -1);

    return {
        total: Number.isInteger(total) ? total : Math.floor(total),
        present: Number.isInteger(present) ? present : Math.floor(present)
    };
}

function updateStudentAttendancePreview() {
    const { total, present } = getStudentAttendanceNumbers();
    const preview = $('#studentAttendancePreview');

    if (!total || total < 1 || present < 0 || !$('#presentStudents').val()) {
        preview.removeClass('alert-success alert-warning').addClass('alert-info')
            .text('Isi jumlah siswa hadir untuk melihat persentase.');
        return;
    }

    if (present > total) {
        preview.removeClass('alert-info alert-success').addClass('alert-warning')
            .text('Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.');
        return;
    }

    const percentage = ((present / total) * 100).toFixed(1);
    preview.removeClass('alert-info alert-warning').addClass('alert-success')
        .text(`Kehadiran siswa: ${present}/${total} (${percentage}%)`);
}

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
        $('#locationStatus').addClass('alert-warning').html('<i class="bx bx-error-circle"></i> ' + message + '<br><small class="text-muted">Presensi hanya bisa dilakukan sesuai jam mengajar.</small>');
        $('#confirmAttendanceBtn').prop('disabled', true);
    } else {
        $('#locationStatus').addClass('alert-danger').html('<i class="bx bx-error"></i> ' + message);
        $('#confirmAttendanceBtn').prop('disabled', true);
    }
}

function markAttendance(scheduleId, subject, className, schoolName, startTime, endTime, classTotalStudents, isManual = 0) {
    const overlaps = getOverlaps(scheduleId);
    const warnings = [];

    if (overlaps.length > 0) {
        const list = overlaps.slice(0, 3).map(o => `${o.subject} (${o.start_time}-${o.end_time})`).join('<br>');
        warnings.push(`<div class="text-start"><b>Jadwal bentrok</b><br>${list}${overlaps.length > 3 ? '<br>...' : ''}</div>`);
    }

    if (Number(isManual) === 1) {
        warnings.push('<div class="text-start"><b>Input manual</b><br>Anda akan menginput presensi di luar jam mengajar. Lanjutkan?</div>');
    }

    const proceed = () => {
        isEditMode = false;
        currentAttendanceId = null;
        requiresLocationValidation = true;
        currentForce = Number(isManual) === 1;
        currentForceReason = currentForce ? 'outside_time' : '';

        currentScheduleId = scheduleId;
        userLocation = null;
        currentClassTotalStudents = classTotalStudents ? Number(classTotalStudents) : null;
        $('#attendanceMateri').val('');
        $('#presentStudents').val('').removeAttr('max');
        $('#classTotalStudents').val('');

        if (currentClassTotalStudents) {
            $('#classTotalInputGroup').hide();
            $('#classTotalInfo')
                .removeClass('alert-warning alert-light')
                .addClass('alert-success')
                .html(`Jumlah siswa kelas sudah tersimpan: <strong>${currentClassTotalStudents} siswa</strong>.`);
            $('#presentStudents').attr('max', currentClassTotalStudents);
        } else {
            $('#classTotalInputGroup').show();
            $('#classTotalInfo')
                .removeClass('alert-success alert-light')
                .addClass('alert-warning')
                .text('Jumlah siswa kelas belum tersimpan. Isi sekali untuk kelas ini.');
        }
        updateStudentAttendancePreview();

        // Update modal content
        $('#modal-subject').text(subject);
        $('#modal-class').text(className);
        $('#modal-school').text(schoolName);
        $('#modal-time').text(startTime + ' - ' + endTime);

        $('#attendanceModal').modal('show');
        updateLocationStatus('loading', 'Mendapatkan lokasi Anda...');

        // Get user location
        getUserLocation().then(location => {
            userLocation = location;

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
    };

    if (warnings.length === 0) {
        proceed();
        return;
    }

    Swal.fire({
        icon: 'warning',
        title: 'Konfirmasi',
        html: warnings.join('<hr class="my-2">'),
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
    }).then(res => {
        if (res.isConfirmed) proceed();
    });
}

function refreshLocation() {
    updateLocationStatus('loading', 'Memperbarui lokasi Anda...');

    getUserLocation().then(location => {
        userLocation = location;

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
    const materi = $('#attendanceMateri').val().trim();
    if (!materi) {
        Swal.fire({ icon: 'warning', title: 'Materi Wajib Diisi', text: 'Materi atau topik yang disampaikan wajib diisi.' });
        return;
    }

    const { total, present } = getStudentAttendanceNumbers();
    if (!total || total < 1) {
        Swal.fire({ icon: 'warning', title: 'Jumlah Siswa Wajib Diisi', text: 'Jumlah siswa yang ada di kelas wajib diisi.' });
        return;
    }

    if (present < 0 || !$('#presentStudents').val()) {
        Swal.fire({ icon: 'warning', title: 'Jumlah Hadir Wajib Diisi', text: 'Jumlah siswa hadir wajib diisi.' });
        return;
    }

    if (present > total) {
        Swal.fire({ icon: 'warning', title: 'Jumlah Tidak Valid', text: 'Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.' });
        return;
    }

    // Update mode (edit) does not require location
    if (isEditMode) {
        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i> Memproses...');

        const updateUrl = `{{ url('/teaching-attendances') }}/${currentAttendanceId}`;
        $.ajax({
            url: updateUrl,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                materi: materi,
                class_total_students: currentClassTotalStudents ? currentClassTotalStudents : total,
                present_students: present
            },
            success: function(response) {
                $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-save me-2"></i> Simpan');
                if (response.success) {
                    $('#attendanceModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 1500 }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: response.message || 'Terjadi kesalahan' });
                }
            },
            error: function(xhr) {
                $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-save me-2"></i> Simpan');
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan perubahan.';
                Swal.fire({ icon: 'error', title: 'Error', text: message });
            }
        });
        return;
    }

    if (!userLocation || !currentScheduleId) {
        Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Lokasi belum didapatkan atau jadwal tidak valid.' });
        return;
    }

    // Check location in polygon before submitting
    checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
        if (!isValid) {
            Swal.fire({ icon: 'warning', title: 'Diluar Area', text: 'Lokasi Anda berada di luar area sekolah.' });
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
                lokasi: 'Presensi Mengajar',
                materi: materi,
                class_total_students: currentClassTotalStudents ? null : total,
                present_students: present,
                force: currentForce ? 1 : 0,
                force_reason: currentForceReason || null
            },
            success: function(response) {
                $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> Ya, Lakukan Presensi');

                if (response.success) {
                    $('#attendanceModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message || 'Presensi mengajar berhasil dicatat!', timer: 1500 }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: response.message || 'Terjadi kesalahan' });
                }
            },
            error: function(xhr) {
                $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> Ya, Lakukan Presensi');

                let message = 'Terjadi kesalahan saat melakukan presensi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({ icon: 'error', title: 'Error', text: message });
            }
        });
    }).catch(error => {
        Swal.fire({ icon: 'error', title: 'Error', text: String(error) });
    });
});

$('#classTotalStudents, #presentStudents').on('input', updateStudentAttendancePreview);

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

$(document).ready(function() {
    // Initialize modal when shown
    $('#attendanceModal').on('shown.bs.modal', function () {
        // Modal initialization code if needed
    });

    $(document).on('click', '.edit-attendance-btn', function() {
        let payload = null;
        try { payload = JSON.parse($(this).attr('data-attendance') || 'null'); } catch (err) {}
        if (!payload || !payload.id) return;

        isEditMode = true;
        currentAttendanceId = payload.id;
        currentScheduleId = payload.schedule_id;
        userLocation = null;
        requiresLocationValidation = false;
        currentForce = false;
        currentForceReason = '';

        currentClassTotalStudents = payload.class_total_students ? Number(payload.class_total_students) : null;

        $('#attendanceMateri').val(payload.materi || '');
        $('#presentStudents').val(payload.present_students ?? '').removeAttr('max');
        $('#classTotalStudents').val(payload.class_total_students ?? '');

        if (currentClassTotalStudents) {
            $('#classTotalInputGroup').hide();
            $('#classTotalInfo')
                .removeClass('alert-warning alert-light')
                .addClass('alert-success')
                .html(`Jumlah siswa kelas sudah tersimpan: <strong>${currentClassTotalStudents} siswa</strong>.`);
            $('#presentStudents').attr('max', currentClassTotalStudents);
        } else {
            $('#classTotalInputGroup').show();
            $('#classTotalInfo')
                .removeClass('alert-success alert-light')
                .addClass('alert-warning')
                .text('Jumlah siswa kelas belum tersimpan. Isi sekali untuk kelas ini.');
        }
        updateStudentAttendancePreview();

        $('#modal-subject').text(payload.subject || '');
        $('#modal-class').text(payload.class_name || '');
        $('#modal-school').text(payload.school_name || '');
        $('#modal-time').text((payload.start_time || '') + ' - ' + (payload.end_time || ''));

        $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-save me-2"></i> Simpan');
        updateLocationStatus('success', 'Mode edit: lokasi tidak perlu divalidasi.', true);

        $('#attendanceModal').modal('show');
    });
});
</script>
@endsection
