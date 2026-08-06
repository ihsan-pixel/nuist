<?php $__env->startSection('title', 'Hasil Saya'); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php echo $__env->make('talenta.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="section-clean">
<div class="container">
    <h2>Hasil Saya</h2>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schoolScore): ?>
        <ul>
            <li>Skor Layanan: <?php echo e($schoolScore->layanan); ?></li>
            <li>Skor Tata Kelola: <?php echo e($schoolScore->tata_kelola); ?></li>
            <li>Skor Jumlah Siswa: <?php echo e($schoolScore->jumlah_siswa); ?></li>
            <li>Skor Jumlah Penghasilan: <?php echo e($schoolScore->jumlah_penghasilan); ?></li>
            <li>Skor Jumlah Prestasi: <?php echo e($schoolScore->jumlah_prestasi); ?></li>
            <li>Skor Jumlah Talenta: <?php echo e($schoolScore->jumlah_talenta); ?></li>
            <li>Total Skor: <?php echo e($schoolScore->total_skor); ?></li>
            <li>Level Sekolah: <?php echo e($schoolScore->level_sekolah); ?></li>
        </ul>
    <?php else: ?>
        <p>Belum ada hasil assessment untuk sekolah Anda.</p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/assessment/my_results.blade.php ENDPATH**/ ?>