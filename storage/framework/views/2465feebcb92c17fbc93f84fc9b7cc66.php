<?php $__env->startSection('title', 'Izin'); ?>
<?php $__env->startSection('subtitle', 'Pengajuan Izin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        .izin-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .izin-action { background: #fff; border-radius: 10px; padding: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04); text-decoration: none; color: #333; }
        .izin-action i { font-size: 20px; color: #0e8549; display:block; margin-bottom:6px; }
        .izin-action .label { font-weight: 600; font-size: 13px; }
    </style>

    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
    </div>

    <h6 class="mb-3">Pengajuan Izin</h6>

    <div class="izin-grid">
        <a href="<?php echo e(route('mobile.izin', ['type' => 'tidak_masuk'])); ?>" class="izin-action">
            <i class="bx bx-user-x"></i>
            <div class="label">Izin Tidak Masuk</div>
        </a>

        <a href="<?php echo e(route('mobile.izin', ['type' => 'sakit'])); ?>" class="izin-action">
            <i class="bx bx-medical"></i>
            <div class="label">Izin Sakit</div>
        </a>

        <a href="<?php echo e(route('mobile.izin', ['type' => 'terlambat'])); ?>" class="izin-action">
            <i class="bx bx-time-five"></i>
            <div class="label">Izin Terlambat</div>
        </a>

        <a href="<?php echo e(route('mobile.izin', ['type' => 'tugas_luar'])); ?>" class="izin-action">
            <i class="bx bx-briefcase"></i>
            <div class="label">Izin Tugas Diluar</div>
        </a>

        <a href="<?php echo e(route('mobile.izin', ['type' => 'cuti'])); ?>" class="izin-action">
            <i class="bx bx-calendar-star"></i>
            <div class="label">Izin Cuti</div>
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/izin.blade.php ENDPATH**/ ?>