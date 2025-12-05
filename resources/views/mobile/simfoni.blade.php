@extends('layouts.mobile')

@section('title', 'Simfoni')
@section('subtitle', 'Data SK Tenaga Pendidik')

@section('content')
<div class="container-fluid p-0" style="max-width: 420px; margin: auto;">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --accent-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        body {
            background: var(--primary-gradient);
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background: var(--secondary-gradient);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateX(-50%) translateY(-50%) rotate(0deg); }
            100% { transform: translateX(-50%) translateY(-50%) rotate(360deg); }
        }

        .hero-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
        }

        .hero-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .progress-container {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 15px;
            margin: 0 20px 20px 20px;
            position: relative;
            z-index: 2;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .progress-title {
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .progress-step {
            font-size: 11px;
            color: rgba(255,255,255,0.8);
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--accent-gradient);
            border-radius: 3px;
            transition: width 0.5s ease;
            width: 25%;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px 20px 0 0;
            margin-top: -15px;
            padding: 25px 20px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
            min-height: calc(100vh - 200px);
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.8);
            transition: all 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.12);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f8f9fa;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 12px;
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .form-group.required .form-label::after {
            content: ' *';
            color: #e53e3e;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .form-control.readonly {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 50%, #4facfe 100%);
            color: white;
            font-weight: 600;
            border: none;
            cursor: not-allowed;
        }

        .form-control.readonly:focus {
            box-shadow: 0 0 0 3px rgba(240, 147, 251, 0.2);
        }

        .form-hint {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 4px;
            font-style: italic;
        }

        .form-error {
            color: #e53e3e;
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-error::before {
            content: '⚠';
            font-size: 12px;
        }

        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            border: 1px solid rgba(72, 187, 120, 0.3);
        }

        .alert-error {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            border: 1px solid rgba(245, 101, 101, 0.3);
        }

        .currency-input {
            position: relative;
        }

        .currency-input::before {
            content: 'Rp';
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #a0aec0;
            font-weight: 600;
            z-index: 2;
        }

        .currency-input .form-control {
            padding-left: 40px;
        }

        .navigation-section {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .nav-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 100px;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .nav-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .submit-section {
            background: var(--accent-gradient);
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.3);
            margin-top: 20px;
        }

        .submit-btn {
            background: white;
            color: #38f9d7;
            border: 2px solid white;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(255,255,255,0.3);
            width: 100%;
            justify-content: center;
        }

        .submit-btn:hover {
            background: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,255,255,0.4);
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 25px 15px;
            }

            .form-container {
                padding: 20px 15px;
            }

            .form-card {
                padding: 16px;
            }
        }
    </style>

    <!-- Hero Section with Timeline -->
    <div class="hero-section">
        <button onclick="history.back()" class="back-btn">
            <i class="bx bx-arrow-back" style="font-size: 18px;"></i>
        </button>

        <div class="hero-title">SIMFONI MINI GTK LPMNU DIY</div>
        <div class="hero-subtitle">Tahun 2025</div>

        <!-- Progress Timeline -->
        <div class="progress-container">
            <div class="progress-header">
                <div class="progress-title">Progress Pengisian Form</div>
                <div class="progress-step" id="currentStepDisplay">Langkah 1 dari 4</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 25%;"></div>
            </div>
        </div>
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

            <form action="{{ route('mobile.simfoni.store') }}" method="POST" id="simfoniForm">
                @csrf

                <!-- Step 1: A. DATA SK -->
                <div class="step-content active" data-step="1">
                    <div class="form-card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="bx bx-id-card"></i>
                            </div>
                            <h6 class="card-title">A. DATA SK</h6>
                        </div>

                        <div class="form-group required">
                            <label class="form-label">Nama Lengkap dengan Gelar</label>
                            <input type="text" name="nama_lengkap_gelar" value="{{ old('nama_lengkap_gelar', $simfoni->nama_lengkap_gelar ?? '') }}" class="form-control readonly" readonly>
                            <div class="form-hint">Otomatis dari data user</div>
                        </div>

                        <div class="grid-2col">
                            <div class="form-group required">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $simfoni->tempat_lahir ?? '') }}" class="form-control readonly" readonly>
                            </div>
                            <div class="form-group required">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $simfoni->tanggal_lahir ?? '') }}" class="form-control readonly" readonly>
                            </div>
                        </div>

                        <div class="grid-2col">
                            <div class="form-group">
                                <label class="form-label">NUPTK</label>
                                <input type="text" name="nuptk" value="{{ old('nuptk', $simfoni->nuptk ?? '') }}" class="form-control readonly" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Karta-NU</label>
                                <input type="text" name="kartanu" value="{{ old('kartanu', $simfoni->kartanu ?? '') }}" class="form-control readonly" readonly>
                            </div>
                        </div>

                        <div class="grid-2col">
                            <div class="form-group">
                                <label class="form-label">NIP Ma'arif Baru</label>
                                <input type="text" name="nipm" value="{{ old('nipm', $simfoni->nipm ?? '') }}" class="form-control readonly" readonly>
                            </div>
                            <div class="form-group required">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik', $simfoni->nik ?? '') }}" class="form-control" required>
                                @error('nik')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid-2col">
                            <div class="form-group required">
                                <label class="form-label">TMT Pertama</label>
                                <input type="date" name="tmt" value="{{ old('tmt', $simfoni->tmt ?? '') }}" class="form-control readonly" readonly>
                            </div>
                            <div class="form-group required">
                                <label class="form-label">Strata Pendidikan</label>
                                <input type="text" name="strata_pendidikan" value="{{ old('strata_pendidikan', $simfoni->strata_pendidikan ?? '') }}" class="form-control readonly" readonly>
                            </div>
                        </div>

                        <div class="grid-2col">
                            <div class="form-group">
                                <label class="form-label">PT Asal</label>
                                <input type="text" name="pt_asal" value="{{ old('pt_asal', $simfoni->pt_asal ?? '') }}" class="form-control" placeholder="Nama Perguruan Tinggi">
                                @error('pt_asal')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group required">
                                <label class="form-label">Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $simfoni->tahun_lulus ?? '') }}" min="1900" max="2100" placeholder="YYYY" class="form-control" required>
                                @error('tahun_lulus')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group required">
                            <label class="form-label">Nama Program Studi</label>
                            <input type="text" name="program_studi" value="{{ old('program_studi', $simfoni->program_studi ?? '') }}" class="form-control readonly" readonly>
                        </div>
                    </div>
                </div>

                <!-- Step 2: B. RIWAYAT KERJA -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="form-card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="bx bx-briefcase"></i>
                            </div>
                            <h6 class="card-title">B. RIWAYAT KERJA</h6>
                        </div>

                        <div class="form-group required">
                            <label class="form-label">Status Kerja Saat Ini</label>
                            <select name="status_kerja" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="PNS" {{ old('status_kerja', $simfoni->status_kerja ?? '') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                <option value="PPPK" {{ old('status_kerja', $simfoni->status_kerja ?? '') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                <option value="Honorer" {{ old('status_kerja', $simfoni->status_kerja ?? '') == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                <option value="Yayasan" {{ old('status_kerja', $simfoni->status_kerja ?? '') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                            </select>
                            @error('status_kerja')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid-2col">
                            <div class="form-group required">
                                <label class="form-label">Tanggal SK Pertama</label>
                                <input type="date" name="tanggal_sk_pertama" value="{{ old('tanggal_sk_pertama', $simfoni->tanggal_sk_pertama ?? '') }}" class="form-control" required>
                                @error('tanggal_sk_pertama')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group required">
                                <label class="form-label">Nomor SK Pertama</label>
                                <input type="text" name="nomor_sk_pertama" value="{{ old('nomor_sk_pertama', $simfoni->nomor_sk_pertama ?? '') }}" class="form-control" required>
                                @error('nomor_sk_pertama')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Sertifikasi Pendidik</label>
                            <input type="text" name="nomor_sertifikasi_pendidik" value="{{ old('nomor_sertifikasi_pendidik', $simfoni->nomor_sertifikasi_pendidik ?? '') }}" class="form-control" placeholder="Nomor sertifikat pendidik (jika ada)">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Riwayat Kerja Sebelumnya</label>
                            <textarea name="riwayat_kerja_sebelumnya" class="form-control" placeholder="Ceritakan pengalaman kerja sebelumnya...">{{ old('riwayat_kerja_sebelumnya', $simfoni->riwayat_kerja_sebelumnya ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 3: C. KEAHLIAN DAN DATA LAIN -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="form-card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="bx bx-star"></i>
                            </div>
                            <h6 class="card-title">C. KEAHLIAN DAN DATA LAIN</h6>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keahlian</label>
                            <textarea name="keahlian" class="form-control" placeholder="Sebutkan keahlian khusus Anda...">{{ old('keahlian', $simfoni->keahlian ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kedudukan di LPM</label>
                            <input type="text" name="kedudukan_lpm" value="{{ old('kedudukan_lpm', $simfoni->kedudukan_lpm ?? '') }}" class="form-control" placeholder="Posisi di Lembaga Pendidikan Masyarakat">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Prestasi</label>
                            <textarea name="prestasi" class="form-control" placeholder="Pencapaian atau prestasi yang diraih...">{{ old('prestasi', $simfoni->prestasi ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tahun Sertifikasi & Impassing</label>
                            <input type="text" name="tahun_sertifikasi_impassing" value="{{ old('tahun_sertifikasi_impassing', $simfoni->tahun_sertifikasi_impassing ?? '') }}" class="form-control" placeholder="Contoh: 2015 & 2018">
                        </div>

                        <div class="grid-2col">
                            <div class="form-group required">
                                <label class="form-label">Nomor HP/WA</label>
                                <input type="tel" name="no_hp" value="{{ old('no_hp', $simfoni->no_hp ?? '') }}" class="form-control readonly" readonly>
                            </div>
                            <div class="form-group required">
                                <label class="form-label">E-mail Aktif</label>
                                <input type="email" name="email" value="{{ old('email', $simfoni->email ?? '') }}" class="form-control readonly" readonly>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label class="form-label">Status Pernikahan</label>
                            <select name="status_pernikahan" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Belum Kawin" {{ old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                <option value="Kawin" {{ old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Cerai Hidup" {{ old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                <option value="Cerai Mati" {{ old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
                            @error('status_pernikahan')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group required">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control" required>{{ old('alamat_lengkap', $simfoni->alamat_lengkap ?? '') }}</textarea>
                            @error('alamat_lengkap')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                <!-- Step 4: D. DATA KEUANGAN/KESEJAHTERAAN -->
                <div class="step-content" data-step="4" style="display: none;">
                    <div class="form-card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="bx bx-money"></i>
                            </div>
                            <h6 class="card-title">D. DATA KEUANGAN/KESEJAHTERAAN</h6>
                        </div>

                        <div class="grid-2col">
                            <div class="form-group">
                                <label class="form-label">Bank</label>
                                <input type="text" name="bank" value="{{ old('bank', $simfoni->bank ?? '') }}" class="form-control" placeholder="Nama Bank">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $simfoni->nomor_rekening ?? '') }}" class="form-control" placeholder="No Rekening">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Gaji Sertifikasi</label>
                            <div class="currency-input">
                                <input type="number" name="gaji_sertifikasi" value="{{ old('gaji_sertifikasi', $simfoni->gaji_sertifikasi ?? '') }}" min="0" step="0.01" placeholder="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Gaji Pokok Perbulan dari Satpen</label>
                            <div class="currency-input">
                                <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $simfoni->gaji_pokok ?? '') }}" min="0" step="0.01" placeholder="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Honor Lain</label>
                            <div class="currency-input">
                                <input type="number" name="honor_lain" value="{{ old('honor_lain', $simfoni->honor_lain ?? '') }}" min="0" step="0.01" placeholder="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Penghasilan Lain</label>
                            <div class="currency-input">
                                <input type="number" name="penghasilan_lain" value="{{ old('penghasilan_lain', $simfoni->penghasilan_lain ?? '') }}" min="0" step="0.01" placeholder="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Penghasilan Pasangan (tidak dihitung)</label>
                            <div class="currency-input">
                                <input type="number" name="penghasilan_pasangan" value="{{ old('penghasilan_pasangan', $simfoni->penghasilan_pasangan ?? '') }}" min="0" step="0.01" placeholder="0" class="form-control">
                            </div>
                            <div class="form-hint">Informasi ini tidak masuk dalam perhitungan total</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jumlah Total Penghasilan Diri</label>
                            <div class="currency-input">
                                <input type="number" name="total_penghasilan" value="{{ old('total_penghasilan', $simfoni->total_penghasilan ?? '') }}" min="0" step="0.01" placeholder="0" id="totalPenghasilan" class="form-control">
                            </div>
                            <div class="form-hint">Otomatis: Gaji Sertifikasi + Gaji Pokok + Honor Lain + Penghasilan Lain</div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="navigation-section">
                    <div class="nav-buttons">
                        <button type="button" class="nav-btn nav-prev" id="prevBtn" style="display: none;">
                            <i class="bx bx-chevron-left"></i> Sebelumnya
                        </button>
                        <button type="button" class="nav-btn nav-next" id="nextBtn">
                            Selanjutnya <i class="bx bx-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button (Hidden initially) -->
                <div class="submit-section" id="submitContainer" style="display: none;">
                    <button type="submit" class="submit-btn">
                        <i class="bx bx-save"></i> SIMPAN DATA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-fill readonly fields
        document.addEventListener('DOMContentLoaded', function() {
            const nama = document.querySelector('input[name="nama_lengkap_gelar"]');
            const tempat = document.querySelector('input[name="tempat_lahir"]');
            const tanggal = document.querySelector('input[name="tanggal_lahir"]');
            const nuptk = document.querySelector('input[name="nuptk"]');
            const kartanu = document.querySelector('input[name="kartanu"]');
            const nipm = document.querySelector('input[name="nipm"]');
            const tmt = document.querySelector('input[name="tmt"]');
            const strata = document.querySelector('input[name="strata_pendidikan"]');
            const prodi = document.querySelector('input[name="program_studi"]');
            const noHp = document.querySelector('input[name="no_hp"]');
            const email = document.querySelector('input[name="email"]');

            // Set default values from user data if empty
            if (!nama.value) nama.value = '{{ $user->name ?? "" }}';
            if (!tempat.value) tempat.value = '{{ $user->tempat_lahir ?? "" }}';
            if (!tanggal.value) tanggal.value = '{{ $user->tanggal_lahir ?? "" }}';
            if (!nuptk.value) nuptk.value = '{{ $user->nuptk ?? "" }}';
            if (!kartanu.value) kartanu.value = '{{ $user->kartanu ?? "" }}';
            if (!nipm.value) nipm.value = '{{ $user->nipm ?? "" }}';
            if (!tmt.value) tmt.value = '{{ $user->tmt ?? "" }}';
            if (!strata.value) strata.value = '{{ $user->pendidikan_terakhir ?? "" }}';
            if (!prodi.value) prodi.value = '{{ $user->program_studi ?? "" }}';
            if (!noHp.value) noHp.value = '{{ $user->phone ?? "" }}';
            if (!email.value) email.value = '{{ $user->email ?? "" }}';
        });

        // Multi-step form navigation
        let currentStep = 1;
        const totalSteps = 4;

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(stepEl => {
                stepEl.classList.remove('active');
                stepEl.style.display = 'none';
            });

            // Show current step
            const currentStepEl = document.querySelector(`.step-content[data-step="${step}"]`);
            if (currentStepEl) {
                currentStepEl.classList.add('active');
                currentStepEl.style.display = 'block';
            }

            // Update timeline
            document.querySelectorAll('.timeline-step').forEach((timelineStep, index) => {
                const stepNumber = index + 1;
                timelineStep.classList.remove('active', 'completed');

                if (stepNumber === step) {
                    timelineStep.classList.add('active');
                } else if (stepNumber < step) {
                    timelineStep.classList.add('completed');
                }
            });

            // Update step indicator
            document.getElementById('currentStep').textContent = step;

            // Update navigation buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitContainer = document.getElementById('submitContainer');

            if (step === 1) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'block';
                submitContainer.style.display = 'none';
            } else if (step === totalSteps) {
                prevBtn.style.display = 'block';
                nextBtn.style.display = 'none';
                submitContainer.style.display = 'block';
            } else {
                prevBtn.style.display = 'block';
                nextBtn.style.display = 'block';
                submitContainer.style.display = 'none';
            }
        }

        document.getElementById('nextBtn').addEventListener('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        });

        document.getElementById('prevBtn').addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Auto calculate total penghasilan
        document.getElementById('simfoniForm').addEventListener('change', function(e) {
            if (['gaji_sertifikasi', 'gaji_pokok', 'honor_lain', 'penghasilan_lain'].includes(e.target.name)) {
                const gaji_sertifikasi = parseFloat(document.querySelector('input[name="gaji_sertifikasi"]').value) || 0;
                const gaji_pokok = parseFloat(document.querySelector('input[name="gaji_pokok"]').value) || 0;
                const honor_lain = parseFloat(document.querySelector('input[name="honor_lain"]').value) || 0;
                const penghasilan_lain = parseFloat(document.querySelector('input[name="penghasilan_lain"]').value) || 0;

                const total = gaji_sertifikasi + gaji_pokok + honor_lain + penghasilan_lain;
                document.getElementById('totalPenghasilan').value = total.toFixed(2);
            }
        });
    </script>
</div>
@endsection
