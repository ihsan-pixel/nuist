<?php $__env->startSection('title', 'Profile DPS'); ?>

<?php $__env->startSection('content'); ?>
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div>
            <div class="fw-semibold">Profile DPS</div>
            <div class="text-muted small">Akses sekolah: <?php echo e($madrasahs->count()); ?></div>
        </div>
        <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('mobile.dps.dashboard')); ?>">
            <i class="bx bx-home"></i>
        </a>
    </div>
</header>

<div class="card mb-3">
    <div class="card-body">
        <div class="fw-semibold"><?php echo e($user->name); ?></div>
        <div class="text-muted small"><?php echo e($user->email); ?></div>
        <div class="mt-2">
            <span class="badge bg-primary-subtle text-primary">role: dps</span>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="fw-semibold mb-2">Sekolah & Unsur DPS</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahs->isEmpty()): ?>
            <div class="text-muted text-center py-2">Belum ada sekolah.</div>
        <?php else: ?>
            <?php
                $bySchool = $assignments->groupBy('madrasah_id');
            ?>
            <div class="d-grid gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php
                        $rows = $bySchool->get($m->id, collect());
                        $unsur = $rows->pluck('unsur')->filter()->unique()->values();
                        $periode = $rows->pluck('periode')->filter()->unique()->values();
                    ?>
                    <div class="border rounded p-2">
                        <div class="fw-semibold"><?php echo e($m->name ?? '-'); ?></div>
                        <div class="text-muted small mb-1">SCOD: <?php echo e($m->scod ?? '-'); ?></div>
                        <div class="small">
                            <span class="text-muted">Unsur:</span>
                            <?php echo e($unsur->isEmpty() ? '-' : $unsur->implode(', ')); ?>

                        </div>
                        <div class="small">
                            <span class="text-muted">Periode:</span>
                            <?php echo e($periode->isEmpty() ? '-' : $periode->implode(', ')); ?>

                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<div class="d-grid gap-2">
    
    <a class="btn btn-outline-danger" href="<?php echo e(route('logout')); ?>"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bx bx-log-out me-1"></i> Logout
    </a>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;">
        <?php echo csrf_field(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/dps/profile.blade.php ENDPATH**/ ?>