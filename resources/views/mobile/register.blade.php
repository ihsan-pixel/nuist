@extends('layouts.master-without-nav')

@section('title')
    Daftar - Nuist Mobile
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    @include('mobile._auth-styles')
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
