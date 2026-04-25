<?php $__env->startSection('title', 'Tagihan Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Tagihan', 'subtitle' => 'Daftar tagihan siswa'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="section-card">
        <div class="section-title">
            <h5>Semua tagihan</h5>
            <span class="pill pill-info"><?php echo e($tagihans->count()); ?> data</span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tagihans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="list-item">
                <h6><?php echo e($tagihan->nomor_tagihan); ?></h6>
                <p><?php echo e($tagihan->jenis_tagihan ?? 'SPP'); ?> periode <?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $tagihan->periode)->translatedFormat('F Y')); ?></p>
                <div class="meta-row">
                    <span><?php echo e(optional($tagihan->jatuh_tempo)->translatedFormat('d M Y')); ?></span>
                    <strong>Rp <?php echo e(number_format($tagihan->total_tagihan, 0, ',', '.')); ?></strong>
                </div>
                <div class="meta-row">
                    <span class="pill <?php echo e($tagihan->status === 'lunas' ? 'pill-success' : ($tagihan->status === 'sebagian' ? 'pill-warning' : 'pill-danger')); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $tagihan->status))); ?></span>
                    <div style="display:flex; gap:8px;">
                        <a href="<?php echo e(route('mobile.siswa.detail', $tagihan->id)); ?>" class="ghost-btn" style="width:auto; padding:8px 12px;">Detail</a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($tagihan->setting->payment_provider ?? 'manual') === 'bni_va' && $tagihan->status !== 'lunas'): ?>
                            <a href="<?php echo e(route('mobile.siswa.billing', $tagihan->id)); ?>" class="ghost-btn" style="width:auto; padding:8px 12px;">Billing</a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="list-item">
                <h6>Belum ada tagihan</h6>
                <p>Daftar tagihan akan muncul di sini setelah dibuat oleh admin sekolah.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/tagihan.blade.php ENDPATH**/ ?>