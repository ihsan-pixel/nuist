<?php $__env->startSection('title', 'Pengaturan Presensi'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Presensi Admin <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Pengaturan Presensi <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-cog me-2"></i>Pengaturan Presensi
                </h4>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Informasi:</strong> Sistem presensi sekarang menggunakan jadwal waktu tetap berdasarkan Hari KBM madrasah. Pengaturan waktu tidak lagi dapat diubah secara manual.
                </div>

                <div class="row gx-0">
                    <?php $__currentLoopData = $hariKbmOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 mb-3 px-1">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-calendar me-2"></i><?php echo e($label); ?>

                                    </h6>
                                </div>
                                <div class="card-body py-3">
                                    <?php
                                        $timeRanges = ($key == '5') ? $timeRanges5 : $timeRanges6;
                                    ?>

                                    <div class="row">
                                        <!-- Presensi Masuk Settings -->
                                        <div class="col-6">
                                            <h6 class="text-primary mb-2">
                                                <i class="bx bx-log-in-circle me-1"></i>Masuk
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Mulai
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="<?php echo e($timeRanges['masuk_start']); ?>" readonly>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Akhir
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="<?php echo e($timeRanges['masuk_end']); ?>" readonly>
                                                </div>
                                            </div>
                                            <small class="text-muted small">Terlambat setelah akhir</small>
                                        </div>

                                        <!-- Presensi Pulang Settings -->
                                        <div class="col-6">
                                            <h6 class="text-success mb-2">
                                                <i class="bx bx-log-out-circle me-1"></i>Pulang
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Mulai
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="<?php echo e($timeRanges['pulang_start']); ?>" readonly>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Akhir
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="<?php echo e($timeRanges['pulang_end']); ?>" readonly>
                                                </div>
                                            </div>
                                            <small class="text-muted small">Tidak boleh setelah akhir</small>
                                        </div>
                                    </div>

                                    <?php if($key == '5'): ?>
                                    <div class="mt-3">
                                        <small class="text-warning">
                                            <i class="bx bx-info-circle me-1"></i>
                                            <strong>Catatan:</strong> Untuk hari Jumat, waktu mulai presensi pulang adalah 14:00.
                                        </small>
                                    </div>
                                    <?php elseif($key == '6'): ?>
                                    <div class="mt-3">
                                        <small class="text-info">
                                            <i class="bx bx-info-circle me-1"></i>
                                            <strong>Catatan:</strong> Untuk hari Sabtu, waktu mulai presensi pulang adalah 12:00. Hari lainnya mulai pukul 13:00.
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php if($loop->iteration % 2 == 0 && !$loop->last): ?>
                            </div><div class="row gx-0">
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="bx bx-error-circle me-2"></i>
                    <strong>Pengecualian:</strong> Pengguna dengan "Pemenuhan Beban Kerja Lain" = Ya dapat melakukan presensi masuk dan keluar tanpa batasan waktu dan dapat presensi di madrasah tambahan.
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/presensi_admin/settings.blade.php ENDPATH**/ ?>