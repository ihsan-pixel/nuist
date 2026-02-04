<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | NUIST Mobile - Pengurus</title>
    <base href="{{ url('/') }}/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="NUIST Mobile - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta name="keywords" content="nuist, ma'arif, nu, pwnu diy, sistem informasi, mobile, pwa" />
    <meta name="author" content="LP. Ma'arif NU PWNU DIY" />
    <meta name="theme-color" content="#004b4c">
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mobile-specific CSS -->
    <style>
        /* Mobile-first responsive design */
        body {
            font-size: 14px;
            line-height: 1.4;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fb;
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
            color: white !important;
        }

        .mobile-nav .nav-link.active i {
            color: white !important;
        }

        .mobile-nav .nav-link.active span {
            color: white !important;
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

        /* Sticky header untuk mobile */
        .mobile-header {
            position: sticky;
            top: 0;
            z-index: 1050;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }

        /* Efek saat scroll */
        .mobile-header.scrolled {
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        /* Custom Bottom Navigation (Floating Center Button) */
        .custom-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #eaeaea;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.08);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .custom-bottom-nav .nav-container {
            display: flex;
            width: 100%;
            justify-content: space-around;
            align-items: center;
            position: relative;
        }

        .custom-bottom-nav .nav-link {
            color: #0e8549;
            text-align: center;
            flex: 1;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .custom-bottom-nav .nav-link i {
            font-size: 20px;
            margin-bottom: 3px;
        }

        .custom-bottom-nav .nav-link.active {
            color: #004b4c;
        }

        /* Tombol tengah melingkar */
        .nav-center-btn {
            position: absolute;
            top: -28px;
            left: 50%;
            transform: translateX(-50%);
            background: transparent;
        }

        .center-action {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #004b4c, #0e8549);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            color: #fff;
            font-size: 26px;
            transition: all 0.3s ease;
        }

        .center-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.25);
        }

        /* Primary color scheme */
        .text-primary-custom {
            color: #004b4c !important;
        }

        .bg-primary-custom {
            background-color: #004b4c !important;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #003838 0%, #0a6b3d 100%);
            color: white;
        }
    </style>
</head>

<body data-layout-mode="light" class="mobile-layout">
    <!-- Offline indicator -->
    <div id="offline-indicator" class="offline-indicator">
        <i class="bx bx-wifi-off me-1"></i>
        Anda sedang offline. Beberapa fitur mungkin tidak tersedia.
    </div>

    <!-- Main Content -->
    <main class="mobile-content">
        <div class="container-fluid px-1 py-3">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Bottom Navigation for Pengurus -->
    <nav class="mobile-nav d-md-none custom-bottom-nav">
        <div class="nav-container">
            <a href="{{ route('mobile.pengurus.dashboard') }}" class="nav-link {{ request()->routeIs('mobile.pengurus.dashboard') ? 'active' : '' }}">
                <i class="bx bx-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('mobile.pengurus.sekolah') }}" class="nav-link {{ request()->routeIs('mobile.pengurus.sekolah') ? 'active' : '' }}">
                <i class="bx bx-building"></i>
                <span>Madrasah</span>
            </a>
            <a>
                <i></i>
                <span style="color: #ffffff !important;">|---------|</span>
            </a>
            <!-- Tombol Tengah -->
            <div class="nav-center-btn">
                <a href="{{ route('tenaga-pendidik.index') }}" class="center-action">
                    <i class="bx bx-user-voice"></i>
                </a>
            </div>

            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="bx bx-desktop"></i>
                <span>Desktop</span>
            </a>
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bx bx-log-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    <!-- ApexCharts -->
    <script src="{{ asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Face Recognition Scripts -->
    <script>
        window.MODEL_PATH = "{{ asset('models') }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="{{ asset('js/face-recognition.js') }}"></script>

    <!-- Mobile-specific scripts -->
    <script>
        // Offline detection
        const offlineIndicator = document.getElementById('offline-indicator');

        window.addEventListener('online', () => {
            offlineIndicator.style.display = 'none';
        });

        window.addEventListener('offline', () => {
            offlineIndicator.style.display = 'block';
        });

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw-v2.js?v=1').then(reg => {
                console.log("SW loaded:", reg.scope);
            }).catch(err => console.error("SW failed:", err));
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

            // Show SweetAlert success message if present
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                const message = successMessage.getAttribute('data-message');
                console.log('Success message found:', message);
                if (message) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#004b4c',
                        customClass: {
                            popup: 'swal-mobile'
                        }
                    });
                }
            }
        });

        // Add shadow effect on scroll
        document.addEventListener('scroll', () => {
            const header = document.querySelector('.mobile-header');
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>

    @yield('script')
</body>

</html>

