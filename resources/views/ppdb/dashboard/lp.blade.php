@extends('layouts.master')

@section('title', 'Dashboard PPDB - Super Admin')

@push('css')
<style>
    .ppdb-header {
        background: linear-gradient(135deg, #004b4c 0%, #667eea 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 75, 76, 0.2);
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #004b4c;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.total-sekolah { border-left-color: #004b4c; }
    .stat-card.total-pendaftar { border-left-color: #efaa0c; }
    .stat-card.sekolah-buka { border-left-color: #667eea; }
    .stat-card.pending { border-left-color: #ffc107; }
    .stat-card.verifikasi { border-left-color: #17a2b8; }
    .stat-card.lulus { border-left-color: #28a745; }
    .stat-card.tidak-lulus { border-left-color: #dc3545; }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #004b4c;
        color: white;
        border: none;
        font-weight: 600;
        padding: 1rem;
    }

    .table tbody tr {
        transition: background-color 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-buka { background-color: #28a745; color: white; }
    .badge-tutup { background-color: #dc3545; color: white; }
    .badge-belum-buka { background-color: #ffc107; color: black; }
    .badge-tidak-aktif { background-color: #6c757d; color: white; }

    .sekolah-name {
        font-weight: 600;
        color: #004b4c;
    }

    .action-btn {
        background: linear-gradient(45deg, #004b4c, #667eea);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .ppdb-header {
            padding: 1.5rem;
        }

        .stat-card {
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Dashboard -->
    <div class="ppdb-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">
                    <i class="bx bx-bar-chart-alt-2 me-2"></i>
                    Dashboard Monitoring PPDB
                </h2>
                <p class="mb-0 opacity-75">Pantau pendaftaran siswa baru di seluruh madrasah</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex align-items-center justify-content-lg-end">
                    <i class="bx bx-calendar me-2"></i>
                    <span>Tahun {{ date('Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card total-sekolah">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-primary">{{ number_format($statistik['total_sekolah']) }}</div>
                        <div class="stat-label">Total Madrasah</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-building-house" style="font-size: 2.5rem; color: #004b4c;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card total-pendaftar">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-warning">{{ number_format($statistik['total_pendaftar']) }}</div>
                        <div class="stat-label">Total Pendaftar</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-user-plus" style="font-size: 2.5rem; color: #efaa0c;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card sekolah-buka">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-info">{{ number_format($statistik['total_buka']) }}</div>
                        <div class="stat-label">PPDB Aktif</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-check-circle" style="font-size: 2.5rem; color: #667eea;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card lulus">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-success">{{ number_format($statistik['lulus']) }}</div>
                        <div class="stat-label">Lulus Seleksi</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-check-double" style="font-size: 2.5rem; color: #28a745;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Detail -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card pending">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-warning">{{ number_format($statistik['pending']) }}</div>
                        <div class="stat-label">Menunggu Verifikasi</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-time" style="font-size: 2rem; color: #ffc107;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card verifikasi">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-info">{{ number_format($statistik['verifikasi']) }}</div>
                        <div class="stat-label">Dalam Verifikasi</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-search" style="font-size: 2rem; color: #17a2b8;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card tidak-lulus">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-danger">{{ number_format($statistik['tidak_lulus']) }}</div>
                        <div class="stat-label">Tidak Lulus</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-x" style="font-size: 2rem; color: #dc3545;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-number text-secondary">
                            {{ $statistik['total_sekolah'] > 0 ? round(($statistik['lulus'] / $statistik['total_pendaftar']) * 100, 1) : 0 }}%
                        </div>
                        <div class="stat-label">Tingkat Kelulusan</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-trending-up" style="font-size: 2rem; color: #6c757d;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Sekolah -->
    <div class="card">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="bx bx-list-ul me-2"></i>
                    Detail Pendaftaran per Madrasah
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                        <i class="bx bx-refresh me-1"></i>Refresh
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="exportData()">
                        <i class="bx bx-download me-1"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="bx bx-building me-1"></i>Madrasah</th>
                            <th><i class="bx bx-info-circle me-1"></i>Status PPDB</th>
                            <th class="text-center"><i class="bx bx-user-plus me-1"></i>Total</th>
                            <th class="text-center"><i class="bx bx-time me-1"></i>Pending</th>
                            <th class="text-center"><i class="bx bx-search me-1"></i>Verifikasi</th>
                            <th class="text-center"><i class="bx bx-check me-1"></i>Lulus</th>
                            <th class="text-center"><i class="bx bx-x me-1"></i>Tidak Lulus</th>
                            <th class="text-center"><i class="bx bx-bar-chart me-1"></i>Tingkat Kelulusan</th>
                            <th><i class="bx bx-cog me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailSekolah as $detail)
                        <tr>
                            <td>
                                <div class="sekolah-name">{{ $detail['sekolah']->nama }}</div>
                                <small class="text-muted">{{ $detail['sekolah']->kabupaten }}, {{ $detail['sekolah']->provinsi }}</small>
                            </td>
                            <td>
                                @if($detail['status_ppdb'] === 'buka')
                                    <span class="badge badge-buka">Buka</span>
                                @elseif($detail['status_ppdb'] === 'tutup')
                                    <span class="badge badge-tutup">Tutup</span>
                                @elseif($detail['status_ppdb'] === 'belum_buka')
                                    <span class="badge badge-belum-buka">Belum Buka</span>
                                @else
                                    <span class="badge badge-tidak-aktif">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $detail['total'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $detail['pending'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $detail['verifikasi'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $detail['lulus'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $detail['tidak_lulus'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $detail['total'] > 0 ? ($detail['lulus'] / $detail['total'] > 0.5 ? 'bg-success' : 'bg-warning') : 'bg-secondary' }}">
                                    {{ $detail['total'] > 0 ? round(($detail['lulus'] / $detail['total']) * 100, 1) : 0 }}%
                                </span>
                            </td>
                            <td>
                                @if($detail['ppdb_setting'])
                                    <a href="{{ route('ppdb.sekolah.dashboard', $detail['ppdb_setting']->slug) }}" class="action-btn btn-sm">
                                        <i class="bx bx-show me-1"></i>Lihat Detail
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm" disabled>
                                        <i class="bx bx-block me-1"></i>Tidak Aktif
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bx bx-info-circle" style="font-size: 3rem; color: #6c757d;"></i>
                                <p class="mt-2 text-muted">Belum ada data madrasah</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportData() {
    // Implementasi export data
    alert('Fitur export akan segera diimplementasikan');
}

$(document).ready(function() {
    // Auto refresh setiap 5 menit
    setInterval(function() {
        // Uncomment untuk auto refresh
        // window.location.reload();
    }, 300000);
});
</script>
@endpush
@endsection
