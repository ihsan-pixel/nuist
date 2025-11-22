@extends('layouts.master')

@section('title', 'Edit Profil Madrasah - ' . $madrasah->name)

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        min-height: 25vh;
        position: relative;
        display: flex;
        align-items: center;
        padding: 60px 0;
        color: white;
        margin-bottom: -50px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('{{ asset("images/bg_ppdb4.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        opacity: 0.1;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Main Container */
    .edit-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        position: relative;
        z-index: 3;
        margin-bottom: 2rem;
    }

    /* Header Section */
    .edit-header {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .edit-header h2 {
        margin-bottom: 10px;
        font-weight: 700;
    }

    .edit-header p {
        margin: 0;
        opacity: 0.9;
    }

    /* Form Sections */
    .form-section {
        padding: 40px;
        border-bottom: 1px solid #e9ecef;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .section-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin: 0 auto 15px;
        box-shadow: 0 5px 15px rgba(0, 75, 76, 0.3);
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
        margin: 0;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 8px;
        display: block;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
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

    /* Array Inputs */
    .array-input-container {
        border: 2px dashed #e9ecef;
        border-radius: 10px;
        padding: 20px;
        background: #f8f9fa;
    }

    .array-input-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        gap: 10px;
    }

    .array-input-item input {
        flex: 1;
    }

    .btn-add-array {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-add-array:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .btn-remove-array {
        background: #dc3545;
        border: none;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
    }

    /* File Upload */
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

    /* Buttons */
    .btn-submit {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 15px 50px;
        font-size: 18px;
        border-radius: 30px;
        border: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 76, 0.4);
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        padding: 15px 30px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        margin-right: 15px;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    /* Alert Messages */
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

    /* Info Box */
    .info-box {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 1px solid #2196f3;
        border-radius: 15px;
        padding: 20px;
        margin: 20px 0;
    }

    /* Image Gallery */
    .image-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .image-preview {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }

    .image-item {
        position: relative;
    }

    .image-item .btn {
        position: absolute;
        top: 5px;
        right: 5px;
        padding: 2px 6px;
        font-size: 12px;
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
            margin-bottom: -30px;
        }

        .edit-container {
            border-radius: 15px;
        }

        .edit-header {
            padding: 20px;
        }

        .form-section {
            padding: 20px;
        }

        .section-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-cancel {
            margin-right: 0;
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
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3 animate-fade-in-up">
                    <i class="fas fa-edit text-warning me-3"></i>Edit Profil Madrasah
                </h1>
                <h2 class="lead mb-0 animate-fade-in-up">{{ $madrasah->name }}</h2>
                <h4 class="text-muted animate-fade-in-up">Kelola informasi dan pengaturan madrasah</h4>
            </div>
        </div>
    </div>
</section>

<!-- Edit Form Container -->
<div class="container edit-container animate-bounce-in">
    <!-- Header Section -->
    <div class="edit-header">
        <h2>
            <i class="fas fa-school me-2"></i>Edit Profil Madrasah
        </h2>
        <p>Lengkapi dan perbarui informasi profil {{ $madrasah->name }}</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-custom animate-fade-in-up">
            <h5 class="alert-heading">
                <i class="fas fa-check-circle me-2"></i>Berhasil!
            </h5>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
    @endif

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

    <form action="{{ route('ppdb.lp.update', $madrasah->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h4 class="section-title">Informasi Dasar</h4>
                <p class="section-description">Informasi pokok tentang madrasah</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label required-field">Nama Madrasah</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $madrasah->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kabupaten" class="form-label required-field">Kabupaten</label>
                        <input type="text" class="form-control @error('kabupaten') is-invalid @enderror"
                               id="kabupaten" name="kabupaten" value="{{ old('kabupaten', $madrasah->kabupaten) }}" required>
                        @error('kabupaten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label required-field">Alamat Lengkap</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $madrasah->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tagline" class="form-label">Tagline</label>
                        <input type="text" class="form-control @error('tagline') is-invalid @enderror"
                               id="tagline" name="tagline" value="{{ old('tagline', $madrasah->tagline) }}"
                               placeholder="Contoh: Madrasah Unggul di Bidang Teknologi">
                        @error('tagline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="akreditasi" class="form-label">Akreditasi</label>
                        <select class="form-select @error('akreditasi') is-invalid @enderror" id="akreditasi" name="akreditasi">
                            <option value="">Pilih Akreditasi</option>
                            <option value="A" {{ old('akreditasi', $madrasah->akreditasi) == 'A' ? 'selected' : '' }}>A (Unggul)</option>
                            <option value="B" {{ old('akreditasi', $madrasah->akreditasi) == 'B' ? 'selected' : '' }}>B (Baik)</option>
                            <option value="C" {{ old('akreditasi', $madrasah->akreditasi) == 'C' ? 'selected' : '' }}>C (Cukup)</option>
                        </select>
                        @error('akreditasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_berdiri" class="form-label">Tahun Berdiri</label>
                        <input type="number" class="form-control @error('tahun_berdiri') is-invalid @enderror"
                               id="tahun_berdiri" name="tahun_berdiri" value="{{ old('tahun_berdiri', $madrasah->tahun_berdiri) }}"
                               min="1800" max="{{ date('Y') + 1 }}">
                        @error('tahun_berdiri')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                               id="telepon" name="telepon" value="{{ old('telepon', $madrasah->telepon) }}"
                               placeholder="Contoh: (021) 1234567">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $madrasah->email) }}"
                               placeholder="info@madrasah.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                               id="website" name="website" value="{{ old('website', $madrasah->website) }}"
                               placeholder="https://www.madrasah.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Profil Madrasah -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-school"></i>
                </div>
                <h4 class="section-title">Profil Madrasah</h4>
                <p class="section-description">Deskripsi dan informasi detail tentang madrasah</p>
            </div>

            <div class="form-group">
                <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control @error('deskripsi_singkat') is-invalid @enderror"
                          id="deskripsi_singkat" name="deskripsi_singkat" rows="3"
                          placeholder="Deskripsi singkat tentang madrasah dalam 1-2 paragraf">{{ old('deskripsi_singkat', $madrasah->deskripsi_singkat) }}</textarea>
                @error('deskripsi_singkat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sejarah" class="form-label">Sejarah Madrasah</label>
                <textarea class="form-control @error('sejarah') is-invalid @enderror"
                          id="sejarah" name="sejarah" rows="4"
                          placeholder="Ceritakan sejarah berdirinya madrasah">{{ old('sejarah', $madrasah->sejarah) }}</textarea>
                @error('sejarah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nilai_nilai" class="form-label">Nilai-Nilai</label>
                <textarea class="form-control @error('nilai_nilai') is-invalid @enderror"
                          id="nilai_nilai" name="nilai_nilai" rows="3"
                          placeholder="Nilai-nilai yang dianut oleh madrasah">{{ old('nilai_nilai', $madrasah->nilai_nilai) }}</textarea>
                @error('nilai_nilai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="visi" class="form-label">Visi</label>
                <textarea class="form-control @error('visi') is-invalid @enderror"
                          id="visi" name="visi" rows="3"
                          placeholder="Visi madrasah">{{ old('visi', $madrasah->visi) }}</textarea>
                @error('visi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Misi -->
            <div class="form-group">
                <label class="form-label">Misi</label>
                <div id="misi-container" class="array-input-container">
                    @php $misiArray = old('misi', $madrasah->misi ?? []); @endphp
                    @if(is_array($misiArray) && count($misiArray) > 0)
                        @foreach($misiArray as $index => $misi)
                            <div class="array-input-item">
                                <input type="text" class="form-control @error('misi.' . $index) is-invalid @enderror"
                                       name="misi[]" value="{{ $misi }}" placeholder="Poin misi">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="misi[]" placeholder="Poin misi">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="misi-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Misi
                </button>
                @error('misi.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>


        </div>

        {{-- Opsi Pilihan Ke 2 dipindahkan ke form pendaftaran publik (resources/views/ppdb/daftar.blade.php) --}}

        <!-- Fasilitas dan Keunggulan -->
        <div class="form-section hover-lift">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-star me-2"></i>Fasilitas & Keunggulan
                </h3>
                <p class="section-subtitle">Fasilitas yang tersedia dan keunggulan madrasah</p>
            </div>

            <!-- Fasilitas -->
            <div class="form-group">
                <label class="form-label">Fasilitas</label>
                <div id="fasilitas-container" class="array-input-container">
                    @php $fasilitasArray = old('fasilitas', $madrasah->fasilitas ?? []); @endphp
                    @if(is_array($fasilitasArray) && count($fasilitasArray) > 0)
                        @foreach($fasilitasArray as $index => $fasilitas)
                            <div class="array-input-item">
                                <input type="text" class="form-control @error('fasilitas.' . $index) is-invalid @enderror"
                                       name="fasilitas[]" value="{{ $fasilitas }}" placeholder="Contoh: Laboratorium Komputer">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="fasilitas[]" placeholder="Contoh: Laboratorium Komputer">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="fasilitas-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Fasilitas
                </button>
                @error('fasilitas.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Keunggulan -->
            <div class="form-group">
                <label class="form-label">Keunggulan</label>
                <div id="keunggulan-container" class="array-input-container">
                    @php $keunggulanArray = old('keunggulan', $madrasah->keunggulan ?? []); @endphp
                    @if(is_array($keunggulanArray) && count($keunggulanArray) > 0)
                        @foreach($keunggulanArray as $index => $keunggulan)
                            <div class="array-input-item">
                                <input type="text" class="form-control @error('keunggulan.' . $index) is-invalid @enderror"
                                       name="keunggulan[]" value="{{ $keunggulan }}" placeholder="Keunggulan madrasah">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="keunggulan[]" placeholder="Keunggulan madrasah">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="keunggulan-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Keunggulan
                </button>
                @error('keunggulan.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Jurusan -->
            <div class="form-group">
                <label class="form-label">Jurusan</label>
                <div id="jurusan-container" class="array-input-container">
                    @php $jurusanArray = old('jurusan', $madrasah->jurusan ?? []); @endphp
                    @if(is_array($jurusanArray) && count($jurusanArray) > 0)
                        @foreach($jurusanArray as $index => $jurusan)
                            <div class="array-input-item">
                                <input type="text" class="form-control @error('jurusan.' . $index) is-invalid @enderror"
                                       name="jurusan[]" value="{{ $jurusan }}" placeholder="Contoh: Teknik Informatika">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="jurusan[]" placeholder="Contoh: Teknik Informatika">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="jurusan-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Jurusan
                </button>
                @error('jurusan.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Prestasi -->
            <div class="form-group">
                <label class="form-label">Prestasi</label>
                <div id="prestasi-container" class="array-input-container">
                    @php $prestasiArray = old('prestasi', $madrasah->prestasi ?? []); @endphp
                    @if(is_array($prestasiArray) && count($prestasiArray) > 0)
                        @foreach($prestasiArray as $index => $prestasi)
                            <div class="array-input-item">
                                <input type="text" class="form-control @error('prestasi.' . $index) is-invalid @enderror"
                                       name="prestasi[]" value="{{ $prestasi }}" placeholder="Contoh: Juara 1 Lomba Matematika Tingkat Nasional">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="prestasi[]" placeholder="Contoh: Juara 1 Lomba Matematika Tingkat Nasional">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="prestasi-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Prestasi
                </button>
                @error('prestasi.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Program Unggulan -->
            <div class="form-group">
                <label class="form-label">Program Unggulan</label>
                <div id="program_unggulan-container" class="array-input-container">
                    @php $programUnggulanArray = old('program_unggulan', $madrasah->program_unggulan ?? []); @endphp
                    @if(is_array($programUnggulanArray) && count($programUnggulanArray) > 0)
                        @foreach($programUnggulanArray as $index => $program)
                            <div class="array-input-item">
                                <input type="text" class="form-control @error('program_unggulan.' . $index) is-invalid @enderror"
                                       name="program_unggulan[]" value="{{ $program }}" placeholder="Contoh: Program Tahfidz Al-Quran">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="program_unggulan[]" placeholder="Contoh: Program Tahfidz Al-Quran">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="program_unggulan-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Program Unggulan
                </button>
                @error('program_unggulan.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Ekstrakurikuler -->
            <div class="form-group">
                <label class="form-label">Ekstrakurikuler</label>
                <div id="ekstrakurikuler-container" class="array-input-container">
                    @php $ekstrakurikulerArray = old('ekstrakurikuler', $madrasah->ekstrakurikuler ?? []); @endphp
                    @if(is_array($ekstrakurikulerArray) && count($ekstrakurikulerArray) > 0)
                        @foreach($ekstrakurikulerArray as $index => $ekstra)
                            <div class="array-input-item">
                                <input type="text" class="form-control @error('ekstrakurikuler.' . $index) is-invalid @enderror"
                                       name="ekstrakurikuler[]" value="{{ $ekstra }}" placeholder="Contoh: Pramuka, Futsal, Basket">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="ekstrakurikuler[]" placeholder="Contoh: Pramuka, Futsal, Basket">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="ekstrakurikuler-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Ekstrakurikuler
                </button>
                @error('ekstrakurikuler.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Kepala Sekolah -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h4 class="section-title">Kepala Sekolah</h4>
                <p class="section-description">Informasi tentang kepala sekolah</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kepala_sekolah_nama" class="form-label">Nama Kepala Sekolah</label>
                        <input type="text" class="form-control @error('kepala_sekolah_nama') is-invalid @enderror"
                               id="kepala_sekolah_nama" name="kepala_sekolah_nama"
                               value="{{ old('kepala_sekolah_nama', $madrasah->kepala_sekolah_nama) }}"
                               placeholder="Dr. H. Ahmad Susanto, M.Pd.">
                        @error('kepala_sekolah_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kepala_sekolah_gelar" class="form-label">Gelar</label>
                        <input type="text" class="form-control @error('kepala_sekolah_gelar') is-invalid @enderror"
                               id="kepala_sekolah_gelar" name="kepala_sekolah_gelar"
                               value="{{ old('kepala_sekolah_gelar', $madrasah->kepala_sekolah_gelar) }}"
                               placeholder="M.Pd., S.Pd., dll">
                        @error('kepala_sekolah_gelar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="kepala_sekolah_sambutan" class="form-label">Sambutan Kepala Sekolah</label>
                <textarea class="form-control @error('kepala_sekolah_sambutan') is-invalid @enderror"
                          id="kepala_sekolah_sambutan" name="kepala_sekolah_sambutan" rows="4"
                          placeholder="Sambutan dari kepala sekolah">{{ old('kepala_sekolah_sambutan', $madrasah->kepala_sekolah_sambutan) }}</textarea>
                @error('kepala_sekolah_sambutan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Statistik -->
        <div class="form-section hover-lift">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-chart-bar me-2"></i>Statistik Madrasah
                </h3>
                <p class="section-subtitle">Data jumlah siswa, guru, dan sarana prasarana</p>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_siswa" class="form-label">Jumlah Siswa</label>
                        <input type="number" class="form-control @error('jumlah_siswa') is-invalid @enderror"
                               id="jumlah_siswa" name="jumlah_siswa" value="{{ old('jumlah_siswa', $madrasah->jumlah_siswa) }}" min="0">
                        @error('jumlah_siswa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_guru" class="form-label">Jumlah Guru</label>
                        <input type="number" class="form-control @error('jumlah_guru') is-invalid @enderror"
                               id="jumlah_guru" name="jumlah_guru" value="{{ old('jumlah_guru', $jumlahGuru ?? $madrasah->jumlah_guru ?? '') }}" min="0" readonly>
                        <small class="text-muted">Jumlah guru dihitung otomatis dari data tenaga pendidik</small>
                        @error('jumlah_guru')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_jurusan" class="form-label">Jumlah Jurusan</label>
                        <input type="number" class="form-control @error('jumlah_jurusan') is-invalid @enderror"
                               id="jumlah_jurusan" name="jumlah_jurusan" value="{{ old('jumlah_jurusan', $madrasah->jumlah_jurusan) }}" min="0" readonly>
                        <div class="help-text">Otomatis dihitung dari jumlah jurusan yang diisi</div>
                        @error('jumlah_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_sarana" class="form-label">Jumlah Sarana</label>
                        <input type="number" class="form-control @error('jumlah_sarana') is-invalid @enderror"
                               id="jumlah_sarana" name="jumlah_sarana" value="{{ old('jumlah_sarana', $madrasah->jumlah_sarana) }}" min="0">
                        @error('jumlah_sarana')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Media dan Dokumen -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-images"></i>
                </div>
                <h4 class="section-title">Media & Dokumen</h4>
                <p class="section-description">Upload gambar galeri dan dokumen pendukung</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="video_profile" class="form-label">Link Video Profile</label>
                        <input type="url" class="form-control @error('video_profile') is-invalid @enderror"
                               id="video_profile" name="video_profile" value="{{ old('video_profile', $madrasah->video_profile) }}"
                               placeholder="https://youtube.com/watch?v=...">
                        <div class="help-text">Link YouTube atau video lainnya</div>
                        @error('video_profile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="brosur_pdf" class="form-label">Upload Brosur (PDF)</label>
                        @if($madrasah->brosur_pdf)
                            <div class="mb-2" id="brosur-actions">
                                <a href="{{ asset('uploads/brosur/' . $madrasah->brosur_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Brosur
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBrosur()">
                                    <i class="mdi mdi-delete me-1"></i>Hapus Brosur
                                </button>
                            </div>
                            <input type="file" class="form-control @error('brosur_pdf') is-invalid @enderror"
                                   id="brosur_pdf" name="brosur_pdf" accept=".pdf" style="display: none;">
                            <div class="help-text" id="brosur-help-text">Brosur sudah ada. Klik tombol di atas untuk melihat atau menghapus, atau upload file baru untuk mengganti.</div>
                        @else
                            <input type="file" class="form-control @error('brosur_pdf') is-invalid @enderror"
                                   id="brosur_pdf" name="brosur_pdf" accept=".pdf">
                            <div class="help-text">Maksimal 5MB, format PDF</div>
                        @endif
                        @error('brosur_pdf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <input type="hidden" id="delete_brosur" name="delete_brosur" value="0">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Galeri Foto</label>
            <div class="file-upload-area" id="galeri-upload-area">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="file-upload-text">Klik untuk upload gambar galeri</div>
                    <div class="file-upload-hint">atau drag & drop file gambar (JPG, PNG, GIF) - Maksimal 2MB per file</div>
                    <input type="file" id="galeri_foto" name="galeri_foto[]" multiple accept="image/*" style="display: none;">
                </div>
                @error('galeri_foto.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror

                <!-- Preview existing images -->
                @if($madrasah->galeri_foto && is_array($madrasah->galeri_foto))
                    <div class="image-gallery" id="existing-gallery">
                        @foreach($madrasah->galeri_foto as $index => $image)
                            <div class="image-item position-relative d-inline-block me-2 mb-2" data-image="{{ $image }}">
                                <img src="{{ asset('images/madrasah/galeri/' . $image) }}" alt="Galeri" class="image-preview">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="deleteImage('{{ $image }}', {{ $index }})">
                                    <i class="mdi mdi-close"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Preview new images -->
                <div class="image-gallery" id="new-gallery"></div>
                <input type="hidden" id="deleted_galeri_foto" name="deleted_galeri_foto" value="">
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="form-section">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-cancel">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Array input management
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-array-item') || e.target.closest('.add-array-item')) {
            const button = e.target.classList.contains('add-array-item') ? e.target : e.target.closest('.add-array-item');
            const targetId = button.getAttribute('data-target');
            const container = document.getElementById(targetId);

            const newItem = document.createElement('div');
            newItem.className = 'array-input-item';
            newItem.innerHTML = `
                <input type="text" class="form-control" name="${targetId.replace('-container', '[]')}" placeholder="${getPlaceholderText(targetId)}">
                <button type="button" class="btn btn-remove-array remove-array-item">
                    <i class="mdi mdi-minus"></i>
                </button>
            `;

            // Insert before the last item (which should be empty)
            const items = container.querySelectorAll('.array-input-item');
            if (items.length > 0) {
                container.insertBefore(newItem, items[items.length - 1]);
            } else {
                container.appendChild(newItem);
            }
        }

        if (e.target.classList.contains('remove-array-item') || e.target.closest('.remove-array-item')) {
            const item = e.target.closest('.array-input-item');
            if (item) {
                item.remove();
            }
        }
    });

    function getPlaceholderText(containerId) {
        const placeholders = {
            'misi-container': 'Poin misi',
            'fasilitas-container': 'Contoh: Laboratorium Komputer',
            'keunggulan-container': 'Keunggulan madrasah',
            'jurusan-container': 'Contoh: Teknik Informatika',
            'prestasi-container': 'Contoh: Juara 1 Lomba Matematika Tingkat Nasional',
            'program_unggulan-container': 'Contoh: Program Tahfidz Al-Quran',
            'ekstrakurikuler-container': 'Contoh: Pramuka, Futsal, Basket',
            'ppdb-quota-jurusan-container': 'Nama Jurusan',
            'ppdb-jalur-container': 'Contoh: Jalur Prestasi, Jalur Reguler'
        };
        return placeholders[containerId] || 'Masukkan teks';
    }



    // File upload handling
    const uploadArea = document.getElementById('galeri-upload-area');
    const fileInput = document.getElementById('galeri_foto');
    const newGallery = document.getElementById('new-gallery');

    if (!uploadArea.dataset.clickListenerAdded) {
        uploadArea.addEventListener('click', function (e) {
            if (e.target === this) {
                fileInput.click();
            }
        });
        uploadArea.dataset.clickListenerAdded = "true";
    }

    if (!uploadArea.dataset.dragoverListenerAdded) {
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.dataset.dragoverListenerAdded = "true";
    }

    if (!uploadArea.dataset.dragleaveListenerAdded) {
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        uploadArea.dataset.dragleaveListenerAdded = "true";
    }

    if (!uploadArea.dataset.dropListenerAdded) {
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            handleFiles(files);
        });
        uploadArea.dataset.dropListenerAdded = "true";
    }

    if (!fileInput.dataset.listenerAdded) {
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });
        fileInput.dataset.listenerAdded = "true";
    }

    function handleFiles(files) {
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageItem = document.createElement('div');
                    imageItem.className = 'image-item position-relative d-inline-block me-2 mb-2';
                    imageItem.dataset.fileName = file.name;

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview';
                    img.alt = 'Preview';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                    removeBtn.innerHTML = '<i class="mdi mdi-close"></i>';
                    removeBtn.onclick = function() {
                        removePreviewImage(imageItem, file);
                    };

                    imageItem.appendChild(img);
                    imageItem.appendChild(removeBtn);
                    newGallery.appendChild(imageItem);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function removePreviewImage(imageItem, file) {
        // Remove from DOM
        imageItem.remove();

        // Remove from file input (recreate input with remaining files)
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);

        // Remove the specific file
        const remainingFiles = files.filter(f => f.name !== file.name && f.lastModified !== file.lastModified);

        // Update file input with remaining files
        remainingFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    // Auto-update jumlah jurusan
    function updateJumlahJurusan() {
        const jurusanInputs = document.querySelectorAll('input[name="jurusan[]"]');
        let count = 0;
        jurusanInputs.forEach(input => {
            if (input.value.trim() !== '') {
                count++;
            }
        });
        document.getElementById('jumlah_jurusan').value = count;
    }

    // Listen for changes in jurusan inputs
    document.addEventListener('input', function(e) {
        if (e.target.name === 'jurusan[]') {
            updateJumlahJurusan();
        }
    });

    // Also listen for array item additions/removals
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-array-item') || e.target.classList.contains('remove-array-item') ||
            e.target.closest('.add-array-item') || e.target.closest('.remove-array-item')) {
            setTimeout(updateJumlahJurusan, 100); // Small delay to ensure DOM updates
        }
    });

    // Initialize on page load
    updateJumlahJurusan();

    // Copy to clipboard function
    window.copyToClipboard = function(event, text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check me-1"></i>Berhasil Disalin!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }

    // Delete brosur function
    window.deleteBrosur = function() {
        if (confirm('Apakah Anda yakin ingin menghapus brosur ini?')) {
            document.getElementById('delete_brosur').value = '1';
            document.getElementById('brosur-actions').style.display = 'none';
            document.getElementById('brosur_pdf').style.display = 'block';
            document.getElementById('brosur_pdf').required = true;
            document.getElementById('brosur-help-text').innerHTML = 'Maksimal 5MB, format PDF';
        }
    }



    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Clean up empty array inputs
        const arrayInputs = form.querySelectorAll('input[name$="[]"]');
        arrayInputs.forEach(input => {
            if (!input.value.trim()) {
                input.remove();
            }
        });
    });
});
</script>
@endpush
@endsection
