<?php $__env->startSection('title'); ?>
    Admin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="<?php echo e(URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin']);
?>
<?php if($isAllowed): ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Admin <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card mb-4">
    <div class="card-body">

        <!-- Tombol aksi -->
        <div class="mb-3 d-flex justify-content-end gap-2 <?php if(strtolower(auth()->user()->role) == 'admin'): ?> d-none <?php endif; ?>">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                <i class="bx bx-plus"></i> Tambah Admin
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportAdmin">
                <i class="bx bx-plus"></i> Tambah Admin
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportAdmin">
                <i class="bx bx-upload"></i> Import Data Admin
            </button>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
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
                    <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($admin->name); ?></td>
                        <td><?php echo e($admin->email); ?></td>
                        <td><?php echo e($admin->madrasah ? $admin->madrasah->name : '-'); ?></td>
                        <td>
                            <?php if($admin->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $admin->avatar)); ?>" alt="Avatar" width="50">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditAdmin<?php echo e($admin->id); ?>">
                                    Edit
                                </button>

                                <!-- Tombol Delete -->
                                <form action="<?php echo e(route('admin.destroy', $admin->id)); ?>" method="POST" style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger <?php if(strtolower(auth()->user()->role) == 'admin'): ?> d-none <?php endif; ?>" onclick="return confirm('Yakin hapus admin ini?')">Delete</button>
                                </form>
                            </td>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center p-4">
                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                <strong>Belum ada data Admin</strong><br>
                                <small>Silakan tambahkan data admin terlebih dahulu untuk melanjutkan.</small>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
                            <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($madrasah->id); ?>"
                                    <?php echo e(isset($admin) && $admin->madrasah_id == $madrasah->id ? 'selected' : ''); ?>>
                                    <?php echo e($madrasah->name); ?> <!-- ini tampilannya saja -->
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <a href="<?php echo e(asset('template/admin_template.xlsx')); ?>" download>Download Template Excel</a>
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
<?php else: ?>
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>

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

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apk_nuist\resources\views/masterdata/admin/index.blade.php ENDPATH**/ ?>