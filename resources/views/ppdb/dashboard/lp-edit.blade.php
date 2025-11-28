@extends('layouts.master')

@section('title', 'Edit Profil Madrasah - ' . $ppdbSetting->nama_sekolah)

@push('css')
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .welcome-section {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2);
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50px, -50px);
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .form-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 2rem;
    }

    .section-header {
        border-bottom: 2px solid #004b4c;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        color: #004b4c;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #004b4c;
        box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.25);
    }

    .array-input-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }

    .array-input-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .array-input-item input {
        flex: 1;
        margin-right: 0.5rem;
    }

    .btn-add-array {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .btn-add-array:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        color: white;
    }

    .btn-remove-array {
        background: #dc3545;
        border: none;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .file-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: #004b4c;
        background: rgba(0, 75, 76, 0.05);
    }

    .file-upload-area.dragover {
        border-color: #004b4c;
        background: rgba(0, 75, 76, 0.1);
    }

    .btn-submit {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        margin-right: 1rem;
    }

    .btn-cancel:hover {
        background: #5a6268;
        color: white;
    }

    .required-field::after {
        content: ' *';
        color: #dc3545;
        font-weight: bold;
    }

    .help-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .image-preview {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
        border-radius: 4px;
        margin: 0.25rem;
    }

    .image-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .image-item {
        position: relative;
    }

    .image-item .btn {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
        line-height: 1;
    }

    .btn-outline-primary {
        border-color: #004b4c;
        color: #004b4c;
    }

    .btn-outline-primary:hover {
        background-color: #004b4c;
        border-color: #004b4c;
        color: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        transform: translateY(-1px);
        color: white;
    }

    .text-dark {
        color: #004b4c !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .fw-medium {
        font-weight: 500;
    }

    .section-wrapper {
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .welcome-section {
            padding: 1.5rem;
        }

        .form-section {
            padding: 1rem;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .btn-cancel {
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="section-wrapper mb-4">
        <div class="welcome-section animate-fade-in">
            <div class="welcome-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-2">
                            <i class="mdi mdi-pencil me-2"></i>
                            Edit Profil Madrasah
                        </h2>
                        <p class="mb-0 opacity-75">Lengkapi informasi profil {{ $ppdbSetting->nama_sekolah }}</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left me-1"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show hover-lift" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show hover-lift" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ppdb.lp.update', $ppdbSetting->sekolah_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <div class="form-section hover-lift">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-information-outline me-2"></i>Informasi Dasar
                </h3>
                <p class="section-subtitle">Informasi pokok tentang madrasah</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Madrasah</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $ppdbSetting->sekolah->name }}" readonly>
                        <small class="text-muted">Nama madrasah diambil otomatis dari database dan tidak dapat diubah</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kabupaten" class="form-label">Kabupaten</label>
                        <input type="text" class="form-control" id="kabupaten" name="kabupaten" value="{{ $ppdbSetting->sekolah->kabupaten }}" readonly>
                        <small class="text-muted">Kabupaten diambil otomatis dari database dan tidak dapat diubah</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" readonly>{{ $ppdbSetting->sekolah->alamat }}</textarea>
                <small class="text-muted">Alamat lengkap diambil otomatis dari database dan tidak dapat diubah</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tagline" class="form-label">Tagline</label>
                        <input type="text" class="form-control @error('tagline') is-invalid @enderror"
                               id="tagline" name="tagline" value="{{ old('tagline', $ppdbSetting->tagline ?? '') }}"
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
                            <option value="A" {{ old('akreditasi', $ppdbSetting->akreditasi ?? '') == 'A' ? 'selected' : '' }}>A (Unggul)</option>
                            <option value="B" {{ old('akreditasi', $ppdbSetting->akreditasi ?? '') == 'B' ? 'selected' : '' }}>B (Baik)</option>
                            <option value="C" {{ old('akreditasi', $ppdbSetting->akreditasi ?? '') == 'C' ? 'selected' : '' }}>C (Cukup)</option>
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
                               id="tahun_berdiri" name="tahun_berdiri" value="{{ old('tahun_berdiri', $ppdbSetting->tahun_berdiri ?? '') }}"
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
                               id="telepon" name="telepon" value="{{ old('telepon', $ppdbSetting->telepon) }}"
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
                               id="email" name="email" value="{{ old('email', $ppdbSetting->email ?? '') }}"
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
                               id="website" name="website" value="{{ old('website', $ppdbSetting->website ?? '') }}"
                               placeholder="https://www.madrasah.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Profil Madrasah -->
        <div class="form-section hover-lift">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-school me-2"></i>Profil Madrasah
                </h3>
                <p class="section-subtitle">Deskripsi dan informasi detail tentang madrasah</p>
            </div>

            <div class="form-group">
                <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control @error('deskripsi_singkat') is-invalid @enderror"
                          id="deskripsi_singkat" name="deskripsi_singkat" rows="3"
                          placeholder="Deskripsi singkat tentang madrasah dalam 1-2 paragraf">{{ old('deskripsi_singkat', $ppdbSetting->deskripsi_singkat ?? '') }}</textarea>
                @error('deskripsi_singkat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sejarah" class="form-label">Sejarah Madrasah</label>
                <textarea class="form-control @error('sejarah') is-invalid @enderror"
                          id="sejarah" name="sejarah" rows="4"
                          placeholder="Ceritakan sejarah berdirinya madrasah">{{ old('sejarah', $ppdbSetting->sejarah ?? '') }}</textarea>
                @error('sejarah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nilai_nilai" class="form-label">Nilai-Nilai</label>
                <textarea class="form-control @error('nilai_nilai') is-invalid @enderror"
                          id="nilai_nilai" name="nilai_nilai" rows="3"
                          placeholder="Nilai-nilai yang dianut oleh madrasah">{{ old('nilai_nilai', $ppdbSetting->nilai_nilai ?? '') }}</textarea>
                @error('nilai_nilai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="visi" class="form-label">Visi</label>
                <textarea class="form-control @error('visi') is-invalid @enderror"
                          id="visi" name="visi" rows="3"
                          placeholder="Visi madrasah">{{ old('visi', $ppdbSetting->visi ?? '') }}</textarea>
                @error('visi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Misi -->
            <div class="form-group">
                <label class="form-label">Misi</label>
                <div id="misi-container" class="array-input-container">
                    @php $misiArray = old('misi', $ppdbSetting->misi ?? []); @endphp
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
                    @php
                        $fasilitasArray = old('fasilitas', $ppdbSetting->fasilitas ?? []);
                        if (is_string($fasilitasArray)) {
                            $fasilitasArray = json_decode($fasilitasArray, true) ?? [];
                        }
                    @endphp
                    @if(is_array($fasilitasArray) && count($fasilitasArray) > 0)
                        @foreach($fasilitasArray as $index => $fasilitas)
                            <div class="array-input-item mb-3 p-3 border rounded existing-fasilitas" data-index="{{ $index }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('fasilitas.' . $index . '.name') is-invalid @enderror"
                                               name="fasilitas[{{ $index }}][name]" value="{{ is_array($fasilitas) ? ($fasilitas['name'] ?? '') : $fasilitas }}" placeholder="Nama Fasilitas">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('fasilitas.' . $index . '.description') is-invalid @enderror"
                                               name="fasilitas[{{ $index }}][description]" value="{{ is_array($fasilitas) ? ($fasilitas['description'] ?? '') : '' }}" placeholder="Deskripsi Fasilitas">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="file" class="form-control @error('fasilitas_foto.' . $index) is-invalid @enderror"
                                               name="fasilitas_foto[{{ $index }}]" accept="image/*">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger delete-existing-fasilitas" data-index="{{ $index }}">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </div>
                                @if(isset($fasilitas['foto']) && $fasilitas['foto'])
                                    <div class="mt-2">
                                        {{-- <small class="text-muted">Foto saat ini:</small> --}}
                                        <img src="{{ asset('images/madrasah/galeri/' . $fasilitas['foto']) }}" alt="Foto Fasilitas" class="image-preview">
                                        <input type="hidden" name="fasilitas[{{ $index }}][foto]" value="{{ $fasilitas['foto'] }}">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="array-input-item mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="fasilitas[0][name]" placeholder="Nama Fasilitas">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="fasilitas[0][description]" placeholder="Deskripsi Fasilitas">
                                </div>
                                <div class="col-md-3">
                                    <input type="file" class="form-control" name="fasilitas_foto[0]" accept="image/*">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-remove-array remove-array-item">
                                        <i class="mdi mdi-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
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
                    @php
                        $keunggulanArray = old('keunggulan', $ppdbSetting->keunggulan ?? []);
                        if (is_string($keunggulanArray)) {
                            $keunggulanArray = json_decode($keunggulanArray, true) ?? [];
                        }
                    @endphp
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
                    @else
                        <div class="array-input-item">
                            <input type="text" class="form-control" name="keunggulan[]" placeholder="Keunggulan madrasah">
                            <button type="button" class="btn btn-remove-array remove-array-item">
                                <i class="mdi mdi-minus"></i>
                            </button>
                        </div>
                    @endif
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
                    @php
                        $jurusanArray = old('jurusan', $ppdbSetting->jurusan ?? []);
                        if (is_string($jurusanArray)) {
                            $jurusanArray = json_decode($jurusanArray, true) ?? [];
                        }
                        $hasExistingJurusan = is_array($jurusanArray) && count($jurusanArray) > 0;
                    @endphp
                    @if($hasExistingJurusan)
                        @foreach($jurusanArray as $index => $jurusan)
                            <div class="array-input-item mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('jurusan.' . $index . '.nama') is-invalid @enderror"
                                               name="jurusan[{{ $index }}][nama]"
                                               value="{{ is_array($jurusan) ? ($jurusan['nama'] ?? $jurusan['name'] ?? '') : $jurusan }}"
                                               placeholder="Nama Jurusan">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('jurusan.' . $index . '.prospek_karir') is-invalid @enderror"
                                               name="jurusan[{{ $index }}][prospek_karir]"
                                               value="{{ is_array($jurusan) ? ($jurusan['prospek_karir'] ?? '') : '' }}"
                                               placeholder="Prospek Karir">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control @error('jurusan.' . $index . '.skill_dipelajari') is-invalid @enderror"
                                               name="jurusan[{{ $index }}][skill_dipelajari]"
                                               value="{{ is_array($jurusan) ? (is_array($jurusan['skill_dipelajari'] ?? null) ? implode(', ', $jurusan['skill_dipelajari']) : ($jurusan['skill_dipelajari'] ?? '')) : '' }}"
                                               placeholder="Skill yang Dipelajari (pisahkan dengan koma)">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-remove-array remove-array-item">
                                            <i class="mdi mdi-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="array-input-item mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="jurusan[0][nama]" placeholder="Nama Jurusan">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="jurusan[0][prospek_karir]" placeholder="Prospek Karir">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="jurusan[0][skill_dipelajari]" placeholder="Skill yang Dipelajari (pisahkan dengan koma)">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-remove-array remove-array-item">
                                        <i class="mdi mdi-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
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
                    @php $prestasiArray = old('prestasi', $ppdbSetting->prestasi ?? []); @endphp
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
                    @php $programUnggulanArray = old('program_unggulan', $ppdbSetting->program_unggulan ?? []); @endphp
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
                    @php $ekstrakurikulerArray = old('ekstrakurikuler', $ppdbSetting->ekstrakurikuler ?? []); @endphp
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
        <div class="form-section hover-lift">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-account-tie me-2"></i>Kepala Sekolah
                </h3>
                <p class="section-subtitle">Informasi tentang kepala sekolah</p>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="kepala_sekolah_nama" class="form-label">Nama Kepala Sekolah</label>
                        <input type="text" class="form-control @error('kepala_sekolah_nama') is-invalid @enderror"
                               id="kepala_sekolah_nama" name="kepala_sekolah_nama"
                               value="{{ old('kepala_sekolah_nama', $kepalaSekolah->name ?? $ppdbSetting->kepala_sekolah_nama ?? '') }}"
                               placeholder="Dr. H. Ahmad Susanto, M.Pd." readonly>
                        <small class="text-muted">Nama kepala sekolah diambil otomatis dari database users</small>
                        @error('kepala_sekolah_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="kepala_sekolah_sambutan" class="form-label">Sambutan Kepala Sekolah</label>
                <textarea class="form-control @error('kepala_sekolah_sambutan') is-invalid @enderror"
                          id="kepala_sekolah_sambutan" name="kepala_sekolah_sambutan" rows="4"
                          placeholder="Sambutan dari kepala sekolah">{{ old('kepala_sekolah_sambutan', $ppdbSetting->kepala_sekolah_sambutan ?? '') }}</textarea>
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
                               id="jumlah_siswa" name="jumlah_siswa" value="{{ old('jumlah_siswa', $ppdbSetting->jumlah_siswa ?? '') }}" min="0">
                        @error('jumlah_siswa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_guru" class="form-label">Jumlah Guru</label>
                        <input type="number" class="form-control @error('jumlah_guru') is-invalid @enderror"
                               id="jumlah_guru" name="jumlah_guru" value="{{ old('jumlah_guru', $jumlahGuru ?? $ppdbSetting->jumlah_guru ?? '') }}" min="0" readonly>
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
                               id="jumlah_jurusan" name="jumlah_jurusan" value="{{ old('jumlah_jurusan', $ppdbSetting->jumlah_jurusan ?? '') }}" min="0" readonly>
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
                               id="jumlah_sarana" name="jumlah_sarana" value="{{ old('jumlah_sarana', $ppdbSetting->jumlah_sarana ?? '') }}" min="0">
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
                <h3 class="section-title">
                    <i class="mdi mdi-camera me-2"></i>Media & Dokumen
                </h3>
                <p class="section-subtitle">Upload gambar galeri dan dokumen pendukung</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="video_profile" class="form-label">Link Video Profile</label>
                        <input type="url" class="form-control @error('video_profile') is-invalid @enderror"
                               id="video_profile" name="video_profile" value="{{ old('video_profile', $ppdbSetting->video_profile ?? '') }}"
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
                        @if($ppdbSetting && $ppdbSetting->brosur_pdf)
                            <div class="mb-2" id="brosur-actions">
                                <a href="{{ asset('uploads/brosur/' . $ppdbSetting->brosur_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
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
                    <i class="mdi mdi-cloud-upload fs-1 text-muted mb-2"></i>
                    <p class="mb-1">Klik untuk upload gambar galeri</p>
                    <small class="text-muted">atau drag & drop file gambar (JPG, PNG, GIF) - Maksimal 2MB per file</small>
                    <input type="file" id="galeri_foto" name="galeri_foto[]" multiple accept="image/*" style="display: none;">
                </div>
                @error('galeri_foto.*')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror

                <!-- Preview existing images -->
                @if($ppdbSetting && $ppdbSetting->galeri_foto && is_array($ppdbSetting->galeri_foto))
                    <div class="image-gallery" id="existing-gallery">
                        @foreach($ppdbSetting->galeri_foto as $index => $image)
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
        <div class="form-section hover-lift">
            <div class="d-flex justify-content-end">
                <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-cancel">
                    <i class="mdi mdi-close me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-submit text-white">
                    <i class="mdi mdi-content-save me-1"></i>Simpan Perubahan
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

            // Special handling for fasilitas container
            if (targetId === 'fasilitas-container') {
                const newItem = document.createElement('div');
                newItem.className = 'array-input-item mb-3 p-3 border rounded';
                newItem.innerHTML = `
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="fasilitas[0][name]" placeholder="Nama Fasilitas">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="fasilitas[0][description]" placeholder="Deskripsi Fasilitas">
                        </div>
                        <div class="col-md-3">
                            <input type="file" class="form-control" name="fasilitas_foto[0]" accept="image/*">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-remove-array remove-array-item">
                                <i class="mdi mdi-minus"></i>
                            </button>
                        </div>
                    </div>
                `;

                // Insert before the last item (which should be empty)
                const items = container.querySelectorAll('.array-input-item');
                if (items.length > 0) {
                    container.insertBefore(newItem, items[items.length - 1]);
                } else {
                    container.appendChild(newItem);
                }

                // Update indices for all fasilitas items
                updateFasilitasIndices();
            } else if (targetId === 'jurusan-container') {
                // Special handling for jurusan container - add only 1 empty row
                const newItem = document.createElement('div');
                newItem.className = 'array-input-item mb-3 p-3 border rounded';
                newItem.innerHTML = `
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="jurusan[0][nama]" placeholder="Nama Jurusan">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="jurusan[0][prospek_karir]" placeholder="Prospek Karir">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="jurusan[0][skill_dipelajari]" placeholder="Skill yang Dipelajari (pisahkan dengan koma)">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-remove-array remove-array-item">
                                <i class="mdi mdi-minus"></i>
                            </button>
                        </div>
                    </div>
                `;

                // Always append to the end of the container
                container.appendChild(newItem);

                // Update indices for all jurusan items
                updateJurusanIndices();
            } else {
                // Default handling for other containers
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
        }

        if (e.target.classList.contains('remove-array-item') || e.target.closest('.remove-array-item')) {
            const item = e.target.closest('.array-input-item');
            if (item) {
                item.remove();
                // Update indices after removal
                updateFasilitasIndices();
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
            setTimeout(() => {
                updateJumlahJurusan();
                updateFasilitasIndices();
            }, 100); // Small delay to ensure DOM updates
        }
    });

    // Update jurusan indices function
    function updateJurusanIndices() {
        const container = document.getElementById('jurusan-container');
        const items = container.querySelectorAll('.array-input-item');
        items.forEach((item, index) => {
            const inputs = item.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name;
                if (name.includes('jurusan[')) {
                    input.name = name.replace(/jurusan\[\d+\]/, `jurusan[${index}]`);
                }
            });
        });
    }

    // Initialize on page load
    updateJumlahJurusan();
    updateFasilitasIndices();
    updateJurusanIndices();

    // Copy to clipboard function
    window.copyToClipboard = function(event, text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="mdi mdi-check me-1"></i>Berhasil Disalin!';
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

    // Delete image function
    window.deleteImage = function(imageName, index) {
        if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
            // Remove from DOM
            const imageItem = document.querySelector(`.image-item[data-image="${imageName}"]`);
            if (imageItem) {
                imageItem.remove();
            }

            // Add to deleted list
            const deletedInput = document.getElementById('deleted_galeri_foto');
            let deletedImages = deletedInput.value ? deletedInput.value.split(',') : [];
            deletedImages.push(imageName);
            deletedInput.value = deletedImages.join(',');
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
