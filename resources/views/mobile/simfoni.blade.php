@extends('layouts.mobile')

@section('title', 'Simfoni')
@section('subtitle', 'Data SK Tenaga Pendidik')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb url('{{ asset("images/bg.png") }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .simfoni-header {
            /* background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); */
            color: white;
            padding: 20px 16px;
            text-align: center;
            position: relative;
            /* margin-bottom: 16px; */
        }

        .simfoni-header .back-button {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .form-body {
            padding: 0;
        }

        .section-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: #212121 0px 2px 4px -1px;
            /* border-left: 4px solid #004b4c; */
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
            font-size: 14px;
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
            font-size: 12px;
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

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="tel"]:focus,
        .form-group input[type="number"]:focus,
        .form-group input[type="date"]:focus,
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

        .submit-btn:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 75, 76, 0.4);
        }

        .submit-btn i {
            font-size: 16px;
        }

        .navigation-container {
            background: #f8f9fa;
            padding: 16px;
            border-top: 1px solid #e9ecef;
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

        .nav-btn:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            transform: translateY(-1px);
        }

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .step-indicator {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .back-button:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .back-button i {
            font-size: 16px;
            margin-right: 6px;
        }

        .currency-prefix {
            position: relative;
        }

        .currency-prefix::before {
            content: 'Rp ';
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #999;
            font-weight: 600;
        }

        .currency-prefix input {
            padding-left: 35px !important;
        }

        .auto-fill {
            background: #f0ebf5;
            font-size: 11px;
            color: #666;
        }

        .auto-fill:focus {
            background: #fff;
        }

        .step-timeline {
            background: #f8f9fa;
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
        }

        .timeline-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 300px;
            margin: 0 auto;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }

        .timeline-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 12px;
            left: 50%;
            width: calc(100% - 24px);
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }

        .timeline-step.completed:not(:last-child)::after {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        }

        .timeline-step-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e9ecef;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .timeline-step.active .timeline-step-circle {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            transform: scale(1.1);
        }

        .timeline-step.completed .timeline-step-circle {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
        }

        .timeline-step-label {
            font-size: 9px;
            color: #666;
            margin-top: 4px;
            text-align: center;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .timeline-step.active .timeline-step-label {
            color: #004b4c;
            font-weight: 600;
        }

        .timeline-step.completed .timeline-step-label {
            color: #004b4c;
        }
    </style>

    <!-- Header -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
    </div>
    <div class="simfoni-header" style="margin-top: -10px;">
        <h4>SIMFONI MINI GTK LPMNU DIY</h4>
        <p>Tahun 2025</p>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <!-- Step Timeline -->
        <div class="step-timeline">
            <div class="timeline-steps">
                <div class="timeline-step active" data-step="1">
                    <div class="timeline-step-circle">1</div>
                    <div class="timeline-step-label">Data SK</div>
                </div>
                <div class="timeline-step" data-step="2">
                    <div class="timeline-step-circle">2</div>
                    <div class="timeline-step-label">Riwayat Kerja</div>
                </div>
                <div class="timeline-step" data-step="3">
                    <div class="timeline-step-circle">3</div>
                    <div class="timeline-step-label">Keahlian</div>
                </div>
                <div class="timeline-step" data-step="4">
                    <div class="timeline-step-circle">4</div>
                    <div class="timeline-step-label">Keuangan</div>
                </div>
            </div>
        </div>
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
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bx bx-id-card"></i>
                            </div>
                            <h6 class="section-title">A. DATA SK</h6>
                        </div>
                        <div class="section-content">
                    <div class="form-group required">
                        <label>Nama Lengkap dengan Gelar</label>
                        <input type="text" name="nama_lengkap_gelar" value="{{ old('nama_lengkap_gelar', $simfoni->nama_lengkap_gelar ?? '') }}" class="auto-fill" readonly>
                        <div class="form-hint">Otomatis dari data user</div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $simfoni->tempat_lahir ?? '') }}" class="auto-fill" readonly>
                        </div>
                        <div class="form-group required">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $simfoni->tanggal_lahir ?? '') }}" class="auto-fill" readonly>
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>NUPTK</label>
                            <input type="text" name="nuptk" value="{{ old('nuptk', $simfoni->nuptk ?? '') }}" class="auto-fill" readonly>
                        </div>
                        <div class="form-group">
                            <label>Karta-NU</label>
                            <input type="text" name="kartanu" value="{{ old('kartanu', $simfoni->kartanu ?? '') }}" class="auto-fill" readonly>
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>NIP Ma'arif Baru</label>
                            <input type="text" name="nipm" value="{{ old('nipm', $simfoni->nipm ?? '') }}" class="auto-fill" readonly>
                        </div>
                        <div class="form-group required">
                            <label>NIK</label>
                            <input type="text" name="nik" value="{{ old('nik', $simfoni->nik ?? '') }}" required>
                            @error('nik')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>TMT Pertama</label>
                            <input type="date" name="tmt" value="{{ old('tmt', $simfoni->tmt ?? '') }}" class="auto-fill" readonly>
                        </div>
                        <div class="form-group required">
                            <label>Strata Pendidikan</label>
                            <input type="text" name="strata_pendidikan" value="{{ old('strata_pendidikan', $simfoni->strata_pendidikan ?? '') }}" class="auto-fill" readonly>
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>PT Asal</label>
                            <input type="text" name="pt_asal" value="{{ old('pt_asal', $simfoni->pt_asal ?? '') }}" placeholder="Nama Perguruan Tinggi">
                            @error('pt_asal')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $simfoni->tahun_lulus ?? '') }}" min="1900" max="2100" placeholder="YYYY" required>
                            @error('tahun_lulus')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group required">
                        <label>Nama Program Studi</label>
                        <input type="text" name="program_studi" value="{{ old('program_studi', $simfoni->program_studi ?? '') }}" class="auto-fill" readonly>
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: B. RIWAYAT KERJA -->
                <div class="step-content" data-step="2" style="display: none;">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bx bx-briefcase"></i>
                            </div>
                            <h6 class="section-title">B. RIWAYAT KERJA</h6>
                        </div>
                        <div class="section-content">
                    <div class="form-group required">
                        <label>Status Kerja Saat Ini</label>
                        <select name="status_kerja" required>
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

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Tanggal SK Pertama</label>
                            <input type="date" name="tanggal_sk_pertama" value="{{ old('tanggal_sk_pertama', $simfoni->tanggal_sk_pertama ?? '') }}" required>
                            @error('tanggal_sk_pertama')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>Nomor SK Pertama</label>
                            <input type="text" name="nomor_sk_pertama" value="{{ old('nomor_sk_pertama', $simfoni->nomor_sk_pertama ?? '') }}" required>
                            @error('nomor_sk_pertama')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nomor Sertifikasi Pendidik</label>
                        <input type="text" name="nomor_sertifikasi_pendidik" value="{{ old('nomor_sertifikasi_pendidik', $simfoni->nomor_sertifikasi_pendidik ?? '') }}" placeholder="Nomor sertifikat pendidik (jika ada)">
                    </div>

                    <div class="form-group">
                        <label>Riwayat Kerja Sebelumnya</label>
                        <textarea name="riwayat_kerja_sebelumnya" placeholder="Ceritakan pengalaman kerja sebelumnya...">{{ old('riwayat_kerja_sebelumnya', $simfoni->riwayat_kerja_sebelumnya ?? '') }}</textarea>
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: C. KEAHLIAN DAN DATA LAIN -->
                <div class="step-content" data-step="3" style="display: none;">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bx bx-star"></i>
                            </div>
                            <h6 class="section-title">C. KEAHLIAN DAN DATA LAIN</h6>
                        </div>
                        <div class="section-content">
                    <div class="form-group">
                        <label>Keahlian</label>
                        <textarea name="keahlian" placeholder="Sebutkan keahlian khusus Anda...">{{ old('keahlian', $simfoni->keahlian ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Kedudukan di LPM</label>
                        <input type="text" name="kedudukan_lpm" value="{{ old('kedudukan_lpm', $simfoni->kedudukan_lpm ?? '') }}" placeholder="Posisi di Lembaga Pendidikan Masyarakat">
                    </div>

                    <div class="form-group">
                        <label>Prestasi</label>
                        <textarea name="prestasi" placeholder="Pencapaian atau prestasi yang diraih...">{{ old('prestasi', $simfoni->prestasi ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Tahun Sertifikasi & Impassing</label>
                        <input type="text" name="tahun_sertifikasi_impassing" value="{{ old('tahun_sertifikasi_impassing', $simfoni->tahun_sertifikasi_impassing ?? '') }}" placeholder="Contoh: 2015 & 2018">
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Nomor HP/WA</label>
                            <input type="tel" name="no_hp" value="{{ old('no_hp', $simfoni->no_hp ?? '') }}" class="auto-fill" readonly>
                        </div>
                        <div class="form-group required">
                            <label>E-mail Aktif</label>
                            <input type="email" name="email" value="{{ old('email', $simfoni->email ?? '') }}" class="auto-fill" readonly>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label>Status Pernikahan</label>
                        <select name="status_pernikahan" required>
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
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" required>{{ old('alamat_lengkap', $simfoni->alamat_lengkap ?? '') }}</textarea>
                        @error('alamat_lengkap')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                {{-- </div> --}}

                <!-- Step 4: D. DATA KEUANGAN/KESEJAHTERAAN -->
                <div class="step-content" data-step="4" style="display: none;">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bx bx-money"></i>
                            </div>
                            <h6 class="section-title">D. DATA KEUANGAN/KESEJAHTERAAN</h6>
                        </div>
                        <div class="section-content">
                    <div class="row-2col">
                        <div class="form-group">
                            <label>Bank</label>
                            <input type="text" name="bank" value="{{ old('bank', $simfoni->bank ?? '') }}" placeholder="Nama Bank">
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $simfoni->nomor_rekening ?? '') }}" placeholder="No Rekening">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gaji Sertifikasi</label>
                        <div class="currency-prefix">
                            <input type="number" name="gaji_sertifikasi" value="{{ old('gaji_sertifikasi', $simfoni->gaji_sertifikasi ?? '') }}" min="0" step="0.01" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gaji Pokok Perbulan dari Satpen</label>
                        <div class="currency-prefix">
                            <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $simfoni->gaji_pokok ?? '') }}" min="0" step="0.01" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Honor Lain</label>
                        <div class="currency-prefix">
                            <input type="number" name="honor_lain" value="{{ old('honor_lain', $simfoni->honor_lain ?? '') }}" min="0" step="0.01" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Penghasilan Lain</label>
                        <div class="currency-prefix">
                            <input type="number" name="penghasilan_lain" value="{{ old('penghasilan_lain', $simfoni->penghasilan_lain ?? '') }}" min="0" step="0.01" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Penghasilan Pasangan (tidak dihitung)</label>
                        <div class="currency-prefix">
                            <input type="number" name="penghasilan_pasangan" value="{{ old('penghasilan_pasangan', $simfoni->penghasilan_pasangan ?? '') }}" min="0" step="0.01" placeholder="0">
                        </div>
                        <div class="form-hint">Informasi ini tidak masuk dalam perhitungan total</div>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Total Penghasilan Diri</label>
                        <div class="currency-prefix">
                            <input type="number" name="total_penghasilan" value="{{ old('total_penghasilan', $simfoni->total_penghasilan ?? '') }}" min="0" step="0.01" placeholder="0" id="totalPenghasilan">
                        </div>
                        <div class="form-hint">Otomatis: Gaji Sertifikasi + Gaji Pokok + Honor Lain + Penghasilan Lain</div>
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="navigation-container">
                    <div class="nav-buttons">
                        <button type="button" class="nav-btn nav-prev" id="prevBtn" style="display: none;">
                            <i class="bx bx-chevron-left"></i> Sebelumnya
                        </button>
                        <div class="step-indicator">
                            Langkah <span id="currentStep">1</span> dari 4
                        </div>
                        <button type="button" class="nav-btn nav-next" id="nextBtn">
                            Selanjutnya <i class="bx bx-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button (Hidden initially) -->
                <div class="submit-container" id="submitContainer" style="display: none;">
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
