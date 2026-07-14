<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php
    $hasLandingShell = trim((string) $__env->yieldContent('landing_shell')) !== '';
    $landingNavVersion = file_exists(public_path('build/manifest.json'))
        ? substr(md5_file(public_path('build/manifest.json')), 0, 12)
        : app()->version();
    $landingShellCssPath = public_path('build/css/landing-shell.min.css');
    $landingShellJsPath = public_path('build/js/landing-shell.js');
    $hasLandingShellAssets = file_exists($landingShellCssPath) && file_exists($landingShellJsPath);
    $landingShellSourceCssPath = resource_path('css/landing-shell.css');
    $landingShellSourceJsPath = resource_path('js/landing-shell.js');
    $landingShellSourceCss = file_exists($landingShellSourceCssPath) ? file_get_contents($landingShellSourceCssPath) : '';
    $landingShellSourceJs = file_exists($landingShellSourceJsPath) ? file_get_contents($landingShellSourceJsPath) : '';
    $landingShellFallbackJs = $landingShellSourceJs
        ? preg_replace("/^\\s*import\\s+['\"][^'\"]*landing-shell\\.css['\"];\\s*/m", '', $landingShellSourceJs)
        : '';
    $metaDescription = trim((string) $__env->yieldContent('description')) ?: "Nuist.id adalah Sistem Informasi Digital LP. Ma'arif NU PWNU DIY untuk manajemen data madrasah, tenaga pendidik, dan laporan pendidikan berbasis web.";
    $pageTitle = trim((string) $__env->yieldContent('title'))
        ? trim((string) $__env->yieldContent('title')) . ' | Nuist.id - Sistem Informasi Digital LP. Ma\'arif NU PWNU DIY'
        : 'Nuist.id - Sistem Informasi Digital LP. Ma\'arif NU PWNU DIY';
    $canonicalUrl = url()->current();
    $ogImage = asset('images/logo favicon 1.png');
?>

<head>
    <meta charset="utf-8" />
    <title id="page-title"><?php echo e($pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- SEO Meta -->
    <meta id="meta-description" name="description" content="<?php echo e($metaDescription); ?>">
    <meta name="keywords" content="nuist.id, sistem informasi madrasah, LP Ma'arif NU, pendidikan DIY, aplikasi sekolah, aplikasi madrasah, pendidikan digital">
    <meta name="author" content="Nuist.id">
    <link id="meta-canonical" rel="canonical" href="<?php echo e($canonicalUrl); ?>">

    <!-- Open Graph -->
    <meta id="meta-og-title" property="og:title" content="<?php echo e($pageTitle); ?>" />
    <meta id="meta-og-description" property="og:description" content="<?php echo e($metaDescription); ?>" />
    <meta id="meta-og-url" property="og:url" content="<?php echo e($canonicalUrl); ?>" />
    <meta property="og:type" content="website" />
    <meta id="meta-og-image" property="og:image" content="<?php echo e($ogImage); ?>" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e($ogImage); ?>">

    
    <?php echo $__env->make('layouts.head-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($hasLandingShell)): ?>
        <?php echo $__env->yieldContent('css'); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasLandingShellAssets): ?>
        <link href="<?php echo e(asset('build/css/landing-shell.min.css')); ?>" rel="stylesheet" type="text/css" />
        <script type="module" src="<?php echo e(asset('build/js/landing-shell.js')); ?>"></script>
    <?php elseif($hasLandingShell): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($landingShellSourceCss): ?>
            <style data-landing-shell-fallback>
                <?php echo $landingShellSourceCss; ?>

            </style>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($landingShellFallbackJs): ?>
            <script type="module" data-landing-shell-fallback>
                <?php echo $landingShellFallbackJs; ?>

            </script>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>


<?php echo $__env->yieldContent('body'); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasLandingShell): ?>
        <div
            id="landing-shell"
            data-landing-shell
            data-nav-version="<?php echo e($landingNavVersion); ?>"
            data-current-url="<?php echo e($canonicalUrl); ?>"
        >
            <?php echo $__env->make('landing.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="landing-nav-loading" data-landing-loading hidden aria-hidden="true">
                <div class="landing-nav-loading__bar"></div>
            </div>

            <div class="landing-nav-status visually-hidden" data-landing-status aria-live="polite" aria-atomic="true"></div>

            <main id="landing-content" data-landing-content tabindex="-1">
                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <div data-landing-page-assets hidden aria-hidden="true">
                <?php echo $__env->yieldContent('css'); ?>
                <?php echo $__env->yieldContent('script-bottom'); ?>
                <?php echo $__env->yieldContent('scripts'); ?>
            </div>

            <?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    <?php else: ?>
        
        <?php echo $__env->yieldContent('content'); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php echo $__env->make('layouts.vendor-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($hasLandingShell)): ?>
        <?php echo $__env->yieldContent('script-bottom'); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($hasLandingShell)): ?>
        <?php echo $__env->yieldContent('scripts'); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <script>
        // If the app is running as a PWA (installed / standalone), prefer the mobile login page.
        document.addEventListener('DOMContentLoaded', function(){
            function isPWA() {
                try {
                    return (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches)
                        || window.navigator.standalone === true;
                } catch (e) { return false; }
            }

            if (!isPWA()) return;

            var path = window.location.pathname || '/';
            var qs = window.location.search || '';

            // Redirect common login routes to the mobile login when in PWA context
            var loginPaths = ['/login', '/index', '/mobile-app', '/auth-login', '/auth-login-2', '/talenta/login'];
            for (var i=0;i<loginPaths.length;i++){
                if (path === loginPaths[i] || path.indexOf(loginPaths[i]) === 0) {
                    // avoid redirect loops if we're already on mobile login
                    if (path === '/mobile/login') return;
                    window.location.replace('/mobile/login' + qs);
                    return;
                }
            }
        });
    </script>

</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/layouts/master-without-nav.blade.php ENDPATH**/ ?>