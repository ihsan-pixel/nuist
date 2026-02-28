@extends('layouts.master-without-nav')

@section('title')
    Masuk - Nuist Mobile
@endsection

@section('css')
    <style>
        /* Mobile-first login styles - full width, green theme */
        .mobile-auth-bg {
            min-height: 100vh;
            display: block;
            padding: 0;
            background: linear-gradient(180deg, #eaf7f0 0%, #ffffff 100%);
        }

        .mobile-screen {
            width: 100vw;
            max-width: 100vw;
            position: relative;
            -webkit-font-smoothing:antialiased;
            margin: 0;
        }

        /* Green header that spans full device width. No outer whitespace. */
        .mobile-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 0;
            padding: 28px 18px 22px 18px;
            position: relative;
            overflow: hidden;
        }

        .mobile-header h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
        }

        .mobile-header p.lead {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 13px;
        }

        .mobile-illustration { display:block; width:100%; max-height:120px; object-fit:contain; margin-top:12px }

        /* Form area: full width, sitting directly below header with consistent green background */
        .mobile-form-area {
            padding: 18px;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        }

        .muted {
            color: rgba(255,255,255,0.9);
        }

        .input-round {
            border-radius: 10px !important;
            padding: 12px 14px !important;
            box-shadow: none !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
            font-size: 15px;
            background: rgba(255,255,255,0.95);
            color: #0b3b37;
        }

        .btn-login {
            width: 100%;
            background: #ffffff;
            color: #004b4c;
            border-radius: 10px;
            padding: 12px 14px;
            font-weight: 700;
            border: none;
        }

        .social-row { display:flex; gap:10px; justify-content:center; margin-top:10px }
        .social-btn { border-radius:999px; width:44px; height:44px; display:inline-flex; align-items:center; justify-content:center; background: rgba(255,255,255,0.95); border: 1px solid rgba(255,255,255,0.12) }

        .forgot-link { display:block; text-align:center; margin-top:10px; color:rgba(255,255,255,0.95); }

        @media (max-width:420px){
            .mobile-header { padding:20px 14px 18px 14px }
            .mobile-form-area { padding:14px }
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
            </div>

            <div class="mobile-form-area">
                <div class="text-center muted mb-2">Login To Your Account</div>

                <form method="POST" action="{{ route('mobile.login.authenticate') }}">
                    @csrf

                    <div class="mb-3">
                        <input id="email" name="email" type="email" class="form-control input-round" placeholder="nama@contoh.com" required value="{{ old('email') }}">
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
                        <button class="btn-login" type="submit">Masuk</button>
                    </div>

                    <div class="text-center muted">atau masuk dengan</div>
                    <div class="social-row" role="group" aria-label="social logins">
                        <a class="social-btn" href="#" title="Google"><img src="https://www.svgrepo.com/show/355037/google.svg" alt="G" style="width:18px;height:18px"></a>
                        <a class="social-btn" href="#" title="Facebook"><img src="https://www.svgrepo.com/show/303145/facebook.svg" alt="F" style="width:18px;height:18px"></a>
                    </div>

                    <a class="forgot-link" href="{{ route('password.request') }}">Lupa kata sandi?</a>

                    <div class="text-center muted mt-3">Belum punya akun? <a href="{{ route('register') }}" class="text-white" style="text-decoration:underline">Daftar</a></div>
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
