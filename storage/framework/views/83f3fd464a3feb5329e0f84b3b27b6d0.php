<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('bpp-content'); ?>
<a class="btn btn-primary mb-3" href="<?php echo e(route('admin.agenda.create')); ?>">Buat Kegiatan</a>
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0"><i class="bx bx-calendar-event me-2"></i>Agenda Universal</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr><th>No</th><th>Kegiatan</th><th>Waktu (WIB)</th><th>Status</th><th>Undangan</th><th>Hadir</th><th>Action</th></tr>
                </thead>
                <tbody>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?><tr><td><?php echo e($events->firstItem() + $index); ?></td><td><?php echo e($event->name); ?></td><td><?php echo e($event->start_at->format('d-m-Y H:i')); ?></td><td><?php echo e($event->status); ?></td><td><?php echo e($event->invitations_count + $event->guest_invitations_count); ?></td><td><?php echo e($event->attendances_count + $event->guest_attendances_count); ?></td><td><div class="d-flex gap-1 flex-wrap"><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.agenda.show', $event)); ?>">Detail & Rekap</a><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status !== 'cancelled'): ?><a class="btn btn-sm btn-warning" href="<?php echo e(route('admin.agenda.edit', $event)); ?>">Edit</a><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<form method="post" action="<?php echo e(route('admin.agenda.destroy', $event)); ?>" onsubmit="return confirm('Hapus agenda <?php echo e(addslashes($event->name)); ?> secara permanen? Seluruh undangan, QR, dan data presensi agenda ini juga akan dihapus dan tidak dapat dikembalikan.')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button></form></div></td></tr><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><tr><td colspan="7">Belum ada kegiatan.</td></tr><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($events->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>
<script>
$(function () {
    $('#datatable-buttons').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        paging: false,
        searching: false,
        info: false,
        ordering: false
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.bpppmnu.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/admin/bpppmnu/index.blade.php ENDPATH**/ ?>