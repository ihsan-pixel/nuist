<?php $__env->startSection('title', 'Notifikasi Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Notifikasi', 'subtitle' => 'Reminder dan update pembayaran'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-bell"></i>Pusat notifikasi</span>
        <h4><?php echo e($notifications->count()); ?> update masuk</h4>
        <p class="mb-0">Semua reminder pembayaran dan pemberitahuan terbaru sekolah dikumpulkan di satu tempat.</p>
    </section>

    <section class="list-card">
        <div class="section-title">
            <div>
                <h5>Semua notifikasi</h5>
                <p class="section-subtitle">Reminder jatuh tempo dan pembaruan pembayaran</p>
            </div>
            <span class="pill pill-info"><?php echo e($notifications->count()); ?> item</span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="notif-item">
                <div class="notif-icon">
                    <i class="bx <?php echo e($notification->type === 'reminder' ? 'bx-time-five' : 'bx-bell'); ?>"></i>
                </div>
                <div>
                    <h6 class="mb-1"><?php echo e($notification->title); ?></h6>
                    <p class="mb-1"><?php echo e($notification->message); ?></p>
                    <small><?php echo e(optional($notification->created_at)->diffForHumans()); ?></small>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="empty-state">
                <i class="bx bx-bell-off"></i>
                <h6>Belum ada notifikasi</h6>
                <p>Notifikasi pembayaran dan reminder H-3 jatuh tempo akan tampil di sini.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/notifikasi.blade.php ENDPATH**/ ?>