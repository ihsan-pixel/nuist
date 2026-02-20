@extends('layouts.master')

@section('title', 'Kelengkapan Data Peserta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Kelengkapan Data Peserta</h4>
            <p class="text-muted">Halaman ini menampilkan status kelengkapan data peserta pada database Talenta. Gunakan filter untuk melihat peserta lengkap / belum lengkap.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($pesertas) && $pesertas->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:50px">No</th>
                                        <th>Kode Peserta</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Asal Sekolah / Madrasah</th>
                                        <th style="width:160px">Status Kelengkapan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesertas as $i => $peserta)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $peserta->kode_peserta }}</td>
                                        <td>{{ $peserta->nama ?? '-' }}</td>
                                        <td>{{ $peserta->email ?? '-' }}</td>
                                        <td>{{ $peserta->nama_madrasah ?? ($peserta->asal_sekolah ?? '-') }}</td>
                                        <td>
                                            @if($peserta->in_talenta)
                                                <span class="badge bg-success">Sudah Mengisi Data Talenta</span>
                                                @if(!empty($peserta->talenta->nomor_talenta))
                                                    <div class="small text-muted">No: {{ $peserta->talenta->nomor_talenta }}</div>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Belum Mengisi Data Talenta</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">Tidak ada data peserta Talenta.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
