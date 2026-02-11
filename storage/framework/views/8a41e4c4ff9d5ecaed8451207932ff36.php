<div id="<?php echo e($config['slug']); ?>-terstruktur"
     class="sub-tab-content">

<div class="card">

<form action="<?php echo e(route('talenta.tugas-level-1.simpan')); ?>"
      method="POST"
      enctype="multipart/form-data"
      id="form-terstruktur-<?php echo e($config['slug']); ?>">

<?php echo csrf_field(); ?>

<input type="hidden" name="area" value="<?php echo e($config['slug']); ?>">
<input type="hidden" name="jenis_tugas" value="terstruktur">

<input type="file"
       name="lampiran"
       class="form-control"
       required>

<button class="btn btn-primary mt-3" type="submit">
    Upload Terstruktur
</button>

</form>
</div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/forms/terstruktur.blade.php ENDPATH**/ ?>