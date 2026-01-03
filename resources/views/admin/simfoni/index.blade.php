@extends('layouts.master')

@section('title', 'Data Simfoni')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Simfoni Tenaga Pendidik</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Strata Pendidikan</th>
                                <th>Status Kerja</th>
                                <th>Gaji Pokok</th>
                                <th>Total Penghasilan</th>
                                <th>Skor Proyeksi</th>
                                <th>Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($simfonis as $index => $simfoni)
                            <tr>
                                <td>{{ $simfonis->firstItem() + $index }}</td>
                                <td>{{ $simfoni->nama_lengkap_gelar }}</td>
                                <td>{{ $simfoni->email }}</td>
                                <td>{{ $simfoni->user->madrasah->name ?? '-' }}</td>
                                <td>{{ $simfoni->strata_pendidikan }}</td>
                                <td>{{ $simfoni->status_kerja }}</td>
                                <td>{{ number_format($simfoni->gaji_pokok, 0, ',', '.') }}</td>
                                <td>{{ number_format($simfoni->total_penghasilan, 0, ',', '.') }}</td>
                                <td>{{ $simfoni->skor_proyeksi }}</td>
                                <td>{{ $simfoni->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data Simfoni.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $simfonis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
