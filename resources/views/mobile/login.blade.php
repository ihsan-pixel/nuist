@extends('layouts.master-without-nav')

@section('title')
    Masuk - Nuist Mobile
@endsection

@section('css')
    <style>
        /* Mobile-first login styles - updated to match sample mobile UI */
        .mobile-auth-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(180deg, #f4f8ff 0%, #ffffff 100%);
        }

        .mobile-screen {
            width: 100%;
            max-width: 420px;
            position: relative;
            -webkit-font-smoothing:antialiased;
        }

        /* Blue header with illustration */
        .mobile-header {
            background: linear-gradient(180deg, #0d6efd 0%, #2b9bff 100%);
            color: #fff;
            border-radius: 20px;
            padding: 22px 18px 40px 18px;
            box-shadow: 0 12px 30px rgba(13,110,253,0.12);
            position: relative;
            overflow: hidden;
        }

        .mobile-header h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .mobile-header p.lead {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 13px;
        }

        .mobile-illustration {
            width: 100%;
            max-height: 110px;
            object-fit: contain;
            display: block;
            margin-top: 10px;
        }

        /* White rounded card overlapping the header */
        .mobile-card {
            background: #fff;
            border-radius: 22px;
            padding: 18px;
            position: relative;
            margin-top: -28px;
            box-shadow: 0 10px 30px rgba(20,40,80,0.06);
            border: 1px solid rgba(13,110,253,0.06);
        }

        .mobile-card .muted {
            color: #6c757d;
            font-size: 13px;
        }

        .input-round {
            border-radius: 12px !important;
            padding: 12px 14px !important;
            box-shadow: none !important;
            border: 1px solid rgba(10,40,120,0.06) !important;
            font-size: 15px;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(90deg,#0d6efd,#2b9bff);
            color: #fff;
            border-radius: 12px;
            padding: 12px 14px;
            font-weight: 700;
            border: none;
            box-shadow: 0 8px 18px rgba(13,110,253,0.12);
        }

        .social-row {
            display:flex;
            gap:10px;
            justify-content:center;
            margin-top:8px;
        }

        .social-btn {
            border-radius: 999px;
            width:44px;height:44px;display:inline-flex;align-items:center;justify-content:center;
            border:1px solid rgba(13,110,253,0.08);
            background:#fff;
        }

        .forgot-link { display:block; text-align:center; margin-top:10px; color:#6c757d; }

        .dots {
            display:flex;gap:6px;justify-content:center;margin-top:8px
        }

        .dot { width:6px;height:6px;border-radius:999px;background:rgba(255,255,255,0.45)}

        @media (max-width:420px){
            .mobile-header { padding:18px 14px 36px 14px; border-radius:16px }
            .mobile-card { border-radius:16px; margin-top:-22px }
        }
    </style>
@endsection

@section('body')
    <body class="auth-body-bg">
@endsection

@section('content')

    <div class="mobile-auth-bg">
        <div class="mobile-screen">
            <div class="mobile-header">
                <h2>Hello!</h2>
                <p class="lead">This application will help you to simplify interaction with your team and increase benefits in your work</p>
                {{-- illustration: use existing image if available --}}
                <img src="{{ asset('images/verification-img.png') }}" alt="illustration" class="mobile-illustration">

                <div class="dots" aria-hidden="true">
                    <div class="dot"></div>
                    <div class="dot" style="opacity:.6"></div>
                    <div class="dot" style="opacity:.3"></div>
                </div>
            </div>

            <div class="mobile-card">
                <div class="text-center muted mb-2">Login To Your Account</div>

                <form method="POST" action="{{ route('mobile.login.authenticate') }}">
                    @csrf

                    <div class="mb-3">
                        <input id="email" name="email" type="email" class="form-control input-round" placeholder="nikiforov@dribbble.com" required value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <input id="password" name="password" type="password" class="form-control input-round" placeholder="Kata sandi" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" title="Tampilkan kata sandi">👁</button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button class="btn-login" type="submit">Login</button>
                    </div>

                    <div class="text-center muted">atau masuk dengan</div>
                    <div class="social-row" role="group" aria-label="social logins">
                        <a class="social-btn" href="#" title="Google"><img src="https://www.svgrepo.com/show/355037/google.svg" alt="G" style="width:18px;height:18px"></a>
                        <a class="social-btn" href="#" title="Facebook"><img src="https://www.svgrepo.com/show/303145/facebook.svg" alt="F" style="width:18px;height:18px"></a>
                    </div>

                    <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>

                    <div class="text-center muted mt-3">Belum punya akun? <a href="{{ route('register') }}" class="text-primary">Daftar</a></div>
                </form>
            </div>
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
