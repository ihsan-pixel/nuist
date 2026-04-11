@extends('layouts.master')

@section('title')Dashboard SPP Siswa @endsection

@section('css')
<style>
.spp-hero {
    background: linear-gradient(135deg, #0f3d3e 0%, #14866d 100%);
    color: #fff;
    border: none;
    border-radius: 20px;
}
.spp-stat {
    border: none;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}
.spp-chip {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.25rem;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Dashboard SPP Siswa @endslot
@endcomponent

<div class="card spp-hero mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <h4 class="text-white mb-2"><i class="bx bx-wallet me-2"></i>Dashboard SPP Siswa</h4>
                <p class="mb-0 text-white-50">Modul baru SPP siswa yang terhubung langsung ke data siswa, terpisah dari tagihan lama, dan sudah disiapkan untuk alur pembayaran BNI Virtual Account.</p>
            </div>
            <form method="GET" class="row g-2 align-items-end" style="min-width: 280px;">
                @if($userRole !== 'admin')
                    <div class="col-12">
                        <label class="form-label text-white">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            @foreach($madrasahOptions as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ (string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : '' }}>{{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-light w-100"><i class="bx bx-filter-alt me-1"></i>Terapkan</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-primary"><i class="bx bx-user"></i></div><div><div class="text-muted">Total Siswa</div><h4 class="mb-0">{{ number_format($stats['total_siswa']) }}</h4></div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-success"><i class="bx bx-check-circle"></i></div><div><div class="text-muted">Tagihan Lunas</div><h4 class="mb-0">{{ number_format($stats['tagihan_lunas']) }}</h4></div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-warning"><i class="bx bx-time-five"></i></div><div><div class="text-muted">Belum Lunas</div><h4 class="mb-0">{{ number_format($stats['tagihan_belum_lunas']) }}</h4></div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card spp-stat h-100"><div class="card-body d-flex align-items-center gap-3"><div class="spp-chip bg-info"><i class="bx bx-cog"></i></div><div><div class="text-muted">Pengaturan Aktif</div><h4 class="mb-0">{{ number_format($stats['pengaturan_aktif']) }}</h4></div></div></div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card spp-stat h-100"><div class="card-body"><div class="text-muted mb-2">Total Nominal Tagihan</div><h4>Rp {{ number_format($stats['nominal_tagihan'], 0, ',', '.') }}</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card spp-stat h-100"><div class="card-body"><div class="text-muted mb-2">Total Terbayar</div><h4>Rp {{ number_format($stats['nominal_terbayar'], 0, ',', '.') }}</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card spp-stat h-100"><div class="card-body"><div class="text-muted mb-2">Total Transaksi</div><h4>{{ number_format($stats['total_transaksi']) }}</h4></div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Tagihan Terbaru</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>No Tagihan</th><th>Siswa</th><th>Status</th><th>Total</th></tr></thead>
                        <tbody>
                        @forelse($recentBills as $bill)
                            <tr>
                                <td>{{ $bill->nomor_tagihan }}</td>
                                <td>{{ $bill->siswa->nama_lengkap ?? '-' }}</td>
                                <td><span class="badge bg-{{ $bill->status === 'lunas' ? 'success' : ($bill->status === 'sebagian' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $bill->status)) }}</span></td>
                                <td>Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada tagihan SPP siswa.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Transaksi Terbaru</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>No Transaksi</th><th>Siswa</th><th>Verifikasi</th><th>Nominal</th></tr></thead>
                        <tbody>
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->nomor_transaksi }}</td>
                                <td>{{ $transaction->siswa->nama_lengkap ?? '-' }}</td>
                                <td><span class="badge bg-{{ $transaction->status_verifikasi === 'diverifikasi' ? 'success' : ($transaction->status_verifikasi === 'ditolak' ? 'danger' : 'warning') }}">{{ ucfirst($transaction->status_verifikasi) }}</span></td>
                                <td>Rp {{ number_format($transaction->nominal_bayar, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada transaksi SPP siswa.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
