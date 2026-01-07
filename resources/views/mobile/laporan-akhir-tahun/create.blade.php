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

    .dynamic-inputs {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .input-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .input-row input {
        flex: 1;
        padding: 10px 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        background: #fff;
    }

    .input-row input:focus {
        outline: none;
        border-color: #004b4c;
        box-shadow: 0 0 0 3px rgba(0, 75, 76, 0.1);
    }

    .add-input-btn, .remove-input-btn {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .add-input-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 75, 76, 0.3);
    }

    .remove-input-btn {
        background: #dc3545;
    }

    .remove-input-btn:hover {
        background: #c82333;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin: 8px 0;
        font-size: 10px;
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .info-table th,
    .info-table td {
        padding: 6px 8px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }

    .info-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #004b4c;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-table td {
        color: #495057;
        font-size: 9px;
    }

    .info-table tr:last-child td {
        border-bottom: none;
    }

    .info-section {
        margin: 12px 0;
    }

    .info-section h5 {
        font-size: 11px;
        font-weight: 600;
        color: #004b4c;
        margin: 0 0 6px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-note {
        font-size: 10px;
        color: #6c757d;
        line-height: 1.4;
        margin: 6px 0;
    }

    .dynamic-info {
        font-size: 10px;
        color: #004b4c;
        background: #e8f5e8;
        padding: 4px 8px;
        border-radius: 4px;
        margin-top: 4px;
        border: 1px solid #c3e6c3;
        font-weight: 600;
    }

    .dynamic-info.warning {
        background: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }

    .input-with-symbol {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-symbol input {
        flex: 1;
        padding-right: 40px; /* Space for the symbol */
    }

    .input-symbol {
        position: absolute;
        right: 12px;
        color: #004b4c;
        font-weight: 600;
        font-size: 12px;
        pointer-events: none;
    }

    .input-with-symbol {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-symbol input {
        flex: 1;
        padding-right: 40px; /* Space for the symbol */
    }

    .input-symbol {
        position: absolute;
        right: 12px;
        color: #004b4c;
        font-weight: 600;
        font-size: 12px;
        pointer-events: none;
    }
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 30px 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #ccc;
    }

    .divider span {
        padding: 0 15px;
        font-size: 10px;
        color: #555;
        white-space: nowrap;
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

                <div class="divider">
                    <span>Siswa</span>
                </div>

                <div class="section-content">
                    <h6 class="mb-3">1-A. Capaian dan Target Utama</h6>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Jumlah Siswa</label>
                            <input type="number" name="target_jumlah_siswa" id="target_jumlah_siswa" value="{{ old('target_jumlah_siswa') }}" min="0" placeholder="0">
                            @error('target_jumlah_siswa')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Capaian Jumlah Siswa</label>
                            <input type="number" name="capaian_jumlah_siswa" id="capaian_jumlah_siswa" value="{{ old('capaian_jumlah_siswa') }}" min="0" placeholder="0">
                            @error('capaian_jumlah_siswa')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div id="capaian_siswa_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Tahun Berikutnya</label>
                        <input type="text" name="target_tahun_berikutnya" value="{{ old('target_tahun_berikutnya') }}" placeholder="Target untuk tahun berikutnya">
                        @error('target_tahun_berikutnya')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info-note">
                        <strong>*</strong> Jumlah siswa dihitung keseluruhan tapi pada lampiran tetap dirinci kelas X, XI, XII. Pada SLB disebutkan seluruh jenjang<br>
                    </div>

                    <div class="divider">
                        <span>Dana</span>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Dana</label>
                            <input type="text" name="target_dana" id="target_dana" value="{{ old('target_dana') }}" placeholder="Rp 0">
                            @error('target_dana')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Capaian Dana</label>
                            <input type="text" name="capaian_dana" id="capaian_dana" value="{{ old('capaian_dana') }}" placeholder="Rp 0">
                            @error('capaian_dana')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div id="capaian_dana_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Dana Tahun Berikutnya</label>
                        <input type="text" name="target_dana_tahun_berikutnya" value="{{ old('target_dana_tahun_berikutnya') }}" placeholder="Rp 0">
                        @error('target_dana_tahun_berikutnya')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="info-note">
                        <strong>**</strong> Jumlah dana adalah jumlah gabungan dari BOSNAS, BOSDA, SPP, BP3 dll. Di atas ditulis global, pada lampiran didetailkan
                    </div>

                    <div class="divider">
                        <span>Alumni</span>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Alumni</label>
                            <div class="input-with-symbol">
                                <input type="text" name="target_alumni" id="target_alumni" value="{{ old('target_alumni') }}" placeholder="0">
                                <span class="input-symbol">%</span>
                            </div>
                            @error('target_alumni')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div id="target_alumni_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label>Capaian Alumni</label>
                            <div class="input-with-symbol">
                                <input type="text" name="capaian_alumni" id="capaian_alumni" value="{{ old('capaian_alumni') }}" placeholder="0">
                                <span class="input-symbol">%</span>
                            </div>
                            @error('capaian_alumni')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div id="capaian_alumni_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Alumni Berikutnya</label>
                        <div class="input-with-symbol">
                            <input type="text" name="target_alumni_berikutnya" value="{{ old('target_alumni_berikutnya') }}" placeholder="0">
                            <span class="input-symbol">%</span>
                        </div>
                        @error('target_alumni_berikutnya')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="divider">
                        <span>Akreditasi</span>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Akreditasi</label>
                            <select name="akreditasi" id="akreditasi">
                                <option value="">Pilih Akreditasi</option>
                                <option value="Belum" {{ old('akreditasi') == 'Belum' ? 'selected' : '' }}>Belum</option>
                                <option value="C" {{ old('akreditasi') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="B" {{ old('akreditasi') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="A" {{ old('akreditasi') == 'A' ? 'selected' : '' }}>A</option>
                            </select>
                            @error('akreditasi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div id="akreditasi_info" class="dynamic-info" style="display: none;"></div>
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
                            <!-- Total Skor Field -->
                            <div class="form-group" style="margin-bottom: 12px; padding: 8px; background: #fff; border-radius: 6px; border: 2px solid #004b4c;">
                                <label style="font-weight: 600; color: #004b4c; margin-bottom: 4px; display: block;">Total Skor</label>
                                <input type="text" id="total_skor" value="0" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-weight: bold; font-size: 14px; color: #004b4c;">
                                <div class="form-hint">Total skor otomatis dari capaian siswa, dana, alumni, dan akreditasi</div>
                                <div id="total_skor_info" class="dynamic-info" style="display: none;"></div>
                                <!-- Rincian Skor -->
                                <div id="skor_breakdown" style="margin-top: 8px; font-size: 11px; color: #004b4c;">
                                    <div><strong>Rincian Skor:</strong></div>
                                    <div id="skor_siswa_kategori">Skor Kategori Siswa: 0</div>
                                    <div id="skor_siswa_prestasi">Skor Prestasi Siswa: 0</div>
                                    <div id="skor_dana_kategori">Skor Kategori Dana: 0</div>
                                    <div id="skor_dana_prestasi">Skor Prestasi Dana: 0</div>
                                    <div id="skor_alumni_kategori">Skor Kategori Alumni: 0</div>
                                    <div id="skor_alumni_prestasi">Skor Prestasi Alumni: 0</div>
                                    <div id="skor_akreditasi">Skor Akreditasi: 0</div>
                                    <div style="border-top: 1px solid #004b4c; margin-top: 4px; padding-top: 4px;"><strong id="total_breakdown">Total: 0</strong></div>
                                </div>
                            </div>

                    <!-- Penjelasan -->
                    <div class="form-group">
                        <div style="background: #f7e0e0; padding: 12px; border-radius: 8px; font-size: 11px; line-height: 1.4; color: #004b4c;">

                            <div class="info-section">
                                <h5>Skor Prestasi Target SDA</h5>
                                <table class="info-table">
                                    <tr><td>+0 = turun</td><td>+1 = tetap</td><td>+2 = naik</td></tr>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Prestasi Akreditasi</h5>
                                <table class="info-table">
                                    <tr><td>Belum = +1</td><td>C = +4</td><td>B = +7</td><td>A = +10</td></tr>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Kategori Berdasarkan Jumlah Siswa</h5>
                                <table class="info-table">
                                    <thead>
                                        <tr><th>Skor</th><th>Kategori</th><th>Jumlah Siswa</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>9</td><td>Unggulan A</td><td>>1001</td></tr>
                                        <tr><td>8</td><td>Unggulan B</td><td>751 - 1000</td></tr>
                                        <tr><td>7</td><td>Mandiri A</td><td>501 - 750</td></tr>
                                        <tr><td>6</td><td>Mandiri B</td><td>251 - 500</td></tr>
                                        <tr><td>5</td><td>Pramandiri A</td><td>151 - 250</td></tr>
                                        <tr><td>4</td><td>Pramandiri B</td><td>101 - 150</td></tr>
                                        <tr><td>3</td><td>Rintisan A</td><td>61 - 100</td></tr>
                                        <tr><td>2</td><td>Rintisan B</td><td>20 - 60</td></tr>
                                        <tr><td>1</td><td>Posisi Zero</td><td>0 - 19</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Kategori Berdasarkan Jumlah Dana (Juta)</h5>
                                <table class="info-table">
                                    <thead>
                                        <tr><th>Skor</th><th>Kategori</th><th>Jumlah Dana</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>9</td><td>Unggulan A</td><td>>5001</td></tr>
                                        <tr><td>8</td><td>Unggulan B</td><td>3001-5000</td></tr>
                                        <tr><td>7</td><td>Mandiri A</td><td>2000 - 3000</td></tr>
                                        <tr><td>6</td><td>Mandiri B</td><td>1251 - 1999</td></tr>
                                        <tr><td>5</td><td>Pramandiri A</td><td>751 - 1250</td></tr>
                                        <tr><td>4</td><td>Pramandiri B</td><td>351 - 750</td></tr>
                                        <tr><td>3</td><td>Rintisan A</td><td>151 - 350</td></tr>
                                        <tr><td>2</td><td>Rintisan B</td><td>30 - 150</td></tr>
                                        <tr><td>1</td><td>Posisi Zero</td><td>0 - 29</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Kategori Berdasarkan Keterserapan Lulusan</h5>
                                <table class="info-table">
                                    <thead>
                                        <tr><th>Skor</th><th>Kategori</th><th>Persentase</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>9</td><td>Unggulan A</td><td>81 - 100%</td></tr>
                                        <tr><td>8</td><td>Unggulan B</td><td>66 - 80%</td></tr>
                                        <tr><td>7</td><td>Mandiri A</td><td>51 - 65%</td></tr>
                                        <tr><td>6</td><td>Mandiri B</td><td>35 - 50%</td></tr>
                                        <tr><td>5</td><td>Pramandiri A</td><td>20 - 34%</td></tr>
                                        <tr><td>4</td><td>Pramandiri B</td><td>10 - 19%</td></tr>
                                        <tr><td>3</td><td>Rintisan A</td><td>3 - 9%</td></tr>
                                        <tr><td>2</td><td>Rintisan B</td><td>1 - 2%</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">1-B. UPAYA Satpen Meraih Target Utama di Atas? (Skor maksimal 20)</h6>

                    <!-- Untuk Capaian Siswa -->
                    <div class="form-group">
                        <label>Untuk Capaian Siswa</label>
                        <div class="dynamic-inputs" data-category="siswa">
                            <div class="input-row">
                                <input type="text" name="upaya_capaian_siswa[]" placeholder="Upaya untuk mencapai target siswa" value="{{ old('upaya_capaian_siswa.0') }}">
                                <button type="button" class="add-input-btn" onclick="addInputField('siswa')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            @if(old('upaya_capaian_siswa'))
                                @foreach(old('upaya_capaian_siswa') as $index => $value)
                                    @if($index > 0 && !empty($value))
                                        <div class="input-row">
                                            <input type="text" name="upaya_capaian_siswa[]" placeholder="Upaya untuk mencapai target siswa" value="{{ $value }}">
                                            <button type="button" class="remove-input-btn" onclick="removeInputField(this)">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @error('upaya_capaian_siswa')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('upaya_capaian_siswa.*')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Untuk Capaian Dana -->
                    <div class="form-group">
                        <label>Untuk Capaian Dana</label>
                        <div class="dynamic-inputs" data-category="dana">
                            <div class="input-row">
                                <input type="text" name="upaya_capaian_dana[]" placeholder="Upaya untuk mencapai target dana" value="{{ old('upaya_capaian_dana.0') }}">
                                <button type="button" class="add-input-btn" onclick="addInputField('dana')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                        </div>
                        @error('upaya_capaian_dana')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('upaya_capaian_dana.*')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Untuk Alumni BMWA -->
                    <div class="form-group">
                        <label>Untuk Alumni BMWA</label>
                        <div class="dynamic-inputs" data-category="alumni">
                            <div class="input-row">
                                <input type="text" name="upaya_alumni_bmwa[]" placeholder="Upaya untuk alumni BMWA" value="{{ old('upaya_alumni_bmwa.0') }}">
                                <button type="button" class="add-input-btn" onclick="addInputField('alumni')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            @if(old('upaya_alumni_bmwa'))
                                @foreach(old('upaya_alumni_bmwa') as $index => $value)
                                    @if($index > 0 && !empty($value))
                                        <div class="input-row">
                                            <input type="text" name="upaya_alumni_bmwa[]" placeholder="Upaya untuk alumni BMWA" value="{{ $value }}">
                                            <button type="button" class="remove-input-btn" onclick="removeInputField(this)">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @error('upaya_alumni_bmwa')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('upaya_alumni_bmwa.*')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Untuk Akreditasi -->
                    <div class="form-group">
                        <label>Untuk Akreditasi</label>
                        <div class="dynamic-inputs" data-category="akreditasi">
                            <div class="input-row">
                                <input type="text" name="upaya_akreditasi[]" placeholder="Upaya untuk akreditasi" value="{{ old('upaya_akreditasi.0') }}">
                                <button type="button" class="add-input-btn" onclick="addInputField('akreditasi')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            @if(old('upaya_akreditasi'))
                                @foreach(old('upaya_akreditasi') as $index => $value)
                                    @if($index > 0 && !empty($value))
                                        <div class="input-row">
                                            <input type="text" name="upaya_akreditasi[]" placeholder="Upaya untuk akreditasi" value="{{ $value }}">
                                            <button type="button" class="remove-input-btn" onclick="removeInputField(this)">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @error('upaya_akreditasi')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('upaya_akreditasi.*')
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
                            <input type="number" name="jumlah_siswa" id="jumlah_siswa" value="{{ old('jumlah_siswa') }}" min="0" placeholder="0" required>
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

    // Navigation functions
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

        // Format Rupiah for dana fields
        const danaFields = [
            'target_dana',
            'capaian_dana',
            'target_dana_tahun_berikutnya'
        ];

        danaFields.forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);
            if (input) {
                // Format initial value if it exists
                if (input.value && !input.value.includes('Rp ')) {
                    formatRupiah(input);
                }

                input.addEventListener('focus', function() {
                    // Remove formatting when focused for editing
                    let value = this.value.replace(/[^\d]/g, '');
                    this.value = value;
                });
                input.addEventListener('blur', function() {
                    // Format when leaving the field
                    formatRupiah(this);
                });
                input.addEventListener('input', function() {
                    // Allow free input while focused, only format on blur
                    // This prevents interference during typing
                });
            }
        });

        alumniFields.forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);
            if (input) {
                // Format initial value if it exists
                if (input.value && !input.value.includes('%')) {
                    formatPercentage(input);
                }

                input.addEventListener('focus', function() {
                    // Remove formatting when focused for editing
                    let value = this.value.replace(/[^\d]/g, '');
                    this.value = value;
                });
                input.addEventListener('blur', function() {
                    // Format when leaving the field
                    formatPercentage(this);
                });
                input.addEventListener('input', function() {
                    // Allow free input while focused, only format on blur
                    // This prevents interference during typing
                });
            }
        });
    });

    // Format number to Rupiah
    function formatRupiah(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
            input.value = 'Rp ' + value;
        } else {
            input.value = '';
        }
    }

    // Format number to percentage
    function formatPercentage(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = value + '%';
        } else {
            input.value = '';
        }
    }

    // Remove Rupiah formatting for editing
    function unformatRupiah(input) {
        let value = input.value.replace(/[^\d]/g, '');
        input.value = value;
    }

    // Before form submission, remove Rupiah and percentage formatting
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            danaFields.forEach(fieldName => {
                const input = document.querySelector(`input[name="${fieldName}"]`);
                if (input && input.value.includes('Rp ')) {
                    input.value = input.value.replace(/[^\d]/g, '');
                }
            });
            alumniFields.forEach(fieldName => {
                const input = document.querySelector(`input[name="${fieldName}"]`);
                if (input && input.value.includes('%')) {
                    input.value = input.value.replace(/[^\d]/g, '');
                }
            });
        });
    }

    // Dynamic input fields functionality
    function addInputField(category) {
        const container = document.querySelector(`.dynamic-inputs[data-category="${category}"]`);
        if (!container) return;

        const inputRows = container.querySelectorAll('.input-row');
        const newIndex = inputRows.length;

        const newRow = document.createElement('div');
        newRow.className = 'input-row';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = `upaya_${getFieldName(category)}[]`;
        input.placeholder = getPlaceholderText(category);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-input-btn';
        removeBtn.innerHTML = '<i class="bx bx-minus"></i>';
        removeBtn.onclick = function() {
            removeInputField(this);
        };

        newRow.appendChild(input);
        newRow.appendChild(removeBtn);

        container.appendChild(newRow);
    }

    function removeInputField(button) {
        const inputRow = button.closest('.input-row');
        const container = inputRow.closest('.dynamic-inputs');
        const inputRows = container.querySelectorAll('.input-row');

        // Keep at least one input field
        if (inputRows.length > 1) {
            inputRow.remove();
        }
    }

    function getFieldName(category) {
        switch(category) {
            case 'siswa': return 'capaian_siswa';
            case 'dana': return 'capaian_dana';
            case 'alumni': return 'alumni_bmwa';
            case 'akreditasi': return 'akreditasi';
            default: return category;
        }
    }

    function getPlaceholderText(category) {
        switch(category) {
            case 'siswa': return 'Upaya untuk mencapai target siswa';
            case 'dana': return 'Upaya untuk mencapai target dana';
            case 'alumni': return 'Upaya untuk alumni BMWA';
            case 'akreditasi': return 'Upaya untuk akreditasi';
            default: return 'Upaya';
        }
    }

    // Initialize existing dynamic fields from old input
    document.addEventListener('DOMContentLoaded', function() {
        // ... existing code ...

        // Dynamic inputs are now handled server-side with Blade templates
        // No additional JavaScript initialization needed

        // Initialize dynamic info for existing values
        updateSiswaInfo('capaian_jumlah_siswa', 'capaian_siswa_info');
        updateDanaInfo('capaian_dana', 'capaian_dana_info');
        updateAlumniInfo('capaian_alumni', 'capaian_alumni_info');
        updateAkreditasiInfo();

        // Initialize total score
        updateTotalSkor();

        // Add event listeners for dynamic updates
        document.getElementById('capaian_jumlah_siswa').addEventListener('input', function() {
            updateSiswaInfo('capaian_jumlah_siswa', 'capaian_siswa_info');
        });
        document.getElementById('target_jumlah_siswa').addEventListener('input', function() {
            updateTotalSkor(); // Update total score when target siswa changes
        });
        document.getElementById('capaian_alumni').addEventListener('input', function() {
            updateAlumniInfo('capaian_alumni', 'capaian_alumni_info');
        });
        document.getElementById('capaian_dana').addEventListener('blur', function() {
            updateDanaInfo('capaian_dana', 'capaian_dana_info');
        });
        document.getElementById('target_dana').addEventListener('blur', function() {
            updateTotalSkor(); // Update total score when target dana changes
        });
        document.getElementById('target_alumni').addEventListener('blur', function() {
            updateTotalSkor(); // Update total score when target alumni changes
        });
        document.getElementById('akreditasi').addEventListener('change', updateAkreditasiInfo);
    });

    // Function to update student count info
    function updateSiswaInfo(inputId, infoId) {
        const input = document.getElementById(inputId);
        const info = document.getElementById(infoId);
        const value = parseInt(input.value) || 0;

        let skorKategori = 1;
        let kategori = 'Posisi Zero';

        if (value > 1001) {
            skorKategori = 9;
            kategori = 'Unggulan A';
        } else if (value >= 751) {
            skorKategori = 8;
            kategori = 'Unggulan B';
        } else if (value >= 501) {
            skorKategori = 7;
            kategori = 'Mandiri A';
        } else if (value >= 251) {
            skorKategori = 6;
            kategori = 'Mandiri B';
        } else if (value >= 151) {
            skorKategori = 5;
            kategori = 'Pramandiri A';
        } else if (value >= 101) {
            skorKategori = 4;
            kategori = 'Pramandiri B';
        } else if (value >= 61) {
            skorKategori = 3;
            kategori = 'Rintisan A';
        } else if (value >= 20) {
            skorKategori = 2;
            kategori = 'Rintisan B';
        }

        // Calculate prestasi score
        const targetSiswaInput = document.getElementById('target_jumlah_siswa');
        let skorPrestasi = 0;
        let prestasiText = '';
        if (targetSiswaInput) {
            const target = parseInt(targetSiswaInput.value) || 0;
            if (value > target) {
                skorPrestasi = 2;
                prestasiText = ' (Prestasi: +2 - Melebihi Target)';
            } else if (value === target && value > 0) {
                skorPrestasi = 1;
                prestasiText = ' (Prestasi: +1 - Sesuai Target)';
            } else if (value < target && value > 0) {
                skorPrestasi = 0;
                prestasiText = ' (Prestasi: +0 - Di Bawah Target)';
            }
        }

        if (value > 0) {
            info.textContent = `Skor Kategori: ${skorKategori}, Kategori: ${kategori}${prestasiText}`;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }

        // Update total score when siswa info changes
        updateTotalSkor();
    }

    // Function to update alumni percentage info
    function updateAlumniInfo(inputId, infoId) {
        const input = document.getElementById(inputId);
        const info = document.getElementById(infoId);
        const value = parseInt(input.value.replace(/[^\d]/g, '')) || 0;

        let skorKategori = 2;
        let kategori = 'Rintisan B';

        if (value >= 81) {
            skorKategori = 9;
            kategori = 'Unggulan A';
        } else if (value >= 66) {
            skorKategori = 8;
            kategori = 'Unggulan B';
        } else if (value >= 51) {
            skorKategori = 7;
            kategori = 'Mandiri A';
        } else if (value >= 35) {
            skorKategori = 6;
            kategori = 'Mandiri B';
        } else if (value >= 20) {
            skorKategori = 5;
            kategori = 'Pramandiri A';
        } else if (value >= 10) {
            skorKategori = 4;
            kategori = 'Pramandiri B';
        } else if (value >= 3) {
            skorKategori = 3;
            kategori = 'Rintisan A';
        }

        // Calculate prestasi score
        const targetAlumniInput = document.getElementById('target_alumni');
        let skorPrestasi = 0;
        let prestasiText = '';
        if (targetAlumniInput) {
            const target = parseInt(targetAlumniInput.value.replace(/[^\d]/g, '')) || 0;
            if (value > target) {
                skorPrestasi = 2;
                prestasiText = ' (Prestasi: +2 - Melebihi Target)';
            } else if (value === target && value > 0) {
                skorPrestasi = 1;
                prestasiText = ' (Prestasi: +1 - Sesuai Target)';
            } else if (value < target && value > 0) {
                skorPrestasi = 0;
                prestasiText = ' (Prestasi: +0 - Di Bawah Target)';
            }
        }

        if (value > 0) {
            info.textContent = `Skor Kategori: ${skorKategori}, Kategori: ${kategori}${prestasiText}`;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }

        // Update total score when alumni info changes
        updateTotalSkor();
    }

    // Function to update dana info
    function updateDanaInfo(inputId, infoId) {
        const input = document.getElementById(inputId);
        const info = document.getElementById(infoId);
        const rawValue = parseInt(input.value.replace(/[^\d]/g, '')) || 0;
        const value = Math.floor(rawValue / 1000000); // Convert to millions

        let skorKategori = 1;
        let kategori = 'Posisi Zero';

        if (value > 5001) {
            skorKategori = 9;
            kategori = 'Unggulan A';
        } else if (value >= 3001) {
            skorKategori = 8;
            kategori = 'Unggulan B';
        } else if (value >= 2000) {
            skorKategori = 7;
            kategori = 'Mandiri A';
        } else if (value >= 1251) {
            skorKategori = 6;
            kategori = 'Mandiri B';
        } else if (value >= 751) {
            skorKategori = 5;
            kategori = 'Pramandiri A';
        } else if (value >= 351) {
            skorKategori = 4;
            kategori = 'Pramandiri B';
        } else if (value >= 151) {
            skorKategori = 3;
            kategori = 'Rintisan A';
        } else if (value >= 30) {
            skorKategori = 2;
            kategori = 'Rintisan B';
        }

        // Calculate prestasi score
        const targetDanaInput = document.getElementById('target_dana');
        let skorPrestasi = 0;
        let prestasiText = '';
        if (targetDanaInput) {
            const targetDana = parseInt(targetDanaInput.value.replace(/[^\d]/g, '')) || 0;
            if (rawValue > targetDana) {
                skorPrestasi = 2;
                prestasiText = ' (Prestasi: +2 - Melebihi Target)';
            } else if (rawValue === targetDana && rawValue > 0) {
                skorPrestasi = 1;
                prestasiText = ' (Prestasi: +1 - Sesuai Target)';
            } else if (rawValue < targetDana && rawValue > 0) {
                skorPrestasi = 0;
                prestasiText = ' (Prestasi: +0 - Di Bawah Target)';
            }
        }

        if (rawValue > 0) {
            info.textContent = `Skor Kategori: ${skorKategori}, Kategori: ${kategori}${prestasiText}`;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }

        // Update total score when dana info changes
        updateTotalSkor();
    }

    // Function to update accreditation info
    function updateAkreditasiInfo() {
        const select = document.getElementById('akreditasi');
        const info = document.getElementById('akreditasi_info');
        const value = select.value;

        let skor = 1; // Default for "Belum"
        let kategori = 'Belum';

        if (value === 'A') {
            skor = 10;
            kategori = 'Unggulan A';
        } else if (value === 'B') {
            skor = 7;
            kategori = 'Mandiri A';
        } else if (value === 'C') {
            skor = 4;
            kategori = 'Rintisan A';
        }

        if (value) {
            info.textContent = `Skor: ${skor}, Kategori: ${kategori}`;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }

        // Update total score when akreditasi info changes
        updateTotalSkor();
    }

    // Function to calculate and update total score
    function updateTotalSkor() {
        let totalSkor = 0;
        let skorSiswaKategori = 0;
        let skorSiswaPrestasi = 0;
        let skorDanaKategori = 0;
        let skorDanaPrestasi = 0;
        let skorAlumniKategori = 0;
        let skorAlumniPrestasi = 0;
        let skorAkreditasi = 0;

        // Get siswa kategori score (based on capaian jumlah siswa)
        const siswaInput = document.getElementById('capaian_jumlah_siswa');
        if (siswaInput) {
            const siswaValue = parseInt(siswaInput.value) || 0;
            if (siswaValue > 1001) skorSiswaKategori = 9;
            else if (siswaValue >= 751) skorSiswaKategori = 8;
            else if (siswaValue >= 501) skorSiswaKategori = 7;
            else if (siswaValue >= 251) skorSiswaKategori = 6;
            else if (siswaValue >= 151) skorSiswaKategori = 5;
            else if (siswaValue >= 101) skorSiswaKategori = 4;
            else if (siswaValue >= 61) skorSiswaKategori = 3;
            else if (siswaValue >= 20) skorSiswaKategori = 2;
            else if (siswaValue > 0) skorSiswaKategori = 1;
            totalSkor += skorSiswaKategori;
        }

        // Get siswa prestasi score (based on comparison with target)
        const targetSiswaInput = document.getElementById('target_jumlah_siswa');
        if (siswaInput && targetSiswaInput) {
            const capaian = parseInt(siswaInput.value) || 0;
            const target = parseInt(targetSiswaInput.value) || 0;
            if (capaian > target) skorSiswaPrestasi = 2;
            else if (capaian === target && capaian > 0) skorSiswaPrestasi = 1;
            else skorSiswaPrestasi = 0;
            totalSkor += skorSiswaPrestasi;
        }

        // Get dana kategori score (based on capaian dana)
        const danaInput = document.getElementById('capaian_dana');
        if (danaInput) {
            const danaRawValue = parseInt(danaInput.value.replace(/[^\d]/g, '')) || 0;
            const danaValue = Math.floor(danaRawValue / 1000000); // Convert to millions
            if (danaValue > 5001) skorDanaKategori = 9;
            else if (danaValue >= 3001) skorDanaKategori = 8;
            else if (danaValue >= 2000) skorDanaKategori = 7;
            else if (danaValue >= 1251) skorDanaKategori = 6;
            else if (danaValue >= 751) skorDanaKategori = 5;
            else if (danaValue >= 351) skorDanaKategori = 4;
            else if (danaValue >= 151) skorDanaKategori = 3;
            else if (danaValue >= 30) skorDanaKategori = 2;
            else if (danaRawValue > 0) skorDanaKategori = 1;
            totalSkor += skorDanaKategori;
        }

        // Get dana prestasi score (based on comparison with target)
        const targetDanaInput = document.getElementById('target_dana');
        if (danaInput && targetDanaInput) {
            const capaianDana = parseInt(danaInput.value.replace(/[^\d]/g, '')) || 0;
            const targetDana = parseInt(targetDanaInput.value.replace(/[^\d]/g, '')) || 0;
            if (capaianDana > targetDana) skorDanaPrestasi = 2;
            else if (capaianDana === targetDana) skorDanaPrestasi = 1;
            else skorDanaPrestasi = 0;
            totalSkor += skorDanaPrestasi;
        }

        // Get alumni kategori score (based on capaian alumni)
        const alumniInput = document.getElementById('capaian_alumni');
        if (alumniInput) {
            const alumniValue = parseInt(alumniInput.value.replace(/[^\d]/g, '')) || 0;
            if (alumniValue >= 81) skorAlumniKategori = 9;
            else if (alumniValue >= 66) skorAlumniKategori = 8;
            else if (alumniValue >= 51) skorAlumniKategori = 7;
            else if (alumniValue >= 35) skorAlumniKategori = 6;
            else if (alumniValue >= 20) skorAlumniKategori = 5;
            else if (alumniValue >= 10) skorAlumniKategori = 4;
            else if (alumniValue >= 3) skorAlumniKategori = 3;
            else if (alumniValue >= 1) skorAlumniKategori = 2;
            totalSkor += skorAlumniKategori;
        }

        // Get alumni prestasi score (based on comparison with target)
        const targetAlumniInput = document.getElementById('target_alumni');
        if (alumniInput && targetAlumniInput) {
            const capaian = parseInt(alumniInput.value.replace(/[^\d]/g, '')) || 0;
            const target = parseInt(targetAlumniInput.value.replace(/[^\d]/g, '')) || 0;
            if (capaian > target) skorAlumniPrestasi = 2;
            else if (capaian === target && capaian > 0) skorAlumniPrestasi = 1;
            else skorAlumniPrestasi = 0;
            totalSkor += skorAlumniPrestasi;
        }

        // Get akreditasi score
        const akreditasiSelect = document.getElementById('akreditasi');
        if (akreditasiSelect) {
            const akreditasiValue = akreditasiSelect.value;
            if (akreditasiValue === 'A') skorAkreditasi = 10;
            else if (akreditasiValue === 'B') skorAkreditasi = 7;
            else if (akreditasiValue === 'C') skorAkreditasi = 4;
            else if (akreditasiValue === 'Belum') skorAkreditasi = 1;
            totalSkor += skorAkreditasi;
        }

        // Update total score field
        const totalSkorField = document.getElementById('total_skor');
        if (totalSkorField) {
            totalSkorField.value = totalSkor;
        }

        // Update breakdown display
        document.getElementById('skor_siswa_kategori').textContent = `Skor Kategori Siswa: ${skorSiswaKategori}`;
        document.getElementById('skor_siswa_prestasi').textContent = `Skor Prestasi Siswa: ${skorSiswaPrestasi}`;
        document.getElementById('skor_dana_kategori').textContent = `Skor Kategori Dana: ${skorDanaKategori}`;
        document.getElementById('skor_dana_prestasi').textContent = `Skor Prestasi Dana: ${skorDanaPrestasi}`;
        document.getElementById('skor_alumni').textContent = `Skor Alumni: ${skorAlumni}`;
        document.getElementById('skor_akreditasi').textContent = `Skor Akreditasi: ${skorAkreditasi}`;
        document.getElementById('total_breakdown').textContent = `Total: ${totalSkor}`;
    }
</script>
