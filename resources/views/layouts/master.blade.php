<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Nuist - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</title>
    <base href="{{ url('/') }}/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Digital LP. Ma'arif NU PWNU DIY - Platform terintegrasi untuk manajemen madrasah, presensi, dan data pendidikan." />
    <meta name="keywords" content="nuist, ma'arif, nu, pwnu diy, sistem informasi, madrasah, presensi, pendidikan" />
    <meta name="author" content="LP. Ma'arif NU PWNU DIY" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title') | Nuist - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta property="og:description" content="Sistem Informasi Digital LP. Ma'arif NU PWNU DIY - Platform terintegrasi untuk manajemen madrasah, presensi, dan data pendidikan." />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('build/images/logo-light.png') }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.head-css')
    @yield('css')
    @if(request()->is('admin/data-madrasah','admin/teaching-progress','madrasah/profile','teaching-schedules*','admin/monitor-jurnal-mengajar','presensi-admin*','admin/presensi_admin*'))
    <style>
        body.monitoring-theme{background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body.monitoring-theme .main-content{background:transparent!important}.monitoring-theme .card{border:1px solid rgba(255,255,255,.86);border-radius:20px;background:rgba(255,255,255,.7);backdrop-filter:blur(16px);box-shadow:0 10px 30px rgba(32,70,78,.08);overflow:hidden}.monitoring-theme .card-header{border-bottom:1px solid rgba(220,231,233,.75);background:linear-gradient(135deg,rgba(246,250,249,.86),rgba(255,255,255,.62));color:#192d37}.monitoring-theme .card-header.bg-primary,.monitoring-theme .card-header.bg-secondary{background:linear-gradient(135deg,rgba(0,91,88,.96),rgba(8,116,90,.9))!important;color:#fff}.monitoring-theme .table-responsive{border-radius:16px;overflow-x:auto}.monitoring-theme table thead th{background:#f1f6f6;color:#192d37;border-bottom:1px solid #e5edef}.monitoring-theme table tbody td{border-color:#edf2f3;color:#465a64;white-space:normal;overflow-wrap:anywhere}.monitoring-theme .btn{border-radius:10px;transition:transform .18s ease,box-shadow .18s ease}.monitoring-theme .btn:hover{transform:translateY(-2px);box-shadow:0 8px 16px rgba(32,70,78,.14)}.monitoring-theme .btn-primary,.monitoring-theme .btn-success{background:linear-gradient(90deg,#0f766e,#14b8a6);border:0}.monitoring-theme .btn-warning{background:linear-gradient(90deg,#d97706,#fbbf24);border:0;color:#fff}.monitoring-theme .btn-danger{background:linear-gradient(90deg,#dc2626,#fb7185);border:0;color:#fff}.monitoring-theme .badge{border-radius:999px}.monitoring-theme .modal-content{border:1px solid rgba(255,255,255,.9);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}.monitoring-theme .modal-header{background:#f6faf9;border-bottom:1px solid #e5edef}.monitoring-theme .form-control,.monitoring-theme .form-select{border:1px solid #dce7e9;border-radius:9px}.monitoring-theme .progress{background:#e8f2ef;border-radius:999px}.monitoring-theme .progress-bar{background:linear-gradient(90deg,#0f766e,#14b8a6)}.monitoring-theme .alert{border-radius:14px;border:1px solid #e5edef;background:rgba(255,255,255,.72)}.monitoring-theme .pagination .page-link{border:0;border-radius:8px;color:#08745a;margin-left:4px}.monitoring-theme .pagination .active .page-link{background:#dff3eb;color:#08745a}.monitoring-theme .main-content>.page-content>.container-fluid{position:relative}.monitoring-theme .main-content>.page-content>.container-fluid:before,.monitoring-theme .main-content>.page-content>.container-fluid:after{content:"";position:fixed;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:monitoring-float 12s ease-in-out infinite}.monitoring-theme .main-content>.page-content>.container-fluid:before{width:280px;height:280px;right:-90px;top:180px;background:rgba(42,190,153,.12)}.monitoring-theme .main-content>.page-content>.container-fluid:after{width:230px;height:230px;left:180px;bottom:-100px;background:rgba(98,158,255,.1);animation-direction:reverse}@keyframes monitoring-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(-12px,18px,0)}}@media(prefers-reduced-motion:reduce){.monitoring-theme .main-content>.page-content>.container-fluid:before,.monitoring-theme .main-content>.page-content>.container-fluid:after{animation:none}}
    </style>
    @endif
</head>

<body class="{{ request()->is('admin/data-madrasah','admin/teaching-progress','madrasah/profile','teaching-schedules*','admin/monitor-jurnal-mengajar','presensi-admin*','admin/presensi_admin*') ? 'monitoring-theme' : '' }}" data-topbar="light" data-layout-mode="light">

    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    @include('layouts.right-sidebar')

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    <!-- Script khusus halaman -->
    @stack('scripts')

</body>

</html>
