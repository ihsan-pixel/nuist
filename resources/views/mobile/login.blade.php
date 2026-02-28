@extends('layouts.master-without-nav')

@section('title')
    Masuk - Nuist Mobile
@endsection

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

        /* full-bleed container (no centered wrapper) */
        .mobile-screen {
            width: 100vw;
            max-width: 100vw;
            height: 100vh;
            max-height: 100vh;
            border-radius: 0;
            overflow: hidden;
            background: transparent;
            box-shadow: none;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Blue hero */
        .header-hero{
            background: linear-gradient(180deg,#2089ff 0%,#0d6efd 100%);
            padding: 22px 16px 14px 16px;
            color: #fff;
            position: relative;
            flex: 0 0 auto;
            height: 60%;
        }

        .hero-title{
            text-align:center;
            font-size:22px;
            margin: 6px 0 4px 0;
            font-weight:700;
        }

        .hero-illustration{ display:flex; justify-content:center; margin-top:8px }
        .hero-illustration img{ width:100%; max-width:260px; height:auto; display:block }

        /* White form area overlapping hero */

        .form-card{
            background: #fff;
            border-top-left-radius: 22px;
            border-top-right-radius: 22px;
            margin-top: -18px;
            padding: 22px 16px 18px 16px;
            box-shadow: 0 -6px 18px rgba(14,42,120,0.03);
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
            color: #0b2b4a;
            outline: none;
            transition: box-shadow .15s ease, transform .08s ease;
        }
        .pill-input::placeholder{ color: #94a6d6 }
        .pill-input:focus{
            box-shadow: 0 6px 18px rgba(16,88,236,0.12);
            transform: translateY(-1px);
        }

        .pill-password{ position: relative }
        .eye-btn{
            position:absolute; right:12px; top:50%; transform:translateY(-50%);
            background:transparent; border:none; font-size:16px; cursor:pointer; color:#90a6d8; padding:6px;
        }

        .btn-row{ margin-top:8px }
        .btn-primary-pill{
            display:block; width:100%; background:linear-gradient(90deg,#1e88ff,#0d6efd); color:#fff; border:none;
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
    </style>
@endsection

@section('body')
    <body class="auth-body-bg">
@endsection

@section('content')

    <div class="mobile-auth-bg">
        <div class="mobile-screen" role="main">
            <!-- Blue header with illustration (full-bleed) -->
                <div class="header-hero">
                    <div class="hero-content">
                        <h2 class="hero-title">Hello!</h2>
                    </div>
                    <div class="hero-illustration">
                        <img src="{{ asset('images/verification-img.png') }}" alt="illustration"/>
                    </div>
                </div>

                <!-- White form card overlapping header (rounded) -->
                {{-- <div class="bottom-handle" aria-hidden="true"></div> --}}
                <div class="form-card" id="formCard">
                    <div class="form-top-spacer"></div>

                    <!-- Collapsed area: shows a single login button initially -->
                    <div class="collapsed-area" id="collapsedArea">
                        <button type="button" class="btn-primary-pill open-login-btn" id="openLogin">Masuk</button>
                    </div>

                    <!-- Form fields: hidden initially, slide up when open -->
                    <div class="form-fields" id="formFields" aria-hidden="true">
                        <form method="POST" action="{{ route('mobile.login.authenticate') }}">
                            @csrf

                            <div class="input-wrap">
                                <input id="email" name="email" type="email" class="pill-input" placeholder="nama@contoh.com" required value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-wrap">
                                <div class="pill-password">
                                    <input id="password" name="password" type="password" class="pill-input" placeholder="Kata sandi" required>
                                    <button type="button" id="togglePassword" class="eye-btn" title="Tampilkan kata sandi">&#128065;</button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="btn-row">
                                <button class="btn-primary-pill" type="submit">Masuk</button>
                            </div>

                            <div class="socials">
                                <a class="social-pill" href="#" title="Google"><img src="https://www.svgrepo.com/show/355037/google.svg" alt="G"></a>
                                <a class="social-pill" href="#" title="Facebook"><img src="https://www.svgrepo.com/show/303145/facebook.svg" alt="F"></a>
                            </div>

                            <div class="forgot"><a href="{{ route('password.request') }}">Lupa kata sandi?</a></div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            // password toggle (works after form is revealed too)
            function setupPasswordToggle(){
                var toggle = document.getElementById('togglePassword');
                var pwd = document.getElementById('password');
                if(toggle && pwd){
                    toggle.addEventListener('click', function(){
                        if(pwd.type === 'password') pwd.type = 'text'; else pwd.type = 'password';
                    });
                }
            }

            setupPasswordToggle();

            // open login form: slide up from bottom
            var openBtn = document.getElementById('openLogin');
            var mobileScreen = document.querySelector('.mobile-screen');
            var formFields = document.getElementById('formFields');
            var collapsedArea = document.getElementById('collapsedArea');

            if(openBtn && mobileScreen && formFields){
                openBtn.addEventListener('click', function(){
                    // add open class to enable CSS transition
                    mobileScreen.classList.add('open');
                    formFields.setAttribute('aria-hidden','false');
                    // focus the email input after a short delay to allow animation
                    setTimeout(function(){
                        var email = document.getElementById('email');
                        if(email) email.focus();
                        setupPasswordToggle();
                    }, 300);
                });
            }

            // Optional: close the form if user taps header area
            var headerHero = document.querySelector('.header-hero');
            if(headerHero && mobileScreen){
                headerHero.addEventListener('click', function(){
                    if(mobileScreen.classList.contains('open')){
                        mobileScreen.classList.remove('open');
                        formFields.setAttribute('aria-hidden','true');
                        // scroll to top of mobile screen
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
@endsection
