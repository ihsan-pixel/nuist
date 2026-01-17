<?php $__env->startSection('title'); ?>
    Data Broadcast
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
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
?>
<?php if($isAllowed): ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Data Broadcast <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card mb-4">
    <div class="card-body">

        <!-- Tombol aksi -->
        <div class="mb-3 d-flex justify-content-end gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBroadcast">
                <i class="bx bx-plus"></i> Tambah Nomor Broadcast
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

        <!-- Broadcast Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-broadcast me-2"></i>Kirim Broadcast WhatsApp
                </h5>
            </div>
            <div class="card-body">
                <form id="broadcastForm">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Pilih Madrasah/Sekolah</label>
                            <select name="school_id" class="form-control" required>
                                <option value="">-- Pilih Madrasah/Sekolah --</option>
                                <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($madrasah->id); ?>"><?php echo e($madrasah->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pesan Broadcast</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Ketik pesan yang akan dikirim..." required maxlength="1000"></textarea>
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" id="sendBroadcastBtn">
                                    <i class="bx bx-send"></i> Kirim Broadcast
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="testConnectionBtn">
                                    <i class="bx bx-wifi"></i> Test Koneksi
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div id="broadcastResult" class="mt-3" style="display: none;"></div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Madrasah/Sekolah</th>
                        <th>Nomor WhatsApp</th>
                        <th>Deskripsi</th>
                        <th>Dibuat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $broadcastNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $broadcast): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($broadcast->madrasah->name); ?></td>
                        <td><?php echo e($broadcast->whatsapp_number); ?></td>
                        <td><?php echo e($broadcast->description ?? '-'); ?></td>
                        <td><?php echo e($broadcast->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditBroadcast<?php echo e($broadcast->id); ?>">
                                Edit
                            </button>

                            <!-- Tombol Delete -->
                            <form action="<?php echo e(route('admin_masterdata.broadcast-numbers.destroy', $broadcast->id)); ?>" method="POST" style="display:inline-block;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus nomor broadcast ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Broadcast -->
                    <div class="modal fade" id="modalEditBroadcast<?php echo e($broadcast->id); ?>" tabindex="-1" aria-labelledby="modalEditBroadcastLabel<?php echo e($broadcast->id); ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="<?php echo e(route('admin_masterdata.broadcast-numbers.update', $broadcast->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditBroadcastLabel<?php echo e($broadcast->id); ?>">Edit Nomor Broadcast</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Madrasah/Sekolah</label>
                                            <select name="madrasah_id" class="form-control" required>
                                                <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($madrasah->id); ?>" <?php echo e($broadcast->madrasah_id == $madrasah->id ? 'selected' : ''); ?>>
                                                        <?php echo e($madrasah->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Nomor WhatsApp</label>
                                            <input type="text" name="whatsapp_number" class="form-control" value="<?php echo e($broadcast->whatsapp_number); ?>" required placeholder="Contoh: 6281234567890">
                                            <small class="text-muted">Format: 628xxxxxxxxx (tanpa spasi atau karakter lain)</small>
                                        </div>
                                        <div class="mb-3">
                                            <label>Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi penggunaan nomor broadcast"><?php echo e($broadcast->description); ?></textarea>
                                            <small class="text-muted">Opsional, untuk menjelaskan penggunaan nomor ini</small>
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
                                <strong>Belum ada data broadcast</strong><br>
                                <small>Silakan tambahkan nomor broadcast terlebih dahulu untuk setiap madrasah/sekolah.</small>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Broadcast -->
<div class="modal fade" id="modalTambahBroadcast" tabindex="-1" aria-labelledby="modalTambahBroadcastLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('admin_masterdata.broadcast-numbers.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahBroadcastLabel">Tambah Nomor Broadcast</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Madrasah/Sekolah</label>
                        <select name="madrasah_id" class="form-control" required>
                            <option value="">-- Pilih Madrasah/Sekolah --</option>
                            <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($madrasah->id); ?>"><?php echo e($madrasah->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" class="form-control" required placeholder="Contoh: 6281234567890">
                        <small class="text-muted">Format: 628xxxxxxxxx (tanpa spasi atau karakter lain)</small>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi penggunaan nomor broadcast"></textarea>
                        <small class="text-muted">Opsional, untuk menjelaskan penggunaan nomor ini</small>
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
<?php else: ?>
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</div>
<?php endif; ?>
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

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/masterdata/broadcast-numbers/index.blade.php ENDPATH**/ ?>