<?php $__env->startSection('content'); ?>
<div class="card">
    <h1 class="title">Dashboard AMI</h1>
    <div class="muted">Role: <?php echo e($role); ?></div>
</div>
<div class="stats">
    <div class="stat"><strong><?php echo e($stats['periods']); ?></strong><div class="muted">Periode</div></div>
    <div class="stat"><strong><?php echo e($stats['schools']); ?></strong><div class="muted">Sekolah</div></div>
    <div class="stat"><strong><?php echo e($stats['assignments']); ?></strong><div class="muted">Penugasan</div></div>
    <div class="stat"><strong><?php echo e($stats['tracked_schools']); ?></strong><div class="muted">Tercatat</div></div>
</div>
<div class="card">
    <h3>Periode Aktif</h3>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div style="padding:10px 0;border-bottom:1px solid var(--border)">
            <strong><?php echo e($period->name); ?></strong>
            <div class="muted"><?php echo e($period->year); ?> - <?php echo e($period->status); ?></div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="muted">Belum ada periode.</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('ami.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/ami/dashboard.blade.php ENDPATH**/ ?>