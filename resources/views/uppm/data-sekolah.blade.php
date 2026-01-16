@extends('layouts.master')

@section('title')Data Sekolah UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Sekolah UPPM - Tahun {{ request('tahun', date('Y')) }}</h4>
                <div class="card-tools">
                    <form method="GET" class="d-inline">
                        <div class="input-group">
                            <select name="tahun" class="form-control" onchange="this.form.submit()">
                                @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
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
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->madrasah->name }}</td>
                                <td>{{ number_format($item->jumlah_siswa) }}</td>
                                <td>{{ number_format($item->jumlah_pns_sertifikasi ?? 0) }}</td>
                                <td>{{ number_format($item->jumlah_pns_non_sertifikasi ?? 0) }}</td>
                                <td>{{ number_format($item->jumlah_gty_sertifikasi ?? 0) }}</td>
                                <td>{{ number_format($item->jumlah_gty_sertifikasi_inpassing ?? 0) }}</td>
                                <td>{{ number_format($item->jumlah_gty_non_sertifikasi ?? 0) }}</td>
                                <td>{{ number_format($item->jumlah_gtt ?? 0) }}</td>
                                <td>{{ number_format($item->jumlah_pty ?? 0) }}</td>
                                <td>{{ number_format($item->jumlah_ptt ?? 0) }}</td>
                                <td>Rp {{ number_format($item->total_nominal) }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status_pembayaran)) }}
                                    </span>
                                </td>
                                <td>
                                    @if(isset($item->id))
                                        <a href="{{ route('uppm.invoice', $item->id) }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-receipt"></i> Invoice
                                        </a>
                                    @else
                                        <span class="text-muted">Belum ada data</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="16" class="text-center">Tidak ada data sekolah untuk tahun ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
