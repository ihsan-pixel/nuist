@extends('layouts.master')

@section('title')Tagihan UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tagihan UPPM - Tahun {{ request('tahun', date('Y')) }}</h4>
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
                                <th>Tahun Anggaran</th>
                                <th>Total Tagihan UPPM</th>
                                <th>Status Pembayaran</th>
                                <th>Tanggal Jatuh Tempo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->madrasah->name }}</td>
                                <td>{{ $item->tahun_anggaran }}</td>
                                <td>Rp {{ number_format($item->total_nominal) }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status_pembayaran)) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $setting = \App\Models\UppmSetting::where('tahun_anggaran', $item->tahun_anggaran)->where('aktif', true)->first();
                                        $jatuhTempo = $setting ? $setting->jatuh_tempo : '-';
                                    @endphp
                                    {{ $jatuhTempo }}
                                </td>
                                <td>
                                    <a href="{{ route('uppm.invoice', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-receipt"></i> Lihat Invoice
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data tagihan untuk tahun ini</td>
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
