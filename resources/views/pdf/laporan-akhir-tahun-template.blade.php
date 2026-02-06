<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akhir Tahun Kepala Sekolah - {{ $laporan->nama_kepala_sekolah_madrasah ?? 'N/A' }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .header h2 {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #333;
        }

        .header p {
            font-size: 12px;
            margin: 0;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #f0f0f0;
            padding: 5px 8px;
            font-weight: bold;
            border: 1px solid #000;
            margin-bottom: 8px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .data-table .label {
            width: 35%;
            font-weight: bold;
            background: #f8f8f8;
        }

        .data-table .value {
            width: 65%;
        }

        .achievement-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .achievement-table th,
        .achievement-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            font-size: 11px;
        }

        .achievement-table th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .talent-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .talent-table th,
        .talent-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            font-size: 11px;
        }

        .talent-table th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-style: italic;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .signature-section {
            margin-top: 40px;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 200px;
            display: inline-block;
        }

        .category-note {
            font-size: 10px;
            margin-top: 5px;
            text-align: left;
        }

        .category-note strong {
            color: #dc3545;
        }

        .total-score {
            background: #fff3cd;
            padding: 8px;
            border: 1px solid #000;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
        }

        .warning-text {
            background: #f8d7da;
            color: #721c24;
            padding: 8px;
            border: 1px solid #f5c6cb;
            margin: 10px 0;
            font-size: 11px;
            text-align: justify;
        }

        @media print {
            body { print-color-adjust: exact; }
            .no-print { display: none; }
        }

        .page-break {
            page-break-before: always;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN AKHIR TAHUN KEPALA SEKOLAH/MADRASAH</h1>
        <h2>TAHUN PELAPORAN {{ $laporan->tahun_pelaporan ?? 'N/A' }}</h2>
        <p><strong>Nama Satpen:</strong> {{ $laporan->nama_satpen ?? 'N/A' }}</p>
        <p><strong>Alamat:</strong> {{ $laporan->alamat ?? 'N/A' }}</p>
    </div>

    <!-- Step 1: Identitas Kepala Sekolah -->
    <div class="section">
        <div class="section-header">1. IDENTITAS KEPALA SEKOLAH/MADRASAH</div>
        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="value">{{ $laporan->nama_kepala_sekolah_madrasah ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Gelar</td>
                <td class="value">{{ $laporan->gelar ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">TMT Kepala Sekolah/Madrasah Pertama</td>
                <td class="value">{{ $laporan->tmt_ks_kamad_pertama ? \Carbon\Carbon::parse($laporan->tmt_ks_kamad_pertama)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">TMT Kepala Sekolah/Madrasah Terakhir</td>
                <td class="value">{{ $laporan->tmt_ks_kamad_terakhir ? \Carbon\Carbon::parse($laporan->tmt_ks_kamad_terakhir)->format('d-m-Y') : '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Step 2: Capaian Utama 3 Tahun Berjalan -->
    <div class="section">
        <div class="section-header">2. CAPAIAN UTAMA 3 TAHUN BERJALAN</div>

        <table class="achievement-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Indikator</th>
                    <th style="width: 25%;">2023</th>
                    <th style="width: 25%;">2024</th>
                    <th style="width: 25%;">2025</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jumlah Siswa</td>
                    <td>{{ number_format($laporan->jumlah_siswa_2023 ?? 0) }}</td>
                    <td>{{ number_format($laporan->jumlah_siswa_2024 ?? 0) }}</td>
                    <td>{{ number_format($laporan->jumlah_siswa_2025 ?? 0) }}</td>
                </tr>
                <tr>
                    <td>Persentase Alumni Bekerja (%)</td>
                    <td>{{ $laporan->persentase_alumni_bekerja ?? '-' }}%</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Persentase Alumni Wirausaha (%)</td>
                    <td>{{ $laporan->persentase_alumni_wirausaha ?? '-' }}%</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Persentase Alumni Tidak Terdeteksi (%)</td>
                    <td>{{ $laporan->persentase_alumni_tidak_terdeteksi ?? '-' }}%</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>BOSNAS (Rp)</td>
                    <td>{{ $laporan->bosnas_2023 ?? '-' }}</td>
                    <td>{{ $laporan->bosnas_2024 ?? '-' }}</td>
                    <td>{{ $laporan->bosnas_2025 ?? '-' }}</td>
                </tr>
                <tr>
                    <td>BOSDA (Rp)</td>
                    <td>{{ $laporan->bosda_2023 ?? '-' }}</td>
                    <td>{{ $laporan->bosda_2024 ?? '-' }}</td>
                    <td>{{ $laporan->bosda_2025 ?? '-' }}</td>
                </tr>
                <tr>
                    <td>SPP/BPPP/Lain (Rp)</td>
                    <td>{{ $laporan->spp_bppp_lain_2023 ?? '-' }}</td>
                    <td>{{ $laporan->spp_bppp_lain_2024 ?? '-' }}</td>
                    <td>{{ $laporan->spp_bppp_lain_2025 ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pendapatan Unit Usaha (Rp)</td>
                    <td>{{ $laporan->pendapatan_unit_usaha_2023 ?? '-' }}</td>
                    <td>{{ $laporan->pendapatan_unit_usaha_2024 ?? '-' }}</td>
                    <td>{{ $laporan->pendapatan_unit_usaha_2025 ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
        <table class="achievement-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Indikator</th>
                    <th style="width: 25%;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Persentase Alumni Bekerja (%)</td>
                    <td>{{ $laporan->persentase_alumni_bekerja ?? '-' }}%</td>
                </tr>
                <tr>
                    <td>Persentase Alumni Wirausaha (%)</td>
                    <td>{{ $laporan->persentase_alumni_wirausaha ?? '-' }}%</td>
                </tr>
                <tr>
                    <td>Persentase Alumni Tidak Terdeteksi (%)</td>
                    <td>{{ $laporan->persentase_alumni_tidak_terdeteksi ?? '-' }}%</td>
                </tr>
            </tbody>
        </table>

        <table class="data-table">
            <tr>
                <td class="label">Status Akreditasi</td>
                <td class="value">{{ $laporan->status_akreditasi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Akreditasi Mulai</td>
                <td class="value">{{ $laporan->tanggal_akreditasi_mulai ? \Carbon\Carbon::parse($laporan->tanggal_akreditasi_mulai)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Akreditasi Berakhir</td>
                <td class="value">{{ $laporan->tanggal_akreditasi_berakhir ? \Carbon\Carbon::parse($laporan->tanggal_akreditasi_berakhir)->format('d-m-Y') : '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Step 3: Layanan Pendidikan -->
    <div class="section">
        <div class="section-header">3. LAYANAN PENDIDIKAN</div>
        <table class="data-table">
            <tr>
                <td class="label">Model Layanan Pendidikan</td>
                <td class="value">{{ $laporan->model_layanan_pendidikan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Capaian Layanan Menonjol</td>
                <td class="value">{{ $laporan->capaian_layanan_menonjol ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Masalah Layanan Utama</td>
                <td class="value">{{ $laporan->masalah_layanan_utama ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Step 4: SDM -->
    <div class="section">
        <div class="section-header">4. SUMBER DAYA MANUSIA (SDM)</div>

        <table class="achievement-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Status Kepegawaian</th>
                    <th style="width: 50%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PNS Sertifikasi</td>
                    <td>{{ $laporan->pns_sertifikasi ?? 0 }}</td>
                </tr>
                <tr>
                    <td>PNS Non Sertifikasi</td>
                    <td>{{ $laporan->pns_non_sertifikasi ?? 0 }}</td>
                </tr>
                <tr>
                    <td>GTY Sertifikasi Inpassing</td>
                    <td>{{ $laporan->gty_sertifikasi_inpassing ?? 0 }}</td>
                </tr>
                <tr>
                    <td>GTY Sertifikasi</td>
                    <td>{{ $laporan->gty_sertifikasi ?? 0 }}</td>
                </tr>
                <tr>
                    <td>GTY Non Sertifikasi</td>
                    <td>{{ $laporan->gty_non_sertifikasi ?? 0 }}</td>
                </tr>
                <tr>
                    <td>GTT</td>
                    <td>{{ $laporan->gtt ?? 0 }}</td>
                </tr>
                <tr>
                    <td>PTY</td>
                    <td>{{ $laporan->pty ?? 0 }}</td>
                </tr>
                <tr>
                    <td>PTT</td>
                    <td>{{ $laporan->ptt ?? 0 }}</td>
                </tr>
            </tbody>
        </table>

        @if($laporan->nama_talenta && is_array(json_decode($laporan->nama_talenta, true)))
        <div class="section">
            <div style="font-weight: bold; margin-bottom: 5px;">Talenta Guru/Karyawan:</div>
            <table class="talent-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 30%;">Nama</th>
                        <th style="width: 65%;">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(json_decode($laporan->nama_talenta, true) as $index => $nama)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $nama ?? '-' }}</td>
                            <td>{{ json_decode($laporan->alasan_talenta, true)[$index] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(isset($kondisiGuruUsers) && !empty($kondisiGuruUsers))
        <div class="section">
            <div style="font-weight: bold; margin-bottom: 5px;">Kondisi Guru:</div>
            <table class="talent-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 60%;">Nama Guru</th>
                        <th style="width: 35%;">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kondisiGuruUsers as $index => $guru)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $guru['nama'] }}</td>
                            <td>{{ $guru['kondisi'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($laporan->masalah_sdm_utama && is_array(json_decode($laporan->masalah_sdm_utama, true)))
        <div class="section">
            <div style="font-weight: bold; margin-bottom: 5px;">Masalah SDM Utama:</div>
            <ol style="margin: 0; padding-left: 20px;">
                @foreach(json_decode($laporan->masalah_sdm_utama, true) as $masalah)
                    <li>{{ $masalah }}</li>
                @endforeach
            </ol>
        </div>
        @endif
    </div>

    <!-- Step 5: Keuangan -->
    <div class="section">
        <div class="section-header">5. KEUANGAN</div>
        <table class="data-table">
            <tr>
                <td class="label">Sumber Dana Utama</td>
                <td class="value">{{ $laporan->sumber_dana_utama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kondisi Keuangan Akhir Tahun</td>
                <td class="value">{{ ucfirst($laporan->kondisi_keuangan_akhir_tahun ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Catatan Pengelolaan Keuangan</td>
                <td class="value">{{ $laporan->catatan_pengelolaan_keuangan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Step 6: PPDB -->
    <div class="section">
        <div class="section-header">6. PENERIMAAN PESERTA DIDIK BARU (PPDB)</div>
        <table class="data-table">
            <tr>
                <td class="label">Metode PPDB</td>
                <td class="value">{{ $laporan->metode_ppdb ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Hasil PPDB Tahun Berjalan</td>
                <td class="value">{{ $laporan->hasil_ppdb_tahun_berjalan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Masalah Utama PPDB</td>
                <td class="value">{{ $laporan->masalah_utama_ppdb ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Step 7: Program Unggulan -->
    <div class="section">
        <div class="section-header">7. PROGRAM UNGGULAN</div>
        <table class="data-table">
            <tr>
                <td class="label">Nama Program Unggulan</td>
                <td class="value">{{ $laporan->nama_program_unggulan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alasan Pemilihan Program</td>
                <td class="value">{{ $laporan->alasan_pemilihan_program ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Target Unggulan</td>
                <td class="value">{{ $laporan->target_unggulan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kontribusi Unggulan</td>
                <td class="value">{{ $laporan->kontribusi_unggulan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Sumber Biaya Program</td>
                <td class="value">{{ $laporan->sumber_biaya_program ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tim Program Unggulan</td>
                <td class="value">{{ $laporan->tim_program_unggulan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Step 8: Refleksi -->
    <div class="section">
        <div class="section-header">8. REFLEKSI</div>
        <table class="data-table">
            <tr>
                <td class="label">Keberhasilan Terbesar Tahun Ini</td>
                <td class="value">{{ $laporan->keberhasilan_terbesar_tahun_ini ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Masalah Paling Berat Dihadapi</td>
                <td class="value">{{ $laporan->masalah_paling_berat_dihadapi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Keputusan Sulit yang Diambil</td>
                <td class="value">{{ $laporan->keputusan_sulit_diambil ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Step 9: Risiko -->
    <div class="section">
        <div class="section-header">9. RISIKO</div>
        <table class="data-table">
            <tr>
                <td class="label">Risiko Terbesar Satpen Tahun Depan</td>
                <td class="value">{{ $laporan->risiko_terbesar_satpen_tahun_depan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Fokus Perbaikan Tahun Depan</td>
                <td class="value">
                    @if($laporan->fokus_perbaikan_tahun_depan && is_array(json_decode($laporan->fokus_perbaikan_tahun_depan, true)))
                        <ol style="margin: 0; padding-left: 20px;">
                            @foreach(json_decode($laporan->fokus_perbaikan_tahun_depan, true) as $fokus)
                                <li>{{ $fokus }}</li>
                            @endforeach
                        </ol>
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Step 10: Pernyataan -->
    <div class="section">
        <div class="section-header">10. PERNYATAAN</div>
        <p class="text-justify">
            Dengan ini saya menyatakan bahwa semua yang saya tulis di atas adalah BENAR dan DAPAT DIPERTANGGUNGJAWABKAN. Apabila kelak ditemukan ketidaksesuaian, maka saya bersedia menerima sanksinya.
        </p>

        <div style="margin-top: 20px;">
            <strong>Pernyataan Benar:</strong> {{ $laporan->pernyataan_benar ? 'Ya' : 'Tidak' }}
        </div>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <p>Yogyakarta, {{ $laporan->updated_at ? \Carbon\Carbon::parse($laporan->updated_at)->locale('id')->isoFormat('DD MMMM Y') : \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM Y') }}</p>
        <p><strong>{{ $laporan->nama_kepala_sekolah_madrasah ?? 'N/A' }}</strong></p>
        @if($laporan->signature_data)
            <div style="margin-top: 0px; text-align: center;">
                <img src="{{ $laporan->signature_data }}" alt="Tanda Tangan" style="max-width: 200px; height: auto;">
            </div>
        @endif
        <div class="signature-line" style="margin-top: -20px;"></div>
        <p style="margin-top: -40px">NIP. {{ $laporan->user->nip ?? '-' }}</p>
    </div>

    <!-- Lampiran -->
    <div class="section page-break">
        <div class="section-header">LAMPIRAN</div>
        @for($i = 1; $i <= 9; $i++)
            @php $lampiran = 'lampiran_step_' . $i; @endphp
            @if($laporan->$lampiran)
                <div style="margin-bottom: 20px;">
                    <strong>Lampiran {{ $i }}:</strong> File PDF tersedia ({{ basename($laporan->$lampiran) }})
                </div>
            @endif
        @endfor
        @if(!$laporan->lampiran_step_1 && !$laporan->lampiran_step_2 && !$laporan->lampiran_step_3 && !$laporan->lampiran_step_4 && !$laporan->lampiran_step_5 && !$laporan->lampiran_step_6 && !$laporan->lampiran_step_7 && !$laporan->lampiran_step_8 && !$laporan->lampiran_step_9)
            <div style="margin-bottom: 20px;">
                <em>Tidak ada lampiran yang tersedia.</em>
            </div>
        @endif
    </div>
</body>
</html>
