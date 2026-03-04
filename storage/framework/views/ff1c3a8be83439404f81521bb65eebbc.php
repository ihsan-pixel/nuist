<?php $__env->startSection('title', 'Hasil Assessment (Admin)'); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php echo $__env->make('talenta.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="section-clean">
<div class="container">
    <h2>Hasil Assessment (Admin)</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Sekolah</th>
                <th>Layanan</th>
                <th>Tata Kelola</th>
                <th>Jumlah Siswa</th>
                <th>Jumlah Penghasilan</th>
                <th>Jumlah Prestasi</th>
                <th>Jumlah Talenta</th>
                <th>Total Skor</th>
                <th>Level Sekolah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td><?php echo e(optional($s->school)->nama ?? 'N/A'); ?></td>
                <td><?php echo e($s->layanan); ?></td>
                <td><?php echo e($s->tata_kelola); ?></td>
                <td><?php echo e($s->jumlah_siswa); ?></td>
                <td><?php echo e($s->jumlah_penghasilan); ?></td>
                <td><?php echo e($s->jumlah_prestasi); ?></td>
                <td><?php echo e($s->jumlah_talenta); ?></td>
                <td><?php echo e($s->total_skor); ?></td>
                <td><?php echo e($s->level_sekolah); ?></td>
                <td><a href="#" class="btn btn-sm btn-info">Detail</a></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>
    <?php echo e($scores->links()); ?>

</div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/results/index.blade.php ENDPATH**/ ?>