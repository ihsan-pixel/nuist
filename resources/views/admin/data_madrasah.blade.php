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
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
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
                        @foreach($madrasahs as $index => $madrasah)
                        <tr>
                            <td>{{ $index + 1 }}</td>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush

@endsection
