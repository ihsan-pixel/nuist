@extends('layouts.master-without-nav')

@section('title')
@lang('translation.Login')
@endsection

@section('css')
{{-- CSS dari Vite --}}
{{-- @vite(['resources/scss/bootstrap.scss', 'resources/scss/icons.scss', 'resources/scss/app.scss', 'resources/js/app.js']) --}}

{{-- CSS vendor (taruh file vendor di public/build/libs/) --}}
<link rel="stylesheet" href="{{ asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
@endsection

@section('body')
<body class="auth-body-bg">
@endsection

@section('content')
<div>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-xl-9">
                <div class="auth-full-bg pt-lg-5 p-4">
                    <div class="w-100">
                        <div class="d-flex h-100 flex-column">
                            <div class="p-4 mt-auto">
                                <div class="row justify-content-center">
                                    <div class="col-lg-7">
                                        <div class="text-center">
                                            <!-- Bisa ditambah carousel/testimonial kalau mau -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-xl-3">
                <div class="auth-full-page-content p-md-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4 mb-md-5">
                                <a href="" class="d-block auth-logo">
                                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" height="90" class="auth-logo-dark">
                                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" height="70" class="auth-logo-light">
                                </a>
                            </div>
                            <div class="my-auto">
                                <div>
                                    <h2 class="text-success">Selamat Datang</h2>
                                    <p class="text-muted">Di Aplikasi Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</p>
                                </div>

                                <div class="mt-4">
                                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="mb-3">
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

                                        <div class="mb-3">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                                <input type="password" name="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       id="userpassword" placeholder="Enter password"
                                                       aria-label="Password" aria-describedby="password-addon">
                                                <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
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

                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-success waves-effect waves-light" type="submit">Log In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-4 mt-md-5 text-center">
                                <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Nuist. Crafted by LP. Ma'arif NU PWNU DIY</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container-fluid -->
</div>

<style>
    .auth-full-bg {
        background: url("{{ asset('images/a.png') }}") no-repeat center center !important;
        background-size: cover !important;
    }
    .bg-overlay {
        background: none !important;
    }
</style>
@endsection

@section('script')
{{-- JS vendor (taruh di public/build/libs/) --}}
<script src="{{ asset('build/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('build/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>

{{-- JS custom --}}
<script src="{{ asset('js/auth-2-carousel.init.js') }}"></script>
@endsection

