@extends('layouts.master')

@section('title')Transaksi SPP Siswa @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SPP Siswa @endslot
    @slot('title') Transaksi @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Terjadi kesalahan.</strong>
        <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-body d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
        <div>
            <h4 class="mb-1">Transaksi SPP Siswa</h4>
            <p class="text-muted mb-0">Pencatatan pembayaran SPP siswa baru yang terhubung ke tagihan SPP siswa.</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTransactionModal"><i class="bx bx-plus me-1"></i>Tambah Transaksi</button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-3 align-items-end">
                @if($userRole !== 'admin')
                    <div class="col-md-3">
                        <label class="form-label">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            @foreach($madrasahOptions as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ (string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : '' }}>{{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3">
                    <label class="form-label">Verifikasi</label>
                    <select name="status_verifikasi" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu" @selected(request('status_verifikasi') === 'menunggu')>Menunggu</option>
                        <option value="diverifikasi" @selected(request('status_verifikasi') === 'diverifikasi')>Diverifikasi</option>
                        <option value="ditolak" @selected(request('status_verifikasi') === 'ditolak')>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Pencarian</label><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="No transaksi, nama, NIS"></div>
                <div class="col-md-2 d-grid"><button class="btn btn-primary">Filter</button></div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Transaksi</th>
                        <th>Tagihan</th>
                        <th>Siswa</th>
                        <th>Tanggal Bayar</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $transactions->firstItem() + $index }}</td>
                        <td>{{ $transaction->nomor_transaksi }}</td>
                        <td>{{ $transaction->bill->nomor_tagihan ?? '-' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $transaction->siswa->nama_lengkap ?? '-' }}</div>
                            <small class="text-muted">{{ $transaction->siswa->nis ?? '-' }}</small>
                        </td>
                        <td>{{ optional($transaction->tanggal_bayar)->format('d M Y') }}</td>
                        <td>Rp {{ number_format($transaction->nominal_bayar, 0, ',', '.') }}</td>
                        <td>{{ $transaction->metode_pembayaran }}</td>
                        <td><span class="badge bg-{{ $transaction->status_verifikasi === 'diverifikasi' ? 'success' : ($transaction->status_verifikasi === 'ditolak' ? 'danger' : 'warning') }}">{{ ucfirst($transaction->status_verifikasi) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada transaksi SPP siswa.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
    </div>
</div>

<div class="modal fade" id="createTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('spp-siswa.transaksi.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi SPP Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tagihan</label>
                            <select name="bill_id" class="form-select" required>
                                <option value="">Pilih Tagihan</option>
                                @foreach($bills as $bill)
                                    <option value="{{ $bill->id }}">{{ $bill->nomor_tagihan }} - {{ $bill->siswa->nama_lengkap ?? '-' }} - Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3"><label class="form-label">Tanggal Bayar</label><input type="date" name="tanggal_bayar" class="form-control" required></div>
                        <div class="col-md-3"><label class="form-label">Nominal</label><input type="number" min="0" name="nominal_bayar" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Metode Pembayaran</label><input type="text" name="metode_pembayaran" class="form-control" placeholder="Transfer, Cash, QRIS" required></div>
                        <div class="col-md-4">
                            <label class="form-label">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select" required>
                                <option value="menunggu">Menunggu</option>
                                <option value="diverifikasi">Diverifikasi</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">Keterangan</label><input type="text" name="keterangan" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
