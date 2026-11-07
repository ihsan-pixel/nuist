<?php $__env->startSection('title'); ?>
    DPS (Dewan Pengawas Sekolah)
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" />
    <style>
        .dps-page{--dps-green:#08745a;--dps-ink:#192d37;--dps-muted:#6c7c86;position:relative;isolation:isolate;padding:8px 0 24px}.dps-page:before,.dps-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:dps-float 10s ease-in-out infinite}.dps-page:before{width:280px;height:280px;right:-100px;top:-90px;background:rgba(42,190,153,.15)}.dps-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.11);animation-direction:reverse;animation-duration:12s}body:has(.dps-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.dps-page) .main-content{background:transparent!important}.dps-page .dps-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden;animation:dps-in .72s both}.dps-page .dps-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid #e5edef}.dps-page .dps-eyebrow{margin:0 0 4px;color:var(--dps-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.dps-page .dps-title{margin:0;color:var(--dps-ink);font-size:22px;font-weight:700}.dps-page .dps-subtitle{margin:5px 0 0;color:var(--dps-muted);font-size:12px}.dps-page .dps-actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:8px}.dps-page .dps-actions .btn{border:0;border-radius:12px;padding:.62rem .85rem;box-shadow:0 8px 18px rgba(8,116,90,.14)}.dps-page .dps-content{padding:20px 24px 24px}.dps-page .dps-stat{border:1px solid rgba(255,255,255,.86);border-radius:16px;background:rgba(255,255,255,.68);box-shadow:0 8px 22px rgba(32,70,78,.07)}.dps-page .table-panel{border:1px solid #e5edef;border-radius:16px;background:rgba(255,255,255,.58);overflow-x:auto}.dps-page table.dataTable{width:100%!important;table-layout:auto;min-width:880px}.dps-page table.dataTable thead th{border:0;border-bottom:1px solid #e5edef;background:#f1f6f6;color:var(--dps-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.dps-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle;white-space:normal!important;overflow-wrap:anywhere;word-break:break-word}.dps-page table.dataTable th:nth-child(1),.dps-page table.dataTable td:nth-child(1){width:54px}.dps-page table.dataTable th:nth-child(2),.dps-page table.dataTable td:nth-child(2){width:120px}.dps-page table.dataTable th:nth-child(3),.dps-page table.dataTable td:nth-child(3){min-width:220px}.dps-page table.dataTable th:nth-child(4),.dps-page table.dataTable td:nth-child(4){min-width:280px}.dps-page table.dataTable th:nth-child(5),.dps-page table.dataTable td:nth-child(5){min-width:180px}.dps-page table.dataTable th:last-child,.dps-page table.dataTable td:last-child{width:115px}.dps-page .dataTables_wrapper{padding:16px}.dps-page .dataTables_wrapper .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}.dps-page .dataTables_wrapper .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem}.dps-page .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.dps-page .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.dps-page .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.dps-page .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}.dps-page .buttons-colvis,.dps-page .buttons-columnVisibility{background:linear-gradient(90deg,#db2777,#f472b6)!important}.dps-page div.dt-button-collection{width:245px!important;padding:10px!important;border:1px solid #e3eaec!important;border-radius:16px!important;background:#f8fafb!important;box-shadow:0 12px 28px rgba(32,70,78,.14)!important}.dps-page div.dt-button-collection button.dt-button{display:block!important;width:100%!important;margin:0 0 6px!important;padding:13px 18px!important;border:0!important;border-radius:14px!important;background:#fff!important;background-image:none!important;color:#192d37!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important;text-align:left!important;font-size:15px!important}.dps-page .modal{z-index:2000!important;pointer-events:none}.dps-page .modal.show{pointer-events:auto}.dps-page .modal.show .modal-dialog{position:relative;z-index:2001;pointer-events:auto}.dps-page .modal-content{border:1px solid rgba(255,255,255,.9);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}.dps-page .modal-header{border-bottom:1px solid #e5edef;background:#f6faf9}.dps-page .modal-footer{border-top:1px solid #e5edef}.dps-page .form-control{border:1px solid #dce7e9;border-radius:9px}.dps-swal{width:min(420px,calc(100% - 32px))!important;border-radius:22px!important;background:rgba(255,255,255,.92)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important}.dps-swal-confirm{border:0!important;border-radius:10px!important;background:#08745a!important;color:#fff!important;padding:10px 16px!important}.dps-swal-cancel{border:1px solid #dce7e9!important;border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important;padding:10px 16px!important}@keyframes dps-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@keyframes dps-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(0,14px,0)}}@media(max-width:767.98px){.dps-page .dps-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.dps-page .dps-actions{width:100%;justify-content:stretch}.dps-page .dps-actions .btn{flex:1}.dps-page .dps-content{padding:14px}.dps-page .dataTables_wrapper{padding:10px}.dps-page .modal-dialog{margin:.75rem}}@media(max-width:390px){.dps-page .dataTables_wrapper .dt-buttons{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}.dps-page .dataTables_wrapper .dt-button{width:100%;text-align:center}}
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> DPS (Dewan Pengawas Sekolah) <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="dps-page"><div class="card dps-shell mb-4">
    <div class="card-body dps-content">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card dps-stat border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Jumlah Akun DPS</div>
                        <div class="fw-bold" style="font-size: 22px;"><?php echo e($stats['akun_dps'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dps-stat border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Jumlah Anggota DPS (Record)</div>
                        <div class="fw-bold" style="font-size: 22px;"><?php echo e($stats['anggota_dps'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dps-stat border-0 h-100">
                    <div class="card-body">
                        <div class="text-muted small">Jumlah Sekolah Terkait</div>
                        <div class="fw-bold" style="font-size: 22px;"><?php echo e($stats['sekolah_terkait'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 d-flex justify-content-end gap-2 dps-actions">
            <a href="<?php echo e(route('dps.accounts.export')); ?>" class="btn btn-outline-dark">
                <i class="bx bx-download"></i> Unduh Akun DPS (Excel)
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportDps">
                <i class="bx bx-upload"></i> Import Excel
            </button>
            <a href="<?php echo e(route('dps.create')); ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah DPS
            </a>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('dps_credentials_token')): ?>
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2" role="alert">
                <div>
                    <div class="fw-semibold">File akun DPS hasil import tersedia.</div>
                    <div class="small text-muted">Berisi email dan password untuk baris yang berhasil membuat akun baru.</div>
                </div>
                <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('dps.credentials.download', session('dps_credentials_token'))); ?>">
                    <i class="bx bx-download"></i> Download CSV Akun
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('dps_import_errors')): ?>
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">Sebagian baris gagal diproses (maks 30 ditampilkan):</div>
                <ul class="mb-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = session('dps_import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <li><?php echo e($err); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="table-panel"><div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive w-100 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 110px;">SCOD</th>
                        <th>Nama Sekolah</th>
                        <th>Daftar DPS</th>
                        <th>Unsur DPS</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?php echo e($madrasah->scod ?? '-'); ?></span></td>
                            <td class="fw-semibold"><?php echo e($madrasah->name ?? '-'); ?></td>
                            <td>
                                <ul class="list-unstyled mb-0" style="min-width:260px;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasah->dpsMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <li class="mb-2">
                                            <div><?php echo e($m->nama); ?></div>
                                            <div class="text-muted small">Periode: <?php echo e($m->periode); ?></div>
                                        </li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasah->dpsMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <li class="mb-2"><?php echo e($m->unsur); ?></li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </ul>
                            </td>
                            <td>
                                <a href="<?php echo e(route('dps.show', $madrasah->id)); ?>" class="btn btn-sm btn-outline-primary">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div></div>
    </div>
</div></div>

<!-- Modal Import DPS -->
<div class="modal fade" id="modalImportDps" tabindex="-1" aria-labelledby="modalImportDpsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('dps.import')); ?>" enctype="multipart/form-data" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportDpsLabel">Import DPS (Excel)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">File Excel (.xlsx / .xls / .csv)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="alert alert-info mb-0">
                    <div class="fw-semibold mb-1">Format kolom (heading):</div>
                    <div class="small">
                        <code>scod</code>, <code>nama_dps</code>, <code>unsur_dps</code>, <code>periode</code>
                    </div>
                    <div class="small mt-2">
                        Nilai <code>unsur_dps</code> boleh baru (akan tersimpan sesuai Excel dan otomatis muncul sebagai pilihan unsur di form).
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-upload"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
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
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"],
                language: { emptyTable: 'Belum ada data DPS.', zeroRecords: 'Data DPS tidak ditemukan.' }
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

            const normalizeColumnMenu = () => document.querySelectorAll('div.dt-button-collection, div.dt-button-collection *').forEach((item) => { item.style.setProperty('background','#fff','important'); item.style.setProperty('background-image','none','important'); item.style.setProperty('color','#192d37','important'); });
            new MutationObserver(normalizeColumnMenu).observe(document.body,{childList:true,subtree:true});
            $(document).on('click','.buttons-colvis,.buttons-columnVisibility',()=>setTimeout(normalizeColumnMenu,20));
            <?php if(session('success')): ?> Swal.fire({customClass:{popup:'dps-swal'},buttonsStyling:false,icon:'success',title:'Berhasil',text:<?php echo json_encode(session('success'), 15, 512) ?>,timer:2200,showConfirmButton:false}); <?php endif; ?>
            <?php if(session('error')): ?> Swal.fire({customClass:{popup:'dps-swal',confirmButton:'dps-swal-confirm'},buttonsStyling:false,icon:'error',title:'Gagal',text:<?php echo json_encode(session('error'), 15, 512) ?>,confirmButtonText:'Tutup'}); <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/dps/index.blade.php ENDPATH**/ ?>