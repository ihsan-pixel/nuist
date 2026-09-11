@extends('layouts.mobile')
@section('content')
<style>
.bpp-shell{max-width:560px;margin:auto;padding:0 12px 24px;color:#183d32;font-family:Poppins,sans-serif}.bpp-header{position:sticky;top:0;z-index:20;background:#f6f8f7;padding:10px 0 14px}.bpp-banner{background:linear-gradient(135deg,#043f31,#095341);color:white;border-radius:14px;padding:18px}.bpp-banner h1{font-size:20px;margin:5px 0}.bpp-shell .card{border:1px solid #e7eeea;border-radius:14px;margin-bottom:14px;box-shadow:0 4px 12px #043f3108}.bpp-shell .btn-primary{background:#043f31;border-color:#043f31}.bpp-shell h2{font-size:17px}.bpp-shell h3{font-size:16px}.bpp-shell p,.bpp-shell dd{overflow-wrap:anywhere}.bpp-meta{font-size:13px;color:#5e7168}.bpp-detail{white-space:pre-wrap}.bpp-shell dt{margin-top:10px;font-size:12px;color:#5e7168}.bpp-shell dd{font-size:14px;margin-bottom:4px}.bpp-shell .badge{white-space:normal}.bpp-shell video{width:100%;border-radius:12px;max-height:340px;background:#10271f}.bpp-shell .form-label{font-size:13px}.bpp-shell .pagination{flex-wrap:wrap}
</style>
<div class="bpp-shell">
<header class="bpp-header"><div class="bpp-banner"><small>NUIST · Pengurus BPPPMNU</small><h1>@yield('title')</h1><span>{{ auth()->user()->name }}</span></div></header>
@if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
@yield('bpp-content')
</div>
@endsection
