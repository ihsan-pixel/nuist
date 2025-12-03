<?php $__env->startSection('title', 'Profile Madrasah/Sekolah'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Profile Madrasah/Sekolah <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-school me-2"></i>Profile Madrasah/Sekolah
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

                <form method="GET" action="<?php echo e(route('madrasah.profile')); ?>" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama madrasah..." value="<?php echo e($search ?? ''); ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="kabupaten" class="form-select">
                                <option value="">Pilih Kabupaten</option>
                                <option value="Kabupaten Bantul" <?php echo e(($kabupaten ?? '') == 'Kabupaten Bantul' ? 'selected' : ''); ?>>Kabupaten Bantul</option>
                                <option value="Kabupaten Gunungkidul" <?php echo e(($kabupaten ?? '') == 'Kabupaten Gunungkidul' ? 'selected' : ''); ?>>Kabupaten Gunungkidul</option>
                                <option value="Kabupaten Kulon Progo" <?php echo e(($kabupaten ?? '') == 'Kabupaten Kulon Progo' ? 'selected' : ''); ?>>Kabupaten Kulon Progo</option>
                                <option value="Kabupaten Sleman" <?php echo e(($kabupaten ?? '') == 'Kabupaten Sleman' ? 'selected' : ''); ?>>Kabupaten Sleman</option>
                                <option value="Kota Yogyakarta" <?php echo e(($kabupaten ?? '') == 'Kota Yogyakarta' ? 'selected' : ''); ?>>Kota Yogyakarta</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </div>
                </form>

                <?php if($madrasahs->isEmpty()): ?>
                <div class="text-center p-4">
                    <div class="alert alert-info d-inline-block" role="alert">
                        <i class="bx bx-info-circle bx-lg me-2"></i>
                        <strong>Belum ada data madrasah</strong><br>
                        <small>Silakan tambahkan data madrasah terlebih dahulu melalui menu Master Data.</small>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card project-card" style="border: none; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03); border-radius: 0.75rem; overflow: hidden;">
                            <?php if($madrasah->logo): ?>
                            <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" class="card-img-top" alt="<?php echo e($madrasah->name); ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bx bx-school bx-lg text-muted"></i>
                            </div>
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold mb-2"><?php echo e($madrasah->name); ?></h5>
                                <p class="card-text text-muted small mb-3"><?php echo e(Str::limit($madrasah->alamat ?? 'Alamat tidak tersedia', 100)); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="<?php echo e(route('madrasah.detail', $madrasah->id)); ?>" class="btn btn-success btn-sm rounded-pill px-3">
                                        <i class="bx bx-user me-1"></i>
                                        Lihat Profile <?php echo e($madrasah->tenaga_pendidik_count); ?>

                                    </a>
                                    <div class="d-flex gap-1">
                                        <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script>
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/masterdata/madrasah/profile.blade.php ENDPATH**/ ?>