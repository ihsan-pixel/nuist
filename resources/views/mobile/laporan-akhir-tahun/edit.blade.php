@extends('layouts.mobile')

@section('title', 'Edit Laporan Akhir Tahun')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mobile/laporan-akhir-tahun-create.css') }}">

<style>
    body {
    background: #f8f9fb url('/images/bg.png') no-repeat center center;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
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
    <h4>EDIT LAPORAN AKHIR TAHUN</h4>
    <p>Kepala Sekolah/Madrasah</p>
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

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill" style="width: 10%;"></div>
        </div>
        <div class="progress-text">
            <span id="progress-percentage">10%</span>
            <span id="progress-step">Step 1 dari 10</span>
        </div>
    </div>

    <form action="{{ route('mobile.laporan-akhir-tahun.update', $laporan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Hidden inputs for pre-filled required fields -->
        <input type="hidden" name="tahun_pelaporan" value="{{ $laporan->tahun_pelaporan }}">
        <input type="hidden" name="nama_kepala_sekolah" value="{{ $laporan->nama_kepala_sekolah }}">
        <input type="hidden" name="nama_madrasah" value="{{ $laporan->nama_madrasah }}">
        <input type="hidden" name="alamat_madrasah" value="{{ $laporan->alamat_madrasah }}">

        <!-- Step 1: A. IDENTITAS SATPEN -->
        <div class="step-content active" data-step="1">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-file"></i>
                    </div>
                    <h6 class="section-title">A. IDENTITAS SATPEN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Nama Satpen</label>
                        <input type="text" name="nama_satpen" value="{{ old('nama_satpen', $laporan->nama_satpen) }}" placeholder="Nama Satpen" required>
                        @error('nama_satpen')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Alamat</label>
                        <textarea name="alamat" placeholder="Alamat lengkap" required>{{ old('alamat', $laporan->alamat) }}</textarea>
                        @error('alamat')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Nama Kepala Sekolah/Madrasah</label>
                        <input type="text" name="nama_kepala_sekolah_madrasah" value="{{ old('nama_kepala_sekolah_madrasah', $laporan->nama_kepala_sekolah_madrasah) }}" placeholder="Nama Lengkap" required>
                        @error('nama_kepala_sekolah_madrasah')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="info-note" style="font-style: italic">
                        <strong>*</strong>Nama Lengkap Tanpa Gelar
                    </div>

                    <div class="form-group required">
                        <label>Gelar</label>
                        <input type="text" name="gelar" value="{{ old('gelar', $laporan->gelar) }}" placeholder="S.Pd., M.Pd., dll" required>
                        @error('gelar')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TMT KS/Kamad Pertama</label>
                        <input type="date" name="tmt_ks_kamad_pertama" value="{{ old('tmt_ks_kamad_pertama', $laporan->tmt_ks_kamad_pertama ? $laporan->tmt_ks_kamad_pertama->format('Y-m-d') : '') }}" required>
                        @error('tmt_ks_kamad_pertama')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TMT KS/Kamad Terakhir</label>
                        <input type="date" name="tmt_ks_kamad_terakhir" value="{{ old('tmt_ks_kamad_terakhir', $laporan->tmt_ks_kamad_terakhir ? $laporan->tmt_ks_kamad_terakhir->format('Y-m-d') : '') }}" required>
                        @error('tmt_ks_kamad_terakhir')
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

        <!-- Step 2: CAPAIAN UTAMA 3 TAHUN BERJALAN -->
        <div class="step-content" data-step="2">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-target"></i>
                    </div>
                    <h6 class="section-title">B. CAPAIAN UTAMA 3 TAHUN BERJALAN</h6>
                </div>

                <div class="section-content">
                    <div class="divider">
                        <span>Data Siswa</span>
                    </div>

                    <div class="form-group required">
                        <label>Jumlah Siswa 2023</label>
                        <input type="number" name="jumlah_siswa_2023" value="{{ old('jumlah_siswa_2023', $laporan->jumlah_siswa_2023) }}" min="0" placeholder="0" required>
                        @error('jumlah_siswa_2023')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Jumlah Siswa 2024</label>
                        <input type="number" name="jumlah_siswa_2024" value="{{ old('jumlah_siswa_2024', $laporan->jumlah_siswa_2024) }}" min="0" placeholder="0" required>
                        @error('jumlah_siswa_2024')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Jumlah Siswa 2025</label>
                        <input type="number" name="jumlah_siswa_2025" value="{{ old('jumlah_siswa_2025', $laporan->jumlah_siswa_2025) }}" min="0" placeholder="0" required>
                        @error('jumlah_siswa_2025')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="divider">
                        <span>Kondisi Alumni</span>
                    </div>

                    <div class="form-group required">
                        <label>Persentase Alumni Bekerja/Melanjutkan</label>
                        <div class="input-with-symbol">
                            <input type="text" name="persentase_alumni_bekerja" value="{{ old('persentase_alumni_bekerja', $laporan->persentase_alumni_bekerja . '%') }}" placeholder="0.00" required>
                            <span class="input-symbol">%</span>
                        </div>
                        @error('persentase_alumni_bekerja')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Persentase Alumni Wirausaha-Agamaawan</label>
                        <div class="input-with-symbol">
                            <input type="text" name="persentase_alumni_wirausaha" value="{{ old('persentase_alumni_wirausaha', $laporan->persentase_alumni_wirausaha . '%') }}" placeholder="0.00" required>
                            <span class="input-symbol">%</span>
                        </div>
                        @error('persentase_alumni_wirausaha')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Persentase Alumni Tidak Terdeteksi</label>
                        <div class="input-with-symbol">
                            <input type="text" name="persentase_alumni_tidak_terdeteksi" value="{{ old('persentase_alumni_tidak_terdeteksi', $laporan->persentase_alumni_tidak_terdeteksi . '%') }}" placeholder="0.00" required>
                            <span class="input-symbol">%</span>
                        </div>
                        @error('persentase_alumni_tidak_terdeteksi')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="divider">
                        <span>Input Keuangan 3 Tahun Terakhir</span>
                    </div>

                    <h6 class="mb-2">a. BOSNAS</h6>

                    <div class="form-group required">
                        <label>TAHUN 2023</label>
                        <input type="text" name="bosnas_2023" value="{{ old('bosnas_2023', $laporan->bosnas_2023) }}" placeholder="Rp 0" required>
                        @error('bosnas_2023')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TAHUN 2024</label>
                        <input type="text" name="bosnas_2024" value="{{ old('bosnas_2024', $laporan->bosnas_2024) }}" placeholder="Rp 0" required>
                        @error('bosnas_2024')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TAHUN 2025</label>
                        <input type="text" name="bosnas_2025" value="{{ old('bosnas_2025', $laporan->bosnas_2025) }}" placeholder="Rp 0" required>
                        @error('bosnas_2025')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="mb-2">b. BOSDA</h6>

                    <div class="form-group required">
                        <label>TAHUN 2023</label>
                        <input type="text" name="bosda_2023" value="{{ old('bosda_2023', $laporan->bosda_2023) }}" placeholder="Rp 0" required>
                        @error('bosda_2023')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TAHUN 2024</label>
                        <input type="text" name="bosda_2024" value="{{ old('bosda_2024', $laporan->bosda_2024) }}" placeholder="Rp 0" required>
                        @error('bosda_2024')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TAHUN 2025</label>
                        <input type="text" name="bosda_2025" value="{{ old('bosda_2025', $laporan->bosda_2025) }}" placeholder="Rp 0" required>
                        @error('bosda_2025')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="mb-2">c. SPP, BPPP, Sumbangan Lain</h6>

                    <div class="form-group required">
                        <label>TAHUN 2023</label>
                        <input type="text" name="spp_bppp_lain_2023" value="{{ old('spp_bppp_lain_2023', $laporan->spp_bppp_lain_2023) }}" placeholder="Rp 0" required>
                        @error('spp_bppp_lain_2023')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TAHUN 2024</label>
                        <input type="text" name="spp_bppp_lain_2024" value="{{ old('spp_bppp_lain_2024', $laporan->spp_bppp_lain_2024) }}" placeholder="Rp 0" required>
                        @error('spp_bppp_lain_2024')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>TAHUN 2025</label>
                        <input type="text" name="spp_bppp_lain_2025" value="{{ old('spp_bppp_lain_2025', $laporan->spp_bppp_lain_2025) }}" placeholder="Rp 0" required>
                        @error('spp_bppp_lain_2025')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="mb-2">d. Pendapatan Unit Usaha</h6>

                    <div class="form-group required">
                        <label>Tahun 2023</label>
                        <input type="text" name="pendapatan_unit_usaha_2023" value="{{ old('pendapatan_unit_usaha_2023', $laporan->pendapatan_unit_usaha_2023) }}" placeholder="Rp 0" required>
                        @error('pendapatan_unit_usaha_2023')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Tahun 2024</label>
                        <input type="text" name="pendapatan_unit_usaha_2024" value="{{ old('pendapatan_unit_usaha_2024', $laporan->pendapatan_unit_usaha_2024) }}" placeholder="Rp 0" required>
                        @error('pendapatan_unit_usaha_2024')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Tahun 2025</label>
                        <input type="text" name="pendapatan_unit_usaha_2025" value="{{ old('pendapatan_unit_usaha_2025', $laporan->pendapatan_unit_usaha_2025) }}" placeholder="Rp 0" required>
                        @error('pendapatan_unit_usaha_2025')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="divider">
                        <span>Akreditasi</span>
                    </div>

                    <div class="form-group required">
                        <label>Status Akreditasi</label>
                        <select name="status_akreditasi" required>
                            <option value="">Pilih Status</option>
                            <option value="Belum" {{ old('status_akreditasi', $data['status_akreditasi']) == 'Belum' ? 'selected' : '' }}>Belum</option>
                            <option value="C" {{ old('status_akreditasi', $data['status_akreditasi']) == 'C' ? 'selected' : '' }}>C</option>
                            <option value="B" {{ old('status_akreditasi', $data['status_akreditasi']) == 'B' ? 'selected' : '' }}>B</option>
                            <option value="A" {{ old('status_akreditasi', $data['status_akreditasi']) == 'A' ? 'selected' : '' }}>A</option>
                        </select>
                        @error('status_akreditasi')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Tanggal Akreditasi Mulai Berlaku</label>
                        <input type="date" name="tanggal_akreditasi_mulai" value="{{ old('tanggal_akreditasi_mulai', $data['tanggal_akreditasi_mulai']) }}" required>
                        @error('tanggal_akreditasi_mulai')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Tanggal Akreditasi Berakhir</label>
                        <input type="date" name="tanggal_akreditasi_berakhir" value="{{ old('tanggal_akreditasi_berakhir', $data['tanggal_akreditasi_berakhir']) }}" required>
                        @error('tanggal_akreditasi_berakhir')
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

        <!-- Step 3: C. LAYANAN PENDIDIKAN -->
        <div class="step-content" data-step="3">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-graduation-cap"></i>
                    </div>
                    <h6 class="section-title">C. LAYANAN PENDIDIKAN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Model Layanan Pendidikan yang Diterapkan</label>
                        <textarea name="model_layanan_pendidikan" placeholder="Jelaskan model layanan pendidikan yang diterapkan..." required>{{ old('model_layanan_pendidikan', $laporan->model_layanan_pendidikan) }}</textarea>
                        @error('model_layanan_pendidikan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Capaian Layanan yang Paling Menonjol</label>
                        <textarea name="capaian_layanan_menonjol" placeholder="Jelaskan capaian layanan yang paling menonjol..." required>{{ old('capaian_layanan_menonjol', $laporan->capaian_layanan_menonjol) }}</textarea>
                        @error('capaian_layanan_menonjol')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Masalah Layanan Utama Tahun Ini</label>
                        <textarea name="masalah_layanan_utama" placeholder="Jelaskan masalah layanan utama tahun ini..." required>{{ old('masalah_layanan_utama', $laporan->masalah_layanan_utama) }}</textarea>
                        @error('masalah_layanan_utama')
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

        <!-- Step 4: D. SUMBER DAYA MANUSIA (SDM) -->
        <div class="step-content" data-step="4">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-group"></i>
                    </div>
                    <h6 class="section-title">D. SUMBER DAYA MANUSIA (SDM)</h6>
                </div>

                <div class="section-content">
                    <div class="divider">
                        <span>Jumlah Guru dan Karyawan</span>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>PNS Sertifikasi</label>
                            <input type="number" name="pns_sertifikasi" value="{{ old('pns_sertifikasi', $laporan->pns_sertifikasi) }}" min="0" placeholder="0" required>
                            @error('pns_sertifikasi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>PNS Non Sertifikasi</label>
                            <input type="number" name="pns_non_sertifikasi" value="{{ old('pns_non_sertifikasi', $laporan->pns_non_sertifikasi) }}" min="0" placeholder="0" required>
                            @error('pns_non_sertifikasi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>GTY Sertifikasi Inpassing</label>
                            <input type="number" name="gty_sertifikasi_inpassing" value="{{ old('gty_sertifikasi_inpassing', $laporan->gty_sertifikasi_inpassing) }}" min="0" placeholder="0" required>
                            @error('gty_sertifikasi_inpassing')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>GTY Sertifikasi</label>
                            <input type="number" name="gty_sertifikasi" value="{{ old('gty_sertifikasi', $laporan->gty_sertifikasi) }}" min="0" placeholder="0" required>
                            @error('gty_sertifikasi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>GTY Non Sertifikasi</label>
                            <input type="number" name="gty_non_sertifikasi" value="{{ old('gty_non_sertifikasi', $laporan->gty_non_sertifikasi) }}" min="0" placeholder="0" required>
                            @error('gty_non_sertifikasi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>GTT</label>
                            <input type="number" name="gtt" value="{{ old('gtt', $laporan->gtt) }}" min="0" placeholder="0" required>
                            @error('gtt')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>PTY</label>
                            <input type="number" name="pty" value="{{ old('pty', $laporan->pty) }}" min="0" placeholder="0" required>
                            @error('pty')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>PTT</label>
                            <input type="number" name="ptt" value="{{ old('ptt', $laporan->ptt) }}" min="0" placeholder="0" required>
                            @error('ptt')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="info-note" style="font-style: italic">
                        <strong>*</strong>Data diatas diambil otomatis dari database Nuist (Bisa Disesuaikan)
                    </div>

                    <div class="form-group" style="margin-bottom: 12px; padding: 8px; background: #fff; border-radius: 6px; border: 2px solid #004b4c;">
                        <label style="font-weight: 600; color: #004b4c; margin-bottom: 4px; display: block;">Total Jumlah Guru dan Karyawan</label>
                        <input type="text" id="total_guru_karyawan" value="0" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-weight: bold; font-size: 14px; color: #004b4c;">
                        <div class="form-hint">Total otomatis dari semua kategori di atas</div>
                    </div>

                    <div class="divider">
                        <span>Jumlah Talenta yang Diproyeksikan</span>
                    </div>

                    <div class="form-group required">
                        <label>Jumlah (Minimal 3 Maksimal 9)</label>
                        <input type="number" name="jumlah_talenta" id="jumlah_talenta" value="{{ old('jumlah_talenta', $laporan->jumlah_talenta) }}" min="3" max="9" placeholder="3" required>
                        @error('jumlah_talenta')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Nama dan Alasan</label>
                        <div class="dynamic-inputs" data-category="talenta">
                            <!-- Talenta fields will be dynamically generated by JavaScript -->
                        </div>
                        @error('nama_talenta')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('alasan_talenta')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <script>
                        window.guruKaryawanData = @json($guruKaryawan ?? []);
                        window.existingNamaTalenta = @json($data['nama_talenta'] ?? []);
                        window.existingAlasanTalenta = @json($data['alasan_talenta'] ?? []);
                    </script>

                    <div class="divider">
                        <span>Kondisi SDM Secara Umum</span>
                    </div>

                    <div class="form-group required">
                        <label>Nama Guru dan Karyawan dengan Kondisi secara umum</label>
                        @if(isset($guruKaryawan) && is_array($guruKaryawan))
                            @foreach($guruKaryawan as $guru)
                                <div class="form-group">
                                    <label>{{ $guru['name'] }}</label>
                                    <select name="kondisi_guru[{{ $guru['id'] }}]" required>
                                        <option value="">Pilih Kondisi</option>
                                        <option value="baik" {{ old('kondisi_guru.' . $guru['id'], json_decode($laporan->kondisi_guru, true)[$guru['id']] ?? '') == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ old('kondisi_guru.' . $guru['id'], json_decode($laporan->kondisi_guru, true)[$guru['id']] ?? '') == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="bermasalah" {{ old('kondisi_guru.' . $guru['id'], json_decode($laporan->kondisi_guru, true)[$guru['id']] ?? '') == 'bermasalah' ? 'selected' : '' }}>Bermasalah</option>
                                    </select>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="divider">
                        <span>Masalah SDM Utama</span>
                    </div>

                    <div class="form-group required">
                        <label>Masalah SDM Utama 1</label>
                        <textarea name="masalah_sdm_utama[]" placeholder="Masalah 1..." required>{{ old('masalah_sdm_utama.0', json_decode($laporan->masalah_sdm_utama, true)[0] ?? '') }}</textarea>
                        @error('masalah_sdm_utama.0')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Masalah SDM Utama 2</label>
                        <textarea name="masalah_sdm_utama[]" placeholder="Masalah 2..." required>{{ old('masalah_sdm_utama.1', json_decode($laporan->masalah_sdm_utama, true)[1] ?? '') }}</textarea>
                        @error('masalah_sdm_utama.1')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Masalah SDM Utama 3</label>
                        <textarea name="masalah_sdm_utama[]" placeholder="Masalah 3..." required>{{ old('masalah_sdm_utama.2', json_decode($laporan->masalah_sdm_utama, true)[2] ?? '') }}</textarea>
                        @error('masalah_sdm_utama.2')
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

        <!-- Step 5: E. KEUANGAN -->
        <div class="step-content" data-step="5">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-money"></i>
                    </div>
                    <h6 class="section-title">E. KEUANGAN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Sumber Dana Utama</label>
                        <textarea name="sumber_dana_utama" placeholder="Jelaskan sumber dana utama..." required>{{ old('sumber_dana_utama', $laporan->sumber_dana_utama) }}</textarea>
                        @error('sumber_dana_utama')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Kondisi Keuangan Akhir Tahun</label>
                        <select name="kondisi_keuangan_akhir_tahun" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="sehat" {{ old('kondisi_keuangan_akhir_tahun', $laporan->kondisi_keuangan_akhir_tahun) == 'sehat' ? 'selected' : '' }}>Sehat</option>
                            <option value="cukup" {{ old('kondisi_keuangan_akhir_tahun', $laporan->kondisi_keuangan_akhir_tahun) == 'cukup' ? 'selected' : '' }}>Cukup</option>
                            <option value="risiko" {{ old('kondisi_keuangan_akhir_tahun', $laporan->kondisi_keuangan_akhir_tahun) == 'risiko' ? 'selected' : '' }}>Risiko</option>
                            <option value="kritis" {{ old('kondisi_keuangan_akhir_tahun', $laporan->kondisi_keuangan_akhir_tahun) == 'kritis' ? 'selected' : '' }}>Kritis</option>
                        </select>
                        @error('kondisi_keuangan_akhir_tahun')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Catatan Penting Terkait Pengelolaan Keuangan</label>
                        <textarea name="catatan_pengelolaan_keuangan" placeholder="Jelaskan catatan penting terkait pengelolaan keuangan..." required>{{ old('catatan_pengelolaan_keuangan', $laporan->catatan_pengelolaan_keuangan) }}</textarea>
                        @error('catatan_pengelolaan_keuangan')
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

        <!-- Step 6: F. PPDB -->
        <div class="step-content" data-step="6">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-user-plus"></i>
                    </div>
                    <h6 class="section-title">F. PPDB</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Metode PPDB yang Digunakan</label>
                        <textarea name="metode_ppdb" placeholder="Jelaskan metode PPDB yang digunakan..." required>{{ old('metode_ppdb', $laporan->metode_ppdb) }}</textarea>
                        @error('metode_ppdb')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Hasil PPDB Tahun Berjalan</label>
                        <textarea name="hasil_ppdb_tahun_berjalan" placeholder="Jelaskan hasil PPDB tahun berjalan..." required>{{ old('hasil_ppdb_tahun_berjalan', $laporan->hasil_ppdb_tahun_berjalan) }}</textarea>
                        @error('hasil_ppdb_tahun_berjalan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Masalah Utama PPDB</label>
                        <textarea name="masalah_utama_ppdb" placeholder="Jelaskan masalah utama PPDB..." required>{{ old('masalah_utama_ppdb', $laporan->masalah_utama_ppdb) }}</textarea>
                        @error('masalah_utama_ppdb')
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

        <!-- Step 7: G. UNGGULAN SEKOLAH/MADRASAH -->
        <div class="step-content" data-step="7">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-star"></i>
                    </div>
                    <h6 class="section-title">G. UNGGULAN SEKOLAH/MADRASAH</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Nama Program Unggulan</label>
                        <textarea name="nama_program_unggulan" placeholder="Jelaskan nama program unggulan..." required>{{ old('nama_program_unggulan', $laporan->nama_program_unggulan) }}</textarea>
                        @error('nama_program_unggulan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Alasan Pemilihan Program</label>
                        <textarea name="alasan_pemilihan_program" placeholder="Jelaskan alasan pemilihan program..." required>{{ old('alasan_pemilihan_program', $laporan->alasan_pemilihan_program) }}</textarea>
                        @error('alasan_pemilihan_program')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Target Unggulan</label>
                        <textarea name="target_unggulan" placeholder="Jelaskan target unggulan..." required>{{ old('target_unggulan', $laporan->target_unggulan) }}</textarea>
                        @error('target_unggulan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Kontribusi Unggulan</label>
                        <textarea name="kontribusi_unggulan" placeholder="Jelaskan kontribusi unggulan..." required>{{ old('kontribusi_unggulan', $laporan->kontribusi_unggulan) }}</textarea>
                        @error('kontribusi_unggulan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Sumber Biaya Program</label>
                        <textarea name="sumber_biaya_program" placeholder="Jelaskan sumber biaya program..." required>{{ old('sumber_biaya_program', $laporan->sumber_biaya_program) }}</textarea>
                        @error('sumber_biaya_program')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Tim Program Unggulan</label>
                        <textarea name="tim_program_unggulan" placeholder="Jelaskan tim program unggulan..." required>{{ old('tim_program_unggulan', $laporan->tim_program_unggulan) }}</textarea>
                        @error('tim_program_unggulan')
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

        <!-- Step 8: H. REFLEKSI KEPALA SEKOLAH/MADRASAH -->
        <div class="step-content" data-step="8">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-brain"></i>
                    </div>
                    <h6 class="section-title">H. REFLEKSI KEPALA SEKOLAH/MADRASAH</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Keberhasilan Terbesar Tahun Ini</label>
                        <textarea name="keberhasilan_terbesar_tahun_ini" placeholder="Jelaskan keberhasilan terbesar tahun ini..." required>{{ old('keberhasilan_terbesar_tahun_ini', $laporan->keberhasilan_terbesar_tahun_ini) }}</textarea>
                        @error('keberhasilan_terbesar_tahun_ini')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Masalah Paling Berat yang Dihadapi</label>
                        <textarea name="masalah_paling_berat_dihadapi" placeholder="Jelaskan masalah paling berat yang dihadapi..." required>{{ old('masalah_paling_berat_dihadapi', $laporan->masalah_paling_berat_dihadapi) }}</textarea>
                        @error('masalah_paling_berat_dihadapi')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Keputusan yang Sulit Diambil</label>
                        <textarea name="keputusan_sulit_diambil" placeholder="Jelaskan keputusan yang sulit diambil..." required>{{ old('keputusan_sulit_diambil', $laporan->keputusan_sulit_diambil) }}</textarea>
                        @error('keputusan_sulit_diambil')
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

        <!-- Step 9: I. RISIKO DAN TINDAK LANJUT -->
        <div class="step-content" data-step="9">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-shield"></i>
                    </div>
                    <h6 class="section-title">I. RISIKO DAN TINDAK LANJUT</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Risiko Terbesar Satpen Tahun Depan</label>
                        <textarea name="risiko_terbesar_satpen_tahun_depan" placeholder="Jelaskan risiko terbesar satpen tahun depan..." required>{{ old('risiko_terbesar_satpen_tahun_depan', $laporan->risiko_terbesar_satpen_tahun_depan) }}</textarea>
                        @error('risiko_terbesar_satpen_tahun_depan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group required">
                        <label>Fokus Perbaikan Tahun Depan 1</label>
                        <textarea name="fokus_perbaikan_tahun_depan[]" placeholder="Fokus perbaikan 1..." required>{{ old('fokus_perbaikan_tahun_depan.0', json_decode($laporan->fokus_perbaikan_tahun_depan, true)[0] ?? '') }}</textarea>
                        @error('fokus_perbaikan_tahun_depan.0')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Fokus Perbaikan Tahun Depan 2</label>
                        <textarea name="fokus_perbaikan_tahun_depan[]" placeholder="Fokus perbaikan 2...">{{ old('fokus_perbaikan_tahun_depan.1', json_decode($laporan->fokus_perbaikan_tahun_depan, true)[1] ?? '') }}</textarea>
                        @error('fokus_perbaikan_tahun_depan.1')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Fokus Perbaikan Tahun Depan 3</label>
                        <textarea name="fokus_perbaikan_tahun_depan[]" placeholder="Fokus perbaikan 3...">{{ old('fokus_perbaikan_tahun_depan.2', json_decode($laporan->fokus_perbaikan_tahun_depan, true)[2] ?? '') }}</textarea>
                        @error('fokus_perbaikan_tahun_depan.2')
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

        <!-- Step 10: J. PERNYATAAN -->
        <div class="step-content" data-step="10">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <h6 class="section-title">J. PERNYATAAN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <div class="checkbox-container">
                            <input type="checkbox" name="pernyataan_benar" value="1" id="pernyataan_benar" required {{ old('pernyataan_benar', $laporan->pernyataan_benar) ? 'checked' : '' }}>
                            <label for="pernyataan_benar" class="checkbox-label">Saya menyetujui dan menyatakan bahwa semua data yang saya isi adalah benar dan sesuai dengan kondisi satpen</label>
                        </div>
                        @error('pernyataan_benar')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="signature-section">
                        <div class="location-date">
                            <p>Yogyakarta, {{ date('d F Y') }}</p>
                        </div>

                        <div class="signature-area">
                            <p>Tanda Tangan</p>
                            <div style="margin-bottom: 10px;">
                                <p style="font-size: 11px; color: #666; font-style: italic;">Masukkan tanda tangan baru jika ingin memperbarui tanda tangan</p>
                            </div>
                            <canvas id="signature-canvas" width="500" height="300" style="border: 1px solid #ccc; border-radius: 4px; background: #fff;"></canvas>
                            <div style="margin-top: 8px;">
                                <button type="button" id="clear-signature" class="btn btn-sm btn-outline-secondary" style="font-size: 11px;">Hapus Tanda Tangan</button>
                            </div>
                            <input type="hidden" name="signature_data" id="signature-data" value="{{ $laporan->signature_data }}">
                            @if($laporan->signature_data)
                                <div style="margin-bottom: 10px;">
                                    <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Tanda Tangan Tersimpan:</p>
                                    <img src="{{ $laporan->signature_data }}" alt="Tanda Tangan Tersimpan" style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                </div>
                            @endif
                        </div>

                        <div class="name-section">
                            <p>{{ $laporan->nama_kepala_sekolah }}</p>
                        </div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/mobile/laporan-akhir-tahun-edit.js') }}"></script>
