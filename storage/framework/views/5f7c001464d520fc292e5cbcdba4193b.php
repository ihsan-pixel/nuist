<?php $__env->startSection('title', 'Dashboard Pengurus'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Pengurus</h4>
            </div>
        </div>
    </div>

    <!-- Banner -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBanner && $bannerImage): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <img src="<?php echo e(asset($bannerImage)); ?>" alt="Banner" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Total Madrasah</h4>
                            <h2 class="mb-0"><?php echo e($totalMadrasah); ?></h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-school text-primary display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Tenaga Pendidik</h4>
                            <h2 class="mb-0"><?php echo e($totalTenagaPendidik); ?></h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-account-group text-success display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Total Pengurus</h4>
                            <h2 class="mb-0"><?php echo e($totalPengurus); ?></h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-account-tie text-info display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Aktivitas</h4>
                            <h2 class="mb-0"><?php echo e(count($recentActivities)); ?></h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-chart-line text-warning display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Aktivitas Terbaru</h4>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($recentActivities) > 0): ?>
                        <div class="list-group">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?php echo e($activity['title'] ?? 'Aktivitas'); ?></h5>
                                        <small><?php echo e($activity['date'] ?? now()->format('d M Y')); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo e($activity['description'] ?? 'Deskripsi aktivitas'); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Belum ada aktivitas terbaru.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/dashboard.blade.php ENDPATH**/ ?>