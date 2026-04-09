@extends('layouts.master-without-nav')

@section('title')
    Lupa Password - Nuist Mobile
@endsection

@section('css')
    @include('mobile._auth-styles')
@endsection

@section('content')
    <div class="mobile-auth-page">
        <div class="forgot-card">
            <div class="card-top">
            </div>

            <div class="card-body">
                <div class="brand-pill">
                    <img src="{{ asset('images/logo favicon 1.png') }}" alt="Nuist">
                </div>
                <h1 class="welcome-title">Welcome!</h1>
                <p class="welcome-subtitle">Reset your password through your email</p>

                {{-- <img
                    class="hero-illustration"
                    src="{{ asset('build/images/verification-img.png') }}"
                    alt="Ilustrasi forgot password Nuist"
                > --}}

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

                <form class="forgot-form" method="POST" action="{{ route('mobile.password.email') }}">
                    @csrf

                    <div class="input-group">
                        <label class="input-label" for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="input-control"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email akun"
                            required
                        >
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="submit-btn" type="submit">Kirim Tautan Reset</button>
                    <a class="secondary-btn" href="{{ route('mobile.login') }}">Kembali ke Login</a>
                </form>

                <div class="panel-footer">
                    Ingat password Anda? <a href="{{ route('mobile.login') }}">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
@endsection
