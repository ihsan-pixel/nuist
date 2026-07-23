@extends('layouts.master')

@section('title')Update UPPM @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') UPPM @endslot
    @slot('title') Update UPPM @endslot
@endcomponent

<style>
    .uppm-summary-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .uppm-summary-card .card-body {
        padding: 1.25rem 1.35rem;
    }

    .uppm-summary-card .label {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }

    .uppm-summary-card .value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }

    .uppm-panel {
        border: 0;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.07);
    }

    .uppm-panel .card-body {
        padding: 1.5rem;
    }

    .uppm-period-cell {
        min-width: 180px;
    }

    .uppm-entry {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.65rem 0.8rem;
        margin-bottom: 0.65rem;
    }

    .uppm-entry:last-child {
        margin-bottom: 0;
    }

    .uppm-entry small {
        color: #64748b;
    }

    .uppm-sticky-col {
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 1;
    }
</style>

<div class="row g-3 mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="card uppm-summary-card">
            <div class="card-body">
                <div class="label">Tahun Anggaran</div>
                <div class="value">{{ $tahun }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card uppm-summary-card">
            <div class="card-body">
                <div class="label">Sekolah Lunas</div>
                <div class="value text-success">{{ number_format($lunasCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card uppm-summary-card">
            <div class="card-body">
                <div class="label">Sekolah Bayar Sebagian</div>
                <div class="value text-warning">{{ number_format($partialCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card uppm-summary-card">
            <div class="card-body">
                <div class="label">Total Pembayaran</div>
                <div class="value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card uppm-panel mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="mb-1">Filter dan Input Update UPPM</h5>
                <p class="text-muted mb-0">Super admin dapat mencatat transfer per sekolah dan per semester. Status lunas dihitung otomatis dari total pembayaran terhadap tagihan tahun anggaran yang dipilih.</p>
            </div>
            <form method="GET" action="{{ route('uppm.update-uppm') }}" class="d-flex gap-2">
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @foreach($yearOptions as $yearOption)
                        <option value="{{ $yearOption }}" @selected((int) $tahun === (int) $yearOption)>{{ $yearOption }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="row g-3 align-items-end">
            <div class="col-lg-3">
                <label class="form-label">Total Target Tahun Ini</label>
                <input type="text" class="form-control" value="Rp {{ number_format($totalTarget, 0, ',', '.') }}" disabled>
            </div>
            <div class="col-lg-9">
                <form method="POST" action="{{ route('uppm.update-uppm.store') }}" class="row g-3">
                    @csrf
                    <input type="hidden" name="tahun_anggaran" value="{{ $tahun }}">

                    <div class="col-lg-4">
                        <label class="form-label">Sekolah</label>
                        <select name="madrasah_id" class="form-select" required>
                            <option value="">Pilih sekolah</option>
                            @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}" @selected(old('madrasah_id') == $madrasah->id)>{{ $madrasah->scod ?: '-' }} - {{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Periode</label>
                        <select name="payment_period" class="form-select" required>
                            @foreach($periodOptions as $periodKey => $periodLabel)
                                <option value="{{ $periodKey }}" @selected(old('payment_period') === $periodKey)>{{ $periodLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Tgl Transfer</label>
                        <input type="date" name="transfer_date" class="form-control" value="{{ old('transfer_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="amount" class="form-control" min="0" step="0.01" value="{{ old('amount') }}" required>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="note" class="form-control" value="{{ old('note') }}" placeholder="Opsional">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Simpan Update UPPM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card uppm-panel mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="mb-1">Rekap Pembayaran UPPM Tahun {{ $tahun }}</h5>
                <p class="text-muted mb-0">Tampilan ini merangkum pembayaran `Jan-Jun` dan `Jul-Des` agar super admin mudah mencocokkan dengan rekap manual.</p>
            </div>
            <span class="badge bg-light text-dark">{{ number_format($summaryRows->count()) }} sekolah</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="uppm-sticky-col">No</th>
                        <th>SCOD</th>
                        <th>Nama Sekolah</th>
                        <th class="uppm-period-cell">Jan-Jun</th>
                        <th class="uppm-period-cell">Jul-Des</th>
                        <th>Total Bayar</th>
                        <th>Tagihan</th>
                        <th>Sisa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summaryRows as $index => $row)
                        @php($madrasah = $row['madrasah'])
                        @php($summary = $row['summary'])
                        @php($paymentsByPeriod = $summary['payments_by_period'] ?? collect())
                        <tr>
                            <td class="uppm-sticky-col">{{ $index + 1 }}</td>
                            <td>{{ $madrasah->scod ?: '-' }}</td>
                            <td>
                                <div class="fw-semibold">{{ $madrasah->name }}</div>
                                <small class="text-muted">{{ $madrasah->kabupaten ?: 'Kabupaten belum diisi' }}</small>
                            </td>
                            @foreach(['jan_jun', 'jul_des'] as $periodKey)
                                <td class="uppm-period-cell">
                                    @forelse(($paymentsByPeriod->get($periodKey) ?? collect()) as $payment)
                                        <div class="uppm-entry">
                                            <div class="fw-semibold">{{ $payment->transfer_date?->format('d/m/Y') }}</div>
                                            <div>Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</div>
                                            @if($payment->note)
                                                <small>{{ $payment->note }}</small>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-muted">Belum ada pembayaran</span>
                                    @endforelse
                                </td>
                            @endforeach
                            <td>Rp {{ number_format((float) ($summary['total_paid'] ?? 0), 0, ',', '.') }}</td>
                            <td>
                                @if(!empty($summary['has_target']))
                                    Rp {{ number_format((float) ($summary['target_nominal'] ?? 0), 0, ',', '.') }}
                                @else
                                    <span class="text-muted">Belum ada nominal</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($summary['has_target']))
                                    Rp {{ number_format((float) ($summary['remaining'] ?? 0), 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php($status = $summary['status'] ?? 'belum_lunas')
                                <span class="badge {{ $status === 'lunas' ? 'bg-success' : ($status === 'sebagian' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $summary['status_label'] ?? 'Belum Lunas' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card uppm-panel">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="mb-1">Riwayat Input</h5>
                <p class="text-muted mb-0">Edit atau hapus input jika ada koreksi nominal, tanggal transfer, atau keterangan.</p>
            </div>
            <span class="badge bg-light text-dark">{{ number_format($paymentUpdates->count()) }} baris input</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tgl Transfer</th>
                        <th>Sekolah</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentUpdates as $paymentUpdate)
                        <tr>
                            <td>{{ $paymentUpdate->transfer_date?->format('d/m/Y') }}</td>
                            <td>{{ $paymentUpdate->madrasah?->scod ?: '-' }} - {{ $paymentUpdate->madrasah?->name }}</td>
                            <td>{{ $paymentUpdate->payment_period_label }}</td>
                            <td>Rp {{ number_format((float) $paymentUpdate->amount, 0, ',', '.') }}</td>
                            <td>{{ $paymentUpdate->note ?: '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPaymentUpdate{{ $paymentUpdate->id }}">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('uppm.update-uppm.destroy', $paymentUpdate) }}" onsubmit="return confirm('Hapus update pembayaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat input untuk tahun anggaran ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($paymentUpdates as $paymentUpdate)
    <div class="modal fade" id="editPaymentUpdate{{ $paymentUpdate->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('uppm.update-uppm.update', $paymentUpdate) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Update UPPM</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Sekolah</label>
                                <select name="madrasah_id" class="form-select" required>
                                    @foreach($madrasahs as $madrasah)
                                        <option value="{{ $madrasah->id }}" @selected((int) $paymentUpdate->madrasah_id === (int) $madrasah->id)>{{ $madrasah->scod ?: '-' }} - {{ $madrasah->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tahun Anggaran</label>
                                <input type="number" name="tahun_anggaran" class="form-control" value="{{ $paymentUpdate->tahun_anggaran }}" min="2020" max="{{ now()->year + 1 }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Periode</label>
                                <select name="payment_period" class="form-select" required>
                                    @foreach($periodOptions as $periodKey => $periodLabel)
                                        <option value="{{ $periodKey }}" @selected($paymentUpdate->payment_period === $periodKey)>{{ $periodLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tgl Transfer</label>
                                <input type="date" name="transfer_date" class="form-control" value="{{ optional($paymentUpdate->transfer_date)->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="amount" class="form-control" min="0" step="0.01" value="{{ (float) $paymentUpdate->amount }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="note" class="form-control" value="{{ $paymentUpdate->note }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
