@extends('layouts.master-without-nav')

@section('title')
    Daftar - Nuist Mobile
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <style>
        :root {
            --auth-bg-start: #0d8e89;
            --auth-bg-end: #08756f;
            --text-main: #1f4f4c;
            --text-muted: #6d7f7d;
            --border-soft: #b7e3df;
            --shadow-main: 0 28px 60px rgba(0, 71, 67, 0.34);
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--auth-bg-start);
        }

        .mobile-auth-page {
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(180deg, var(--auth-bg-start) 0%, var(--auth-bg-end) 100%);
        }

        .register-card {
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            border-radius: 0;
            background: #fff;
            box-shadow: none;
            overflow: hidden;
            position: relative;
        }

        .register-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 0;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
            pointer-events: none;
        }

        .card-top {
            position: relative;
            height: 132px;
            background: linear-gradient(180deg, #8fe6e0 0%, #78d7d1 100%);
            overflow: hidden;
        }

        .card-top::before,
        .card-top::after {
            content: "";
            position: absolute;
            left: -8%;
            right: -8%;
            border-radius: 50%;
        }

        .card-top::before {
            bottom: 22px;
            height: 78px;
            background: rgba(255, 255, 255, 0.45);
        }

        .card-top::after {
            bottom: -18px;
            height: 92px;
            background: #fff;
        }

        .brand-pill {
            position: absolute;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(0, 98, 93, 0.16);
        }

        .brand-pill img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .card-body {
            padding: 14px 20px 28px;
            text-align: center;
        }

        .welcome-title {
            margin: 0;
            font-size: 2rem;
            line-height: 1.1;
            font-weight: 700;
            color: #67d3cc;
            text-shadow: 0 3px 10px rgba(103, 211, 204, 0.2);
        }

        .welcome-subtitle {
            margin: 10px 0 0;
            color: var(--text-muted);
            font-size: 0.96rem;
            font-weight: 500;
        }

        .hero-illustration {
            margin: 22px auto 14px;
            width: min(100%, 210px);
            display: block;
            filter: drop-shadow(0 12px 20px rgba(127, 224, 219, 0.22));
        }

        .status-stack {
            display: grid;
            gap: 10px;
            margin-bottom: 12px;
            text-align: left;
        }

        .status-alert {
            border-radius: 16px;
            padding: 12px 14px;
            font-size: 0.88rem;
            line-height: 1.45;
        }

        .status-alert.success {
            background: #e8f8ee;
            color: #1d6b40;
            border: 1px solid #bfe8cb;
        }

        .status-alert.error {
            background: #fdecec;
            color: #a33b3b;
            border: 1px solid #f7c4c4;
        }

        .register-form {
            text-align: left;
        }

        .input-group {
            margin-bottom: 12px;
        }

        .input-label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-main);
            font-size: 0.88rem;
            font-weight: 600;
        }

        .input-control,
        .select-control {
            width: 100%;
            min-height: 46px;
            border-radius: 14px;
            border: 1px solid #d8ece9;
            background: #f8fcfb;
            padding: 10px 14px;
            color: #244744;
            font-size: 0.94rem;
            outline: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .input-control:focus,
        .select-control:focus {
            border-color: #72d5cf;
            box-shadow: 0 0 0 4px rgba(114, 213, 207, 0.16);
        }

        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7px;
            align-items: stretch;
        }

        .role-option {
            position: relative;
        }

        .role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-label {
            min-height: 38px;
            border-radius: 12px;
            border: 1px solid #d8ece9;
            background: #f8fcfb;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px 8px;
            text-align: center;
            font-size: 0.76rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s ease;
            line-height: 1.2;
            letter-spacing: 0.01em;
        }

        .role-option input:checked + .role-label {
            background: linear-gradient(180deg, #7dded8 0%, #61cbc4 100%);
            border-color: #61cbc4;
            color: #fff;
            box-shadow: 0 12px 24px rgba(97, 203, 196, 0.24);
        }

        .submit-btn,
        .secondary-btn {
            width: 100%;
            min-height: 46px;
            border: 0;
            border-radius: 14px;
            font-size: 0.96rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .submit-btn {
            margin-top: 8px;
            background: linear-gradient(180deg, #169892 0%, #0b7d77 100%);
            color: #fff;
            box-shadow: 0 12px 24px rgba(11, 125, 119, 0.24);
        }

        .secondary-btn {
            margin-top: 10px;
            background: #fff;
            color: #78bdb8;
            border: 2px solid var(--border-soft);
        }

        .field-error {
            margin-top: 6px;
            color: #c44f4f;
            font-size: 0.78rem;
        }

        .panel-footer {
            margin-top: 12px;
            text-align: center;
            font-size: 0.84rem;
            color: var(--text-muted);
        }

        .panel-footer a {
            color: #15918b;
            font-weight: 600;
            text-decoration: none;
        }

        [hidden] {
            display: none !important;
        }

        @media (max-width: 420px) {
            .card-body {
                padding-left: 18px;
                padding-right: 18px;
            }

            .role-label {
                min-height: 36px;
                font-size: 0.72rem;
                padding: 5px 6px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="mobile-auth-page">
        <div class="register-card">
            <div class="card-top">
            </div>

            <div class="card-body">
                <div class="brand-pill">
                    <img src="{{ asset('images/logo favicon 1.png') }}" alt="Nuist">
                </div>
                <h1 class="welcome-title">Welcome!</h1>
                <p class="welcome-subtitle">Create your account to get started</p>

                {{-- <img
                    class="hero-illustration"
                    src="{{ asset('build/images/verification-img.png') }}"
                    alt="Ilustrasi register Nuist"
                > --}}

                @if (session('status'))
                    <div class="status-stack">
                        <div class="status-alert success">{{ session('status') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="status-stack">
                        <div class="status-alert error">{{ session('error') }}</div>
                    </div>
                @endif

                <form class="register-form" id="mobileRegisterForm" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-group">
                        <label class="input-label" for="name">Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="input-control"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama"
                            required
                        >
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="input-control"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email"
                            required
                        >
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Daftar sebagai</label>
                        <div class="role-grid">
                            <label class="role-option">
                                <input type="radio" name="role" value="pengurus" {{ old('role') == 'pengurus' ? 'checked' : '' }} required>
                                <span class="role-label">Pengurus</span>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="tenaga_pendidik" {{ old('role') == 'tenaga_pendidik' ? 'checked' : '' }} required>
                                <span class="role-label">Tenaga Pendidik</span>
                            </label>
                        </div>
                        @error('role')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group" id="jabatan-group" hidden>
                        <label class="input-label" for="jabatan">Jabatan</label>
                        <input
                            id="jabatan"
                            name="jabatan"
                            type="text"
                            class="input-control"
                            value="{{ old('jabatan') }}"
                            placeholder="Masukkan jabatan"
                        >
                        @error('jabatan')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group" id="asal_sekolah-group" hidden>
                        <label class="input-label" for="asal_sekolah">Asal Sekolah</label>
                        <select id="asal_sekolah" name="asal_sekolah" class="select-control">
                            <option value="">Pilih asal sekolah</option>
                            @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ old('asal_sekolah') == $madrasah->id ? 'selected' : '' }}>
                                    {{ $madrasah->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('asal_sekolah')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="submit-btn" type="submit">Sign Up</button>
                    <a class="secondary-btn" href="{{ route('mobile.login') }}">Login</a>
                </form>

                <div class="panel-footer">
                    Sudah punya akun? <a href="{{ route('mobile.login') }}">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var roleRadios = document.querySelectorAll('input[name="role"]');
            var jabatanGroup = document.getElementById('jabatan-group');
            var asalSekolahGroup = document.getElementById('asal_sekolah-group');
            var jabatanInput = document.getElementById('jabatan');
            var asalSekolahInput = document.getElementById('asal_sekolah');
            var registerForm = document.getElementById('mobileRegisterForm');

            function toggleConditionalFields() {
                var selectedRole = document.querySelector('input[name="role"]:checked');

                if (selectedRole && selectedRole.value === 'pengurus') {
                    jabatanGroup.hidden = false;
                    asalSekolahGroup.hidden = true;
                    jabatanInput.required = true;
                    asalSekolahInput.required = false;
                    asalSekolahInput.value = '';
                    return;
                }

                if (selectedRole && selectedRole.value === 'tenaga_pendidik') {
                    jabatanGroup.hidden = true;
                    asalSekolahGroup.hidden = false;
                    jabatanInput.required = false;
                    asalSekolahInput.required = true;
                    jabatanInput.value = '';
                    return;
                }

                jabatanGroup.hidden = true;
                asalSekolahGroup.hidden = true;
                jabatanInput.required = false;
                asalSekolahInput.required = false;
            }

            roleRadios.forEach(function (radio) {
                radio.addEventListener('change', toggleConditionalFields);
            });

            toggleConditionalFields();

            if (!registerForm) return;

            registerForm.addEventListener('submit', function (event) {
                event.preventDefault();

                var formData = new FormData(registerForm);

                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your registration',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: function () {
                        Swal.showLoading();
                    }
                });

                fetch('{{ route("register") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function (response) {
                    if (!response.ok) {
                        return response.json().then(function (err) {
                            throw err;
                        });
                    }

                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(function () {
                            window.location.href = '{{ route("mobile.login") }}';
                        });
                        return;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: data.message || 'An error occurred during registration',
                        confirmButtonText: 'Try Again'
                    });
                })
                .catch(function (error) {
                    if (error.errors) {
                        var errorMessages = [];
                        for (var field in error.errors) {
                            errorMessages.push(error.errors[field].join(', '));
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessages.join('<br>'),
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'An unexpected error occurred. Please try again.',
                        confirmButtonText: 'OK'
                    });
                });
            });
        });
    </script>
@endsection
