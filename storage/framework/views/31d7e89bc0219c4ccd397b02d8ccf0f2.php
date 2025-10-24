<?php $__env->startSection('title'); ?> Presensi <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Presensi <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
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
                <!-- Mobile-optimized action buttons -->
                <div class="mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?php echo e(route('mobile.presensi')); ?>" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="bx bx-plus me-2"></i>Presensi Hari Ini
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo e(route('izin.create')); ?>" class="btn btn-warning btn-lg w-100 py-3">
                                <i class="bx bx-upload me-2"></i>Upload Surat Izin
                            </a>
                        </div>
                    </div>
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

                <!-- Mobile-friendly card layout for tenaga_pendidik, table for admin roles -->
                <?php if(auth()->user()->role === 'tenaga_pendidik'): ?>
                <div class="row g-3">
                    <?php $__empty_1 = true; $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-<?php echo e($presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger'))); ?> rounded-circle">
                                                <i class="bx bx-<?php echo e($presensi->status === 'hadir' ? 'check' : ($presensi->status === 'izin' ? 'calendar-x' : ($presensi->status === 'sakit' ? 'medical' : 'x'))); ?> fs-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1"><?php echo e($presensi->tanggal->format('d F Y')); ?></h6>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <small class="badge bg-<?php echo e($presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger'))); ?>">
                                                <?php echo e(ucfirst($presensi->status)); ?>

                                            </small>
                                            <?php if($presensi->status === 'izin' && $presensi->status_izin): ?>
                                            <small class="badge bg-<?php echo e($presensi->status_izin === 'approved' ? 'success' : ($presensi->status_izin === 'rejected' ? 'danger' : 'secondary')); ?>">
                                                <?php echo e(ucfirst($presensi->status_izin)); ?>

                                            </small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row g-2 text-muted small">
                                            <div class="col-6">
                                                <i class="bx bx-log-in me-1"></i>Masuk: <?php echo e($presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : '-'); ?>

                                            </div>
                                            <div class="col-6">
                                                <i class="bx bx-log-out me-1"></i>Keluar: <?php echo e($presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : '-'); ?>

                                            </div>
                                            <?php if($presensi->lokasi): ?>
                                            <div class="col-12">
                                                <i class="bx bx-map me-1"></i><?php echo e($presensi->lokasi); ?>

                                            </div>
                                            <?php endif; ?>
                                            <?php if($presensi->keterangan): ?>
                                            <div class="col-12">
                                                <i class="bx bx-note me-1"></i><?php echo e($presensi->keterangan); ?>

                                            </div>
                                            <?php endif; ?>
                                            <?php if($presensi->status === 'izin' && $presensi->surat_izin_path): ?>
                                            <div class="col-12">
                                                <a href="<?php echo e(asset('storage/app/public/'.$presensi->surat_izin_path)); ?>" target="_blank" class="text-info">
                                                    <i class="bx bx-file me-1"></i>Lihat Surat Izin
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-body text-center py-5">
                                <i class="bx bx-info-circle bx-lg text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada data presensi</h6>
                                <p class="text-muted small">Silakan lakukan presensi terlebih dahulu.</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <!-- Table layout for admin roles -->
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Tenaga Pendidik</th>
                                <th>Madrasah</th>
                                <th>Status Kepegawaian</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Detail Izin</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <?php if($presensis instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator): ?>
                                            <?php echo e($presensis->firstItem() + $index); ?>

                                        <?php else: ?>
                                            <?php echo e($index + 1); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($presensi->user->name); ?></td>
                                    <td><?php echo e($presensi->user->madrasah?->name ?? '-'); ?></td>
                                    <td><?php echo e($presensi->statusKepegawaian->name ?? '-'); ?></td>
                                    <td><?php echo e($presensi->tanggal->format('d/m/Y')); ?></td>
                                    <td><?php echo e($presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : '-'); ?></td>
                                    <td><?php echo e($presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : '-'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger'))); ?>">
                                            <?php echo e(ucfirst($presensi->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($presensi->status === 'izin'): ?>
                                            <?php if($presensi->status_izin): ?>
                                                <span class="badge bg-<?php echo e($presensi->status_izin === 'approved' ? 'success' : ($presensi->status_izin === 'rejected' ? 'danger' : 'secondary')); ?>">
                                                    <?php echo e(ucfirst($presensi->status_izin)); ?>

                                                </span>
                                            <?php endif; ?>
                                            <?php if($presensi->surat_izin_path): ?>
                                                <br>
                                                <a href="<?php echo e(asset('storage/app/public/'.$presensi->surat_izin_path)); ?>" target="_blank" class="text-info"><small>Lihat Surat</small></a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($presensi->lokasi ?? '-'); ?></td>
                                    <td><?php echo e($presensi->keterangan ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="11" class="text-center p-4">
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
                <?php endif; ?>

                <?php if($presensis instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $presensis->hasPages()): ?>
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
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>
<script>
$(document).ready(function() {
    var isSuperAdmin = "<?php echo e(auth()->user()->role); ?>" === "super_admin";
    $('#datatable-buttons').DataTable({
        pageLength: isSuperAdmin ? -1 : 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
    });

    // Get initial location when page loads (Reading 1)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            // Store first location reading in sessionStorage
            sessionStorage.setItem('reading1_latitude', position.coords.latitude);
            sessionStorage.setItem('reading1_longitude', position.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());
            console.log('Reading 1 location stored:', position.coords.latitude, position.coords.longitude);
        }, function(error) {
            console.log('Error getting reading 1 location:', error.message);
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 30000
        });
    }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/presensi/index.blade.php ENDPATH**/ ?>