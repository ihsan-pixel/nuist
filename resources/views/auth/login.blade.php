@extends('layouts.master-without-nav')

@section('title') Login @endsection

@section('css')
<link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<style>
.auth-bg {
    background: linear-gradient(135deg, #0f5132, #198754);
    color: #fff;
}

.auth-card {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.auth-left {
    background: #fff;
    padding: 3rem;
}

.auth-right {
    background: linear-gradient(135deg, #0f5132, #198754);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    flex-direction: column;
}

.password-toggle {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 44px;
    border: none;
    background: transparent;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease;
}

.password-toggle:hover {
    background: rgba(0, 0, 0, 0.05);
    color: #198754;
}
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7 col-xl-6">
            <div class="card auth-card">
                <div class="row g-0">
                    <!-- Bagian Kiri -->
                    <div class="col-md-6 auth-left">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/nuist-logo.png') }}" alt="Logo" height="60">
                            <h4 class="mt-3 mb-0 fw-bold">LOGIN</h4>
                            <p class="text-muted">Welcome back! Please sign in to your account.</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="Enter Email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-3 position-relative">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password" required autocomplete="current-password"
                                           placeholder="Enter Password">
                                    <button type="button" class="password-toggle" data-target="#password">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <!-- Tombol Login -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success" style="background:#0f5132; border:none;">Log In</button>
                            </div>
                        </form>

                        <div class="text-center mt-4 small text-muted">
                            © 2025 Nuist. Crafted by LP. Ma'arif NU PWNU DIY
                        </div>
                    </div>

                    <!-- Bagian Kanan -->
                    <div class="col-md-6 auth-right">
                        <h4 class="fw-bold mb-2">Welcome Back!</h4>
                        <p class="mb-4">Sistem Informasi Digital LP. Ma’arif NU PWNU DIY</p>
                        <img src="{{ asset('assets/images/login-illustration.png') }}" alt="Login Illustration" class="img-fluid" width="200">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.password-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = document.querySelector(this.dataset.target);
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('mdi-eye-outline');
                icon.classList.add('mdi-eye-off-outline');
            } else {
                input.type = 'password';
                icon.classList.remove('mdi-eye-off-outline');
                icon.classList.add('mdi-eye-outline');
            }
        });
    });
});
</script>
@endsection
