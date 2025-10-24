<?php $__env->startSection('title'); ?> Presensi Hari Ini <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Presensi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Presensi Hari Ini <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<!-- leaflet Css -->
<link href="<?php echo e(asset('build/libs/leaflet/leaflet.css')); ?>" rel="stylesheet" type="text/css" />
<!-- SweetAlert2 Css -->
<link href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.map-container {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.info-item {
    transition: all 0.2s ease;
}
.info-item:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
}
.schedule-card {
    transition: all 0.3s ease;
}
.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
<?php $__env->stopSection(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Presensi Hari Ini - <?php echo e(\Carbon\Carbon::now()->format('d F Y')); ?></h4>
            </div>
            <div class="card-body">

                <?php if($isHoliday): ?>
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">
                        <i class="bx bx-calendar-x me-2"></i>
                        Hari Libur: <?php echo e($holiday->name); ?>

                    </h4>
                    <p><?php echo e($holiday->description ?? 'Hari ini adalah hari libur nasional atau tanggal merah.'); ?></p>
                    <p class="mb-0">Presensi tidak diperlukan pada hari libur.</p>
                </div>
                <?php endif; ?>

                <?php if($presensiHariIni): ?>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Presensi Masuk Sudah Dicatat!</h4>
                    <p>Waktu Masuk: <strong><?php echo e($presensiHariIni->waktu_masuk->format('H:i:s')); ?></strong></p>
                    <p>Lokasi: <strong><?php echo e($presensiHariIni->lokasi ?? 'Tidak tercatat'); ?></strong></p>
                    <?php if($presensiHariIni->waktu_keluar): ?>
                    <p>Waktu Keluar: <strong><?php echo e($presensiHariIni->waktu_keluar->format('H:i:s')); ?></strong></p>
                    <div class="alert alert-info mt-3">
                        <strong>Presensi hari ini sudah lengkap!</strong>
                    </div>
                    <?php else: ?>
                    <hr>
                    <p class="mb-0">Silakan lakukan presensi keluar jika sudah selesai bekerja.</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Elegant Mobile-First Layout -->
                <div class="row g-4">
                    <!-- Main Presensi Card - Full width on mobile -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-gradient-primary text-white border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-white bg-opacity-25 rounded-circle">
                                            <i class="bx bx-<?php echo e($presensiHariIni ? 'log-out-circle' : 'log-in-circle'); ?> fs-4"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <?php echo e($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk'); ?>

                                        </h5>
                                        <small class="opacity-75"><?php echo e(\Carbon\Carbon::now()->format('l, d F Y')); ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <!-- Location Status -->
                                <div id="location-info" class="mb-4">
                                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-center">
                                        <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                                        <div>
                                            <strong>Mendapatkan lokasi Anda...</strong>
                                            <br><small class="text-muted">Pastikan GPS aktif dan izinkan akses lokasi</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Container -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bx bx-map text-primary me-2"></i>
                                        <label class="form-label fw-semibold mb-0">Lokasi Anda Saat Ini</label>
                                    </div>
                                    <div class="map-container rounded-3 overflow-hidden shadow-sm">
                                        <div id="map" style="height: 280px; width: 100%;"></div>
                                    </div>
                                </div>

                                <!-- Coordinates -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bx bx-target-lock text-success me-2"></i>
                                        <label class="form-label fw-semibold mb-0">Koordinat Lokasi</label>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bx bx-up text-muted"></i>
                                                </span>
                                                <input type="text" id="latitude" class="form-control form-control-lg border-start-0 ps-0"
                                                       placeholder="Latitude" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bx bx-right text-muted"></i>
                                                </span>
                                                <input type="text" id="longitude" class="form-control form-control-lg border-start-0 ps-0"
                                                       placeholder="Longitude" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bx bx-home text-info me-2"></i>
                                        <label class="form-label fw-semibold mb-0">Alamat Lokasi</label>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="bx bx-map-pin text-muted"></i>
                                        </span>
                                        <input type="text" id="lokasi" class="form-control form-control-lg"
                                               placeholder="Alamat akan muncul otomatis" readonly>
                                    </div>
                                </div>

                                <!-- Presensi Button -->
                                <div class="d-grid">
                                    <button type="button" id="btn-presensi"
                                            class="btn btn-<?php echo e($isHoliday ? 'secondary' : 'primary'); ?> btn-lg py-3 rounded-3 shadow-sm fw-semibold"
                                            <?php echo e(($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : ''); ?>>
                                        <i class="bx bx-<?php echo e($isHoliday ? 'calendar-x' : 'check-circle'); ?> me-2 fs-5"></i>
                                        <?php echo e($isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Information Cards - Stacked on mobile -->
                    <div class="col-12">
                        <div class="row g-3">
                            <!-- User Info Card -->
                            <div class="col-12">
                                <div class="card shadow-sm border-0 bg-light">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-user-circle text-primary me-2 fs-4"></i>
                                            <h6 class="card-title mb-0 fw-semibold">Informasi Personal</h6>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Nama</span>
                                                    <span class="fw-medium"><?php echo e(auth()->user()->name); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Madrasah</span>
                                                    <span class="fw-medium"><?php echo e(auth()->user()->madrasah?->name ?? 'Tidak ada data'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Status Kepegawaian</span>
                                                    <span class="fw-medium"><?php echo e(auth()->user()->statusKepegawaian?->name ?? 'Belum diatur'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Time Info Card -->
                            <div class="col-12">
                                <div class="card shadow-sm border-0 bg-light">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-time text-success me-2 fs-4"></i>
                                            <h6 class="card-title mb-0 fw-semibold">Informasi Waktu</h6>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Tanggal</span>
                                                    <span class="fw-medium"><?php echo e(\Carbon\Carbon::now()->format('d F Y')); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="info-item d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                                                    <span class="text-muted small">Waktu Sekarang</span>
                                                    <span class="fw-medium" id="current-time"><?php echo e(\Carbon\Carbon::now()->format('H:i:s')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Info Card -->
                            <?php if(isset($timeRanges) && $timeRanges): ?>
                            <div class="col-12">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bx bx-calendar-check text-warning me-2 fs-4"></i>
                                            <h6 class="card-title mb-0 fw-semibold">Jadwal Presensi</h6>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <div class="schedule-card bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-3 text-center">
                                                    <i class="bx bx-log-in-circle text-primary fs-3 mb-2"></i>
                                                    <h6 class="text-primary mb-2">Presensi Masuk</h6>
                                                    <p class="mb-1 fw-semibold"><?php echo e($timeRanges['masuk_start']); ?> - <?php echo e($timeRanges['masuk_end']); ?></p>
                                                    <small class="text-muted">Terlambat setelah 07:00</small>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="schedule-card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3 text-center">
                                                    <i class="bx bx-log-out-circle text-success fs-3 mb-2"></i>
                                                    <h6 class="text-success mb-2">Presensi Pulang</h6>
                                                    <p class="mb-1 fw-semibold"><?php echo e($timeRanges['pulang_start']); ?> - <?php echo e($timeRanges['pulang_end']); ?></p>
                                                    <small class="text-muted">Waktu pulang normal</small>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(auth()->user()->madrasah && auth()->user()->madrasah->hari_kbm == '6'): ?>
                                        <div class="mt-3 p-2 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2">
                                            <small class="text-info">
                                                <i class="bx bx-info-circle me-1"></i>
                                                <strong>Catatan:</strong> Untuk hari Sabtu, waktu mulai presensi pulang adalah 12:00. Hari lainnya mulai pukul 13:00.
                                            </small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-warning border-0 rounded-3">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Pengaturan Presensi:</strong> Hari KBM madrasah Anda belum diatur. Silakan hubungi administrator untuk mengaturnya.
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Important Notice -->
                            <div class="col-12">
                                <div class="alert alert-danger border-0 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                    <div class="d-flex">
                                        <i class="bx bx-error-circle text-danger me-3 fs-4"></i>
                                        <div>
                                            <strong class="text-danger">Penting!</strong>
                                            <p class="mb-0 text-muted small">Pastikan Anda berada dalam lingkungan Madrasah/Sekolah untuk melakukan presensi.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for warning -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="warningModalLabel">Peringatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="warningMessage">
        <!-- Warning message will be injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/leaflet/leaflet.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script>
$(document).ready(function() {
    let latitude, longitude, lokasi;

    // Update waktu sekarang setiap detik
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        $('#current-time').text(timeString);
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Mendapatkan lokasi pengguna (Reading 1) ketika masuk ke halaman create
    if (navigator.geolocation) {
        // Tampilkan loading
        $('#location-info').html(`
            <div class="alert alert-info border-0 rounded-3 d-flex align-items-center">
                <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                <div>
                    <strong>Mendapatkan lokasi Anda...</strong>
                    <br><small class="text-muted">Proses ini akan selesai dalam beberapa detik</small>
                </div>
            </div>
        `);

        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            // Store reading 1 in sessionStorage (menu entry reading)
            sessionStorage.setItem('reading1_latitude', position.coords.latitude);
            sessionStorage.setItem('reading1_longitude', position.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            // Mendapatkan alamat dari koordinat secara paralel (tidak blocking)
            getAddressFromCoordinates(latitude, longitude);

            $('#location-info').html(`
                <div class="alert alert-success border-0 rounded-3 d-flex align-items-center">
                    <i class="bx bx-check-circle me-3 fs-4"></i>
                    <div>
                        <strong>Lokasi berhasil didapatkan!</strong>
                    </div>
                </div>
            `);

            // Initialize Leaflet map dengan loading yang lebih cepat
            var map = L.map('map', {
                center: [latitude, longitude],
                zoom: 15,
                zoomControl: true,
                scrollWheelZoom: false
            });

            // Gunakan tile layer yang lebih cepat loading
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
                updateWhenIdle: true
            }).addTo(map);

            var marker = L.marker([latitude, longitude]).addTo(map)
                .bindPopup('Lokasi Anda saat ini')
                .openPopup();

            // Enable map setelah loading selesai
            setTimeout(function() {
                map.invalidateSize();
            }, 100);

        }, function(error) {
            $('#location-info').html(`
                <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center">
                    <i class="bx bx-error-circle me-3 fs-4"></i>
                    <div>
                        <strong>Gagal mendapatkan lokasi</strong>
                        <br><small class="text-muted">${error.message}</small>
                    </div>
                </div>
            `);

            // Initialize map with default location on error
            var map = L.map('map', {
                center: [-7.7956, 110.3695],
                zoom: 10,
                zoomControl: true,
                scrollWheelZoom: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
                updateWhenIdle: true
            }).addTo(map);

            var marker = L.marker([-7.7956, 110.3695]).addTo(map)
                .bindPopup('Tidak dapat mendapatkan lokasi Anda')
                .openPopup();

            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        }, {
            enableHighAccuracy: true,
            timeout: 10000, // Timeout 10 detik
            maximumAge: 30000 // Cache lokasi selama 30 detik
        });
    } else {
        $('#location-info').html(`
            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center">
                <i class="bx bx-error-circle me-3 fs-4"></i>
                <div>
                    <strong>Browser tidak mendukung GPS</strong>
                    <br><small class="text-muted">Silakan gunakan browser modern dengan dukungan GPS</small>
                </div>
            </div>
        `);

        // Initialize map with default location
        var map = L.map('map', {
            center: [-7.7956, 110.3695],
            zoom: 10,
            zoomControl: true,
            scrollWheelZoom: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
            updateWhenIdle: true
        }).addTo(map);

        var marker = L.marker([-7.7956, 110.3695]).addTo(map)
            .bindPopup('Browser tidak mendukung GPS')
            .openPopup();

        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    }

    // Fungsi untuk mendapatkan alamat dari koordinat
    function getAddressFromCoordinates(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    lokasi = data.display_name;
                    $('#lokasi').val(lokasi);
                }
            })
            .catch(error => {
                console.error('Error getting address:', error);
                $('#lokasi').val('Tidak dapat mendapatkan alamat');
            });
    }

    // Handle tombol presensi
    $('#btn-presensi').click(function() {
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Lokasi belum didapatkan. Pastikan GPS aktif dan izinkan akses lokasi.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        // Disable button and show loading
        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Get second location reading (Reading 2) when button is clicked
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Get reading1 from sessionStorage
                let reading1Lat = sessionStorage.getItem('reading1_latitude');
                let reading1Lng = sessionStorage.getItem('reading1_longitude');
                let reading1Timestamp = sessionStorage.getItem('reading1_timestamp');

                // Create second reading
                let reading2Lat = position.coords.latitude;
                let reading2Lng = position.coords.longitude;
                let reading2Timestamp = Date.now();

                // Prepare data for AJAX with two readings
                let postData = {
                    _token: '<?php echo e(csrf_token()); ?>',
                    latitude: reading2Lat,
                    longitude: reading2Lng,
                    lokasi: lokasi,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed,
                    device_info: navigator.userAgent,
                    location_readings: JSON.stringify([
                        {
                            latitude: parseFloat(reading1Lat),
                            longitude: parseFloat(reading1Lng),
                            timestamp: parseInt(reading1Timestamp)
                        },
                        {
                            latitude: reading2Lat,
                            longitude: reading2Lng,
                            timestamp: reading2Timestamp
                        }
                    ])
                };

                $.ajax({
                    url: '<?php echo e(route("presensi.store")); ?>',
                    method: 'POST',
                    data: postData,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: response.message,
                                confirmButtonText: 'Oke'
                            });
                            // Re-enable button and reset text
                            $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> <?php echo e($presensiHariIni ? "Presensi Keluar" : "Presensi Masuk"); ?>');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan tidak diketahui',
                            confirmButtonText: 'Oke'
                        });
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> <?php echo e($presensiHariIni ? "Presensi Keluar" : "Presensi Masuk"); ?>');
                    }
                });
            },
            function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Gagal mendapatkan lokasi: ' + error.message,
                    confirmButtonText: 'Oke'
                });
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i> <?php echo e($presensiHariIni ? "Presensi Keluar" : "Presensi Masuk"); ?>');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000, // Timeout 5 detik untuk GPS ketiga
                maximumAge: 10000 // Cache lokasi selama 10 detik
            }
        );
    });




});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/presensi/create.blade.php ENDPATH**/ ?>