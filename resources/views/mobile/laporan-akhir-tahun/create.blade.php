@extends('layouts.mobile')

@section('title', 'Buat Laporan Akhir Tahun')

@section('content')
<style>
    body {
        background: #f8f9fb url('{{ asset("images/bg.png") }}') no-repeat center center;
        background-size: cover;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
    }

    .simfoni-header {
        color: white;
        padding: 20px 16px;
        text-align: center;
        position: relative;
    }

    .simfoni-header h4 {
        margin: 0 0 4px 0;
        font-weight: 600;
        font-size: 16px;
    }

    .simfoni-header p {
        margin: 0;
        font-size: 12px;
        opacity: 0.9;
    }

    .form-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        overflow: hidden;
        margin: 0 16px 20px 16px;
    }

    .section-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: #212121 6px 2px 4px -1px;
    }

    .section-card:last-child {
        margin-bottom: 0;
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .section-icon {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 14px;
    }

    .section-title {
        font-weight: 600;
        font-size: 12px;
        color: #004b4c;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 6px;
        font-size: 10px;
    }

    .form-group.required label::after {
        content: ' *';
        color: #dc3545;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="number"],
    .form-group input[type="date"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        background: #fff;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #004b4c;
        box-shadow: 0 0 0 3px rgba(0, 75, 76, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 70px;
    }

    .form-hint {
        font-size: 10px;
        color: #999;
        margin-top: 3px;
    }

    .form-error {
        color: #dc3545;
        font-size: 10px;
        margin-top: 3px;
    }

    .error-container {
        background: #fff5f5;
        border-left: 4px solid #dc3545;
        padding: 10px 12px;
        border-radius: 4px;
        margin-bottom: 12px;
    }

    .error-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .error-list li {
        color: #dc3545;
        font-size: 11px;
        padding: 2px 0;
    }

    .success-alert {
        background: #d4edda;
        border-left: 4px solid #28a745;
        color: #155724;
        padding: 10px 12px;
        border-radius: 4px;
        margin-bottom: 12px;
        font-size: 12px;
    }

    .row-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .submit-container {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 0 0 12px 12px;
        border-top: 1px solid #e9ecef;
    }

    .submit-btn {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .nav-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-btn {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .nav-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .auto-fill {
        background: #f0ebf5;
        font-size: 11px;
        color: #666;
    }

    .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        cursor: pointer;
        font-size: 12px;
        line-height: 1.4;
        color: #004b4c;
    }

    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin: 0;
        accent-color: #004b4c;
    }

    .checkmark {
        display: none;
    }

    .step-indicators {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        gap: 8px;
    }

    .step-indicator {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e9ecef;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .step-indicator.active {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
    }

    .step-indicator.completed {
        background: #28a745;
        color: white;
    }

    .step-indicator::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: 20px;
        height: 2px;
        background: #e9ecef;
        transform: translateY(-50%);
        z-index: -1;
    }

    .step-indicator:last-child::after {
        display: none;
    }

    .step-indicator.completed + .step-indicator::after {
        background: #28a745;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    .step-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
        border-top: 1px solid #e9ecef;
    }

    .step-btn {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        min-width: 100px;
        justify-content: center;
    }

    .step-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 75, 76, 0.3);
    }

    .step-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .step-btn.secondary {
        background: #6c757d;
    }

    .step-btn.secondary:hover:not(:disabled) {
        background: #5a6268;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
</style>

<!-- Header -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('mobile.laporan-akhir-tahun.index') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>LAPORAN AKHIR TAHUN</h4>
    <p>Kepala Madrasah/Sekolah</p>
</div>

<!-- Form Container -->
<div class="form-container">
    <!-- Success Alert -->
    @if (session('success'))
        <div class="success-alert">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="error-container">
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Step Indicators -->
    <div class="step-indicators">
        <div class="step-indicator active" data-step="1"><i class="bx bx-file"></i></div>
        <div class="step-indicator" data-step="2">1</div>
        <div class="step-indicator" data-step="3">2</div>
        <div class="step-indicator" data-step="4">3</div>
        <div class="step-indicator" data-step="5">4</div>
        <div class="step-indicator" data-step="6">5</div>
        <div class="step-indicator" data-step="7">6</div>
        <div class="step-indicator" data-step="8">7</div>
    </div>

    <form action="{{ route('mobile.laporan-akhir-tahun.store') }}" method="POST">
        @csrf

        <!-- Step 1: DATA POKOK LAPORAN -->
        <div class="step-content active" data-step="1">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-file"></i>
                    </div>
                    <h6 class="section-title">DATA POKOK LAPORAN</h6>
                </div>

                <div class="section-content">
                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Nama Kepala Sekolah</label>
                            <input type="text" name="nama_kepala_sekolah" value="{{ old('nama_kepala_sekolah', $data['nama_kepala_sekolah'] ?? '') }}" placeholder="Nama Lengkap" required>
                            @error('nama_kepala_sekolah')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Gelar</label>
                            <input type="text" name="gelar" value="{{ old('gelar') }}" placeholder="S.Pd., M.Pd., dll">
                            @error('gelar')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>NIPM</label>
                            <input type="text" name="nip" value="{{ old('nip', $data['nip'] ?? '') }}" placeholder="NIPM" required>
                            @error('nip')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>Tanggal TMT Kepala Sekolah</label>
                            <input type="date" name="tanggal_tmt_kepala_sekolah" value="{{ old('tanggal_tmt_kepala_sekolah') }}" required>
                            @error('tanggal_tmt_kepala_sekolah')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group required">
                        <label>Tahun Laporan</label>
                        <input type="number" name="tahun_pelaporan" value="{{ old('tahun_pelaporan', $data['tahun_pelaporan'] ?? date('Y')) }}" min="2020" max="{{ date('Y') + 1 }}" placeholder="2024" required>
                        @error('tahun_pelaporan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <div></div>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: 1. TARGET UTAMA -->
        <div class="step-content" data-step="2">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-target"></i>
                    </div>
                    <h6 class="section-title">1. TARGET UTAMA</h6>
                </div>

                <div class="section-content">
                    <h6 class="mb-3">1. Capaian dan Target Utama</h6>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Jumlah Siswa</label>
                            <input type="number" name="target_jumlah_siswa" value="{{ old('target_jumlah_siswa') }}" min="0" placeholder="0">
                            @error('target_jumlah_siswa')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Capaian Jumlah Siswa</label>
                            <input type="number" name="capaian_jumlah_siswa" value="{{ old('capaian_jumlah_siswa') }}" min="0" placeholder="0">
                            @error('capaian_jumlah_siswa')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Tahun Berikutnya</label>
                        <input type="text" name="target_tahun_berikutnya" value="{{ old('target_tahun_berikutnya') }}" placeholder="Target untuk tahun berikutnya">
                        @error('target_tahun_berikutnya')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Dana</label>
                            <input type="number" name="target_dana" value="{{ old('target_dana') }}" min="0" step="1000" placeholder="0">
                            @error('target_dana')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Capaian Dana</label>
                            <input type="number" name="capaian_dana" value="{{ old('capaian_dana') }}" min="0" step="1000" placeholder="0">
                            @error('capaian_dana')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Alumni</label>
                            <input type="number" name="target_alumni" value="{{ old('target_alumni') }}" min="0" placeholder="0">
                            @error('target_alumni')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Capaian Alumni</label>
                            <input type="number" name="capaian_alumni" value="{{ old('capaian_alumni') }}" min="0" placeholder="0">
                            @error('capaian_alumni')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Alumni Berikutnya</label>
                        <input type="text" name="target_alumni_berikutnya" value="{{ old('target_alumni_berikutnya') }}" placeholder="Target alumni untuk tahun berikutnya">
                        @error('target_alumni_berikutnya')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Akreditasi</label>
                            <select name="akreditasi">
                                <option value="">Pilih Akreditasi</option>
                                <option value="A" {{ old('akreditasi') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('akreditasi') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('akreditasi') == 'C' ? 'selected' : '' }}>C</option>
                            </select>
                            @error('akreditasi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Tahun Akreditasi</label>
                            <input type="number" name="tahun_akreditasi" value="{{ old('tahun_akreditasi') }}" min="2000" max="{{ date('Y') + 10 }}" placeholder="2024">
                            @error('tahun_akreditasi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nilai Akreditasi</label>
                        <input type="number" name="nilai_akreditasi" value="{{ old('nilai_akreditasi') }}" min="0" max="100" step="0.01" placeholder="0.00">
                        @error('nilai_akreditasi')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">1-B. UPAYA Satpen Meraih Target Utama di Atas? (Skor maksimal 20)</h6>

                    <div class="form-group">
                        <label>Untuk Capaian Siswa</label>
                        <textarea name="upaya_capaian_siswa" placeholder="Jelaskan upaya untuk mencapai target siswa..." rows="2">{{ old('upaya_capaian_siswa') }}</textarea>
                        @error('upaya_capaian_siswa')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Untuk Capaian Dana</label>
                        <textarea name="upaya_capaian_dana" placeholder="Jelaskan upaya untuk mencapai target dana..." rows="2">{{ old('upaya_capaian_dana') }}</textarea>
                        @error('upaya_capaian_dana')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Untuk Alumni BMWA</label>
                        <textarea name="upaya_alumni_bmwa" placeholder="Jelaskan upaya untuk alumni BMWA..." rows="2">{{ old('upaya_alumni_bmwa') }}</textarea>
                        @error('upaya_alumni_bmwa')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Untuk Akreditasi</label>
                        <textarea name="upaya_akreditasi" placeholder="Jelaskan upaya untuk akreditasi..." rows="2">{{ old('upaya_akreditasi') }}</textarea>
                        @error('upaya_akreditasi')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: B. DATA MADRASAH -->
        <div class="step-content" data-step="3">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-building"></i>
                    </div>
                    <h6 class="section-title">B. DATA MADRASAH</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Nama Madrasah</label>
                        <input type="text" name="nama_madrasah" value="{{ old('nama_madrasah', $data['nama_madrasah'] ?? '') }}" placeholder="Nama Madrasah" required>
                        @error('nama_madrasah')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Alamat Madrasah</label>
                        <textarea name="alamat_madrasah" placeholder="Alamat lengkap madrasah" required>{{ old('alamat_madrasah', $data['alamat_madrasah'] ?? '') }}</textarea>
                        @error('alamat_madrasah')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 4: C. DATA STATISTIK -->
        <div class="step-content" data-step="4">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-bar-chart"></i>
                    </div>
                    <h6 class="section-title">C. DATA STATISTIK</h6>
                </div>

                <div class="section-content">
                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Jumlah Guru</label>
                            <input type="number" name="jumlah_guru" value="{{ old('jumlah_guru') }}" min="0" placeholder="0" required>
                            @error('jumlah_guru')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>Jumlah Siswa</label>
                            <input type="number" name="jumlah_siswa" value="{{ old('jumlah_siswa') }}" min="0" placeholder="0" required>
                            @error('jumlah_siswa')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group required">
                        <label>Jumlah Kelas</label>
                        <input type="number" name="jumlah_kelas" value="{{ old('jumlah_kelas') }}" min="0" placeholder="0" required>
                        @error('jumlah_kelas')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 5: D. INFORMASI LAPORAN -->
        <div class="step-content" data-step="5">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <h6 class="section-title">D. INFORMASI LAPORAN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Tanggal Laporan</label>
                        <input type="date" name="tanggal_laporan" value="{{ old('tanggal_laporan', date('Y-m-d')) }}" required>
                        @error('tanggal_laporan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 6: E. PRESTASI MADRASAH -->
        <div class="step-content" data-step="6">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-trophy"></i>
                    </div>
                    <h6 class="section-title">E. PRESTASI MADRASAH</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Prestasi Madrasah</label>
                        <textarea name="prestasi_madrasah" placeholder="Jelaskan prestasi-prestasi yang telah dicapai oleh madrasah selama tahun ini..." required>{{ old('prestasi_madrasah') }}</textarea>
                        @error('prestasi_madrasah')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 7: F. KENDALA UTAMA -->
        <div class="step-content" data-step="7">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-error"></i>
                    </div>
                    <h6 class="section-title">F. KENDALA UTAMA</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Kendala Utama</label>
                        <textarea name="kendala_utama" placeholder="Jelaskan kendala-kendala utama yang dihadapi selama tahun ini..." required>{{ old('kendala_utama') }}</textarea>
                        @error('kendala_utama')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 8: G. PROGRAM KERJA TAHUN DEPAN -->
        <div class="step-content" data-step="8">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-target"></i>
                    </div>
                    <h6 class="section-title">G. PROGRAM KERJA TAHUN DEPAN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Program Kerja Tahun Depan</label>
                        <textarea name="program_kerja_tahun_depan" placeholder="Jelaskan program-program kerja yang akan dilaksanakan pada tahun depan..." required>{{ old('program_kerja_tahun_depan') }}</textarea>
                        @error('program_kerja_tahun_depan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="submit" class="step-btn">
                    <i class="bx bx-save"></i>
                    Simpan Laporan
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

<script>
    let currentStep = 1;
    const totalSteps = 8;

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.remove('active');
        });

        // Show current step
        const currentStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (currentStepContent) {
            currentStepContent.classList.add('active');
        }

        // Update indicators
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNumber = index + 1;
            indicator.classList.remove('active', 'completed');

            if (stepNumber === step) {
                indicator.classList.add('active');
            } else if (stepNumber < step) {
                indicator.classList.add('completed');
            }
        });

        currentStep = step;
    }

    function nextStep() {
        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    }

    // Title case for Nama Kepala Sekolah and Nama Madrasah
    function toTitleCase(str) {
        return str.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    const namaKepalaInput = document.querySelector('input[name="nama_kepala_sekolah"]');
    if (namaKepalaInput) {
        namaKepalaInput.addEventListener('input', function() {
            this.value = toTitleCase(this.value);
        });
    }

    const namaMadrasahInput = document.querySelector('input[name="nama_madrasah"]');
    if (namaMadrasahInput) {
        namaMadrasahInput.addEventListener('input', function() {
            this.value = toTitleCase(this.value);
        });
    }

    // Initialize first step
    document.addEventListener('DOMContentLoaded', function() {
        showStep(1);
    });
</script>
