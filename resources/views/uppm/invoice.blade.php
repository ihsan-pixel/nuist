@extends('layouts.master')

@section('title')Invoice UPPM @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Invoice UPPM - {{ $madrasah->name }}</h4>
                <div class="card-tools">
                    <a href="{{ route('uppm.invoice.download', ['madrasah_id' => $madrasah->id, 'tahun' => $tahun]) }}" class="btn btn-primary">
                        <i class="bx bx-download"></i> Download PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Identitas Sekolah</h5>
                        <p><strong>Nama Sekolah:</strong> {{ $madrasah->name }}</p>
                        <p><strong>Alamat:</strong> {{ $madrasah->address ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Identitas UPPM</h5>
                        <p><strong>Unit Pengembangan Pendidikan Ma'arif</strong></p>
                        <p><strong>Tahun Anggaran:</strong> {{ $tahun }}</p>
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
                            <td>{{ number_format($dataSekolah->jumlah_siswa ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_siswa ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['siswa'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>PNS Sertifikasi</td>
                            <td>{{ number_format($dataSekolah->jumlah_pns_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_pns_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['pns_sertifikasi'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>PNS Non Sertifikasi</td>
                            <td>{{ number_format($dataSekolah->jumlah_pns_non_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_pns_non_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['pns_non_sertifikasi'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>GTY Sertifikasi</td>
                            <td>{{ number_format($dataSekolah->jumlah_gty_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_gty_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['gty_sertifikasi'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>GTY Sertifikasi Inpassing</td>
                            <td>{{ number_format($dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_gty_sertifikasi_inpassing ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['gty_sertifikasi_inpassing'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>GTY Non Sertifikasi</td>
                            <td>{{ number_format($dataSekolah->jumlah_gty_non_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_gty_non_sertifikasi ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['gty_non_sertifikasi'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>GTT</td>
                            <td>{{ number_format($dataSekolah->jumlah_gtt ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_gtt ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['gtt'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>PTY</td>
                            <td>{{ number_format($dataSekolah->jumlah_pty ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_pty ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['pty'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>PTT</td>
                            <td>{{ number_format($dataSekolah->jumlah_ptt ?? 0) }}</td>
                            <td>Rp {{ number_format($setting->nominal_ptt ?? 0) }}</td>
                            <td>Rp {{ number_format($rincian['ptt'] ?? 0) }}</td>
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
