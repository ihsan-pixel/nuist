<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Form Kelengkapan Dokumen GTK - {{ $user->nama_dengan_gelar }}</title>
    <style>
        @page { size: A4 portrait; margin: 12mm 14mm 13mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #111; font-family: DejaVu Sans, sans-serif; font-size: 8.2pt; line-height: 1.2; }
        .header { width: 100%; margin: -2mm 0 10px; }
        .header img { display: block; width: 100%; height: auto; }
        .title { margin: 4px 0 11px; text-align: center; font-size: 14pt; line-height: 1.4; font-weight: 700; }
        h2 { margin: 8px 0 6px; font-size: 11.5pt; }
        .intro { margin: 0 0 5px; }
        table.data { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data th, table.data td { border: .65px solid #222; padding: 4px 6px; vertical-align: middle; word-wrap: break-word; }
        table.data th { font-weight: 700; text-align: center; background: #fff; }
        .number { width: 7%; text-align: center; }
        .label { width: 29%; }
        .value { width: 64%; word-wrap: break-word; }
        .verification-label { width: 62%; }
        .verification-result { width: 31%; }
        .check { font-family: DejaVu Sans, sans-serif; white-space: nowrap; }
        .page-break { page-break-before: always; height: 1px; }
        .footer { position: fixed; bottom: -6mm; left: 0; color: #666; font-size: 8pt; }
        .note { color: #444; font-size: 8pt; }
        .wrap { white-space: normal; }
    </style>
</head>
<body>
@php
    $data = $user->gtkPendataan;
    $simfoni = $user->simfoni;
    $mgmpNames = $data?->nama_mgmp ?: $user->mgmpMemberships
        ->map(fn ($membership) => $membership->mgmpGroup?->name)
        ->filter()->unique()->implode(', ');
    $value = fn ($item) => filled($item) ? $item : '-';
    $date = fn ($item) => $item ? $item->translatedFormat('d F Y') : '-';
    $money = fn ($item) => filled($item) ? 'Rp '.number_format((float) $item, 0, ',', '.') : '-';
    $yesNo = fn ($condition, $yes = 'Sudah', $no = 'Belum') => ($condition ? '[x] '.$yes.'    [ ] '.$no : '[ ] '.$yes.'    [x] '.$no);
    $firstSkDate = $data?->tmt_sk_pertama ?: $user->tmt;
    $hasIdentity = filled($user->name) && filled($data?->nik) && filled($user->alamat)
        && filled($user->tempat_lahir) && filled($user->tanggal_lahir);
    $hasDocuments = filled($data?->ktp_path) && filled($data?->sk_awal_path) && filled($data?->sk_akhir_path);
    $hasEmployment = filled($user->nuptk) && filled($user->kartanu) && filled($firstSkDate)
        && filled($user->pendidikan_terakhir) && filled($user->program_studi);
@endphp

<div class="footer">LP Ma'arif NU PWNU DIY | Kebutuhan Data GTK</div>
@for($page = 1; $page <= 2; $page++)
    <div class="header">@if($letterheadDataUri)<img src="{{ $letterheadDataUri }}" alt="Kop LP Ma'arif NU PWNU DIY">@endif</div>

    @if($page === 1)
        <div class="title">FORM KELENGKAPAN DOKUMEN GTK<br>LP MA'ARIF NU PWNU DIY</div>
        <h2>1. Data Identitas GTK</h2>
        <table class="data">
            <tr><th class="number">No.</th><th class="label">Data GTK</th><th class="value">Keterangan Pengisian</th></tr>
            <tr><td class="number">1</td><td>Nama dan Gelar</td><td>{{ $value($user->nama_dengan_gelar) }}</td></tr>
            <tr><td class="number">2</td><td>NIK</td><td>{{ $value($data?->nik ?: $simfoni?->nik) }}</td></tr>
            <tr><td class="number">3</td><td>Alamat</td><td>{{ $value($user->alamat ?: $simfoni?->alamat_lengkap) }}</td></tr>
            <tr><td class="number">4</td><td>Asal Satpen</td><td>{{ $value($user->madrasah?->name) }}</td></tr>
            <tr><td class="number">5</td><td>Status Kepegawaian</td><td>{{ $value($user->statusKepegawaian?->name ?: $simfoni?->status_kerja) }}</td></tr>
            <tr><td class="number">6</td><td>Tempat, Tanggal Lahir</td><td>{{ $value(collect([$user->tempat_lahir ?: $simfoni?->tempat_lahir, $date($user->tanggal_lahir)])->filter(fn ($part) => filled($part) && $part !== '-')->implode(', ')) }}</td></tr>
            <tr><td class="number">7</td><td>Status perkawinan</td><td>{{ $value($simfoni?->status_pernikahan) }}</td></tr>
            <tr><td class="number">8</td><td>Golongan darah</td><td>{{ $value($data?->gol_darah) }}</td></tr>
            <tr><td class="number">9</td><td>No. HP</td><td>{{ $value($user->no_hp ?: $simfoni?->no_hp) }}</td></tr>
            <tr><td class="number">10</td><td>E-mail aktif</td><td>{{ $value($data?->email_aktif ?: $user->email ?: $simfoni?->email) }}</td></tr>
        </table>
        <h2>2. Data Kepegawaian</h2>
        <table class="data">
            <tr><th class="number">No.</th><th class="label">Data Kepegawaian</th><th class="value">Keterangan Pengisian</th></tr>
            <tr><td class="number">1</td><td>NUPTK</td><td>{{ $value($user->nuptk ?: $simfoni?->nuptk) }}</td></tr>
            <tr><td class="number">2</td><td>NIPM</td><td>{{ $value($user->nip ?: $simfoni?->nipm) }}</td></tr>
            <tr><td class="number">3</td><td>Kartanu</td><td>{{ $value($user->kartanu ?: $simfoni?->kartanu) }}</td></tr>
            <tr><td class="number">4</td><td>TMT</td><td>{{ $date($user->tmt) }}</td></tr>
            <tr><td class="number">5</td><td>Pendidikan Terakhir</td><td>{{ $value($user->pendidikan_terakhir ?: $simfoni?->strata_pendidikan) }}</td></tr>
            <tr><td class="number">6</td><td>Tahun Lulus</td><td>{{ $value($user->tahun_lulus ?: $simfoni?->tahun_lulus) }}</td></tr>
            <tr><td class="number">7</td><td>Program Studi</td><td>{{ $value($user->program_studi ?: $simfoni?->program_studi) }}</td></tr>
            <tr><td class="number">8</td><td>SCOD</td><td>{{ $value($user->madrasah?->scod) }}</td></tr>
            <tr><td class="number">9</td><td>NUIST ID</td><td>{{ $value($user->nuist_id) }}</td></tr>
            <tr><td class="number">10</td><td>TMT SK I</td><td>{{ $date($firstSkDate) }}</td></tr>
            <tr><td class="number">11</td><td>Nomor SK Pertama</td><td>{{ $value($data?->nomor_sk_pertama ?: $simfoni?->nomor_sk_pertama) }}</td></tr>
            <tr><td class="number">12</td><td>Agama</td><td>-</td></tr>
            <tr><td class="number">13</td><td>Kewarganegaraan</td><td>-</td></tr>
        </table>
        <div class="page-break"></div>
    @else
        <table class="data">
            <tr><th class="number">No.</th><th class="label">Data Kepegawaian</th><th class="value">Keterangan Pengisian</th></tr>
            <tr><td class="number">14</td><td>Masa Kerja</td><td>{{ $value($user->masa_kerja ?: $simfoni?->masa_kerja) }}</td></tr>
            <tr><td class="number">15</td><td>Gaji dari Satpen (Rp)</td><td>{{ $money($data?->gaji_satpen ?: $simfoni?->gaji_pokok) }}</td></tr>
            <tr><td class="number">16</td><td>No. Sertifikasi Pendidik</td><td>{{ $value($data?->nomor_sertifikasi_pendidik ?: $simfoni?->nomor_sertifikasi_pendidik) }}</td></tr>
            <tr><td class="number">17</td><td>Sertifikasi (Rp)</td><td>{{ $money($data?->gaji_sertifikasi ?: $simfoni?->gaji_sertifikasi) }}</td></tr>
            <tr><td class="number">18</td><td>Tunjangan rerata per bulan (Rp)</td><td>{{ $money($data?->tunjangan_rerata_bulanan) }}</td></tr>
        </table>
        <h2>3. Proses Verifikasi Oleh Petugas</h2>
        <table class="data">
            <tr><th class="number">No.</th><th class="verification-label">Pemeriksaan</th><th class="verification-result">Hasil</th></tr>
            <tr><td class="number">1</td><td>KTP asli telah di-scan</td><td class="check">{{ $yesNo(filled($data?->ktp_path)) }}</td></tr>
            <tr><td class="number">2</td><td>Scan wajah telah dilakukan</td><td class="check">{{ $yesNo($user->hasFaceEnrollment()) }}</td></tr>
            <tr><td class="number">3</td><td>SK Pertama telah di-scan</td><td class="check">{{ $yesNo(filled($data?->sk_awal_path)) }}</td></tr>
            <tr><td class="number">4</td><td>SK Terakhir telah di-scan</td><td class="check">{{ $yesNo(filled($data?->sk_akhir_path)) }}</td></tr>
            <tr><td class="number">5</td><td>SK Pertama dan SK Terakhir telah sesuai</td><td class="check">[ ] Sesuai &nbsp;&nbsp; [ ] Tidak Sesuai</td></tr>
            <tr><td class="number">6</td><td>Foto formal terbaru</td><td class="check">{{ $yesNo(filled($user->avatar)) }}</td></tr>
        </table>
        <h2>4. Checklist Persiapan GTK</h2>
        <table class="data">
            <tr><th class="number">Cek</th><th class="verification-label">Pemeriksaan</th><th class="verification-result">Hasil</th></tr>
            <tr><td class="number">{{ $hasIdentity ? '[x]' : '[ ]' }}</td><td>Memastikan nama, gelar, tempat/tanggal lahir, dan identitas sudah benar.</td><td></td></tr>
            <tr><td class="number">{{ $hasDocuments ? '[x]' : '[ ]' }}</td><td>Menyiapkan KTP asli, SK Pertama, dan SK Terakhir.</td><td></td></tr>
            <tr><td class="number">{{ $hasEmployment ? '[x]' : '[ ]' }}</td><td>Memastikan NUPTK, NIPM, Kartanu, TMT, pendidikan, dan program studi sesuai dokumen.</td><td></td></tr>
            <tr><td class="number">{{ $user->hasFaceEnrollment() ? '[x]' : '[ ]' }}</td><td>Hadir langsung untuk pemindaian wajah di hadapan petugas.</td><td></td></tr>
            <tr><td class="number">[ ]</td><td>Mengoreksi data yang tidak sesuai sebelum proses verifikasi dinyatakan selesai.</td><td></td></tr>
        </table>
        <h2>5. Keaktifan MGMP</h2>
        <table class="data">
            <tr><th class="number">No.</th><th class="verification-label">Keaktifan MGMP</th><th class="verification-result">Keterangan Pengisian</th></tr>
            <tr><td class="number">1</td><td>Nama MGMP</td><td>{{ $value($mgmpNames) }}</td></tr>
            <tr><td class="number">2</td><td>Status keaktifan</td><td>{{ filled($mgmpNames) ? 'Aktif' : '-' }}</td></tr>
            <tr><td class="number">3</td><td>Produk kerja kolaboratif</td><td class="wrap">{{ $value($data?->produk_kerja_kolaboratif) }}</td></tr>
        </table>
        <p class="note" style="margin-top:14px">Dokumen ini dibuat otomatis dari data Pendataan GTK NUIST.</p>
    @endif
@endfor
</body>
</html>
