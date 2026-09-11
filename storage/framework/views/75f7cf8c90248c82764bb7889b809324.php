<?php $__env->startSection('bpp-content'); ?>
<div class="card"><div class="card-body text-center"><h2><?php echo e($event->name); ?></h2><p>QR berlaku sampai <?php echo e($event->attendance_close_at->format('d-m-Y H:i')); ?> WIB.</p><p class="text-muted">QR sebelumnya sudah dicabut. Simpan QR ini sebelum meninggalkan halaman.</p>
<div id="event-qr" style="max-width:360px;margin:auto"><?php echo $svg; ?></div>
<button id="download-qr" type="button" class="btn btn-primary mt-3">Unduh QR (SVG)</button>
<a href="<?php echo e(route('admin.bpppmnu.events.show', $event)); ?>" class="btn btn-outline-secondary mt-3">Kembali ke Rekap</a>
</div></div>
<script>
document.getElementById('download-qr').addEventListener('click', () => {
    const content = document.getElementById('event-qr').innerHTML;
    const url = URL.createObjectURL(new Blob([content], {type:'image/svg+xml'}));
    const link = document.createElement('a'); link.href = url; link.download = 'qr-bpppmnu-<?php echo e($event->id); ?>.svg'; link.click(); setTimeout(() => URL.revokeObjectURL(url), 1000);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.bpppmnu.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/admin/bpppmnu/qr.blade.php ENDPATH**/ ?>