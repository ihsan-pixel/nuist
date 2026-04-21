<?php $__env->startSection('title'); ?>Dashboard SPP Siswa <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.spp-hero {
    background: linear-gradient(135deg, #0f3d3e 0%, #14866d 100%);
    color: #fff;
    border: none;
    border-radius: 20px;
}
.spp-stat {
    border: none;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}
.spp-chip {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.25rem;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard SPP Siswa <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card spp-hero mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <h4 class="text-white mb-2"><i class="bx bx-wallet me-2"></i>Dashboard SPP Siswa</h4>
                <p class="mb-0 text-white-50">Modul baru SPP siswa yang terhubung langsung ke data siswa, terpisah dari tagihan lama, dan sudah disiapkan untuk alur pembayaran BNI Virtual Account.</p>
            </div>
            <form method="GET" class="row g-2 align-items-end" style="min-width: 280px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin'): ?>
                    <div class="col-12">
                        <label class="form-label text-white">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : ''); ?>><?php echo e($madrasah->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-light w-100"><i class="bx bx-filter-alt me-1"></i>Terapkan</button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-primary"><i class="bx bx-user"></i></div><div><div class="text-muted">Total Siswa</div><h4 class="mb-0"><?php echo e(number_format($stats['total_siswa'])); ?></h4></div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-success"><i class="bx bx-check-circle"></i></div><div><div class="text-muted">Tagihan Lunas</div><h4 class="mb-0"><?php echo e(number_format($stats['tagihan_lunas'])); ?></h4></div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-warning"><i class="bx bx-time-five"></i></div><div><div class="text-muted">Belum Lunas</div><h4 class="mb-0"><?php echo e(number_format($stats['tagihan_belum_lunas'])); ?></h4></div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-info"><i class="bx bx-cog"></i></div><div><div class="text-muted">Pengaturan Aktif</div><h4 class="mb-0"><?php echo e(number_format($stats['pengaturan_aktif'])); ?></h4></div></div></div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card spp-stat h-100"><div class="card-body"><div class="text-muted mb-2">Total Nominal Tagihan</div><h4>Rp <?php echo e(number_format($stats['nominal_tagihan'], 0, ',', '.')); ?></h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card spp-stat h-100"><div class="card-body"><div class="text-muted mb-2">Total Terbayar</div><h4>Rp <?php echo e(number_format($stats['nominal_terbayar'], 0, ',', '.')); ?></h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card spp-stat h-100"><div class="card-body"><div class="text-muted mb-2">Total Transaksi</div><h4><?php echo e(number_format($stats['total_transaksi'])); ?></h4></div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Tagihan Terbaru</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>No Tagihan</th><th>Siswa</th><th>Status</th><th>Total</th></tr></thead>
                        <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentBills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td><?php echo e($bill->nomor_tagihan); ?></td>
                                <td><?php echo e($bill->siswa->nama_lengkap ?? '-'); ?></td>
                                <td><span class="badge bg-<?php echo e($bill->status === 'lunas' ? 'success' : ($bill->status === 'sebagian' ? 'warning' : 'danger')); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $bill->status))); ?></span></td>
                                <td>Rp <?php echo e(number_format($bill->total_tagihan, 0, ',', '.')); ?></td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada tagihan SPP siswa.</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Transaksi Terbaru</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>No Transaksi</th><th>Siswa</th><th>Verifikasi</th><th>Nominal</th></tr></thead>
                        <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td><?php echo e($transaction->nomor_transaksi); ?></td>
                                <td><?php echo e($transaction->siswa->nama_lengkap ?? '-'); ?></td>
                                <td><span class="badge bg-<?php echo e($transaction->status_verifikasi === 'diverifikasi' ? 'success' : ($transaction->status_verifikasi === 'ditolak' ? 'danger' : 'warning')); ?>"><?php echo e(ucfirst($transaction->status_verifikasi)); ?></span></td>
                                <td>Rp <?php echo e(number_format($transaction->nominal_bayar, 0, ',', '.')); ?></td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada transaksi SPP siswa.</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/spp-siswa/dashboard.blade.php ENDPATH**/ ?>