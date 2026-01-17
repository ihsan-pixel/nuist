@extends('layouts.master')

@section('title')Data Sekolah UPPM @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('li_2') UPPM @endslot
    @slot('title') Data Sekolah @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-school me-2"></i>
                    Data Sekolah UPPM
                </h4>
                <p class="text-white-50 mb-0">
                    Kelola dan pantau data sekolah beserta informasi pembayaran iuran UPPM untuk tahun {{ request('tahun', date('Y')) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-school"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Sekolah</p>
                        <h5 class="mb-0">{{ count($data) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
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

    <div class="col-xl-2 col-md-4 col-sm-6">
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

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bx bx-x-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Belum Bayar</p>
                        <h5 class="mb-0">{{ $data->where('status_pembayaran', 'belum')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-group"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Siswa</p>
                        <h5 class="mb-0">{{ collect($data)->sum('jumlah_siswa') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Nominal</p>
                        <h5 class="mb-0">Rp {{ number_format(collect($data)->sum('total_nominal')) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('uppm.data-sekolah') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="tahun" class="form-label">Tahun Anggaran</label>
                            <select class="form-select" id="tahun" name="tahun">
                                @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status Pembayaran</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('uppm.data-sekolah') }}" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(count($data) > 0)
                    <div class="table-responsive">
                        <table class="table-modern table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sekolah</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Jumlah PNS Sertifikasi</th>
                                    <th>Jumlah PNS Non Sertifikasi</th>
                                    <th>Jumlah GTY Sertifikasi</th>
                                    <th>Jumlah GTY Sertifikasi Inpassing</th>
                                    <th>Jumlah GTY Non Sertifikasi</th>
                                    <th>Jumlah GTT</th>
                                    <th>Jumlah PTY</th>
                                    <th>Jumlah PTT</th>
                                    <th>Total Nominal UPPM per Tahun</th>
                                    <th>Status Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="card-icon school me-3">
                                                <i class="bx bx-school"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $item->madrasah->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->jumlah_siswa) }}</td>
                                    <td>{{ number_format($item->jumlah_pns_sertifikasi ?? 0) }}</td>
                                    <td>{{ number_format($item->jumlah_pns_non_sertifikasi ?? 0) }}</td>
                                    <td>{{ number_format($item->jumlah_gty_sertifikasi ?? 0) }}</td>
                                    <td>{{ number_format($item->jumlah_gty_sertifikasi_inpassing ?? 0) }}</td>
                                    <td>{{ number_format($item->jumlah_gty_non_sertifikasi ?? 0) }}</td>
                                    <td>{{ number_format($item->jumlah_gtt ?? 0) }}</td>
                                    <td>{{ number_format($item->jumlah_pty ?? 0) }}</td>
                                    <td>{{ number_format($item->jumlah_ptt ?? 0) }}</td>
                                    <td>
                                        <strong class="text-success">Rp {{ number_format($item->total_nominal) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status_pembayaran)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(isset($item->id))
                                            <a href="{{ route('uppm.invoice', $item->id) }}" class="btn-modern btn-sm">
                                                <i class="bx bx-receipt me-1"></i> Invoice
                                            </a>
                                        @else
                                            <span class="text-muted">Belum ada data</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bx bx-school"></i>
                        </div>
                        <h5>Tidak Ada Data Sekolah</h5>
                        <p class="text-muted">Belum ada data sekolah untuk tahun {{ request('tahun', date('Y')) }}.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif
</script>
@endsection
