@extends('layouts.master')

@section('title', 'Pembayaran UPPM - Tahun ' . $tahun)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="bx bx-money me-2"></i>Pembayaran
                            </h4>
                            <p class="card-title-desc mb-0">
                                Kelola pembayaran iuran UPPM untuk tahun {{ $tahun }}
                            </p>
                        </div>
                        <div>
                            <form method="GET" class="d-inline">
                                <div class="input-group">
                                    <select name="tahun" class="form-select" onchange="this.form.submit()">
                                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success rounded">
                                                <i class="bx bx-check-circle font-size-20"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-1">Lunas</p>
                                            <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'lunas')->count() }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning rounded">
                                                <i class="bx bx-time font-size-20"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-1">Sebagian</p>
                                            <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'sebagian')->count() }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-danger rounded">
                                                <i class="bx bx-x-circle font-size-20"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-1">Belum Bayar</p>
                                            <h5 class="mb-0">{{ collect($data)->where('status_pembayaran', 'belum_lunas')->count() }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info rounded">
                                                <i class="bx bx-money font-size-20"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-1">Total Tagihan</p>
                                            <h5 class="mb-0">Rp {{ number_format(collect($data)->sum('total_nominal'), 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
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
                                        <a href="{{ route('uppm.pembayaran.detail', ['madrasah_id' => $item->madrasah->id, 'tahun' => $tahun]) }}"
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
    </div>
</div>
@endsection
