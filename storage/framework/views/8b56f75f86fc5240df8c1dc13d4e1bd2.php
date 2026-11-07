<?php $__env->startSection('title'); ?>
    Tahun Pelajaran
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <style>
        body:has(#datatable-buttons){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(#datatable-buttons) .card{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08)}body:has(#datatable-buttons) .card-header{border-bottom:1px solid #e5edef;background:transparent;padding:22px 24px}body:has(#datatable-buttons) .card-body{padding:20px 24px 24px}body:has(#datatable-buttons) .table-responsive{border:1px solid #e5edef;border-radius:16px;overflow-x:auto}body:has(#datatable-buttons) table.dataTable{table-layout:auto;min-width:620px}body:has(#datatable-buttons) table.dataTable thead th{background:#f1f6f6;color:#192d37;border:0;border-bottom:1px solid #e5edef;font-size:11px;padding:13px 14px;white-space:nowrap}body:has(#datatable-buttons) table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;white-space:normal!important;overflow-wrap:anywhere}body:has(#datatable-buttons) table.dataTable th:first-child,body:has(#datatable-buttons) table.dataTable td:first-child{width:60px}body:has(#datatable-buttons) table.dataTable th:nth-child(2),body:has(#datatable-buttons) table.dataTable td:nth-child(2){min-width:240px}body:has(#datatable-buttons) table.dataTable th:last-child,body:has(#datatable-buttons) table.dataTable td:last-child{width:135px}body:has(#datatable-buttons) .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}body:has(#datatable-buttons) .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem}body:has(#datatable-buttons) .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}body:has(#datatable-buttons) .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}body:has(#datatable-buttons) .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}body:has(#datatable-buttons) .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}body:has(#datatable-buttons) .buttons-colvis{background:linear-gradient(90deg,#db2777,#f472b6)!important}body:has(#datatable-buttons) div.dt-button-collection{padding:10px!important;border-radius:16px!important;background:#f8fafb!important}body:has(#datatable-buttons) div.dt-button-collection button.dt-button{background:#fff!important;background-image:none!important;color:#192d37!important;border:0!important;border-radius:14px!important;width:100%!important;margin-bottom:6px;text-align:left}body:has(#datatable-buttons) .modal{z-index:2000!important;pointer-events:none}body:has(#datatable-buttons) .modal.show{pointer-events:auto}body:has(#datatable-buttons) .modal.show .modal-dialog{position:relative;z-index:2001;pointer-events:auto}
    body:has(#datatable-buttons)::before,body:has(#datatable-buttons)::after{content:"";position:fixed;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:md-orb 11s ease-in-out infinite}body:has(#datatable-buttons)::before{width:280px;height:280px;right:-90px;top:110px;background:rgba(42,190,153,.14)}body:has(#datatable-buttons)::after{width:230px;height:230px;left:180px;bottom:-100px;background:rgba(98,158,255,.12);animation-direction:reverse;animation-duration:14s}body:has(#datatable-buttons) .card{animation:md-card-in .7s cubic-bezier(.22,.8,.24,1) both}body:has(#datatable-buttons) .dt-button{transition:transform .18s ease,filter .18s ease,box-shadow .18s ease}body:has(#datatable-buttons) .dt-button:hover{transform:translateY(-2px);filter:brightness(.97);box-shadow:0 8px 16px rgba(32,70,78,.16)}body:has(#datatable-buttons) table.dataTable tbody tr{transition:background-color .18s ease,transform .18s ease}body:has(#datatable-buttons) table.dataTable tbody tr:hover{background:rgba(231,246,240,.45);transform:translateX(2px)}@keyframes md-orb{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(-12px,18px,0)}}@keyframes md-card-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@media(prefers-reduced-motion:reduce){body:has(#datatable-buttons)::before,body:has(#datatable-buttons)::after,body:has(#datatable-buttons) .card{animation:none!important}}
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Tahun Pelajaran <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-calendar me-2"></i>Tahun Pelajaran
                    </h4>
                </div>
                <div class="card-body">

                    <div class="mb-3 d-flex justify-content-end gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTahunPelajaran">
                            <i class="bx bx-plus"></i> Tambah Tahun Pelajaran
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportTahunPelajaran">
                            <i class="bx bx-upload"></i> Import Data
                        </button>
                    </div>

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
                            <th>Tahun Pelajaran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tahunPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($tp->name); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditTahunPelajaran<?php echo e($tp->id); ?>">
                                        Edit
                                    </button>

                                    <form action="<?php echo e(route('tahun-pelajaran.destroy', $tp->id)); ?>" method="POST" style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus tahun pelajaran ini?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Tahun Pelajaran -->
                            <div class="modal fade" id="modalEditTahunPelajaran<?php echo e($tp->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="<?php echo e(route('tahun-pelajaran.update', $tp->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tahun Pelajaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Tahun Pelajaran</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo e($tp->name); ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="3" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>Belum ada data Tahun Pelajaran</strong><br>
                                        <small>Silakan tambahkan data tahun pelajaran terlebih dahulu untuk melanjutkan.</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tahun Pelajaran -->
    <div class="modal fade" id="modalTambahTahunPelajaran" tabindex="-1" aria-labelledby="modalTambahTahunPelajaranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo e(route('tahun-pelajaran.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahTahunPelajaranLabel">Tambah Tahun Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tahun Pelajaran</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Import Tahun Pelajaran -->
    <div class="modal fade" id="modalImportTahunPelajaran" tabindex="-1" aria-labelledby="modalImportTahunPelajaranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo e(route('tahun-pelajaran.import')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImportTahunPelajaranLabel">Import Data Tahun Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Pilih File (Excel/CSV)</label>
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                Gunakan template file sesuai format data tahun pelajaran.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
            $('.modal').appendTo(document.body);
            $('#datatable-buttons tbody tr').has('td[colspan]').remove();
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"],
                language: { emptyTable: 'Belum ada tahun pelajaran.', zeroRecords: 'Data tidak ditemukan.' }
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/tahun-pelajaran/index.blade.php ENDPATH**/ ?>