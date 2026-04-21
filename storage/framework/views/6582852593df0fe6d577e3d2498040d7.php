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
                            Kelola dan import data siswa per madrasah.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo e(route('data-sekolah.data-siswa.template')); ?>" class="btn btn-light">
                            <i class="bx bx-download me-1"></i>Template Import
                        </a>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bx bx-upload me-1"></i>Import
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="bx bx-plus me-1"></i>Tambah Siswa
                        </button>
                    </div>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin'): ?>
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
                    <input type="text" name="q" id="q" class="form-control" value="<?php echo e(request('q')); ?>" placeholder="Cari NIS, nama, email, wali">
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
        <div class="alert alert-info">
            Password default akun siswa saat tambah/import adalah <strong>NIS</strong>.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
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
                            <td><?php echo e($siswa->nis); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo e($siswa->nama_lengkap); ?></div>
                                <small class="text-muted"><?php echo e($siswa->no_hp); ?></small>
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
                                        data-nama_lengkap="<?php echo e($siswa->nama_lengkap); ?>"
                                        data-nama_orang_tua_wali="<?php echo e($siswa->nama_orang_tua_wali); ?>"
                                        data-email="<?php echo e($siswa->email); ?>"
                                        data-email_orang_tua_wali="<?php echo e($siswa->email_orang_tua_wali); ?>"
                                        data-no_hp="<?php echo e($siswa->no_hp); ?>"
                                        data-no_hp_orang_tua_wali="<?php echo e($siswa->no_hp_orang_tua_wali); ?>"
                                        data-kelas="<?php echo e($siswa->kelas); ?>"
                                        data-jurusan="<?php echo e($siswa->jurusan); ?>"
                                        data-alamat="<?php echo e($siswa->alamat); ?>"
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

<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
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

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                        Gunakan template resmi agar nama kolom sesuai. Kolom nama madrasah tidak perlu ada di file import. Jika NIS atau email sudah ada, data akan diperbarui.
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin'): ?>
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

        form.action = `<?php echo e(route('data-sekolah.data-siswa.index')); ?>/${siswaId}`;
        form.querySelector('[name="madrasah_id"]').value = button.getAttribute('data-madrasah_id');
        form.querySelector('[name="nis"]').value = button.getAttribute('data-nis');
        form.querySelector('[name="nama_lengkap"]').value = button.getAttribute('data-nama_lengkap');
        form.querySelector('[name="nama_orang_tua_wali"]').value = button.getAttribute('data-nama_orang_tua_wali');
        form.querySelector('[name="email"]').value = button.getAttribute('data-email');
        form.querySelector('[name="email_orang_tua_wali"]').value = button.getAttribute('data-email_orang_tua_wali');
        form.querySelector('[name="no_hp"]').value = button.getAttribute('data-no_hp');
        form.querySelector('[name="no_hp_orang_tua_wali"]').value = button.getAttribute('data-no_hp_orang_tua_wali');
        form.querySelector('[name="kelas"]').value = button.getAttribute('data-kelas');
        form.querySelector('[name="jurusan"]').value = button.getAttribute('data-jurusan');
        form.querySelector('[name="alamat"]').value = button.getAttribute('data-alamat');
        form.querySelector('[name="is_active"]').checked = button.getAttribute('data-is_active') === '1';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/data-sekolah/data-siswa.blade.php ENDPATH**/ ?>