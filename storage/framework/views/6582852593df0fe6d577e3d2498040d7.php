<?php $__env->startSection('title'); ?>Data Siswa <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.hero-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: #fff;
    border: none;
    border-radius: 18px;
    box-shadow: 0 12px 32px rgba(0, 75, 76, 0.18);
}

.stats-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
}

.table thead th {
    white-space: nowrap;
    vertical-align: middle;
}

.badge-soft-success {
    background: rgba(14, 133, 73, 0.12);
    color: #0e8549;
}

.badge-soft-secondary {
    background: rgba(100, 116, 139, 0.14);
    color: #475569;
}

.modal .form-label {
    font-weight: 600;
}

.student-modal .modal-dialog {
    margin: 0.75rem auto;
    height: calc(100vh - 1.5rem);
}

.student-modal .modal-content {
    height: 100%;
    border: 0;
    border-radius: 1rem;
    overflow: hidden;
}

.student-modal form {
    display: flex;
    flex-direction: column;
    min-height: 0;
    height: 100%;
}

.student-modal .modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

.student-modal .modal-header,
.student-modal .modal-footer {
    flex: 0 0 auto;
    background: #fff;
}

@media (max-width: 991.98px) {
    .student-modal .modal-dialog {
        margin: 0;
        height: 100vh;
        max-width: 100%;
    }

    .student-modal .modal-content {
        border-radius: 0;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Data Sekolah <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Data Siswa <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan.</strong>
                <ul class="mb-0 mt-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="card hero-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h4 class="text-white mb-2"><i class="bx bx-id-card me-2"></i>Data Siswa</h4>
                        <p class="mb-0 text-white-50">
                            <?php echo e($userRole === 'admin_spp' ? 'Lihat data siswa sesuai madrasah yang terhubung dengan akun Anda.' : 'Kelola dan import data siswa per madrasah dengan format data sesuai template.'); ?>

                        </p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin_spp'): ?>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?php echo e(route('data-sekolah.data-siswa.template')); ?>" class="btn btn-light">
                                <i class="bx bx-download me-1"></i>Template
                            </a>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bx bx-upload me-1"></i>Import
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="bx bx-plus me-1"></i>Tambah Siswa
                            </button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary"><i class="bx bx-user"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Total Siswa</p>
                        <h4 class="mb-0"><?php echo e(number_format($stats['total'])); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success"><i class="bx bx-check-circle"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Akun Aktif</p>
                        <h4 class="mb-0"><?php echo e(number_format($stats['aktif'])); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info"><i class="bx bx-buildings"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Madrasah</p>
                        <h4 class="mb-0"><?php echo e(number_format($stats['madrasah'])); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-dark"><i class="bx bx-grid-alt"></i></div>
                    <div class="ms-3">
                        <p class="text-muted mb-1">Jumlah Kelas</p>
                        <h4 class="mb-0"><?php echo e(number_format($stats['kelas'])); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('data-sekolah.data-siswa.index')); ?>">
            <div class="row g-3 align-items-end">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($userRole, ['admin', 'admin_spp'])): ?>
                <div class="col-md-3">
                    <label for="madrasah_id" class="form-label">Madrasah</label>
                    <select name="madrasah_id" id="madrasah_id" class="form-select">
                        <option value="">Semua Madrasah</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) request('madrasah_id') === (string) $madrasah->id ? 'selected' : ''); ?>>
                                <?php echo e($madrasah->name); ?>

                            </option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="col-md-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" name="kelas" id="kelas" class="form-control" value="<?php echo e(request('kelas')); ?>" placeholder="Contoh: X IPA 1">
                </div>
                <div class="col-md-3">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" name="jurusan" id="jurusan" class="form-control" value="<?php echo e(request('jurusan')); ?>" placeholder="Contoh: IPA">
                </div>
                <div class="col-md-2">
                    <label for="q" class="form-label">Pencarian</label>
                    <input type="text" name="q" id="q" class="form-control" value="<?php echo e(request('q')); ?>" placeholder="Cari NIS, NISN, NIK, nama, email, wali">
                </div>
                <div class="col-md-2">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success"><i class="bx bx-search me-1"></i>Filter</button>
                        <a href="<?php echo e(route('data-sekolah.data-siswa.index')); ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin_spp'): ?>
            <div class="alert alert-info">
                Password default akun siswa saat tambah/import adalah <strong>NIS</strong>. Jika email siswa kosong pada file template, sistem akan membuat email internal otomatis.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIS / NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Madrasah</th>
                        <th>Email Siswa</th>
                        <th>Orang Tua/Wali</th>
                        <th>Status</th>
                        <th style="min-width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($siswas->firstItem() + $index); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo e($siswa->nis); ?></div>
                                <small class="text-muted"><?php echo e($siswa->nisn ?: '-'); ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo e($siswa->nama_lengkap); ?></div>
                                <small class="text-muted">
                                    <?php echo e($siswa->jenis_kelamin ?: '-'); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($siswa->tempat_lahir || $siswa->tanggal_lahir): ?>
                                        • <?php echo e($siswa->tempat_lahir ?: '-'); ?><?php echo e($siswa->tanggal_lahir ? ', ' . $siswa->tanggal_lahir->format('d-m-Y') : ''); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </small>
                            </td>
                            <td><?php echo e($siswa->kelas); ?></td>
                            <td><?php echo e($siswa->jurusan); ?></td>
                            <td><?php echo e($siswa->nama_madrasah); ?></td>
                            <td>
                                <div><?php echo e($siswa->email); ?></div>
                                <small class="text-muted"><?php echo e($siswa->email_orang_tua_wali); ?></small>
                            </td>
                            <td>
                                <div><?php echo e($siswa->nama_orang_tua_wali); ?></div>
                                <small class="text-muted"><?php echo e($siswa->no_hp_orang_tua_wali); ?></small>
                            </td>
                            <td>
                                <span class="badge <?php echo e($siswa->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>">
                                    <?php echo e($siswa->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="<?php echo e($siswa->id); ?>"
                                        data-madrasah_id="<?php echo e($siswa->madrasah_id); ?>"
                                        data-nis="<?php echo e($siswa->nis); ?>"
                                        data-nisn="<?php echo e($siswa->nisn); ?>"
                                        data-nik="<?php echo e($siswa->nik); ?>"
                                        data-no_kk="<?php echo e($siswa->no_kk); ?>"
                                        data-nama_lengkap="<?php echo e($siswa->nama_lengkap); ?>"
                                        data-jenis_kelamin="<?php echo e($siswa->jenis_kelamin); ?>"
                                        data-tempat_lahir="<?php echo e($siswa->tempat_lahir); ?>"
                                        data-tanggal_lahir="<?php echo e(optional($siswa->tanggal_lahir)->format('Y-m-d')); ?>"
                                        data-agama="<?php echo e($siswa->agama); ?>"
                                        data-nama_orang_tua_wali="<?php echo e($siswa->nama_orang_tua_wali); ?>"
                                        data-email="<?php echo e($siswa->email); ?>"
                                        data-email_orang_tua_wali="<?php echo e($siswa->email_orang_tua_wali); ?>"
                                        data-no_hp="<?php echo e($siswa->no_hp); ?>"
                                        data-no_hp_orang_tua_wali="<?php echo e($siswa->no_hp_orang_tua_wali); ?>"
                                        data-kelas="<?php echo e($siswa->kelas); ?>"
                                        data-jurusan="<?php echo e($siswa->jurusan); ?>"
                                        data-tahun_masuk="<?php echo e($siswa->tahun_masuk); ?>"
                                        data-jenis_tinggal="<?php echo e($siswa->jenis_tinggal); ?>"
                                        data-alat_transportasi="<?php echo e($siswa->alat_transportasi); ?>"
                                        data-alamat="<?php echo e($siswa->alamat); ?>"
                                        data-dusun="<?php echo e($siswa->dusun); ?>"
                                        data-kelurahan="<?php echo e($siswa->kelurahan); ?>"
                                        data-kecamatan="<?php echo e($siswa->kecamatan); ?>"
                                        data-kode_pos="<?php echo e($siswa->kode_pos); ?>"
                                        data-nama_ayah="<?php echo e($siswa->nama_ayah); ?>"
                                        data-pendidikan_ayah="<?php echo e($siswa->pendidikan_ayah); ?>"
                                        data-pekerjaan_ayah="<?php echo e($siswa->pekerjaan_ayah); ?>"
                                        data-penghasilan_ayah="<?php echo e($siswa->penghasilan_ayah); ?>"
                                        data-nama_ibu="<?php echo e($siswa->nama_ibu); ?>"
                                        data-pendidikan_ibu="<?php echo e($siswa->pendidikan_ibu); ?>"
                                        data-pekerjaan_ibu="<?php echo e($siswa->pekerjaan_ibu); ?>"
                                        data-penghasilan_ibu="<?php echo e($siswa->penghasilan_ibu); ?>"
                                        data-nama_wali="<?php echo e($siswa->nama_wali); ?>"
                                        data-pendidikan_wali="<?php echo e($siswa->pendidikan_wali); ?>"
                                        data-pekerjaan_wali="<?php echo e($siswa->pekerjaan_wali); ?>"
                                        data-penghasilan_wali="<?php echo e($siswa->penghasilan_wali); ?>"
                                        data-is_active="<?php echo e($siswa->is_active ? 1 : 0); ?>"
                                    >
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <form method="POST" action="<?php echo e(route('data-sekolah.data-siswa.destroy', $siswa)); ?>" onsubmit="return confirm('Hapus data siswa ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Belum ada data siswa.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($siswas->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin_spp'): ?>
    <div class="modal fade student-modal" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="<?php echo e(route('data-sekolah.data-siswa.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $__env->make('data-sekolah.partials.siswa-form', ['formId' => 'create', 'siswa' => null, 'madrasahOptions' => $madrasahOptions, 'selectedMadrasahId' => $selectedMadrasahId, 'userRole' => $userRole], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="modal fade student-modal" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" id="editForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php echo $__env->make('data-sekolah.partials.siswa-form', ['formId' => 'edit', 'siswa' => null, 'madrasahOptions' => $madrasahOptions, 'selectedMadrasahId' => $selectedMadrasahId, 'userRole' => $userRole], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin_spp'): ?>
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?php echo e(route('data-sekolah.data-siswa.import')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Import Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning mb-3">
                            Gunakan template agar nama kolom sesuai. Kolom nama madrasah tidak perlu ada di file import. Jika NIS, NISN, atau email siswa sudah ada, data akan diperbarui.
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($userRole, ['admin', 'admin_spp'])): ?>
                            <div class="mb-3">
                                <label for="import_madrasah_id" class="form-label">Madrasah/Sekolah</label>
                                <select class="form-select" id="import_madrasah_id" name="madrasah_id" required>
                                    <option value="">Pilih Madrasah</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) old('madrasah_id', $selectedMadrasahId) === (string) $madrasah->id ? 'selected' : ''); ?>>
                                            <?php echo e($madrasah->name); ?>

                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="madrasah_id" value="<?php echo e($selectedMadrasahId); ?>">
                            <div class="mb-3">
                                <label class="form-label">Madrasah/Sekolah</label>
                                <input type="text" class="form-control" value="<?php echo e(optional($madrasahOptions->first())->name); ?>" readonly>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <label for="file" class="form-label">File Excel/CSV</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Import Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editModal');
    if (!editModal) {
        return;
    }

    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const form = document.getElementById('editForm');
        const siswaId = button.getAttribute('data-id');
        const setValue = (name, attribute) => {
            const field = form.querySelector(`[name="${name}"]`);
            if (!field) {
                return;
            }

            field.value = button.getAttribute(attribute) ?? '';
        };

        form.action = `<?php echo e(route('data-sekolah.data-siswa.index')); ?>/${siswaId}`;
        setValue('madrasah_id', 'data-madrasah_id');
        setValue('nis', 'data-nis');
        setValue('nisn', 'data-nisn');
        setValue('nik', 'data-nik');
        setValue('no_kk', 'data-no_kk');
        setValue('nama_lengkap', 'data-nama_lengkap');
        setValue('jenis_kelamin', 'data-jenis_kelamin');
        setValue('tempat_lahir', 'data-tempat_lahir');
        setValue('tanggal_lahir', 'data-tanggal_lahir');
        setValue('agama', 'data-agama');
        setValue('nama_orang_tua_wali', 'data-nama_orang_tua_wali');
        setValue('email', 'data-email');
        setValue('email_orang_tua_wali', 'data-email_orang_tua_wali');
        setValue('no_hp', 'data-no_hp');
        setValue('no_hp_orang_tua_wali', 'data-no_hp_orang_tua_wali');
        setValue('kelas', 'data-kelas');
        setValue('jurusan', 'data-jurusan');
        setValue('tahun_masuk', 'data-tahun_masuk');
        setValue('jenis_tinggal', 'data-jenis_tinggal');
        setValue('alat_transportasi', 'data-alat_transportasi');
        setValue('alamat', 'data-alamat');
        setValue('dusun', 'data-dusun');
        setValue('kelurahan', 'data-kelurahan');
        setValue('kecamatan', 'data-kecamatan');
        setValue('kode_pos', 'data-kode_pos');
        setValue('nama_ayah', 'data-nama_ayah');
        setValue('pendidikan_ayah', 'data-pendidikan_ayah');
        setValue('pekerjaan_ayah', 'data-pekerjaan_ayah');
        setValue('penghasilan_ayah', 'data-penghasilan_ayah');
        setValue('nama_ibu', 'data-nama_ibu');
        setValue('pendidikan_ibu', 'data-pendidikan_ibu');
        setValue('pekerjaan_ibu', 'data-pekerjaan_ibu');
        setValue('penghasilan_ibu', 'data-penghasilan_ibu');
        setValue('nama_wali', 'data-nama_wali');
        setValue('pendidikan_wali', 'data-pendidikan_wali');
        setValue('pekerjaan_wali', 'data-pekerjaan_wali');
        setValue('penghasilan_wali', 'data-penghasilan_wali');
        form.querySelector('[name="is_active"]').checked = button.getAttribute('data-is_active') === '1';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/data-sekolah/data-siswa.blade.php ENDPATH**/ ?>