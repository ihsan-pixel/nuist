@extends('layouts.master-without-nav')

@section('title')
    Masuk - Nuist Mobile
@endsection

@section('css')
    <style>
        :root {
            --auth-bg-start: #0d8e89;
            --auth-bg-end: #08756f;
            --card-accent: #7fe0db;
            --card-accent-soft: #dff6f4;
            --text-main: #1f4f4c;
            --text-muted: #6d7f7d;
            --border-soft: #b7e3df;
            --shadow-main: 0 28px 60px rgba(0, 71, 67, 0.34);
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, var(--auth-bg-start) 0%, var(--auth-bg-end) 100%);
        }

        .mobile-auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.14), transparent 32%),
                radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.12), transparent 28%);
        }

        .welcome-card {
            width: min(100%, 360px);
            border-radius: 34px;
            background: #ffffff;
            box-shadow: var(--shadow-main);
            overflow: hidden;
            position: relative;
        }

        .welcome-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 34px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
            pointer-events: none;
        }

        .card-top {
            position: relative;
            height: 132px;
            background: linear-gradient(180deg, #8fe6e0 0%, #78d7d1 100%);
            overflow: hidden;
        }

        .card-top::before,
        .card-top::after {
            content: "";
            position: absolute;
            left: -8%;
            right: -8%;
            border-radius: 50%;
        }

        .card-top::before {
            bottom: 22px;
            height: 78px;
            background: rgba(255, 255, 255, 0.45);
        }

        .card-top::after {
            bottom: -18px;
            height: 92px;
            background: #ffffff;
        }

        .brand-pill {
            position: absolute;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(0, 98, 93, 0.16);
        }

        .brand-pill img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .card-body {
            padding: 14px 26px 28px;
            text-align: center;
        }

        .welcome-title {
            margin: 0;
            font-size: 2rem;
            line-height: 1.1;
            font-weight: 700;
            color: #67d3cc;
            text-shadow: 0 3px 10px rgba(103, 211, 204, 0.2);
        }

        .welcome-subtitle {
            margin: 10px 0 0;
            color: var(--text-muted);
            font-size: 0.96rem;
            font-weight: 500;
        }

        .hero-illustration {
            margin: 26px auto 18px;
            width: min(100%, 228px);
            display: block;
            filter: drop-shadow(0 12px 20px rgba(127, 224, 219, 0.22));
        }

        .status-stack {
            display: grid;
            gap: 10px;
            margin-bottom: 8px;
            text-align: left;
        }

        .status-alert {
            border-radius: 16px;
            padding: 12px 14px;
            font-size: 0.88rem;
            line-height: 1.45;
        }

        .status-alert.success {
            background: #e8f8ee;
            color: #1d6b40;
            border: 1px solid #bfe8cb;
        }

        .status-alert.error {
            background: #fdecec;
            color: #a33b3b;
            border: 1px solid #f7c4c4;
        }

        .action-stack {
            display: grid;
            gap: 12px;
            margin-top: 8px;
        }

        .action-btn {
            width: 100%;
            border-radius: 14px;
            height: 48px;
            border: 0;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .action-btn-primary {
            background: linear-gradient(180deg, #7dded8 0%, #61cbc4 100%);
            color: #ffffff;
            box-shadow: 0 14px 28px rgba(97, 203, 196, 0.28);
        }

        .action-btn-secondary {
            background: #ffffff;
            color: #78bdb8;
            border: 2px solid var(--border-soft);
        }

        .login-panel {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #edf6f5;
            text-align: left;
            display: none;
        }

        .welcome-card.is-open .login-panel {
            display: block;
            animation: revealPanel 0.25s ease;
        }

        @keyframes revealPanel {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .panel-title {
            margin: 0 0 12px;
            font-size: 0.96rem;
            font-weight: 600;
            color: var(--text-main);
            text-align: center;
        }

        .input-group {
            margin-bottom: 12px;
        }

        .input-label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-main);
            font-size: 0.88rem;
            font-weight: 600;
        }

        .input-control {
            width: 100%;
            height: 46px;
            border-radius: 14px;
            border: 1px solid #d8ece9;
            background: #f8fcfb;
            padding: 0 14px;
            color: #244744;
            font-size: 0.94rem;
            outline: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .input-control:focus {
            border-color: #72d5cf;
            box-shadow: 0 0 0 4px rgba(114, 213, 207, 0.16);
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap .input-control {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #6ca7a2;
            font-size: 1.05rem;
            cursor: pointer;
        }

        .field-error {
            margin-top: 6px;
            color: #c44f4f;
            font-size: 0.78rem;
        }

        .submit-btn {
            margin-top: 6px;
            width: 100%;
            height: 46px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(180deg, #169892 0%, #0b7d77 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.96rem;
            box-shadow: 0 12px 24px rgba(11, 125, 119, 0.24);
        }

        .panel-footer {
            margin-top: 12px;
            text-align: center;
            font-size: 0.84rem;
            color: var(--text-muted);
        }

        .panel-footer a {
            color: #15918b;
            font-weight: 600;
            text-decoration: none;
        }

        @media (max-width: 420px) {
            .mobile-auth-page {
                padding: 18px 12px;
            }

            .welcome-card {
                width: 100%;
                border-radius: 30px;
            }

            .card-body {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="mobile-auth-page">
        <div class="welcome-card {{ $errors->any() ? 'is-open' : '' }}" id="welcomeCard">
            <div class="card-top">
            </div>

            <div class="card-body">
                <div class="brand-pill">
                    <img src="{{ asset('images/logo favicon 1.png') }}" alt="Nuist">
                </div>
                <h1 class="welcome-title">Welcome!</h1>
                <p class="welcome-subtitle">Nuist Mobile LP. Ma'arif NU PWNU DIY</p>

                {{-- <img
                    class="hero-illustration"
                    src="{{ asset('build/images/verification-img.png') }}"
                    alt="Ilustrasi login Nuist"
                > --}}

                @if (session('status'))
                    <div class="status-stack">
                        <div class="status-alert success">{{ session('status') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="status-stack">
                        <div class="status-alert error">{{ session('error') }}</div>
                    </div>
                @endif

                <div class="action-stack">
                    <a class="action-btn action-btn-primary" href="{{ route('mobile.register') }}">Sign Up</a>
                    <button
                        type="button"
                        class="action-btn action-btn-secondary"
                        id="toggleLoginPanel"
                        aria-expanded="{{ $errors->any() ? 'true' : 'false' }}"
                        aria-controls="loginPanel"
                    >
                        Login
                    </button>
                </div>

                <div class="login-panel" id="loginPanel">
                    <p class="panel-title">Masuk ke akun Anda</p>

                    <form method="POST" action="{{ route('mobile.login.authenticate') }}">
                        @csrf

                        <div class="input-group">
                            <label class="input-label" for="email">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="input-control"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email"
                                required
                            >
                            @error('email')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-group">
                            <label class="input-label" for="password">Password</label>
                            <div class="password-wrap">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="input-control"
                                    placeholder="Masukkan password"
                                    required
                                >
                                <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                                    &#128065;
                                </button>
                            </div>
                            @error('password')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="submit-btn" type="submit">Login Sekarang</button>
                    </form>

                    <div class="panel-footer">
                        <a href="{{ route('mobile.password.request') }}">Forgot password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var card = document.getElementById('welcomeCard');
            var panel = document.getElementById('loginPanel');
            var togglePanelBtn = document.getElementById('toggleLoginPanel');
            var passwordInput = document.getElementById('password');
            var togglePasswordBtn = document.getElementById('togglePassword');

            function setPanelState(isOpen) {
                if (!card || !togglePanelBtn) return;

                card.classList.toggle('is-open', isOpen);
                togglePanelBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                if (isOpen) {
                    var emailInput = document.getElementById('email');
                    if (emailInput) {
                        setTimeout(function () {
                            emailInput.focus();
                        }, 120);
                    }
                }
            }

            if (togglePanelBtn) {
                togglePanelBtn.addEventListener('click', function () {
                    setPanelState(!card.classList.contains('is-open'));
                });
            }

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                });
            }

            if (panel && card.classList.contains('is-open')) {
                setPanelState(true);
            }
        });
    </script>
@endsection
