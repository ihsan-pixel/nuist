
<!-- Bootstrap Css -->
<link href="{{ asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="preload" href="{{ asset('build/icons/boxicons.woff2') }}" as="font" type="font/woff2" crossorigin>
<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- App Css-->
<link href="{{ asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

<style id="nuist-global-glass-sidebar">
    .vertical-menu{background:rgba(255,255,255,.68)!important;border-right:1px solid rgba(255,255,255,.78);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);box-shadow:8px 0 30px rgba(35,76,83,.06)}
    .vertical-menu #sidebar-menu>ul>li>a{margin:3px 12px;border-radius:16px;transition:background .22s ease,color .22s ease,transform .22s ease,box-shadow .22s ease}
    .vertical-menu #sidebar-menu>ul>li>a:hover{background:rgba(7,116,90,.08);transform:translateX(3px)}
    .vertical-menu #sidebar-menu>ul>li.mm-active>a,.vertical-menu #sidebar-menu>ul>li>a.active{background:rgba(7,116,90,.13);color:#075f4a!important;box-shadow:0 8px 18px rgba(7,116,90,.08)}
    .vertical-menu .menu-title{color:#6c7c86}
    .vertical-menu #sidebar-menu>ul>li>a{color:#526074!important;font-weight:600;letter-spacing:.01em;padding:11px 14px!important}
    .vertical-menu #sidebar-menu>ul>li>a i{color:#08745a!important;font-size:1.15rem;transition:transform .22s ease,color .22s ease,filter .22s ease}
    .vertical-menu #sidebar-menu>ul>li>a:hover i{transform:scale(1.12);filter:drop-shadow(0 3px 5px rgba(8,116,90,.18))}
    .vertical-menu #sidebar-menu>ul>li.mm-active>a,.vertical-menu #sidebar-menu>ul>li>a.active{background:linear-gradient(135deg,rgba(224,246,238,.9),rgba(205,237,225,.68));color:#075f4a!important;border:1px solid rgba(255,255,255,.82);box-shadow:0 10px 22px rgba(8,116,90,.1),inset 0 1px 0 rgba(255,255,255,.9)}
    .vertical-menu #sidebar-menu .sub-menu{padding:5px 10px 7px 28px}
    .vertical-menu #sidebar-menu .sub-menu li a{border-radius:10px;color:#697487;padding:8px 12px;margin:2px 0;transition:background .2s ease,color .2s ease,transform .2s ease}
    .vertical-menu #sidebar-menu .sub-menu li a:hover{background:rgba(8,116,90,.07);color:#08745a;transform:translateX(3px)}
    .vertical-menu #sidebar-menu .sub-menu li.mm-active>a,.vertical-menu #sidebar-menu .sub-menu li a.active{background:rgba(8,116,90,.1);color:#08745a!important;font-weight:700}
    .vertical-menu #sidebar-menu .has-arrow:after{color:#687486;transition:transform .2s ease,color .2s ease}.vertical-menu #sidebar-menu .mm-active>.has-arrow:after{color:#08745a}
    .vertical-menu .menu-title{font-size:10px;font-weight:800;letter-spacing:.09em;padding:18px 16px 8px}
    body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li>a{margin:3px 6px;padding:15px 0!important;min-height:55px;text-align:center;border-radius:16px}
    body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li>a i{margin:0!important;min-width:0;font-size:1.45rem}
    body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li.mm-active>a,body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li>a.active{width:auto;background:rgba(7,116,90,.13)}
</style>
<!-- App js -->
<script src="{{ asset('build/js/plugin.js') }}"></script>

<!-- Laporan Akhir Tahun Create Styles -->
<link href="{{ asset('css/mobile/laporan-akhir-tahun-create.css') }}" rel="stylesheet" type="text/css" />

@stack('css')
