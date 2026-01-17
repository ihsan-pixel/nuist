<?php $__env->startSection('title'); ?> Tenaga Pendidik <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus']);
?>
<?php if($isAllowed): ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Tenaga Pendidik <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-user me-2"></i>Tenaga Pendidik
                </h4>
            </div>
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTP"><i class="bx bx-plus"></i> Tambah Tenaga Pendidik</button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportTP"><i class="bx bx-upload"></i> Import Data TP</button>
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
            <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nuist ID</th>
                        <th>Madrasah</th>
                        <th>Status Kepegawaian</th>
                        <th>TMT</th>
                        <th>Ketugasan</th>
                        <th>Mengajar</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tenagaPendidiks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td>
                            <?php if($tp->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $tp->avatar)); ?>"
                                    alt="Avatar <?php echo e($tp->name); ?>"
                                    class="rounded-circle"
                                    width="50" height="50">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                            </td>
                            <td><?php echo e($tp->name); ?></td>
                            <td><?php echo e($tp->email); ?></td>
                            <td><?php echo e($tp->nuist_id ?? '-'); ?></td>
                            <td><?php echo e($tp->madrasah?->name ?? '-'); ?></td>
                            <td><?php echo e($tp->statusKepegawaian->name ?? '-'); ?></td>
                            <td><?php echo e($tp->tmt ? \Carbon\Carbon::parse($tp->tmt)->translatedFormat('j F Y') : '-'); ?></td>
                            <td><?php echo e($tp->ketugasan ?? '-'); ?></td>
                            <td><?php echo e($tp->mengajar ?? '-'); ?></td>
                            <td>
                                <?php if(strtolower(auth()->user()->role) == 'admin'): ?>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalViewTP<?php echo e($tp->id); ?>">
                                        <i class="bx bx-show"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditTP<?php echo e($tp->id); ?>">
                                        <i class="bx bx-edit"></i> Edit
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditTP<?php echo e($tp->id); ?>">Edit</button>
                                    <form action="<?php echo e(route('tenaga-pendidik.destroy', $tp->id)); ?>" method="POST" style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Modal Edit Tenaga Pendidik -->
                        <div class="modal fade" id="modalEditTP<?php echo e($tp->id); ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <form action="<?php echo e(route('tenaga-pendidik.update', $tp->id)); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Tenaga Pendidik</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body row g-3">
                                            <div class="col-md-6">
                                                <label>Nama Lengkap & Gelar</label>
                                                <input type="text" name="nama" class="form-control" value="<?php echo e($tp->name); ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo e($tp->email); ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Password (Kosongkan jika tidak diubah)</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" class="form-control" value="<?php echo e($tp->tempat_lahir); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo e(old('tanggal_lahir', $tp->tanggal_lahir ? $tp->tanggal_lahir->format('Y-m-d') : '')); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>No HP</label>
                                                <input type="text" name="no_hp" class="form-control" value="<?php echo e($tp->no_hp); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Kartu NU</label>
                                                <input type="text" name="kartanu" class="form-control" value="<?php echo e($tp->kartanu); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>NIP Ma'arif</label>
                                                <input type="text" name="nip" class="form-control" value="<?php echo e($tp->nip); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>NUPTK</label>
                                                <input type="text" name="nuptk" class="form-control" value="<?php echo e($tp->nuptk); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>NPK</label>
                                                <input type="text" name="npk" class="form-control" value="<?php echo e($tp->npk); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Madrasah</label>
                                                <input type="text" class="form-control" value="<?php echo e($tp->madrasah ? $tp->madrasah->name : '-'); ?>" readonly>
                                                <input type="hidden" name="madrasah_id" value="<?php echo e($tp->madrasah_id); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Status Kepegawaian</label>
                                                <select name="status_kepegawaian_id" class="form-control">
                                                    <option value="">-- Pilih Status Kepegawaian --</option>
                                                    <?php $__currentLoopData = $statusKepegawaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($status->id); ?>" <?php echo e($tp->status_kepegawaian_id == $status->id ? 'selected' : ''); ?>>
                                                            <?php echo e($status->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>TMT</label>
                                                <input type="date" name="tmt" class="form-control" value="<?php echo e(old('tmt', $tp->tmt ? $tp->tmt->format('Y-m-d') : '')); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Pendidikan Terakhir</label>
                                                <input type="text" name="pendidikan_terakhir" class="form-control" value="<?php echo e($tp->pendidikan_terakhir); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Tahun Lulus</label>
                                                <input type="number" name="tahun_lulus" class="form-control" value="<?php echo e($tp->tahun_lulus); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Program Studi</label>
                                                <input type="text" name="program_studi" class="form-control" value="<?php echo e($tp->program_studi); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Foto Profile</label>
                                                <input type="file" name="avatar" class="form-control">
                                                <small class="text-muted">Opsional, boleh dikosongkan</small>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Ketugasan</label>
                                                <select name="ketugasan" class="form-control">
                                                    <option value="">-- Pilih Ketugasan --</option>
                                                    <option value="tenaga pendidik" <?php echo e(old('ketugasan', $tp->ketugasan) == 'tenaga pendidik' ? 'selected' : ''); ?>>Tenaga Pendidik</option>
                                                    <option value="penjaga sekolah" <?php echo e(old('ketugasan', $tp->ketugasan) == 'penjaga sekolah' ? 'selected' : ''); ?>>Penjaga Sekolah</option>
                                                    <option value="kepala madrasah/sekolah" <?php echo e(old('ketugasan', $tp->ketugasan) == 'kepala madrasah/sekolah' ? 'selected' : ''); ?>>Kepala Madrasah/Sekolah</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Mengajar</label>
                                                <input type="text" name="mengajar" class="form-control" value="<?php echo e(old('mengajar', $tp->mengajar)); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Pemenuhan Beban Kerja di Sekolah/Madrasah Lain</label>
                                                <select name="pemenuhan_beban_kerja_lain" id="pemenuhan_beban_kerja_lain_edit<?php echo e($tp->id); ?>" class="form-control">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="1" <?php echo e($tp->pemenuhan_beban_kerja_lain ? 'selected' : ''); ?>>Iya</option>
                                                    <option value="0" <?php echo e(!$tp->pemenuhan_beban_kerja_lain ? 'selected' : ''); ?>>Tidak</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6" id="madrasah_tambahan_edit_container<?php echo e($tp->id); ?>" style="display: <?php echo e($tp->pemenuhan_beban_kerja_lain ? 'block' : 'none'); ?>;">
                                                <label>Madrasah Tambahan</label>
                                                <select name="madrasah_id_tambahan" class="form-control">
                                                    <option value="">-- Pilih Madrasah --</option>
                                                    <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($madrasah->id); ?>" <?php echo e($tp->madrasah_id_tambahan == $madrasah->id ? 'selected' : ''); ?>><?php echo e($madrasah->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label>Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="2"><?php echo e($tp->alamat); ?></textarea>
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

                        <!-- Modal View Tenaga Pendidik -->
                        <div class="modal fade" id="modalViewTP<?php echo e($tp->id); ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Tenaga Pendidik</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Profile Header -->
                                        <div class="text-center mb-4">
                                            <?php if($tp->avatar): ?>
                                                <img src="<?php echo e(asset('storage/app/public/' . $tp->avatar)); ?>"
                                                    alt="Foto <?php echo e($tp->name); ?>"
                                                    class="rounded-circle border border-3 border-primary mb-3"
                                                    width="120" height="120"
                                                    style="object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                    style="width: 120px; height: 120px;">
                                                    <i class="bx bx-user text-muted" style="font-size: 3rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                            <h4 class="mb-1"><?php echo e($tp->name); ?></h4>
                                            <p class="text-muted mb-0"><?php echo e($tp->email); ?></p>
                                            <?php if($tp->nuist_id): ?>
                                                <small class="text-primary fw-bold">NUist ID: <?php echo e($tp->nuist_id); ?></small>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Personal Information -->
                                        <div class="card border-0 bg-light mb-3">
                                            <div class="card-header bg-white border-bottom-0">
                                                <h6 class="mb-0 text-primary">
                                                    <i class="bx bx-user-circle me-2"></i>Informasi Pribadi
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Tempat Lahir</label>
                                                        <p class="mb-0"><?php echo e($tp->tempat_lahir ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Tanggal Lahir</label>
                                                        <p class="mb-0"><?php echo e($tp->tanggal_lahir ? $tp->tanggal_lahir->translatedFormat('j F Y') : '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">No HP</label>
                                                        <p class="mb-0"><?php echo e($tp->no_hp ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Kartu NU</label>
                                                        <p class="mb-0"><?php echo e($tp->kartanu ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold text-muted small">Alamat</label>
                                                        <p class="mb-0"><?php echo e($tp->alamat ?? '-'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Professional Information -->
                                        <div class="card border-0 bg-light mb-3">
                                            <div class="card-header bg-white border-bottom-0">
                                                <h6 class="mb-0 text-primary">
                                                    <i class="bx bx-briefcase me-2"></i>Informasi Kepegawaian
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">NIP Ma'arif</label>
                                                        <p class="mb-0"><?php echo e($tp->nip ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">NUPTK</label>
                                                        <p class="mb-0"><?php echo e($tp->nuptk ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">NPK</label>
                                                        <p class="mb-0"><?php echo e($tp->npk ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Status Kepegawaian</label>
                                                        <p class="mb-0"><?php echo e($tp->statusKepegawaian->name ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">TMT</label>
                                                        <p class="mb-0"><?php echo e($tp->tmt ? $tp->tmt->translatedFormat('j F Y') : '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Ketugasan</label>
                                                        <p class="mb-0"><?php echo e($tp->ketugasan ?? '-'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Education Information -->
                                        <div class="card border-0 bg-light mb-3">
                                            <div class="card-header bg-white border-bottom-0">
                                                <h6 class="mb-0 text-primary">
                                                    <i class="bx bx-graduation me-2"></i>Informasi Pendidikan
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Pendidikan Terakhir</label>
                                                        <p class="mb-0"><?php echo e($tp->pendidikan_terakhir ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Tahun Lulus</label>
                                                        <p class="mb-0"><?php echo e($tp->tahun_lulus ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Program Studi</label>
                                                        <p class="mb-0"><?php echo e($tp->program_studi ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Mengajar</label>
                                                        <p class="mb-0"><?php echo e($tp->mengajar ?? '-'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Assignment Information -->
                                        <div class="card border-0 bg-light mb-3">
                                            <div class="card-header bg-white border-bottom-0">
                                                <h6 class="mb-0 text-primary">
                                                    <i class="bx bx-building me-2"></i>Informasi Penugasan
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Madrasah Utama</label>
                                                        <p class="mb-0"><?php echo e($tp->madrasah ? $tp->madrasah->name : '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">Pemenuhan Beban Kerja Lain</label>
                                                        <p class="mb-0"><?php echo e($tp->pemenuhan_beban_kerja_lain ? 'Ya' : 'Tidak'); ?></p>
                                                    </div>
                                                    <?php if($tp->pemenuhan_beban_kerja_lain): ?>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold text-muted small">Madrasah Tambahan</label>
                                                        <p class="mb-0"><?php echo e($tp->madrasahTambahan ? $tp->madrasahTambahan->name : '-'); ?></p>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="12" class="text-center p-4">
                                <div class="alert alert-info d-inline-block text-center" role="alert">
                                    <i class="bx bx-info-circle bx-lg me-2"></i>
                                    <strong>Belum ada data Tenaga Pendidik</strong><br>
                                    <small>Silakan tambahkan data tenaga pendidik terlebih dahulu untuk melanjutkan.</small>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahTP" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="<?php echo e(route('tenaga-pendidik.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tenaga Pendidik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">

                    <div class="col-md-6">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Kartu NU</label>
                        <input type="text" name="kartanu" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>NIP Ma'arif</label>
                        <input type="text" name="nip" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>NUPTK</label>
                        <input type="text" name="nuptk" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>NPK</label>
                        <input type="text" name="npk" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Madrasah</label>
                        <select name="madrasah_id" class="form-control">
                            <option value="">-- Pilih Madrasah --</option>
                            <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($madrasah->id); ?>"><?php echo e($madrasah->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Status Kepegawaian</label>
                        <select name="status_kepegawaian_id" class="form-control">
                            <option value="">-- Pilih Status Kepegawaian --</option>
                            <?php $__currentLoopData = $statusKepegawaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status->id); ?>"><?php echo e($status->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>TMT</label>
                        <input type="date" name="tmt" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Program Studi</label>
                        <input type="text" name="program_studi" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Foto Profile</label>
                        <input type="file" name="avatar" class="form-control">
                        <small class="text-muted">Opsional, boleh dikosongkan</small>
                    </div>

                    <div class="col-md-6">
                        <label>Ketugasan</label>
                        <select name="ketugasan" class="form-control">
                            <option value="">-- Pilih Ketugasan --</option>
                            <option value="tenaga pendidik">Tenaga Pendidik</option>
                            <option value="penjaga sekolah">Penjaga Sekolah</option>
                            <option value="kepala madrasah/sekolah">Kepala Madrasah/Sekolah</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Mengajar</label>
                        <input type="text" name="mengajar" class="form-control">
                    </div>

                                            <div class="col-md-6">
                                                <label>Pemenuhan Beban Kerja di Sekolah/Madrasah Lain</label>
                                                <select name="pemenuhan_beban_kerja_lain" id="pemenuhan_beban_kerja_lain_add" class="form-control">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="1">Iya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6" id="madrasah_tambahan_add_container" style="display: none;">
                                                <label>Madrasah Tambahan</label>
                                                <select name="madrasah_id_tambahan" class="form-control">
                                                    <option value="">-- Pilih Madrasah --</option>
                                                    <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($madrasah->id); ?>"><?php echo e($madrasah->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label>Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="2"></textarea>
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

<!-- Modal Import -->
<div class="modal fade" id="modalImportTP" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-height: 90vh; overflow-y: auto;">
        <form action="<?php echo e(route('tenaga-pendidik.import')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-upload me-2"></i>Import Data Tenaga Pendidik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">
                                    <i class="bx bx-file me-1"></i>Pilih File Excel (.xlsx, .xls, .csv)
                                </label>
                                <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            </div>

                            <div class="alert alert-info">
                                <strong><i class="bx bx-info-circle me-1"></i>Catatan Penting:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>File Excel HARUS memiliki baris header dengan nama kolom yang sesuai</li>
                                    <li>Password akan dibuat otomatis menggunakan NIP (jika ada) atau default 'nuist123'</li>
                                    <li>Email harus unik dan belum terdaftar</li>
                                    <li>Gunakan ID numerik untuk madrasah_id dan status_kepegawaian_id</li>
                                    <li><strong>PERUBAHAN BARU:</strong> Kolom 'ketugasan' menggunakan enum: 'tenaga pendidik' atau 'kepala madrasah/sekolah'</li>
                                    <li><strong>PERUBAHAN BARU:</strong> Kolom 'mengajar' wajib diisi</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bx bx-download me-1"></i>Template & Panduan</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo e(asset('template/tenaga_pendidik_template.xlsx')); ?>"
                                           class="btn btn-outline-primary btn-sm" download>
                                            <i class="bx bx-download me-1"></i>Download Template Excel
                                        </a>
                                        <a href="<?php echo e(asset('template/tenaga_pendidik_template.csv')); ?>"
                                           class="btn btn-outline-success btn-sm" download>
                                            <i class="bx bx-user me-1"></i>Download Template CSV
                                        </a>
                                        
                                        <a href="<?php echo e(asset('template/tenaga_pendidik_import_structure.txt')); ?>"
                                           class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="bx bx-file-blank me-1"></i>Lihat Struktur Data
                                        </a>
                                        
                                        <a href="<?php echo e(asset('template/panduan_import_tenaga_pendidik.txt')); ?>"
                                           class="btn btn-outline-secondary btn-sm" target="_blank">
                                            <i class="bx bx-book me-1"></i>Baca Panduan Lengkap
                                        </a>
                                    </div>

                                    <hr class="my-3">

                                    <div class="text-muted small">
                                        <strong>Kolom Wajib:</strong><br>
                                        nama, email, tempat_lahir, tanggal_lahir, no_hp, kartanu, nip, nuptk, madrasah_id, pendidikan_terakhir, tahun_lulus, program_studi, status_kepegawaian_id, tmt, ketugasan, mengajar, alamat<br>
                                        <strong>Kolom Opsional:</strong><br>
                                        npk, pemenuhan_beban_kerja_lain, madrasah_id_tambahan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="bx bx-list-ul me-1"></i>Referensi ID</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>ID Madrasah (Contoh):</strong>
                                            <div class="small text-muted mt-1">
                                                10 - SMA Ma'arif 1 Sleman<br>
                                                16 - SMK Ma'arif 1 Sleman<br>
                                                23 - SMK Ma'arif 1 Yogyakarta<br>
                                                <em>...dan lainnya (lihat struktur data lengkap)</em>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>ID Status Kepegawaian:</strong>
                                            <div class="small text-muted mt-1">
                                                1 - PNS Sertifikasi<br>
                                                3 - GTY Sertifikasi<br>
                                                5 - GTY Non Sertifikasi<br>
                                                6 - GTT<br>
                                                <em>...dan lainnya (lihat struktur data lengkap)</em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-upload me-1"></i>Import Data
                    </button>
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
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"]
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

            // Handle add modal
            $('#pemenuhan_beban_kerja_lain_add').change(function() {
                if ($(this).val() == '1') {
                    $('#madrasah_tambahan_add_container').show();
                } else {
                    $('#madrasah_tambahan_add_container').hide();
                }
            });

            // Handle edit modals
            <?php $__currentLoopData = $tenagaPendidiks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                $('#pemenuhan_beban_kerja_lain_edit<?php echo e($tp->id); ?>').change(function() {
                    if ($(this).val() == '1') {
                        $('#madrasah_tambahan_edit_container<?php echo e($tp->id); ?>').show();
                    } else {
                        $('#madrasah_tambahan_edit_container<?php echo e($tp->id); ?>').hide();
                    }
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/masterdata/tenaga-pendidik/index.blade.php ENDPATH**/ ?>