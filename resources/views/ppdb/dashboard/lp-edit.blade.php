@extends('layouts.master')

@section('title', 'Edit Profil Madrasah - ' . $madrasah->name)

@push('css')
<style>
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
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

    @media (max-width: 768px) {
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
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="mdi mdi-pencil me-2 text-primary"></i>
                Edit Profil Madrasah
            </h2>
            <p class="text-muted mb-0">Lengkapi informasi profil {{ $madrasah->name }}</p>
        </div>
        <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

    <form action="{{ route('ppdb.lp.update', $madrasah->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-information-outline me-2"></i>Informasi Dasar
                </h3>
                <p class="section-subtitle">Informasi pokok tentang madrasah</p>
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
                <h3 class="section-title">
                    <i class="mdi mdi-school me-2"></i>Profil Madrasah
                </h3>
                <p class="section-subtitle">Deskripsi dan informasi detail tentang madrasah</p>
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
        <div class="form-section">
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
                <h3 class="section-title">
                    <i class="mdi mdi-account-tie me-2"></i>Kepala Sekolah
                </h3>
                <p class="section-subtitle">Informasi tentang kepala sekolah</p>
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
        <div class="form-section">
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
                        <input type="file" class="form-control @error('brosur_pdf') is-invalid @enderror"
                               id="brosur_pdf" name="brosur_pdf" accept=".pdf">
                        <div class="help-text">Maksimal 5MB, format PDF</div>
                        @error('brosur_pdf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                @if($madrasah->galeri_foto && is_array($madrasah->galeri_foto))
                    <div class="image-gallery" id="existing-gallery">
                        @foreach($madrasah->galeri_foto as $image)
                            <img src="{{ asset('images/madrasah/galeri/' . $image) }}" alt="Galeri" class="image-preview">
                        @endforeach
                    </div>
                @endif

                <!-- Preview new images -->
                <div class="image-gallery" id="new-gallery"></div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="form-section">
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

    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview';
                    newGallery.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
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
