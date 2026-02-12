@extends('layouts.master-without-nav')

@section('title')
Register - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY
@endsection

@section('css')
<link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
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
                <h1 class="login-title">REGISTER</h1>
                <p class="login-subtitle">Create your account to get started.</p>

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

                <form class="login-form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" id="name" name="name"
                               placeholder="Enter Name" autocomplete="name" autofocus required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" id="email"
                               placeholder="Enter Email" autocomplete="email" required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label class="form-label">Daftar sebagai <span class="text-danger">*</span></label>
                        <div class="role-selection">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="pengurus" value="pengurus" {{ old('role') == 'pengurus' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="pengurus">
                                    Pengurus
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="tenaga_pendidik" value="tenaga_pendidik" {{ old('role') == 'tenaga_pendidik' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="tenaga_pendidik">
                                    Tenaga Pendidik
                                </label>
                            </div>
                        </div>
                        @error('role')
                        <span class="invalid-feedback" role="alert" style="display: block;">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group" id="jabatan-group" style="display: none;">
                        <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror"
                               value="{{ old('jabatan') }}" id="jabatan" name="jabatan"
                               placeholder="Enter Jabatan">
                        @error('jabatan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group" id="asal_sekolah-group" style="display: none;">
                        <label for="asal_sekolah" class="form-label">Asal Sekolah <span class="text-danger">*</span></label>
                        <select class="form-control @error('asal_sekolah') is-invalid @enderror" id="asal_sekolah" name="asal_sekolah">
                            <option value="">Pilih Asal Sekolah</option>
                            @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ old('asal_sekolah') == $madrasah->id ? 'selected' : '' }}>
                                    {{ $madrasah->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('asal_sekolah')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <button class="btn btn-primary login-btn" type="submit">Register</button>
                </form>

                <div class="mt-3 text-center">
                    <p class="mb-0">Already have an account? <a href="{{ url('login') }}" class="text-primary">Login here</a></p>
                </div>

                <div class="mt-2 text-center">
                    <p class="mb-0"><a href="{{ route('landing') }}" class="text-primary">Kembali ke Halaman Utama</a></p>
                </div>

                <div class="footer-text">
                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Nuist. Crafted by LP. Ma'arif NU PWNU DIY</p>
                </div>
            </div>
        </div>

        <!-- Illustration Section -->
        <div class="illustration-section">
            <div class="illustration-content">
                <h2>Join Our Platform!</h2>
                <p>Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</p>
                <div class="illustration-placeholder">
                    <img src="{{ asset('images/verification-img.png') }}" alt="Register Illustration" class="illustration-image">
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

    .password-input {
        padding-right: 50px !important;
    }

    .password-input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-toggle-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #6c757d;
        cursor: pointer;
        z-index: 2;
        padding: 5px;
    }

    .password-toggle-btn:hover {
        color: #004b4c;
    }

    .role-selection {
        display: flex;
        gap: 20px;
    }

    .form-check {
        margin-bottom: 0;
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

        .role-selection {
            flex-direction: column;
            gap: 10px;
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
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Role selection logic
    const roleRadios = document.querySelectorAll('input[name="role"]');
    const jabatanGroup = document.getElementById('jabatan-group');
    const asalSekolahGroup = document.getElementById('asal_sekolah-group');
    const jabatanInput = document.getElementById('jabatan');
    const asalSekolahInput = document.getElementById('asal_sekolah');

    function toggleConditionalFields() {
        const selectedRole = document.querySelector('input[name="role"]:checked');
        if (selectedRole) {
            if (selectedRole.value === 'pengurus') {
                jabatanGroup.style.display = 'block';
                asalSekolahGroup.style.display = 'none';
                jabatanInput.required = true;
                asalSekolahInput.required = false;
                asalSekolahInput.value = '';
            } else if (selectedRole.value === 'tenaga_pendidik') {
                jabatanGroup.style.display = 'none';
                asalSekolahGroup.style.display = 'block';
                jabatanInput.required = false;
                asalSekolahInput.required = true;
                jabatanInput.value = '';
            }
        } else {
            jabatanGroup.style.display = 'none';
            asalSekolahGroup.style.display = 'none';
            jabatanInput.required = false;
            asalSekolahInput.required = false;
        }
    }

    roleRadios.forEach(radio => {
        radio.addEventListener('change', toggleConditionalFields);
    });

    // Initial check
    toggleConditionalFields();

    // Handle form submission with AJAX
    const registerForm = document.querySelector('.login-form');
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your registration',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
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
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw err;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to login page
                    window.location.href = '{{ route("login") }}';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: data.message || 'An error occurred during registration',
                    confirmButtonText: 'Try Again'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Handle validation errors
            if (error.errors) {
                let errorMessages = [];
                for (let field in error.errors) {
                    errorMessages.push(error.errors[field].join(', '));
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorMessages.join('<br>'),
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'An unexpected error occurred. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred. Please try again.',
                confirmButtonText: 'OK'
            });
        });
    });
});

// ==== Force reload register page if cached by Service Worker ====
if ('serviceWorker' in navigator) {
    // Ensure register page is not taken from cache
    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for (let registration of registrations) {
            registration.active?.postMessage({ type: 'CLEAR_REGISTER_CACHE' });
        }
    });

    // If SW is still active and trying to take cache for register
    caches.keys().then(function(names) {
        for (let name of names) {
            caches.delete(name);
        }
    });
}

// ==== Disable browser back cache (bypass 419 issue) ====
if (window.history && window.history.pushState) {
    window.history.pushState('forward', null, '');
    window.onpopstate = function () {
        window.location.href = '/register';
    };
}
</script>
@endsection

