<?php $__env->startSection('title', (isset($question) && $question->id) ? 'Edit Soal' : 'Tambah Soal'); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php echo $__env->make('talenta.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.partials.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('talenta.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<section class="section-clean">
<div class="container">
    <h2><?php echo e(isset($question) && $question->id ? 'Edit Soal' : 'Tambah Soal'); ?></h2>
    <form method="POST" action="<?php echo e(isset($question) && $question->id ? route('talenta.questions.update', $question) : route('talenta.questions.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($question) && $question->id): ?> <?php echo method_field('PUT'); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['layanan','tata_kelola','jumlah_siswa','jumlah_penghasilan','jumlah_prestasi','jumlah_talenta']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <option value="<?php echo e($cat); ?>" <?php echo e((old('kategori', $question->kategori ?? '') == $cat) ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Pertanyaan</label>
            <textarea name="pertanyaan" class="form-control"><?php echo e(old('pertanyaan', $question->pertanyaan ?? '')); ?></textarea>
        </div>
        <div class="mb-3">
            <label>Skor Ya</label>
            <input type="number" name="skor_ya" class="form-control" value="<?php echo e(old('skor_ya', $question->skor_ya ?? 1)); ?>">
        </div>
        <div class="mb-3">
            <label>Skor Tidak</label>
            <input type="number" name="skor_tidak" class="form-control" value="<?php echo e(old('skor_tidak', $question->skor_tidak ?? 0)); ?>">
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
</section>

<?php echo $__env->make('talenta.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/questions/form.blade.php ENDPATH**/ ?>