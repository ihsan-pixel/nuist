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
<link href="{{ asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
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

                <form class="login-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="user_type" class="form-label">Pilih Jenis Pendaftaran <span class="text-danger">*</span></label>
                        <select class="form-control @error('user_type') is-invalid @enderror" id="user_type" name="user_type" required>
                            <option value="">Pilih Jenis Pendaftaran</option>
                            <option value="pengurus" {{ old('user_type') == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                            <option value="staff" {{ old('user_type') == 'staff' ? 'selected' : '' }}>Kepala Sekolah/Tenaga Pendidik</option>
                        </select>
                        @error('user_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group" id="jabatan_field" style="display: none;">
                        <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror"
                               value="{{ old('jabatan') }}" id="jabatan" name="jabatan"
                               placeholder="Masukkan Jabatan">
                        @error('jabatan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group" id="sekolah_field" style="display: none;">
                        <label for="sekolah_asal" class="form-label">Sekolah Asal <span class="text-danger">*</span></label>
                        <select class="form-control @error('sekolah_asal') is-invalid @enderror" id="sekolah_asal" name="sekolah_asal">
                            <option value="">Pilih Sekolah Asal</option>
                            @foreach(\App\Models\Madrasah::all() as $madrasah)
                            <option value="{{ $madrasah->id }}" {{ old('sekolah_asal') == $madrasah->id ? 'selected' : '' }}>{{ $madrasah->nama_madrasah }}</option>
                            @endforeach
                        </select>
                        @error('sekolah_asal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group registration-fields" style="display: none;">
                        <label for="useremail" class="form-label">Email <span class="text-danger">*</span></label>
                        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" id="useremail"
                               placeholder="Enter Email" autocomplete="email" autofocus required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group registration-fields" style="display: none;">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" id="name" name="name"
                               placeholder="Enter Name" autocomplete="name" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group registration-fields" style="display: none;">
                        <label for="userpassword" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="password-input-container">
                            <input type="password" name="password"
                                class="form-control password-input @error('password') is-invalid @enderror"
                                id="userpassword" placeholder="Enter password"
                                aria-label="Password" autocomplete="new-password" required>
                            <button type="button" class="btn password-toggle-btn" id="togglePassword" aria-label="Lihat password">
                                <i class="mdi mdi-eye-outline"></i>
                            </button>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group registration-fields" style="display: none;">
                        <label for="confirmpassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="password-input-container">
                            <input type="password" name="password_confirmation"
                                class="form-control password-input @error('password_confirmation') is-invalid @enderror"
                                id="confirmpassword" placeholder="Confirm password"
                                aria-label="Confirm Password" autocomplete="new-password" required>
                            <button type="button" class="btn password-toggle-btn" id="toggleConfirmPassword" aria-label="Lihat password">
                                <i class="mdi mdi-eye-outline"></i>
                            </button>
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>



                    <button class="btn btn-primary login-btn registration-fields" type="submit" style="display: none;">Register</button>
                </form>

                <div class="mt-3 text-center">
                    <p class="mb-0">Already have an account? <a href="{{ url('login') }}" class="text-primary">Login</a></p>
                </div>

                <div class="footer-text">
                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Nuist. Crafted by LP. Ma'arif NU PWNU DIY</p>
                </div>
            </div>
        </div>

        <!-- Illustration Section -->
        <div class="illustration-section">
            <div class="illustration-content">
                <h2>Welcome!</h2>
                <p>Join Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</p>
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

    .input-group-text {
        background: #f8f9fa;
        border-left: none;
    }

    .input-group .form-control {
        border-right: none;
    }

    .input-group .form-control:focus {
        border-right: 1px solid #dee2e6;
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

    .text-primary {
        color: #004b4c !important;
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
<script src="{{ asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User type selection handler
    const userTypeSelect = document.getElementById('user_type');
    const jabatanField = document.getElementById('jabatan_field');
    const sekolahField = document.getElementById('sekolah_field');
    const jabatanInput = document.getElementById('jabatan');
    const sekolahSelect = document.getElementById('sekolah_asal');
    const registrationFields = document.querySelectorAll('.registration-fields');
    const registerButton = document.querySelector('.login-btn[type="submit"]');

    // Form inputs that need to be checked
    const emailInput = document.getElementById('useremail');
    const nameInput = document.getElementById('name');
    const passwordInput = document.getElementById('userpassword');
    const confirmPasswordInput = document.getElementById('confirmpassword');

    function checkFormCompletion() {
        const userType = userTypeSelect.value;
        if (!userType) return false;

        const email = emailInput.value.trim();
        const name = nameInput.value.trim();
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        let isComplete = email && name && password && confirmPassword;

        if (userType === 'pengurus') {
            const jabatan = jabatanInput.value.trim();
            isComplete = isComplete && jabatan;
        } else if (userType === 'staff') {
            const sekolah = sekolahSelect.value;
            isComplete = isComplete && sekolah;
        }

        return isComplete;
    }

    function toggleRegisterButton() {
        if (checkFormCompletion()) {
            registerButton.style.display = 'block';
        } else {
            registerButton.style.display = 'none';
        }
    }

    function toggleFields() {
        const selectedValue = userTypeSelect.value;

        if (selectedValue) {
            // Show all registration fields
            registrationFields.forEach(field => {
                if (!field.classList.contains('login-btn')) {
                    field.style.display = 'block';
                }
            });

            if (selectedValue === 'pengurus') {
                jabatanField.style.display = 'block';
                sekolahField.style.display = 'none';
                jabatanInput.required = true;
                sekolahSelect.required = false;
            } else if (selectedValue === 'staff') {
                jabatanField.style.display = 'none';
                sekolahField.style.display = 'block';
                jabatanInput.required = false;
                sekolahSelect.required = true;
            }
        } else {
            // Hide all registration fields
            registrationFields.forEach(field => {
                field.style.display = 'none';
            });
            jabatanField.style.display = 'none';
            sekolahField.style.display = 'none';
            jabatanInput.required = false;
            sekolahSelect.required = false;
        }

        toggleRegisterButton();
    }

    if (userTypeSelect) {
        userTypeSelect.addEventListener('change', toggleFields);
        // Initial check in case of form validation errors
        toggleFields();
    }

    // Add event listeners to form inputs to check completion
    [emailInput, nameInput, passwordInput, confirmPasswordInput, jabatanInput, sekolahSelect].forEach(input => {
        if (input) {
            input.addEventListener('input', toggleRegisterButton);
            input.addEventListener('change', toggleRegisterButton);
        }
    });

    // Password toggle for password field
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('userpassword');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // Toggle icon
            icon.classList.toggle('mdi-eye-outline', !isPassword);
            icon.classList.toggle('mdi-eye-off-outline', isPassword);
        });
    }

    // Password toggle for confirm password field
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('confirmpassword');

    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const isPassword = confirmPasswordInput.type === 'password';
            confirmPasswordInput.type = isPassword ? 'text' : 'password';

            // Toggle icon
            icon.classList.toggle('mdi-eye-outline', !isPassword);
            icon.classList.toggle('mdi-eye-off-outline', isPassword);
        });
    }
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

