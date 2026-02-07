@extends('layouts.master')

@section('title', 'Kelengkapan Data Madrasah')

@section('content')
@foreach($kabupatenOrder as $kabupaten)
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-white">Kabupaten: {{ $kabupaten }}</h4>
                <a href="{{ route('admin.data_madrasah.export', ['kabupaten' => $kabupaten]) }}" class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
            <div class="card-body">
                <table id="datatable-{{ Str::slug($kabupaten) }}" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>SCOD</th>
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
                            <td>{{ $madrasah->scod }}</td>
                            <td>{{ $madrasah->name }}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['alamat'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['logo'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['latitude'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['longitude'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">{!! $madrasah->field_status['map_link'] ?? '❌' !!}</td>
                            <td style="font-size: 20px; text-align: center;">
                                {!! $madrasah->field_status['polygon_koordinat'] ?? '❌' !!}
                                @if($madrasah->enable_dual_polygon && $madrasah->field_status['polygon_koordinat_2'] === '✅')
                                    <br><small class="text-success">+ Dual</small>
                                @endif
                            </td>
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

<!-- Table for Jumlah Tenaga Pendidik -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">Jumlah Tenaga Pendidik</h4>
            </div>
            <div class="card-body">
                @foreach($kabupatenOrder as $kabupaten)
                <h5 class="mt-4 mb-3">{{ $kabupaten }}</h5>
                <table id="datatable-tenaga-{{ Str::slug($kabupaten) }}" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="text-center align-middle">SCOD</th>
                            <th rowspan="2" class="text-center align-middle">Nama Sekolah/Madrasah</th>
                            <th colspan="10" class="text-center">Jumlah Tenaga Pendidik</th>
                        </tr>
                        <tr>
                            <th class="text-center">PNS Sertifikasi</th>
                            <th class="text-center">PNS Non Sertifikasi</th>
                            <th class="text-center">GTY Sertifikasi Inpassing</th>
                            <th class="text-center">GTY Sertifikasi</th>
                            <th class="text-center">GTY</th>
                            <th class="text-center">GTT</th>
                            <th class="text-center">PTY</th>
                            <th class="text-center">PTT</th>
                            <th class="text-center">Tidak Diketahui</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenagaPendidikData[$kabupaten] ?? [] as $data)
                        <tr>
                            <td class="text-center">{{ $data['scod'] }}</td>
                            <td>{{ $data['name'] }}</td>
                            <td class="text-center">{{ $data['pns_sertifikasi'] }}</td>
                            <td class="text-center">{{ $data['pns_non_sertifikasi'] }}</td>
                            <td class="text-center">{{ $data['gty_sertifikasi_inpassing'] }}</td>
                            <td class="text-center">{{ $data['gty_sertifikasi'] }}</td>
                            <td class="text-center">{{ $data['gty'] }}</td>
                            <td class="text-center">{{ $data['gtt'] }}</td>
                            <td class="text-center">{{ $data['pty'] }}</td>
                            <td class="text-center">{{ $data['ptt'] }}</td>
                            <td class="text-center">{{ $data['tidak_diketahui'] }}</td>
                            <td class="text-center font-weight-bold">{{ $data['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        @foreach($kabupatenOrder as $kabupaten)
        $('#datatable-{{ Str::slug($kabupaten) }}').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        @endforeach
    });
</script>
@endpush

@endsection
