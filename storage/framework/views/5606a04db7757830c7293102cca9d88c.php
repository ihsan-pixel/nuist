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
                <li><a href="<?php echo e(route('landing.produk')); ?>" class="<?php echo e(request()->routeIs('landing.produk') ? 'active' : ''); ?>" data-nav-ajax="true">Produk</a></li>
                <li><a href="<?php echo e(route('landing.sekolah')); ?>" class="<?php echo e(request()->routeIs('landing.sekolah') ? 'active' : ''); ?>" data-nav-ajax="true">Sekolah</a></li>
                <li><a href="<?php echo e(route('landing.tentang')); ?>" class="<?php echo e(request()->routeIs('landing.tentang') ? 'active' : ''); ?>" data-nav-ajax="true">Tentang</a></li>
                <li><a href="<?php echo e(route('landing.kontak')); ?>" class="<?php echo e(request()->routeIs('landing.kontak') ? 'active' : ''); ?>" data-nav-ajax="true">Kontak</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/navbar.blade.php ENDPATH**/ ?>