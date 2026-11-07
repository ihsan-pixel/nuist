<?php $__env->startSection('title'); ?>
    Yayasan
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <style>
        .yayasan-page{--yayasan-green:#08745a;--yayasan-ink:#192d37;--yayasan-muted:#6c7c86;--yayasan-border:#e5edef;padding:8px 0 24px}
        .yayasan-page .yayasan-shell{border:1px solid rgba(255,255,255,.82);border-radius:22px;background:rgba(255,255,255,.72);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden}
        .yayasan-page .yayasan-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid rgba(220,231,233,.75)}
        .yayasan-page .yayasan-eyebrow{margin:0 0 4px;color:var(--yayasan-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
        .yayasan-page .yayasan-title{margin:0;color:var(--yayasan-ink);font-size:22px;font-weight:700}
        .yayasan-page .yayasan-subtitle{margin:5px 0 0;color:var(--yayasan-muted);font-size:12px}
        .yayasan-page .yayasan-toolbar .btn-primary{display:inline-flex;align-items:center;gap:6px;border:0;border-radius:12px;background:var(--yayasan-green);padding:.62rem .9rem;box-shadow:0 8px 18px rgba(8,116,90,.16)}
        .yayasan-page .yayasan-content{padding:20px 24px 24px}
        .yayasan-page .alert{border:1px solid transparent;border-radius:12px;box-shadow:none;font-size:13px}
        .yayasan-page .alert-success{border-color:#cde9dc;background:#effaf4;color:#176b4d}
        .yayasan-page .alert-danger{border-color:#f2d2d2;background:#fff5f5;color:#a33737}
        .yayasan-page .table-panel{border:1px solid var(--yayasan-border);border-radius:16px;background:rgba(255,255,255,.58);overflow:hidden}
        .yayasan-page .table-responsive{overflow-x:auto}
        .yayasan-page table.dataTable{margin:0!important;border-collapse:separate;border-spacing:0;min-width:900px}
        .yayasan-page table.dataTable thead th{border:0;border-bottom:1px solid var(--yayasan-border);background:#f1f6f6;color:var(--yayasan-ink);font-size:11px;font-weight:700;letter-spacing:.02em;padding:13px 14px;white-space:nowrap}
        .yayasan-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle}
        .yayasan-page table.dataTable tbody tr:last-child td{border-bottom:0}
        .yayasan-page table.dataTable tbody tr:hover{background:rgba(231,246,240,.4)}
        .yayasan-page .yayasan-name{display:block;color:var(--yayasan-ink);font-weight:700;min-width:150px}
        .yayasan-page .yayasan-text{display:block;max-width:220px;line-height:1.5;overflow-wrap:anywhere}
        .yayasan-page .yayasan-location{display:inline-flex;align-items:center;gap:5px;color:var(--yayasan-green);font-size:11px;font-weight:600;white-space:nowrap}
        .yayasan-page .yayasan-location.is-empty{color:var(--yayasan-muted);font-weight:400}
        .yayasan-page .yayasan-map-link{display:inline-flex;align-items:center;gap:4px;margin-top:6px;border-radius:8px!important;padding:.3rem .5rem!important;color:#08745a!important;background:#e7f6ef!important;border:0!important;font-size:11px}
        .yayasan-page .yayasan-actions{display:flex;flex-wrap:wrap;gap:6px;min-width:112px}
        .yayasan-page .yayasan-actions .btn{border-radius:8px;font-size:11px;padding:.34rem .55rem}
        .yayasan-page .yayasan-actions .btn-warning{color:#6d4b00;background:#fff4d6;border-color:#ffe5a0}
        .yayasan-page .yayasan-actions .btn-danger{background:#fff0f0;border-color:#ffd2d2;color:#b33b3b}
        .yayasan-page .dataTables_wrapper{padding:16px}
        .yayasan-page .dataTables_wrapper .dataTables_length,.yayasan-page .dataTables_wrapper .dataTables_filter{margin-bottom:14px;color:var(--yayasan-muted);font-size:12px}
        .yayasan-page .dataTables_wrapper select,.yayasan-page .dataTables_wrapper input{border:1px solid #dce7e9;border-radius:9px;background:#fff;color:var(--yayasan-ink);font-size:12px;min-height:34px}
        .yayasan-page .dataTables_wrapper input{margin-left:6px;padding:6px 10px;outline:0}
        .yayasan-page .dataTables_wrapper input:focus{border-color:#75c8ac;box-shadow:0 0 0 3px rgba(8,116,90,.1)}
        .yayasan-page .dataTables_wrapper .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}
        .yayasan-page .dataTables_wrapper .dt-button{border:1px solid #dce7e9;border-radius:8px;background:#fff;color:#49616a;font-size:11px;padding:.4rem .62rem}
        .yayasan-page .dataTables_wrapper .dt-button:hover{border-color:#9fd6c2;background:#effaf5;color:var(--yayasan-green)}
        .yayasan-page .dataTables_info{color:var(--yayasan-muted);font-size:11px;padding-top:14px!important}
        .yayasan-page .dataTables_paginate{padding-top:10px!important}
        .yayasan-page .dataTables_paginate .paginate_button{border:0!important;border-radius:8px!important;color:var(--yayasan-green)!important;font-size:11px;padding:5px 9px!important}
        .yayasan-page .dataTables_paginate .paginate_button.current{background:#dff3eb!important;color:var(--yayasan-green)!important}
        .yayasan-page .modal-content{border:1px solid rgba(255,255,255,.88);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}
        .yayasan-page .modal-header{border-bottom:1px solid var(--yayasan-border);background:#f6faf9;padding:18px 22px}
        .yayasan-page .modal-title{color:var(--yayasan-ink);font-size:17px;font-weight:700}
        .yayasan-page .modal-body{padding:22px}
        .yayasan-page .modal-body label{display:block;margin-bottom:6px;color:var(--yayasan-ink);font-size:12px;font-weight:600}
        .yayasan-page .modal-body .form-control{border:1px solid #dce7e9;border-radius:9px;font-size:13px;padding:.55rem .7rem}
        .yayasan-page .modal-body .form-control:focus{border-color:#75c8ac;box-shadow:0 0 0 3px rgba(8,116,90,.1)}
        .yayasan-page .modal-footer{border-top:1px solid var(--yayasan-border);padding:14px 22px}
        .yayasan-page .modal-footer .btn{border-radius:9px;font-size:12px}
        .yayasan-page .empty-state{padding:46px 20px!important;color:var(--yayasan-muted);background:#fbfdfd}
        .yayasan-page .empty-state .bx{display:block;margin-bottom:8px;color:#9ac9b8;font-size:38px}
        @media(max-width:767.98px){.yayasan-page .yayasan-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.yayasan-page .yayasan-toolbar .btn-primary{width:100%;justify-content:center}.yayasan-page .yayasan-content{padding:14px}.yayasan-page .dataTables_wrapper{padding:10px}.yayasan-page .dataTables_wrapper .dataTables_length,.yayasan-page .dataTables_wrapper .dataTables_filter{float:none!important;text-align:left!important}.yayasan-page .dataTables_wrapper .dataTables_filter input{width:calc(100% - 70px);max-width:220px}.yayasan-page .modal-dialog{margin:.75rem}.yayasan-page .modal-body{padding:18px}}
        @media(max-width:390px){.yayasan-page{padding-top:0}.yayasan-page .yayasan-title{font-size:19px}.yayasan-page .yayasan-content{padding:10px}.yayasan-page .dataTables_wrapper .dt-buttons{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}.yayasan-page .dataTables_wrapper .dt-button{width:100%}}
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAllowed): ?>
<div class="yayasan-page">
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Yayasan <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="yayasan-shell mb-4">
        <div class="yayasan-toolbar">
            <div>
                <p class="yayasan-eyebrow">Master Data</p>
                <h1 class="yayasan-title">Data Yayasan</h1>
                <p class="yayasan-subtitle">Kelola informasi yayasan, alamat, visi, misi, dan lokasi.</p>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahYayasan">
                    <i class="bx bx-plus"></i><span>Tambah Yayasan</span>
                </button>
            </div>
        </div>
        <div class="yayasan-content">

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

            <div class="table-panel">
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Yayasan</th>
                            <th>Alamat</th>
                            <th>Lokasi</th>
                            <th>Visi</th>
                            <th>Misi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $yayasans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $yayasan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><span class="yayasan-name"><?php echo e($yayasan->name); ?></span></td>
                                <td><span class="yayasan-text"><?php echo e($yayasan->alamat ?? '-'); ?></span></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($yayasan->latitude && $yayasan->longitude): ?>
                                        <span class="yayasan-location"><i class="bx bx-map-pin"></i><?php echo e($yayasan->latitude); ?>, <?php echo e($yayasan->longitude); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($yayasan->map_link): ?>
                                            <br><a href="<?php echo e($yayasan->map_link); ?>" target="_blank" class="btn btn-sm yayasan-map-link"><i class="bx bx-link-external"></i>Lihat Map</a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <span class="yayasan-location is-empty">Belum diatur</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td><span class="yayasan-text"><?php echo e($yayasan->visi ? Str::limit($yayasan->visi, 50) : '-'); ?></span></td>
                                <td><span class="yayasan-text"><?php echo e($yayasan->misi ? Str::limit($yayasan->misi, 50) : '-'); ?></span></td>
                                <td><div class="yayasan-actions">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditYayasan<?php echo e($yayasan->id); ?>" title="Edit yayasan">
                                        <i class="bx bx-edit-alt"></i><span>Edit</span>
                                    </button>

                                    <form action="<?php echo e(route('yayasan.destroy', $yayasan->id)); ?>" method="POST" style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus yayasan ini?')" title="Hapus yayasan"><i class="bx bx-trash"></i><span>Hapus</span></button>
                                    </form>
                                </div></td>
                            </tr>

                            <!-- Modal Edit Yayasan -->
                            <div class="modal fade" id="modalEditYayasan<?php echo e($yayasan->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form action="<?php echo e(route('yayasan.update', $yayasan->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Yayasan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nama Yayasan</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo e($yayasan->name); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Alamat</label>
                                                    <textarea name="alamat" class="form-control" rows="2"><?php echo e($yayasan->alamat); ?></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label>Latitude</label>
                                                            <input type="text" name="latitude" class="form-control" value="<?php echo e($yayasan->latitude); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label>Longitude</label>
                                                            <input type="text" name="longitude" class="form-control" value="<?php echo e($yayasan->longitude); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Link Map</label>
                                                    <input type="text" name="map_link" class="form-control" value="<?php echo e($yayasan->map_link); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Visi</label>
                                                    <textarea name="visi" class="form-control" rows="3"><?php echo e($yayasan->visi); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Misi</label>
                                                    <textarea name="misi" class="form-control" rows="3"><?php echo e($yayasan->misi); ?></textarea>
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
                                <td colspan="7" class="text-center empty-state">
                                    <div role="status">
                                        <i class="bx bx-buildings"></i>
                                        <strong class="d-block">Belum ada data Yayasan</strong>
                                        <small>Silakan tambahkan data yayasan terlebih dahulu.</small>
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

    <!-- Modal Tambah Yayasan -->
    <div class="modal fade" id="modalTambahYayasan" tabindex="-1" aria-labelledby="modalTambahYayasanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="<?php echo e(route('yayasan.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahYayasanLabel">Tambah Yayasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Yayasan</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control" placeholder="Contoh: -7.7956">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control" placeholder="Contoh: 110.3695">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Link Map</label>
                            <input type="text" name="map_link" class="form-control" placeholder="https://maps.app.goo.gl/xxxx">
                        </div>
                        <div class="mb-3">
                            <label>Visi</label>
                            <textarea name="visi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Misi</label>
                            <textarea name="misi" class="form-control" rows="3"></textarea>
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
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"]
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/yayasan/index.blade.php ENDPATH**/ ?>