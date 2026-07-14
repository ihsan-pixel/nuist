<section class="landing-home-stats" aria-labelledby="landing-home-stats-title">
    <div class="container">
        <h2 id="landing-home-stats-title" class="visually-hidden">Statistik Penggunaan Nuist</h2>

        <div class="landing-home-stats__grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <article class="landing-home-stats__item animate fade-up delay-<?php echo e(min($index + 1, 3)); ?>">
                    <p id="<?php echo e($stat['id']); ?>" class="landing-home-stats__value"><?php echo e($stat['value']); ?></p>
                    <p class="landing-home-stats__label"><?php echo e($stat['label']); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/partials/home/statistics.blade.php ENDPATH**/ ?>