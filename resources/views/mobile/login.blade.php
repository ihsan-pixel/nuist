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
            background: #004b4c;
            padding: 22px 16px 14px 16px;
            color: #fff;
            position: relative;
            flex: 0 0 auto;
            height: 100%;
        }

        .hero-title{
            text-align:center;
            font-size:22px;
            margin: 100px 0 4px 0;
            font-weight:700;
        }

        .hero-illustration{ display:flex; justify-content:center; margin-top:14px; margin-bottom: 14px }
        .hero-illustration img{ width:100%; max-width:260px; height:auto; display:block }

    /* logo pill top-right */
    .logo-pill{ position:absolute; right:14px; top:14px; background:#ffffff; padding:8px; border-radius:12px; box-shadow:0 6px 18px rgba(14,42,120,0.06); display:inline-flex; align-items:center; justify-content:center }
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
        .pill-input::placeholder{ color: #0e8549 }
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

    /* Hero login button (white, placed under fast-menu) */
    .hero-login-white{ display:block; margin:12px auto 6px auto; background:#fff; color:#004b4c; border-radius:999px; padding:10px 18px; font-weight:700; border:none; box-shadow:0 8px 20px rgba(9,30,66,0.08); cursor:pointer; min-width:200px; margin-top: 14px }

    /* Drawer/backdrop for mobile form */
    .drawer-backdrop{ position:fixed; inset:0; background:rgba(3,9,23,0.45); opacity:0; pointer-events:none; transition:opacity .28s ease; z-index:1000 }
    .drawer-backdrop.visible{ opacity:1; pointer-events:auto }

    .form-card.drawer{ position:fixed; left:0; right:0; bottom:0; margin:0; border-top-left-radius:18px; border-top-right-radius:18px; transform:translateY(110%); transition:transform .45s cubic-bezier(.2,.9,.2,1); z-index:1100; max-height:86vh; overflow:auto; box-shadow: 0 -18px 30px rgba(9,30,66,0.12) }
    .mobile-screen.open .form-card.drawer{ transform:translateY(0) }

    .drawer-handle{ width:64px; height:6px; background:#e6eefb; border-radius:6px; margin:8px auto 10px auto }

    /* Fast menu (icon grid) */
    .fast-menu { display:flex; flex-direction:column; gap:10px; align-items:center; margin-bottom:6px }
    .fast-menu-title{ font-size:13px; color:#6b7cae; font-weight:600 }
    .fast-menu-row{ display:flex; gap:12px; justify-content:center; width:100%; padding:4px 6px }
    .menu-item{ display:flex; flex-direction:column; align-items:center; gap:8px; width:20%; min-width:60px; text-align:center }
    .menu-icon{ width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#f3f8ff; box-shadow:inset 0 -4px 10px rgba(13,110,253,0.03); }
    .menu-label{ font-size:12px; color:#1b3554 }

    /* Login action row: large blue pill + small circular icon */
    .login-actions{ display:flex; gap:10px; align-items:center; margin-top:6px }
    .btn-primary-pill{ flex:1; display:inline-flex; align-items:center; justify-content:center }
    .btn-icon{ width:46px; height:46px; border-radius:50%; background:linear-gradient(180deg,#fff 0,#cfe2ff 100%); display:inline-flex; align-items:center; justify-content:center; box-shadow:0 6px 18px rgba(13,110,253,0.08); border:none }
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
                        <div class="logo-pill" aria-hidden="true">
                            <img src="{{ asset('images/logo1.png') }}" alt="logo">
                        </div>
                        <h2 class="hero-title">Halo, Selamat Datang!</h2>
                    </div>
                    <div class="hero-illustration">
                        <img src="{{ asset('images/verification-img.png') }}" alt="illustration"/>
                    </div>
                    <div class="fast-menu" aria-hidden="false" style="margin-top: 14px">
                        <div class="fast-menu-title">Fast Menu</div>
                        <div class="fast-menu-row">
                            <div class="menu-item">
                                <div class="menu-icon"><img src="https://www.svgrepo.com/show/331488/note.svg" alt="note" style="width:22px;height:22px"></div>
                                <div class="menu-label">Catatan<br/>Kewajiban</div>
                            </div>
                            <div class="menu-item">
                                <div class="menu-icon"><img src="https://www.svgrepo.com/show/443131/wallet.svg" alt="wallet" style="width:22px;height:22px"></div>
                                <div class="menu-label">BSE/TB</div>
                            </div>
                            <div class="menu-item">
                                <div class="menu-icon"><img src="https://www.svgrepo.com/show/50899/wallet.svg" alt="ewallet" style="width:22px;height:22px"></div>
                                <div class="menu-label">E-Wallet</div>
                            </div>
                            <div class="menu-item">
                                <div class="menu-icon"><img src="https://www.svgrepo.com/show/349164/data.svg" alt="data" style="width:22px;height:22px"></div>
                                <div class="menu-label">Pulsa/Data</div>
                            </div>
                        </div>
                    </div>
                    <!-- White login button placed under Fast Menu (visible on hero) -->
                    <button id="openLogin" class="hero-login-white" aria-expanded="false" aria-controls="loginDrawer">Login</button>
                </div>


                <!-- Drawer backdrop -->
                <div id="drawerBackdrop" class="drawer-backdrop" aria-hidden="true"></div>

                <!-- White form card as a bottom drawer (hidden initially) -->
                <div id="loginDrawer" class="form-card drawer" role="dialog" aria-modal="true" aria-hidden="true">
                    <div class="drawer-handle" aria-hidden="true"></div>
                    <div class="form-top-spacer"></div>

                    <form method="POST" action="{{ route('mobile.login.authenticate') }}">
                        @csrf

                        <div class="input-wrap">
                            <input id="email" name="email" type="email" class="pill-input" placeholder="contoh@gmail.com" required value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-wrap">
                            <div class="pill-password">
                                <input id="password" name="password" type="password" class="pill-input" placeholder="password" required>
                                <button type="button" id="togglePassword" class="eye-btn" title="Tampilkan kata sandi">&#128065;</button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="login-actions">
                            <button class="btn-primary-pill" type="submit">Login</button>
                            <button type="button" class="btn-icon" aria-hidden="true">&rarr;</button>
                        </div>

                        {{-- <div class="socials">
                            <a class="social-pill" href="#" title="Google"><img src="https://www.svgrepo.com/show/355037/google.svg" alt="G"><span class="social-text">Sign in</span></a>
                            <a class="social-pill" href="#" title="Facebook"><img src="https://www.svgrepo.com/show/303145/facebook.svg" alt="F"><span class="social-text">Sign in</span></a>
                        </div> --}}

                        <div class="forgot"><a href="{{ route('password.request') }}">Forgot password?</a></div>
                    </form>
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
                    // ensure we don't double-bind
                    toggle.replaceWith(toggle.cloneNode(true));
                    toggle = document.getElementById('togglePassword');
                    toggle.addEventListener('click', function(){
                        if(pwd.type === 'password') pwd.type = 'text'; else pwd.type = 'password';
                    });
                }
            }

            setupPasswordToggle();

            var openBtn = document.getElementById('openLogin');
            var mobileScreen = document.querySelector('.mobile-screen');
            var drawer = document.getElementById('loginDrawer');
            var backdrop = document.getElementById('drawerBackdrop');
            var headerHero = document.querySelector('.header-hero');

            function openDrawer(){
                if(mobileScreen) mobileScreen.classList.add('open');
                if(drawer) drawer.setAttribute('aria-hidden','false');
                if(openBtn) openBtn.setAttribute('aria-expanded','true');
                if(backdrop) backdrop.classList.add('visible');
                // autofocus email after animation starts
                setTimeout(function(){ var email = document.getElementById('email'); if(email) email.focus(); setupPasswordToggle(); }, 320);
            }

            function closeDrawer(){
                if(mobileScreen) mobileScreen.classList.remove('open');
                if(drawer) drawer.setAttribute('aria-hidden','true');
                if(openBtn) openBtn.setAttribute('aria-expanded','false');
                if(backdrop) backdrop.classList.remove('visible');
            }

            if(openBtn){
                openBtn.addEventListener('click', function(e){ e.stopPropagation(); openDrawer(); });
            }

            if(backdrop){
                backdrop.addEventListener('click', function(){ closeDrawer(); });
            }

            // tapping header when open will close drawer (convenience)
            if(headerHero){
                headerHero.addEventListener('click', function(){ if(mobileScreen && mobileScreen.classList.contains('open')) closeDrawer(); });
            }

            // allow Escape key to close the drawer
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape' && mobileScreen && mobileScreen.classList.contains('open')) closeDrawer(); });
        });
    </script>
@endsection
