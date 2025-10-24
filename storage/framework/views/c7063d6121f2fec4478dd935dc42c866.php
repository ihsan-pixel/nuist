<?php $__env->startSection('title'); ?> Pengguna Aktif <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.user-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.user-card:hover {
    transform: translateY(-2px);
}

.role-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
    padding: 1rem;
}

.user-list {
    max-height: 400px;
    overflow-y: auto;
}

.user-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f1f1;
    transition: background-color 0.2s;
}

.user-item:hover {
    background-color: #f8f9fa;
}

.user-item:last-child {
    border-bottom: none;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.stats-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Pengguna Aktif <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>



<!-- Statistics -->
<div class="row mb-4">
    <?php
        $totalActive = 0;
        foreach($activeUsersByRole as $role => $users) {
            $totalActive += $users->count();
        }
    ?>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary rounded-circle fs-3">
                            <i class="bx bx-user-check"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Aktif</p>
                        <h5 class="mb-0"><?php echo e($totalActive); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = $activeUsersByRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-3">
                            <i class="bx bx-group"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2"><?php echo e($roleLabels[$role] ?? ucfirst($role)); ?></p>
                        <h5 class="mb-0"><?php echo e($users->count()); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Active Users by Role -->
<div class="row">
    <?php $__currentLoopData = $activeUsersByRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card user-card">
            <div class="role-header">
                <h5 class="mb-0">
                    <i class="bx bx-group me-2"></i>
                    <?php echo e($roleLabels[$role] ?? ucfirst($role)); ?>

                    <span class="badge bg-light text-dark ms-2"><?php echo e($users->count()); ?></span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="user-list">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="user-item">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo e($user->avatar ? asset('storage/' . $user->avatar) : asset('build/images/users/images/avatar-11.jpg')); ?>"
                                 alt="Avatar" class="user-avatar me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo e($user->name); ?></h6>
                                <p class="text-muted mb-0 small">
                                    <i class="bx bx-envelope me-1"></i><?php echo e($user->email); ?>

                                </p>
                                <?php if($user->madrasah): ?>
                                    <p class="text-muted mb-0 small">
                                        <i class="bx bx-building me-1"></i><?php echo e($user->madrasah->nama); ?>

                                    </p>
                                <?php endif; ?>
                                <p class="text-muted mb-0 small">
                                    <i class="bx bx-time me-1"></i>Terakhir aktif: <?php echo e(optional($user->last_seen)->diffForHumans() ?? 'N/A'); ?>

                                </p>
                            </div>
                            <div class="text-end">
                                <small class="text-muted"><?php echo e($user->nuist_id); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Tidak ada pengguna aktif</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/active-users/index.blade.php ENDPATH**/ ?>