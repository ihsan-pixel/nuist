@extends('layouts.master-without-nav')

@section('title')
    Lupa Password - Nuist Mobile
@endsection

@section('css')
    @include('mobile._auth-styles')
@endsection

@section('content')
    @include('mobile._auth-loader')
    <div class="mobile-auth-page">
        <div class="login-shell">
            <div class="login-backdrop" aria-hidden="true">
                <span class="backdrop-orb backdrop-orb-left"></span>
                <span class="backdrop-orb backdrop-orb-right"></span>
            </div>

            <div class="auth-header">
                <div class="brand-card brand-card-inline">
                    <img src="{{ asset('images/nuist_logo.png') }}" alt="Nuist">
                </div>

                <h1 class="welcome-title">Lupa Password</h1>
                <p class="welcome-subtitle">Masukkan email akun Anda untuk menerima tautan reset password.</p>
            </div>

            <div class="forgot-card welcome-card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="status-stack">
                            <div class="status-alert success">{{ session('status') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="status-stack">
                            <div class="status-alert error">Periksa kembali email yang Anda masukkan.</div>
                        </div>
                    @endif

                    <div class="login-panel" id="loginPanel">
                        <form class="forgot-form login-form" method="POST" action="{{ route('mobile.password.email') }}">
                            @csrf

                            <div class="auth-field-group">
                                <label class="input-label" for="email">Email</label>
                                <div class="field-shell">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="presentation">
                                            <path d="M12 13.5a5.5 5.5 0 1 0-5.5-5.5 5.5 5.5 0 0 0 5.5 5.5Zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5Z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="input-control"
                                        value="{{ old('email', request()->cookie('mobile_login_email')) }}"
                                        placeholder="Masukkan email akun"
                                        required
                                    >
                                </div>
                                @error('email')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="submit-btn" type="submit">Kirim Tautan Reset</button>
                            <div class="form-actions">
                                <a class="forgot-link" href="{{ route('mobile.login') }}">Kembali ke Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('mobile._auth-loader-script')
@endsection
