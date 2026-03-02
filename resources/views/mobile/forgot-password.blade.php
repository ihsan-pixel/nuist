@extends('layouts.master-without-nav')

@section('title')
    Lupa Password - Nuist Mobile
@endsection

@section('css')
    <style>
        /* Reuse mobile login styles (trimmed) to keep visual parity */
        html, body { height: 100%; margin: 0; padding: 0; }
        .mobile-auth-bg { height: 100vh; width: 100vw; background: #f2f6fb; }
        .auth-background { min-height: 100vh; background: #f5f5f5; position: relative; overflow: hidden; }
        .auth-background::before{ content: ""; position:absolute; top:0; left:0; width:100%; height:420px; background:#004b4c; border-bottom-left-radius:200px; border-bottom-right-radius:200px; z-index:0 }
        .auth-background::after{ content: ""; position:absolute; bottom:-80px; left:0; width:100%; height:220px; background:#004b4c; border-top-left-radius:200px; border-top-right-radius:200px; z-index:0 }

        .mobile-screen{ width:100vw; max-width:100vw; min-height:100vh; display:flex; flex-direction:column; z-index:1 }
        .header-hero{ background:transparent; padding:110px 16px 20px 16px; color:#fff; display:flex; flex-direction:column; align-items:center; gap:18px }
        .logo-pill{ position: absolute; top: 14px; left: 50%; transform: translateX(-50%); background:#ffffff; padding:8px 16px; border-radius:12px; box-shadow:0 6px 18px rgba(14,42,120,0.06); display:inline-flex }
        .logo-pill img{ width:60px }
        .hero-title{ text-align:center; font-size:16px; margin: 28px 0 8px 0; font-weight:400; color:#fff }

        .form-card{ background:#fff; border-top-left-radius:22px; border-top-right-radius:22px; margin-top:-18px; padding:18px 16px; box-shadow: 0 -6px 18px #0e8549; display:flex; flex-direction:column; gap:12px }
        .pill-input{ width:100%; border-radius:28px; padding:14px 18px; border:none; background:#f3f7ff; font-size:15px; color:#0e8549 }
        .btn-primary-pill{ display:block; width:100%; background:#004b4c; color:#fff; border:none; padding:12px 18px; border-radius:28px; font-weight:600 }
        .muted-center{ text-align:center; color:#6c757d; margin-top:6px }

        @media (max-width:380px){ .pill-input{ padding:12px 14px } .btn-primary-pill{ padding:10px 14px } .header-hero{ padding:18px 12px 12px 12px } }
    </style>
@endsection

@section('body')
    <body class="auth-body-bg">
@endsection

@section('content')

    <div class="auth-background">
        <div class="mobile-screen" role="main">
            <div class="header-hero">
                <div class="logo-pill" aria-hidden="true">
                    <img src="{{ asset('images/logo1.png') }}" alt="logo">
                </div>
                <h4 class="hero-title">Lupa Kata Sandi</h4>
                <p style="color:rgba(255,255,255,0.9); text-align:center; max-width:320px; font-size:13px">Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mereset kata sandi.</p>
            </div>

            <div class="form-card" style="margin-bottom:24px">
                @if(session('status'))
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('mobile.password.email') }}">
                    @csrf

                    <div class="input-wrap">
                        <input id="email" name="email" type="email" class="pill-input" placeholder="contoh@gmail.com" required value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-row">
                        <button type="submit" class="btn-primary-pill">Kirim tautan reset</button>
                    </div>

                    <div class="muted-center">
                        <a href="{{ route('mobile.login') }}">Kembali ke Login</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
