<nav class="landing-navbar" data-landing-navbar>
    <div class="container nav-flex">
        <div class="nav-left">
            <a href="<?php echo e(route('landing')); ?>" class="brand-mark" aria-label="NUIST" data-nav-ajax="true">
                <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="NUIST">
            </a>

            <button
                type="button"
                class="hamburger"
                id="landing-navbar-toggle"
                aria-expanded="false"
                aria-controls="landing-nav-menu"
                aria-label="Buka menu navigasi">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="nav-menu" id="landing-nav-menu">
                <li><a href="<?php echo e(route('landing')); ?>" class="<?php echo e(request()->routeIs('landing') ? 'active' : ''); ?>" data-nav-ajax="true">Beranda</a></li>
                <li class="nav-dropdown">
                    <details class="nav-dropdown-details">
                        <summary class="<?php echo e(request()->routeIs('landing.produk') ? 'active' : ''); ?>">
                            <span>Produk</span>
                            <i class='bx bx-chevron-down'></i>
                        </summary>
                        <div class="nav-submenu">
                            <a href="<?php echo e(route('landing.produk')); ?>" class="<?php echo e(request()->routeIs('landing.produk') ? 'active' : ''); ?>" data-nav-ajax="true">Semua Produk</a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ($subdomainProducts ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdomainProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <a href="<?php echo e($subdomainProduct['url']); ?>" target="_blank" rel="noopener noreferrer">
                                    <span><?php echo e($subdomainProduct['name']); ?></span>
                                    <small><?php echo e($subdomainProduct['domain']); ?></small>
                                </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </details>
                </li>
                <li><a href="<?php echo e(route('landing.sekolah')); ?>" class="<?php echo e(request()->routeIs('landing.sekolah') ? 'active' : ''); ?>" data-nav-ajax="true">Sekolah</a></li>
                <li><a href="<?php echo e(route('landing.tentang')); ?>" class="<?php echo e(request()->routeIs('landing.tentang') ? 'active' : ''); ?>" data-nav-ajax="true">Tentang</a></li>
                <li><a href="<?php echo e(route('landing.kontak')); ?>" class="<?php echo e(request()->routeIs('landing.kontak') ? 'active' : ''); ?>" data-nav-ajax="true">Kontak</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/navbar.blade.php ENDPATH**/ ?>