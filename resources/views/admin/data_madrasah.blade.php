@extends('layouts.master')

@section('title', 'Kelengkapan Data Madrasah')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Kelengkapan Data Madrasah</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Madrasah</th>
                                <th>Alamat</th>
                                <th>Logo</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Map Link</th>
                                <th>Polygon (koordinat)</th>
                                <th>Hari KBM</th>
                                <th>Status Guru</th>
                                <th>Kelengkapan (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($madrasahs as $madrasah)
                            <tr>
                                <td>{{ $madrasah->name }}</td>
                                <td>{{ $madrasah->alamat ?? '-' }}</td>
                                <td>{{ $madrasah->logo ? 'Uploaded' : '-' }}</td>
                                <td>{{ $madrasah->latitude ?? '-' }}</td>
                                <td>{{ $madrasah->longitude ?? '-' }}</td>
                                <td>{{ $madrasah->map_link ? 'Defined' : '-' }}</td>
                                <td>{{ $madrasah->polygon_koordinat ? 'Defined' : '-' }}</td>
                                <td>{{ $madrasah->hari_kbm ?? '-' }}</td>
                                <td>{{ $madrasah->status_guru }}</td>
                                <td>{{ $madrasah->completeness_percentage }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
