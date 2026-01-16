@extends('layouts.master')

@section('title')Invoice UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Invoice UPPM - {{ $schoolData->madrasah->name }}</h4>
                <div class="card-tools">
                    <a href="{{ route('uppm.invoice.download', $schoolData->id) }}" class="btn btn-primary">
                        <i class="bx bx-download"></i> Download PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Identitas Sekolah</h5>
                        <p><strong>Nama Sekolah:</strong> {{ $schoolData->madrasah->name }}</p>
                        <p><strong>Alamat:</strong> {{ $schoolData->madrasah->address ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Identitas UPPM</h5>
                        <p><strong>Unit Pengembangan Pendidikan Ma'arif</strong></p>
                        <p><strong>Tahun Anggaran:</strong> {{ $schoolData->tahun_anggaran }}</p>
                        <p><strong>Jatuh Tempo:</strong> {{ $setting ? $setting->jatuh_tempo : '-' }}</p>
                    </div>
                </div>

                <hr>

                <h5>Rincian Perhitungan Iuran</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Komponen</th>
                            <th>Jumlah</th>
                            <th>Nominal per Bulan</th>
                            <th>Total per Tahun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Siswa</td>
                            <td>{{ number_format($schoolData->jumlah_siswa) }}</td>
                            <td>Rp {{ number_format($setting->nominal_siswa) }}</td>
                            <td>Rp {{ number_format($rincian['siswa']) }}</td>
                        </tr>
                        <tr>
                            <td>Guru Tetap</td>
                            <td>{{ number_format($schoolData->jumlah_guru_tetap) }}</td>
                            <td>Rp {{ number_format($setting->nominal_guru_tetap) }}</td>
                            <td>Rp {{ number_format($rincian['guru_tetap']) }}</td>
                        </tr>
                        <tr>
                            <td>Guru Tidak Tetap</td>
                            <td>{{ number_format($schoolData->jumlah_guru_tidak_tetap) }}</td>
                            <td>Rp {{ number_format($setting->nominal_guru_tidak_tetap) }}</td>
                            <td>Rp {{ number_format($rincian['guru_tidak_tetap']) }}</td>
                        </tr>
                        <tr>
                            <td>Guru PNS</td>
                            <td>{{ number_format($schoolData->jumlah_guru_pns) }}</td>
                            <td>Rp {{ number_format($setting->nominal_guru_pns) }}</td>
                            <td>Rp {{ number_format($rincian['guru_pns']) }}</td>
                        </tr>
                        <tr>
                            <td>Guru PPPK</td>
                            <td>{{ number_format($schoolData->jumlah_guru_pppk) }}</td>
                            <td>Rp {{ number_format($setting->nominal_guru_pppk) }}</td>
                            <td>Rp {{ number_format($rincian['guru_pppk']) }}</td>
                        </tr>
                        <tr>
                            <td>Karyawan Tetap</td>
                            <td>{{ number_format($schoolData->jumlah_karyawan_tetap) }}</td>
                            <td>Rp {{ number_format($setting->nominal_karyawan_tetap) }}</td>
                            <td>Rp {{ number_format($rincian['karyawan_tetap']) }}</td>
                        </tr>
                        <tr>
                            <td>Karyawan Tidak Tetap</td>
                            <td>{{ number_format($schoolData->jumlah_karyawan_tidak_tetap) }}</td>
                            <td>Rp {{ number_format($setting->nominal_karyawan_tidak_tetap) }}</td>
                            <td>Rp {{ number_format($rincian['karyawan_tidak_tetap']) }}</td>
                        </tr>
                        <tr class="table-primary">
                            <td colspan="3"><strong>Total Tagihan UPPM Tahunan</strong></td>
                            <td><strong>Rp {{ number_format($totalTahunan) }}</strong></td>
                        </tr>
                    </tbody>
                </table>

                @if($setting && $setting->catatan)
                <div class="mt-3">
                    <h6>Catatan:</h6>
                    <p>{{ $setting->catatan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif
</script>
@endsection
