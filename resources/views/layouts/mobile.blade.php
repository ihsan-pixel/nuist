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
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('build/images/logo favicon 1.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('build/images/logo favicon 1.png') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('build/images/logo favicon 1.png') }}">

    <!-- Open Graph for better social sharing -->
    <meta property="og:title" content="@yield('title') | NUIST Mobile" />
    <meta property="og:description" content="NUIST Mobile - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('build/images/logo favicon 1.png') }}" />

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
            <img src="{{ asset('build/images/logo favicon 1.png') }}" alt="NUIST" width="40" height="40" class="me-3 rounded">
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
                    <img src="{{ asset('build/images/logo favicon 1.png') }}" alt="NUIST" height="32" class="me-2">
                    <div>
                        <h6 class="mb-0 fw-bold">NUIST Mobile</h6>
                        <small class="text-muted">@yield('subtitle', 'Sistem Informasi Digital')</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                            <div class="avatar-sm">
                                <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                                     alt="" class="img-thumbnail rounded-circle">
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
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
    <main class="mobile-content">
        <div class="container-fluid px-3 py-3">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="bg-white rounded-full shadow-md flex justify-between items-center px-4 py-2 mb-6 w-[90%] max-w-md fixed bottom-0 left-1/2 transform -translate-x-1/2 d-md-none" style="z-index: 1030;">
        <!-- Beranda -->
        <a href="{{ route('mobile.dashboard') }}" class="flex items-center gap-1 {{ request()->routeIs('mobile.dashboard') ? 'bg-gradient-to-r from-green-600 to-green-500 text-white px-3 py-2 rounded-full' : 'text-gray-400 hover:text-green-500 transition' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9.75l8.485-6.364a1 1 0 011.03 0L21 9.75M4.5 10.5V20a1 1 0 001 1h13a1 1 0 001-1v-9.5" />
            </svg>
            <span class="text-sm font-medium">Beranda</span>
        </a>

        <!-- Presensi -->
        <a href="{{ route('mobile.presensi') }}" class="flex items-center gap-1 {{ request()->routeIs('mobile.presensi*') ? 'bg-gradient-to-r from-green-600 to-green-500 text-white px-3 py-2 rounded-full' : 'text-gray-400 hover:text-green-500 transition' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 10.5a6.15 6.15 0 11-12.3 0 6.15 6.15 0 0112.3 0z" />
            </svg>
            <span class="text-sm font-medium">Presensi</span>
        </a>

        <!-- Jadwal -->
        <a href="{{ route('mobile.jadwal') }}" class="flex items-center gap-1 {{ request()->routeIs('mobile.jadwal*') ? 'bg-gradient-to-r from-green-600 to-green-500 text-white px-3 py-2 rounded-full' : 'text-gray-400 hover:text-green-500 transition' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h9a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z" />
            </svg>
            <span class="text-sm font-medium">Jadwal</span>
        </a>

        <!-- Mengajar -->
        <a href="{{ route('mobile.teaching-attendances') }}" class="flex items-center gap-1 {{ request()->routeIs('mobile.teaching-attendances*') ? 'bg-gradient-to-r from-green-600 to-green-500 text-white px-3 py-2 rounded-full' : 'text-gray-400 hover:text-green-500 transition' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v14l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
            </svg>
            <span class="text-sm font-medium">Mengajar</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('mobile.profile') }}" class="flex items-center gap-1 {{ request()->routeIs('mobile.profile') ? 'bg-gradient-to-r from-green-600 to-green-500 text-white px-3 py-2 rounded-full' : 'text-gray-400 hover:text-green-500 transition' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9a3 3 0 110 6 3 3 0 010-6zM19.4 15a1.5 1.5 0 010 2.12l-1.41 1.41a1.5 1.5 0 01-2.12 0l-.9-.9a6.97 6.97 0 01-2.59.48 6.97 6.97 0 01-2.59-.48l-.9.9a1.5 1.5 0 01-2.12 0L4.6 17.12a1.5 1.5 0 010-2.12l.9-.9A6.97 6.97 0 015 12c0-.9.17-1.77.48-2.59l-.9-.9a1.5 1.5 0 010-2.12L5.1 4.6a1.5 1.5 0 012.12 0l.9.9A6.97 6.97 0 0111 5c.9 0 1.77.17 2.59.48l.9-.9a1.5 1.5 0 012.12 0l1.41 1.41a1.5 1.5 0 010 2.12l-.9.9c.31.82.48 1.69.48 2.59 0 .9-.17 1.77-.48 2.59l.9.9z" />
            </svg>
            <span class="text-sm font-medium">Profil</span>
        </a>
    </nav>

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

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

        // Service worker registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
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
        });
    </script>

    @yield('script')
</body>

</html>
