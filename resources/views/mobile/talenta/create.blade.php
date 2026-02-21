@extends('layouts.mobile')

@section('title', 'Buat Menu Talenta')

@section('content')
<link rel="stylesheet" href="{{ asset('css/mobile/talenta.css') }}">

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
    <button onclick="window.location.href='{{ route('mobile.talenta.index') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>MENU TALENTA</h4>
    <p>Update Data Peserta</p>
</div>

<!-- Form Container -->
<div class="form-container">
    <!-- Success Alert will be shown via SweetAlert -->
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" style="display: none;"></div>
    @endif

    <!-- Auto-save Indicator -->
    <div id="auto-save-indicator" class="auto-save-indicator" style="display: none;">
        <i class="bx bx-save"></i> Draft tersimpan
    </div>

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
            <div class="progress-fill" id="progress-fill" style="width: 25%;"></div>
        </div>
        <div class="progress-text">
            <span id="progress-percentage">25%</span>
            <span id="progress-step">Step 1 dari 4</span>
        </div>
    </div>

    <form action="{{ route('mobile.talenta.store') }}" method="POST" enctype="multipart/form-data" id="talenta-form">
        @csrf

        <!-- Hidden inputs -->
        <input type="hidden" name="tahun_pelaporan" value="{{ $data['tahun_pelaporan'] ?? date('Y') }}">
        <input type="hidden" name="status" value="draft" id="form-status">

        <!-- Step 1: B. MENU UPDATE TALENTA - TPT -->
        <div class="step-content active" data-step="1">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-user"></i>
                    </div>
                    <h6 class="section-title">UPDATE TALENTA - TPT</h6>
                </div>

                <div class="section-content">
                    <!-- Disabled Notice -->
                    <div class="info-note" style="background: #fff3cd; border-color: #ffeaa7; color: #856404; margin-bottom: 20px;">
                        <i class="bx bx-info-circle" style="margin-right: 8px;"></i>
                        <strong>Update data level TPT belum bisa dilakukan sesuai jadwal TPT</strong>
                        <br>
                        <small>Semua kolom pada step ini tidak dapat diisi untuk sementara waktu.</small>
                    </div>

                    <!-- TPT Level 1 - Collapsible -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(1)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 1</span>
                        <i class="bx bx-chevron-down" id="icon-1" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-1" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 1</label>
                            <input type="text" name="nomor_talenta_1" value="{{ old('nomor_talenta_1') }}" placeholder="Nomor Talenta 1" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('nomor_talenta_1')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_1" value="{{ old('skor_penilaian_1') }}" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('skor_penilaian_1')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 1 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_1" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('sertifikat_tpt_1')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 1 (PDF)</label>
                            <input type="file" name="produk_unggulan_1" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('produk_unggulan_1')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- TPT Level 2 - Collapsible -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(2)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 2</span>
                        <i class="bx bx-chevron-down" id="icon-2" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-2" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 2</label>
                            <input type="text" name="nomor_talenta_2" value="{{ old('nomor_talenta_2') }}" placeholder="Nomor Talenta 2" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('nomor_talenta_2')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_2" value="{{ old('skor_penilaian_2') }}" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('skor_penilaian_2')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 2 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_2" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('sertifikat_tpt_2')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 2 (PDF)</label>
                            <input type="file" name="produk_unggulan_2" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('produk_unggulan_2')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- TPT Level 3 - Collapsible -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(3)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 3</span>
                        <i class="bx bx-chevron-down" id="icon-3" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-3" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 3</label>
                            <input type="text" name="nomor_talenta_3" value="{{ old('nomor_talenta_3') }}" placeholder="Nomor Talenta 3" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('nomor_talenta_3')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_3" value="{{ old('skor_penilaian_3') }}" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('skor_penilaian_3')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 3 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_3" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('sertifikat_tpt_3')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 3 (PDF)</label>
                            <input type="file" name="produk_unggulan_3" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('produk_unggulan_3')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- TPT Level 4 - Collapsible -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(4)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 4 (Diklat Cakep)</span>
                        <i class="bx bx-chevron-down" id="icon-4" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-4" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 4</label>
                            <input type="text" name="nomor_talenta_4" value="{{ old('nomor_talenta_4') }}" placeholder="Nomor Talenta 4" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('nomor_talenta_4')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_4" value="{{ old('skor_penilaian_4') }}" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('skor_penilaian_4')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 4 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_4" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('sertifikat_tpt_4')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 4 (PDF)</label>
                            <input type="file" name="produk_unggulan_4" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('produk_unggulan_4')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- TPT Level 5 - Collapsible -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(5)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 5 (Training Lanjut Khusus-Case)</span>
                        <i class="bx bx-chevron-down" id="icon-5" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-5" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 5</label>
                            <input type="text" name="nomor_talenta_5" value="{{ old('nomor_talenta_5') }}" placeholder="Nomor Talenta 5" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('nomor_talenta_5')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_5" value="{{ old('skor_penilaian_5') }}" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('skor_penilaian_5')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 5 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_5" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('sertifikat_tpt_5')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 5 (PDF)</label>
                            <input type="file" name="produk_unggulan_5" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            @error('produk_unggulan_5')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
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

        <!-- Step 2: B.2 MENU PENDIDIKAN KADER -->
        <div class="step-content" data-step="2">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-graduation-cap"></i>
                    </div>
                    <h6 class="section-title">PENDIDIKAN KADER</h6>
                </div>

                <div class="section-content">
                    <!-- PKPNU/PDPKPNU -->
                    <div class="divider">
                        <span>PKPNU/PDPKPNU</span>
                    </div>
                    <div class="form-group">
                        <label>Pilihan</label>
                        <select name="pkpnu_status" required onchange="toggleUploadField('pkpnu')">
                            <option value="">Pilih</option>
                            <option value="sudah" {{ old('pkpnu_status') == 'sudah' ? 'selected' : '' }}>Sudah</option>
                            <option value="belum" {{ old('pkpnu_status') == 'belum' ? 'selected' : '' }}>Belum</option>
                        </select>
                        @error('pkpnu_status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="pkpnu-upload" style="display: {{ old('pkpnu_status') == 'sudah' ? 'block' : 'none' }};">
                        <label>Upload Sertifikat (PDF)</label>
                        <input type="file" name="pkpnu_sertifikat" accept=".pdf" {{ old('pkpnu_status') == 'sudah' ? 'required' : '' }}>
                        @error('pkpnu_sertifikat')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- MKNU -->
                    <div class="divider">
                        <span>MKNU</span>
                    </div>
                    <div class="form-group">
                        <label>Pilihan</label>
                        <select name="mknu_status" required onchange="toggleUploadField('mknu')">
                            <option value="">Pilih</option>
                            <option value="sudah" {{ old('mknu_status') == 'sudah' ? 'selected' : '' }}>Sudah</option>
                            <option value="belum" {{ old('mknu_status') == 'belum' ? 'selected' : '' }}>Belum</option>
                        </select>
                        @error('mknu_status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="mknu-upload" style="display: {{ old('mknu_status') == 'sudah' ? 'block' : 'none' }};">
                        <label>Upload Sertifikat (PDF)</label>
                        <input type="file" name="mknu_sertifikat" accept=".pdf" {{ old('mknu_status') == 'sudah' ? 'required' : '' }}>
                        @error('mknu_sertifikat')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PMKNU -->
                    <div class="divider">
                        <span>PMKNU</span>
                    </div>
                    <div class="form-group">
                        <label>Pilihan</label>
                        <select name="pmknu_status" required onchange="toggleUploadField('pmknu')">
                            <option value="">Pilih</option>
                            <option value="sudah" {{ old('pmknu_status') == 'sudah' ? 'selected' : '' }}>Sudah</option>
                            <option value="belum" {{ old('pmknu_status') == 'belum' ? 'selected' : '' }}>Belum</option>
                        </select>
                        @error('pmknu_status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="pmknu-upload" style="display: {{ old('pmknu_status') == 'sudah' ? 'block' : 'none' }};">
                        <label>Upload Sertifikat (PDF)</label>
                        <input type="file" name="pmknu_sertifikat" accept=".pdf">
                        @error('pmknu_sertifikat')
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

        <!-- Step 3: B.3 MENU PROYEKSI DIRI -->
        <div class="step-content" data-step="3">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-user"></i>
                    </div>
                    <h6 class="section-title">PROYEKSI DIRI</h6>
                </div>

                <div class="section-content">
                    <!-- Data Jabatan -->
                    <div class="divider">
                        <span>Data Jabatan</span>
                    </div>
                    <div class="form-group">
                        <label>Jabatan saat ini</label>
                        <select name="jabatan_saat_ini">
                            <option value="">Pilih Jabatan</option>
                            <option value="guru" {{ old('jabatan_saat_ini') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="kepala_sekolah" {{ old('jabatan_saat_ini') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            <option value="kaprodi" {{ old('jabatan_saat_ini') == 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                            <option value="kepala_laboratorium" {{ old('jabatan_saat_ini') == 'kepala_laboratorium' ? 'selected' : '' }}>Kepala Laboratorium</option>
                            <option value="kepala_perpustakaan" {{ old('jabatan_saat_ini') == 'kepala_perpustakaan' ? 'selected' : '' }}>Kepala Perpustakaan</option>
                            <option value="kepala_bengkel" {{ old('jabatan_saat_ini') == 'kepala_bengkel' ? 'selected' : '' }}>Kepala Bengkel</option>
                            <option value="bendahara_i" {{ old('jabatan_saat_ini') == 'bendahara_i' ? 'selected' : '' }}>Bendahara I</option>
                        </select>
                        @error('jabatan_saat_ini')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Proyeksi Akademik -->
                    <div class="divider">
                        <span>Proyeksi Akademik</span>
                    </div>
                    <div class="form-group">
                        <label>Pilih Proyeksi Akademik</label>
                        <select name="proyeksi_akademik">
                            <option value="">Pilih</option>
                            <option value="guru_terampil" {{ old('proyeksi_akademik') == 'guru_terampil' ? 'selected' : '' }}>Guru Terampil</option>
                            <option value="guru_mahir" {{ old('proyeksi_akademik') == 'guru_mahir' ? 'selected' : '' }}>Guru Mahir</option>
                            <option value="guru_ahli" {{ old('proyeksi_akademik') == 'guru_ahli' ? 'selected' : '' }}>Guru Ahli</option>
                        </select>
                        @error('proyeksi_akademik')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Proyeksi Jabatan -->
                    <div class="divider">
                        <span>Proyeksi Jabatan</span>
                    </div>
                    <div class="form-group">
                        <label>Level 2 Umum</label>
                        <select name="proyeksi_jabatan_level2_umum">
                            <option value="">Pilih</option>
                            <option value="wakil_kepala_sekolah" {{ old('proyeksi_jabatan_level2_umum') == 'wakil_kepala_sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                            <option value="kaprodi" {{ old('proyeksi_jabatan_level2_umum') == 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                            <option value="kepala_perpustakaan" {{ old('proyeksi_jabatan_level2_umum') == 'kepala_perpustakaan' ? 'selected' : '' }}>Kepala Perpustakaan</option>
                            <option value="bendahara_sekolah" {{ old('proyeksi_jabatan_level2_umum') == 'bendahara_sekolah' ? 'selected' : '' }}>Bendahara Sekolah</option>
                        </select>
                        @error('proyeksi_jabatan_level2_umum')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Level 2 Khusus</label>
                        <select name="proyeksi_jabatan_level2_khusus">
                            <option value="">Pilih</option>
                            <option value="kepala_unit_usaha" {{ old('proyeksi_jabatan_level2_khusus') == 'kepala_unit_usaha' ? 'selected' : '' }}>Kepala Unit Usaha</option>
                            <option value="leader_sister_school" {{ old('proyeksi_jabatan_level2_khusus') == 'leader_sister_school' ? 'selected' : '' }}>Leader Sister School</option>
                            <option value="leader_jejaring" {{ old('proyeksi_jabatan_level2_khusus') == 'leader_jejaring' ? 'selected' : '' }}>Leader Jejaring</option>
                            <option value="leader_unggulan_sekolah" {{ old('proyeksi_jabatan_level2_khusus') == 'leader_unggulan_sekolah' ? 'selected' : '' }}>Leader Unggulan Sekolah</option>
                            <option value="leader_prestasi_sekolah" {{ old('proyeksi_jabatan_level2_khusus') == 'leader_prestasi_sekolah' ? 'selected' : '' }}>Leader Prestasi Sekolah</option>
                            <option value="leader_panjaminan_mutu" {{ old('proyeksi_jabatan_level2_khusus') == 'leader_panjaminan_mutu' ? 'selected' : '' }}>Leader Panjaminan Mutu</option>
                        </select>
                        @error('proyeksi_jabatan_level2_khusus')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Level I</label>
                        <select name="proyeksi_jabatan_level1">
                            <option value="">Pilih</option>
                            <option value="kepala_sekolah" {{ old('proyeksi_jabatan_level1') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            <option value="kepala_madrasah" {{ old('proyeksi_jabatan_level1') == 'kepala_madrasah' ? 'selected' : '' }}>Kepala Madrasah</option>
                            <option value="tidak" {{ old('proyeksi_jabatan_level1') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('proyeksi_jabatan_level1')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Level Top Leader</label>
                        <select name="proyeksi_jabatan_top_leader">
                            <option value="">Pilih</option>
                            <option value="pengawas" {{ old('proyeksi_jabatan_top_leader') == 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                            <option value="pembina_utama" {{ old('proyeksi_jabatan_top_leader') == 'pembina_utama' ? 'selected' : '' }}>Pembina Utama</option>
                            <option value="tidak" {{ old('proyeksi_jabatan_top_leader') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('proyeksi_jabatan_top_leader')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Proyeksi Keahlian -->
                    <div class="divider">
                        <span>Proyeksi Keahlian</span>
                    </div>
                    <div class="form-group">
                        <label>Studi lanjut S2, S3</label>
                        <input type="text" name="studi_lanjut" value="{{ old('studi_lanjut') }}" placeholder="Jelaskan studi lanjut">
                        @error('studi_lanjut')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Leader dan Aktif MGMP</label>
                        <input type="text" name="leader_mgmp" value="{{ old('leader_mgmp') }}" placeholder="Jelaskan kepemimpinan MGMP">
                        @error('leader_mgmp')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Produk ajar : modul, buku ajar</label>
                        <input type="text" name="produk_ajar" value="{{ old('produk_ajar') }}" placeholder="Jelaskan produk ajar">
                        @error('produk_ajar')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Prestasi kompetitif</label>
                        <input type="text" name="prestasi_kompetitif" value="{{ old('prestasi_kompetitif') }}" placeholder="Jelaskan prestasi kompetitif">
                        @error('prestasi_kompetitif')
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

        <!-- Step 4: B.4 MENU DATA DIRI -->
        <div class="step-content" data-step="4">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-id-card"></i>
                    </div>
                    <h6 class="section-title">DATA DIRI</h6>
                </div>

                <div class="section-content">
                    <!-- B.4.1 Data Pribadi -->
                    <div class="divider">
                        <span>Data Pribadi</span>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap gelar</label>
                        <input type="text" name="nama_lengkap_gelar" value="{{ old('nama_lengkap_gelar') }}" placeholder="Nama Lengkap dengan Gelar">
                        @error('nama_lengkap_gelar')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" placeholder="Nama Panggilan">
                        @error('nama_panggilan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor KTP</label>
                        <input type="text" name="nomor_ktp" value="{{ old('nomor_ktp') }}" placeholder="Nomor KTP">
                        @error('nomor_ktp')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>NIP Ma'arif</label>
                        <input type="text" name="nip_maarif" value="{{ old('nip_maarif') }}" placeholder="NIP Ma'arif">
                        @error('nip_maarif')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor Talenta</label>
                        <input type="text" name="nomor_talenta" value="{{ old('nomor_talenta') }}" placeholder="Nomor Talenta">
                        @error('nomor_talenta')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tempat lahir (Kabupaten atau kota)</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Tempat Lahir">
                        @error('tempat_lahir')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Email Aktif</label>
                        <input type="email" name="email_aktif" value="{{ old('email_aktif') }}" placeholder="Email Aktif">
                        @error('email_aktif')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor WA aktif</label>
                        <input type="text" name="nomor_wa" value="{{ old('nomor_wa') }}" placeholder="Nomor WA">
                        @error('nomor_wa')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat KTP</label>
                        <textarea name="alamat_ktp" placeholder="Alamat KTP">{{ old('alamat_ktp') }}</textarea>
                        @error('alamat_ktp')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat yang ditinggali saat ini</label>
                        <textarea name="alamat_tinggal" placeholder="Alamat Tinggal">{{ old('alamat_tinggal') }}</textarea>
                        @error('alamat_tinggal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Link FB, link Tik Tok, Link Instagram</label>
                        <input type="text" name="link_fb" value="{{ old('link_fb') }}" placeholder="Facebook">
                        <input type="text" name="link_tiktok" value="{{ old('link_tiktok') }}" placeholder="TikTok" style="margin-top: 8px;">
                        <input type="text" name="link_instagram" value="{{ old('link_instagram') }}" placeholder="Instagram" style="margin-top: 8px;">
                        @error('link_fb')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('link_tiktok')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('link_instagram')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Upload Foto: 1 Foto Resmi, 1 Foto Bebas, 1 Foto Keluarga</label>
                        <input type="file" name="foto_resmi" accept="image/*">
                        <input type="file" name="foto_bebas" accept="image/*" style="margin-top: 8px;">
                        <input type="file" name="foto_keluarga" accept="image/*" style="margin-top: 8px;">
                        @error('foto_resmi')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('foto_bebas')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('foto_keluarga')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- B.4.2 Data Pendidikan -->
                    <div class="divider">
                        <span>Data Pendidikan</span>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah SD</label>
                        <input type="text" name="asal_sekolah_sd" value="{{ old('asal_sekolah_sd') }}" placeholder="Nama Sekolah SD">
                        @error('asal_sekolah_sd')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah SMP</label>
                        <input type="text" name="asal_sekolah_smp" value="{{ old('asal_sekolah_smp') }}" placeholder="Nama Sekolah SMP">
                        @error('asal_sekolah_smp')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah SMA</label>
                        <input type="text" name="asal_sekolah_sma" value="{{ old('asal_sekolah_sma') }}" placeholder="Nama Sekolah SMA">
                        @error('asal_sekolah_sma')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah S1</label>
                        <input type="text" name="asal_sekolah_s1" value="{{ old('asal_sekolah_s1') }}" placeholder="Nama Perguruan Tinggi S1">
                        @error('asal_sekolah_s1')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah S2</label>
                        <input type="text" name="asal_sekolah_s2" value="{{ old('asal_sekolah_s2') }}" placeholder="Nama Perguruan Tinggi S2">
                        @error('asal_sekolah_s2')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah S3</label>
                        <input type="text" name="asal_sekolah_s3" value="{{ old('asal_sekolah_s3') }}" placeholder="Nama Perguruan Tinggi S3">
                        @error('asal_sekolah_s3')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Upload Ijazah: S1, S2, S3 (PDF/JPG)</label>
                        <input type="file" name="ijazah_s1" accept=".pdf,.jpg,.jpeg">
                        <input type="file" name="ijazah_s2" accept=".pdf,.jpg,.jpeg" style="margin-top: 8px;">
                        <input type="file" name="ijazah_s3" accept=".pdf,.jpg,.jpeg" style="margin-top: 8px;">
                        @error('ijazah_s1')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('ijazah_s2')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('ijazah_s3')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- B.4.3 Data Pendapatan -->
                    <div class="divider">
                        <span>Data Pendapatan</span>
                    </div>
                    <div class="form-group">
                        <label>Kategori Level Pendapatan Internal</label>
                        <select name="level_pendapatan_internal">
                            <option value="">Pilih Level</option>
                            <option value="1" {{ old('level_pendapatan_internal') == '1' ? 'selected' : '' }}>Level 1 : Rp200.000 – Rp500.000</option>
                            <option value="2" {{ old('level_pendapatan_internal') == '2' ? 'selected' : '' }}>Level 2 : Rp600.000 – Rp1.000.000</option>
                            <option value="3" {{ old('level_pendapatan_internal') == '3' ? 'selected' : '' }}>Level 3 : Rp1.100.000 – Rp1.500.000</option>
                            <option value="4" {{ old('level_pendapatan_internal') == '4' ? 'selected' : '' }}>Level 4 : Rp1.600.000 – Rp2.000.000</option>
                            <option value="5" {{ old('level_pendapatan_internal') == '5' ? 'selected' : '' }}>Level 5 : Rp2.100.000 – Rp3.000.000</option>
                            <option value="6" {{ old('level_pendapatan_internal') == '6' ? 'selected' : '' }}>Level 6 : Rp3.100.000 – Rp4.000.000</option>
                            <option value="7" {{ old('level_pendapatan_internal') == '7' ? 'selected' : '' }}>Level 7 : Rp4.100.000 – Rp5.500.000</option>
                            <option value="8" {{ old('level_pendapatan_internal') == '8' ? 'selected' : '' }}>Level 8 : Rp5.600.000 – Rp7.500.000</option>
                            <option value="9" {{ old('level_pendapatan_internal') == '9' ? 'selected' : '' }}>Level 9 : Rp7.600.000 – Rp10.000.000</option>
                            <option value="10" {{ old('level_pendapatan_internal') == '10' ? 'selected' : '' }}>Level 10 : Di atas Rp10.000.000</option>
                        </select>
                        @error('level_pendapatan_internal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Level pendapatan eksternal, rerata</label>
                        <input type="text" name="pekerjaan_eksternal" value="{{ old('pekerjaan_eksternal') }}" placeholder="Sebagai .....">
                        <input type="number" name="pendapatan_eksternal" value="{{ old('pendapatan_eksternal') }}" placeholder="Rupiah ....." style="margin-top: 8px;">
                        @error('pekerjaan_eksternal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('pendapatan_eksternal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- B.4.4 Data Riwayat Kerja -->
                    <div class="divider">
                        <span>Data Riwayat Kerja</span>
                    </div>
                    <div class="form-group required">
                        <label>GTT-PTT</label>
                        <input type="date" name="gtt_ptt_tanggal" value="{{ old('gtt_ptt_tanggal') }}" placeholder="Tanggal-bulan-Tahun" required>
                        <input type="file" name="gtt_ptt_sk" accept=".pdf" required style="margin-top: 8px;">
                        @error('gtt_ptt_tanggal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('gtt_ptt_sk')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label>GTY</label>
                        <input type="date" name="gty_tanggal" value="{{ old('gty_tanggal') }}" placeholder="Tanggal-bulan-tahun" required>
                        <input type="file" name="gty_sk" accept=".pdf" required style="margin-top: 8px;">
                        @error('gty_tanggal')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('gty_sk')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label>Masa Kerja LPMNU DIY (Tahun)</label>
                        <input type="number" name="masa_kerja_lpmnu" value="{{ old('masa_kerja_lpmnu') }}" placeholder="... tahun" required>
                        @error('masa_kerja_lpmnu')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label>Riwayat Jabatan Guru</label>
                        <input type="text" name="riwayat_jabatan_pemula" value="{{ old('riwayat_jabatan_pemula') }}" placeholder="(1) Pemula diajukan tanggal :" required>
                        <input type="text" name="riwayat_jabatan_terampil" value="{{ old('riwayat_jabatan_terampil') }}" placeholder="(2) Terampil diajukan tanggal :" required style="margin-top: 8px;">
                        <input type="text" name="riwayat_jabatan_mahir" value="{{ old('riwayat_jabatan_mahir') }}" placeholder="(3) Mahir diajukan tanggal :" required style="margin-top: 8px;">
                        <input type="text" name="riwayat_jabatan_ahli" value="{{ old('riwayat_jabatan_ahli') }}" placeholder="(4) Ahli diajukan tanggal :" required style="margin-top: 8px;">
                        @error('riwayat_jabatan_pemula')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('riwayat_jabatan_terampil')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('riwayat_jabatan_mahir')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        @error('riwayat_jabatan_ahli')
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
                <button type="button" class="step-btn draft-btn" onclick="submitDraft()">
                    <i class="bx bx-edit"></i>
                    Simpan Data
                </button>
                {{-- <button type="button" class="step-btn publish-btn" onclick="submitPublish()">
                    <i class="bx bx-save"></i>
                    Simpan
                </button> --}}
            </div>
        </div>

    </form>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    @endif
</script>
<script src="{{ asset('js/mobile/talenta.js') }}"></script>
