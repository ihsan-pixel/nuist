@extends('layouts.master-without-nav')

@section('title', 'Cek Status PPDB - Ma\'arif NU IST')

@section('css')
<link rel="stylesheet" href="{{ asset('css/ppdb-custom.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .status-hero {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .status-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('{{ asset("images/bg_ppdb2.png") }}') center/cover;
        opacity: 0.1;
        z-index: 1;
    }

    .status-hero-content {
        position: relative;
        z-index: 2;
    }

    .status-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-top: -50px;
        position: relative;
        z-index: 3;
    }

    .status-form-section {
        padding: 40px;
    }

    .status-result-section {
        padding: 40px;
        background: #f8f9fa;
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
        border-color: #004b4c;
        box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.25);
    }

    .btn-check-status {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        border: none;
        color: white;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-check-status:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .status-timeline {
        position: relative;
        padding-left: 30px;
    }

    .status-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 45px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-item.active::before {
        background: #004b4c;
    }

    .timeline-item.completed::before {
        background: #28a745;
    }

    .timeline-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .timeline-title {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 5px;
    }

    .timeline-date {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .timeline-description {
        color: #666;
        margin-bottom: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-verifikasi {
        background: #cce5ff;
        color: #004085;
    }

    .status-lulus {
        background: #d4edda;
        color: #155724;
    }

    .status-tidak_lulus {
        background: #f8d7da;
        color: #721c24;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-left: 4px solid #004b4c;
    }

    .info-title {
        color: #004b4c;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .info-value {
        color: #666;
        margin-bottom: 0;
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

    .btn-back {
        background: #6c757d;
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .status-hero {
            padding: 40px 0;
        }

        .status-container {
            margin-top: -30px;
            border-radius: 15px;
        }

        .status-form-section,
        .status-result-section {
            padding: 20px;
        }

        .timeline-item {
            padding-left: 35px;
        }

        .timeline-item::before {
            left: -18px;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="status-hero">
    <div class="container status-hero-content">
        <div class="row justify-content-center">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-search me-3"></i>Cek Status Pendaftaran
                </h1>
                <p class="lead mb-0">Masukkan NISN Anda untuk melihat status pendaftaran PPDB secara langsung</p>
                <p class="lead mb-0">Masukkan NISN Anda untuk melihat status pendaftaran PPDB</p>
            </div>
        </div>
    </div>
</section>

<!-- Status Container -->
<div class="container status-container">
    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-custom animate-fade-in-up">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Data Tidak Ditemukan
            </h5>
            <p class="mb-0">{{ session('error') }}</p>
        </div>
    @endif

    @if(!isset($pendaftar))
        <!-- Form Section -->
        <div class="status-form-section">
            <div class="text-center mb-4">
                <div class="section-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <h4 class="section-title">Masukkan NISN Anda</h4>
                <p class="section-description">Status pendaftaran akan ditampilkan secara langsung setelah memasukkan NISN</p>
            </div>

            <form action="{{ route('ppdb.cek-status.post') }}" method="POST" id="cekStatusForm">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nisn" class="form-label">
                                <i class="fas fa-hashtag me-2"></i>Nomor Induk Siswa Nasional (NISN)
                            </label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                   id="nisn" name="nisn" value="{{ old('nisn') }}"
                                   placeholder="Masukkan NISN (10-20 digit)" required
                                   pattern="[0-9]{10,20}" maxlength="20">
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-check-status">
                            <i class="fas fa-search me-2"></i>Cek Status
                        </button>
                    </div>
                </div>
            </form>

            <!-- OTP Form (hidden by default) -->
            <div id="otpForm" class="row justify-content-center" style="display: none;">
                <div class="col-md-6">
                    <div class="alert alert-info alert-custom">
                        <h6><i class="fas fa-envelope me-2"></i>Kode OTP Dikirim</h6>
                        <p class="mb-0">Kode OTP telah dikirim ke email Anda. Masukkan kode tersebut untuk melihat status pendaftaran.</p>
                    </div>

                    <div class="form-group">
                        <label for="otp" class="form-label">
                            <i class="fas fa-key me-2"></i>Kode OTP (6 digit)
                        </label>
                        <input type="text" class="form-control" id="otp" name="otp"
                               placeholder="Masukkan kode OTP" maxlength="6" pattern="[0-9]{6}">
                        <input type="hidden" id="pendaftar_id" value="{{ session('pendaftar_id') }}">
                    </div>

                    <button type="button" class="btn btn-check-status" onclick="verifyOTP()">
                        <i class="fas fa-check me-2"></i>Verifikasi & Lihat Status
                    </button>

                    <button type="button" class="btn btn-outline-secondary mt-2" onclick="resetForm()">
                        <i class="fas fa-arrow-left me-2"></i>Cek NISN Lain
                    </button>
                </div>
            </div>

            <!-- Show OTP form if OTP was sent -->
            @if(session('otp_sent'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('cekStatusForm').style.display = 'none';
                    document.getElementById('otpForm').style.display = 'block';
                });
            </script>
            @endif
        </div>
    @else
        <!-- Result Section -->
        <div class="status-result-section">
            <div class="text-center mb-4">
                <h4 class="section-title">Status Pendaftaran Anda</h4>
                <p class="section-description">Berikut adalah detail status pendaftaran PPDB Anda</p>
            </div>

            <!-- School Info - Highlighted -->
            <div class="info-card mb-4" style="border-left-color: #004b4c; background: linear-gradient(135deg, #e8f5e8 0%, #f1f8e9 100%);">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="fas fa-school fa-3x text-success"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="info-title text-success mb-2">
                            <i class="fas fa-university me-2"></i>Sekolah Tujuan Pendaftaran
                        </h5>
                        <h4 class="mb-1" style="color: #004b4c; font-weight: 700;">
                            {{ $pendaftar->ppdbSetting->sekolah->name ?? 'N/A' }}
                        </h4>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-calendar-alt me-2"></i>Tahun Akademik {{ $pendaftar->ppdbSetting->tahun ?? date('Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Timeline Status Horizontal -->
            <div class="info-card mb-4">
                <h6 class="info-title mb-4">
                    <i class="fas fa-tasks me-2"></i>Tahapan Status Pendaftaran
                </h6>

                <!-- Horizontal Timeline -->
                <div style="margin: 0 -25px; padding: 25px; background: #f8f9fa; border-radius: 10px; overflow-x: auto;">
                    <div class="d-flex align-items-center" style="min-width: fit-content; gap: 20px;">
                        <!-- Step 1: Data Dikirim -->
                        <div class="text-center" style="min-width: 120px;">
                            <div class="mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; background: #004b4c; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div style="font-weight: 600; color: #004b4c; margin-bottom: 5px;">Data Dikirim</div>
                            <div style="font-size: 0.8rem; color: #666;">{{ $pendaftar->created_at->format('d/m/Y') }}</div>
                            <div class="badge bg-success mt-2" style="font-size: 0.7rem;">✓ Selesai</div>
                        </div>

                        <!-- Connector -->
                        <div style="flex-grow: 1; height: 2px; background: {{ in_array($pendaftar->status, ['verifikasi', 'lulus', 'tidak_lulus']) ? '#004b4c' : '#dee2e6' }}; margin-bottom: 20px;"></div>

                        <!-- Step 2: Diverifikasi -->
                        <div class="text-center" style="min-width: 120px;">
                            <div class="mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; {{ in_array($pendaftar->status, ['verifikasi', 'lulus', 'tidak_lulus']) ? 'background: #004b4c' : 'background: #dee2e6' }}; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div style="font-weight: 600; color: #004b4c; margin-bottom: 5px;">Diverifikasi</div>
                            <div style="font-size: 0.8rem; color: #666;">
                                @if($pendaftar->diverifikasi_tanggal)
                                    {{ $pendaftar->diverifikasi_tanggal->format('d/m/Y') }}
                                @else
                                    Dalam proses...
                                @endif
                            </div>
                            <div class="badge {{ in_array($pendaftar->status, ['verifikasi', 'lulus', 'tidak_lulus']) ? 'bg-success' : 'bg-secondary' }} mt-2" style="font-size: 0.7rem;">
                                {{ in_array($pendaftar->status, ['verifikasi', 'lulus', 'tidak_lulus']) ? '✓ Selesai' : 'Proses...' }}
                            </div>
                        </div>

                        <!-- Connector -->
                        <div style="flex-grow: 1; height: 2px; background: {{ in_array($pendaftar->status, ['lulus', 'tidak_lulus']) ? '#004b4c' : '#dee2e6' }}; margin-bottom: 20px;"></div>

                        <!-- Step 3: Hasil Seleksi -->
                        <div class="text-center" style="min-width: 120px;">
                            <div class="mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; {{ in_array($pendaftar->status, ['lulus', 'tidak_lulus']) ? 'background: ' . ($pendaftar->status === 'lulus' ? '#28a745' : '#dc3545') : 'background: #dee2e6' }}; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas {{ $pendaftar->status === 'lulus' ? 'fa-smile' : ($pendaftar->status === 'tidak_lulus' ? 'fa-frown' : 'fa-hourglass-half') }}"></i>
                            </div>
                            <div style="font-weight: 600; color: #004b4c; margin-bottom: 5px;">
                                @if($pendaftar->status === 'lulus')
                                    Lulus
                                @elseif($pendaftar->status === 'tidak_lulus')
                                    Tidak Lulus
                                @else
                                    Seleksi
                                @endif
                            </div>
                            <div style="font-size: 0.8rem; color: #666;">
                                @if($pendaftar->diseleksi_tanggal)
                                    {{ $pendaftar->diseleksi_tanggal->format('d/m/Y') }}
                                @else
                                    Dalam proses...
                                @endif
                            </div>
                            <div class="badge {{ in_array($pendaftar->status, ['lulus', 'tidak_lulus']) ? ($pendaftar->status === 'lulus' ? 'bg-success' : 'bg-danger') : 'bg-secondary' }} mt-2" style="font-size: 0.7rem;">
                                {{ in_array($pendaftar->status, ['lulus', 'tidak_lulus']) ? '✓ Selesai' : 'Proses...' }}
                            </div>
                        </div>

                        <!-- Connector -->
                        <div style="flex-grow: 1; height: 2px; background: {{ $pendaftar->status === 'lulus' ? '#004b4c' : '#dee2e6' }}; margin-bottom: 20px;"></div>

                        <!-- Step 4: Pengumuman Daftar Ulang -->
                        <div class="text-center" style="min-width: 120px;">
                            <div class="mx-auto mb-2" style="width: 50px; height: 50px; border-radius: 50%; {{ $pendaftar->status === 'lulus' ? 'background: #ffc107' : 'background: #dee2e6' }}; {{ $pendaftar->status === 'lulus' ? 'color: #000' : 'color: #666' }}; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div style="font-weight: 600; color: #004b4c; margin-bottom: 5px;">Daftar Ulang</div>
                            <div style="font-size: 0.8rem; color: #666;">
                                @if($pendaftar->status === 'lulus')
                                    Segera
                                @else
                                    Menunggu
                                @endif
                            </div>
                            <div class="badge {{ $pendaftar->status === 'lulus' ? 'bg-warning' : 'bg-secondary' }} mt-2" style="font-size: 0.7rem;">
                                {{ $pendaftar->status === 'lulus' ? 'Selanjutnya' : 'Pending' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Status Detail -->
                <div class="mt-4 p-3 bg-light rounded">
                    <strong class="text-primary">Status Saat Ini:</strong>
                    <div class="mt-2">
                        <span class="status-badge status-{{ $pendaftar->status }}" style="font-size: 1rem; padding: 8px 16px;">
                            @if($pendaftar->status === 'pending')
                                <i class="fas fa-clock me-2"></i>Menunggu Verifikasi
                            @elseif($pendaftar->status === 'verifikasi')
                                <i class="fas fa-magnifying-glass me-2"></i>Dalam Verifikasi
                            @elseif($pendaftar->status === 'lulus')
                                <i class="fas fa-check-circle me-2"></i>Lulus Seleksi
                            @else
                                <i class="fas fa-times-circle me-2"></i>Tidak Lulus
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-user me-2"></i>Nama Lengkap
                        </h6>
                        <p class="info-value">{{ $pendaftar->nama_lengkap }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-hashtag me-2"></i>NISN
                        </h6>
                        <p class="info-value">{{ $pendaftar->nisn }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-school me-2"></i>Asal Sekolah
                        </h6>
                        <p class="info-value">{{ $pendaftar->asal_sekolah }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-graduation-cap me-2"></i>Jurusan Pilihan
                        </h6>
                        <p class="info-value">{{ $pendaftar->jurusan_pilihan }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-building me-2"></i>Sekolah Tujuan
                        </h6>
                        <p class="info-value">{{ $pendaftar->ppdbSetting->sekolah->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-route me-2"></i>Jalur Pendaftaran
                        </h6>
                        <p class="info-value">{{ $pendaftar->ppdbJalur->nama_jalur ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-calendar-check me-2"></i>Nomor Pendaftaran
                        </h6>
                        <p class="info-value">{{ $pendaftar->nomor_pendaftaran }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-info-circle me-2"></i>Status Saat Ini
                        </h6>
                        <p class="info-value">
                            <span class="status-badge status-{{ $pendaftar->status }}">
                                {{ ucfirst(str_replace('_', ' ', $pendaftar->status)) }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-map-marker-alt me-2"></i>Alamat Lengkap
                        </h6>
                        <p class="info-value">{{ $pendaftar->alamat_lengkap ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-phone me-2"></i>No. HP Wali
                        </h6>
                        <p class="info-value">{{ $pendaftar->nomor_hp_orang_tua ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Checklist Pengiriman Data Section -->
            @php
                // Dokumen Pokok (Wajib)
                $mainFiles = [
                    'berkas_kk' => ['label' => 'Kartu Keluarga', 'icon' => 'fa-file', 'type' => 'dokumen'],
                    'berkas_ijazah' => ['label' => 'Ijazah', 'icon' => 'fa-certificate', 'type' => 'dokumen'],
                    'berkas_akta_kelahiran' => ['label' => 'Akta Kelahiran', 'icon' => 'fa-file', 'type' => 'dokumen'],
                    'berkas_ktp_ayah' => ['label' => 'KTP Ayah', 'icon' => 'fa-id-card', 'type' => 'dokumen'],
                    'berkas_ktp_ibu' => ['label' => 'KTP Ibu', 'icon' => 'fa-id-card', 'type' => 'dokumen'],
                    'berkas_sertifikat_prestasi' => ['label' => 'Sertifikat Prestasi', 'icon' => 'fa-trophy', 'type' => 'dokumen'],
                ];

                // Dokumen Tambahan (Jika memiliki)
                $additionalFiles = [
                    'berkas_kip_pkh' => ['label' => 'KIP/PKH', 'icon' => 'fa-heart', 'type' => 'dokumen'],
                ];

                $uploadedMainFiles = [];
                $missingMainFiles = [];
                $uploadedAdditionalFiles = [];

                // Proses dokumen pokok
                foreach ($mainFiles as $field => $info) {
                    if (!empty($pendaftar->$field)) {
                        $uploadedMainFiles[$field] = $info;
                    } else {
                        $missingMainFiles[$field] = $info;
                    }
                }

                // Proses dokumen tambahan
                foreach ($additionalFiles as $field => $info) {
                    if (!empty($pendaftar->$field)) {
                        $uploadedAdditionalFiles[$field] = $info;
                    }
                }

                $incompleteFields = [];
                $optionalFields = [
                    'nik' => 'NIK',
                    'agama' => 'Agama',
                    'nama_ayah' => 'Nama Ayah',
                    'nama_ibu' => 'Nama Ibu',
                    'pekerjaan_ayah' => 'Pekerjaan Ayah',
                    'pekerjaan_ibu' => 'Pekerjaan Ibu',
                    'tahun_lulus' => 'Tahun Lulus',
                    'rata_rata_nilai_raport' => 'Rata-rata Nilai Raport',
                    'nomor_ijazah' => 'Nomor Ijazah',
                    'nomor_skhun' => 'Nomor SKHUN',
                ];

                foreach ($optionalFields as $field => $label) {
                    if (empty($pendaftar->$field)) {
                        $incompleteFields[$field] = $label;
                    }
                }

                if (empty($pendaftar->rata_rata_nilai_raport)) {
                    $semesterFields = [
                        'nilai_semester_1' => 'Nilai Semester 1',
                        'nilai_semester_2' => 'Nilai Semester 2',
                        'nilai_semester_3' => 'Nilai Semester 3',
                        'nilai_semester_4' => 'Nilai Semester 4',
                        'nilai_semester_5' => 'Nilai Semester 5',
                    ];

                    foreach ($semesterFields as $field => $label) {
                        if (empty($pendaftar->$field)) {
                            $incompleteFields[$field] = $label;
                        }
                    }
                }
            @endphp

            <!-- Document Submission Status - Dokumen Pokok -->
            <div class="info-card mb-4" style="border-left-color: #17a2b8; background: linear-gradient(135deg, #e8f5f9 0%, #e1f5fe 100%);">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="info-title text-info mb-2">
                            <i class="fas fa-folder-check me-2"></i>Status Pengiriman Dokumen Wajib
                        </h6>
                        <p class="mb-0 text-muted">
                            <strong>{{ count($uploadedMainFiles) }}</strong> dari <strong>{{ count($mainFiles) }}</strong> dokumen wajib sudah dikirim
                        </p>
                    </div>
                    <div class="progress" style="width: 150px; height: 30px;">
                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar"
                             style="width: {{ (count($uploadedMainFiles) / count($mainFiles) * 100) }}%"
                             aria-valuenow="{{ count($uploadedMainFiles) }}" aria-valuemin="0" aria-valuemax="{{ count($mainFiles) }}">
                            {{ round((count($uploadedMainFiles) / count($mainFiles) * 100)) }}%
                        </div>
                    </div>
                </div>

                <!-- Dokumen Pokok yang Sudah Dikirim -->
                @if(count($uploadedMainFiles) > 0)
                    <div class="mb-3">
                        <h6 class="text-success mb-2">
                            <i class="fas fa-check-circle me-1"></i>Dokumen Sudah Dikirim
                        </h6>
                        <div class="row">
                            @foreach($uploadedMainFiles as $field => $info)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="badge bg-success text-white w-100 p-2 text-start">
                                        <i class="fas {{ $info['icon'] }} me-1"></i>{{ $info['label'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Dokumen Pokok yang Belum Dikirim -->
                @if(count($missingMainFiles) > 0)
                    <div>
                        <h6 class="text-warning mb-2">
                            <i class="fas fa-exclamation-circle me-1"></i>Dokumen Belum Dikirim
                        </h6>
                        <div class="row">
                            @foreach($missingMainFiles as $field => $info)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="badge bg-light text-dark border border-warning w-100 p-2 text-start">
                                        <i class="fas {{ $info['icon'] }} me-1 text-warning"></i>{{ $info['label'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-warning btn-sm mt-3" onclick="toggleUpdateForm()">
                            <i class="fas fa-upload me-1"></i>Upload Dokumen Sekarang
                        </button>
                    </div>
                @else
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Sempurna!</strong> Semua dokumen wajib sudah dikirim.
                    </div>
                @endif
            </div>

            <!-- Document Submission Status - Dokumen Tambahan -->
            <div class="info-card mb-4" style="border-left-color: #28a745; background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="info-title text-success mb-2">
                            <i class="fas fa-heart me-2"></i>Dokumen Tambahan (Jika Memiliki)
                        </h6>
                        <p class="mb-3 text-muted">
                            Jika Anda memiliki KIP/PKH, silakan upload dokumen ini:
                        </p>

                        @if(count($uploadedAdditionalFiles) > 0)
                            <div class="mb-3">
                                <h6 class="text-success mb-2">
                                    <i class="fas fa-check-circle me-1"></i>Dokumen Sudah Dikirim
                                </h6>
                                <div class="row">
                                    @foreach($uploadedAdditionalFiles as $field => $info)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="badge bg-success text-white w-100 p-2 text-start">
                                                <i class="fas {{ $info['icon'] }} me-1"></i>{{ $info['label'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Jika Anda memiliki KIP/PKH, silakan upload dokumen melalui tombol di bawah untuk mendapatkan prioritas lebih tinggi.
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-success btn-sm" onclick="toggleUpdateForm()" style="white-space: nowrap; margin-left: 10px;">
                        <i class="fas fa-upload me-1"></i>Upload PIP/PKH
                    </button>
                </div>
            </div>

            @if(count($incompleteFields) > 0)
                <div class="info-card border-warning mb-4" style="border-left-color: #ffc107; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="info-title text-warning mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Data yang Belum Lengkap
                            </h6>
                            <p class="mb-0 text-muted">Lengkapi data berikut untuk melengkapi pendaftaran Anda:</p>
                        </div>
                        <button type="button" class="btn btn-warning btn-sm" onclick="toggleUpdateForm()">
                            <i class="fas fa-edit me-1"></i>Update Data
                        </button>
                    </div>

                    <div class="row">
                        @foreach($incompleteFields as $field => $label)
                            <div class="col-md-6 col-lg-4 mb-2">
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-times-circle text-danger me-1"></i>{{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Update Form (Hidden by default) -->
                <div id="updateForm" class="info-card mb-4" style="display: none; border-left-color: #17a2b8; background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);">
                    <h6 class="info-title text-info mb-3">
                        <i class="fas fa-edit me-2"></i>Update Data Pendaftaran
                    </h6>

                    <form action="{{ route('ppdb.update-data', $pendaftar->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Personal Data -->
                            @if(empty($pendaftar->nik))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nik" maxlength="16" pattern="[0-9]{16}" placeholder="16 digit NIK">
                                </div>
                            @endif

                            @if(empty($pendaftar->agama))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Agama <span class="text-danger">*</span></label>
                                    <select class="form-control" name="agama">
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                            @endif

                            {{-- @if(empty($pendaftar->alamat_lengkap))
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="alamat_lengkap" rows="2" placeholder="Alamat lengkap sesuai KTP"></textarea>
                                </div>
                            @endif --}}

                            <!-- Parent Data -->
                            @if(empty($pendaftar->nama_ayah))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Ayah</label>
                                    <input type="text" class="form-control" name="nama_ayah" placeholder="Nama lengkap ayah">
                                </div>
                            @endif

                            @if(empty($pendaftar->nama_ibu))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Ibu</label>
                                    <input type="text" class="form-control" name="nama_ibu" placeholder="Nama lengkap ibu">
                                </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerjaan Ayah</label>
                                <select class="form-control" name="pekerjaan_ayah">
                                    <option value="">-- Pilih Pekerjaan Ayah --</option>
                                    <option value="Tidak Bekerja" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                    <option value="Pegawai Negeri Sipil (PNS)" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Pegawai Negeri Sipil (PNS)' ? 'selected' : '' }}>Pegawai Negeri Sipil (PNS)</option>
                                    <option value="Pegawai Swasta" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                    <option value="Wiraswasta" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="Petani" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Petani' ? 'selected' : '' }}>Petani</option>
                                    <option value="Nelayan" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                    <option value="Pedagang" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Pedagang' ? 'selected' : '' }}>Pedagang</option>
                                    <option value="Buruh" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                    <option value="Guru/Dosen" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Guru/Dosen' ? 'selected' : '' }}>Guru/Dosen</option>
                                    <option value="Dokter" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="Perawat" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                                    <option value="Polisi" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Polisi' ? 'selected' : '' }}>Polisi</option>
                                    <option value="TNI" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'TNI' ? 'selected' : '' }}>TNI</option>
                                    <option value="Sopir" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Sopir' ? 'selected' : '' }}>Sopir</option>
                                    <option value="Karyawan" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="Lainnya" {{ ($pendaftar->pekerjaan_ayah ?? old('pekerjaan_ayah')) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerjaan Ibu</label>
                                <select class="form-control" name="pekerjaan_ibu">
                                    <option value="">-- Pilih Pekerjaan Ibu --</option>
                                    <option value="Tidak Bekerja" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                    <option value="Ibu Rumah Tangga" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                    <option value="Pegawai Negeri Sipil (PNS)" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Pegawai Negeri Sipil (PNS)' ? 'selected' : '' }}>Pegawai Negeri Sipil (PNS)</option>
                                    <option value="Pegawai Swasta" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                    <option value="Wiraswasta" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="Petani" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Petani' ? 'selected' : '' }}>Petani</option>
                                    <option value="Nelayan" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                    <option value="Pedagang" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Pedagang' ? 'selected' : '' }}>Pedagang</option>
                                    <option value="Buruh" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                                    <option value="Guru/Dosen" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Guru/Dosen' ? 'selected' : '' }}>Guru/Dosen</option>
                                    <option value="Dokter" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="Perawat" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                                    <option value="Polisi" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Polisi' ? 'selected' : '' }}>Polisi</option>
                                    <option value="TNI" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'TNI' ? 'selected' : '' }}>TNI</option>
                                    <option value="Sopir" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Sopir' ? 'selected' : '' }}>Sopir</option>
                                    <option value="Karyawan" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="Lainnya" {{ ($pendaftar->pekerjaan_ibu ?? old('pekerjaan_ibu')) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>



                            <!-- Academic Data -->
                            @if(empty($pendaftar->tahun_lulus))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tahun Lulus</label>
                                    <input type="number" class="form-control" name="tahun_lulus" min="2000" max="{{ date('Y') + 1 }}" placeholder="2024">
                                </div>
                            @endif

                            @if(empty($pendaftar->npsn_sekolah_asal))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NPSN Sekolah Asal</label>
                                    <input type="text" class="form-control" name="npsn_sekolah_asal" maxlength="20" placeholder="Masukkan NPSN sekolah asal">
                                    <small class="text-muted">Nomor Pokok Sekolah Nasional (NPSN) sekolah asal</small>
                                </div>
                            @endif



                            <!-- Semester Grades -->
                            @if(empty($pendaftar->rata_rata_nilai_raport))
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Nilai Raport per Semester</label>
                                    <div class="row">
                                        @for($i = 1; $i <= 5; $i++)
                                            <div class="col-md-2 col-sm-4 mb-2">
                                                <label class="form-label small">Semester {{ $i }}</label>
                                                <input type="number"
                                                       class="form-control form-control-sm semester-grade"
                                                       name="nilai_semester_{{ $i }}"
                                                       step="0.01"
                                                       min="0"
                                                       max="100"
                                                       placeholder="85.5"
                                                       value="{{ old('nilai_semester_' . $i, $pendaftar->{'nilai_semester_' . $i} ?? '') }}"
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
                            @endif

                            @if(empty($pendaftar->nomor_ijazah))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Ijazah (Opsional jika sudah memiliki)</label>
                                    <input type="text" class="form-control" name="nomor_ijazah" placeholder="DN-XX-XXXX-XXXX">
                                </div>
                            @endif

                            @if(empty($pendaftar->nomor_skhun))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor SKHUN (Opsional jika sudah memiliki)</label>
                                    <input type="text" class="form-control" name="nomor_skhun" placeholder="DN-XX-XXXX-XXXX">
                                </div>
                            @endif

                            <!-- File Uploads -->
                            @if(empty($pendaftar->berkas_kk))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kartu Keluarga <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="berkas_kk" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Format: PDF, JPG, PNG. Max: 2MB</small>
                                </div>
                            @endif

                            @if(empty($pendaftar->berkas_ijazah))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ijazah <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="berkas_ijazah" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Format: PDF, JPG, PNG. Max: 2MB</small>
                                </div>
                            @endif

                            <!-- Additional Document Uploads -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sertifikat Prestasi</label>
                                @php
                                    $sertifikatFiles = $pendaftar->berkas_sertifikat_prestasi;
                                    if (is_string($sertifikatFiles)) {
                                        $sertifikatFiles = json_decode($sertifikatFiles, true) ?? [];
                                    }
                                    if (!is_array($sertifikatFiles)) {
                                        $sertifikatFiles = [];
                                    }
                                @endphp
                                @if(!empty($sertifikatFiles))
                                    <div class="mb-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>{{ count($sertifikatFiles) }} file sudah diupload
                                        </small>
                                        <br>
                                        @foreach($sertifikatFiles as $index => $file)
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1 mb-1">
                                                <i class="fas fa-eye me-1"></i>Lihat File {{ $index + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="berkas_sertifikat_prestasi[]" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                <small class="text-muted">Format: PDF, JPG, PNG. Max: 2MB per file. Pilih multiple file jika memiliki lebih dari 1 sertifikat</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">PIP/PKH</label>
                                @if($pendaftar->berkas_kip_pkh)
                                    <div class="mb-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>File sudah diupload
                                        </small>
                                        <br>
                                        <a href="{{ asset('storage/' . $pendaftar->berkas_kip_pkh) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat File
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="berkas_kip_pkh" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, JPG, PNG. Max: 2MB. Upload file baru untuk mengganti yang lama</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Akta Kelahiran</label>
                                @if($pendaftar->berkas_akta_kelahiran)
                                    <div class="mb-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>File sudah diupload
                                        </small>
                                        <br>
                                        <a href="{{ asset('storage/' . $pendaftar->berkas_akta_kelahiran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat File
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="berkas_akta_kelahiran" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, JPG, PNG. Max: 2MB. Upload file baru untuk mengganti yang lama</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">KTP Ayah</label>
                                @if($pendaftar->berkas_ktp_ayah)
                                    <div class="mb-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>File sudah diupload
                                        </small>
                                        <br>
                                        <a href="{{ asset('storage/' . $pendaftar->berkas_ktp_ayah) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat File
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="berkas_ktp_ayah" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, JPG, PNG. Max: 2MB. Upload file baru untuk mengganti yang lama</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">KTP Ibu</label>
                                @if($pendaftar->berkas_ktp_ibu)
                                    <div class="mb-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>File sudah diupload
                                        </small>
                                        <br>
                                        <a href="{{ asset('storage/' . $pendaftar->berkas_ktp_ibu) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat File
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="berkas_ktp_ibu" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format: PDF, JPG, PNG. Max: 2MB. Upload file baru untuk mengganti yang lama</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleUpdateForm()">
                                <i class="fas fa-times me-1"></i>Batal
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Next Steps -->
            @if($pendaftar->status === 'lulus')
                <div class="info-card border-success">
                    <h6 class="info-title text-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>Langkah Selanjutnya
                    </h6>
                    <ul class="mb-0">
                        <li>Tunggu pengumuman jadwal daftar ulang dari sekolah</li>
                        <li>Siapkan berkas asli untuk verifikasi</li>
                        <li>Ikuti semua instruksi yang diberikan sekolah</li>
                        <li>Segera lakukan daftar ulang sesuai jadwal yang ditentukan</li>
                    </ul>
                </div>
            @elseif($pendaftar->status === 'tidak_lulus')
                <div class="info-card border-warning">
                    <h6 class="info-title text-warning mb-3">
                        <i class="fas fa-info-circle me-2"></i>Informasi Penting
                    </h6>
                    <p class="mb-0">
                        Anda dapat mendaftar ke sekolah lain atau mencoba lagi di tahun depan.
                        Tetap semangat dan jangan menyerah dalam mengejar cita-cita!
                    </p>
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('ppdb.cek-status') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Cek NISN Lain
                </a>
            </div>
        </div>
    @endif
</div>

<script>
function toggleUpdateForm() {
    const form = document.getElementById('updateForm');
    const button = event.target.closest('button');

    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        if (button) {
            button.innerHTML = '<i class="fas fa-times me-1"></i>Tutup Form';
            button.classList.remove('btn-warning');
            button.classList.add('btn-danger');
        }
    } else {
        form.style.display = 'none';
        if (button) {
            button.innerHTML = '<i class="fas fa-edit me-1"></i>Update Data';
            button.classList.remove('btn-danger');
            button.classList.add('btn-warning');
        }
    }
}

function calculateAverage() {
    const semesterInputs = document.querySelectorAll('.semester-grade');
    let total = 0;
    let count = 0;

    semesterInputs.forEach(input => {
        const value = parseFloat(input.value);
        if (!isNaN(value) && value > 0) {
            total += value;
            count++;
        }
    });

    const average = count > 0 ? (total / count).toFixed(2) : '';
    document.getElementById('rata_rata_nilai_raport').value = average;
}

// Calculate average on page load if values exist
document.addEventListener('DOMContentLoaded', function() {
    calculateAverage();
});
</script>

{{-- <!-- Back to PPDB Button -->
<div class="text-center py-4">
    <a href="{{ route('ppdb.index') }}" class="btn btn-outline-primary btn-lg">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Halaman PPDB
    </a>
</div> --}}
@endsection
