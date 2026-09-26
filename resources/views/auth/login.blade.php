@extends('layouts.master-without-nav')
@section('title', 'Masuk - Sistem Informasi Digital LP. Ma\'arif NU PWNU DIY')
@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
@include('mobile._auth-styles')
<style>
@media(min-width:768px){.desktop-auth .login-shell{padding:40px 24px;gap:18px}.desktop-auth .auth-header,.desktop-auth .welcome-card{max-width:440px}.desktop-auth .welcome-card{border-radius:30px}.desktop-auth .card-body{padding:24px 28px 28px}.desktop-auth .welcome-title{font-size:1.55rem}.desktop-auth .welcome-subtitle{max-width:38ch;font-size:.86rem}}
.desktop-auth .secondary-links{margin-top:18px;display:grid;gap:8px;text-align:center}.desktop-auth .secondary-links a{color:var(--accent-main);font-size:.72rem;font-weight:600;text-decoration:none}.desktop-auth .secondary-links a:hover{text-decoration:underline}
</style>
@endsection
@section('content')
@php
$isSpmbHost=request()->getHost()==='spmb.nuist.id';
$loginAction=$isSpmbHost?url('/login'):route('login');
$forgotPasswordUrl=$isSpmbHost?url('/password/reset'):route('mobile.password.request');
$backUrl=$isSpmbHost?url('/'):route('landing');
@endphp
@include('mobile._auth-loader')
<div class="mobile-auth-page desktop-auth"><div class="login-shell">
<div class="login-backdrop" aria-hidden="true"><span class="backdrop-orb backdrop-orb-left"></span><span class="backdrop-orb backdrop-orb-right"></span></div>
<header class="auth-header"><div class="brand-card brand-card-inline"><img src="{{ asset('images/logo1.png') }}" alt="NUIST"></div><h1 class="welcome-title">Selamat Datang Kembali</h1><p class="welcome-subtitle">{{ $isSpmbHost?'Masuk sebagai Admin Sekolah untuk mengelola dashboard SPMB.':'Masuk untuk mengakses seluruh layanan NUIST.' }}</p></header>
<main class="welcome-card {{ $errors->any()?'is-open':'' }}"><div class="card-body">
@if(session('status'))<div class="status-stack"><div class="status-alert success">{{ session('status') }}</div></div>@endif
@if(session('error'))<div class="status-stack"><div class="status-alert error">{{ session('error') }}</div></div>@endif
<div class="login-panel"><form method="POST" action="{{ $loginAction }}" class="login-form">@csrf
<div class="auth-field-group"><label class="input-label" for="email">Email</label><div class="field-shell"><span class="field-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 13.5a5.5 5.5 0 1 0-5.5-5.5 5.5 5.5 0 0 0 5.5 5.5Zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5Z"/></svg></span><input id="email" name="email" type="email" class="input-control" value="{{ old('email') }}" placeholder="Masukkan email" autocomplete="email" required autofocus></div>@error('email')<div class="field-error">{{ $message }}</div>@enderror</div>
<div class="auth-field-group"><label class="input-label" for="password">Password</label><div class="field-shell field-shell-password"><span class="field-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-5 8a2 2 0 1 1 2-2 2 2 0 0 1-2 2Zm-2-8V6a2 2 0 0 1 4 0v2Z"/></svg></span><input id="password" name="password" type="password" class="input-control" placeholder="Masukkan password" autocomplete="current-password" required><button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password"><svg viewBox="0 0 24 24"><path d="M12 5c5.33 0 9.73 3.61 11 7-1.27 3.39-5.67 7-11 7S2.27 15.39 1 12c1.27-3.39 5.67-7 11-7Zm0 2C8.08 7 4.72 9.37 3.34 12 4.72 14.63 8.08 17 12 17s7.28-2.37 8.66-5C19.28 9.37 15.92 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z"/></svg></button></div>@error('password')<div class="field-error">{{ $message }}</div>@enderror</div>
<div class="form-actions"><label class="remember-check"><input type="checkbox" name="remember" value="1" {{ old('remember')?'checked':'' }}><span>Ingat saya</span></label><a href="{{ $forgotPasswordUrl }}" class="forgot-link">Lupa Password?</a></div><button class="submit-btn" type="submit">Masuk</button></form>
<div class="secondary-links"><a href="{{ $backUrl }}">{{ $isSpmbHost?'Kembali ke Halaman SPMB':'Kembali ke Halaman Utama' }}</a></div>
</div></div></main><p class="page-version">NUIST Desktop &middot; LP. Ma'arif NU PWNU DIY</p>
</div></div>
@endsection
@section('script')
@include('mobile._auth-loader-script')
<script>document.addEventListener('DOMContentLoaded',function(){const i=document.getElementById('password'),b=document.getElementById('togglePassword');if(i&&b)b.addEventListener('click',function(){i.type=i.type==='password'?'text':'password';this.setAttribute('aria-label',i.type==='password'?'Tampilkan password':'Sembunyikan password')})});</script>
@endsection
