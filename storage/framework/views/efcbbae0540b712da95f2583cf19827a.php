<?php $__env->startSection('title', 'Presensi Mengajar'); ?>

<?php $__env->startSection('vendor-script'); ?>
<!-- SweetAlert2 -->
<link href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Presensi Mengajar <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

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
                        <p class="text-muted mb-0"><?php echo e(\Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY')); ?></p>
                    </div>
                    <div class="col-auto">
                        <div class="text-end">
                            <div class="fs-4 fw-bold text-primary"><?php echo e($schedules->count()); ?></div>
                            <small class="text-muted">Jadwal Hari Ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-12">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedules->isEmpty()): ?>
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
        <?php else: ?>
            <!-- Schedule Cards - Mobile Optimized -->
            <div class="row g-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12">
                    <div class="card schedule-card h-100 position-relative <?php echo e($schedule->attendance ? 'attendance-success' : 'attendance-pending'); ?>">
                        <!-- Status Badge -->
                        <div class="status-badge <?php echo e($schedule->attendance ? 'bg-success' : 'bg-warning'); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedule->attendance): ?>
                                <i class="bx bx-check text-white"></i>
                            <?php else: ?>
                                <i class="bx bx-time text-white"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="card-body p-4">
                            <!-- Subject Header -->
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">
                                        <i class="bx bx-book-open text-primary me-2"></i><?php echo e($schedule->subject); ?>

                                    </h5>
                                    <div class="d-flex align-items-center text-muted small mb-2">
                                        <i class="bx bx-building-house me-1"></i>
                                        <?php echo e($schedule->school->name ?? 'N/A'); ?>

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
                                            <span class="fw-medium"><?php echo e($schedule->class_name); ?></span>
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
                                            <span class="fw-medium"><?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Attendance Status -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedule->attendance): ?>
                                <div class="alert alert-success border-0 rounded-3 p-3 mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-check-circle fs-4 me-3"></i>
                                        <div>
                                            <h6 class="mb-1 text-success">Presensi Berhasil</h6>
                                            <small class="text-muted">Waktu: <?php echo e($schedule->attendance->waktu); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php
                                    $currentTime = \Carbon\Carbon::now('Asia/Jakarta');
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
                                    $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                                    $isWithinTime = $currentTime->between($startTime, $endTime);
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isWithinTime): ?>
                                    <button type="button" class="btn btn-primary btn-lg w-100 rounded-3 py-3 fw-semibold"
                                            onclick="markAttendance(<?php echo e($schedule->id); ?>, '<?php echo e(addslashes($schedule->subject)); ?>', '<?php echo e(addslashes($schedule->class_name)); ?>', '<?php echo e(addslashes($schedule->school->name ?? 'N/A')); ?>', '<?php echo e($schedule->start_time); ?>', '<?php echo e($schedule->end_time); ?>')">
                                        <i class="bx bx-check-circle me-2 fs-5"></i> Lakukan Presensi
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-secondary btn-lg w-100 rounded-3 py-3" disabled>
                                        <i class="bx bx-time me-2 fs-5"></i> Diluar Waktu Mengajar
                                    </button>
                                    <div class="text-center mt-2">
                                        <small class="text-muted bg-light px-2 py-1 rounded-pill">
                                            <i class="bx bx-info-circle me-1"></i>Waktu mengajar: <?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>

                                        </small>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
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

function markAttendance(scheduleId, subject, className, schoolName, startTime, endTime) {
    currentScheduleId = scheduleId;
    userLocation = null;

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
    if (!userLocation || !currentScheduleId) {
        alert('Lokasi belum didapatkan atau jadwal tidak valid.');
        return;
    }

    // Check location in polygon before submitting
    checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
        if (!isValid) {
            alert('Lokasi Anda berada di luar area sekolah. Pastikan Anda berada di dalam lingkungan madrasah untuk melakukan presensi.');
            return;
        }

        // Disable button to prevent double submission
        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i> Memproses...');

        // Send AJAX request
        $.ajax({
            url: '<?php echo e(route("teaching-attendances.store")); ?>',
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                teaching_schedule_id: currentScheduleId,
                latitude: userLocation.latitude,
                longitude: userLocation.longitude,
                lokasi: 'Presensi Mengajar'
            },
            success: function(response) {
                $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> Ya, Lakukan Presensi');

                if (response.success) {
                    $('#attendanceModal').modal('hide');
                    alert('Presensi mengajar berhasil dicatat!');
                    location.reload();
                } else {
                    alert('Gagal: ' + response.message);
                }
            },
            error: function(xhr) {
                $('#confirmAttendanceBtn').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> Ya, Lakukan Presensi');

                let message = 'Terjadi kesalahan saat melakukan presensi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert('Error: ' + message);
            }
        });
    }).catch(error => {
        alert('Error: ' + error);
    });
});

// Function to check location in polygon via AJAX
function checkLocationInPolygon(lat, lng, scheduleId) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '<?php echo e(route("teaching-attendances.check-location")); ?>',
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
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
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/teaching-attendances/index.blade.php ENDPATH**/ ?>