<?php $__env->startSection('title'); ?> Presensi <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Presensi <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-check-square me-2"></i>Data Presensi
                </h4>
            </div>
            <div class="card-body">

                <?php if(auth()->user()->role === 'tenaga_pendidik'): ?>
                <div class="mb-3 d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('presensi.create')); ?>" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Presensi Hari Ini
                    </a>
                </div>
                <?php endif; ?>

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

                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <?php if(in_array(auth()->user()->role, ['super_admin', 'admin'])): ?>
                                <th>Nama Tenaga Pendidik</th>
                                <th>Madrasah</th>
                                <?php endif; ?>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($presensis->firstItem() + $index); ?></td>
                                    <?php if(in_array(auth()->user()->role, ['super_admin', 'admin'])): ?>
                                    <td><?php echo e($presensi->user->name); ?></td>
                                    <td><?php echo e($presensi->user->madrasah?->name ?? '-'); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($presensi->tanggal->format('d/m/Y')); ?></td>
                                    <td><?php echo e($presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : '-'); ?></td>
                                    <td><?php echo e($presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : '-'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger'))); ?>">
                                            <?php echo e(ucfirst($presensi->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($presensi->lokasi ?? '-'); ?></td>
                                    <td><?php echo e($presensi->keterangan ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="<?php echo e(auth()->user()->role === 'tenaga_pendidik' ? '7' : '9'); ?>" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block text-center" role="alert">
                                            <i class="bx bx-info-circle bx-lg me-2"></i>
                                            <strong>Belum ada data presensi</strong><br>
                                            <small>Silakan lakukan presensi terlebih dahulu.</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($presensis->hasPages()): ?>
                <div class="d-flex justify-content-center">
                    <?php echo e($presensis->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apk_nuist\resources\views/presensi/index.blade.php ENDPATH**/ ?>