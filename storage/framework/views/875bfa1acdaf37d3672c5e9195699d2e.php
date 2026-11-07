<?php $__env->startSection('title'); ?>
    Admin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" />
<style>
 .admin-page{--a-green:#08745a;--a-ink:#192d37;--a-muted:#6c7c86;position:relative;isolation:isolate;padding:8px 0 24px}.admin-page:before,.admin-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:admin-float 10s ease-in-out infinite}.admin-page:before{width:280px;height:280px;right:-100px;top:-90px;background:rgba(42,190,153,.15)}.admin-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.11);animation-direction:reverse;animation-duration:12s}body:has(.admin-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.admin-page) .main-content{background:transparent!important}.admin-page .admin-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden;animation:admin-in .72s both}.admin-page .admin-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:22px 24px;border-bottom:1px solid #e5edef}.admin-page .eyebrow{margin:0 0 4px;color:var(--a-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.admin-page h1{margin:0;color:var(--a-ink);font-size:22px;font-weight:700}.admin-page .subtitle{margin:5px 0 0;color:var(--a-muted);font-size:12px}.admin-page .admin-actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:8px}.admin-page .admin-actions .btn{border:0;border-radius:12px;padding:.62rem .85rem;box-shadow:0 8px 18px rgba(8,116,90,.14)}.admin-page .admin-content{padding:20px 24px 24px}.admin-page .table-panel{border:1px solid #e5edef;border-radius:16px;background:rgba(255,255,255,.58);overflow:hidden}.admin-page table.dataTable{width:100%!important;table-layout:fixed;min-width:760px}.admin-page table.dataTable thead th{border:0;border-bottom:1px solid #e5edef;background:#f1f6f6;color:var(--a-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.admin-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle;white-space:normal!important;overflow-wrap:anywhere}.admin-page table.dataTable tbody tr:last-child td{border-bottom:0}.admin-page .admin-avatar{width:38px;height:38px;object-fit:cover;border-radius:50%;border:3px solid #e4f5ee}.admin-page .admin-name{display:block;color:var(--a-ink);font-weight:700}.admin-page .admin-actions-cell{display:flex;flex-wrap:wrap;gap:6px}.admin-page .admin-actions-cell .btn{border-radius:8px;font-size:11px;padding:.34rem .55rem}.admin-page .btn-warning{color:#6d4b00;background:#fff4d6;border-color:#ffe5a0}.admin-page .btn-danger{color:#b33b3b;background:#fff0f0;border-color:#ffd2d2}.admin-page .dataTables_wrapper{padding:16px}.admin-page .dataTables_wrapper .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}.admin-page .dataTables_wrapper .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem}.admin-page .dataTables_wrapper .dt-button:nth-child(1){background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.admin-page .dataTables_wrapper .dt-button:nth-child(2){background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.admin-page .dataTables_wrapper .dt-button:nth-child(3){background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.admin-page .dataTables_wrapper .dt-button:nth-child(4){background:linear-gradient(90deg,#d97706,#fbbf24)!important}.admin-page .dataTables_wrapper .dt-button:nth-child(5){background:linear-gradient(90deg,#db2777,#f472b6)!important}.admin-page .dt-button-collection{padding:10px!important;border:1px solid #e3eaec!important;border-radius:16px!important;background:#f8fafb!important;box-shadow:0 12px 28px rgba(32,70,78,.14)!important}.admin-page .dt-button-collection .dt-button{display:block;width:100%;margin:0 0 6px;background:#fff!important;background-image:none!important;color:var(--a-ink)!important;border:0!important;border-radius:14px!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important;text-align:left;font-size:15px;font-weight:600}.admin-page .empty-state{padding:46px 20px!important;color:var(--a-muted)}.admin-page .empty-state .bx{display:block;color:#9ac9b8;font-size:38px;margin-bottom:8px}.admin-page .modal-content{border:1px solid rgba(255,255,255,.9);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}.admin-page .modal-header{border-bottom:1px solid #e5edef;background:#f6faf9;padding:18px 22px}.admin-page .modal-body{padding:22px}.admin-page .modal-body label{display:block;margin-bottom:6px;color:var(--a-ink);font-size:12px;font-weight:600}.admin-page .modal-body .form-control,.admin-page .modal-body select{border:1px solid #dce7e9;border-radius:9px;font-size:13px;padding:.55rem .7rem}.admin-page .modal-footer{border-top:1px solid #e5edef;padding:14px 22px}.admin-page .modal-footer .btn{border-radius:9px;font-size:12px}.admin-swal{width:min(420px,calc(100% - 32px))!important;padding:26px 28px 24px!important;border-radius:22px!important;background:rgba(255,255,255,.9)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important}.admin-swal-confirm{border:0!important;border-radius:10px!important;background:#08745a!important;color:#fff!important;padding:10px 16px!important}.admin-swal-cancel{border:1px solid #dce7e9!important;border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important;padding:10px 16px!important}@keyframes admin-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@keyframes admin-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(0,14px,0)}}
 @media(max-width:767.98px){.admin-page .admin-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.admin-page .admin-actions{width:100%;justify-content:stretch}.admin-page .admin-actions .btn{flex:1}.admin-page .admin-content{padding:14px}.admin-page .dataTables_wrapper{padding:10px}.admin-page .modal-dialog{margin:.75rem}}
    .admin-page .dataTables_wrapper .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important;border-color:#0f766e!important}.admin-page .dataTables_wrapper .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important;border-color:#2563eb!important}.admin-page .dataTables_wrapper .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important;border-color:#7c3aed!important}.admin-page .dataTables_wrapper .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important;border-color:#d97706!important}.admin-page .dataTables_wrapper .buttons-colvis,.admin-page .dataTables_wrapper .buttons-columnVisibility{background:linear-gradient(90deg,#db2777,#f472b6)!important;border-color:#db2777!important}
    div.dt-button-collection{width:245px!important;padding:10px!important;border:1px solid #e3eaec!important;border-radius:16px!important;background:#f8fafb!important;background-image:none!important;box-shadow:0 12px 28px rgba(32,70,78,.14)!important}div.dt-button-collection button.dt-button,div.dt-button-collection button.buttons-columnVisibility{display:block!important;width:100%!important;min-height:48px!important;margin:0 0 6px!important;padding:13px 18px!important;border:0!important;border-radius:14px!important;background:#fff!important;background-color:#fff!important;background-image:none!important;color:#192d37!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important;text-align:left!important;font-size:16px!important;font-weight:600!important}div.dt-button-collection button.dt-button:hover,div.dt-button-collection button.buttons-columnVisibility:hover{background:#f1faf6!important;background-color:#f1faf6!important;color:#08745a!important}
    body:has(.admin-page) div.dt-button-collection button,body:has(.admin-page) div.dt-button-collection .dt-button,body:has(.admin-page) div.dt-button-collection .buttons-columnVisibility{background:#fff!important;background-color:#fff!important;background-image:none!important;color:#192d37!important;border:0!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important}
    body:has(.admin-page) div.dt-button-collection button:hover,body:has(.admin-page) div.dt-button-collection .dt-button:hover{background:#f1faf6!important;background-color:#f1faf6!important;color:#08745a!important}
 .admin-page .table-panel{overflow-x:auto}.admin-page table.dataTable{table-layout:auto;min-width:900px}.admin-page table.dataTable th:nth-child(1),.admin-page table.dataTable td:nth-child(1){width:54px}.admin-page table.dataTable th:nth-child(2),.admin-page table.dataTable td:nth-child(2){min-width:220px}.admin-page table.dataTable th:nth-child(3),.admin-page table.dataTable td:nth-child(3){min-width:230px}.admin-page table.dataTable th:nth-child(4),.admin-page table.dataTable td:nth-child(4){min-width:220px}.admin-page table.dataTable th:nth-child(5),.admin-page table.dataTable td:nth-child(5){width:90px}.admin-page table.dataTable th:last-child,.admin-page table.dataTable td:last-child{width:125px}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus']);
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAllowed): ?>
<div class="admin-page">
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Admin <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="admin-shell mb-4">
    <div class="admin-toolbar"><div><p class="eyebrow">Master Data</p><h1>Data Admin</h1><p class="subtitle">Kelola operator admin dan penugasan madrasah NUIST.</p></div><div class="admin-actions">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(strtolower(auth()->user()->role) != 'admin'): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin"><i class="bx bx-plus"></i> Tambah Admin</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportAdmin"><i class="bx bx-upload"></i> Import Data Admin</button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div></div>
    <div class="admin-content">

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

        <div class="table-panel"><div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Operator</th>
                        <th>Email</th>
                        <th>Madrasah</th>
                        <th>Avatar</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><span class="admin-name"><?php echo e($admin->name); ?></span></td>
                        <td><?php echo e($admin->email); ?></td>
                        <td><?php echo e($admin->madrasah ? $admin->madrasah->name : '-'); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($admin->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $admin->avatar)); ?>" alt="Avatar" class="admin-avatar">
                            <?php else: ?>
                                -
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                            <td><div class="admin-actions-cell">
                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditAdmin<?php echo e($admin->id); ?>">
                                    Edit
                                </button>

                                <!-- Tombol Delete -->
                                <form action="<?php echo e(route('admin.destroy', $admin->id)); ?>" method="POST" style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger <?php if(strtolower(auth()->user()->role) == 'admin'): ?> d-none <?php endif; ?>">Hapus</button>
                                </form>
                            </div></td>
                    </tr>

                    <!-- Modal Edit Admin -->
                    <div class="modal fade" id="modalEditAdmin<?php echo e($admin->id); ?>" tabindex="-1" aria-labelledby="modalEditAdminLabel<?php echo e($admin->id); ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="<?php echo e(route('admin.update', $admin->id)); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditAdminLabel<?php echo e($admin->id); ?>">Edit Admin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Nama Operator</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo e($admin->name); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo e($admin->email); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Password (Kosongkan jika tidak ingin diubah)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label>Madrasah</label>
                                            <input type="text" class="form-control" value="<?php echo e($admin->madrasah ? $admin->madrasah->name : '-'); ?>" readonly>
                                            <input type="hidden" name="madrasah_id" value="<?php echo e($admin->madrasah_id); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label>Foto (Avatar)</label>
                                            <input type="file" name="avatar" class="form-control" accept="image/*">
                                            <small class="text-muted">Opsional, boleh dikosongkan</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="6" class="text-center empty-state">
                            <div role="status">
                                <i class="bx bx-user"></i>
                                <strong class="d-block">Belum ada data Admin</strong>
                                <small>Silakan tambahkan data admin terlebih dahulu untuk melanjutkan.</small>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div></div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-labelledby="modalTambahAdminLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('admin.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAdminLabel">Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Operator</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Madrasah</label>
                        <select name="madrasah_id" class="form-control" required>
                            <option value="">-- Pilih Madrasah --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($madrasah->id); ?>"
                                    <?php echo e(isset($admin) && $admin->madrasah_id == $madrasah->id ? 'selected' : ''); ?>>
                                    <?php echo e($madrasah->name); ?> <!-- ini tampilannya saja -->
                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Foto (Avatar)</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*">
                        <small class="text-muted">Opsional, boleh dikosongkan</small>
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

<!-- Modal Import Admin -->
<div class="modal fade" id="modalImportAdmin" tabindex="-1" aria-labelledby="modalImportAdminLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('admin.import')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportAdminLabel">Import Data Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih File (Excel/CSV)</label>
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">
                            Gunakan template file sesuai format data admin. Urutan kolom harus: nama, email, no_hp, madrasah_id.
                            <a href="<?php echo e(url('public/template/admin_template.xlsx')); ?>" download>Download Template Excel</a>
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

<script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

<script>
$(document).ready(function () {
    $('.modal').appendTo(document.body);
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    const normalizeAdminColumnMenu = () => {
        document.querySelectorAll('div.dt-button-collection, div.dt-button-collection *').forEach((item) => {
            item.style.setProperty('background', '#fff', 'important');
            item.style.setProperty('background-color', '#fff', 'important');
            item.style.setProperty('background-image', 'none', 'important');
            item.style.setProperty('color', '#192d37', 'important');
            item.style.setProperty('border-color', 'transparent', 'important');
            item.style.setProperty('box-shadow', 'none', 'important');
        });
    };
    new MutationObserver(normalizeAdminColumnMenu).observe(document.body, {childList:true, subtree:true});
    $(document).on('click', '.buttons-colvis,.buttons-columnVisibility', () => setTimeout(normalizeAdminColumnMenu, 20));

    <?php if(session('success')): ?>
        Swal.fire({customClass:{popup:'admin-swal',confirmButton:'admin-swal-confirm'},buttonsStyling:false,icon:'success',title:'Berhasil',text:<?php echo json_encode(session('success'), 15, 512) ?>,timer:2200,showConfirmButton:false});
    <?php endif; ?>

    <?php if(session('error')): ?>
        Swal.fire({customClass:{popup:'admin-swal',confirmButton:'admin-swal-confirm'},buttonsStyling:false,icon:'error',title:'Gagal',text:<?php echo json_encode(session('error'), 15, 512) ?>});
    <?php endif; ?>
});

$(document).on('submit','form[action*="/admin/"]',function(event){if(!this.querySelector('input[name="_method"][value="DELETE"]'))return;event.preventDefault();const form=this;Swal.fire({customClass:{popup:'admin-swal',confirmButton:'admin-swal-confirm',cancelButton:'admin-swal-cancel'},buttonsStyling:false,icon:'warning',title:'Hapus admin?',text:'Data yang dihapus tidak dapat dikembalikan.',showCancelButton:true,confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(result=>{if(result.isConfirmed)form.submit()})});
    </script>
    <?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/admin/index.blade.php ENDPATH**/ ?>