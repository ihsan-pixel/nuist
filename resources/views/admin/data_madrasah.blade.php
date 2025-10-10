@extends('layouts.master')

@section('title', 'Kelengkapan Data Madrasah')

@section('content')
@foreach($kabupatenOrder as $kabupaten)
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-white">Kabupaten: {{ $kabupaten }}</h4>
                <button class="btn btn-success btn-sm" onclick="exportTableToExcel('datatable-{{ Str::slug($kabupaten) }}', 'Kelengkapan_Data_{{ Str::slug($kabupaten) }}')">
                    Export Excel
                </button>
            </div>
            <div class="card-body">
                <table id="datatable-{{ Str::slug($kabupaten) }}" class="table table-bordered dt-responsive nowrap w-100">
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
                        @foreach($madrasahs[$kabupaten] ?? [] as $index => $madrasah)
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
@endforeach

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename = ''){
        var tableSelect = document.getElementById(tableID);
        if(!tableSelect){
            alert('Table not found: ' + tableID);
            return;
        }
        var workbook = XLSX.utils.book_new();
        var worksheet = XLSX.utils.table_to_sheet(tableSelect);
        XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
        XLSX.writeFile(workbook, filename ? filename + ".xlsx" : "excel_data.xlsx");
    }

    $(document).ready(function() {
        @foreach($kabupatenOrder as $kabupaten)
        $('#datatable-{{ Str::slug($kabupaten) }}').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        @endforeach
    });
</script>
@endpush

@endsection
