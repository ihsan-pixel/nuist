<section id="sekolah" class="landing-home-carousel" aria-labelledby="landing-home-carousel-title">
    <h2 id="landing-home-carousel-title" class="section-title animate fade-up">
        Sekolah/Madrasah di bawah naungan kami
    </h2>

    <div class="landing-home-carousel__viewport animate fade-up delay-1" data-home-carousel>
        <ul class="landing-home-carousel__track" data-home-carousel-track aria-label="Daftar sekolah dan madrasah naungan NUIST">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <li class="landing-home-carousel__item">
                    <img
                        src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>"
                        alt="Logo <?php echo e($madrasah->name); ?>"
                        class="landing-home-carousel__logo"
                        width="150"
                        height="75"
                        loading="lazy"
                        decoding="async">
                    <p class="landing-home-carousel__name"><?php echo e($madrasah->name); ?></p>
                    <p class="landing-home-carousel__region"><?php echo e($madrasah->kabupaten); ?></p>
                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    </div>
</section>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/partials/home/carousel.blade.php ENDPATH**/ ?>