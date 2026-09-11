<?php $__env->startSection('title', 'Riwayat Presensi'); ?>
<?php $__env->startSection('bpp-shell-class', 'bpp-history-shell'); ?>
<?php $__env->startSection('bpp-header'); ?>
<header class="bpp-history-header">
    <h1>Riwayat Presensi</h1>
    <p>Daftar kehadiran Anda pada kegiatan BPPPMNU</p>
</header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('bpp-content'); ?>
<style>
.bpp-shell.bpp-history-shell { max-width:760px; background:#f6f8f7; padding:16px 16px 32px; }
.bpp-history-header { margin-bottom:20px; }
.bpp-history-header h1 { font-size:15px; font-weight:600; line-height:1.4; margin:0 0 6px; color:#183d32; }
.bpp-history-header p { font-size:11px; color:#64756d; margin:0; }
.bpp-history-card { background:#fff; border:1px solid #e1e8e3; border-radius:12px; margin-bottom:12px; padding:18px; }
.bpp-history-status { display:inline-block; font-size:10px; font-weight:600; line-height:1.5; padding:4px 9px; border-radius:6px; margin-bottom:10px; background:#eef1ef; color:#59665f; }
.bpp-history-status-present { background:#e8f3eb; color:#235a3e; }
.bpp-history-shell .bpp-history-name { font-size:12px; font-weight:600; line-height:1.5; margin:0 0 5px; overflow-wrap:anywhere; }
.bpp-history-date { font-size:11px; color:#627269; margin:0; }
.bpp-history-detail { display:inline-flex; align-items:center; gap:8px; min-height:44px; margin-top:6px; font-size:11px; font-weight:600; color:#205c43; text-decoration:none; }
.bpp-history-detail:hover { color:#123d2c; text-decoration:underline; }
.bpp-history-detail:focus-visible { outline:2px solid #38775e; outline-offset:3px; border-radius:4px; }
.bpp-history-empty { text-align:center; padding:30px 20px; }
.bpp-history-empty h2 { font-size:12px; font-weight:600; margin:0 0 8px; }
.bpp-history-empty p { font-size:11px; color:#64756d; margin:0 auto; max-width:360px; }
.bpp-history-pagination { margin-top:24px; font-size:10px; }
.bpp-history-pagination .pagination { gap:4px; margin-bottom:0; flex-wrap:wrap; }
.bpp-history-pagination .page-link { min-width:40px; min-height:44px; display:inline-flex; justify-content:center; align-items:center; font-size:11px; }
@media(min-width:768px) { .bpp-shell.bpp-history-shell { padding:24px 24px 40px; } }
</style>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <article class="bpp-history-card">
        <span class="bpp-history-status <?php echo e($row->attended_at ? 'bpp-history-status-present' : ''); ?>"><?php echo e($row->attended_at ? 'Hadir' : 'Tidak Hadir'); ?></span>
        <h2 class="bpp-history-name"><?php echo e($row->name); ?></h2>
        <p class="bpp-history-date"><?php echo e(\Carbon\Carbon::parse($row->start_at)->locale('id')->translatedFormat('d F Y')); ?> &bull; <?php echo e(\Carbon\Carbon::parse($row->start_at)->format('H:i')); ?> WIB</p>
        <a class="bpp-history-detail" href="<?php echo e(route('mobile.bpppmnu.events.show', $row->id)); ?>">Lihat Detail <span aria-hidden="true">&rarr;</span></a>
    </article>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <div class="bpp-history-card bpp-history-empty">
        <h2>Belum ada riwayat</h2>
        <p>Riwayat presensi akan muncul setelah kegiatan selesai dan presensi ditutup.</p>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<div class="bpp-history-pagination">
    <?php echo e($history->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mobile.bpppmnu.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/bpppmnu/history.blade.php ENDPATH**/ ?>