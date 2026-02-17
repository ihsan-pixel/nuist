<div id="<?php echo e($config['slug']); ?>-onsite"
     class="sub-tab-content active">

<div class="card">

<form action="<?php echo e(route('talenta.tugas-level-1.simpan')); ?>"
      method="POST"
      enctype="multipart/form-data"
      id="form-onsite-<?php echo e($config['slug']); ?>">

<?php echo csrf_field(); ?>

<input type="hidden" name="area" value="<?php echo e($config['slug']); ?>">
<input type="hidden" name="jenis_tugas" value="on_site">

<?php
    // Determine if the materi has passed its tanggal_materi (expired)
    $expired = false;
    if (isset($materi) && $materi->tanggal_materi) {
        $expired = now()->gt($materi->tanggal_materi);
    }
?>

<input type="file"
       name="lampiran"
       class="form-control"
       <?php if($expired): ?> disabled <?php else: ?> required <?php endif; ?>>

<div class="d-flex justify-content-end mt-3">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expired): ?>
        <p class="text-danger me-2">Batas waktu upload telah lewat (<?php echo e(isset($materi->tanggal_materi) ? $materi->tanggal_materi->format('d M Y') : '-'); ?>).</p>
    <?php else: ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($existingTasks[$config['slug'] . '-on_site'])): ?>
            <button class="btn btn-primary me-2" type="submit">
                Upload On Site
            </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($existingTasks[$config['slug'] . '-on_site'])): ?>
        <a href="<?php echo e(asset($existingTasks[$config['slug'] . '-on_site']->file_path)); ?>"
           target="_blank"
           class="btn btn-secondary">
            <i class="bx bx-file"></i> Lihat File Terupload
        </a>
        <form action="<?php echo e(route('talenta.tugas-level-1.reset')); ?>" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Yakin ingin menghapus file terupload?');">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="area" value="<?php echo e($config['slug']); ?>">
            <input type="hidden" name="jenis_tugas" value="on_site">
            <button type="submit" class="btn btn-danger">
                <i class="bx bx-trash"></i> Reset
            </button>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

</form>
</div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/forms/onsite.blade.php ENDPATH**/ ?>