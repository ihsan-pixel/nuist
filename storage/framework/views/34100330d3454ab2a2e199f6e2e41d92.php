<?php $__env->startSection('title'); ?>Pratinjau Jenjang Siswa <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Data Sekolah <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Data Siswa <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Inisialisasi Jenjang <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?><li><?php echo e($error); ?></li><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="alert alert-info">
    <strong>Proses aman:</strong> halaman ini hanya membaca data siswa. Saat dikonfirmasi, sistem membuat snapshot
    riwayat akademik baru dan tidak mengubah kelas, jurusan, status, maupun identitas pada tabel siswa.
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('data-sekolah.data-siswa.academic-history.preview')); ?>">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input name="tahun_ajaran" class="form-control" value="<?php echo e($tahunAjaran); ?>" pattern="\d{4}/\d{4}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="1" <?php echo e($semester === 1 ? 'selected' : ''); ?>>1 (Ganjil)</option>
                        <option value="2" <?php echo e($semester === 2 ? 'selected' : ''); ?>>2 (Genap)</option>
                    </select>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole === 'super_admin'): ?>
                    <div class="col-md-5">
                        <label class="form-label">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($madrasah->id); ?>" <?php echo e((int) $selectedMadrasahId === (int) $madrasah->id ? 'selected' : ''); ?>>
                                    <?php echo e($madrasah->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary"><i class="bx bx-search me-1"></i>Pratinjau</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Siap Dibuat</div><h3 class="mb-0"><?php echo e(number_format($rows->count())); ?></h3></div></div></div>
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Dikenali Otomatis</div><h3 class="mb-0 text-success"><?php echo e(number_format($recognizedCount)); ?></h3></div></div></div>
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Perlu Verifikasi</div><h3 class="mb-0 text-warning"><?php echo e(number_format($reviewCount)); ?></h3></div></div></div>
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Sudah Memiliki Riwayat</div><h3 class="mb-0"><?php echo e(number_format($alreadyRecorded)); ?></h3></div></div></div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="mb-1">Hasil Pembacaan Kelas</h5>
                <div class="text-muted"><?php echo e($tahunAjaran); ?> · Semester <?php echo e($semester); ?></div>
            </div>
            <a href="<?php echo e(route('data-sekolah.data-siswa.index')); ?>" class="btn btn-outline-secondary">Kembali ke Data Siswa</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light"><tr><th>No</th><th>Sekolah</th><th>Siswa</th><th>Kelas Asli</th><th>Jurusan</th><th>Jenjang</th><th>Tingkat</th><th>Hasil</th><th>Catatan</th></tr></thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($row['siswa']->madrasah->name ?? $row['siswa']->nama_madrasah ?? '-'); ?></td>
                            <td><strong><?php echo e($row['siswa']->nama_lengkap ?: '-'); ?></strong><br><small class="text-muted"><?php echo e($row['siswa']->nis ?: $row['siswa']->nisn); ?></small></td>
                            <td><?php echo e($row['siswa']->kelas ?: '-'); ?></td>
                            <td><?php echo e($row['siswa']->jurusan ?: '-'); ?></td>
                            <td><?php echo e(str_replace('_', '/', $row['jenjang_sekolah'] ?? '-')); ?></td>
                            <td><?php echo e($row['tingkat'] ?? '-'); ?></td>
                            <td>
                                <span class="badge <?php echo e($row['status'] === 'perlu_verifikasi' ? 'bg-warning text-dark' : 'bg-success'); ?>">
                                    <?php echo e(str_replace('_', ' ', ucfirst($row['status']))); ?>

                                </span>
                            </td>
                            <td><?php echo e($row['catatan'] ?: '-'); ?></td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada siswa baru untuk periode ini. Semua data pada cakupan terpilih sudah memiliki riwayat.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole === 'super_admin' && $rows->isNotEmpty()): ?>
            <form method="POST" action="<?php echo e(route('data-sekolah.data-siswa.academic-history.store')); ?>" class="mt-3" onsubmit="return confirm('Buat snapshot riwayat akademik sesuai pratinjau? Data siswa lama tidak akan diubah.');">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="tahun_ajaran" value="<?php echo e($tahunAjaran); ?>">
                <input type="hidden" name="semester" value="<?php echo e($semester); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedMadrasahId): ?><input type="hidden" name="madrasah_id" value="<?php echo e($selectedMadrasahId); ?>"><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="confirmation" id="confirmation" required>
                    <label class="form-check-label" for="confirmation">Saya sudah memeriksa pratinjau dan memahami bahwa data ambigu diberi status perlu verifikasi.</label>
                </div>
                <button class="btn btn-success"><i class="bx bx-check-shield me-1"></i>Buat <?php echo e(number_format($rows->count())); ?> Riwayat Akademik</button>
            </form>
        <?php elseif($userRole === 'admin'): ?>
            <div class="alert alert-secondary mb-0">Admin sekolah dapat memeriksa hasil. Pembuatan snapshot awal hanya dapat dikonfirmasi oleh super admin.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/data-sekolah/riwayat-akademik-preview.blade.php ENDPATH**/ ?>