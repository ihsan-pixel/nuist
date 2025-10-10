@extends('layouts.master')

@section('title', 'Kelengkapan Data Madrasah')

@section('content')
@foreach($kabupatenOrder as $kabupaten)
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h4 class="card-title mb-0">Kabupaten: {{ $kabupaten }}</h4>
            </div>
            <div class="card-body">
                <table id="datatable-{{ Str::slug($kabupaten) }}" class="table table-bordered dt-responsive nowrap w-100">
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
                        @foreach($madrasahs[$kabupaten] ?? [] as $madrasah)
                        <tr>
                            <td>{{ $madrasah->name }}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['alamat'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['logo'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['latitude'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['longitude'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['map_link'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['polygon_koordinat'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['hari_kbm'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['status_guru'] ?? '❌' !!}</td>
                            <td style="font-weight: bold; text-align: center;">{{ $madrasah->completeness_percentage }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            responsive: true
        });
    });
</script>
@endpush

@endsection
