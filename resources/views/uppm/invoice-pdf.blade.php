<div class="card-body">
    <img src="{{ asset('images/logo1.png') }}" alt="" height="50">
    <hr style="border-top: 1px dotted #484747; margin: 1rem 0;">
    <div class="row">
        <div class="col-md-6">
            <h5 style="margin-bottom: 1rem">Identitas Sekolah/Madrasah</h5>
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 40%; padding: 0; vertical-align: top;"><strong>Nama Sekolah/Madrasah</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding: 0; vertical-align: top;">{{ $madrasah->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 0; vertical-align: top;"><strong>Alamat</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding: 0; vertical-align: top;">{{ $madrasah->alamat ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table style="width: 100%; border: none; margin-top: 2.5rem;">
                <tr>
                    <td style="width: 40%; padding: 0; vertical-align: top;"><strong>Tahun Anggaran</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding: 0; vertical-align: top;">{{ $tahun }}</td>
                </tr>
                <tr>
                    <td style="padding: 0; vertical-align: top;"><strong>Jatuh Tempo</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding: 0; vertical-align: top;">{{ $setting ? $setting->jatuh_tempo : '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <hr>

    <h5>Rincian Perhitungan Iuran</h5>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="border: 1px solid #000; padding: 8px;">Komponen</th>
                <th style="border: 1px solid #000; padding: 8px;">Jumlah</th>
                <th style="border: 1px solid #000; padding: 8px;">Nominal per Bulan</th>
                <th style="border: 1px solid #000; padding: 8px;">Total per Tahun</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">Siswa</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_siswa ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_siswa ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['siswa'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">PNS Sertifikasi</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_pns_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_pns_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['pns_sertifikasi'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">PNS Non Sertifikasi</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_pns_non_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_pns_non_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['pns_non_sertifikasi'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">GTY Sertifikasi</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_gty_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_gty_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['gty_sertifikasi'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">GTY Sertifikasi Inpassing</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_gty_sertifikasi_inpassing ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['gty_sertifikasi_inpassing'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">GTY Non Sertifikasi</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_gty_non_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_gty_non_sertifikasi ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['gty_non_sertifikasi'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">GTT</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_gtt ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_gtt ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['gtt'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">PTY</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_pty ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_pty ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['pty'] ?? 0) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">PTT</td>
                <td style="border: 1px solid #000; padding: 8px;">{{ number_format($dataSekolah->jumlah_ptt ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($setting->nominal_ptt ?? 0) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Rp {{ number_format($rincian['ptt'] ?? 0) }}</td>
            </tr>
            <tr style="background-color: #e9ecef;">
                <td colspan="3" style="border: 1px solid #000; padding: 8px;"><strong>Total Tagihan UPPM Tahunan</strong></td>
                <td style="border: 1px solid #000; padding: 8px;"><strong>Rp {{ number_format($totalTahunan) }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($setting && $setting->catatan)
    <div style="margin-top: 1rem;">
        <h6>Catatan:</h6>
        <p>{{ $setting->catatan }}</p>
    </div>
    @endif
</div>
