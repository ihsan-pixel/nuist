@extends('layouts.master-without-nav')

@section('title', 'Formulir PPDB ' . $ppdbSetting->nama_sekolah)

@section('css')
<link rel="stylesheet" href="{{ asset('css/ppdb-custom.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Enhanced Registration Form Styles */
    .hero-section {
        background: url('{{ asset("images/bg_ppdb2.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 40vh;
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
        background: rgba(255, 255, 255, 0.9);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .registration-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-top: -50px;
        position: relative;
        z-index: 3;
    }

    .progress-header {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .progress-steps {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .step {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        color: white;
        font-weight: bold;
        margin: 0 10px;
        position: relative;
        transition: all 0.3s ease;
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
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 1px solid #2196f3;
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
                <p class="lead mb-0 animate-fade-in-up">{{ $ppdbSetting->nama_sekolah }}</p>
                <p class="text-muted animate-fade-in-up">Tahun Pelajaran {{ $ppdbSetting->tahun }}/{{ $ppdbSetting->tahun + 1 }}</p>
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
        </div>
        <h3 id="progress-title" class="mb-0">Data Pribadi</h3>
        <p id="progress-description" class="mb-0 opacity-75">Langkah 1 dari 3</p>
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
                        <label for="nisn" class="form-label">
                            <i class="fas fa-id-card me-2"></i>NISN <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                               id="nisn" name="nisn" value="{{ old('nisn') }}"
                               placeholder="Nomor Induk Siswa Nasional" required
                               onblur="checkNISNAvailability()">
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

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
                <input type="text" class="form-control @error('jurusan_pilihan') is-invalid @enderror"
                       id="jurusan_pilihan" name="jurusan_pilihan" value="{{ old('jurusan_pilihan') }}"
                       placeholder="Contoh: IPA, IPS, Teknik Komputer, Bahasa" required>
                @error('jurusan_pilihan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

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
    const titles = ['Data Pribadi', 'Pilihan Program', 'Upload Berkas'];
    const descriptions = ['Langkah 1 dari 3', 'Langkah 2 dari 3', 'Langkah 3 dari 3'];

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
