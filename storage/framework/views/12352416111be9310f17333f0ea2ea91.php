<?php $__env->startSection('title'); ?>
    Pending Registrations
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAllowed): ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Pending Registrations <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="card mb-4">
        <div class="card-body">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Jabatan</th>
                            <th>Asal Sekolah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendingRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($registration->name); ?></td>
                                <td><?php echo e($registration->email); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo e(ucfirst($registration->role)); ?></span>
                                </td>
                                <td><?php echo e($registration->jabatan ?: '-'); ?></td>
                                <td><?php echo e(optional($registration->madrasah)->name ?? '-'); ?></td>
                                <td>
                                    <form action="<?php echo e(route('admin.pending-registrations.approve', $registration->id)); ?>" method="POST" style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('POST'); ?>
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Are you sure you want to approve this registration?')">Approve</button>
                                    </form>

                                    <form action="<?php echo e(route('admin.pending-registrations.reject', $registration->id)); ?>" method="POST" style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('POST'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to reject this registration?')">Reject</button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr class="empty-row">
                                <td colspan="7" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>No pending registrations found</strong><br>
                                        <small>All registrations have been processed.</small>
                                    </div>
                                </td>
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

        <script>
            $(document).ready(function () {
                if ($('#datatable-buttons tbody tr').not('.empty-row').length > 0) {
                    let table = $("#datatable-buttons").DataTable({
                        responsive: true,
                        lengthChange: true,
                        autoWidth: false,
                        dom: 'Bfrtip',
                        buttons: ["copy", "excel", "pdf", "print", "colvis"]
                    });
                }
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/pending-registrations/index.blade.php ENDPATH**/ ?>