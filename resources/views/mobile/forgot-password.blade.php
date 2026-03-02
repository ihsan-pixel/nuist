@extends('layouts.master-without-nav')

@section('title')
    Lupa Password - Nuist Mobile
@endsection

@section('css')
    <style>
        /* Reuse mobile login styles (trimmed) to keep visual parity but fix stacking and polish UI */
        html, body { height: 100%; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .mobile-auth-bg { height: 100vh; width: 100vw; background: #f2f6fb; }
        .auth-background { min-height: 100vh; background: #f5f5f5; position: relative; overflow: visible; }

        /* Decorative shapes remain behind everything (z-index:0) */
        .auth-background::before{ content: ""; position:absolute; top:0; left:0; width:100%; height:420px; background:#004b4c; border-bottom-left-radius:200px; border-bottom-right-radius:200px; z-index:0 }
        .auth-background::after{ content: ""; position:absolute; bottom:-80px; left:0; width:100%; height:220px; background:#004b4c; border-top-left-radius:200px; border-top-right-radius:200px; z-index:0 }

        /* Ensure mobile-screen creates a new stacking context above pseudo-elements */
        .mobile-screen{ width:100vw; max-width:100vw; min-height:100vh; display:flex; flex-direction:column; position:relative; z-index:1 }

        /* Header hero sits visually above background shapes */
        .header-hero{ background:transparent; padding:110px 18px 28px 18px; color:#fff; display:flex; flex-direction:column; align-items:center; gap:12px; position:relative; z-index:2 }
        .logo-pill{ position: absolute; top: 16px; left: 50%; transform: translateX(-50%); background:#ffffff; padding:8px 16px; border-radius:12px; box-shadow:0 6px 18px rgba(14,42,120,0.06); display:inline-flex; z-index:3 }
        .logo-pill img{ width:56px; height:auto }
        .hero-title{ text-align:center; font-size:18px; margin: 50px 0 4px 0; font-weight:700; color:#fff }
        .hero-sub{ color: rgba(255,255,255,0.9); text-align:center; max-width:320px; font-size:13px }

        /* White form card overlapping hero: make sure it is frontmost */
        .form-card{ background:#fff; border-top-left-radius:18px; border-top-right-radius:18px; border-bottom-left-radius: 18px; border-bottom-right-radius: 18px; margin-top: 0px; padding:20px 16px; box-shadow: 0 10px 30px rgba(8,40,80,0.12); display:flex; flex-direction:column; gap:12px; position:relative; z-index:4 }

        .pill-input{ width:100%; border-radius:28px; padding:14px 18px; border:none; background:#f6f9ff; font-size:15px; color:#0e8549; box-shadow: inset 0 1px 0 rgba(255,255,255,0.6) }
        .pill-input::placeholder{ color: rgba(14,133,73,0.35) }

        .btn-primary-pill{ display:block; width:100%; background: linear-gradient(180deg,#006b67,#004b4c); color:#fff; border:none; padding:12px 18px; border-radius:28px; font-weight:700; box-shadow:0 8px 22px rgba(2,70,64,0.12); transition: transform .12s ease, box-shadow .12s ease }
        .btn-primary-pill:hover{ transform: translateY(-2px); box-shadow:0 12px 30px rgba(2,70,64,0.16) }

        .muted-center{ text-align:center; color:#6c757d; margin-top:8px; font-size:13px }

        .alert-success{ background: #e6f7ef; color:#044d35; padding:10px 12px; border-radius:10px; border:1px solid rgba(4,77,53,0.06) }

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
