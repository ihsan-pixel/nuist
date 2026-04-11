@extends('layouts.master')

@section('title')Laporan SPP Siswa @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SPP Siswa @endslot
    @slot('title') Laporan @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
            <div>
                <h4 class="mb-1">Laporan SPP Siswa</h4>
                <p class="text-muted mb-0">Ringkasan tagihan dan pembayaran per siswa serta distribusi siswa per kelas.</p>
            </div>
            <form method="GET" class="row g-2 align-items-end">
                @if($userRole !== 'admin')
                    <div class="col-md-12">
                        <label class="form-label">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            @foreach($madrasahOptions as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ (string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : '' }}>{{ $madrasah->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-12">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Ringkasan Kelas</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Kelas</th><th>Jumlah Siswa</th></tr></thead>
                        <tbody>
                        @forelse($classSummary as $row)
                            <tr><td>{{ $row->kelas ?: '-' }}</td><td>{{ number_format($row->jumlah_siswa) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">Belum ada data siswa.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Rekap Per Siswa</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Total Tagihan</th>
                                <th>Lunas</th>
                                <th>Belum Lunas</th>
                                <th>Total Nominal</th>
                                <th>Terbayar</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($reportRows as $row)
                            <tr>
                                <td><div class="fw-semibold">{{ $row->nama_lengkap }}</div><small class="text-muted">{{ $row->nis }}</small></td>
                                <td>{{ $row->kelas }}</td>
                                <td>{{ number_format($row->total_tagihan) }}</td>
                                <td>{{ number_format($row->tagihan_lunas) }}</td>
                                <td>{{ number_format($row->tagihan_belum_lunas) }}</td>
                                <td>Rp {{ number_format($row->total_nominal_tagihan ?? 0, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row->total_nominal_terbayar ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Belum ada data laporan SPP siswa.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $reportRows->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
