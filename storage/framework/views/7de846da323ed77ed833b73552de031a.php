<?php $__env->startSection('title', 'Bukti Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Bukti Pembayaran', 'subtitle' => 'Dokumen transaksi siswa'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-badge-check"></i>Receipt pembayaran</span>
        <h4>Rp <?php echo e(number_format($selectedPayment->nominal_bayar, 0, ',', '.')); ?></h4>
        <p class="mb-0">Dokumen transaksi ini menampilkan rincian pembayaran yang sudah tercatat pada sistem sekolah.</p>
        <div class="hero-meta">
            <span class="hero-chip"><i class="bx bx-receipt"></i><?php echo e($selectedTagihan->nomor_tagihan ?? '-'); ?></span>
            <span class="hero-chip"><i class="bx bx-shield"></i><?php echo e(ucfirst($selectedPayment->status_verifikasi)); ?></span>
        </div>
    </section>

    <section class="receipt-card">
        <div class="section-title">
            <div>
                <h5>Receipt</h5>
                <p class="section-subtitle">Bukti transaksi pembayaran siswa</p>
            </div>
            <span class="pill <?php echo e($selectedPayment->status_verifikasi === 'diverifikasi' ? 'pill-success' : ($selectedPayment->status_verifikasi === 'menunggu' ? 'pill-warning' : 'pill-danger')); ?>"><?php echo e(ucfirst($selectedPayment->status_verifikasi)); ?></span>
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <small>No. transaksi</small>
                <strong><?php echo e($selectedPayment->nomor_transaksi); ?></strong>
            </div>
            <div class="detail-box">
                <small>Invoice</small>
                <strong><?php echo e($selectedTagihan->nomor_tagihan ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Metode</small>
                <strong><?php echo e(strtoupper($selectedPayment->metode_pembayaran ?? '-')); ?></strong>
            </div>
            <div class="detail-box">
                <small>Channel</small>
                <strong><?php echo e(strtoupper($selectedPayment->payment_channel ?? '-')); ?></strong>
            </div>
            <div class="detail-box">
                <small>VA BNI</small>
                <strong><?php echo e($selectedPayment->va_number ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Waktu bayar</small>
                <strong><?php echo e(optional($selectedPayment->tanggal_bayar)->translatedFormat('d M Y') ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Nominal</small>
                <strong>Rp <?php echo e(number_format($selectedPayment->nominal_bayar, 0, ',', '.')); ?></strong>
            </div>
            <div class="detail-box">
                <small>Sekolah</small>
                <strong><?php echo e($studentSchool->name ?? '-'); ?></strong>
            </div>
        </div>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/bukti.blade.php ENDPATH**/ ?>