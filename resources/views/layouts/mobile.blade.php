<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | NUIST Mobile</title>
    <base href="{{ url('/') }}/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="NUIST Mobile - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta name="keywords" content="nuist, ma'arif, nu, pwnu diy, sistem informasi, mobile, pwa" />
    <meta name="author" content="LP. Ma'arif NU PWNU DIY" />
    <meta name="theme-color" content="#556ee6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NUIST Mobile">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('build/images/logo-light.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('build/images/logo-light.png') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('build/images/favicon.ico') }}">

    <!-- Open Graph for better social sharing -->
    <meta property="og:title" content="@yield('title') | NUIST Mobile" />
    <meta property="og:description" content="NUIST Mobile - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('build/images/logo-light.png') }}" />

    @include('layouts.head-css')

    <!-- Mobile-specific CSS -->
    <style>
        /* Mobile-first responsive design */
        body {
            font-size: 14px;
            line-height: 1.4;
        }

        /* Bottom navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 12px;
            z-index: 1030;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }

        .mobile-nav .nav-item {
            flex: 1;
            text-align: center;
        }

        .mobile-nav .nav-link {
            padding: 8px 4px;
            color: #0e8549;
            font-size: 11px;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            background: white;
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.2s;
            min-height: 50px;
            justify-content: center;
        }

        .mobile-nav .nav-link.active {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
        }

        .mobile-nav .nav-link i {
            font-size: 18px;
            margin-bottom: 2px;
        }

        .mobile-nav .nav-link.active {
            flex-direction: column;
        }

        .mobile-nav .nav-link.active i {
            margin-bottom: 2px;
            margin-right: 0;
        }

        .mobile-nav .nav-link:not(.active) span {
            display: none;
        }

        /* Content padding for bottom nav */
        .mobile-content {
            padding-bottom: 90px;
        }

        /* Card optimizations for mobile */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            padding: 16px;
        }

        /* Button optimizations */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 12px 16px;
        }

        .btn-lg {
            padding: 14px 20px;
            font-size: 16px;
        }

        /* Form controls */
        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 12px 16px;
        }

        .form-control:focus {
            border-color: #556ee6;
            box-shadow: 0 0 0 0.2rem rgba(85, 110, 230, 0.25);
        }

        /* Alert optimizations */
        .alert {
            border-radius: 8px;
            border: none;
        }

        /* Avatar optimizations */
        .avatar-lg {
            width: 48px !important;
            height: 48px !important;
        }

        /* Hide desktop elements on mobile */
        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }

            .sidebar {
                display: none !important;
            }

            .topbar {
                display: none !important;
            }
        }

        /* Loading states */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Swipe gestures */
        .swipe-container {
            touch-action: pan-y;
        }

        /* Pull to refresh */
        .pull-refresh {
            transform: translateY(-60px);
            transition: transform 0.3s ease;
        }

        .pull-refresh.pulling {
            transform: translateY(0);
        }

        /* Offline indicator */
        .offline-indicator {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #dc3545;
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 12px;
            z-index: 1040;
            display: none;
        }

        /* PWA install prompt */
        .pwa-install-prompt {
            position: fixed;
            bottom: 80px;
            left: 16px;
            right: 16px;
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1035;
            display: none;
        }

    </style>
</head>

<body data-layout-mode="light" class="mobile-layout">
    <!-- Offline indicator -->
    <div id="offline-indicator" class="offline-indicator">
        <i class="bx bx-wifi-off me-1"></i>
        Anda sedang offline. Beberapa fitur mungkin tidak tersedia.
    </div>

    <!-- PWA Install Prompt -->
    <div id="pwa-install-prompt" class="pwa-install-prompt">
        <div class="d-flex align-items-center">
            <img src="{{ asset('build/images/logo-light.png') }}" alt="NUIST" width="40" height="40" class="me-3 rounded">
            <div class="flex-grow-1">
                <h6 class="mb-1">Install NUIST Mobile</h6>
                <small class="text-muted">Akses lebih cepat dan offline</small>
            </div>
            <div>
                <button id="install-pwa" class="btn btn-primary btn-sm me-2">Install</button>
                <button id="dismiss-pwa" class="btn btn-outline-secondary btn-sm">Nanti</button>
            </div>
        </div>
    </div>

    <!-- Mobile Header -->
    <header class="bg-white border-bottom d-md-none">
        <div class="container-fluid px-3 py-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('build/images/logo-light.png') }}" alt="NUIST" height="32" class="me-2">
                    <div>
                        <h6 class="mb-0 fw-bold">NUIST Mobile</h6>
                        <small class="text-muted">@yield('subtitle', 'Sistem Informasi Digital')</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Notification Bell -->
                    <a href="{{ route('mobile.notifications') }}" class="btn btn-link text-decoration-none p-0 me-3 position-relative">
                        <i class="bx bx-bell" style="font-size: 24px; color: #0e8549;"></i>
                        <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 10px; padding: 2px 6px; top: -5px; right: -5px; display: none;">0</span>
                    </a>

                    <!-- User Avatar Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                            <div class="avatar-sm">
                                <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="44" height="44" alt="User">
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('mobile.notifications') }}"><i class="bx bx-bell me-2"></i>Notifikasi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bx bx-home me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out me-2"></i>Logout
                            </a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mobile-content" style="padding-top: 0;">
        {{-- Jika halaman Dashboard, hilangkan header putih dan beri ruang penuh --}}
        @if(request()->routeIs('mobile.dashboard'))
            <div class="position-relative" style="margin-top:-10px;">
                @yield('content')
            </div>
        @else
            <div class="container-fluid px-3 py-3">
                @yield('content')
            </div>
        @endif
    </main>


    <!-- Mobile Bottom Navigation -->
    @php
        $menuRoutes = ['mobile.dashboard', 'mobile.presensi*', 'mobile.jadwal*', 'mobile.teaching-attendances*', 'mobile.profile'];
        $showNav = false;
        foreach ($menuRoutes as $route) {
            if (request()->routeIs($route)) {
                $showNav = true;
                break;
            }
        }
    @endphp
    @if($showNav)
    <nav class="mobile-nav d-md-none">
        <div class="container-fluid">
            <div class="row g-0">
                <div class="col">
                    <a href="{{ route('mobile.dashboard') }}" class="nav-link {{ request()->routeIs('mobile.dashboard') ? 'active' : '' }}">
                        <i class="bx bx-home"></i>
                        <span>Beranda</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('mobile.presensi') }}" class="nav-link {{ request()->routeIs('mobile.presensi*') ? 'active' : '' }}">
                        <i class="bx bx-check-square"></i>
                        <span>Presensi</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('mobile.jadwal') }}" class="nav-link {{ request()->routeIs('mobile.jadwal*') ? 'active' : '' }}">
                        <i class="bx bx-calendar"></i>
                        <span>Jadwal</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('mobile.teaching-attendances') }}" class="nav-link {{ request()->routeIs('mobile.teaching-attendances*') ? 'active' : '' }}">
                        <i class="bx bx-chalkboard"></i>
                        <span>Mengajar</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('mobile.profile') }}" class="nav-link {{ request()->routeIs('mobile.profile') ? 'active' : '' }}">
                        <i class="bx bx-user"></i>
                        <span>Profil</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    @endif

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    <!-- Face Recognition Scripts -->
    <script>
        window.MODEL_PATH = "{{ asset('models') }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="{{ asset('js/face-recognition.js') }}"></script>

    <!-- Mobile-specific scripts -->
    <script>
        // PWA Install Prompt
        let deferredPrompt;
        const installPrompt = document.getElementById('pwa-install-prompt');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show install prompt if not dismissed before
            if (!localStorage.getItem('pwa-install-dismissed')) {
                setTimeout(() => {
                    installPrompt.style.display = 'block';
                }, 3000);
            }
        });

        document.getElementById('install-pwa').addEventListener('click', () => {
            installPrompt.style.display = 'none';
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                });
            }
        });

        document.getElementById('dismiss-pwa').addEventListener('click', () => {
            installPrompt.style.display = 'none';
            localStorage.setItem('pwa-install-dismissed', 'true');
        });

        // Offline detection
        const offlineIndicator = document.getElementById('offline-indicator');

        window.addEventListener('online', () => {
            offlineIndicator.style.display = 'none';
        });

        window.addEventListener('offline', () => {
            offlineIndicator.style.display = 'block';
        });

        //<!-- Service Worker Auto-Refresh & Cache Cleanup -->
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', async () => {
                try {
                    // Unregister old service workers first
                    const regs = await navigator.serviceWorker.getRegistrations();
                    for (let reg of regs) await reg.update();

                    // Register new SW
                    const reg = await navigator.serviceWorker.register('/sw.js');
                    console.log('SW registered:', reg.scope);

                    // Auto refresh when new SW is ready
                    if (reg.waiting) {
                        reg.waiting.postMessage({ type: 'SKIP_WAITING' });
                    }

                    reg.addEventListener('updatefound', () => {
                        const newWorker = reg.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('New SW installed, reloading...');
                                window.location.reload();
                            }
                        });
                    });

                    // Listen for controller change (new SW taking over)
                    navigator.serviceWorker.addEventListener('controllerchange', () => {
                        console.log('Controller changed → reload');
                        window.location.reload();
                    });

                    // Clear old caches automatically
                    caches.keys().then(names => {
                        for (let name of names) caches.delete(name);
                    });
                } catch (e) {
                    console.warn('SW registration failed:', e);
                }
            });
        }

        // Pull to refresh functionality
        let startY = 0;
        let currentY = 0;
        let isPulling = false;

        document.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
        });

        document.addEventListener('touchmove', (e) => {
            currentY = e.touches[0].clientY;
            const diff = currentY - startY;

            if (diff > 50 && window.scrollY === 0) {
                isPulling = true;
                // Add visual feedback for pull to refresh
            }
        });

        document.addEventListener('touchend', () => {
            if (isPulling && currentY - startY > 100) {
                window.location.reload();
            }
            isPulling = false;
        });

        // Notification badge functionality
        function updateNotificationBadge() {
            fetch('/mobile/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error updating notification badge:', error));
        }



        // Mobile optimizations
        document.addEventListener('DOMContentLoaded', () => {
            // Prevent zoom on input focus
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });

            // Add loading states to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.form || this.getAttribute('href') === '#') {
                        this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Loading...';
                        this.disabled = true;
                    }
                });
            });

            // Update notification badge on page load
            updateNotificationBadge();

            // Update badge every 30 seconds
            setInterval(updateNotificationBadge, 30000);
        });
    </script>

    @yield('script')
</body>

</html>
