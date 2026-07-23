<section id="features" class="landing-home-features" aria-labelledby="landing-home-features-title">
    <h2 id="landing-home-features-title" class="section-title animate fade-up">Fitur Unggulan</h2>
    <p class="landing-home-features__description animate fade-up delay-1">
        Berbagai fitur canggih yang dirancang untuk memaksimalkan efisiensi dan keamanan dalam pengelolaan sekolah Anda.
    </p>

    <div class="landing-home-features__grid animate fade-up delay-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = collect($landing->features ?? [])->filter(fn ($feature) => in_array($feature['status'] ?? null, ['active', 'coming_soon'], true)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <article class="landing-home-feature-card animate fade-up delay-<?php echo e(($index % 3) + 1); ?> <?php echo e(($feature['status'] ?? null) === 'coming_soon' ? 'is-coming-soon' : 'is-active'); ?>">
                <h3><?php echo e($feature['name']); ?></h3>
                <p><?php echo e($feature['content']); ?></p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($feature['status'] ?? null) === 'coming_soon'): ?>
                    <div class="landing-home-feature-card__ribbon">Coming Soon</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
</section>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/partials/home/features.blade.php ENDPATH**/ ?>