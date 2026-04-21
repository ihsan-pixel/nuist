<?php $__env->startSection('title'); ?>Transaksi SPP Siswa <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SPP Siswa <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Transaksi <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Terjadi kesalahan.</strong>
        <ul class="mb-0 mt-2"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?><li><?php echo e($error); ?></li><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="card mb-4">
    <div class="card-body d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
        <div>
            <h4 class="mb-1">Transaksi SPP Siswa</h4>
            <p class="text-muted mb-0">Pencatatan pembayaran SPP siswa baru yang terhubung ke tagihan SPP siswa.</p>
        </div>
        
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-3 align-items-end">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin'): ?>
                    <div class="col-md-3">
                        <label class="form-label">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : ''); ?>><?php echo e($madrasah->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="col-md-3">
                    <label class="form-label">Verifikasi</label>
                    <select name="status_verifikasi" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu" <?php if(request('status_verifikasi') === 'menunggu'): echo 'selected'; endif; ?>>Menunggu</option>
                        <option value="diverifikasi" <?php if(request('status_verifikasi') === 'diverifikasi'): echo 'selected'; endif; ?>>Diverifikasi</option>
                        <option value="ditolak" <?php if(request('status_verifikasi') === 'ditolak'): echo 'selected'; endif; ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Pencarian</label><input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="No transaksi, nama, NIS"></div>
                <div class="col-md-2 d-grid"><button class="btn btn-primary">Filter</button></div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>Tagihan</th>
                        <th>Siswa</th>
                        <th>Tanggal Bayar</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>VA</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($transactions->firstItem() + $index); ?></td>
                        <td><?php echo e($transaction->nomor_transaksi); ?></td>
                        <td><?php echo e($transaction->bill->nomor_tagihan ?? '-'); ?></td>
                        <td>
                            <div class="fw-semibold"><?php echo e($transaction->siswa->nama_lengkap ?? '-'); ?></div>
                            <small class="text-muted"><?php echo e($transaction->siswa->nis ?? '-'); ?></small>
                        </td>
                        <td><?php echo e(optional($transaction->tanggal_bayar)->format('d M Y')); ?></td>
                        <td>Rp <?php echo e(number_format($transaction->nominal_bayar, 0, ',', '.')); ?></td>
                        <td><?php echo e($transaction->metode_pembayaran); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transaction->payment_channel === 'bni_va'): ?>
                                <div class="fw-semibold"><?php echo e($transaction->va_number ?? '-'); ?></div>
                                <small class="text-muted"><?php echo e(optional($transaction->va_expired_at)->format('d M Y H:i') ?? 'Tanpa expiry'); ?></small>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td><span class="badge bg-<?php echo e($transaction->status_verifikasi === 'diverifikasi' ? 'success' : ($transaction->status_verifikasi === 'ditolak' ? 'danger' : 'warning')); ?>"><?php echo e(ucfirst($transaction->status_verifikasi)); ?></span></td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="9" class="text-center text-muted">Belum ada transaksi SPP siswa.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($transactions->links()); ?>

    </div>
</div>

<div class="modal fade" id="createTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('spp-siswa.transaksi.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi SPP Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tagihan</label>
                            <select name="bill_id" class="form-select" required>
                                <option value="">Pilih Tagihan</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($bill->id); ?>"><?php echo e($bill->nomor_tagihan); ?> - <?php echo e($bill->siswa->nama_lengkap ?? '-'); ?> - Rp <?php echo e(number_format($bill->total_tagihan, 0, ',', '.')); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3"><label class="form-label">Tanggal Bayar</label><input type="date" name="tanggal_bayar" class="form-control" required></div>
                        <div class="col-md-3"><label class="form-label">Nominal</label><input type="number" min="0" name="nominal_bayar" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Metode Pembayaran</label><input type="text" name="metode_pembayaran" class="form-control" placeholder="Transfer, Cash, QRIS, Virtual Account" required></div>
                        <div class="col-md-4">
                            <label class="form-label">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select" required>
                                <option value="menunggu">Menunggu</option>
                                <option value="diverifikasi">Diverifikasi</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">Keterangan</label><input type="text" name="keterangan" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/spp-siswa/transaksi.blade.php ENDPATH**/ ?>