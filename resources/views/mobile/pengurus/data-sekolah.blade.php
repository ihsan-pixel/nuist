@extends('layouts.mobile')

@section('title', 'Data Sekolah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Data Sekolah</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Madrasah</th>
                                    <th>Alamat</th>
                                    <th>Kecamatan</th>
                                    <th>Kabupaten</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($madrasahs as $madrasah)
                                <tr>
                                    <td>{{ $madrasah->name }}</td>
                                    <td>{{ $madrasah->alamat ?? '-' }}</td>
                                    <td>{{ $madrasah->kecamatan ?? '-' }}</td>
                                    <td>{{ $madrasah->kabupaten ?? '-' }}</td>
                                    <td>
                                        @if($madrasah->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data sekolah.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $madrasahs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
