<div id="<?php echo e($config['slug']); ?>-kelompok"
     class="sub-tab-content">

<div class="card">

<form action="<?php echo e(route('talenta.tugas-level-1.simpan')); ?>"
      method="POST"
      enctype="multipart/form-data">

<?php echo csrf_field(); ?>

<input type="hidden" name="area" value="<?php echo e($config['slug']); ?>">
<input type="hidden" name="jenis_tugas" value="kelompok">

<input type="file"
       name="lampiran"
       class="form-control">

<button class="btn btn-primary mt-3">
    Upload Kelompok
</button>

</form>
</div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/forms/kelompok.blade.php ENDPATH**/ ?>