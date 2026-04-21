<?php $__env->startSection('title', 'Upload Nilai Ujian'); ?>

<?php $__env->startSection('css'); ?>
<!-- DataTables CSS (reused from admin simfoni) -->
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
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?php echo e(session('success')); ?>',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?php echo e(session('error')); ?>',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: true
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Instumen Talenta <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Upload Nilai Ujian <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('instumen-talenta.import-nilai-ujian')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="file" class="form-label">File CSV</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Nilai Ujian</h5>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Peserta</th>
                                    <th>Nama Peserta</th>
                                    <th>Nilai Ujian</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($entry->peserta->kode_peserta ?? '-'); ?></td>
                                        <td><?php echo e($entry->peserta->user->name ?? '-'); ?></td>
                                        <td>
                                            <form action="<?php echo e(route('instumen-talenta.update-nilai-ujian', $entry->id)); ?>" method="post" class="d-flex">
                                                <?php echo csrf_field(); ?>
                                                <input type="number" name="nilai_ujian" value="<?php echo e($entry->nilai_ujian ?? ''); ?>" min="0" max="100" class="form-control form-control-sm me-2" style="width:110px">
                                                <button class="btn btn-sm btn-success">Simpan</button>
                                            </form>
                                        </td>
                                        <td><?php echo e($entry->keterangan ?? '-'); ?></td>
                                        <td>
                                            <form action="<?php echo e(route('instumen-talenta.delete-nilai-ujian', $entry->id)); ?>" method="post" onsubmit="return confirm('Hapus entry ini?');">
                                                <?php echo csrf_field(); ?>
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/instumen-talenta/upload-nilai-ujian.blade.php ENDPATH**/ ?>