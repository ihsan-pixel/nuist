<?php $__env->startSection('title', 'Laporan Akhir Tahun'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/mobile/laporan-akhir-tahun-create.css')); ?>">

<style>
    body {
    background: #f8f9fb url('/images/bg.png') no-repeat center center;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
}
</style>

<!-- Header -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='<?php echo e(route('mobile.profile')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>LAPORAN AKHIR TAHUN</h4>
    <p>Kepala Sekolah/Madrasah</p>
</div>

<!-- Main Container -->
<div class="form-container">
    <!-- Success Alert -->
    <?php if(session('success')): ?>
        <div class="success-alert">
            ✓ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Info Alert -->
    <?php if(session('info')): ?>
        <div class="info-alert">
            ℹ <?php echo e(session('info')); ?>

        </div>
    <?php endif; ?>

    <!-- Warning Alert -->
    <?php if(session('warning')): ?>
        <div class="warning-alert">
            ⚠ <?php echo e(session('warning')); ?>

        </div>
    <?php endif; ?>

    <!-- Reports List Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-list-ul"></i>
            </div>
            <h6 class="section-title">DAFTAR LAPORAN</h6>
        </div>

        <div class="section-content">
            <?php if($laporans->count() > 0): ?>
                <div class="reports-list">
                    <?php $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="report-card">
                            <div class="report-header">
                                <div class="report-title">
                                    <h6><?php echo e($laporan->nama_madrasah); ?></h6>
                                    <span>
                                        Berhasil Terkirim
                                    </span>
                                </div>
                                <div class="report-meta">
                                    <span class="year">Tahun <?php echo e($laporan->tahun_pelaporan); ?></span>
                                    <span class="date"><?php echo e($laporan->created_at ? \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y') : '-'); ?></span>
                                </div>
                            </div>
                            <div class="report-actions">
                                <a href="<?php echo e(route('mobile.laporan-akhir-tahun.show', $laporan->id)); ?>" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="bx bx-show"></i>
                                    Lihat
                                </a>
                                <a href="<?php echo e(route('mobile.laporan-akhir-tahun.edit', $laporan->id)); ?>" class="btn btn-sm btn-outline-warning" title="Edit Laporan">
                                    <i class="bx bx-edit"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bx bx-file-blank"></i>
                    </div>
                    <h5>Belum Ada Laporan</h5>
                    <p>Anda belum membuat laporan akhir tahun. Mulai buat laporan pertama Anda sekarang.</p>
                    <a href="<?php echo e(route('mobile.laporan-akhir-tahun.create')); ?>" class="btn btn-primary">
                        <i class="bx bx-plus"></i>
                        Buat Laporan Baru
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/laporan-akhir-tahun/index.blade.php ENDPATH**/ ?>