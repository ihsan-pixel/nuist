@extends('layouts.master-without-nav')

@section('title')
    Reset Password - Nuist Mobile
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
                    <img
                        src="{{ asset('build/images/logo.svg') }}"
                        alt="Nuist"
                        onerror="this.onerror=null;this.src='{{ asset('build/images/logo-light.svg') }}';"
                        style="width:100%;height:100%;max-width:64px;max-height:64px;display:block;object-fit:contain;"
                    >
                </div>

                <h1 class="welcome-title">Reset Password</h1>
                <p class="welcome-subtitle">Buat password baru untuk akun Anda.</p>
            </div>

            <div class="reset-card welcome-card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="status-stack">
                            <div class="status-alert success">{{ session('status') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="status-stack">
                            <div class="status-alert error">Periksa kembali data reset password Anda.</div>
                        </div>
                    @endif

                    <div class="login-panel" id="loginPanel">
                        <form class="reset-form login-form" method="POST" action="{{ route('mobile.password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

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
                                        value="{{ $email ?? old('email') }}"
                                        placeholder="Masukkan email akun"
                                        required
                                    >
                                </div>
                                @error('email')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="auth-field-group">
                                <label class="input-label" for="password">Password Baru</label>
                                <div class="field-shell">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="presentation">
                                            <path d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-5 8a2 2 0 1 1 2-2 2 2 0 0 1-2 2Zm-2-8V6a2 2 0 0 1 4 0v2Z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="input-control"
                                        placeholder="Masukkan password baru"
                                        required
                                    >
                                </div>
                                @error('password')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="auth-field-group">
                                <label class="input-label" for="password_confirmation">Konfirmasi Password</label>
                                <div class="field-shell">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="presentation">
                                            <path d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-5 8a2 2 0 1 1 2-2 2 2 0 0 1-2 2Zm-2-8V6a2 2 0 0 1 4 0v2Z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        class="input-control"
                                        placeholder="Ulangi password baru"
                                        required
                                    >
                                </div>
                            </div>

                            <button class="submit-btn" type="submit">Reset Password</button>
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
