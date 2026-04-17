@extends('layouts.master-without-nav')

@section('title')
    Masuk - Nuist Mobile
@endsection

@section('css')
    @include('mobile._auth-styles')
@endsection

@section('content')
    @include('mobile._auth-loader')
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
                            {{-- <div class="password-wrap"> --}}
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
                            {{-- </div> --}}
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
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('mobile._auth-loader-script')
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
