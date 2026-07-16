<?php $__env->startSection('title', 'Riwayat Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Riwayat Pembayaran', 'subtitle' => 'Filter transaksi siswa'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-history"></i>Riwayat transaksi</span>
        <h4><?php echo e($filteredPayments->count()); ?> hasil ditemukan</h4>
        <p class="mb-0">Gunakan filter untuk menelusuri transaksi berdasarkan status verifikasi dan bulan pembayaran.</p>
    </section>

    <section class="section-card">
        <div class="section-title">
            <div>
                <h5>Filter riwayat pembayaran</h5>
                <p class="section-subtitle">Persempit daftar transaksi sesuai kebutuhan</p>
            </div>
        </div>
        <form method="GET" class="filter-form">
            <select name="status" class="form-control">
                <option value="">Semua status</option>
                <option value="diverifikasi" <?php echo e(request('status') === 'diverifikasi' ? 'selected' : ''); ?>>Diverifikasi</option>
                <option value="menunggu" <?php echo e(request('status') === 'menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                <option value="ditolak" <?php echo e(request('status') === 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
            </select>
            <select name="bulan" class="form-control">
                <option value="">Semua bulan</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($bulan = 1; $bulan <= 12; $bulan++): ?>
                    <option value="<?php echo e($bulan); ?>" <?php echo e((int) request('bulan') === $bulan ? 'selected' : ''); ?>>
                        <?php echo e(\Carbon\Carbon::create()->month($bulan)->translatedFormat('F')); ?>

                    </option>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <button class="cta-btn" type="submit" style="width:auto; padding:0 14px;">
                <i class="bx bx-search"></i>
            </button>
        </form>
    </section>

    <section class="list-card">
        <div class="section-title">
            <div>
                <h5>Daftar transaksi</h5>
                <p class="section-subtitle">Histori pembayaran siswa yang sudah tercatat</p>
            </div>
            <span class="pill pill-info"><?php echo e($filteredPayments->count()); ?> hasil</span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $filteredPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="list-item">
                <div class="list-kicker"><i class="bx bx-transfer-alt"></i><?php echo e(strtoupper($payment->payment_channel ?? 'manual')); ?></div>
                <h6><?php echo e($payment->nomor_transaksi); ?></h6>
                <p><?php echo e($payment->bill?->nomor_tagihan ?? 'Tagihan tidak ditemukan'); ?></p>
                <div class="meta-row">
                    <span><?php echo e(optional($payment->tanggal_bayar)->translatedFormat('d M Y') ?? 'Belum dibayar'); ?></span>
                    <strong>Rp <?php echo e(number_format($payment->nominal_bayar, 0, ',', '.')); ?></strong>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment->payment_channel === 'bni_va' && $payment->va_number): ?>
                    <div class="meta-row">
                        <span>VA BNI <?php echo e($payment->va_number); ?></span>
                        <span><?php echo e(optional($payment->va_expired_at)->translatedFormat('d M Y H:i') ?? '-'); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="meta-row">
                    <span class="pill <?php echo e($payment->status_verifikasi === 'diverifikasi' ? 'pill-success' : ($payment->status_verifikasi === 'menunggu' ? 'pill-warning' : 'pill-danger')); ?>"><?php echo e(ucfirst($payment->status_verifikasi)); ?></span>
                    <a href="<?php echo e(route('mobile.siswa.bukti', $payment->id)); ?>" class="ghost-btn" style="width:auto; padding:8px 12px;">Bukti</a>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="empty-state">
                <i class="bx bx-folder"></i>
                <h6>Belum ada riwayat pembayaran</h6>
                <p>Transaksi yang sudah diproses akan muncul di sini dan bisa difilter per status atau bulan.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/riwayat.blade.php ENDPATH**/ ?>