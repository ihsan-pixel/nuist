@extends('layouts.master-without-nav')

@section('title')
Login - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY
@endsection

@section('css')
<link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('body')
<body class="auth-body-bg">
@endsection

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <!-- Form Section -->
        <div class="form-section">
            <div class="form-container">
                <div class="logo-section">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="logo">
                </div>
                <h1 class="login-title">LOGIN</h1>
                <p class="login-subtitle">Welcome back! Please sign in to your account.</p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username" class="form-label">Email <span class="text-danger">*</span></label>
                        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" id="username"
                               placeholder="Enter Email" autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="userpassword" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="password-input-container @error('password') is-invalid @enderror">
                            <input type="password" name="password"
                                   class="form-control password-input @error('password') is-invalid @enderror"
                                   id="userpassword" placeholder="Enter password"
                                   aria-label="Password" aria-describedby="password-addon" autocomplete="current-password">
                            <button type="button" class="btn btn-light password-toggle-btn" id="password-addon" data-target="#userpassword" aria-pressed="false" aria-label="Lihat password">
                                <i class="mdi mdi-eye-outline"></i>
                            </button>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <button class="btn btn-primary login-btn" type="submit">Log In</button>
                </form>

                <div class="footer-text">
                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Nuist. Crafted by LP. Ma'arif NU PWNU DIY</p>
                </div>
            </div>
        </div>

        <!-- Illustration Section -->
        <div class="illustration-section">
            <div class="illustration-content">
                <h2>Welcome Back!</h2>
                <p>Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</p>
                <div class="illustration-placeholder">
                    <img src="{{ asset('images/p.png') }}" alt="Login Illustration" class="illustration-image">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-wrapper {
        display: flex;
        width: 100%;
        max-width: 1200px;
        min-height: 600px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        flex: 1;
        background: white;
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .illustration-section {
        flex: 1;
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        padding: 60px 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .form-container {
        max-width: 400px;
        width: 100%;
    }

    .logo-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .logo {
        height: 80px;
        width: auto;
    }

    .login-title {
        font-size: 32px;
        font-weight: 700;
        color: #004b4c;
        text-align: center;
        margin-bottom: 10px;
    }

    .login-subtitle {
        font-size: 16px;
        color: #6c757d;
        text-align: center;
        margin-bottom: 30px;
    }

    .login-form {
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #6C63FF;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
        outline: none;
    }

    .password-input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input {
        padding-right: 50px !important;
    }

    .password-toggle-btn {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 50px;
        border: none;
        background: transparent;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .password-toggle-btn:hover {
        background: rgba(108, 99, 255, 0.05);
        color: #495057;
    }

    .form-check {
        margin-bottom: 20px;
    }

    .form-check-input:checked {
        background-color: #6C63FF;
        border-color: #6C63FF;
    }

    .login-btn {
        width: 100%;
        padding: 14px;
        background: #004b4c;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .login-btn:hover {
        background: #006e70;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
    }

    .footer-text {
        text-align: center;
        color: #6c757d;
        font-size: 14px;
    }

    .illustration-content {
        text-align: center;
        max-width: 400px;
    }

    .illustration-content h2 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .illustration-content p {
        font-size: 16px;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .illustration-placeholder {
        display: flex;
        justify-content: center;
    }

    .illustration-image {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-wrapper {
            flex-direction: column;
            min-height: auto;
            max-width: 100%;
        }

        .form-section {
            flex: none;
            padding: 30px 20px;
        }

        .illustration-section {
            order: -1;
            min-height: 50px;
            padding: 20px;
        }

        .illustration-content {
            display: none;
        }

        .form-container {
            max-width: none;
        }

        .login-title {
            font-size: 24px;
        }

        .login-subtitle {
            font-size: 14px;
        }

        .form-control {
            font-size: 14px;
            padding: 10px 12px;
        }

        .login-btn {
            padding: 12px;
            font-size: 14px;
        }

        .logo {
            height: 60px;
        }
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 10px;
        }

        .form-section {
            padding: 20px 15px;
        }

        .login-title {
            font-size: 20px;
        }

        .login-subtitle {
            font-size: 13px;
        }

        .form-control {
            font-size: 13px;
            padding: 8px 10px;
        }

        .login-btn {
            padding: 10px;
            font-size: 13px;
        }

        .logo {
            height: 50px;
        }
    }
</style>
@endsection

@section('script')
<script src="{{ asset('build/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('build/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('password-addon');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const input = document.getElementById('userpassword');
            const icon = this.querySelector('i');

            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('mdi-eye-outline');
                icon.classList.add('mdi-eye-off-outline');
                this.setAttribute('aria-pressed', 'true');
            } else {
                input.type = 'password';
                icon.classList.remove('mdi-eye-off-outline');
                icon.classList.add('mdi-eye-outline');
                this.setAttribute('aria-pressed', 'false');
            }
        });
    }
});
</script>
@endsection
