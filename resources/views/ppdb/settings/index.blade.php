@extends('layouts.master')

@section('title', 'Pengaturan PPDB - ' . now()->year)

@push('css')
<style>
    .settings-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .school-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .school-logo {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        margin-right: 1rem;
        border: 2px solid #dee2e6;
    }

    .school-info h5 {
        margin: 0;
        color: #004b4c;
        font-weight: 600;
    }

    .school-info p {
        margin: 0.25rem 0 0 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-buka {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .status-tutup {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        color: white;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .setting-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        border-left: 4px solid #004b4c;
    }

    .setting-label {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .setting-value {
        color: #495057;
        font-size: 0.95rem;
        margin: 0;
    }

    .btn-edit {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .quota-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .quota-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .quota-item:last-child {
        border-bottom: none;
    }

    .quota-major {
        font-weight: 600;
        color: #004b4c;
    }

    .quota-number {
        background: #004b4c;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .settings-card {
            padding: 1rem;
        }

        .school-header {
            flex-direction: column;
            text-align: center;
        }

        .school-logo {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="mdi mdi-cog-outline me-2 text-primary"></i>
                Pengaturan PPDB {{ now()->year }}
            </h2>
            <p class="text-muted mb-0">Kelola pengaturan penerimaan peserta didik baru</p>
        </div>
        <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($ppdbSettings->count() > 0)
        @foreach($ppdbSettings as $setting)
        <div class="settings-card">
            <!-- School Header -->
            <div class="school-header">
                @if($setting->sekolah && $setting->sekolah->logo)
                    <img src="{{ asset('images/madrasah/' . $setting->sekolah->logo) }}"
                         alt="Logo {{ $setting->nama_sekolah }}"
                         class="school-logo"
                         onerror="this.src='{{ asset('images/default-school.png') }}'">
                @else
                    <div class="school-logo bg-light d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-school text-muted fs-4"></i>
                    </div>
                @endif

                <div class="school-info flex-grow-1">
                    <h5>{{ $setting->nama_sekolah }}</h5>
                    <p>{{ $setting->sekolah ? $setting->sekolah->kabupaten : 'Kabupaten tidak tersedia' }}</p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="status-badge status-{{ $setting->status }}">
                        {{ $setting->status === 'buka' ? 'Buka' : 'Tutup' }}
                    </span>
                    <a href="{{ route('ppdb.settings.edit', $setting->id) }}" class="btn btn-edit">
                        <i class="mdi mdi-pencil me-1"></i>Edit Pengaturan
                    </a>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="settings-grid">
                <!-- Jadwal PPDB -->
                <div class="setting-item">
                    <div class="setting-label">
                        <i class="mdi mdi-calendar-clock me-1"></i>Jadwal PPDB
                    </div>
                    <div class="setting-value">
                        @if($setting->jadwal_buka && $setting->jadwal_tutup)
                            {{ $setting->jadwal_buka->format('d/m/Y H:i') }} - {{ $setting->jadwal_tutup->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">Belum diatur</span>
                        @endif
                    </div>
                </div>

                <!-- Kuota Total -->
                <div class="setting-item">
                    <div class="setting-label">
                        <i class="mdi mdi-account-group me-1"></i>Kuota Total
                    </div>
                    <div class="setting-value">
                        {{ number_format($setting->kuota_total) }} siswa
                    </div>
                </div>

                <!-- Periode Presensi -->
                <div class="setting-item">
                    <div class="setting-label">
                        <i class="mdi mdi-clipboard-check me-1"></i>Periode Presensi
                    </div>
                    <div class="setting-value">
                        @if($setting->periode_presensi_mulai && $setting->periode_presensi_selesai)
                            {{ $setting->periode_presensi_mulai->format('d/m/Y') }} - {{ $setting->periode_presensi_selesai->format('d/m/Y') }}
                        @else
                            <span class="text-muted">Belum diatur</span>
                        @endif
                    </div>
                </div>

                <!-- Pengumuman -->
                <div class="setting-item">
                    <div class="setting-label">
                        <i class="mdi mdi-bullhorn me-1"></i>Pengumuman Hasil
                    </div>
                    <div class="setting-value">
                        @if($setting->jadwal_pengumuman)
                            {{ $setting->jadwal_pengumuman->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">Belum diatur</span>
                        @endif
                    </div>
                </div>

                <!-- Persyaratan Upload -->
                <div class="setting-item">
                    <div class="setting-label">
                        <i class="mdi mdi-file-upload me-1"></i>Persyaratan Upload
                    </div>
                    <div class="setting-value">
                        @php
                            $wajib = [];
                            if($setting->wajib_unggah_foto) $wajib[] = 'Foto';
                            if($setting->wajib_unggah_ijazah) $wajib[] = 'Ijazah';
                            if($setting->wajib_unggah_kk) $wajib[] = 'KK';
                        @endphp
                        @if(count($wajib) > 0)
                            {{ implode(', ', $wajib) }}
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </div>
                </div>

                <!-- Kontak -->
                <div class="setting-item">
                    <div class="setting-label">
                        <i class="mdi mdi-phone me-1"></i>Kontak
                    </div>
                    <div class="setting-value">
                        @if($setting->telepon_kontak)
                            {{ $setting->telepon_kontak }}
                        @else
                            <span class="text-muted">Belum diatur</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kuota per Jurusan -->
            @if($setting->kuota_jurusan && is_array($setting->kuota_jurusan) && count($setting->kuota_jurusan) > 0)
            <div class="quota-info">
                <h6 class="mb-3">
                    <i class="mdi mdi-chart-pie me-1"></i>Kuota per Jurusan
                </h6>
                @foreach($setting->kuota_jurusan as $jurusan => $kuota)
                    <div class="quota-item">
                        <span class="quota-major">{{ $jurusan }}</span>
                        <span class="quota-number">{{ number_format($kuota) }}</span>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="settings-card">
            <div class="empty-state">
                <i class="mdi mdi-cog-outline"></i>
                <h4>Tidak ada pengaturan PPDB</h4>
                <p>Belum ada sekolah yang mengatur PPDB untuk tahun {{ now()->year }}</p>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here if needed
});
</script>
@endpush
@endsection
