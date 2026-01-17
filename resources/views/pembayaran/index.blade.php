@extends('layouts.master')

@section('title')Dashboard Pembayaran @endsection

@section('css')
<style>
/* Modern Card Grid Design */
.dashboard-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.menu-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.menu-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #48bb78, #38a169);
}

.menu-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin: 0 auto 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.menu-icon.payment { background: linear-gradient(135deg, #667eea, #764ba2); }
.menu-icon.cash { background: linear-gradient(135deg, #4299e1, #3182ce); }
.menu-icon.gateway { background: linear-gradient(135deg, #ed8936, #dd6b20); }
.menu-icon.history { background: linear-gradient(135deg, #004b4c, #0e8549); }

.menu-card:hover .menu-icon {
    transform: scale(1.1);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
}

/* .menu-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 10px;
} */

.menu-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 20px;
}

.btn-modern {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 500;
    color: white;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
    color: white;
    text-decoration: none;
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Statistics Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Expandable Sections */
.expandable-section {
    display: none;
    margin-top: 20px;
}

.expandable-section.show {
    display: block;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-card {
        margin-bottom: 1rem;
    }

    .dashboard-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .menu-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .menu-title {
        font-size: 1.1rem;
    }
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Dashboard Pembayaran @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-credit-card me-2"></i>
                    Dashboard Pembayaran
                </h4>
                <p class="text-white-50 mb-0">
                    Sistem pembayaran terintegrasi dengan data . Kelola pembayaran iuran, pantau status pembayaran, dan proses transaksi dengan mudah.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Lunas</p>
                        <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'lunas')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bx bx-time"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Sebagian</p>
                        <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'sebagian')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bx bx-x-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Belum Bayar</p>
                        <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'belum_lunas')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Tagihan</p>
                        <h5 class="mb-0">Rp {{ number_format(collect($data)->sum('total_nominal'), 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Cards Grid -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="menu-card">
                            <div class="menu-icon payment">
                                <i class="bx bx-list-ul"></i>
                            </div>
                            <h3 class="menu-title">Data Pembayaran</h3>
                            <p class="menu-description">Lihat daftar pembayaran semua entitas dengan status terkini</p>
                            <button type="button" class="btn-modern" onclick="toggleSection('payment-data')">
                                <i class="bx bx-right-arrow-alt me-1"></i> Lihat Data
                            </button>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="menu-card">
                            <div class="menu-icon cash">
                                <i class="bx bx-cash"></i>
                            </div>
                            <h3 class="menu-title">Pembayaran Cash</h3>
                            <p class="menu-description">Proses pembayaran tunai langsung untuk madrasah</p>
                            <button type="button" class="btn-modern" onclick="toggleSection('cash-payment')">
                                <i class="bx bx-right-arrow-alt me-1"></i> Bayar Cash
                            </button>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="menu-card">
                            <div class="menu-icon gateway">
                                <i class="bx bx-credit-card"></i>
                            </div>
                            <h3 class="menu-title">Payment Gateway</h3>
                            <p class="menu-description">Integrasi dengan Midtrans untuk pembayaran online</p>
                            <button type="button" class="btn-modern" disabled>
                                <i class="bx bx-right-arrow-alt me-1"></i> Segera Hadir
                            </button>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="menu-card">
                            <div class="menu-icon history">
                                <i class="bx bx-history"></i>
                            </div>
                            <h3 class="menu-title">Riwayat Pembayaran</h3>
                            <p class="menu-description">Lihat history pembayaran dan laporan transaksi</p>
                            <button type="button" class="btn-modern" disabled>
                                <i class="bx bx-right-arrow-alt me-1"></i> Segera Hadir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Expandable Sections -->
<div id="payment-data" class="expandable-section">
    <div class="card dashboard-card">
        <div class="card-header">
            <h4 class="card-title mb-0">
                <i class="bx bx-list-ul me-2"></i>Data Pembayaran Madrasah
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Madrasah</th>
                            <th>Total Tagihan</th>
                            <th>Status Pembayaran</th>
                            <th>Nominal Dibayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $item->madrasah->name }}</h6>
                                    <small class="text-muted">{{ $item->madrasah->address ?? '-' }}</small>
                                </div>
                            </td>
                            <td>Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-modern bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status_pembayaran)) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($item->nominal_dibayar, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ rou .pembayaran.detail', ['madrasah_id' => $item->madrasah->id, 'tahun' => $tahun]) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bx bx-detail me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="cash-payment" class="expandable-section">
    <div class="card dashboard-card">
        <div class="card-header">
            <h4 class="card-title mb-0">
                <i class="bx bx-cash me-2"></i>Pembayaran Cash
            </h4>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="bx bx-info-circle me-2"></i>
                Pilih entitas dari tabel di atas untuk melakukan pembayaran cash.
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const allSections = document.querySelectorAll('.expandable-section');

    // Hide all sections
    allSections.forEach(sec => {
        if (sec.id !== sectionId) {
            sec.classList.remove('show');
        }
    });

    // Toggle current section
    section.classList.toggle('show');

    // Scroll to section if shown
    if (section.classList.contains('show')) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>
@endsection
