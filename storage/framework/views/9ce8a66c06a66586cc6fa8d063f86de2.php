<div id="<?php echo e($config['slug']); ?>-onsite"
     class="sub-tab-content active">

<div class="card">

<form action="<?php echo e(route('talenta.tugas-level-1.simpan')); ?>"
      method="POST"
      enctype="multipart/form-data">

<?php echo csrf_field(); ?>

<input type="hidden" name="area" value="<?php echo e($config['slug']); ?>">
<input type="hidden" name="jenis_tugas" value="onsite">

<input type="file"
       name="lampiran"
       class="form-control">

<button class="btn btn-primary mt-3">
    Upload On Site
</button>

</form>
</div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/forms/onsite.blade.php ENDPATH**/ ?>