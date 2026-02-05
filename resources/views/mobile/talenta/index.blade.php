@extends('layouts.mobile')

@section('title', 'Menu Talenta')

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
    <button onclick="window.location.href='{{ route('mobile.dashboard') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>MENU TALENTA</h4>
    <p>Update Data Peserta</p>
</div>

<!-- Main Content -->
<div class="form-container">
    <!-- Success Alert will be shown via SweetAlert -->
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" style="display: none;"></div>
    @endif

    <!-- Action Buttons -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-list-ul"></i>
            </div>
            <h6 class="section-title">Aksi</h6>
        </div>

        <div class="section-content">
            @if($talenta)
                <!-- Existing Record -->
                <div class="action-buttons">
                    <a href="{{ route('mobile.talenta.show', $talenta->id) }}" class="btn btn-primary w-100 mb-3">
                        <i class="bx bx-show"></i>
                        Lihat Data Talenta
                    </a>
                    <a href="{{ route('mobile.talenta.edit', $talenta->id) }}" class="btn btn-warning w-100 mb-3">
                        <i class="bx bx-edit"></i>
                        Edit Data Talenta
                    </a>
                    <div class="status-info">
                        <p><strong>Status:</strong>
                            <span class="badge {{ $talenta->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $talenta->status === 'published' ? 'Published' : 'Draft' }}
                            </span>
                        </p>
                        <p><strong>Terakhir Update:</strong> {{ $talenta->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            @else
                <!-- No Record Yet -->
                <div class="text-center">
                    <div class="mb-4">
                        <i class="bx bx-plus-circle" style="font-size: 48px; color: #004b4c;"></i>
                    </div>
                    <h6 class="mb-3">Belum ada data talenta</h6>
                    <p class="text-muted mb-4">Silakan buat data talenta pertama Anda</p>
                    <a href="{{ route('mobile.talenta.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i>
                        Buat Data Talenta
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-info-circle"></i>
            </div>
            <h6 class="section-title">Informasi Menu Talenta</h6>
        </div>

        <div class="section-content">
            <div class="info-note">
                <p><strong>Menu Talenta</strong> digunakan untuk memperbarui data capaian level TPT dan informasi pribadi peserta.</p>
                <p>Form terdiri dari 4 langkah utama:</p>
                <ol style="margin: 10px 0 0 20px; padding: 0;">
                    <li>Update Level TPT (5 level)</li>
                    <li>Pendidikan Kader</li>
                    <li>Proyeksi Diri</li>
                    <li>Data Diri</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
.action-buttons .btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
}

.status-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.status-info p {
    margin: 5px 0;
    font-size: 13px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.stat-icon {
    font-size: 24px;
    color: #004b4c;
    min-width: 24px;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 16px;
    font-weight: 700;
    color: #004b4c;
    margin-bottom: 2px;
}

.stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

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
@endsection
