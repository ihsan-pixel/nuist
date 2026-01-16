@extends('layouts.master')

@section('title')Perhitungan Iuran UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Perhitungan Iuran UPPM - Tahun {{ request('tahun', date('Y')) }}</h4>
                <div class="card-tools">
                    <form method="GET" class="d-inline">
                        <div class="input-group">
                            <select name="tahun" class="form-control" onchange="this.form.submit()">
                                @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Sekolah</th>
                                <th>Jumlah Siswa</th>
                                <th>Jumlah PNS Sertifikasi</th>
                                <th>Jumlah PNS Non Sertifikasi</th>
                                <th>Jumlah GTY Sertifikasi</th>
                                <th>Jumlah GTY Sertifikasi Inpassing</th>
                                <th>Jumlah GTY Non Sertifikasi</th>
                                <th>Jumlah GTT</th>
                                <th>Jumlah PTY</th>
                                <th>Jumlah PTT</th>
                                <th>Jumlah Karyawan Tetap</th>
                                <th>Jumlah Karyawan Tidak Tetap</th>
                                <th>Nominal Bulanan</th>
                                <th>Total Tahunan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perhitungan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['madrasah']->name }}</td>
                                <td>{{ number_format($item['data']->jumlah_siswa) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_tetap) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_tidak_tetap) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_pns) }}</td>
                                <td>{{ number_format($item['data']->jumlah_guru_pppk) }}</td>
                                <td>{{ number_format($item['data']->jumlah_karyawan_tetap) }}</td>
                                <td>{{ number_format($item['data']->jumlah_karyawan_tidak_tetap) }}</td>
                                <td>Rp {{ number_format($item['nominal_bulanan']) }}</td>
                                <td>Rp {{ number_format($item['total_tahunan']) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="17" class="text-center">Tidak ada data sekolah untuk tahun ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($perhitungan)
                <div class="mt-3">
                    <h5>Rincian Pengaturan Iuran Tahun {{ $tahun }}</h5>
                    <ul>
                        <li>Siswa: Rp {{ number_format($setting->nominal_siswa) }} per bulan</li>
                        <li>PNS Sertifikasi: Rp {{ number_format($setting->nominal_pns_sertifikasi) }} per bulan</li>
                        <li>PNS Non Sertifikasi: Rp {{ number_format($setting->nominal_pns_non_sertifikasi) }} per bulan</li>
                        <li>GTY Sertifikasi: Rp {{ number_format($setting->nominal_gty_sertifikasi) }} per bulan</li>
                        <li>GTY Sertifikasi Inpassing: Rp {{ number_format($setting->nominal_gty_sertifikasi_inpassing) }} per bulan</li>
                        <li>GTY Non Sertifikasi: Rp {{ number_format($setting->nominal_gty_non_sertifikasi) }} per bulan</li>
                        <li>GTT: Rp {{ number_format($setting->nominal_gtt) }} per bulan</li>
                        <li>PTY: Rp {{ number_format($setting->nominal_pty) }} per bulan</li>
                        <li>PTT: Rp {{ number_format($setting->nominal_ptt) }} per bulan</li>
                        <li>Karyawan Tetap: Rp {{ number_format($setting->nominal_karyawan_tetap) }} per bulan</li>
                        <li>Karyawan Tidak Tetap: Rp {{ number_format($setting->nominal_karyawan_tidak_tetap) }} per bulan</li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
