@extends('layouts.master')

@section('title')Invoice UPPM @endsection

@section('css')
<style>
.invoice-container {
    max-width: 900px;
    margin: 0 auto;
    background: white;
    box-shadow: 0 0 25px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.invoice-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 30px;
    text-align: center;
    position: relative;
}

.invoice-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="50" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.invoice-header h1 {
    margin: 0;
    font-size: 3rem;
    font-weight: 300;
    letter-spacing: 2px;
    position: relative;
    z-index: 1;
}

.invoice-header .subtitle {
    margin: 15px 0 0 0;
    font-size: 1.2rem;
    opacity: 0.9;
    font-weight: 400;
    position: relative;
    z-index: 1;
}

.invoice-body {
    padding: 50px 40px;
}

.invoice-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 50px;
}

.meta-section {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    border-left: 4px solid #667eea;
}

.meta-section h5 {
    color: #667eea;
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.meta-section p {
    margin: 8px 0;
    line-height: 1.6;
    color: #495057;
}

.meta-section strong {
    color: #343a40;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin: 40px 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
}

.invoice-table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    padding: 20px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.invoice-table td {
    padding: 18px 15px;
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
    font-size: 1.1rem;
    border: none;
}

.total-row td {
    padding: 25px 15px;
    border: none;
}

.total-row:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%) !important;
}

.invoice-footer {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 2px solid #dee2e6;
    text-align: center;
    color: #6c757d;
}

.invoice-footer p {
    margin: 10px 0;
    line-height: 1.6;
}

.invoice-footer em {
    font-style: italic;
    color: #667eea;
}

.download-section {
    text-align: center;
    margin-bottom: 30px;
}

.download-btn {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border: none;
    padding: 15px 40px;
    border-radius: 30px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
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

.download-btn i {
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .invoice-container {
        margin: 10px;
        border-radius: 10px;
    }

    .invoice-header {
        padding: 30px 20px;
    }

    .invoice-header h1 {
        font-size: 2.5rem;
    }

    .invoice-body {
        padding: 30px 20px;
    }

    .invoice-meta {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .invoice-table {
        font-size: 0.9rem;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 12px 8px;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <div class="subtitle">Unit Pengembangan Pendidikan Ma'arif</div>
        </div>

        <div class="invoice-body">
            <div class="download-section">
                <a href="{{ route('uppm.invoice.download', ['madrasah_id' => $madrasah->id, 'tahun' => $tahun]) }}" class="download-btn">
                    <i class="bx bx-download"></i>
                    <span>Download PDF Invoice</span>
                </a>
            </div>

            <div class="invoice-meta">
                <div class="meta-section">
                    <h5>Bill To</h5>
                    <p><strong>{{ $madrasah->name }}</strong></p>
                    <p>{{ $madrasah->address ?? 'Address not available' }}</p>
                </div>

                <div class="meta-section">
                    <h5>Invoice Details</h5>
                    <p><strong>Invoice Number:</strong> UPPM/{{ $madrasah->id }}/{{ $tahun }}</p>
                    <p><strong>Academic Year:</strong> {{ $tahun }}</p>
                    <p><strong>Issue Date:</strong> {{ date('d F Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $setting ? date('d F Y', strtotime($setting->jatuh_tempo)) : 'Not specified' }}</p>
                </div>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Monthly Rate</th>
                        <th class="text-right">Annual Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Students</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_siswa ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_siswa ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['siswa'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PNS Certified Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_pns_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_pns_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['pns_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PNS Non-Certified Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_pns_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_pns_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['pns_non_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTY Certified Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gty_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gty_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gty_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTY Certified Inpassing Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gty_sertifikasi_inpassing ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gty_sertifikasi_inpassing'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTY Non-Certified Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gty_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gty_non_sertifikasi ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gty_non_sertifikasi'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>GTT Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_gtt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_gtt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['gtt'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PTY Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_pty ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_pty ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['pty'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td><strong>PTT Teachers</strong></td>
                        <td class="text-center">{{ number_format($dataSekolah->jumlah_ptt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($setting->nominal_ptt ?? 0) }}</td>
                        <td class="text-right">Rp {{ number_format($rincian['ptt'] ?? 0) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Total Annual UPPM Fee</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($totalTahunan) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="invoice-footer">
                <p><strong>Payment Terms:</strong> {{ $setting ? ($setting->skema_pembayaran == 'lunas' ? 'Full payment due by due date' : 'Monthly installments available') : 'Please contact UPPM for payment terms' }}</p>
                <p><strong>Note:</strong> This invoice is generated automatically. Please ensure all details are correct before making payment.</p>
                <p><em>Unit Pengembangan Pendidikan Ma'arif - Supporting Quality Education for Future Generations</em></p>
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
