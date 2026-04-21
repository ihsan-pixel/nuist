<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMFONI GTK LPMNU DIY - <?php echo e($simfoni->nama_lengkap ?? 'N/A'); ?></title>
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
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .header h2 {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #efaa0c;
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

        .projection-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .projection-table th,
        .projection-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            font-size: 11px;
        }

        .projection-table th {
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>SIMFONI GTK MINI LPMNU DIY</h1>
        <h2><?php echo e(\Carbon\Carbon::parse($simfoni->created_at)->locale('id')->isoFormat('MMMM Y')); ?></h2>
        <p><strong>Nama Satpen:</strong> <?php echo e(\App\Models\Madrasah::find($simfoni->user->madrasah_id)->name ?? 'N/A'); ?></p>
    </div>

    <!-- A. DATA SK -->
    <div class="section">
        <div class="section-header">A. DATA SK</div>
        <table class="data-table">
            <tr>
                <td class="label">1. Nama Lengkap dengan gelar</td>
                <td class="value"><?php echo e($simfoni->nama_lengkap_gelar ?? '-'); ?><?php echo e($simfoni->gelar); ?></td>
            </tr>
            <tr>
                <td class="label">2. Tempat dan tanggal lahir</td>
                <td class="value"><?php echo e($simfoni->tempat_lahir ?? '-'); ?>, <?php echo e($simfoni->tanggal_lahir ? \Carbon\Carbon::parse($simfoni->tanggal_lahir)->format('d-m-Y') : '-'); ?></td>
            </tr>
            <tr>
                <td class="label">3. NUPTK</td>
                <td class="value"><?php echo e($simfoni->nuptk ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">4. Karta-NU</td>
                <td class="value"><?php echo e($simfoni->kartanu ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">5. NIP Ma'arif Baru</td>
                <td class="value"><?php echo e($simfoni->nipm ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">6. Nomor Induk Kependudukan (NIK)</td>
                <td class="value"><?php echo e($simfoni->nik ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">7. TMT-Pertama dan Masa Kerja</td>
                <td class="value"><?php echo e($simfoni->tmt ? \Carbon\Carbon::parse($simfoni->tmt)->format('d-m-Y') : '-'); ?> (<?php echo e($simfoni->masa_kerja ?? '-'); ?>)</td>
            </tr>
            <tr>
                <td class="label">8. Strata, PT Asal, Tahun Lulus</td>
                <td class="value"><?php echo e($simfoni->strata_pendidikan ?? '-'); ?>, <?php echo e($simfoni->pt_asal ?? '-'); ?>, <?php echo e($simfoni->tahun_lulus ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">9. Nama Program Studi</td>
                <td class="value"><?php echo e($simfoni->program_studi ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">10. Penilaian Perilaku Kerja 2025</td>
                <td class="value">-</td>
            </tr>
        </table>
    </div>

    <!-- B. RIWAYAT KERJA -->
    <div class="section">
        <div class="section-header">B. RIWAYAT KERJA</div>
        <table class="data-table">
            <tr>
                <td class="label">11. Status kerja saat ini</td>
                <td class="value"><?php echo e($simfoni->status_kerja ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">12. Tanggal & Nomor SK Pertama</td>
                <td class="value"><?php echo e($simfoni->tanggal_sk_pertama ? \Carbon\Carbon::parse($simfoni->tanggal_sk_pertama)->format('d-m-Y') : '-'); ?> / <?php echo e($simfoni->nomor_sk_pertama ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">13. Nomor Sertifikasi Pendidik</td>
                <td class="value"><?php echo e($simfoni->nomor_sertifikasi_pendidik ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">14. Riwayat Kerja Sebelumnya</td>
                <td class="value"><?php echo e($simfoni->riwayat_kerja_sebelumnya ?? '-'); ?></td>
            </tr>
        </table>
    </div>

    <!-- C. KEAHLIAN DAN DATA LAIN -->
    <div class="section">
        <div class="section-header">C. KEAHLIAN DAN DATA LAIN</div>
        <table class="data-table">
            <tr>
                <td class="label">15. Keahlian</td>
                <td class="value"><?php echo e($simfoni->keahlian ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">16. Kedudukan di LPM</td>
                <td class="value"><?php echo e($simfoni->kedudukan_lpm ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">17. Prestasi</td>
                <td class="value"><?php echo e($simfoni->prestasi ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">18. Tahun Sertifikasi dan Impassing</td>
                <td class="value"><?php echo e($simfoni->tahun_sertifikasi_impassing ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">19. Nomor HP/WA</td>
                <td class="value"><?php echo e($simfoni->no_hp ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">20. E-mail aktif</td>
                <td class="value"><?php echo e($simfoni->email ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">21. Status Pernikahan</td>
                <td class="value"><?php echo e($simfoni->status_pernikahan ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">22. Alamat Lengkap</td>
                <td class="value"><?php echo e($simfoni->alamat_lengkap ?? '-'); ?></td>
            </tr>
        </table>
    </div>

    <!-- D. DATA KEUANGAN/KESEJAHTERAAN -->
    <div class="section">
        <div class="section-header">D. DATA KEUANGAN/KESEJAHTERAAN</div>
        <table class="data-table">
            <tr>
                <td class="label">23. BANK, Nomor Rekening</td>
                <td class="value"><?php echo e($simfoni->bank ?? '-'); ?>, <?php echo e($simfoni->nomor_rekening ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">24. Gaji Sertifikasi</td>
                <td class="value">Rp <?php echo e(number_format($simfoni->gaji_sertifikasi ?? 0, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="label">25. Gaji Pokok Perbulan dari Satpen</td>
                <td class="value">Rp <?php echo e(number_format($simfoni->gaji_pokok ?? 0, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="label">26. Honor lain</td>
                <td class="value">Rp <?php echo e(number_format($simfoni->honor_lain ?? 0, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="label">27. Penghasilan Lain</td>
                <td class="value">Rp <?php echo e(number_format($simfoni->penghasilan_lain ?? 0, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="label">28. Penghasilan Pasangan (not-count)</td>
                <td class="value">Rp <?php echo e(number_format($simfoni->penghasilan_pasangan ?? 0, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td class="label">29. Jumlah Total Penghasilan Diri</td>
                <td class="value">Rp <?php echo e(number_format($simfoni->total_penghasilan ?? 0, 0, ',', '.')); ?></td>
            </tr>
        </table>

        <div class="category-note">
            <strong>Kategori: <?php echo e($simfoni->kategori_penghasilan ?? '-'); ?></strong><br>
            [A=bagus] 10 juta lebih &nbsp;&nbsp;&nbsp;&nbsp; [B=baik] 6,0 - 9,9 juta &nbsp;&nbsp;&nbsp;&nbsp; [C=cukup] 4,0 - 5,9 juta<br>
            [D=hampir cukup]= 2,5 - 3,9 &nbsp;&nbsp;&nbsp;&nbsp; [E=Kurang] 1,5 - 2,4 &nbsp;&nbsp;&nbsp;&nbsp; [F=sangat kurang] di bawah 1,5
        </div>
    </div>

    <!-- E. STATUS KEKADERAN -->
    <div class="section">
        <div class="section-header">E. STATUS KEKADERAN</div>
        <table class="data-table">
            <tr>
                <td class="label">30. Status Kader Diri</td>
                <td class="value"><?php echo e($simfoni->status_kader_diri ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">31. Pendidikan Kader yang Diikuti</td>
                <td class="value"><?php echo e($simfoni->pendidikan_kader ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">32. Status Kader Ayah</td>
                <td class="value"><?php echo e($simfoni->status_kader_ayah ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">33. Status Kader Ibu</td>
                <td class="value"><?php echo e($simfoni->status_kader_ibu ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">34. Status Kader Suami/Istri</td>
                <td class="value"><?php echo e($simfoni->status_kader_pasangan ?? '-'); ?></td>
            </tr>
        </table>

        <div class="category-note">
            STATUS KEKADERAN: [4] militan &nbsp;&nbsp;&nbsp;&nbsp; [3] aktif &nbsp;&nbsp;&nbsp;&nbsp; [2] Baru &nbsp;&nbsp;&nbsp;&nbsp; [0] Non-NU
        </div>
    </div>

    <!-- F. DATA KELUARGA -->
    <div class="section">
        <div class="section-header">F. DATA KELUARGA</div>
        <table class="data-table">
            <tr>
                <td class="label">35. Nama Ayah</td>
                <td class="value"><?php echo e($simfoni->nama_ayah ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">36. Nama Ibu</td>
                <td class="value"><?php echo e($simfoni->nama_ibu ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">37. Nama Suami/Istri</td>
                <td class="value"><?php echo e($simfoni->nama_pasangan ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">38. Jumlah Anak Tanggungan</td>
                <td class="value"><?php echo e($simfoni->jumlah_anak ?? '-'); ?></td>
            </tr>
        </table>
    </div>

    <!-- G. PROYEKSI KE DEPAN -->
    <div class="section">
        <div class="section-header">G. PROYEKSI KE DEPAN</div>

        <table class="projection-table">
            <thead>
                <tr>
                    <th style="width: 5%;">NO</th>
                    <th style="width: 55%;">TOPIK</th>
                    <th style="width: 20%;">Jawab</th>
                    <th style="width: 20%;">SKOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td style="text-align: left;">Akan Kuliah S2</td>
                    <td><?php echo e($simfoni->akan_kuliah_s2 == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_kuliah_s2 == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td style="text-align: left;">Akan mendaftar PNS</td>
                    <td><?php echo e($simfoni->akan_mendaftarkan_pns == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_mendaftarkan_pns == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td style="text-align: left;">Akan mendaftar PPPK</td>
                    <td><?php echo e($simfoni->akan_mendaftarkan_pppk == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_mendaftarkan_pppk == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td style="text-align: left;">Akan mengikuti PPG</td>
                    <td><?php echo e($simfoni->akan_mengikuti_ppg == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_mengikuti_ppg == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td style="text-align: left;">Akan menulis buku/modul/riset</td>
                    <td><?php echo e($simfoni->akan_menulis_buku_modul_riset == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_menulis_buku_modul_riset == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td style="text-align: left;">Akan mengikuti Seleksi Diklat CAKEP</td>
                    <td><?php echo e($simfoni->akan_mengikuti_seleksi_diklat_cakep == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_mengikuti_seleksi_diklat_cakep == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td style="text-align: left;">Akan membimbing riset & prestasi siswa</td>
                    <td><?php echo e($simfoni->akan_membimbing_riset_prestasi_siswa == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_membimbing_riset_prestasi_siswa == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td style="text-align: left;">Akan masuk tim unggulan sekolah/madrasah</td>
                    <td><?php echo e($simfoni->akan_masuk_tim_unggulan_sekolah_madrasah == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_masuk_tim_unggulan_sekolah_madrasah == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td style="text-align: left;">Akan kompetisi Pimpinan Level II</td>
                    <td><?php echo e($simfoni->akan_kompetisi_pimpinan_level_ii == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_kompetisi_pimpinan_level_ii == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td style="text-align: left;">Akan aktif mengikuti Pelatihan-pelatihan</td>
                    <td><?php echo e($simfoni->akan_aktif_mengikuti_pelatihan == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_aktif_mengikuti_pelatihan == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>11</td>
                    <td style="text-align: left;">Akan aktif di MGMP dan MKKSM</td>
                    <td><?php echo e($simfoni->akan_aktif_mgmp_mkk == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_aktif_mgmp_mkk == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>12</td>
                    <td style="text-align: left;">Akan mengikuti Pendidikan Kader NU</td>
                    <td><?php echo e($simfoni->akan_mengikuti_pendidikan_kader_nu == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_mengikuti_pendidikan_kader_nu == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>13</td>
                    <td style="text-align: left;">Akan aktif membantu kegiatan lembaga</td>
                    <td><?php echo e($simfoni->akan_aktif_membantu_kegiatan_lembaga == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_aktif_membantu_kegiatan_lembaga == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>14</td>
                    <td style="text-align: left;">Akan aktif mengikuti kegiatan ke-NU-an</td>
                    <td><?php echo e($simfoni->akan_aktif_mengikuti_kegiatan_nu == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_aktif_mengikuti_kegiatan_nu == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>15</td>
                    <td style="text-align: left;">Akan aktif ikut ZIS & kegiatan sosial</td>
                    <td><?php echo e($simfoni->akan_aktif_ikut_zis_kegiatan_sosial == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_aktif_ikut_zis_kegiatan_sosial == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>16</td>
                    <td style="text-align: left;">Akan aktif mengembangkan unit usaha satpen</td>
                    <td><?php echo e($simfoni->akan_mengembangkan_unit_usaha_satpen == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_mengembangkan_unit_usaha_satpen == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>17</td>
                    <td style="text-align: left;">Akan bekerja dengan disiplin dan produktif</td>
                    <td><?php echo e($simfoni->akan_bekerja_disiplin_produktif == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_bekerja_disiplin_produktif == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>18</td>
                    <td style="text-align: left;">Akan loyal pada NU dan aktif di masyarakat</td>
                    <td><?php echo e($simfoni->akan_loyal_nu_aktif_masyarakat == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_loyal_nu_aktif_masyarakat == 'iya' ? '1' : '0'); ?></td>
                </tr>
                <tr>
                    <td>19</td>
                    <td style="text-align: left;">Bersedia jika dipindah di satpen lain di LPM</td>
                    <td><?php echo e($simfoni->akan_bersedia_dipindah_satpen_lain == 'iya' ? 'Ya' : 'Tidak'); ?></td>
                    <td><?php echo e($simfoni->akan_bersedia_dipindah_satpen_lain == 'iya' ? '1' : '0'); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total-score">
            Skor Proyeksi: <?php echo e($simfoni->skor_proyeksi ?? 0); ?>

        </div>

        <div class="category-note">
            Kategori Proyeksi: <?php echo e($simfoni->skor_proyeksi >= 20 ? 'Optimal' : ($simfoni->skor_proyeksi >= 15 ? 'Baik' : ($simfoni->skor_proyeksi >= 10 ? 'Cukup' : ($simfoni->skor_proyeksi >= 6 ? 'Kurang' : 'Buruk')))); ?><br>
            [20=optimal] ::: [15-19=baik] ::: [10-14=cukup] ::: [6-9=kurang] ::: [0-5=buruk]
        </div>
    </div>

    <!-- Statement -->
    <div class="section">
        <p style="text-align: justify; line-height: 1.5;">
            Dengan ini saya menyatakan bahwa semua yang saya tulis di atas adalah BENAR dan DAPAT DIPERTANGGUNGJAWABKAN. Apabila kelak ditemukan ketidaksesuaian, maka saya bersedia menerima sanksinya.
        </p>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <p>Yogyakarta, <?php echo e($simfoni->created_at->format('d')); ?> <?php echo e(\Carbon\Carbon::parse($simfoni->created_at)->locale('id')->isoFormat('MMMM')); ?> <?php echo e($simfoni->created_at->format('Y')); ?></p>
        <p><strong><?php echo e($simfoni->nama_lengkap_gelar ?? 'N/A'); ?></strong></p>
        <p>NIPM. <?php echo e($simfoni->nipm ?? '-'); ?></p>
    </div>
</body>
</html>

<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/simfoni-template.blade.php ENDPATH**/ ?>