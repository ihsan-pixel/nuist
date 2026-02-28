<?php $__env->startSection('title'); ?>
    Academica - Proposal
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus', 'mgmp']) && auth()->user()->password_changed;
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAllowed): ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> MGMP <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Academica <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card mb-4">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col text-end">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userHasUploaded): ?>
                    <div class="text-muted">Anda sudah mengupload proposal. Hanya satu upload per user.</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">Upload Proposal PDF</h5>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userHasUploaded): ?>
                    <div class="mb-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userProposal): ?>
                        <a href="<?php echo e(url('/uploads/' . $userProposal->path)); ?>" target="_blank" class="btn btn-outline-primary">
                            <i class="bx bx-file"></i> <?php echo e($userProposal->filename); ?>

                        </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('mgmp.academica.upload')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-2">
                            <label for="proposal" class="form-label">Pilih file PDF proposal</label>
                            <input type="file" name="proposal" id="proposal" accept="application/pdf" class="form-control" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proposal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <button class="btn btn-primary">Upload Proposal</button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Daftar Proposal</h5>
        <div class="table-responsive">
            <table id="datatable-academica" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Pengupload</th>
                        <th>File</th>
                        <th>Diunggah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($p->user->name ?? 'User ID ' . $p->user_id); ?></td>
                        <td><?php echo e($p->filename); ?></td>
                        <td><?php echo e($p->created_at->format('Y-m-d H:i')); ?></td>
                        <td>
                            <a href="<?php echo e(url('/uploads/' . $p->path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada proposal.</td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php else: ?>
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#datatable-academica')) {
        $('#datatable-academica').DataTable().destroy();
    }

    let table = $("#datatable-academica").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        destroy: true,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-academica_wrapper .col-md-6:eq(0)');
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/academica.blade.php ENDPATH**/ ?>