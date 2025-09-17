<?php $__env->startSection('title'); ?> Presensi Hari Ini <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Presensi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Presensi Hari Ini <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<!-- leaflet Css -->
<link href="<?php echo e(URL::asset('build/libs/leaflet/leaflet.css')); ?>" rel="stylesheet" type="text/css" />
<!-- SweetAlert2 Css -->
<link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
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

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bx bx-log-in-circle me-2"></i>
                                    <?php echo e($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk'); ?>

                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="location-info" class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Mendapatkan lokasi Anda...
                                    </div>
                                </div>

                                <!-- Map Section -->
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Anda Saat Ini</label>
                                    <div id="map" style="height: 300px; border-radius: 5px;"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Koordinat Lokasi</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" id="latitude" class="form-control" placeholder="Latitude" readonly>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" id="longitude" class="form-control" placeholder="Longitude" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat Lokasi</label>
                                    <input type="text" id="lokasi" class="form-control" placeholder="Alamat akan muncul otomatis" readonly>
                                </div>

                                <button type="button" id="btn-presensi" class="btn btn-primary btn-lg w-100" <?php echo e(($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : ''); ?>>
                                    <i class="bx bx-check-circle me-2"></i>
                                    <?php echo e($isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk')); ?>

                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Presensi</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" value="<?php echo e(auth()->user()->name); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Madrasah</label>
                                    <input type="text" class="form-control" value="<?php echo e(auth()->user()->madrasah?->name ?? 'Tidak ada data'); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="text" class="form-control" value="<?php echo e(\Carbon\Carbon::now()->format('d F Y')); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Waktu Sekarang</label>
                                    <input type="text" id="current-time" class="form-control" readonly>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="bx bx-error-circle me-2"></i>
                                    <strong>Penting!</strong><br>
                                    Pastikan Anda berada dalam radius 20 meter dari lokasi madrasah untuk melakukan presensi.
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
<script src="<?php echo e(URL::asset('build/libs/leaflet/leaflet.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script>
$(document).ready(function() {
    let latitude, longitude, lokasi;

    // Update waktu sekarang setiap detik
    function updateTime() {
        const now = new Date();
        $('#current-time').val(now.toLocaleTimeString('id-ID'));
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Mendapatkan lokasi pengguna
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            // Mendapatkan alamat dari koordinat
            getAddressFromCoordinates(latitude, longitude);

            $('#location-info').html(`
                <div class="alert alert-success">
                    <i class="bx bx-check-circle me-2"></i>
                    Lokasi berhasil didapatkan!
                </div>
            `);

            // Initialize Leaflet map
            var map = L.map('map').setView([latitude, longitude], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([latitude, longitude]).addTo(map)
                .bindPopup('Lokasi Anda saat ini')
                .openPopup();

        }, function(error) {
            $('#location-info').html(`
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle me-2"></i>
                    Gagal mendapatkan lokasi: ${error.message}
                </div>
            `);
        });
    } else {
        $('#location-info').html(`
            <div class="alert alert-danger">
                <i class="bx bx-error-circle me-2"></i>
                Browser Anda tidak mendukung geolocation.
            </div>
        `);
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

        // Prepare data for AJAX
        let postData = {
            _token: '<?php echo e(csrf_token()); ?>',
            latitude: latitude,
            longitude: longitude,
            lokasi: lokasi
        };

        // Disable button and show loading
        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

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
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apk_nuist\resources\views/presensi/create.blade.php ENDPATH**/ ?>