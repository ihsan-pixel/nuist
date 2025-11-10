<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | NUIST Mobile</title>
    <base href="{{ url('/') }}/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="#556ee6">
    <meta name="mobile-web-app-capable" content="yes">

    <link rel="shortcut icon" href="{{ asset('build/images/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('build/images/logo-light.png') }}">

    @include('layouts.head-css')

    <style>
        body {
            font-size: 14px;
            line-height: 1.4;
        }

        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 12px;
            z-index: 1030;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
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

        .mobile-content {
            padding-bottom: 90px;
        }
    </style>
</head>

<body data-layout-mode="light" class="mobile-layout">
    <!-- Header -->
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
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('build/images/users/avatar-11.jpg') }}" class="rounded-circle" width="40" height="40" alt="User">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('mobile.dashboard') }}"><i class="bx bx-home me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out me-2"></i>Logout</a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
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
    <nav class="mobile-nav d-md-none">
        <div class="container-fluid">
            <div class="row g-0">
                <div class="col">
                    <a href="{{ route('mobile.dashboard') }}" class="nav-link {{ request()->routeIs('mobile.dashboard') ? 'active' : '' }}">
                        <i class="bx bx-home"></i><span>Home</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('mobile.activity') }}" class="nav-link {{ request()->routeIs('mobile.activity') ? 'active' : '' }}">
                        <i class="bx bx-run"></i><span>Activity</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('mobile.profile') }}" class="nav-link {{ request()->routeIs('mobile.profile') ? 'active' : '' }}">
                        <i class="bx bx-user"></i><span>Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @include('layouts.vendor-scripts')
    @yield('script')
</body>
</html>
