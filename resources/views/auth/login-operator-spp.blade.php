@extends('layouts.master-without-nav')

@section('title')
Login Operator SPP
@endsection

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('body')
<body>
@endsection

@section('content')
<div class="spp-login-shell">
    <div class="spp-login-grid">
        <section class="spp-login-hero">
            <div class="hero-badge">Portal Operator SPP</div>
            <h1>Login Admin SPP Sekolah</h1>
            <p>Gunakan email dan password yang dikirim saat pendaftaran Operator SPP disetujui.</p>

            <div class="hero-points">
                <div class="hero-point">
                    <strong>Akses Khusus</strong>
                    <span>Halaman ini hanya menerima akun <code>Operator SPP Sekolah</code>.</span>
                </div>
                <div class="hero-point">
                    <strong>Kredensial Email</strong>
                    <span>Login menggunakan email unik yang didaftarkan pada form Operator SPP.</span>
                </div>
                {{-- <div class="hero-point">
                    <strong>Alur Pembayaran</strong>
                    <span>Setelah login, operator langsung masuk ke dashboard monitoring SPP siswa.</span>
                </div> --}}
            </div>
        </section>

        <section class="spp-login-card">
            <div class="card-head">
                <img src="{{ asset('images/logo1.png') }}" alt="NUist" class="brand-logo">
                <div>
                    <h2>Masuk Operator SPP</h2>
                    <p>Portal login khusus pengelola tagihan dan pembayaran SPP.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.operator-spp.submit') }}" class="spp-login-form">
                @csrf
                <div class="form-block">
                    <label for="email">Email Operator SPP</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Masukkan email operator" required autofocus>
                </div>

                <div class="form-block">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input id="password" type="password" name="password" class="form-control password-input" placeholder="Masukkan password" required>
                        <button type="button" class="toggle-btn" id="toggleOperatorPassword" aria-label="Tampilkan password">
                            <span>Tampil</span>
                        </button>
                    </div>
                </div>

                <label class="remember-row">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat sesi login ini</span>
                </label>

                <div class="info-box">
                    <strong>Catatan</strong>
                    <span>Jika Anda sebelumnya sudah punya akun , gunakan email baru/lain untuk akun Operator SPP.</span>
                </div>

                <button type="submit" class="submit-btn">Masuk ke Dashboard SPP</button>
            </form>

            <div class="action-links">
                <a href="{{ route('spp-operator.register') }}">Ajukan akun Operator SPP</a>
                <a href="{{ route('login') }}">Login umum Nuist</a>
            </div>
        </section>
    </div>
</div>

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background:
            radial-gradient(circle at top left, rgba(20, 83, 45, 0.16), transparent 34%),
            radial-gradient(circle at bottom right, rgba(14, 116, 144, 0.18), transparent 36%),
            linear-gradient(180deg, #f7faf9 0%, #edf5f3 100%);
        color: #17342d;
    }

    .spp-login-shell {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 28px 18px;
    }

    .spp-login-grid {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 28px;
    }

    .spp-login-hero,
    .spp-login-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 28px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
    }

    .spp-login-hero {
        padding: 42px 38px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .hero-badge {
        display: inline-flex;
        width: fit-content;
        padding: 8px 14px;
        border-radius: 999px;
        background: #dff7ec;
        color: #166534;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .spp-login-hero h1 {
        font-size: 42px;
        line-height: 1.08;
        margin: 0 0 14px;
    }

    .spp-login-hero p {
        margin: 0 0 28px;
        font-size: 16px;
        color: #5b706a;
        max-width: 580px;
    }

    .hero-points {
        display: grid;
        gap: 14px;
    }

    .hero-point {
        padding: 18px 20px;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f6fbf9 100%);
        border: 1px solid #dbe8e3;
    }

    .hero-point strong,
    .hero-point span {
        display: block;
    }

    .hero-point strong {
        margin-bottom: 6px;
        font-size: 15px;
    }

    .hero-point span {
        color: #5b706a;
        font-size: 14px;
    }

    .spp-login-card {
        padding: 30px;
    }

    .card-head {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 24px;
    }

    .brand-logo {
        width: 100px;
        height: auto;
    }

    .card-head h2 {
        margin: 0 0 4px;
        font-size: 26px;
    }

    .card-head p {
        margin: 0;
        color: #61766f;
    }

    .spp-login-form {
        display: grid;
        gap: 18px;
    }

    .form-block label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        min-height: 50px;
        border-radius: 14px;
        border: 1px solid #d5e2dc;
        padding: 12px 14px;
        background: #fff;
    }

    .password-wrap {
        position: relative;
    }

    .password-input {
        padding-right: 84px;
    }

    .toggle-btn {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #166534;
        font-weight: 600;
    }

    .remember-row {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #4f655f;
        font-size: 14px;
    }

    .info-box {
        padding: 16px 18px;
        border-radius: 16px;
        background: #f6fbf8;
        border: 1px dashed #bfd8ce;
    }

    .info-box strong,
    .info-box span {
        display: block;
    }

    .info-box span {
        margin-top: 6px;
        color: #5c726c;
        font-size: 14px;
    }

    .submit-btn {
        border: 0;
        border-radius: 16px;
        padding: 14px 18px;
        background: linear-gradient(135deg, #14532d, #0f766e);
        color: #fff;
        font-weight: 700;
    }

    .action-links {
        margin-top: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .action-links a {
        color: #14532d;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .spp-login-grid {
            grid-template-columns: 1fr;
        }

        .spp-login-hero h1 {
            font-size: 34px;
        }
    }

    @media (max-width: 640px) {
        .spp-login-shell {
            padding: 18px 12px;
        }

        .spp-login-hero,
        .spp-login-card {
            padding: 22px;
            border-radius: 22px;
        }

        .spp-login-hero h1 {
            font-size: 30px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('password');
        var toggleButton = document.getElementById('toggleOperatorPassword');

        if (!passwordInput || !toggleButton) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            var isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleButton.querySelector('span').textContent = isPassword ? 'Sembunyi' : 'Tampil';
        });
    });
</script>
@endsection
