@extends('layouts.master')

@section('title', 'Teaching Progress')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Teaching Progress</h3>
                    <div class="card-tools">
                        <form method="GET" class="d-inline">
                            <div class="input-group input-group-sm">
                                <input type="week" name="week" value="{{ $startOfWeek->format('Y-W') }}" class="form-control" onchange="this.form.submit()">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 0; background: #f8f9fa; z-index: 10;">SCOD</th>
                                <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 60px; background: #f8f9fa; z-index: 10;">Nama Sekolah / Madrasah</th>
                                <th rowspan="2" class="text-center align-middle">Hari KBM</th>
                                <th colspan="3" class="text-center">Jumlah Tenaga Pendidik</th>
                                <th colspan="2" class="text-center">Senin</th>
                                <th colspan="2" class="text-center">Selasa</th>
                                <th colspan="2" class="text-center">Rabu</th>
                                <th colspan="2" class="text-center">Kamis</th>
                                <th colspan="2" class="text-center">Jumat</th>
                                <th colspan="2" class="text-center">Sabtu</th>
                                <th rowspan="2" class="text-center align-middle">Persentase Kehadiran (%)</th>
                            </tr>
                            <tr>
                                <th class="text-center">Sudah</th>
                                <th class="text-center">Belum</th>
                                <th class="text-center">Total</th>
                                @for($i = 0; $i < 6; $i++)
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Alpha</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporanData as $kabupaten)
                            <tr class="bg-info">
                                <td colspan="22" class="font-weight-bold text-center">{{ $kabupaten['kabupaten'] }}</td>
                            </tr>
                            @foreach(collect($kabupaten['madrasahs'])->sortBy(function($madrasah) { return (int)$madrasah['scod']; }) as $madrasah)
                            <tr>
                                <td class="text-center" style="position: sticky; left: 0; background: white;">{{ $madrasah['scod'] }}</td>
                                <td style="position: sticky; left: 60px; background: white;">{{ $madrasah['nama'] }}</td>
                                <td class="text-center">{{ $madrasah['hari_kbm'] }}</td>
                                <td class="text-center">{{ $madrasah['sudah'] }}</td>
                                <td class="text-center">{{ $madrasah['belum'] }}</td>
                                <td class="text-center">{{ $madrasah['total'] }}</td>
                                @foreach($madrasah['presensi'] as $presensi)
                                <td class="text-center">{{ $presensi['hadir'] }}</td>
                                <td class="text-center">{{ $presensi['alpha'] }}</td>
                                @endforeach
                                <td class="text-center font-weight-bold">{{ number_format($madrasah['persentase_kehadiran'], 2) }}%</td>
                            </tr>
                            @endforeach
                            <tr class="bg-warning font-weight-bold">
                                <td colspan="3" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL {{ $kabupaten['kabupaten'] }}</td>
                                <td class="text-center">{{ collect($kabupaten['madrasahs'])->sum('sudah') }}</td>
                                <td class="text-center">{{ collect($kabupaten['madrasahs'])->sum('belum') }}</td>
                                <td class="text-center">{{ collect($kabupaten['madrasahs'])->sum('total') }}</td>
                                @for($i = 0; $i < 6; $i++)
                                <td class="text-center">{{ $kabupaten['total_hadir'] }}</td>
                                <td class="text-center">{{ $kabupaten['total_alpha'] }}</td>
                                @endfor
                                <td class="text-center">{{ number_format($kabupaten['persentase_kehadiran'], 2) }}%</td>
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

@push('styles')
<style>
.table-responsive {
    max-height: 70vh;
    overflow-y: auto;
}

.table thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 5;
}

.table thead th[rowspan="2"] {
    z-index: 10;
}

.table tbody tr:hover {
    background-color: #f5f5f5;
}

.bg-info {
    background-color: #d1ecf1 !important;
}

.bg-warning {
    background-color: #fff3cd !important;
}
</style>
@endpush
