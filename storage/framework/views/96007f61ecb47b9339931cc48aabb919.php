<?php $__env->startSection('title', 'Detail Profile Madrasah'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Profile Madrasah/Sekolah <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Detail <?php echo e($madrasah->name); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-school me-2"></i>Detail Profile Madrasah
                </h4>
            </div>
            <div class="card-body">
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

                <!-- Data Madrasah -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <?php if($madrasah->logo): ?>
                        <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" class="rounded mx-auto d-block mb-3" alt="<?php echo e($madrasah->name); ?>" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                        <div class="rounded mx-auto d-block mb-3 bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                            <i class="bx bx-school bx-lg text-muted"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold"><?php echo e($madrasah->name); ?></h3>
                        <p class="text-muted"><?php echo e($madrasah->kabupaten ? 'Kabupaten ' . $madrasah->kabupaten : ''); ?></p>
                        <p class="text-muted"><?php echo e($madrasah->alamat ?? 'Alamat tidak tersedia'); ?></p>
                        <p class="text-muted"><strong>Hari KBM:</strong> <?php echo e($madrasah->hari_kbm ? $madrasah->hari_kbm . ' hari' : 'Tidak ditentukan'); ?></p>
                        <?php if($madrasah->map_link): ?>
                        <a href="<?php echo e($madrasah->map_link); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-map me-1"></i> Lihat di Peta
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Kepala Sekolah -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-2">Kepala Sekolah/Madrasah</h5>
                        <?php if($kepalaSekolah): ?>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <?php if($kepalaSekolah->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $kepalaSekolah->avatar)); ?>" class="rounded-circle" alt="<?php echo e($kepalaSekolah->name); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <?php echo e(substr($kepalaSekolah->name, 0, 1)); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?php echo e($kepalaSekolah->name); ?></h6>
                                <small class="text-muted"><?php echo e($kepalaSekolah->email); ?></small>
                            </div>
                        </div>
                        <?php else: ?>
                        <p class="text-muted">Kepala sekolah belum ditetapkan.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Jumlah TP berdasarkan Status Kepegawaian -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-3">Jumlah Tenaga Pendidik berdasarkan Status Kepegawaian</h5>
                        <div class="row">
                            <?php $__empty_1 = true; $__currentLoopData = $tpByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-3 mb-2">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted mb-1"><?php echo e($status ?? 'Tidak Diketahui'); ?></h6>
                                        <h4 class="fw-bold text-primary"><?php echo e($count); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12">
                                <p class="text-muted">Belum ada data tenaga pendidik.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Data Table Tenaga Pendidik -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-3">Data Tenaga Pendidik</h5>
                        <div class="table-responsive">
                            <table id="tenaga-pendidik-table" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status Kepegawaian</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $madrasah->tenagaPendidikUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($tp->name); ?></td>
                                        <td><?php echo e($tp->email); ?></td>
                                        <td><?php echo e($tp->statusKepegawaian->name ?? '-'); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info view-btn"
                                                    data-name="<?php echo e($tp->name); ?>"
                                                    data-email="<?php echo e($tp->email); ?>"
                                                    data-status="<?php echo e($tp->statusKepegawaian->name ?? '-'); ?>"
                                                    data-no_hp="<?php echo e($tp->no_hp ?? '-'); ?>"
                                                    data-nip="<?php echo e($tp->nip ?? '-'); ?>"
                                                    data-nuptk="<?php echo e($tp->nuptk ?? '-'); ?>"
                                                    data-avatar="<?php echo e($tp->avatar ? asset('storage/app/public/' . $tp->avatar) : asset('build/images/users/avatar-11.jpg')); ?>"
                                                    data-tempat_lahir="<?php echo e($tp->tempat_lahir ?? '-'); ?>"
                                                    data-tanggal_lahir="<?php echo e($tp->tanggal_lahir ? $tp->tanggal_lahir->format('d-m-Y') : '-'); ?>"
                                                    data-alamat="<?php echo e($tp->alamat ?? '-'); ?>"
                                                    data-kartanu="<?php echo e($tp->kartanu ?? '-'); ?>"
                                                    data-npk="<?php echo e($tp->npk ?? '-'); ?>"
                                                    data-pendidikan_terakhir="<?php echo e($tp->pendidikan_terakhir ?? '-'); ?>"
                                                    data-tahun_lulus="<?php echo e($tp->tahun_lulus ?? '-'); ?>"
                                                    data-program_studi="<?php echo e($tp->program_studi ?? '-'); ?>"
                                                    data-tmt="<?php echo e($tp->tmt ? $tp->tmt->format('d-m-Y') : '-'); ?>"
                                                    data-ketugasan="<?php echo e($tp->ketugasan ?? '-'); ?>"
                                                    data-mengajar="<?php echo e($tp->mengajar ?? '-'); ?>"
                                                    data-jabatan="<?php echo e($tp->jabatan ?? '-'); ?>">
                                                <i class="bx bx-show"></i> View
                                            </button>
                                            <?php if(auth()->user()->role == 'super_admin'): ?>
                                            <button class="btn btn-sm btn-warning edit-btn ms-1"
                                                    data-id="<?php echo e($tp->id); ?>"
                                                    data-name="<?php echo e($tp->name); ?>"
                                                    data-email="<?php echo e($tp->email); ?>"
                                                    data-tempat_lahir="<?php echo e($tp->tempat_lahir ?? ''); ?>"
                                                    data-tanggal_lahir="<?php echo e($tp->tanggal_lahir ? $tp->tanggal_lahir->format('Y-m-d') : ''); ?>"
                                                    data-no_hp="<?php echo e($tp->no_hp ?? ''); ?>"
                                                    data-kartanu="<?php echo e($tp->kartanu ?? ''); ?>"
                                                    data-nip="<?php echo e($tp->nip ?? ''); ?>"
                                                    data-nuptk="<?php echo e($tp->nuptk ?? ''); ?>"
                                                    data-npk="<?php echo e($tp->npk ?? ''); ?>"
                                                    data-madrasah_id="<?php echo e($tp->madrasah_id); ?>"
                                                    data-status_kepegawaian_id="<?php echo e($tp->status_kepegawaian_id); ?>"
                                                    data-tmt="<?php echo e($tp->tmt ? $tp->tmt->format('Y-m-d') : ''); ?>"
                                                    data-pendidikan_terakhir="<?php echo e($tp->pendidikan_terakhir ?? ''); ?>"
                                                    data-tahun_lulus="<?php echo e($tp->tahun_lulus ?? ''); ?>"
                                                    data-program_studi="<?php echo e($tp->program_studi ?? ''); ?>"
                                                    data-ketugasan="<?php echo e($tp->ketugasan ?? ''); ?>"
                                                    data-mengajar="<?php echo e($tp->mengajar ?? ''); ?>"
                                                    data-alamat="<?php echo e($tp->alamat ?? ''); ?>"
                                                    data-pemenuhan_beban_kerja_lain="<?php echo e($tp->pemenuhan_beban_kerja_lain ? '1' : '0'); ?>"
                                                    data-madrasah_id_tambahan="<?php echo e($tp->madrasah_id_tambahan); ?>">
                                                <i class="bx bx-edit"></i> Edit
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center p-4">
                                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                                <strong>Belum ada data tenaga pendidik</strong>
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
        </div>
    </div>
</div>

<!-- Modal for Editing Tenaga Pendidik -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="<?php echo e(route('tenaga-pendidik.update', ':id')); ?>" method="POST" enctype="multipart/form-data" id="editForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="bx bx-edit me-2"></i>Edit Tenaga Pendidik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control" id="edit-nama" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" id="edit-email" required>
                    </div>

                    <div class="col-md-6">
                        <label>Password (Kosongkan jika tidak diubah)</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="edit-password">
                            <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                <i class="bx bx-show"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" id="edit-tempat_lahir">
                    </div>

                    <div class="col-md-6">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" id="edit-tanggal_lahir">
                    </div>

                    <div class="col-md-6">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control" id="edit-no_hp">
                    </div>

                    <div class="col-md-6">
                        <label>Kartu NU</label>
                        <input type="text" name="kartanu" class="form-control" id="edit-kartanu">
                    </div>

                    <div class="col-md-6">
                        <label>NIP Ma'arif</label>
                        <input type="text" name="nip" class="form-control" id="edit-nip">
                    </div>

                    <div class="col-md-6">
                        <label>NUPTK</label>
                        <input type="text" name="nuptk" class="form-control" id="edit-nuptk">
                    </div>

                    <div class="col-md-6">
                        <label>NPK</label>
                        <input type="text" name="npk" class="form-control" id="edit-npk">
                    </div>

                    <div class="col-md-6">
                        <label>Madrasah</label>
                        <select name="madrasah_id" class="form-control" id="edit-madrasah_id">
                            <option value="">-- Pilih Madrasah --</option>
                            <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($madrasah->id); ?>"><?php echo e($madrasah->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Status Kepegawaian</label>
                        <select name="status_kepegawaian_id" class="form-control" id="edit-status_kepegawaian_id">
                            <option value="">-- Pilih Status Kepegawaian --</option>
                            <?php $__currentLoopData = $statusKepegawaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status->id); ?>"><?php echo e($status->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>TMT</label>
                        <input type="date" name="tmt" class="form-control" id="edit-tmt">
                    </div>

                    <div class="col-md-6">
                        <label>Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" class="form-control" id="edit-pendidikan_terakhir">
                    </div>

                    <div class="col-md-6">
                        <label>Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="form-control" id="edit-tahun_lulus">
                    </div>

                    <div class="col-md-6">
                        <label>Program Studi</label>
                        <input type="text" name="program_studi" class="form-control" id="edit-program_studi">
                    </div>

                    <div class="col-md-6">
                        <label>Foto Profile</label>
                        <input type="file" name="avatar" class="form-control" id="edit-avatar">
                        <small class="text-muted">Opsional, boleh dikosongkan</small>
                    </div>

                    <div class="col-md-6">
                        <label>Ketugasan</label>
                        <select name="ketugasan" class="form-control" id="edit-ketugasan">
                            <option value="">-- Pilih Ketugasan --</option>
                            <option value="tenaga pendidik">Tenaga Pendidik</option>
                            <option value="penjaga sekolah">Penjaga Sekolah</option>
                            <option value="kepala madrasah/sekolah">Kepala Madrasah/Sekolah</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Mengajar</label>
                        <input type="text" name="mengajar" class="form-control" id="edit-mengajar">
                    </div>

                    <div class="col-md-6">
                        <label>Pemenuhan Beban Kerja di Sekolah/Madrasah Lain</label>
                        <select name="pemenuhan_beban_kerja_lain" id="edit-pemenuhan_beban_kerja_lain" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1">Iya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>

                    <div class="col-md-6" id="edit-madrasah_tambahan_container" style="display: none;">
                        <label>Madrasah Tambahan</label>
                        <select name="madrasah_id_tambahan" class="form-control" id="edit-madrasah_id_tambahan">
                            <option value="">-- Pilih Madrasah --</option>
                            <?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($madrasah->id); ?>"><?php echo e($madrasah->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" id="edit-alamat"></textarea>
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

<!-- Modal for Viewing Tenaga Pendidik Details -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewModalLabel">
                    <i class="bx bx-user-circle me-2"></i>Detail Tenaga Pendidik
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="modal-avatar" src="" alt="Avatar" class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5 id="modal-name" class="fw-bold text-primary"></h5>
                        <p class="text-muted"><i class="bx bx-envelope me-1"></i><span id="modal-email"></span></p>
                        <span class="badge bg-success" id="modal-status"></span>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-phone text-primary me-1"></i>No HP:</strong><br>
                                <span id="modal-no_hp">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NIP:</strong><br>
                                <span id="modal-nip">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NUPTK:</strong><br>
                                <span id="modal-nuptk">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NPK:</strong><br>
                                <span id="modal-npk">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-map-pin text-primary me-1"></i>Tempat Lahir:</strong><br>
                                <span id="modal-tempat_lahir">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar text-primary me-1"></i>Tanggal Lahir:</strong><br>
                                <span id="modal-tanggal_lahir">-</span>
                            </div>
                            <div class="col-12 mb-2">
                                <strong><i class="bx bx-home text-primary me-1"></i>Alamat:</strong><br>
                                <span id="modal-alamat">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-card text-primary me-1"></i>Kartanu:</strong><br>
                                <span id="modal-kartanu">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-graduation text-primary me-1"></i>Pendidikan:</strong><br>
                                <span id="modal-pendidikan_terakhir">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar-check text-primary me-1"></i>Tahun Lulus:</strong><br>
                                <span id="modal-tahun_lulus">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-book text-primary me-1"></i>Program Studi:</strong><br>
                                <span id="modal-program_studi">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar-event text-primary me-1"></i>TMT:</strong><br>
                                <span id="modal-tmt">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-briefcase text-primary me-1"></i>Ketugasan:</strong><br>
                                <span id="modal-ketugasan">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-chalkboard text-primary me-1"></i>Mengajar:</strong><br>
                                <span id="modal-mengajar">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
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
    let table = $("#tenaga-pendidik-table").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#tenaga-pendidik-table_wrapper .col-md-6:eq(0)');

    // Handle View Button Click
    $('#tenaga-pendidik-table').on('click', '.view-btn', function() {
        const data = $(this).data();
        $('#modal-name').text(data.name);
        $('#modal-email').text(data.email);
        $('#modal-status').text(data.status);
        $('#modal-no_hp').text(data.no_hp);
        $('#modal-nip').text(data.nip);
        $('#modal-nuptk').text(data.nuptk);
        $('#modal-npk').text(data.npk);
        $('#modal-tempat_lahir').text(data.tempat_lahir);
        $('#modal-tanggal_lahir').text(data.tanggal_lahir);
        $('#modal-alamat').text(data.alamat);
        $('#modal-kartanu').text(data.kartanu);
        $('#modal-pendidikan_terakhir').text(data.pendidikan_terakhir);
        $('#modal-tahun_lulus').text(data.tahun_lulus);
        $('#modal-program_studi').text(data.program_studi);
        $('#modal-tmt').text(data.tmt);
        $('#modal-ketugasan').text(data.ketugasan);
        $('#modal-mengajar').text(data.mengajar);
        $('#modal-avatar').attr('src', data.avatar);
        $('#viewModal').modal('show');
    });

    // Handle Edit Button Click
    $('#tenaga-pendidik-table').on('click', '.edit-btn', function() {
        const data = $(this).data();
        $('#edit-nama').val(data.name);
        $('#edit-email').val(data.email);
        $('#edit-password').val(data.password);
        $('#edit-tempat_lahir').val(data.tempat_lahir);
        $('#edit-tanggal_lahir').val(data.tanggal_lahir);
        $('#edit-no_hp').val(data.no_hp);
        $('#edit-kartanu').val(data.kartanu);
        $('#edit-nip').val(data.nip);
        $('#edit-nuptk').val(data.nuptk);
        $('#edit-npk').val(data.npk);
        $('#edit-madrasah_id').val(data.madrasah_id);
        $('#edit-status_kepegawaian_id').val(data.status_kepegawaian_id);
        $('#edit-tmt').val(data.tmt);
        $('#edit-pendidikan_terakhir').val(data.pendidikan_terakhir);
        $('#edit-tahun_lulus').val(data.tahun_lulus);
        $('#edit-program_studi').val(data.program_studi);
        $('#edit-ketugasan').val(data.ketugasan);
        $('#edit-mengajar').val(data.mengajar);
        $('#edit-alamat').val(data.alamat);
        $('#edit-pemenuhan_beban_kerja_lain').val(data.pemenuhan_beban_kerja_lain);
        $('#edit-madrasah_id_tambahan').val(data.madrasah_id_tambahan);

        // Update form action URL
        const editUrl = '<?php echo e(route("tenaga-pendidik.update", ":id")); ?>'.replace(':id', data.id);
        $('#editForm').attr('action', editUrl);

        // Handle madrasah tambahan visibility
        if (data.pemenuhan_beban_kerja_lain === '1') {
            $('#edit-madrasah_tambahan_container').show();
        } else {
            $('#edit-madrasah_tambahan_container').hide();
        }

        $('#editModal').modal('show');
    });

    // Handle pemenuhan beban kerja lain change in edit modal
    $('#edit-pemenuhan_beban_kerja_lain').change(function() {
        if ($(this).val() == '1') {
            $('#edit-madrasah_tambahan_container').show();
        } else {
            $('#edit-madrasah_tambahan_container').hide();
        }
    });

    // Handle toggle password visibility
    $('#toggle-password').click(function() {
        const passwordInput = $('#edit-password');
        const icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('bx-show').addClass('bx-hide');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('bx-hide').addClass('bx-show');
        }
    });

    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '<?php echo e(session('success')); ?>',
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo e(session('error')); ?>',
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/masterdata/madrasah/detail.blade.php ENDPATH**/ ?>