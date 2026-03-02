@extends('layouts.master-without-nav')

@section('title')
    Lupa Password - Nuist Mobile
@endsection

@section('css')
    @section('css')
        <style>
            /* Mobile login design - match supplied mockup; full-bleed (no margins) */
            html, body { height: 100%; margin: 0; padding: 0; }
            .mobile-auth-bg {
                height: 100vh;
                width: 100vw;
                display:block;
                background: #f2f6fb;
                padding: 0;
                margin: 0;
            }

            /* Decorative auth background shapes (top & bottom) */
            .auth-background {
                min-height: 100vh;
                background: #f5f5f5;
                position: relative;
                overflow: hidden;
            }

            /* Shape atas */
            .auth-background::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 420px;
                background: #004b4c;
                border-bottom-left-radius: 200px;
                border-bottom-right-radius: 200px;
                z-index: 0;
            }

            /* Shape bawah */
            .auth-background::after {
                content: "";
                position: absolute;
                bottom: -80px;
                left: 0;
                width: 100%;
                height: 220px;
                background: #004b4c;
                border-top-left-radius: 200px;
                border-top-right-radius: 200px;
                z-index: 0;
            }

            /* Optional smoother clip-path variant (uncomment to use)
            .auth-background::before {
                content: "";
                position: absolute;
                top: 0;
                width: 100%;
                height: 300px;
                background: linear-gradient(135deg, #0e8549, #006b3f);
                clip-path: ellipse(100% 80% at 50% 0%);
            }
            */

            /* full-bleed container (no centered wrapper) */
            .mobile-screen {
                width: 100vw;
                max-width: 100vw;
                min-height: 100vh; /* allow content to grow without locking exact viewport height */
                border-radius: 0;
                overflow: hidden;
                background: transparent;
                box-shadow: none;
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
            }

            /* Hero (transparent so auth-background shapes show) - use flex layout so children push each other
               and allow illustration to center between title and login button */
            .header-hero{
                background: transparent; /* let auth-background shapes be visible */
                /* top padding gives space for logo/title, bottom padding reserves area for fixed login button */
                padding: 120px 16px 140px 16px;
                color: #fff;
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
                min-height: calc(100vh - 96px); /* reserve space so hero content sits above fixed button */
            }

            /* hero content (title/logo) stays at the top of the hero */
            .hero-content{ width:100%; display:flex; justify-content:center; flex:0 0 auto; }

            /* make illustration take available space and center vertically between title and bottom */
            .hero-illustration{ flex: 1 1 auto; display:flex; align-items:center; justify-content:center; margin-top: -20% }
            .hero-illustration img{ max-width:100%; max-height:100%; height:auto; display:block }

            .hero-title{
                text-align:center;
                font-size:14px;
                margin: 50px 0 8px 0;
                font-weight:300;
            }

            /* .hero-illustration{ display:flex; justify-content:center}
            .hero-illustration img{ width:100%; max-width:260px; height:auto; display:block } */

        /* logo pill top-right */
        .logo-pill{
            position: absolute;
            top: 14px;
            left: 50%;
            transform: translateX(-50%);
            background:#ffffff;
            padding:8px 16px;
            border-radius:12px;
            box-shadow:0 6px 18px rgba(14,42,120,0.06);
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }
        .logo-pill img{ display:block; width:60px; height:auto }

            /* White form area overlapping hero */

            .form-card{
                background: #fff;
                border-top-left-radius: 22px;
                border-top-right-radius: 22px;
                margin-top: -18px;
                padding: 22px 16px 18px 16px;
                box-shadow: 0 -6px 18px #0e8549;
                flex: 1 1 auto;
                overflow: auto;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .form-top-spacer{ height: 6px }

            /* tidy input rows */
            .input-wrap{ display:block; width:100%; }
            .input-wrap + .input-wrap{ margin-top: 12px }

            .pill-input{
                width: 100%;
                border-radius: 28px;
                padding: 14px 18px;
                border: none;
                background: #f3f7ff;
                font-size: 15px;
                color: #0e8549;
                outline: none;
                transition: box-shadow .15s ease, transform .08s ease;
            }
            .pill-input::placeholder{ color: #bee8d3 }
            .pill-input:focus{
                box-shadow: 0 6px 18px rgba(16,88,236,0.12);
                transform: translateY(-1px);
            }

            .pill-password{ position: relative }
            .eye-btn{
                position:absolute; right:12px; top:50%; transform:translateY(-50%);
                background:transparent; border:none; font-size:16px; cursor:pointer; color:#0e8549; padding:6px;
            }

            .btn-row{ margin-top:8px }
            .btn-primary-pill{
                display:block; width:100%; background:#004b4c; color:#fff; border:none;
                padding:12px 18px; border-radius:28px; font-weight:600; font-size:15px; box-shadow:0 8px 18px rgba(13,110,253,0.12);
            }

            .socials{ display:flex; gap:12px; justify-content:center; margin-top:14px }
            /* make social pills smaller and consistent */
            .social-pill{ display:inline-flex; align-items:center; justify-content:center; height:40px; min-width:110px; gap:8px;
                border-radius:22px; border:1px solid rgba(13,110,253,0.08); padding:6px 10px; text-decoration:none; color:#23374a; background:#fff }
            .social-pill img{ width:18px; height:18px }

            .forgot{ text-align:center; margin-top:12px; color:#6c757d }

        .bottom-handle{ width:60px; height:6px; background:#e6eefb; border-radius:4px; position:absolute; left:50%; transform:translateX(-50%); bottom:10px }

            @media (max-width:380px){
                .mobile-screen{ max-width: 100vw; width: 100vw }
                .pill-input{ padding:12px 14px }
                .btn-primary-pill{ padding:10px 14px }
                .header-hero{ padding:18px 12px 12px 12px }
            }

        /* Collapsed / open states for sliding form */
            .collapsed-area{ display:flex; justify-content:center; padding:6px 0 }
            .form-fields{ max-height:0; opacity:0; transform:translateY(12px); overflow:hidden; transition:all .45s cubic-bezier(.2,.9,.2,1); }
            .mobile-screen.open .form-fields{ max-height:800px; opacity:1; transform:translateY(0); }
            .mobile-screen.open .collapsed-area{ display:none }

        /* Hero login button: fixed to viewport bottom, safe-area aware; hidden when drawer opens */
            .hero-login-white{
                position: fixed;
                left: 50%;
                transform: translateX(-50%);
                bottom: max(env(safe-area-inset-bottom), 16px);
                background: #fff;
                color: #004b4c;
                border-radius: 999px;
                padding: 12px 24px;
                font-weight: 700;
                border: none;
                box-shadow: 0 8px 20px rgba(9,30,66,0.08);
                cursor: pointer;
                min-width: 250px;
                max-width: 360px;
                z-index: 1200; /* above drawer */
                display: inline-block;
            }

            /* hide fixed button when the screen is open (drawer visible) to avoid overlap */
            .mobile-screen.open .hero-login-white{ display: none }

        /* Drawer/backdrop for mobile form */
        .drawer-backdrop{ position:fixed; inset:0; background:rgba(3,9,23,0.45); opacity:0; pointer-events:none; transition:opacity .28s ease; z-index:1000 }
        .drawer-backdrop.visible{ opacity:1; pointer-events:auto }

        .form-card.drawer{ position:fixed; left:0; right:0; bottom:0; margin:0; border-top-left-radius:18px; border-top-right-radius:18px; transform:translateY(110%); transition:transform .45s cubic-bezier(.2,.9,.2,1); z-index:1100; max-height:86vh; overflow:auto; box-shadow: 0 -18px 30px rgba(9,30,66,0.12) }
        .mobile-screen.open .form-card.drawer{ transform:translateY(0) }

        .drawer-handle{ width:64px; height:6px; background:#e6eefb; border-radius:6px; margin:8px auto 10px auto }

        /* Fast menu removed — layout simplified (menu HTML removed) */

        /* Login action row: large green pill + small circular icon */
        .login-actions{ display:flex; gap:10px; align-items:center; margin-top:6px }
        .btn-primary-pill{ flex:1; display:inline-flex; align-items:center; justify-content:center }
        .btn-icon{ width:46px; height:46px; border-radius:50%; background:linear-gradient(180deg,#fff 0,#e6f5ea 100%); display:inline-flex; align-items:center; justify-content:center; box-shadow:0 6px 18px rgba(14,133,73,0.08); border:none }
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

            <div class="form-card">
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
