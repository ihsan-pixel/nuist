<div class="card-body">
    <table style="width: 100%; border: none; margin-bottom: 1rem;">
        <tr>
            <td style="width: 50%; padding: 0; vertical-align: top;">
                <img src="{{ asset('images/logo1.png') }}" alt="" height="50">
            </td>
            <td style="width: 50%; padding: 0; vertical-align: top; text-align: right;">
                <h1 style="margin: 0; font-size: 24px;">Invoice UPPM</h1>
            </td>
        </tr>
    </table>
    <hr style="border-top: 1px dotted #484747; margin: 1rem 0;">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; padding: 0; vertical-align: top;">
                {{-- <h5 style="margin-bottom: 1rem; font-size: 14px;">Identitas Sekolah/Madrasah</h5> --}}
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="width: 25%; padding: 0; vertical-align: top; font-size: 12px;"><strong>Nama Sekolah/Madrasah</strong></td>
                        <td style="width: 5%; font-size: 12px;"><strong>:</strong></td>
                        <td style="padding: 0; vertical-align: top; font-size: 12px;">{{ $madrasah->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0; vertical-align: top; font-size: 12px;"><strong>Alamat</strong></td>
                        <td style="font-size: 12px;"><strong>:</strong></td>
                        <td style="padding: 0; vertical-align: top; font-size: 12px;">{{ $madrasah->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; padding: 0; vertical-align: top;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="width: 40%; padding: 0; vertical-align: top; font-size: 12px;"><strong>Tahun Anggaran</strong></td>
                        <td style="font-size: 12px;"><strong>:</strong></td>
                        <td style="padding: 0; vertical-align: top; font-size: 12px;">{{ $tahun }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0; vertical-align: top; font-size: 12px;"><strong>Jatuh Tempo</strong></td>
                        <td style="font-size: 12px;"><strong>:</strong></td>
                        <td style="padding: 0; vertical-align: top; font-size: 12px;">{{ $setting ? $setting->jatuh_tempo : '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

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
