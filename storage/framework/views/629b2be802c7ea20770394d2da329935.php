<?php $__env->startSection('title', 'Pembayaran Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Pembayaran', 'subtitle' => 'Instruksi dan kanal pembayaran'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('bni_va')): ?>
        <section class="section-card"><div class="list-item"><h6>BNI VA</h6><p><?php echo e($errors->first('bni_va')); ?></p></div></section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-credit-card-alt"></i>Pembayaran aktif</span>
        <h4><?php echo e($activeTagihan?->nomor_tagihan ?? 'Belum ada tagihan'); ?></h4>
        <p class="mb-0">Gunakan halaman ini untuk melihat nominal, membuat Virtual Account BNI, dan memantau status pembayaran tagihan siswa.</p>
        <div class="hero-meta">
            <span class="hero-chip"><i class="bx bx-calendar"></i><?php echo e($activeTagihan ? \Carbon\Carbon::createFromFormat('Y-m', $activeTagihan->periode)->translatedFormat('F Y') : 'Belum ada periode aktif'); ?></span>
            <span class="hero-chip"><i class="bx bx-wallet"></i>Rp <?php echo e(number_format($activeTagihan->total_tagihan ?? 0, 0, ',', '.')); ?></span>
        </div>
    </section>

    <section class="section-card">
        <div class="section-title">
            <div>
                <h5>Ringkasan pembayaran</h5>
                <p class="section-subtitle">Info singkat untuk tagihan yang sedang berjalan</p>
            </div>
            <span class="pill pill-info"><?php echo e($activeTagihan?->status ? ucfirst(str_replace('_', ' ', $activeTagihan->status)) : 'Kosong'); ?></span>
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <small>Periode</small>
                <strong><?php echo e($activeTagihan ? \Carbon\Carbon::createFromFormat('Y-m', $activeTagihan->periode)->translatedFormat('F Y') : '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp <?php echo e(number_format($activeTagihan->total_tagihan ?? 0, 0, ',', '.')); ?></strong>
            </div>
            <div class="detail-box">
                <small>Jatuh tempo</small>
                <strong><?php echo e(optional($activeTagihan?->jatuh_tempo)->translatedFormat('d M Y') ?? '-'); ?></strong>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTagihan): ?>
            <div class="mt-3">
                <a href="<?php echo e(route('mobile.siswa.detail', $activeTagihan->id)); ?>" class="cta-btn">
                    <i class="bx bx-file-find"></i>Lihat detail pembayaran
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <section class="section-card">
        <div class="section-title">
            <div>
                <h5>BNI Virtual Account</h5>
                <p class="section-subtitle">Pembayaran digital untuk tagihan aktif</p>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTagihan && ($activeTagihan->setting->payment_provider ?? 'manual') === 'bni_va' && $bniVaEnabled): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeVaTransaction && $activeVaTransaction->va_number): ?>
                <div class="list-item">
                    <div class="list-kicker"><i class="bx bx-key"></i>Virtual account aktif</div>
                    <h6>Nomor VA BNI</h6>
                    <p style="font-size:1.15rem; font-weight:700; letter-spacing:1px;"><?php echo e($activeVaTransaction->va_number); ?></p>
                    <small>Berlaku sampai <?php echo e(optional($activeVaTransaction->va_expired_at)->translatedFormat('d M Y H:i') ?? '-'); ?></small>
                </div>
                <div class="note-card">
                    <h6>Langkah pembayaran</h6>
                    <p>Bayar sesuai nominal tagihan melalui ATM BNI, BNI Mobile Banking, atau teller dengan memasukkan nomor Virtual Account di atas.</p>
                </div>
                <a href="<?php echo e(route('mobile.siswa.billing', $activeTagihan->id)); ?>" class="cta-btn">
                    <i class="bx bx-printer"></i>Cetak Billing
                </a>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bx bx-printer"></i>
                    <h6>Billing belum dicetak</h6>
                    <p>Virtual Account BNI akan diterbitkan saat Anda mencetak billing tagihan aktif ini.</p>
                </div>
                <a href="<?php echo e(route('mobile.siswa.billing', $activeTagihan->id)); ?>" class="cta-btn">
                    <i class="bx bx-printer"></i>Cetak Billing & Terbitkan VA
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php elseif($activeTagihan && ($activeTagihan->setting->payment_provider ?? 'manual') === 'bni_va'): ?>
            <div class="empty-state">
                <i class="bx bx-power-off"></i>
                <h6>BNI Virtual Account belum aktif</h6>
                <p>Administrator belum mengaktifkan konfigurasi BNI Virtual Account pada aplikasi.</p>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bx bx-receipt"></i>
                <h6>Pembayaran manual</h6>
                <p>Tagihan ini masih menggunakan mekanisme pembayaran manual. Hubungi admin sekolah untuk konfirmasi pembayaran.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTagihan?->setting?->payment_notes): ?>
            <div class="note-card warning">
                <h6>Catatan pembayaran</h6>
                <p><?php echo e($activeTagihan->setting->payment_notes); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <a href="<?php echo e(route('mobile.siswa.chat')); ?>" class="ghost-btn">
            <i class="bx bx-message-square-detail"></i>Hubungi admin sekolah
        </a>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/pembayaran.blade.php ENDPATH**/ ?>