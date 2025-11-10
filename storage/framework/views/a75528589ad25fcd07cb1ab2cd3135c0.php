<?php $__env->startSection('title', 'Laporan'); ?>
<?php $__env->startSection('subtitle', 'Laporan Presensi'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
    </div>

<div class="row g-2">
    <div class="col-6">
    <a href="<?php echo e(route('mobile.riwayat-presensi')); ?>" class="text-decoration-none">
            <div class="card shadow-sm text-center p-3">
                <div class="avatar-md mx-auto mb-2">
                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bx bx-history fs-3"></i>
                    </div>
                </div>
                <h6 class="mb-0">Aktifitas Presensi</h6>
                <p class="text-muted small mb-0">Riwayat presensi harian Anda (bulan ini)</p>
            </div>
        </a>
    </div>

    <div class="col-6">
    <a href="<?php echo e(route('mobile.laporan.mengajar')); ?>" class="text-decoration-none">
            <div class="card shadow-sm text-center p-3">
                <div class="avatar-md mx-auto mb-2">
                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bx bx-chalkboard fs-3"></i>
                    </div>
                </div>
                <h6 class="mb-0">Presensi Mengajar</h6>
                <p class="text-muted small mb-0">Laporan dan presensi sesuai jadwal mengajar</p>
            </div>
        </a>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/laporan.blade.php ENDPATH**/ ?>