<?php $__env->startSection('landing_shell', '1'); ?>
<?php $__env->startSection('title', 'Sekolah/Madrasah - NUIST'); ?>
<?php $__env->startSection('description', 'Daftar Sekolah/Madrasah Dibawah Naungan LPMNU PWNU DIY'); ?>

<?php $__env->startSection('css'); ?>
<?php echo $__env->make('landing._sekolah_styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $mapLocations = $madrasahs
        ->filter(fn ($madrasah) => $madrasah->latitude && $madrasah->longitude)
        ->map(fn ($madrasah) => [
            'lat' => (float) $madrasah->latitude,
            'lng' => (float) $madrasah->longitude,
            'name' => $madrasah->name,
            'kabupaten' => $madrasah->kabupaten,
            'detailUrl' => route('landing.sekolah.detail', $madrasah->id),
        ])
        ->values();
?>

<div class="landing-page landing-school-page" data-landing-page="sekolah">
    <!-- HERO -->
    <section id="hero" class="hero">
        <div class="container">
            <h1 class="hero-title">Sekolah/Madrasah</h1>
            <h1 class="hero-subtitle" style="color: #eda711">Dibawah Naungan LPMNU PWNU DIY</h1>
            <p>Temukan sekolah dan madrasah yang menjadi bagian dari ekosistem pendidikan kami. Klik pada sekolah untuk melihat profil lengkapnya.</p>
        </div>
    </section>

    <!-- MAP SECTION -->
    <section id="map-section" class="map-section">
        <div class="container">
            <h2 class="section-title animate fade-up" style="margin-bottom:50px; font-size:24px;">Peta Lokasi Madrasah di Yogyakarta</h2>
            <div id="map"></div>
        </div>
    </section>

    <!-- SEKOLAH LIST -->
    <section id="sekolah-list" class="sekolah-list">
        <div class="container">
            <h2 class="section-title animate fade-up" style="margin-bottom:50px; font-size:24px;">Daftar Sekolah/Madrasah</h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groupedMadrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten => $madrasahList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div class="kabupaten-section animate fade-up">
                    <h3 class="kabupaten-header">
                        <span class="kabupaten-icon">📍</span>
                        <?php echo e($kabupaten); ?>

                        <span class="kabupaten-count">(<?php echo e(count($madrasahList)); ?> sekolah)</span>
                    </h3>
                    <div class="schools-grid">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <a href="<?php echo e(route('landing.sekolah.detail', $madrasah->id)); ?>" class="school-card-link">
                                <div class="school-card">
                                    <div class="school-logo">
                                        <img src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>" alt="<?php echo e($madrasah->name); ?>" loading="lazy" decoding="async">
                                    </div>
                                    <div class="school-info">
                                        <h3><?php echo e($madrasah->name); ?></h3>
                                        <p><?php echo e($madrasah->kabupaten); ?></p>
                                    </div>
                                </div>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </section>

    <script type="application/json" data-sekolah-map-locations>
        <?php echo json_encode($mapLocations, 15, 512) ?>
    </script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/sekolah.blade.php ENDPATH**/ ?>