<?php $__env->startSection('title', 'Detail Tagihan'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Detail Tagihan', 'subtitle' => $selectedTagihan->nomor_tagihan], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('bni_va')): ?>
        <section class="section-card"><div class="list-item"><h6>BNI VA</h6><p><?php echo e($errors->first('bni_va')); ?></p></div></section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section class="section-card">
        <div class="section-title">
            <h5>Detail invoice</h5>
            <span class="pill <?php echo e($selectedTagihan->status === 'lunas' ? 'pill-success' : ($selectedTagihan->status === 'sebagian' ? 'pill-warning' : 'pill-danger')); ?>">
                <?php echo e(ucfirst(str_replace('_', ' ', $selectedTagihan->status))); ?>

            </span>
        </div>

        <div class="detail-grid">
            <div class="detail-box">
                <small>Nomor invoice</small>
                <strong><?php echo e($selectedTagihan->nomor_tagihan); ?></strong>
            </div>
            <div class="detail-box">
                <small>Jenis tagihan</small>
                <strong><?php echo e($selectedTagihan->jenis_tagihan ?? 'SPP'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Periode</small>
                <strong><?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $selectedTagihan->periode)->translatedFormat('F Y')); ?></strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp <?php echo e(number_format($selectedTagihan->nominal, 0, ',', '.')); ?></strong>
            </div>
            <div class="detail-box">
                <small>Total tagihan</small>
                <strong>Rp <?php echo e(number_format($selectedTagihan->total_tagihan, 0, ',', '.')); ?></strong>
            </div>
            <div class="detail-box">
                <small>Jatuh tempo</small>
                <strong><?php echo e(optional($selectedTagihan->jatuh_tempo)->translatedFormat('d M Y')); ?></strong>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTagihan->catatan): ?>
            <div class="list-item mt-3">
                <h6>Catatan</h6>
                <p><?php echo e($selectedTagihan->catatan); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <section class="section-card">
        <div class="section-title">
            <h5>Status pembayaran</h5>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedPayment): ?>
            <div class="list-item">
                <h6><?php echo e(ucfirst($selectedPayment->status_verifikasi)); ?></h6>
                <p><?php echo e(optional($selectedPayment->tanggal_bayar)->translatedFormat('d M Y') ?? 'Belum ada waktu pembayaran'); ?></p>
                <div class="meta-row">
                    <span><?php echo e($selectedPayment->metode_pembayaran ?? 'Metode belum tercatat'); ?></span>
                    <a href="<?php echo e(route('mobile.siswa.bukti', $selectedPayment->id)); ?>" class="ghost-btn" style="width:auto; padding:8px 12px;">Bukti pembayaran</a>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedPayment->payment_channel === 'bni_va' && $selectedPayment->va_number): ?>
                    <div class="meta-row mt-2">
                        <span>VA BNI: <?php echo e($selectedPayment->va_number); ?></span>
                        <span><?php echo e(optional($selectedPayment->va_expired_at)->translatedFormat('d M Y H:i') ?? '-'); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="list-item">
                <h6>Belum ada pembayaran</h6>
                <p>Tagihan ini belum memiliki transaksi yang tercatat.</p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($selectedTagihan->setting->payment_provider ?? 'manual') === 'bni_va'): ?>
                <a href="<?php echo e(route('mobile.siswa.billing', $selectedTagihan->id)); ?>" class="cta-btn">
                    <i class="bx bx-printer"></i>Cetak Billing & Terbitkan VA
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/detail.blade.php ENDPATH**/ ?>