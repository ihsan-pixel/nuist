<?php $__env->startSection('title'); ?>
    Edit DPS
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $defaults = \App\Models\DpsMember::DEFAULT_UNSUR_OPTIONS;
    $existing = \App\Models\DpsMember::query()->select('unsur')->distinct()->pluck('unsur')->filter()->all();
    $unsurOptions = array_values(array_unique(array_merge($defaults, $existing)));
?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Edit DPS <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card mb-4">
    <div class="card-body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">Validasi gagal:</div>
                <ul class="mb-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="mb-3">
            <div class="fw-semibold"><?php echo e($member->madrasah->name ?? '-'); ?></div>
            <div class="text-muted small">SCOD: <?php echo e($member->madrasah->scod ?? '-'); ?></div>
        </div>

        <form method="POST" action="<?php echo e(route('dps.update', $member->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label">Nama DPS</label>
                    <input type="text" name="nama" value="<?php echo e(old('nama', $member->nama)); ?>" class="form-control" required>
                </div>
                <div class="col-lg-5">
                    <label class="form-label">Unsur DPS</label>
                    <select name="unsur" class="form-select" required>
                        <option value="">Pilih unsur...</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $unsurOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($opt); ?>" <?php if(old('unsur', $member->unsur) === $opt): echo 'selected'; endif; ?>><?php echo e($opt); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Periode</label>
                    <input type="text" name="periode" value="<?php echo e(old('periode', $member->periode)); ?>" class="form-control" required>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
                <a href="<?php echo e(route('dps.show', $member->madrasah_id)); ?>" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/masterdata/dps/edit.blade.php ENDPATH**/ ?>