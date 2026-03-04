<?php $__env->startSection('title', 'Detail Nilai Sekolah'); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php echo $__env->make('talenta.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="section-clean">
<div class="container">
    <h2>Detail Nilai Sekolah</h2>
    <ul>
        <li>Skor Layanan: <?php echo e($score->layanan); ?></li>
        <li>Skor Tata Kelola: <?php echo e($score->tata_kelola); ?></li>
        <li>Skor Jumlah Siswa: <?php echo e($score->jumlah_siswa); ?></li>
        <li>Skor Jumlah Penghasilan: <?php echo e($score->jumlah_penghasilan); ?></li>
        <li>Skor Jumlah Prestasi: <?php echo e($score->jumlah_prestasi); ?></li>
        <li>Skor Jumlah Talenta: <?php echo e($score->jumlah_talenta); ?></li>
        <li>Total Skor: <?php echo e($score->total_skor); ?></li>
        <li>Level Sekolah: <?php echo e($score->level_sekolah); ?></li>
    </ul>
</div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/rekap/detail.blade.php ENDPATH**/ ?>