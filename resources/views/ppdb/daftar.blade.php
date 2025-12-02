@extends('layouts.master-without-nav')

@section('title', 'Formulir PPDB ' . $ppdbSetting->nama_sekolah)

@section('css')
<link rel="stylesheet" href="{{ asset('css/ppdb-custom.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Enhanced Registration Form Styles */
    .hero-section {
        background: #ffffff;
        min-height: 20vh;
        position: relative;
        display: flex;
        align-items: center;
        padding: 60px 0;
        color: #0f854a;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .registration-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.342);
        overflow: hidden;
        margin-top: -50px;
        position: relative;
        z-index: 3;
    }

    /* Background for space below hero section */
    body {
        background: url('{{ asset("images/bg_ppdb4.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;

        min-height: 100vh;
    }

    /* Ensure content area has proper background overlay */
    .content-wrapper {
        background: rgba(255, 255, 255, 0.9);
        min-height: 100vh;
        padding-bottom: 50px;
    }

    .progress-header {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 30px;
        text-align: center;
        margin: 0 -40px 0 -40px;
        border-radius: 20px 20px 0 0;
    }

    .progress-steps {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 5px;
    }

    .step {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        color: white;
        font-weight: bold;
        font-size: 11px;
        position: relative;
        transition: all 0.3s ease;
    }

    .step-line {
        height: 2px;
        width: 40px;
        background: rgba(255,255,255,0.3);
        margin: 0 5px;
    }

    .step.active {
        background: #efaa0c;
        color: #004b4c;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(239, 170, 12, 0.4);
    }

    .step.completed {
        background: #28a745;
        color: white;
    }

    .step-line {
        height: 2px;
        width: 60px;
        background: rgba(255,255,255,0.3);
        margin: 0 10px;
    }

    .step.completed + .step-line {
        background: #28a745;
    }

    .form-section {
        padding: 40px;
        display: none;
    }

    .form-section.active {
        display: block;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #efaa0c;
        box-shadow: 0 0 0 0.2rem rgba(239, 170, 12, 0.25);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
    }

    .file-upload-area {
        border: 2px dashed #e9ecef;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8f9fa;
    }

    .file-upload-area:hover {
        border-color: #efaa0c;
        background: rgba(239, 170, 12, 0.05);
    }

    .file-upload-area.dragover {
        border-color: #efaa0c;
        background: rgba(239, 170, 12, 0.1);
        transform: scale(1.02);
    }

    .file-upload-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .file-upload-text {
        color: #004b4c;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .file-upload-hint {
        color: #6c757d;
        font-size: 14px;
    }

    .file-preview {
        margin-top: 15px;
        padding: 10px;
        background: #e9ecef;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .file-preview-container {
        margin-top: 15px;
    }

    .file-name {
        font-size: 14px;
        color: #004b4c;
        font-weight: 500;
    }

    .file-remove {
        color: #dc3545;
        cursor: pointer;
        font-size: 18px;
    }

    .btn-navigation {
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-prev {
        background: #6c757d;
        color: white;
    }

    .btn-prev:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-next {
        background: linear-gradient(135deg, #efaa0c 0%, #ff8f00 100%);
        color: white;
    }

    .btn-next:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 170, 12, 0.4);
    }

    .btn-submit {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 15px 50px;
        font-size: 18px;
        border-radius: 30px;
        border: none;
        font-weight: 700;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 76, 0.4);
    }

    .alert-custom {
        border-radius: 15px;
        border: none;
        padding: 20px;
        margin-bottom: 30px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
    }

    .info-box {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 20px;
        margin: 20px 0;
    }

    .section-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .section-title {
        color: #004b4c;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .section-description {
        color: #666;
        font-size: 1rem;
        margin-bottom: 0;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }

    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.8s ease-out;
    }

    .animate-bounce-in {
        animation: bounceIn 1s ease-out;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 30vh;
            padding: 40px 0;
        }

        .registration-container {
            margin-top: -30px;
            border-radius: 15px;
        }

        .progress-header {
            padding: 20px;
        }

        .progress-steps {
            flex-direction: column;
            gap: 10px;
        }

        .step-line {
            width: 2px;
            height: 30px;
            margin: 0;
        }

        .form-section {
            padding: 20px;
        }

        .btn-navigation {
            padding: 10px 20px;
            font-size: 14px;
        }

        .file-upload-area {
            padding: 20px;
        }
    }

    /* Loading state */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #004b4c;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3 animate-fade-in-up">
                    <i class="fas fa-edit text-warning me-3"></i>Formulir Pendaftaran
                </h1>
                <h2 class="lead mb-0 animate-fade-in-up">{{ $ppdbSetting->nama_sekolah }}</h2>
                <h4 class="text-muted animate-fade-in-up">Tahun Pelajaran {{ $ppdbSetting->tahun }}/{{ $ppdbSetting->tahun + 1 }}</h4>
            </div>
        </div>
    </div>
</section>

<!-- Registration Form -->
<div class="container registration-container animate-bounce-in">
    <!-- Progress Header -->
    <div class="progress-header">
        <div class="progress-steps">
            <div class="step active" data-step="1">
                <i class="fas fa-user"></i>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="2">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="3">
                <i class="fas fa-file-upload"></i>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="4">
                <i class="fas fa-plus-circle"></i>
            </div>
        </div>
        <h3 id="progress-title" class="mb-0">Data Pribadi</h3>
        <p id="progress-description" class="mb-0 opacity-75">Langkah 1 dari 4</p>
    </div>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-custom animate-fade-in-up">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan
            </h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-custom animate-fade-in-up">
            <h5 class="alert-heading">
                <i class="fas fa-check-circle me-2"></i>Pendaftaran Berhasil!
            </h5>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-custom animate-fade-in-up">
            <h5 class="alert-heading">
                <i class="fas fa-times-circle me-2"></i>Pendaftaran Gagal
            </h5>
            <p class="mb-0">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Form -->
    <form id="registrationForm" action="{{ route('ppdb.store', $ppdbSetting->slug) }}" method="POST" enctype="multipart/form-data" class="animate-fade-in-up">
        @csrf

        <!-- Step 1: Data Pribadi -->
        <div id="step1" class="form-section active">
            <div class="text-center mb-4">
                <div class="section-icon">
                    <i class="fas fa-user"></i>
                </div>
                <h4 class="section-title">Data Pribadi</h4>
                <p class="section-description">Masukkan informasi pribadi Anda dengan lengkap dan benar</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap" class="form-label">
                            <i class="fas fa-signature me-2"></i>Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                               id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                               placeholder="Nama lengkap sesuai KTP/Akte Kelahiran" required>
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nik" class="form-label">
                            <i class="fas fa-id-card me-2"></i>NIK <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control @error('nik') is-invalid @enderror"
                               id="nik" name="nik" value="{{ old('nik') }}"
                               placeholder="Nomor Induk Kependudukan (16 digit)" maxlength="16" pattern="[0-9]{16}" required>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tempat_lahir" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Tempat Lahir <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                               id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                               placeholder="Kota/Kabupaten tempat lahir" required>
                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_lahir" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>Tanggal Lahir <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               max="{{ date('Y-m-d') }}" required>
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label">
                            <i class="fas fa-venus-mars me-2"></i>Jenis Kelamin <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="agama" class="form-label">
                            <i class="fas fa-pray me-2"></i>Agama <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('agama') is-invalid @enderror"
                                id="agama" name="agama" required>
                            <option value="">-- Pilih Agama --</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen Protestan" {{ old('agama') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                            <option value="Kristen Katolik" {{ old('agama') == 'Kristen Katolik' ? 'selected' : '' }}>Kristen Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nisn" class="form-label">
                            <i class="fas fa-id-card me-2"></i>NISN <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control @error('nisn') is-invalid @enderror"
                               id="nisn" name="nisn" value="{{ old('nisn') }}"
                               placeholder="Nomor Induk Siswa Nasional" required
                               onblur="checkNISNAvailability()">
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asal_sekolah" class="form-label">
                            <i class="fas fa-school me-2"></i>Asal Sekolah <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                               id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                               placeholder="Nama sekolah asal" required>
                        @error('asal_sekolah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Data Alamat -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="provinsi" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Provinsi <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('provinsi') is-invalid @enderror"
                                id="provinsi" name="provinsi" required>
                            <option value="">-- Pilih Provinsi --</option>
                            <!-- Provinsi akan diisi melalui JavaScript -->
                        </select>
                        @error('provinsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kabupaten" class="form-label">
                            <i class="fas fa-city me-2"></i>Kabupaten/Kota <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('kabupaten') is-invalid @enderror"
                                id="kabupaten" name="kabupaten" disabled required>
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            <!-- Kabupaten akan diisi melalui JavaScript -->
                        </select>
                        @error('kabupaten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kecamatan" class="form-label">
                            <i class="fas fa-building me-2"></i>Kecamatan <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('kecamatan') is-invalid @enderror"
                                id="kecamatan" name="kecamatan" disabled required>
                            <option value="">-- Pilih Kecamatan --</option>
                            <!-- Kecamatan akan diisi melalui JavaScript -->
                        </select>
                        @error('kecamatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="desa" class="form-label">
                            <i class="fas fa-home me-2"></i>Desa/Kelurahan <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('desa') is-invalid @enderror"
                                id="desa" name="desa" disabled required>
                            <option value="">-- Pilih Desa/Kelurahan --</option>
                            <!-- Desa akan diisi melalui JavaScript -->
                        </select>
                        @error('desa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="alamat_lengkap" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror"
                                  id="alamat_lengkap" name="alamat_lengkap" rows="3"
                                  placeholder="Alamat lengkap sesuai KTP" required>{{ old('alamat_lengkap') }}</textarea>
                        @error('alamat_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-next btn-navigation" onclick="nextStep(2)">
                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Pilihan Program -->
        <div id="step2" class="form-section">
            <div class="text-center mb-4">
                <div class="section-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4 class="section-title">Pilihan Program</h4>
                <p class="section-description">Pilih jalur dan jurusan yang sesuai dengan minat Anda</p>
            </div>

            <div class="form-group">
                <label for="ppdb_jalur_id" class="form-label">
                    <i class="fas fa-route me-2"></i>Jalur Pendaftaran <span class="text-danger">*</span>
                </label>
                <select class="form-control @error('ppdb_jalur_id') is-invalid @enderror"
                        id="ppdb_jalur_id" name="ppdb_jalur_id" required>
                    <option value="">-- Pilih Jalur Pendaftaran --</option>
                    @forelse($jalurs as $jalur)
                        <option value="{{ $jalur->id }}" {{ old('ppdb_jalur_id') == $jalur->id ? 'selected' : '' }}>
                            {{ $jalur->nama_jalur }}
                            @if($jalur->keterangan)
                                - {{ $jalur->keterangan }}
                            @endif
                        </option>
                    @empty
                        <option disabled>Tidak ada jalur tersedia</option>
                    @endforelse
                </select>
                @error('ppdb_jalur_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jurusan_pilihan" class="form-label">
                    <i class="fas fa-book me-2"></i>Pilihan Jurusan <span class="text-danger">*</span>
                </label>
                <select class="form-control @error('jurusan_pilihan') is-invalid @enderror"
                        id="jurusan_pilihan" name="jurusan_pilihan" required>
                    <option value="">-- Pilih Jurusan --</option>
                    @php
                        $jurusanList = $ppdbSetting->jurusan ?? [];
                        if (is_string($jurusanList)) {
                            $jurusanList = json_decode($jurusanList, true) ?? [];
                        }
                    @endphp
                    @if(is_array($jurusanList) && count($jurusanList) > 0)
                        @foreach($jurusanList as $jurusan)
                            @if(is_array($jurusan) && isset($jurusan['nama']))
                                <option value="{{ $jurusan['nama'] }}" {{ old('jurusan_pilihan') == $jurusan['nama'] ? 'selected' : '' }}>
                                    {{ $jurusan['nama'] }}
                                </option>
                            @else
                                <option value="{{ $jurusan }}" {{ old('jurusan_pilihan') == $jurusan ? 'selected' : '' }}>
                                    {{ $jurusan }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
                @error('jurusan_pilihan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Opsi Pilihan Ke 2 (wajib diisi jika memilih opsi) -->
            @php
                // Ambil daftar madrasah pada kabupaten yang sama dengan sekolah utama (kecuali sekolah utama itu sendiri)
                $sekolahLain = [];
                if (isset($ppdbSetting->sekolah) && $ppdbSetting->sekolah && $ppdbSetting->sekolah->kabupaten) {
                    $sekolahLain = \App\Models\Madrasah::where('kabupaten', $ppdbSetting->sekolah->kabupaten)
                        ->where('id', '!=', $ppdbSetting->sekolah->id)
                        ->orderBy('name')
                        ->get();
                }

                $sekolahLainData = [];
                foreach($sekolahLain as $s) {
                    // pastikan jurusan disimpan sebagai array di model (atau JSON)
                    $sekolahLainData[$s->id] = is_array($s->jurusan) ? $s->jurusan : (is_string($s->jurusan) ? json_decode($s->jurusan, true) ?? [] : []);
                }
            @endphp

            <!-- Contact fields: wajib dan ditempatkan di atas opsi ke-2 -->
            <div class="row mt-3 mb-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ppdb_nomor_whatsapp_siswa" class="form-label">Nomor WhatsApp Siswa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ppdb_nomor_whatsapp_siswa') is-invalid @enderror"
                               id="ppdb_nomor_whatsapp_siswa" name="ppdb_nomor_whatsapp_siswa"
                               value="{{ old('ppdb_nomor_whatsapp_siswa') }}"
                               placeholder="081234567890" required>
                        @error('ppdb_nomor_whatsapp_siswa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ppdb_email_siswa" class="form-label">Email Siswa <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('ppdb_email_siswa') is-invalid @enderror"
                               id="ppdb_email_siswa" name="ppdb_email_siswa"
                               value="{{ old('ppdb_email_siswa') }}"
                               placeholder="siswa@example.com" required>
                        @error('ppdb_email_siswa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-2">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="enable_opsi_ke_2" {{ old('use_opsi_ke_2') ? 'checked' : '' }}>
                    <label class="form-check-label" for="enable_opsi_ke_2">Tambahkan Opsi Pilihan Ke 2</label>
                </div>
                <input type="hidden" name="use_opsi_ke_2" id="use_opsi_ke_2" value="{{ old('use_opsi_ke_2', 0) }}">

                <div id="opsi-ke-2-section" style="display: {{ old('use_opsi_ke_2') ? 'block' : 'none' }};">
                    <label for="ppdb_opsi_pilihan_ke_2" class="form-label">
                        <i class="fas fa-people-arrows me-2"></i>Opsi Pilihan Ke 2 (Nama Sekolah)
                    </label>
                    <select id="ppdb_opsi_pilihan_ke_2" name="ppdb_opsi_pilihan_ke_2" class="form-control @error('ppdb_opsi_pilihan_ke_2') is-invalid @enderror">
                        <option value="">-- Pilih Sekolah Alternatif --</option>
                        @foreach($sekolahLain as $s)
                            <option value="{{ $s->id }}" {{ old('ppdb_opsi_pilihan_ke_2') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('ppdb_opsi_pilihan_ke_2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text mt-1">Hanya madrasah pada kabupaten yang sama ditampilkan.</div>

                    <!-- Jurusan Sekolah Pilihan (muncul hanya saat opsi ke-2 aktif) -->
                    <div class="form-group mt-3">
                        <label for="ppdb_jurusan_pilihan_alt" class="form-label">Jurusan Sekolah Pilihan</label>
                        <select id="ppdb_jurusan_pilihan_alt" name="ppdb_jurusan_pilihan_alt[]" class="form-control" multiple size="4">
                            {{-- Opsi akan terisi otomatis setelah memilih sekolah alternatif --}}
                        </select>
                        <div class="help-text mt-1">Pilih minimal 1 jurusan dari sekolah alternatif yang dipilih.</div>
                    </div>
                </div>
            </div>

            <script>
                // Data jurusan untuk setiap sekolah alternatif
                window._sekolahAlternatifData = {!! json_encode($sekolahLainData) !!};

                document.addEventListener('DOMContentLoaded', function() {
                    const sekolahSelect = document.getElementById('ppdb_opsi_pilihan_ke_2');
                    const jurusanSelect = document.getElementById('ppdb_jurusan_pilihan_alt');
                    const enableCheckbox = document.getElementById('enable_opsi_ke_2');
                    const useHidden = document.getElementById('use_opsi_ke_2');
                    const opsiSection = document.getElementById('opsi-ke-2-section');

                    function populateJurusan(sekolahId) {
                        // Clear
                        jurusanSelect.innerHTML = '';

                        if (!sekolahId) return;

                        const jurusanList = window._sekolahAlternatifData[sekolahId] || [];
                        if (jurusanList.length === 0) {
                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.text = 'Tidak ada jurusan terdaftar pada sekolah ini';
                            jurusanSelect.appendChild(opt);
                            return;
                        }

                            jurusanList.forEach(j => {
                                const opt = document.createElement('option');
                                opt.value = j;
                                opt.text = j;
                                jurusanSelect.appendChild(opt);
                            });

                            // Also include the primary jurusan_pilihan if present (so user can keep same jurusan in opsi ke-2)
                            try {
                                const primaryJurusan = (document.getElementById('jurusan_pilihan') && document.getElementById('jurusan_pilihan').value) ? document.getElementById('jurusan_pilihan').value : null;
                                if (primaryJurusan) {
                                    // If not already included, prepend it and mark selected
                                    let exists = Array.from(jurusanSelect.options).some(o => o.value === primaryJurusan);
                                    if (!exists) {
                                        const optPrimary = document.createElement('option');
                                        optPrimary.value = primaryJurusan;
                                        optPrimary.text = primaryJurusan + ' (Jurusan pilihan utama)';
                                        optPrimary.selected = true;
                                        jurusanSelect.insertBefore(optPrimary, jurusanSelect.firstChild);
                                    } else {
                                        // mark it selected if present
                                        Array.from(jurusanSelect.options).forEach(o => { if (o.value === primaryJurusan) o.selected = true; });
                                    }
                                }
                            } catch (err) {
                                console.warn('Could not include primary jurusan into opsi ke-2 list', err);
                            }
                    }

                    // Toggle show/hide opsi section and required attributes
                    function setOpsiEnabled(enabled) {
                        if (enabled) {
                            opsiSection.style.display = 'block';
                            useHidden.value = '1';
                            document.getElementById('ppdb_opsi_pilihan_ke_2').setAttribute('required', 'required');
                            jurusanSelect.setAttribute('required', 'required');
                            document.getElementById('ppdb_nomor_whatsapp_siswa').setAttribute('required', 'required');
                            document.getElementById('ppdb_email_siswa').setAttribute('required', 'required');
                        } else {
                            opsiSection.style.display = 'none';
                            useHidden.value = '0';
                            document.getElementById('ppdb_opsi_pilihan_ke_2').removeAttribute('required');
                            jurusanSelect.removeAttribute('required');
                            document.getElementById('ppdb_nomor_whatsapp_siswa').removeAttribute('required');
                            document.getElementById('ppdb_email_siswa').removeAttribute('required');
                        }
                    }

                    // Initialize
                    setOpsiEnabled(enableCheckbox.checked || useHidden.value === '1');

                    // Populate if old value exists
                    if (sekolahSelect && sekolahSelect.value) populateJurusan(sekolahSelect.value);

                    if (sekolahSelect) sekolahSelect.addEventListener('change', function() {
                        populateJurusan(this.value);
                    });

                    enableCheckbox.addEventListener('change', function() {
                        setOpsiEnabled(this.checked);
                    });
                });
            </script>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-prev btn-navigation" onclick="prevStep(1)">
                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-next btn-navigation" onclick="nextStep(3)">
                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: Upload Berkas -->
        <div id="step3" class="form-section">
            <div class="text-center mb-4">
                <div class="section-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <h4 class="section-title">Upload Berkas</h4>
                <p class="section-description">Unggah dokumen pendukung dalam format PDF, JPG, atau PNG</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users me-2"></i>Kartu Keluarga (KK) <span class="text-danger">*</span>
                        </label>
                        <div class="file-upload-area" onclick="document.getElementById('berkas_kk').click()">
                            <div class="file-upload-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="file-upload-text">Klik untuk memilih file KK</div>
                            <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB)</div>
                        </div>
                        <input type="file" id="berkas_kk" name="berkas_kk"
                               accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
                        @error('berkas_kk')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="berkas_kk_preview" class="file-preview" style="display: none;"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-certificate me-2"></i>Ijazah/SKHUN <span class="text-danger">*</span>
                        </label>
                        <div class="file-upload-area" onclick="document.getElementById('berkas_ijazah').click()">
                            <div class="file-upload-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="file-upload-text">Klik untuk memilih file Ijazah</div>
                            <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB)</div>
                        </div>
                        <input type="file" id="berkas_ijazah" name="berkas_ijazah"
                               accept=".pdf,.jpg,.jpeg,.png" style="display: none;" required>
                        @error('berkas_ijazah')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="berkas_ijazah_preview" class="file-preview" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <div class="info-box">
                <h6 class="mb-2">
                    <i class="fas fa-info-circle text-primary me-2"></i>Penting!
                </h6>
                <p class="mb-0">Pastikan semua data yang Anda isi sudah benar dan sesuai dengan dokumen asli. Berkas yang diunggah harus jelas dan dapat dibaca.</p>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-prev btn-navigation" onclick="prevStep(2)">
                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-next btn-navigation" onclick="nextStep(4)">
                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 4: Data Tambahan -->
        <div id="step4" class="form-section">
            <div class="text-center mb-4">
                <div class="section-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h4 class="section-title">Data Tambahan</h4>
                <p class="section-description">Lengkapi informasi tambahan untuk kelengkapan data</p>
            </div>

            <!-- Data Orang Tua -->
            <div class="info-box mb-4">
                <h6 class="mb-3">
                    <i class="fas fa-users text-primary me-2"></i>Data Orang Tua
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_ayah" class="form-label">
                                <i class="fas fa-user-tie me-2"></i>Nama Ayah
                            </label>
                            <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                                   id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}"
                                   placeholder="Nama lengkap ayah">
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_ibu" class="form-label">
                                <i class="fas fa-female me-2"></i>Nama Ibu
                            </label>
                            <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
                                   id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}"
                                   placeholder="Nama lengkap ibu">
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan_ayah" class="form-label">
                                <i class="fas fa-briefcase me-2"></i>Pekerjaan Ayah
                            </label>
                            <select class="form-control @error('pekerjaan_ayah') is-invalid @enderror"
                                    id="pekerjaan_ayah" name="pekerjaan_ayah">
                                <option value="">-- Pilih Pekerjaan Ayah --</option>
                                <option value="Tidak Bekerja" {{ old('pekerjaan_ayah') == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="Pegawai Negeri Sipil (PNS)" {{ old('pekerjaan_ayah') == 'Pegawai Negeri Sipil (PNS)' ? 'selected' : '' }}>Pegawai Negeri Sipil (PNS)</option>
                                <option value="Pegawai Swasta" {{ old('pekerjaan_ayah') == 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                <option value="Wiraswasta" {{ old('pekerjaan_ayah') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Petani" {{ old('pekerjaan_ayah') == 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Nelayan" {{ old('pekerjaan_ayah') == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                <option value="Pedagang" {{ old('pekerjaan_ayah') == 'Pedagang' ? 'selected' : '' }}>Pedagang</option>
                                <option value="Buruh" {{ old('pekerjaan_ayah') == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                <option value="Guru/Dosen" {{ old('pekerjaan_ayah') == 'Guru/Dosen' ? 'selected' : '' }}>Guru/Dosen</option>
                                <option value="Dokter" {{ old('pekerjaan_ayah') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                <option value="Perawat" {{ old('pekerjaan_ayah') == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                                <option value="Polisi" {{ old('pekerjaan_ayah') == 'Polisi' ? 'selected' : '' }}>Polisi</option>
                                <option value="TNI" {{ old('pekerjaan_ayah') == 'TNI' ? 'selected' : '' }}>TNI</option>
                                <option value="Sopir" {{ old('pekerjaan_ayah') == 'Sopir' ? 'selected' : '' }}>Sopir</option>
                                <option value="Karyawan" {{ old('pekerjaan_ayah') == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="Lainnya" {{ old('pekerjaan_ayah') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('pekerjaan_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan_ibu" class="form-label">
                                <i class="fas fa-briefcase me-2"></i>Pekerjaan Ibu
                            </label>
                            <select class="form-control @error('pekerjaan_ibu') is-invalid @enderror"
                                    id="pekerjaan_ibu" name="pekerjaan_ibu">
                                <option value="">-- Pilih Pekerjaan Ibu --</option>
                                <option value="Tidak Bekerja" {{ old('pekerjaan_ibu') == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="Ibu Rumah Tangga" {{ old('pekerjaan_ibu') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                <option value="Pegawai Negeri Sipil (PNS)" {{ old('pekerjaan_ibu') == 'Pegawai Negeri Sipil (PNS)' ? 'selected' : '' }}>Pegawai Negeri Sipil (PNS)</option>
                                <option value="Pegawai Swasta" {{ old('pekerjaan_ibu') == 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                <option value="Wiraswasta" {{ old('pekerjaan_ibu') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Petani" {{ old('pekerjaan_ibu') == 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Nelayan" {{ old('pekerjaan_ibu') == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                <option value="Pedagang" {{ old('pekerjaan_ibu') == 'Pedagang' ? 'selected' : '' }}>Pedagang</option>
                                <option value="Buruh" {{ old('pekerjaan_ibu') == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                <option value="Guru/Dosen" {{ old('pekerjaan_ibu') == 'Guru/Dosen' ? 'selected' : '' }}>Guru/Dosen</option>
                                <option value="Dokter" {{ old('pekerjaan_ibu') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                <option value="Perawat" {{ old('pekerjaan_ibu') == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                                <option value="Polisi" {{ old('pekerjaan_ibu') == 'Polisi' ? 'selected' : '' }}>Polisi</option>
                                <option value="TNI" {{ old('pekerjaan_ibu') == 'TNI' ? 'selected' : '' }}>TNI</option>
                                <option value="Sopir" {{ old('pekerjaan_ibu') == 'Sopir' ? 'selected' : '' }}>Sopir</option>
                                <option value="Karyawan" {{ old('pekerjaan_ibu') == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="Lainnya" {{ old('pekerjaan_ibu') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('pekerjaan_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_hp_orangtua" class="form-label">
                                <i class="fas fa-phone me-2"></i>Nomor HP Orangtua/Wali
                            </label>
                            <input type="text" class="form-control @error('nomor_hp_orangtua') is-invalid @enderror"
                                   id="nomor_hp_orangtua" name="nomor_hp_orangtua" value="{{ old('nomor_hp_orangtua') }}"
                                   placeholder="081234567890">
                            @error('nomor_hp_orangtua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Akademik -->
            <div class="info-box mb-4">
                <h6 class="mb-3">
                    <i class="fas fa-graduation-cap text-primary me-2"></i>Data Akademik
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="npsn_sekolah_asal" class="form-label">
                                <i class="fas fa-hashtag me-2"></i>NPSN Sekolah Asal
                            </label>
                            <input type="text" class="form-control @error('npsn_sekolah_asal') is-invalid @enderror"
                                   id="npsn_sekolah_asal" name="npsn_sekolah_asal" value="{{ old('npsn_sekolah_asal') }}"
                                   placeholder="Nomor Pokok Sekolah Nasional">
                            @error('npsn_sekolah_asal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun_lulus" class="form-label">
                                <i class="fas fa-calendar-check me-2"></i>Tahun Lulus
                            </label>
                            <input type="number" class="form-control @error('tahun_lulus') is-invalid @enderror"
                                   id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus') }}"
                                   placeholder="Tahun lulus" min="2000" max="{{ date('Y') }}">
                            @error('tahun_lulus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calculator me-2"></i>Nilai Raport per Semester
                            </label>
                            <div class="row">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="col-6 col-md-2 mb-2">
                                        <label class="form-label small">Semester {{ $i }}</label>
                                        <input type="number"
                                               class="form-control form-control-sm semester-grade"
                                               name="nilai_semester_{{ $i }}"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               placeholder="85.5"
                                               value="{{ old('nilai_semester_' . $i) }}"
                                               onchange="calculateAverage()">
                                    </div>
                                @endfor
                            </div>
                            <div class="mt-2">
                                <label class="form-label small fw-bold">Rata-rata Nilai Raport (Otomatis)</label>
                                <input type="number"
                                       class="form-control"
                                       name="rata_rata_nilai_raport"
                                       id="rata_rata_nilai_raport"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       placeholder="Otomatis dihitung"
                                       readonly>
                                <small class="text-muted">Rata-rata dihitung otomatis dari 5 semester</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_ijazah" class="form-label">
                                <i class="fas fa-certificate me-2"></i>Nomor Ijazah (Opsional jika sudah memiliki)
                            </label>
                            <input type="text" class="form-control @error('nomor_ijazah') is-invalid @enderror"
                                   id="nomor_ijazah" name="nomor_ijazah" value="{{ old('nomor_ijazah') }}"
                                   placeholder="Nomor ijazah">
                            @error('nomor_ijazah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_skhun" class="form-label">
                                <i class="fas fa-file-alt me-2"></i>Nomor SKHUN
                            </label>
                            <input type="text" class="form-control @error('nomor_skhun') is-invalid @enderror"
                                   id="nomor_skhun" name="nomor_skhun" value="{{ old('nomor_skhun') }}"
                                   placeholder="Nomor SKHUN">
                            @error('nomor_skhun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rencana Setelah Lulus -->
            <div class="info-box mb-4">
                <h6 class="mb-3">
                    <i class="fas fa-route text-primary me-2"></i>Rencana Setelah Lulus
                </h6>
                <div class="form-group">
                    <label for="rencana_lulus" class="form-label">
                        <i class="fas fa-compass me-2"></i>Rencana Setelah Lulus <span class="text-danger">*</span>
                    </label>
                    <select class="form-control @error('rencana_lulus') is-invalid @enderror"
                            id="rencana_lulus" name="rencana_lulus" required>
                        <option value="">-- Pilih Rencana --</option>
                        <option value="kuliah" {{ old('rencana_lulus') == 'kuliah' ? 'selected' : '' }}>Melanjutkan ke Perguruan Tinggi</option>
                        <option value="kerja" {{ old('rencana_lulus') == 'kerja' ? 'selected' : '' }}>Bekerja</option>
                    </select>
                    @error('rencana_lulus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dokumen Tambahan -->
            <div class="info-box mb-4">
                <h6 class="mb-3">
                    <i class="fas fa-folder-open text-primary me-2"></i>Dokumen Tambahan (Opsional)
                </h6>
                <p class="text-muted small mb-3">Unggah dokumen pendukung tambahan jika tersedia</p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-baby me-2"></i>Akta Kelahiran
                            </label>
                            <div class="file-upload-area" onclick="document.getElementById('berkas_akta_kelahiran').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-baby"></i>
                                </div>
                                <div class="file-upload-text">Klik untuk memilih file Akta</div>
                                <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB)</div>
                            </div>
                            <input type="file" id="berkas_akta_kelahiran" name="berkas_akta_kelahiran"
                                   accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                            @error('berkas_akta_kelahiran')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="berkas_akta_kelahiran_preview" class="file-preview" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-file-invoice me-2"></i>Raport Terakhir
                            </label>
                            <div class="file-upload-area" onclick="document.getElementById('berkas_raport').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div class="file-upload-text">Klik untuk memilih file Raport</div>
                                <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB)</div>
                            </div>
                            <input type="file" id="berkas_raport" name="berkas_raport"
                                   accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                            @error('berkas_raport')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="berkas_raport_preview" class="file-preview" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-trophy me-2"></i>Sertifikat Prestasi
                            </label>
                            <div class="file-upload-area" onclick="document.getElementById('berkas_sertifikat_prestasi').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="file-upload-text">Klik untuk memilih file Sertifikat</div>
                                <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB per file)</div>
                            </div>
                            <input type="file" id="berkas_sertifikat_prestasi" name="berkas_sertifikat_prestasi[]"
                                   accept=".pdf,.jpg,.jpeg,.png" multiple style="display: none;">
                            @error('berkas_sertifikat_prestasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('berkas_sertifikat_prestasi.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="berkas_sertifikat_prestasi_preview" class="file-preview-container"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave me-2"></i>KIP/PKH
                            </label>
                            <div class="file-upload-area" onclick="document.getElementById('berkas_kip_pkh').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="file-upload-text">Klik untuk memilih file KIP/PKH</div>
                                <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB)</div>
                            </div>
                            <input type="file" id="berkas_kip_pkh" name="berkas_kip_pkh"
                                   accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                            @error('berkas_kip_pkh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="berkas_kip_pkh_preview" class="file-preview" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-id-card me-2"></i>KTP Ayah
                            </label>
                            <div class="file-upload-area" onclick="document.getElementById('berkas_ktp_ayah').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="file-upload-text">Klik untuk memilih file KTP Ayah</div>
                                <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB)</div>
                            </div>
                            <input type="file" id="berkas_ktp_ayah" name="berkas_ktp_ayah"
                                   accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                            @error('berkas_ktp_ayah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="berkas_ktp_ayah_preview" class="file-preview" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-id-card me-2"></i>KTP Ibu
                            </label>
                            <div class="file-upload-area" onclick="document.getElementById('berkas_ktp_ibu').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="file-upload-text">Klik untuk memilih file KTP Ibu</div>
                                <div class="file-upload-hint">PDF, JPG, atau PNG (Maksimal 2MB)</div>
                            </div>
                            <input type="file" id="berkas_ktp_ibu" name="berkas_ktp_ibu"
                                   accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                            @error('berkas_ktp_ibu')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="berkas_ktp_ibu_preview" class="file-preview" style="display: none;"></div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-prev btn-navigation" onclick="prevStep(3)">
                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                </button>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Pendaftaran
                </button>
            </div>
        </div>
        </form>
    </div>

    <!-- Back to School Button -->
    <div class="text-center py-4">
        <a href="{{ route('ppdb.sekolah', $ppdbSetting->slug) }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Halaman Sekolah
        </a>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="text-center">
            <div class="spinner mb-3"></div>
            <h5 class="text-primary">Memproses Pendaftaran...</h5>
            <p class="text-muted">Mohon tunggu sebentar</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentStep = 1;
const totalSteps = 3;

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    showStep(1);
    updateProgress();
    setupFileUploads();
});

// Navigation functions
function nextStep(step) {
    if (validateCurrentStep()) {
        currentStep = step;
        showStep(step);
        updateProgress();
    }
}

function prevStep(step) {
    currentStep = step;
    showStep(step);
    updateProgress();
}

function showStep(step) {
    document.querySelectorAll('.form-section').forEach(section => {
        section.style.display = "none";
        section.classList.remove('active');
    });

    const target = document.getElementById('step' + step);
    target.style.display = "block";
    target.classList.add('active');
}

function updateProgress() {
    // Update step indicators
    document.querySelectorAll('.step').forEach((step, index) => {
        const stepNumber = index + 1;
        step.classList.remove('active', 'completed');

        if (stepNumber === currentStep) {
            step.classList.add('active');
        } else if (stepNumber < currentStep) {
            step.classList.add('completed');
        }
    });

    // Update progress text
    const titles = ['Data Pribadi', 'Pilihan Program', 'Upload Berkas', 'Data Tambahan'];
    const descriptions = ['Langkah 1 dari 4', 'Langkah 2 dari 4', 'Langkah 3 dari 4', 'Langkah 4 dari 4'];

    document.getElementById('progress-title').textContent = titles[currentStep - 1];
    document.getElementById('progress-description').textContent = descriptions[currentStep - 1];
}

function validateCurrentStep() {
    const currentSection = document.getElementById('step' + currentStep);
    const requiredFields = currentSection.querySelectorAll('input[required], select[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        // Jika file input
        if (field.type === "file") {
            if (field.files.length === 0) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        // Jika text atau select
        else {
            if (!field.value || field.value.trim() === "") {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
    });

    // Cek validasi NISN pada Step 1
    if (currentStep === 1) {
        const nisnInput = document.getElementById('nisn');
        if (nisnInput.classList.contains('is-invalid')) {
            isValid = false;
        }

        // Validasi NIK harus 16 digit
        const nikInput = document.getElementById('nik');
        const nikValue = nikInput.value.trim();
        if (nikValue && nikValue.length !== 16) {
            nikInput.classList.add('is-invalid');
            // Tambahkan pesan error jika belum ada
            let errorDiv = nikInput.parentNode.querySelector('.invalid-feedback');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                nikInput.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = 'NIK harus terdiri dari 16 digit angka.';
            isValid = false;
        } else if (nikValue.length === 16) {
            nikInput.classList.remove('is-invalid');
        }
    }

    return isValid;
}

// File upload handling
function setupFileUploads() {
    // KK file upload
    document.getElementById('berkas_kk').addEventListener('change', function(e) {
        handleFileSelect(e.target, 'berkas_kk_preview');
    });

    // Ijazah file upload
    document.getElementById('berkas_ijazah').addEventListener('change', function(e) {
        handleFileSelect(e.target, 'berkas_ijazah_preview');
    });

    // Additional document uploads for step 4
    const additionalFiles = [
        'berkas_akta_kelahiran', 'berkas_raport', 'berkas_sertifikat_prestasi', 'berkas_kip_pkh',
        'berkas_ktp_ayah', 'berkas_ktp_ibu'
    ];

    additionalFiles.forEach(fileId => {
        const element = document.getElementById(fileId);
        if (element) {
            element.addEventListener('change', function(e) {
                if (fileId === 'berkas_sertifikat_prestasi') {
                    handleMultipleFileSelect(e.target, fileId + '_preview');
                } else {
                    handleFileSelect(e.target, fileId + '_preview');
                }
            });
        }
    });

    // Drag and drop
    document.querySelectorAll('.file-upload-area').forEach(area => {
        area.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        area.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        area.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');

            const input = this.nextElementSibling;
            const files = e.dataTransfer.files;

            if (files.length > 0) {
                input.files = files;
                handleFileSelect(input, input.id + '_preview');
            }
        });
    });
}

function handleFileSelect(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);

    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File terlalu besar. Maksimal 2MB.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }

        // Validate file type
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan PDF, JPG, atau PNG.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }

        // Show preview
        preview.innerHTML = `
            <span class="file-name">
                <i class="fas fa-file me-2"></i>${file.name}
            </span>
            <span class="file-remove" onclick="removeFile('${input.id}')">
                <i class="fas fa-times"></i>
            </span>
        `;
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }
}

function removeFile(inputId) {
    document.getElementById(inputId).value = '';
    document.getElementById(inputId + '_preview').style.display = 'none';
}

function handleMultipleFileSelect(input, previewId) {
    const files = input.files;
    const preview = document.getElementById(previewId);

    if (files.length > 0) {
        preview.innerHTML = '';

        Array.from(files).forEach((file, index) => {
            // Validate file size (2MB per file)
            if (file.size > 2 * 1024 * 1024) {
                alert(`File "${file.name}" terlalu besar. Maksimal 2MB per file.`);
                input.value = '';
                preview.innerHTML = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert(`Format file "${file.name}" tidak didukung. Gunakan PDF, JPG, atau PNG.`);
                input.value = '';
                preview.innerHTML = '';
                return;
            }

            // Create preview element
            const filePreview = document.createElement('div');
            filePreview.className = 'file-preview';
            filePreview.innerHTML = `
                <span class="file-name">
                    <i class="fas fa-file me-2"></i>${file.name}
                </span>
                <span class="file-remove" onclick="removeMultipleFile('${input.id}', ${index})">
                    <i class="fas fa-times"></i>
                </span>
            `;
            preview.appendChild(filePreview);
        });
    } else {
        preview.innerHTML = '';
    }
}

function removeMultipleFile(inputId, fileIndex) {
    const input = document.getElementById(inputId);
    const files = Array.from(input.files);
    const preview = document.getElementById(inputId + '_preview');

    // Remove the file from the FileList (create new FileList without the removed file)
    files.splice(fileIndex, 1);

    // Create a new DataTransfer to update the input
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;

    // Update preview
    handleMultipleFileSelect(input, inputId + '_preview');
}

// Form submission
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    if (!validateAllSteps()) {
        e.preventDefault();
        alert('Mohon lengkapi semua data yang diperlukan.');
        return;
    }

    // Show loading overlay
    document.getElementById('loadingOverlay').style.display = 'flex';
});

function validateAllSteps() {
    let isValid = true;

    // Check all required fields
    document.querySelectorAll('input[required], select[required]').forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// NISN availability check
function checkNISNAvailability() {
    const nisnInput = document.getElementById('nisn');
    const nisn = nisnInput.value.trim();

    if (nisn.length === 0) return;

    // Remove existing feedback
    const existingFeedback = nisnInput.parentNode.querySelector('.nisn-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }

    // Show loading
    const feedbackDiv = document.createElement('div');
    feedbackDiv.className = 'nisn-feedback mt-2';
    feedbackDiv.innerHTML = '<small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i>Memeriksa NISN...</small>';
    nisnInput.parentNode.appendChild(feedbackDiv);

    // Make AJAX request
    fetch(`/ppdb/check-nisn/${nisn}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            feedbackDiv.innerHTML = '';

            if (data.exists) {
                feedbackDiv.innerHTML = '<small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>NISN sudah terdaftar</small>';
                nisnInput.classList.add('is-invalid');
                nisnInput.classList.remove('is-valid');
            } else {
                feedbackDiv.innerHTML = '<small class="text-success"><i class="fas fa-check-circle me-1"></i>NISN tersedia</small>';
                nisnInput.classList.add('is-valid');
                nisnInput.classList.remove('is-invalid');
            }
        })
        .catch(error => {
            console.error('Error checking NISN:', error);
            feedbackDiv.innerHTML = '<small class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>Gagal memeriksa NISN</small>';
        });
}

// ====================================================
//   REGISTER ALL FUNCTIONS TO GLOBAL SCOPE (FIX UTAMA)
// ====================================================

window.nextStep = nextStep;
window.prevStep = prevStep;
window.checkNISNAvailability = checkNISNAvailability;
window.removeFile = removeFile;

// Lightweight array add/remove support for jurusan alternatif (Opsi Pilihan Ke 2)
document.addEventListener('click', function(e) {
    // Add new array item
    if (e.target.classList.contains('add-array-item') || e.target.closest('.add-array-item')) {
        const button = e.target.classList.contains('add-array-item') ? e.target : e.target.closest('.add-array-item');
        const targetId = button.getAttribute('data-target');
        const container = document.getElementById(targetId);
        if (!container) return;

        // Only support our alternative jurusan container in this script
        if (targetId === 'ppdb-jurusan-pilihan-container-alt') {
            const newItem = document.createElement('div');
            newItem.className = 'array-input-item d-flex gap-2';
            newItem.innerHTML = `\
                <input type="text" class="form-control" name="ppdb_jurusan_pilihan_alt[]" placeholder="Jurusan dari sekolah pilihan (opsional)">\
                <button type="button" class="btn btn-remove-array remove-array-item">\
                    <i class="mdi mdi-minus"></i>\
                </button>`;
            // insert before the last item if there is an empty template
            const items = container.querySelectorAll('.array-input-item');
            if (items.length > 0) {
                container.insertBefore(newItem, items[items.length - 1]);
            } else {
                container.appendChild(newItem);
            }
        }
    }

    // Remove array item
    if (e.target.classList.contains('remove-array-item') || e.target.closest('.remove-array-item')) {
        const item = e.target.closest('.array-input-item');
        if (item) item.remove();
    }
});

// Address dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const provinsiSelect = document.getElementById('provinsi');
    const kabupatenSelect = document.getElementById('kabupaten');
    const kecamatanSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa');

    // Load provinces
    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(response => response.json())
        .then(provinces => {
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.name;
                option.setAttribute('data-name', province.name);
                provinsiSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading provinces:', error));

    // Load regencies when province changes
    provinsiSelect.addEventListener('change', function() {
        const provinceId = this.value;
        const provinceName = this.options[this.selectedIndex].getAttribute('data-name');

        // Reset dependent selects
        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';

        if (provinceId) {
            kabupatenSelect.disabled = false;
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                .then(response => response.json())
                .then(regencies => {
                    regencies.forEach(regency => {
                        const option = document.createElement('option');
                        option.value = regency.id;
                        option.textContent = regency.name;
                        option.setAttribute('data-name', regency.name);
                        kabupatenSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading regencies:', error));
        } else {
            kabupatenSelect.disabled = true;
            kecamatanSelect.disabled = true;
            desaSelect.disabled = true;
        }
    });

    // Load districts when regency changes
    kabupatenSelect.addEventListener('change', function() {
        const regencyId = this.value;

        // Reset dependent selects
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';

        if (regencyId) {
            kecamatanSelect.disabled = false;
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`)
                .then(response => response.json())
                .then(districts => {
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        option.setAttribute('data-name', district.name);
                        kecamatanSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading districts:', error));
        } else {
            kecamatanSelect.disabled = true;
            desaSelect.disabled = true;
        }
    });

    // Load villages when district changes
    kecamatanSelect.addEventListener('change', function() {
        const districtId = this.value;

        // Reset dependent select
        desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';

        if (districtId) {
            desaSelect.disabled = false;
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
                .then(response => response.json())
                .then(villages => {
                    villages.forEach(village => {
                        const option = document.createElement('option');
                        option.value = village.id;
                        option.textContent = village.name;
                        option.setAttribute('data-name', village.name);
                        desaSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading villages:', error));
        } else {
            desaSelect.disabled = true;
        }
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
@endsection
