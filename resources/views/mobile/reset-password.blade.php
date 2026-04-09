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
        <div class="reset-card">
            <div class="card-top">
            </div>

            <div class="card-body">
                <div class="brand-pill">
                    <img src="{{ asset('images/logo favicon 1.png') }}" alt="Nuist">
                </div>
                <h1 class="welcome-title">Welcome!</h1>
                <p class="welcome-subtitle">Set a new password for your account</p>

                {{-- <img
                    class="hero-illustration"
                    src="{{ asset('build/images/verification-img.png') }}"
                    alt="Ilustrasi reset password Nuist"
                > --}}

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

                <form class="reset-form" method="POST" action="{{ route('mobile.password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

                    <div class="input-group">
                        <label class="input-label" for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="input-control"
                            value="{{ $email ?? old('email') }}"
                            placeholder="Masukkan email akun"
                            required
                        >
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password">Password Baru</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="input-control"
                            placeholder="Masukkan password baru"
                            required
                        >
                        @error('password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password_confirmation">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="input-control"
                            placeholder="Ulangi password baru"
                            required
                        >
                    </div>

                    <button class="submit-btn" type="submit">Reset Password</button>
                    <a class="secondary-btn" href="{{ route('mobile.login') }}">Kembali ke Login</a>
                </form>

                <div class="panel-footer">
                    Butuh halaman login? <a href="{{ route('mobile.login') }}">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('mobile._auth-loader-script')
@endsection
