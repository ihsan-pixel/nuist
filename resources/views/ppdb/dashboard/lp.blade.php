@extends('layouts.master-without-nav')

@section('title', 'Dashboard LP. Ma’arif NUIST')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Monitoring PPDB Seluruh Madrasah</h4>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-3 border-0 bg-light">
                <h1>{{ $totalSekolah }}</h1>
                <p class="text-muted mb-0">Sekolah Terdaftar</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3 border-0 bg-light">
                <h1>{{ $totalPendaftar }}</h1>
                <p class="text-muted mb-0">Total Pendaftar</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3 border-0 bg-light">
                <h1>{{ $totalBuka }}</h1>
                <p class="text-muted mb-0">Sekolah Buka PPDB</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Daftar Sekolah & Jumlah Pendaftar</h5>
            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        <th>Nama Sekolah</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Pendaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listSekolah as $s)
                    <tr>
                        <td>{{ $s->nama_sekolah }}</td>
                        <td>{{ $s->tahun }}</td>
                        <td>
                            <span class="badge bg-{{ $s->status == 'buka' ? 'success' : 'secondary' }}">{{ ucfirst($s->status) }}</span>
                        </td>
                        <td>{{ $s->pendaftar_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
