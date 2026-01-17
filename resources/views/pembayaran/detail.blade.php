@extends('layouts.master')

@section('title', 'Detail Pembayaran - ' . $madrasah->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title mb-0">
                                <i class="bx bx-money me-2"></i>Detail Pembayaran
                            </h4>
                            <p class="card-title-desc mb-0">
                                {{ $madrasah->name }} - Tahun {{ $tahun }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('uppm.pembayaran', ['tahun' => $tahun]) }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Invoice Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-receipt me-2"></i>Invoice Pembayaran
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Invoice Header -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Dari:</h6>
                                            <h5 class="mb-1">LP Ma'arif NU PWNU D.I. Yogyakarta</h5>
                                            <p class="text-muted mb-0">Jl. Kramat Raya No. 45<br>Jakarta Pusat 10450<br>Indonesia</p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <h6 class="text-muted">Kepada:</h6>
                                            <h5 class="mb-1">{{ $madrasah->name }}</h5>
                                            <p class="text-muted mb-0">{{ $madrasah->address ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <!-- Invoice Details -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Nomor Invoice:</strong></td>
                                                    <td>INV-{{ $madrasah->id }}-{{ $tahun }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Invoice:</strong></td>
                                                    <td>{{ date('d/m/Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Periode:</strong></td>
                                                    <td>Januari - Desember {{ $tahun }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Status Pembayaran:</strong></td>
                                                    <td>
                                                        <span class="badge badge-modern bg-warning">Belum Dibayar</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jatuh Tempo:</strong></td>
                                                    <td>{{ $setting ? ($setting->jatuh_tempo ? date('d/m/Y', strtotime($setting->jatuh_tempo)) : '-') : '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Invoice Table -->
                                    <div class="table-responsive">
                                        <h5 style="text-align: center; margin-bottom: 1rem;"><strong>RINCIAN PERHITUNGAN UPPM</strong></h5>
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
                                    </div>

                                    <!-- Invoice Footer -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="border-top pt-3">
                                                <p class="text-muted mb-0">
                                                    <small>
                                                        Pembayaran dapat dilakukan melalui transfer bank atau payment gateway yang tersedia.
                                                        Silakan pilih metode pembayaran di bawah ini.
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-credit-card me-2"></i>Metode Pembayaran
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Cash Payment -->
                                        <div class="col-md-6">
                                            <div class="card border-primary">
                                                <div class="card-body text-center">
                                                    <div class="mb-3">
                                                        <i class="bx bx-cash display-4 text-primary"></i>
                                                    </div>
                                                    <h5 class="card-title">Pembayaran Cash</h5>
                                                    <p class="card-text text-muted">Bayar langsung dengan uang tunai</p>

                                                    <form action="{{ route('uppm.pembayaran.cash') }}" method="POST" class="mt-4">
                                                        @csrf
                                                        <input type="hidden" name="madrasah_id" value="{{ $madrasah->id }}">
                                                        <input type="hidden" name="tahun" value="{{ $tahun }}">

                                                        <div class="mb-3">
                                                            <label for="nominal" class="form-label">Nominal Pembayaran</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">Rp</span>
                                                                <input type="number" class="form-control" id="nominal" name="nominal"
                                                                       placeholder="0" required min="0" step="1000">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="keterangan" class="form-label">Keterangan</label>
                                                            <textarea class="form-control" id="keterangan" name="keterangan"
                                                                      rows="3" placeholder="Catatan pembayaran (opsional)"></textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary btn-lg">
                                                            <i class="bx bx-check me-2"></i>Bayar Sekarang
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Online Payment (Midtrans) -->
                                        <div class="col-md-6">
                                            <div class="card border-success">
                                                <div class="card-body text-center">
                                                    <div class="mb-3">
                                                        <i class="bx bx-credit-card display-4 text-success"></i>
                                                    </div>
                                                    <h5 class="card-title">Pembayaran Online</h5>
                                                    <p class="card-text text-muted">Bayar melalui Midtrans Payment Gateway</p>

                                                    <div class="mt-4">
                                                        <div class="alert alert-info">
                                                            <i class="bx bx-info-circle me-2"></i>
                                                            <small>Integrasi dengan Midtrans sedang dalam pengembangan.</small>
                                                        </div>

                                                        <button type="button" class="btn btn-success btn-lg" disabled>
                                                            <i class="bx bx-credit-card me-2"></i>Bayar via Midtrans
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format number input with thousand separator
    const nominalInput = document.getElementById('nominal');
    nominalInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        e.target.value = value;
    });
});
</script>
@endsection
