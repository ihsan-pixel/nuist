@extends('layouts.app')

@section('title', 'Laporan Presensi Mingguan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Presensi Mingguan</h3>
                    <div class="card-tools">
                        <form method="GET" class="d-inline">
                            <div class="input-group input-group-sm">
                                <input type="week"
                                       name="week"
                                       value="{{ $startOfWeek->format('Y-\WW') }}"
                                       class="form-control"
                                       onchange="this.form.submit()">
                            </div>
                        </form>

                        <a href="{{ route('presensi_admin.laporan_mingguan', [
                            'week' => $startOfWeek->format('Y-\WW'),
                            'export' => 'excel'
                        ]) }}"
                           class="btn btn-success btn-sm ml-2">
                            <i class="fas fa-download"></i> Export Excel
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2">SCOD</th>
                                <th rowspan="2">Nama Sekolah / Madrasah</th>
                                <th rowspan="2">Hari KBM</th>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                                    <th colspan="3" class="text-center">{{ $hari }}</th>
                                @endforeach
                                <th rowspan="2">Persentase (%)</th>
                            </tr>
                            <tr>
                                @for($i=0;$i<6;$i++)
                                    <th>Hadir</th>
                                    <th>Izin</th>
                                    <th>Alpha</th>
                                @endfor
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($laporanData as $kabupaten)
                            <tr class="bg-info">
                                <td colspan="21" class="text-center font-weight-bold">
                                    {{ $kabupaten['kabupaten'] }}
                                </td>
                            </tr>

                            @foreach($kabupaten['madrasahs'] as $madrasah)
                                <tr>
                                    <td>{{ $madrasah['scod'] }}</td>
                                    <td>{{ $madrasah['nama'] }}</td>
                                    <td class="text-center">{{ $madrasah['hari_kbm'] }}</td>

                                    @foreach($madrasah['presensi'] as $p)
                                        <td class="text-center">{{ $p['hadir'] }}</td>
                                        <td class="text-center">{{ $p['izin'] }}</td>
                                        <td class="text-center">{{ $p['alpha'] }}</td>
                                    @endforeach

                                    <td class="text-center font-weight-bold">
                                        {{ number_format($madrasah['persentase_kehadiran'], 2) }}%
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table-responsive {
    max-height: 70vh;
    overflow-y: auto;
}
.bg-info { background: #d1ecf1; }
</style>
@endpush
