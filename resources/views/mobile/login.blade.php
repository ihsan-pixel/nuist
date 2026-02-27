@extends('layouts.master-without-nav')

@section('title')
    Masuk - Nuist Mobile
@endsection

@section('css')
    <style>
        /* Mobile-first login styles */
        .mobile-auth-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: linear-gradient(180deg, rgba(13,110,253,0.12) 0%, rgba(13,110,253,0.06) 50%, #ffffff 100%);
        }

        .mobile-login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(13,110,253,0.08);
            padding: 20px;
            border: 1px solid rgba(13,110,253,0.06);
        }

        .mobile-login-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .mobile-login-logo img {
            height: 40px;
        }

        .mobile-login-title {
            font-weight: 700;
            color: #0d6efd; /* preserve primary color */
            margin: 0 0 6px 0;
            font-size: 18px;
        }

        .mobile-login-sub {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .form-control-lg {
            padding: 14px 12px;
            font-size: 15px;
            border-radius: 10px;
        }

        .btn-primary-lg {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 600;
        }

        .small-link {
            font-size: 13px;
            color: #6c757d;
        }

        @media (max-width:420px){
            .mobile-login-card { padding: 16px; border-radius: 12px; }
        }
    </style>
@endsection

@section('body')
    <body class="auth-body-bg">
@endsection

@section('content')

    <div class="mobile-auth-bg">
        <div class="mobile-login-card">
            <div class="mobile-login-logo">
                <a href="#" class="d-inline-block">
                    <img src="{{ asset('build/images/logo-light.png') }}" alt="Nuist" />
                </a>
                <div>
                    <h1 class="mobile-login-title">Nuist Mobile</h1>
                    <div class="mobile-login-sub">Masuk untuk mengakses fitur mobile Nuist</div>
                </div>
            </div>

            <form method="POST" action="{{ route('mobile.login.authenticate') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-control form-control-lg" placeholder="nama@contoh.com" required value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata sandi</label>
                    <div class="input-group">
                        <input id="password" name="password" type="password" class="form-control form-control-lg" placeholder="Kata sandi" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" title="Tampilkan kata sandi">👁</button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small-link">Lupa kata sandi?</a>
                </div>

                <div class="d-grid mb-3">
                    <button class="btn btn-primary btn-primary-lg" type="submit">Masuk</button>
                </div>

                <div class="text-center small-link">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-primary">Daftar</a>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var toggle = document.getElementById('togglePassword');
            var pwd = document.getElementById('password');
            if(toggle && pwd){
                toggle.addEventListener('click', function(){
                    if(pwd.type === 'password') pwd.type = 'text'; else pwd.type = 'password';
                });
            }
        });
    </script>
@endsection
