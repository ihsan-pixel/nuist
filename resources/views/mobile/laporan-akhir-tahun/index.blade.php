@extends('layouts.mobile')

@section('title', 'Laporan Akhir Tahun')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Laporan Akhir Tahun</h4>
                </div>
                <div class="card-body">
                    <!-- Development Notice -->
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Fitur Dalam Pengembangan</strong> - Laporan akhir tahun sedang dalam tahap pengembangan. Anda dapat melihat daftar laporan yang ada, namun belum dapat membuat atau mengedit laporan baru.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-muted mb-0">Daftar laporan akhir tahun yang telah Anda buat</p>
                        {{-- <a href="{{ route('mobile.laporan-akhir-tahun.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat Laporan Baru
                        </a> --}}
                    </div>

                    @if($laporans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Nama Madrasah</th>
                                        <th>Status</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporans as $laporan)
                                        <tr>
                                            <td>{{ $laporan->tahun_pelaporan }}</td>
                                            <td>{{ $laporan->nama_madrasah }}</td>
                                            <td>
                                                <span class="badge bg-{{ $laporan->status === 'submitted' ? 'success' : ($laporan->status === 'approved' ? 'primary' : ($laporan->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                                    {{ ucfirst($laporan->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $laporan->tanggal_laporan ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('mobile.laporan-akhir-tahun.show', $laporan->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('mobile.laporan-akhir-tahun.edit', $laporan->id) }}" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada laporan</h5>
                            <p class="text-muted">Anda belum membuat laporan akhir tahun. Klik tombol di atas untuk membuat laporan baru.</p>
                            <a href="{{ route('mobile.laporan-akhir-tahun.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Laporan Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
