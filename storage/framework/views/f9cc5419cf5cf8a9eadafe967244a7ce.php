<?php $__env->startSection('title', 'Dashboard Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Dashboard Siswa', 'subtitle' => $studentSchool->name ?? 'Akses pembayaran sekolah'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="hero-card">
        <small>Status pembayaran semester ini</small>
        <h4><?php echo e($paymentCompletionRate); ?>% selesai</h4>
        

        <div class="hero-stat-grid">
            <div>
                <strong>Rp <?php echo e(number_format($totalTagihanNominal, 0, ',', '.')); ?></strong>
                <small>Total tagihan</small>
            </div>
            <div>
                <strong>Rp <?php echo e(number_format($totalTerbayarNominal, 0, ',', '.')); ?></strong>
                <small>Total terbayar</small>
            </div>
            <div>
                <strong><?php echo e($notifications->count()); ?></strong>
                <small>Update baru</small>
            </div>
        </div>
    </section>

    <div class="summary-grid">
        <div class="mini-card">
            <small>Tagihan aktif</small>
            <h4><?php echo e($activeTagihan ? '1' : '0'); ?></h4>
            <div class="text-soft"><?php echo e($activeTagihan?->nomor_tagihan ?? 'Tidak ada tagihan aktif'); ?></div>
        </div>
        <div class="mini-card">
            <small>Riwayat pembayaran</small>
            <h4><?php echo e($payments->count()); ?></h4>
            <div class="text-soft">Transaksi terdokumentasi</div>
        </div>
    </div>

    <div class="menu-grid">
        <a class="menu-item" href="<?php echo e(route('mobile.siswa.tagihan')); ?>"><i class="bx bx-wallet-alt"></i><span>Tagihan</span></a>
        <a class="menu-item" href="<?php echo e(route('mobile.siswa.pembayaran')); ?>"><i class="bx bx-credit-card-front"></i><span>Pembayaran</span></a>
        <a class="menu-item" href="<?php echo e(route('mobile.siswa.riwayat')); ?>"><i class="bx bx-filter-alt"></i><span>Riwayat</span></a>
        <a class="menu-item" href="<?php echo e(route('mobile.siswa.notifikasi')); ?>"><i class="bx bx-bell-ring"></i><span>Notifikasi</span></a>
        <a class="menu-item" href="<?php echo e(route('mobile.siswa.chat')); ?>"><i class="bx bx-conversation"></i><span>Chat Admin</span></a>
        <a class="menu-item" href="<?php echo e(route('mobile.siswa.profile')); ?>"><i class="bx bx-id-card"></i><span>Profil</span></a>
    </div>

    <section class="section-card">
        <div class="section-title">
            <h5>📊 Grafik pembayaran</h5>
            <span class="pill pill-success"><?php echo e($chartSummary['lunas']); ?> lunas</span>
        </div>
        <div class="bar-row">
            <div class="bar-label">Lunas</div>
            <div class="bar-track">
                <div class="bar-fill-success" style="width: <?php echo e(max(8, $chartSummary['lunas'] === 0 ? 0 : round(($chartSummary['lunas'] / max(1, $tagihans->count())) * 100))); ?>%"></div>
            </div>
            <strong><?php echo e($chartSummary['lunas']); ?></strong>
        </div>
        <div class="bar-row">
            <div class="bar-label">Belum</div>
            <div class="bar-track">
                <div class="bar-fill-warning" style="width: <?php echo e(max(8, $chartSummary['belum'] === 0 ? 0 : round(($chartSummary['belum'] / max(1, $tagihans->count())) * 100))); ?>%"></div>
            </div>
            <strong><?php echo e($chartSummary['belum']); ?></strong>
        </div>
    </section>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($upcomingReminder): ?>
    <section class="section-card">
        <div class="section-title">
            <h5>📅 Reminder otomatis H-3</h5>
            <span class="pill pill-warning">Aktif</span>
        </div>
        <div class="list-item">
            <h6><?php echo e($upcomingReminder->title); ?></h6>
            <p><?php echo e($upcomingReminder->message); ?></p>
        </div>
    </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section class="list-card">
        <div class="section-title">
            <h5>Tagihan terbaru</h5>
            <a href="<?php echo e(route('mobile.siswa.tagihan')); ?>" class="text-soft">Lihat semua</a>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tagihans->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="list-item">
                <h6><?php echo e($tagihan->nomor_tagihan); ?></h6>
                <p>Periode <?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $tagihan->periode)->translatedFormat('F Y')); ?></p>
                <p>Jatuh tempo <?php echo e(optional($tagihan->jatuh_tempo)->translatedFormat('d M Y')); ?></p>
                <div class="meta-row">
                    <span class="pill <?php echo e($tagihan->status === 'lunas' ? 'pill-success' : ($tagihan->status === 'sebagian' ? 'pill-warning' : 'pill-danger')); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $tagihan->status))); ?></span>
                    <strong>Rp <?php echo e(number_format($tagihan->total_tagihan, 0, ',', '.')); ?></strong>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="list-item">
                <h6>Belum ada data tagihan</h6>
                <p>Masukkan data tagihan agar dashboard siswa menampilkan status pembayaran.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/dashboard.blade.php ENDPATH**/ ?>