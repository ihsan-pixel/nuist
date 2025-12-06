@extends('layouts.mobile')

@section('title', 'Simfoni')
@section('subtitle', 'Data SK Tenaga Pendidik')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
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

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .step-indicator {
            font-size: 10px;
            color: #666;
            font-weight: 600;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
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

        .step-timeline {
            background: #f8f9fa;
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
        }

        .timeline-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 400px;
            margin: 0 auto;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }

        /* Lines removed as requested */

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

        .timeline-step.active .timeline-step-circle,
        .timeline-step.completed .timeline-step-circle {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
        }

        .timeline-step-label {
            font-size: 5px;
            color: #666;
            margin-top: 4px;
            text-align: center;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .timeline-step.active .timeline-step-label,
        .timeline-step.completed .timeline-step-label {
            color: #004b4c;
            font-weight: 600;
        }
    </style>

    <!-- Header -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="window.location.href='{{ route('mobile.profile') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
    </div>

    <div class="simfoni-header" style="margin-top: -10px;">
        <h4>SIMFONI <span style="color: #efaa0c;">GTK LPMNU DIY</span></h4>
        <p>Tahun 2025</p>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <!-- Step Timeline -->
        <div class="step-timeline">
            {{-- <div class="step-title" id="currentStepTitle" style="text-align: center; font-weight: 600; color: #004b4c; margin-bottom: 16px; font-size: 14px;">Step 1: Data SK</div> --}}
        <div class="timeline-steps">
            <div class="timeline-step active" data-step="1">
                <div class="timeline-step-circle">1</div>
                {{-- <div class="timeline-step-label">Data SK</div> --}}
            </div>
            <div class="timeline-step" data-step="2">
                <div class="timeline-step-circle">2</div>
                {{-- <div class="timeline-step-label">Riwayat Kerja</div> --}}
            </div>
            <div class="timeline-step" data-step="3">
                <div class="timeline-step-circle">3</div>
                {{-- <div class="timeline-step-label">Keahlian</div> --}}
            </div>
            <div class="timeline-step" data-step="4">
                <div class="timeline-step-circle">4</div>
                {{-- <div class="timeline-step-label">Keuangan</div> --}}
            </div>
            <div class="timeline-step" data-step="5">
                <div class="timeline-step-circle">5</div>
                {{-- <div class="timeline-step-label">Kekaderan</div> --}}
            </div>
            <div class="timeline-step" data-step="6">
                <div class="timeline-step-circle">6</div>
                {{-- <div class="timeline-step-label">Keluarga</div> --}}
            </div>
            <div class="timeline-step" data-step="7">
                <div class="timeline-step-circle">7</div>
                {{-- <div class="timeline-step-label">Proyeksi</div> --}}
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
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap_gelar" value="{{ old('nama_lengkap_gelar', $simfoni->nama_lengkap_gelar ?? '') }}" placeholder="Nama Lengkap" required>
                        @error('nama_lengkap_gelar')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Gelar (Jika Memiliki)</label>
                        <input type="text" name="gelar" value="{{ old('gelar', $simfoni->gelar ?? '') }}" placeholder="Contoh: S.Pd., M.Pd., dll">
                        @error('gelar')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $simfoni->tempat_lahir ?? '') }}" placeholder="Tempat Lahir" required>
                        </div>
                        <div class="form-group required">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $simfoni->tanggal_lahir ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>NUPTK</label>
                            <input type="text" name="nuptk" value="{{ old('nuptk', $simfoni->nuptk ?? '') }}" placeholder="NUPTK (jika ada)">
                        </div>
                        <div class="form-group">
                            <label>Karta-NU</label>
                            <input type="text" name="kartanu" value="{{ old('kartanu', $simfoni->kartanu ?? '') }}" placeholder="Nomor Karta-NU (jika ada)">
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>NIP Ma'arif Baru</label>
                            <input type="text" name="nipm" value="{{ old('nipm', $simfoni->nipm ?? '') }}" placeholder="NIP Ma'arif Baru">
                        </div>
                        <div class="form-group required">
                            <label>NIK</label>
                            <input type="text" name="nik" value="{{ old('nik', $simfoni->nik ?? '') }}" placeholder="Nomor Induk Keluarga" required>
                            @error('nik')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>TMT Pertama</label>
                            <input type="date" name="tmt" value="{{ old('tmt', $simfoni->tmt ?? '') }}" placeholder="Terhitung Malai Tanggal" required id="tmtInput">
                        </div>
                        <div class="form-group required">
                            <label>Strata Pendidikan</label>
                            <input type="text" name="strata_pendidikan" value="{{ old('strata_pendidikan', $simfoni->strata_pendidikan ?? '') }}" placeholder="SMK, SMA, S1, S2, S3" required>
                        </div>
                    </div>

                        <div class="form-group">
                            <label>Masa Kerja</label>
                            <input type="text" id="masaKerja" name="masa_kerja" readonly style="background: #f8f9fa; color: #666;">
                            <div class="form-hint" style="font-style: italic">Otomatis: Dari TMT Pertama sampai Juni 2025</div>
                        </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Perguruan Tinggi Asal</label>
                            <input type="text" name="pt_asal" value="{{ old('pt_asal', $simfoni->pt_asal ?? '') }}" placeholder="Nama Perguruan Tinggi">
                            <div class="form-hint" style="font-style: italic">Bukan Inisal</div>
                            @error('pt_asal')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $simfoni->tahun_lulus ?? '') }}" min="1900" max="2100" placeholder="2024" required>
                            @error('tahun_lulus')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group required">
                        <label>Program Studi</label>
                        <input type="text" name="program_studi" value="{{ old('program_studi', $simfoni->program_studi ?? '') }}" placeholder="Program Studi" required>
                        <div class="form-hint" style="font-style: italic">Bukan Inisal</div>
                    </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

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
                            <select name="status_kerja" id="statusKerjaSelect" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach($statusKepegawaian as $status)
                                    <option value="{{ $status->name }}" {{ old('status_kerja', $simfoni->status_kerja ?? '') == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-hint" style="font-style: italic">Jika masa kerja belum ada 2 tahun maka hanya tersedia sebagai GTT dan PTT</div>
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
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

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
                            <input type="text" name="kedudukan_lpm" value="{{ old('kedudukan_lpm', $simfoni->kedudukan_lpm ?? '') }}" placeholder="Posisi di Lembaga Pendidikan">
                        </div>

                        <div class="form-group">
                            <label>Prestasi</label>
                            <textarea name="prestasi" placeholder="Pencapaian atau prestasi yang diraih...">{{ old('prestasi', $simfoni->prestasi ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Tahun Sertifikasi & Impassing</label>
                            <input type="number" name="tahun_sertifikasi_impassing" value="{{ old('tahun_sertifikasi_impassing', $simfoni->tahun_sertifikasi_impassing ?? '') }}" placeholder="Contoh: 2015 & 2018" id="tahunSertifikasiInput">
                        </div>

                        <div class="row-2col">
                            <div class="form-group required">
                                <label>Nomor HP/WA</label>
                                <input type="tel" name="no_hp" value="{{ old('no_hp', $simfoni->no_hp ?? '') }}">
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
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

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
                                <input type="text" name="gaji_sertifikasi" value="{{ old('gaji_sertifikasi', $simfoni->gaji_sertifikasi ?? '') }}" placeholder="0" id="gajiSertifikasiInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Gaji Pokok Perbulan dari Satpen</label>
                            <div class="currency-prefix">
                                <input type="text" name="gaji_pokok" value="{{ old('gaji_pokok', $simfoni->gaji_pokok ?? '') }}" placeholder="0" id="gajiPokokInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Honor Lain</label>
                            <div class="currency-prefix">
                                <input type="text" name="honor_lain" value="{{ old('honor_lain', $simfoni->honor_lain ?? '') }}" placeholder="0" id="honorLainInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Penghasilan Lain</label>
                            <div class="currency-prefix">
                                <input type="text" name="penghasilan_lain" value="{{ old('penghasilan_lain', $simfoni->penghasilan_lain ?? '') }}" placeholder="0" id="penghasilanLainInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Penghasilan Pasangan (tidak dihitung)</label>
                            <div class="currency-prefix">
                                <input type="text" name="penghasilan_pasangan" value="{{ old('penghasilan_pasangan', $simfoni->penghasilan_pasangan ?? '') }}" placeholder="0" id="penghasilanPasanganInput">
                            </div>
                            <div class="form-hint" style="font-style: italic">Informasi ini tidak masuk dalam perhitungan total</div>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Total Penghasilan Diri</label>
                            <div class="currency-prefix">
                                <input type="text" name="total_penghasilan" value="{{ old('total_penghasilan', $simfoni->total_penghasilan ?? '') }}" placeholder="0" id="totalPenghasilan" readonly style="background: #f8f9fa; color: #666;">
                            </div>
                            <div class="form-hint" style="font-style: italic">Otomatis: Gaji Sertifikasi + Gaji Pokok + Honor Lain + Penghasilan Lain</div>
                        </div>

                        <div class="form-group">
                            <label>Kategori Penghasilan</label>
                            <input type="text" id="kategoriPenghasilan" name="kategori_penghasilan" readonly style="background: #f8f9fa; color: #666; font-weight: bold;" value="{{ old('kategori_penghasilan', $simfoni->kategori_penghasilan ?? '') }}">
                            <div class="form-hint" style="font-style: italic">
                                A = Bagus (≥ 10 juta)<br>
                                B = Baik (6,0 – 9,9 juta)<br>
                                C = Cukup (4,0 – 5,9 juta)<br>
                                D = Hampir Cukup (2,5 – 3,9 juta)<br>
                                E = Kurang (1,5 – 2,4 juta)<br>
                                F = Sangat Kurang (< 1,5 juta)
                            </div>
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 5: E. STATUS KEKADERAN -->
            <div class="step-content" data-step="5" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-group"></i>
                        </div>
                        <h6 class="section-title">E. STATUS KEKADERAN</h6>
                    </div>

                    <div class="section-content">
                        <div class="form-group">
                            <label>Status Kader Diri</label>
                            <select name="status_kader_diri">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" {{ old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Militan' ? 'selected' : '' }}>Militan</option>
                                <option value="Aktif" {{ old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Baru" {{ old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Non-NU" {{ old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Non-NU' ? 'selected' : '' }}>Non-NU</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pendidikan Kader yang Diikuti</label>
                            <input type="text" name="pendidikan_kader" value="{{ old('pendidikan_kader', $simfoni->pendidikan_kader ?? '') }}" placeholder="Pendidikan kader yang diikuti">
                        </div>

                        <div class="form-group">
                            <label>Status Kader Ayah</label>
                            <select name="status_kader_ayah">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" {{ old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Militan' ? 'selected' : '' }}>Militan</option>
                                <option value="Aktif" {{ old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Baru" {{ old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Non-NU" {{ old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Non-NU' ? 'selected' : '' }}>Non-NU</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status Kader Ibu</label>
                            <select name="status_kader_ibu">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" {{ old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Militan' ? 'selected' : '' }}>Militan</option>
                                <option value="Aktif" {{ old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Baru" {{ old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Non-NU" {{ old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Non-NU' ? 'selected' : '' }}>Non-NU</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status Kader Suami/Istri</label>
                            <select name="status_kader_pasangan">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" {{ old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Militan' ? 'selected' : '' }}>Militan</option>
                                <option value="Aktif" {{ old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Baru" {{ old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Non-NU" {{ old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Non-NU' ? 'selected' : '' }}>Non-NU</option>
                            </select>
                        </div>

                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 6: F. DATA KELUARGA -->
            <div class="step-content" data-step="6" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-family"></i>
                        </div>
                        <h6 class="section-title">F. DATA KELUARGA</h6>
                    </div>

                    <div class="section-content">
                        <div class="form-group">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $simfoni->nama_ayah ?? '') }}" placeholder="Nama lengkap ayah">
                        </div>

                        <div class="form-group">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $simfoni->nama_ibu ?? '') }}" placeholder="Nama lengkap ibu">
                        </div>

                        <div class="form-group">
                            <label>Nama Suami/Istri</label>
                            <input type="text" name="nama_pasangan" value="{{ old('nama_pasangan', $simfoni->nama_pasangan ?? '') }}" placeholder="Nama lengkap suami/istri">
                        </div>

                        <div class="form-group">
                            <label>Jumlah Anak Tanggungan</label>
                            <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak', $simfoni->jumlah_anak ?? '') }}" min="0" placeholder="0">
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 7: G. PROYEKSI KE DEPAN -->
            <div class="step-content" data-step="7" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-trending-up"></i>
                        </div>
                        <h6 class="section-title">G. PROYEKSI KE DEPAN</h6>
                    </div>

                    <div class="section-content">
                        <div class="table-responsive">
                            <table class="table table-sm" style="font-size: 10px;">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">No</th>
                                        <th>Pernyataan</th>
                                        <th style="width: 50px;">iya/sudah</th>
                                        <th style="width: 50px;">tidak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Akan kuliah S2</td>
                                        <td><input type="radio" name="akan_kuliah_s2" value="iya" {{ old('akan_kuliah_s2', $simfoni->akan_kuliah_s2 ?? '') == 'iya' ? 'checked' : '' }} required></td>
                                        <td><input type="radio" name="akan_kuliah_s2" value="tidak" {{ old('akan_kuliah_s2', $simfoni->akan_kuliah_s2 ?? '') == 'tidak' ? 'checked' : '' }} required></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Akan mendaftar PNS</td>
                                        <td><input type="radio" name="akan_mendaftar_pns" value="iya" {{ old('akan_mendaftar_pns', $simfoni->akan_mendaftar_pns ?? '') == 'iya' ? 'checked' : '' }} required></td>
                                        <td><input type="radio" name="akan_mendaftar_pns" value="tidak" {{ old('akan_mendaftar_pns', $simfoni->akan_mendaftar_pns ?? '') == 'tidak' ? 'checked' : '' }} required></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Akan mendaftar PPPK</td>
                                        <td><input type="radio" name="akan_mendaftar_pppk" value="iya" {{ old('akan_mendaftar_pppk', $simfoni->akan_mendaftar_pppk ?? '') == 'iya' ? 'checked' : '' }} required></td>
                                        <td><input type="radio" name="akan_mendaftar_pppk" value="tidak" {{ old('akan_mendaftar_pppk', $simfoni->akan_mendaftar_pppk ?? '') == 'tidak' ? 'checked' : '' }} required></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Akan mengikuti PPG</td>
                                        <td><input type="radio" name="akan_mengikuti_ppg" value="iya" {{ old('akan_mengikuti_ppg', $simfoni->akan_mengikuti_ppg ?? '') == 'iya' ? 'checked' : '' }} required></td>
                                        <td><input type="radio" name="akan_mengikuti_ppg" value="tidak" {{ old('akan_mengikuti_ppg', $simfoni->akan_mengikuti_ppg ?? '') == 'tidak' ? 'checked' : '' }} required></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Akan menulis buku/modul/riset</td>
                                        <td><input type="radio" name="akan_menulis_buku_modul_riset" value="iya" {{ old('akan_menulis_buku_modul_riset', $simfoni->akan_menulis_buku_modul_riset ?? '') == 'iya' ? 'checked' : '' }} required></td>
                                        <td><input type="radio" name="akan_menulis_buku_modul_riset" value="tidak" {{ old('akan_menulis_buku_modul_riset', $simfoni->akan_menulis_buku_modul_riset ?? '') == 'tidak' ? 'checked' : '' }} required></td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Akan mengikuti seleksi diklat CAKEP</td>
                                        <td><input type="radio" name="akan_mengikuti_seleksi_diklat_cakep" value="iya" {{ old('akan_mengikuti_seleksi_diklat_cakep', $simfoni->akan_mengikuti_seleksi_diklat_cakep ?? '') == 'iya' ? 'checked' : '' }} required></td>
                                        <td><input type="radio" name="akan_mengikuti_seleksi_diklat_cakep" value="tidak" {{ old('akan_mengikuti_seleksi_diklat_cakep', $simfoni->akan_mengikuti_seleksi_diklat_cakep ?? '') == 'tidak' ? 'checked' : '' }} required></td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Akan membimbing riset prestasi siswa</td>
                                        <td><input type="radio" name="akan_membimbing_riset_prestasi_siswa" value="iya" {{ old('akan_membimbing_riset_prestasi_siswa', $simfoni->akan_membimbing_riset_prestasi_siswa ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_membimbing_riset_prestasi_siswa" value="tidak" {{ old('akan_membimbing_riset_prestasi_siswa', $simfoni->akan_membimbing_riset_prestasi_siswa ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Akan masuk tim unggulan sekolah/madrasah</td>
                                        <td><input type="radio" name="akan_masuk_tim_unggulan_sekolah_madrasah" value="iya" {{ old('akan_masuk_tim_unggulan_sekolah_madrasah', $simfoni->akan_masuk_tim_unggulan_sekolah_madrasah ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_masuk_tim_unggulan_sekolah_madrasah" value="tidak" {{ old('akan_masuk_tim_unggulan_sekolah_madrasah', $simfoni->akan_masuk_tim_unggulan_sekolah_madrasah ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Akan kompetisi pimpinan level II</td>
                                        <td><input type="radio" name="akan_kompetisi_pimpinan_level_ii" value="iya" {{ old('akan_kompetisi_pimpinan_level_ii', $simfoni->akan_kompetisi_pimpinan_level_ii ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_kompetisi_pimpinan_level_ii" value="tidak" {{ old('akan_kompetisi_pimpinan_level_ii', $simfoni->akan_kompetisi_pimpinan_level_ii ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Akan aktif mengikuti pelatihan</td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_pelatihan" value="iya" {{ old('akan_aktif_mengikuti_pelatihan', $simfoni->akan_aktif_mengikuti_pelatihan ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_pelatihan" value="tidak" {{ old('akan_aktif_mengikuti_pelatihan', $simfoni->akan_aktif_mengikuti_pelatihan ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>Akan aktif MGMP/MKK</td>
                                        <td><input type="radio" name="akan_aktif_mgmp_mkk" value="iya" {{ old('akan_aktif_mgmp_mkk', $simfoni->akan_aktif_mgmp_mkk ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_aktif_mgmp_mkk" value="tidak" {{ old('akan_aktif_mgmp_mkk', $simfoni->akan_aktif_mgmp_mkk ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>Akan mengikuti pendidikan kader NU</td>
                                        <td><input type="radio" name="akan_mengikuti_pendidikan_kader_nu" value="iya" {{ old('akan_mengikuti_pendidikan_kader_nu', $simfoni->akan_mengikuti_pendidikan_kader_nu ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_mengikuti_pendidikan_kader_nu" value="tidak" {{ old('akan_mengikuti_pendidikan_kader_nu', $simfoni->akan_mengikuti_pendidikan_kader_nu ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>Akan aktif membantu kegiatan lembaga</td>
                                        <td><input type="radio" name="akan_aktif_membantu_kegiatan_lembaga" value="iya" {{ old('akan_aktif_membantu_kegiatan_lembaga', $simfoni->akan_aktif_membantu_kegiatan_lembaga ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_aktif_membantu_kegiatan_lembaga" value="tidak" {{ old('akan_aktif_membantu_kegiatan_lembaga', $simfoni->akan_aktif_membantu_kegiatan_lembaga ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>Akan aktif mengikuti kegiatan NU</td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_kegiatan_nu" value="iya" {{ old('akan_aktif_mengikuti_kegiatan_nu', $simfoni->akan_aktif_mengikuti_kegiatan_nu ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_kegiatan_nu" value="tidak" {{ old('akan_aktif_mengikuti_kegiatan_nu', $simfoni->akan_aktif_mengikuti_kegiatan_nu ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>Akan aktif ikut ZIS & kegiatan sosial</td>
                                        <td><input type="radio" name="akan_aktif_ikut_zis_kegiatan_sosial" value="iya" {{ old('akan_aktif_ikut_zis_kegiatan_sosial', $simfoni->akan_aktif_ikut_zis_kegiatan_sosial ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_aktif_ikut_zis_kegiatan_sosial" value="tidak" {{ old('akan_aktif_ikut_zis_kegiatan_sosial', $simfoni->akan_aktif_ikut_zis_kegiatan_sosial ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>Akan mengembangkan unit usaha satpen</td>
                                        <td><input type="radio" name="akan_mengembangkan_unit_usaha_satpen" value="iya" {{ old('akan_mengembangkan_unit_usaha_satpen', $simfoni->akan_mengembangkan_unit_usaha_satpen ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_mengembangkan_unit_usaha_satpen" value="tidak" {{ old('akan_mengembangkan_unit_usaha_satpen', $simfoni->akan_mengembangkan_unit_usaha_satpen ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>17</td>
                                        <td>Akan bekerja dengan disiplin & produktif</td>
                                        <td><input type="radio" name="akan_bekerja_disiplin_produktif" value="iya" {{ old('akan_bekerja_disiplin_produktif', $simfoni->akan_bekerja_disiplin_produktif ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_bekerja_disiplin_produktif" value="tidak" {{ old('akan_bekerja_disiplin_produktif', $simfoni->akan_bekerja_disiplin_produktif ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>18</td>
                                        <td>Akan loyal pada NU & aktif di masyarakat</td>
                                        <td><input type="radio" name="akan_loyal_nu_aktif_masyarakat" value="iya" {{ old('akan_loyal_nu_aktif_masyarakat', $simfoni->akan_loyal_nu_aktif_masyarakat ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_loyal_nu_aktif_masyarakat" value="tidak" {{ old('akan_loyal_nu_aktif_masyarakat', $simfoni->akan_loyal_nu_aktif_masyarakat ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                    <tr>
                                        <td>19</td>
                                        <td>Bersedia dipindah ke satpen lain</td>
                                        <td><input type="radio" name="akan_bersedia_dipindah_satpen_lain" value="iya" {{ old('akan_bersedia_dipindah_satpen_lain', $simfoni->akan_bersedia_dipindah_satpen_lain ?? '') == 'iya' ? 'checked' : '' }} requaired></td>
                                        <td><input type="radio" name="akan_bersedia_dipindah_satpen_lain" value="tidak" {{ old('akan_bersedia_dipindah_satpen_lain', $simfoni->akan_bersedia_dipindah_satpen_lain ?? '') == 'tidak' ? 'checked' : '' }} requaired></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="text-align: right; font-weight: bold; border: none;">Total Skor Proyeksi:</td>
                                        <td style="text-align: center; font-weight: bold; border: none;"><input type="text" id="totalSkorProyeksi" name="skor_proyeksi" value="{{ old('skor_proyeksi', $simfoni->skor_proyeksi ?? 0) }}" readonly style="width: 50px; text-align: center; font-weight: bold; border: 1px solid #dee2e6;"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>



                        <div class="form-group">
                            <label style="font-weight: bold; color: #dc3545;">PERNYATAAN</label>
                            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e9ecef; font-size: 11px; line-height: 1.4;">
                                Dengan ini saya menyatakan bahwa semua data yang saya tulis di atas adalah BENAR dan DAPAT DIPERTANGGUNGJAWABKAN. Apabila di kemudian hari ditemukan ketidaksesuaian, saya bersedia menerima konsekuensi dan sanksi yang berlaku.
                            </div>
                        </div>

                        <div class="form-group required">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" id="pernyataan_setuju" name="pernyataan_setuju" value="1" {{ old('pernyataan_setuju', $simfoni->pernyataan_setuju ?? '') ? 'checked' : '' }} required style="margin-right: 8px;">
                                <label for="pernyataan_setuju" style="margin: 0; font-size: 11px; color: #004b4c;">Saya menyetujui dan bertanggung jawab atas kebenaran data yang saya isi</label>
                            </div>
                            @error('pernyataan_setuju')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Navigation Buttons -->
            <div class="navigation-container">
                <div class="nav-buttons">
                    <button type="button" class="nav-btn nav-prev" id="prevBtn" style="display: none;">
                        <i class="bx bx-chevron-left"></i>
                    </button>

                    <div class="step-indicator">
                        Langkah <span id="currentStep">1</span> dari 7
                    </div>

                    <button type="button" class="nav-btn nav-next" id="nextBtn">
                        <i class="bx bx-chevron-right"></i>
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
    </div> <!-- /.form-container -->
</div> <!-- /.container -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ===== FORMAT RUPIAH (INDONESIA, TANPA DESIMAL) =====
        function formatRupiah(angka) {
            const number = parseInt(angka, 10);
            if (isNaN(number)) return '';
            return number.toLocaleString('id-ID');
        }

        function unformatRupiah(str) {
            return parseInt(str.replace(/\./g, ''), 10) || 0;
        }

        // Auto-fill readonly fields (if present)
        const mappings = {
            'nama_lengkap_gelar': '{{ $user->name ?? "" }}',
            'tempat_lahir': '{{ $user->tempat_lahir ?? "" }}',
            'tanggal_lahir': '{{ $user->tanggal_lahir ?? "" }}',
            'nuptk': '{{ $user->nuptk ?? "" }}',
            'kartanu': '{{ $user->kartanu ?? "" }}',
            'nipm': '{{ $user->nipm ?? "" }}',
            'tmt': '{{ $user->tmt ?? "" }}',
            'strata_pendidikan': '{{ $user->pendidikan_terakhir ?? "" }}',
            'program_studi': '{{ $user->program_studi ?? "" }}',
            'no_hp': '{{ $user->phone ?? "" }}',
            'email': '{{ $user->email ?? "" }}'
        };

        Object.keys(mappings).forEach(name => {
            const el = document.querySelector(`input[name="${name}"]`);
            if (el && !el.value) el.value = mappings[name];
        });

        // Multi-step form navigation
        let currentStep = 1;
        const totalSteps = 7;

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
            const currentStepElText = document.getElementById('currentStep');
            if (currentStepElText) currentStepElText.textContent = step;

            // Update navigation buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitContainer = document.getElementById('submitContainer');

            if (prevBtn && nextBtn && submitContainer) {
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
        }

        // Buttons
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        }

        // ===== AUTO FORMAT & HITUNG PENGHASILAN =====
        const form = document.getElementById('simfoniForm');
        if (form) {
            form.addEventListener('input', function (e) {
                const watched = ['gaji_sertifikasi', 'gaji_pokok', 'honor_lain', 'penghasilan_lain'];

                if (watched.includes(e.target.name)) {
                    // Format angka saat diketik
                    e.target.value = formatRupiah(unformatRupiah(e.target.value));

                    const getValue = (name) => {
                        const el = document.querySelector(`input[name="${name}"]`);
                        return el ? unformatRupiah(el.value) : 0;
                    };

                    const total =
                        getValue('gaji_sertifikasi') +
                        getValue('gaji_pokok') +
                        getValue('honor_lain') +
                        getValue('penghasilan_lain');

                    // Total penghasilan
                    const totalEl = document.getElementById('totalPenghasilan');
                    if (totalEl) {
                        totalEl.value = formatRupiah(total);
                    }

                    // Kategori penghasilan
                    let kategori = '';
                    if (total >= 10000000) {
                        kategori = 'A = Bagus (≥ 10 juta)';
                    } else if (total >= 6000000) {
                        kategori = 'B = Baik (6,0 – 9,9 juta)';
                    } else if (total >= 4000000) {
                        kategori = 'C = Cukup (4,0 – 5,9 juta)';
                    } else if (total >= 2500000) {
                        kategori = 'D = Hampir Cukup (2,5 – 3,9 juta)';
                    } else if (total >= 1500000) {
                        kategori = 'E = Kurang (1,5 – 2,4 juta)';
                    } else {
                        kategori = 'F = Sangat Kurang (< 1,5 juta)';
                    }

                    const kategoriEl = document.getElementById('kategoriPenghasilan');
                    if (kategoriEl) kategoriEl.value = kategori;
                }
            });
        }

        // Calculate masa kerja from TMT to June 2025
        let totalYears = 0; // Global variable to store total years

        function calculateMasaKerja() {
            const tmtInput = document.getElementById('tmtInput');
            const masaKerjaInput = document.getElementById('masaKerja');

            if (!tmtInput || !masaKerjaInput) return;

            const tmtDate = new Date(tmtInput.value);
            if (!tmtInput.value || isNaN(tmtDate.getTime())) {
                masaKerjaInput.value = '';
                totalYears = 0;
                filterStatusKerja();
                return;
            }

            // Target date: July 31, 2025
            const targetDate = new Date(2025, 6, 31); // Month is 0-indexed, so 6 = July

            // Calculate difference
            let years = targetDate.getFullYear() - tmtDate.getFullYear();
            let months = targetDate.getMonth() - tmtDate.getMonth();

            // Adjust if months is negative
            if (months < 0) {
                years--;
                months += 12;
            }

            // Adjust if target day is before TMT day in the same month
            if (targetDate.getDate() < tmtDate.getDate() && months === 0) {
                years--;
                months = 11;
            }

            totalYears = years + (months / 12); // Store as decimal for comparison
            masaKerjaInput.value = `${years} tahun ${months} bulan`;
            filterStatusKerja();
        }

        // Filter status kerja options based on masa kerja
        function filterStatusKerja() {
            const statusKerjaSelect = document.getElementById('statusKerjaSelect');
            if (!statusKerjaSelect) return;

            const options = statusKerjaSelect.querySelectorAll('option');
            options.forEach(option => {
                const value = option.value.toLowerCase();
                if (totalYears < 2 && totalYears > 0) {
                    // Show only GTT and PTT (assuming these are the values for id 6 and 8)
                    if (value.includes('gtt') || value.includes('ptt')) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                } else {
                    // Show all options
                    option.style.display = 'block';
                }
            });

            // Reset selection if current selection is hidden
            if (statusKerjaSelect.selectedOptions[0] && statusKerjaSelect.selectedOptions[0].style.display === 'none') {
                statusKerjaSelect.selectedIndex = 0;
            }

            // Disable select if masa kerja is empty
            if (totalYears === 0) {
                statusKerjaSelect.disabled = true;
                statusKerjaSelect.selectedIndex = 0;
            } else {
                statusKerjaSelect.disabled = false;
            }
        }

        // Add event listener to TMT input
        const tmtInput = document.getElementById('tmtInput');
        if (tmtInput) {
            tmtInput.addEventListener('change', calculateMasaKerja);
            // Calculate on page load if TMT has value
            if (tmtInput.value) {
                calculateMasaKerja();
            }
        }

        // Initialize UI
        showStep(currentStep);

        // Function to calculate skor for proyeksi
        function calculateSkorProyeksi() {
            let totalSkor = 0;
            for (let i = 1; i <= 19; i++) {
                const iyaRadio = document.querySelector(`input[name="akan_${getFieldName(i)}"][value="iya"]`);
                const sudahRadio = document.querySelector(`input[name="akan_${getFieldName(i)}"][value="sudah"]`);
                const skorField = document.getElementById(`skor_${i}`);

                let skor = 0;
                if (iyaRadio && iyaRadio.checked) {
                    skor = 1;
                } else if (sudahRadio && sudahRadio.checked) {
                    skor = 2;
                }

                if (skorField) {
                    skorField.value = skor;
                }
                totalSkor += skor;
            }

            const totalField = document.getElementById('totalSkorProyeksi');
            if (totalField) {
                totalField.value = totalSkor;
            }
        }

        // Helper function to get field name based on index
        function getFieldName(index) {
            const fieldNames = [
                'kuliah_s2',
                'mendaftar_pns',
                'mendaftar_pppk',
                'mengikuti_ppg',
                'menulis_buku_modul_riset',
                'mengikuti_seleksi_diklat_cakep',
                'membimbing_riset_prestasi_siswa',
                'masuk_tim_unggulan_sekolah_madrasah',
                'kompetisi_pimpinan_level_ii',
                'aktif_mengikuti_pelatihan',
                'aktif_mgmp_mkk',
                'mengikuti_pendidikan_kader_nu',
                'aktif_membantu_kegiatan_lembaga',
                'aktif_mengikuti_kegiatan_nu',
                'aktif_ikut_zis_kegiatan_sosial',
                'mengembangkan_unit_usaha_satpen',
                'bekerja_disiplin_produktif',
                'loyal_nu_aktif_masyarakat',
                'bersedia_dipindah_satpen_lain'
            ];
            return fieldNames[index - 1];
        }

        // Add event listeners to radio buttons in step 7
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio' && e.target.name.startsWith('akan_')) {
                calculateSkorProyeksi();
            }
        });

        // Calculate on page load if values are set
        calculateSkorProyeksi();

        // Title case for Tempat Lahir, Nama Lengkap dengan Gelar, PT Asal, and Nama Program Studi
        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        const tempatLahirInput = document.querySelector('input[name="tempat_lahir"]');
        if (tempatLahirInput) {
            tempatLahirInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        const namaLengkapInput = document.querySelector('input[name="nama_lengkap_gelar"]');
        if (namaLengkapInput) {
            namaLengkapInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        const ptAsalInput = document.querySelector('input[name="pt_asal"]');
        if (ptAsalInput) {
            ptAsalInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        const programStudiInput = document.querySelector('input[name="program_studi"]');
        if (programStudiInput) {
            programStudiInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        // Format Tahun Sertifikasi & Impassing as years
        function formatTahunSertifikasi(value) {
            // Remove all non-digit and non-& characters
            let cleaned = value.replace(/[^0-9&]/g, '');
            // Split by & and take up to 2 parts
            let parts = cleaned.split('&').slice(0, 2);
            // Format each part as 4-digit year if possible
            let formatted = parts.map(part => {
                let year = part.replace(/\D/g, '').slice(0, 4);
                return year;
            }).filter(year => year.length > 0);
            // Join with ' & '
            return formatted.join(' & ');
        }

        const tahunSertifikasiInput = document.getElementById('tahunSertifikasiInput');
        if (tahunSertifikasiInput) {
            tahunSertifikasiInput.addEventListener('input', function() {
                this.value = formatTahunSertifikasi(this.value);
            });
        }
    });
</script>
@endsection
