<?php $__env->startSection('title', 'Laporan Akhir Tahun'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='<?php echo e(route('mobile.profile')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Laporan Akhir Tahun</h4>
                </div>
                <div class="card-body">


                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('info')): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php echo e(session('info')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('warning')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?php echo e(session('warning')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-muted mb-0">Daftar laporan akhir tahun yang telah Anda buat</p>
                        
                    </div>

                    <?php if($laporans->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Nama Madrasah</th>
                                        <th>Status</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($laporan->tahun_pelaporan); ?></td>
                                            <td><?php echo e($laporan->nama_madrasah); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($laporan->status === 'submitted' ? 'success' : ($laporan->status === 'approved' ? 'primary' : ($laporan->status === 'rejected' ? 'danger' : 'secondary'))); ?>">
                                                    <?php echo e(ucfirst($laporan->status)); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($laporan->tanggal_laporan ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') : '-'); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('mobile.laporan-akhir-tahun.show', $laporan->id)); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('mobile.laporan-akhir-tahun.edit', $laporan->id)); ?>" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada laporan</h5>
                            <p class="text-muted">Anda belum membuat laporan akhir tahun. Klik tombol di atas untuk membuat laporan baru.</p>
                            <a href="<?php echo e(route('mobile.laporan-akhir-tahun.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Laporan Pertama
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/laporan-akhir-tahun/index.blade.php ENDPATH**/ ?>