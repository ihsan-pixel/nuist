<div id="<?php echo e($config['slug']); ?>-kelompok"
     class="sub-tab-content">

<div class="card">

<form action="<?php echo e(route('talenta.tugas-level-1.simpan')); ?>"
      method="POST"
      enctype="multipart/form-data"
      id="form-kelompok-<?php echo e($config['slug']); ?>">

<?php echo csrf_field(); ?>

<input type="hidden" name="area" value="<?php echo e($config['slug']); ?>">
<input type="hidden" name="jenis_tugas" value="kelompok">

<?php
    $soalsForArea = $soalsByArea[$config['slug']] ?? collect();
    $soalList = $soalsForArea['kelompok'] ?? collect();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($soalList->isNotEmpty()): ?>
    <div class="mb-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $soalList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="card bg-light p-2 mb-2">
                <div class="fw-semibold">Soal:</div>
                <div class="small"><?php echo nl2br(e($soal->pertanyaan)); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($soal->instruksi): ?>
                    <div class="small text-muted mt-1"><?php echo nl2br(e($soal->instruksi)); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<input type="file"
       name="lampiran"
       class="form-control"
       required>

<div class="d-flex justify-content-end mt-3">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($existingTasks[$config['slug'] . '-kelompok'])): ?>
        <button class="btn btn-primary me-2" type="submit">
            Upload Kelompok
        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($existingTasks[$config['slug'] . '-kelompok'])): ?>
        <a href="<?php echo e(asset($existingTasks[$config['slug'] . '-kelompok']->file_path)); ?>"
           target="_blank"
           class="btn btn-secondary">
            <i class="bx bx-file"></i> Lihat File Terupload
        </a>
        <form action="<?php echo e(route('talenta.tugas-level-1.reset')); ?>" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Yakin ingin menghapus file terupload?');">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="area" value="<?php echo e($config['slug']); ?>">
            <input type="hidden" name="jenis_tugas" value="kelompok">
            <button type="submit" class="btn btn-danger">
                <i class="bx bx-trash"></i> Reset
            </button>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

</form>
</div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/forms/kelompok.blade.php ENDPATH**/ ?>