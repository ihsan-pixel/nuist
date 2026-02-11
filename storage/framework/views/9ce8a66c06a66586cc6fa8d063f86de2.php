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

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($config['slug'] === 'kepemimpinan'): ?>
    <div class="mb-3">
        <label for="konteks" class="form-label">Konteks</label>
        <textarea name="konteks" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="peran" class="form-label">Peran</label>
        <input type="text" name="peran" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="nilai_kepemimpinan" class="form-label">Nilai Kepemimpinan</label>
        <textarea name="nilai_kepemimpinan" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="masalah_kepemimpinan" class="form-label">Masalah Kepemimpinan</label>
        <textarea name="masalah_kepemimpinan" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="pelajaran_penting" class="form-label">Pelajaran Penting</label>
        <textarea name="pelajaran_penting" class="form-control" required></textarea>
    </div>
<?php else: ?>
    <input type="file"
           name="lampiran"
           class="form-control"
           required>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<button class="btn btn-primary mt-3" type="submit">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($config['slug'] === 'kepemimpinan'): ?>
        Simpan On Site
    <?php else: ?>
        Upload On Site
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</button>

</form>
</div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/forms/onsite.blade.php ENDPATH**/ ?>