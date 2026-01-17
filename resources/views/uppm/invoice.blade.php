@extends('layouts.master')

@section('title')Invoice UPPM @endsection

@section('css')
<style>
/* Modern Card Grid Design */
.setting-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.setting-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.setting-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.setting-card.active::before { background: linear-gradient(90deg, #28a745, #20c997); }
.setting-card.inactive::before { background: linear-gradient(90deg, #6c757d, #495057); }

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.card-icon.active { background: linear-gradient(135deg, #28a745, #20c997); }
.card-icon.inactive { background: linear-gradient(135deg, #6c757d, #495057); }

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.card-date {
    color: #718096;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.badge-modern {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-modern.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
.badge-modern.bg-success { background: linear-gradient(135deg, #48bb78, #38a169) !important; }
.badge-modern.bg-info { background: linear-gradient(135deg, #4299e1, #3182ce) !important; }
.badge-modern.bg-warning { background: linear-gradient(135deg, #ed8936, #dd6b20) !important; }
.badge-modern.bg-secondary { background: linear-gradient(135deg, #a0aec0, #718096) !important; }

.card-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 15px;
}

.card-details {
    background: #f7fafc;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #667eea;
    margin-bottom: 15px;
}

.card-details small {
    color: #718096;
    font-size: 0.8rem;
}

.nominal-info {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
}

.nominal-info .nominal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.nominal-info .nominal-item:last-child {
    margin-bottom: 0;
}

.nominal-info .nominal-label {
    font-size: 0.8rem;
    opacity: 0.9;
}

.nominal-info .nominal-value {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Statistics Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Action Buttons */
.action-buttons {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 8px 30px rgba(72, 187, 120, 0.3);
}

.action-buttons h5 {
    color: white;
    margin-bottom: 1rem;
}

.btn-group-custom .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-group-custom .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(5px);
}

.loading-content {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .btn-group-custom .btn {
        width: 100%;
        margin-right: 0;
    }

    .stats-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .card-description {
        font-size: 0.9rem;
    }

    .card-title {
        font-size: 1.1rem;
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 15px;
    border: 2px dashed #cbd5e0;
}

.empty-state .empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

/* Modern Form Select Styling */
.form-select {
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 0.95rem;
    font-weight: 500;
    color: #2d3748;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
    transform: translateY(-1px);
}

.form-select:hover {
    border-color: #cbd5e0;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filter Card Modern Styling */
.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.card-body {
    padding: 2rem;
}

/* Invoice Specific Styles */
.invoice-header-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(72, 187, 120, 0.3);
    margin-bottom: 2rem;
}

.invoice-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border-left: 4px solid #667eea;
}

.detail-card h6 {
    color: #667eea;
    margin-bottom: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.invoice-table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
}

.invoice-table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.invoice-table td {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    transition: background-color 0.2s;
}

.invoice-table tbody tr:hover {
    background-color: #f8f9fa;
}

.invoice-table .text-center {
    text-align: center;
}

.invoice-table .text-right {
    text-align: right;
}

.total-row {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white;
    font-weight: 600;
}

.total-row td {
    padding: 1.2rem 1rem;
}

.invoice-footer-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    color: #6c757d;
    border: 1px solid #e9ecef;
}

.download-section {
    text-align: center;
    margin-bottom: 2rem;
}

.download-btn {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    color: white;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
    color: white;
    text-decoration: none;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('li_2') UPPM @endslot
    @slot('title') Invoice Sekolah @endslot
@endcomponent

<!-- Header Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card invoice-header-card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title text-white mb-2">
                            <i class="bx bx-receipt"></i>
                            Invoice UPPM
                        </h4>
                        <p class="text-white-50 mb-0">
                            Rincian tagihan iuran UPPM untuk {{ $madrasah->name }} tahun {{ $tahun }}
                        </p>
                    </div>
                    <div class="text-end">
                        <div class="download-section">
                            <a href="{{ route('uppm.invoice.download', ['madrasah_id' => $madrasah->id, 'tahun' => $tahun]) }}" class="download-btn">
                                <i class="bx bx-download"></i>
                                <span>Download PDF</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Details -->
<div class="row mb-4">
    <div class="col-12">
        <div class="invoice-details-grid">
            <div class="detail-card">
                <h6>Informasi Sekolah</h6>
                <p><strong>Nama Sekolah:</strong> {{ $madrasah->name }}</p>
                <p><strong>Alamat:</strong> {{ $madrasah->address ?? 'Alamat tidak tersedia' }}</p>
            </div>

            <div class="detail-card">
                <h6>Detail Invoice</h6>
                <p><strong>Nomor Invoice:</strong> UPPM/{{ $madrasah->id }}/{{ $tahun }}</p>
                <p><strong>Tahun Anggaran:</strong> {{ $tahun }}</p>
                <p><strong>Tanggal Terbit:</strong> {{ date('d F Y') }}</p>
                <p><strong>Jatuh Tempo:</strong> {{ $setting ? date('d F Y', strtotime($setting->jatuh_tempo)) : 'Tidak ditentukan' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="invoice-table-container">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Komponen</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-right">Nominal per Bulan</th>
                        <th class="text-right">Total per Tahun</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Siswa</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_siswa ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_siswa ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['siswa'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PNS Sertifikasi</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_pns_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_pns_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['pns_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PNS Non Sertifikasi</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_pns_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_pns_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['pns_non_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTY Sertifikasi</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gty_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gty_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gty_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTY Sertifikasi Inpassing</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gty_sertifikasi_inpassing ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gty_sertifikasi_inpassing'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTY Non Sertifikasi</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gty_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gty_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gty_non_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTT</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gtt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gtt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gtt'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PTY</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_pty ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_pty ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['pty'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PTT</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_ptt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_ptt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['ptt'] ?? 0) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Total Tagihan UPPM Tahunan</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($totalTahunan) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer Information -->
<div class="row">
    <div class="col-12">
        <div class="invoice-footer-card">
            <p><strong>Syarat Pembayaran:</strong> {{ $setting ? ($setting->skema_pembayaran == 'lunas' ? 'Pembayaran penuh sesuai jatuh tempo' : 'Tersedia pembayaran cicilan bulanan') : 'Silakan hubungi UPPM untuk syarat pembayaran' }}</p>
            <p><strong>Catatan:</strong> Invoice ini dibuat secara otomatis. Pastikan semua detail sudah benar sebelum melakukan pembayaran.</p>
            <p><em>Unit Pengembangan Pendidikan Ma'arif - Mendukung Pendidikan Berkualitas untuk Generasi Masa Depan</em></p>
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
