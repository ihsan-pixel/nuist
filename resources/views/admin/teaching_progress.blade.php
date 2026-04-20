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
                                <input type="hidden" name="month" value="{{ $month }}">
                                <input type="week" name="week" value="{{ $startOfWeek->format('o-\\WW') }}" class="form-control" onchange="this.form.submit()">
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
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Bulanan</h3>
                    <div class="card-tools">
                        <form method="GET" class="d-inline">
                            <div class="input-group input-group-sm">
                                <input type="hidden" name="week" value="{{ $startOfWeek->format('o-\\WW') }}">
                                <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()">
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
                                <th class="text-center align-middle" style="position: sticky; left: 0; background: #f8f9fa; z-index: 10;">SCOD</th>
                                <th class="text-center align-middle" style="position: sticky; left: 60px; background: #f8f9fa; z-index: 10;">Nama Sekolah / Madrasah</th>
                                <th class="text-center align-middle">Hari KBM</th>
                                <th class="text-center align-middle">Sudah</th>
                                <th class="text-center align-middle">Belum</th>
                                <th class="text-center align-middle">Total</th>
                                <th class="text-center align-middle">Total Hadir</th>
                                <th class="text-center align-middle">Total Alpha</th>
                                <th class="text-center align-middle">Persentase Kehadiran (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $monthlyGrandSudah = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('sudah'));
                                $monthlyGrandBelum = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('belum'));
                                $monthlyGrandTotal = collect($laporanBulananData)->sum(fn ($kabupaten) => collect($kabupaten['madrasahs'])->sum('total'));
                                $monthlyGrandHadir = collect($laporanBulananData)->sum('total_hadir');
                                $monthlyGrandAlpha = collect($laporanBulananData)->sum('total_alpha');
                                $monthlyGrandPresensi = collect($laporanBulananData)->sum('total_presensi');
                                $monthlyGrandPercentage = $monthlyGrandPresensi > 0
                                    ? ($monthlyGrandHadir / $monthlyGrandPresensi) * 100
                                    : 0;
                            @endphp
                            @foreach($laporanBulananData as $kabupaten)
                            <tr class="bg-info">
                                <td colspan="9" class="font-weight-bold text-center">{{ $kabupaten['kabupaten'] }} - {{ $startOfMonth->translatedFormat('F Y') }}</td>
                            </tr>
                            @foreach(collect($kabupaten['madrasahs'])->sortBy(function($madrasah) { return (int)$madrasah['scod']; }) as $madrasah)
                            <tr>
                                <td class="text-center" style="position: sticky; left: 0; background: white;">{{ $madrasah['scod'] }}</td>
                                <td style="position: sticky; left: 60px; background: white;">{{ $madrasah['nama'] }}</td>
                                <td class="text-center">{{ $madrasah['hari_kbm'] }}</td>
                                <td class="text-center">{{ $madrasah['sudah'] }}</td>
                                <td class="text-center">{{ $madrasah['belum'] }}</td>
                                <td class="text-center">{{ $madrasah['total'] }}</td>
                                <td class="text-center">{{ $madrasah['total_hadir'] }}</td>
                                <td class="text-center">{{ $madrasah['total_alpha'] }}</td>
                                <td class="text-center font-weight-bold">{{ number_format($madrasah['persentase_kehadiran'], 2) }}%</td>
                            </tr>
                            @endforeach
                            <tr class="bg-warning font-weight-bold">
                                <td colspan="3" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL {{ $kabupaten['kabupaten'] }}</td>
                                <td class="text-center">{{ collect($kabupaten['madrasahs'])->sum('sudah') }}</td>
                                <td class="text-center">{{ collect($kabupaten['madrasahs'])->sum('belum') }}</td>
                                <td class="text-center">{{ collect($kabupaten['madrasahs'])->sum('total') }}</td>
                                <td class="text-center">{{ $kabupaten['total_hadir'] }}</td>
                                <td class="text-center">{{ $kabupaten['total_alpha'] }}</td>
                                <td class="text-center">{{ number_format($kabupaten['persentase_kehadiran'], 2) }}%</td>
                            </tr>
                            @endforeach
                            <tr class="bg-warning font-weight-bold">
                                <td colspan="3" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL RATA-RATA BULANAN</td>
                                <td class="text-center">{{ $monthlyGrandSudah }}</td>
                                <td class="text-center">{{ $monthlyGrandBelum }}</td>
                                <td class="text-center">{{ $monthlyGrandTotal }}</td>
                                <td class="text-center">{{ $monthlyGrandHadir }}</td>
                                <td class="text-center">{{ $monthlyGrandAlpha }}</td>
                                <td class="text-center">{{ number_format($monthlyGrandPercentage, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div>
                            <h3 class="card-title mb-1">Rekap Presensi dan Jadwal Mengajar Tenaga Pendidik</h3>
                            <div class="text-muted small">
                                Periode {{ $teachingRecapData['label'] }}. Data khusus selain status GTT/GTY dan selain kepala sekolah.
                            </div>
                        </div>
                        <form method="GET" class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 mb-0">
                            <input type="hidden" name="week" value="{{ $startOfWeek->format('o-\\WW') }}">
                            <input type="hidden" name="month" value="{{ $month }}">

                            <select name="teaching_recap_period" id="teachingRecapPeriod" class="form-select form-select-sm" style="min-width: 130px;">
                                <option value="week" {{ $teachingRecapData['period'] === 'week' ? 'selected' : '' }}>Mingguan</option>
                                <option value="month" {{ $teachingRecapData['period'] === 'month' ? 'selected' : '' }}>Bulanan</option>
                            </select>

                            <input type="week"
                                name="teaching_recap_week"
                                id="teachingRecapWeek"
                                class="form-control form-control-sm"
                                value="{{ $teachingRecapData['week_value'] }}"
                                style="min-width: 150px;">

                            <input type="month"
                                name="teaching_recap_month"
                                id="teachingRecapMonth"
                                class="form-control form-control-sm"
                                value="{{ $teachingRecapData['month_value'] }}"
                                style="min-width: 150px;">

                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Total Tenaga Pendidik</div>
                                <div class="h4 mb-0">{{ number_format($teachingRecapData['summary']['total_tenaga_pendidik']) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Tidak Presensi Mengajar</div>
                                <div class="h4 mb-0 text-danger">{{ number_format($teachingRecapData['summary']['total_tidak_presensi']) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Sudah Punya Jadwal</div>
                                <div class="h4 mb-0 text-success">{{ number_format($teachingRecapData['summary']['total_sudah_jadwal']) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Belum Punya Jadwal</div>
                                <div class="h4 mb-0 text-warning">{{ number_format($teachingRecapData['summary']['total_belum_jadwal']) }}</div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Tenaga Pendidik Tidak Melakukan Presensi Mengajar</h5>
                    <div class="table-responsive teaching-recap-table mb-4">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">SCOD</th>
                                    <th>Nama User</th>
                                    <th>Asal Sekolah</th>
                                    <th>Status Kepegawaian</th>
                                    <th class="text-center">Jadwal Berjalan</th>
                                    <th class="text-center">Sudah Presensi</th>
                                    <th class="text-center">Tidak Presensi</th>
                                    <th class="text-center">% Tidak Presensi</th>
                                    <th>Rincian Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachingRecapData['absence_rows'] as $teacher)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $teacher['scod'] }}</td>
                                    <td>{{ $teacher['name'] }}</td>
                                    <td>{{ $teacher['madrasah'] }}</td>
                                    <td>{{ $teacher['status_kepegawaian'] }}</td>
                                    <td class="text-center">{{ $teacher['total_jadwal_berjalan'] }}</td>
                                    <td class="text-center">{{ $teacher['total_presensi'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $teacher['total_belum_presensi'] }}</span>
                                    </td>
                                    <td class="text-center">{{ number_format($teacher['persentase_tidak_presensi'], 1) }}%</td>
                                    <td class="small">{{ $teacher['rincian_tanggal'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block mb-0">
                                            <strong>Tidak ada tenaga pendidik yang belum presensi mengajar</strong><br>
                                            <small>Semua jadwal berjalan pada periode ini sudah memiliki presensi mengajar.</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mb-3">Rekap Tenaga Pendidik Sudah atau Belum Memiliki Jadwal Mengajar</h5>
                    <div class="table-responsive teaching-recap-table">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">SCOD</th>
                                    <th>Nama User</th>
                                    <th>Asal Sekolah</th>
                                    <th>Status Kepegawaian</th>
                                    <th class="text-center">Jadwal Master</th>
                                    <th class="text-center">Jadwal Periode</th>
                                    <th class="text-center">Status Jadwal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachingRecapData['schedule_rows'] as $teacher)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $teacher['scod'] }}</td>
                                    <td>{{ $teacher['name'] }}</td>
                                    <td>{{ $teacher['madrasah'] }}</td>
                                    <td>{{ $teacher['status_kepegawaian'] }}</td>
                                    <td class="text-center">{{ $teacher['jumlah_jadwal_master'] }}</td>
                                    <td class="text-center">{{ $teacher['total_jadwal_periode'] }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $teacher['jumlah_jadwal_master'] > 0 ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $teacher['status_jadwal'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block mb-0">
                                            Tidak ada tenaga pendidik sesuai kriteria filter ini.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
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

.teaching-recap-table {
    max-height: 520px;
}
</style>
@endpush

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const periodSelect = document.getElementById('teachingRecapPeriod');
    const weekInput = document.getElementById('teachingRecapWeek');
    const monthInput = document.getElementById('teachingRecapMonth');

    function toggleTeachingRecapFilters() {
        const period = periodSelect ? periodSelect.value : 'week';
        if (weekInput) {
            weekInput.style.display = period === 'week' ? '' : 'none';
        }
        if (monthInput) {
            monthInput.style.display = period === 'month' ? '' : 'none';
        }
    }

    toggleTeachingRecapFilters();
    if (periodSelect) {
        periodSelect.addEventListener('change', toggleTeachingRecapFilters);
    }
});
</script>
@endsection
