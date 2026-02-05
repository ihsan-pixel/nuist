@extends('layouts.mobile')

@section('title', 'Detail Menu Talenta')

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
    <h4>DETAIL TALENTA</h4>
    <p>Data Peserta Talenta</p>
</div>

<!-- Success Alert -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Error Alert -->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="form-container">
    <!-- Status Badge -->
    <div class="mb-3">
        <span class="badge {{ $talenta->status === 'published' ? 'bg-success' : 'bg-warning' }} fs-6">
            {{ $talenta->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
        </span>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-2 mb-4">
        @if($talenta->status !== 'published')
            <a href="{{ route('mobile.talenta.edit', $talenta->id) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit"></i> Edit
            </a>
        @endif
    </div>

    <!-- Data Display -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-user"></i>
            </div>
            <h6 class="section-title">DATA DIRI</h6>
        </div>

        <div class="section-content">
            <div class="row">
                <div class="col-12">
                    <div class="data-item">
                        <label>Nama Lengkap:</label>
                        <span>{{ $talenta->nama_lengkap_gelar }}</span>
                    </div>
                    <div class="data-item">
                        <label>Nama Panggilan:</label>
                        <span>{{ $talenta->nama_panggilan }}</span>
                    </div>
                    <div class="data-item">
                        <label>Nomor KTP:</label>
                        <span>{{ $talenta->nomor_ktp }}</span>
                    </div>
                    <div class="data-item">
                        <label>NIP Ma'arif:</label>
                        <span>{{ $talenta->nip_maarif }}</span>
                    </div>
                    <div class="data-item">
                        <label>Nomor Talenta:</label>
                        <span>{{ $talenta->nomor_talenta }}</span>
                    </div>
                    <div class="data-item">
                        <label>Tempat Lahir:</label>
                        <span>{{ $talenta->tempat_lahir }}</span>
                    </div>
                    <div class="data-item">
                        <label>Tanggal Lahir:</label>
                        <span>{{ $talenta->tanggal_lahir ? $talenta->tanggal_lahir->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="data-item">
                        <label>Email:</label>
                        <span>{{ $talenta->email_aktif }}</span>
                    </div>
                    <div class="data-item">
                        <label>Nomor WA:</label>
                        <span>{{ $talenta->nomor_wa }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TPT Section -->
    @if($talenta->nomor_talenta_1 || $talenta->nomor_talenta_2 || $talenta->nomor_talenta_3 || $talenta->nomor_talenta_4 || $talenta->nomor_talenta_5)
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-trophy"></i>
            </div>
            <h6 class="section-title">DATA TPT</h6>
        </div>

        <div class="section-content">
            @if($talenta->nomor_talenta_1)
            <div class="data-item">
                <label>TPT Level 1:</label>
                <span>{{ $talenta->nomor_talenta_1 }} (Skor: {{ $talenta->skor_penilaian_1 ?: '-' }})</span>
            </div>
            @endif
            @if($talenta->nomor_talenta_2)
            <div class="data-item">
                <label>TPT Level 2:</label>
                <span>{{ $talenta->nomor_talenta_2 }} (Skor: {{ $talenta->skor_penilaian_2 ?: '-' }})</span>
            </div>
            @endif
            @if($talenta->nomor_talenta_3)
            <div class="data-item">
                <label>TPT Level 3:</label>
                <span>{{ $talenta->nomor_talenta_3 }} (Skor: {{ $talenta->skor_penilaian_3 ?: '-' }})</span>
            </div>
            @endif
            @if($talenta->nomor_talenta_4)
            <div class="data-item">
                <label>TPT Level 4:</label>
                <span>{{ $talenta->nomor_talenta_4 }} (Skor: {{ $talenta->skor_penilaian_4 ?: '-' }})</span>
            </div>
            @endif
            @if($talenta->nomor_talenta_5)
            <div class="data-item">
                <label>TPT Level 5:</label>
                <span>{{ $talenta->nomor_talenta_5 }} (Skor: {{ $talenta->skor_penilaian_5 ?: '-' }})</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Pendidikan Kader -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-graduation-cap"></i>
            </div>
            <h6 class="section-title">PENDIDIKAN KADER</h6>
        </div>

        <div class="section-content">
            <div class="data-item">
                <label>PKPNU/PDPKPNU:</label>
                <span>{{ $talenta->pkpnu_status === 'sudah' ? 'Sudah' : 'Belum' }}</span>
            </div>
            <div class="data-item">
                <label>MKNU:</label>
                <span>{{ $talenta->mknu_status === 'sudah' ? 'Sudah' : 'Belum' }}</span>
            </div>
            <div class="data-item">
                <label>PMKNU:</label>
                <span>{{ $talenta->pmknu_status === 'sudah' ? 'Sudah' : 'Belum' }}</span>
            </div>
        </div>
    </div>

    <!-- Proyeksi Diri -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-target"></i>
            </div>
            <h6 class="section-title">PROYEKSI DIRI</h6>
        </div>

        <div class="section-content">
            <div class="data-item">
                <label>Jabatan Saat Ini:</label>
                <span>{{ $talenta->jabatan_saat_ini ?: '-' }}</span>
            </div>
            <div class="data-item">
                <label>Proyeksi Akademik:</label>
                <span>{{ $talenta->proyeksi_akademik ?: '-' }}</span>
            </div>
            <div class="data-item">
                <label>Proyeksi Jabatan Level 1:</label>
                <span>{{ $talenta->proyeksi_jabatan_level1 ?: '-' }}</span>
            </div>
        </div>
    </div>

    <!-- File Attachments -->
    @if($talenta->foto_resmi || $talenta->foto_bebas || $talenta->foto_keluarga || $talenta->ijazah_s1 || $talenta->ijazah_s2 || $talenta->ijazah_s3 || $talenta->sertifikat_tpt_1 || $talenta->sertifikat_tpt_2 || $talenta->sertifikat_tpt_3 || $talenta->sertifikat_tpt_4 || $talenta->sertifikat_tpt_5 || $talenta->produk_unggulan_1 || $talenta->produk_unggulan_2 || $talenta->produk_unggulan_3 || $talenta->produk_unggulan_4 || $talenta->produk_unggulan_5 || $talenta->pkpnu_sertifikat || $talenta->mknu_sertifikat || $talenta->pmknu_sertifikat || $talenta->gtt_ptt_sk || $talenta->gty_sk || $talenta->lampiran_step_1 || $talenta->lampiran_step_2 || $talenta->lampiran_step_3 || $talenta->lampiran_step_4)
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-file"></i>
            </div>
            <h6 class="section-title">FILE TERLAMPIR</h6>
        </div>

        <div class="section-content">
            <!-- TPT Sertifikat Files -->
            @if($talenta->sertifikat_tpt_1)
            <div class="data-item">
                <label>Sertifikat TPT Level 1:</label>
                <a href="/storage/{{ $talenta->sertifikat_tpt_1 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->sertifikat_tpt_2)
            <div class="data-item">
                <label>Sertifikat TPT Level 2:</label>
                <a href="/storage/{{ $talenta->sertifikat_tpt_2 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->sertifikat_tpt_3)
            <div class="data-item">
                <label>Sertifikat TPT Level 3:</label>
                <a href="/storage/{{ $talenta->sertifikat_tpt_3 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->sertifikat_tpt_4)
            <div class="data-item">
                <label>Sertifikat TPT Level 4:</label>
                <a href="/storage/{{ $talenta->sertifikat_tpt_4 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->sertifikat_tpt_5)
            <div class="data-item">
                <label>Sertifikat TPT Level 5:</label>
                <a href="/storage/{{ $talenta->sertifikat_tpt_5 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif

            <!-- TPT Produk Unggulan Files -->
            @if($talenta->produk_unggulan_1)
            <div class="data-item">
                <label>Produk Unggulan Level 1:</label>
                <a href="/storage/{{ $talenta->produk_unggulan_1 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->produk_unggulan_2)
            <div class="data-item">
                <label>Produk Unggulan Level 2:</label>
                <a href="/storage/{{ $talenta->produk_unggulan_2 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->produk_unggulan_3)
            <div class="data-item">
                <label>Produk Unggulan Level 3:</label>
                <a href="/storage/{{ $talenta->produk_unggulan_3 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->produk_unggulan_4)
            <div class="data-item">
                <label>Produk Unggulan Level 4:</label>
                <a href="/storage/{{ $talenta->produk_unggulan_4 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->produk_unggulan_5)
            <div class="data-item">
                <label>Produk Unggulan Level 5:</label>
                <a href="/storage/{{ $talenta->produk_unggulan_5 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif

            <!-- Pendidikan Kader Files -->
            @if($talenta->pkpnu_sertifikat)
            <div class="data-item">
                <label>Sertifikat PKPNU/PDPKPNU:</label>
                <a href="/storage/app/public{{ $talenta->pkpnu_sertifikat }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->mknu_sertifikat)
            <div class="data-item">
                <label>Sertifikat MKNU:</label>
                <a href="/storage/{{ $talenta->mknu_sertifikat }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->pmknu_sertifikat)
            <div class="data-item">
                <label>Sertifikat PMKNU:</label>
                <a href="/storage/{{ $talenta->pmknu_sertifikat }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif

            <!-- Foto Files -->
            @if($talenta->foto_resmi)
            <div class="data-item">
                <label>Foto Resmi:</label>
                <a href="/storage/{{ $talenta->foto_resmi }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->foto_bebas)
            <div class="data-item">
                <label>Foto Bebas:</label>
                <a href="/storage/{{ $talenta->foto_bebas }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->foto_keluarga)
            <div class="data-item">
                <label>Foto Keluarga:</label>
                <a href="/storage/{{ $talenta->foto_keluarga }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif

            <!-- Ijazah Files -->
            @if($talenta->ijazah_s1)
            <div class="data-item">
                <label>Ijazah S1:</label>
                <a href="/storage/{{ $talenta->ijazah_s1 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->ijazah_s2)
            <div class="data-item">
                <label>Ijazah S2:</label>
                <a href="/storage/{{ $talenta->ijazah_s2 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->ijazah_s3)
            <div class="data-item">
                <label>Ijazah S3:</label>
                <a href="/storage/{{ $talenta->ijazah_s3 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif

            <!-- SK Files -->
            @if($talenta->gtt_ptt_sk)
            <div class="data-item">
                <label>SK GTT-PTT:</label>
                <a href="/storage/{{ $talenta->gtt_ptt_sk }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->gty_sk)
            <div class="data-item">
                <label>SK GTY:</label>
                <a href="/storage/{{ $talenta->gty_sk }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif

            <!-- Lampiran Files -->
            @if($talenta->lampiran_step_1)
            <div class="data-item">
                <label>Lampiran Step 1:</label>
                <a href="/storage/{{ $talenta->lampiran_step_1 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->lampiran_step_2)
            <div class="data-item">
                <label>Lampiran Step 2:</label>
                <a href="/storage/{{ $talenta->lampiran_step_2 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->lampiran_step_3)
            <div class="data-item">
                <label>Lampiran Step 3:</label>
                <a href="/storage/{{ $talenta->lampiran_step_3 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
            @if($talenta->lampiran_step_4)
            <div class="data-item">
                <label>Lampiran Step 4:</label>
                <a href="/storage/{{ $talenta->lampiran_step_4 }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
function printData() {
    window.print();
}
</script>

@endsection
