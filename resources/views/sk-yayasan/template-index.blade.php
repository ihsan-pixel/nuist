@extends('layouts.master')

@section('title')Template SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Template SK Yayasan @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')

@php
    $defaultSkBody = <<<'HTML'
<style>
@page { margin: 14mm 18mm 14mm 18mm; }
.sk-full-document {
    color: #000;
    font-family: "Times New Roman", Times, serif;
    font-size: 13.5pt;
    line-height: 1.18;
}
.sk-letterhead {
    border-collapse: collapse;
    margin: 0 auto 4px auto;
    width: 96%;
}
.sk-letterhead td {
    vertical-align: middle;
}
.sk-logo-cell {
    padding: 7px 18px 7px 4px;
    width: 92px;
}
.sk-logo-box {
    border: none;
    color: #2f6f45;
    font-family: Arial, sans-serif;
    font-weight: 700;
    height: 60px;
    text-align: center;
    width: 72px;
}
.sk-logo-mark {
    background: #82c39a;
    color: #fff;
    font-size: 15px;
    height: 74px;
    letter-spacing: 3px;
    line-height: 1.15;
    padding-top: 12px;
}
.sk-logo-name {
    font-size: 15px;
    letter-spacing: 1px;
    padding-top: 5px;
}
.sk-org-title {
    color: #000;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 24pt;
    font-weight: 700;
    line-height: 1.14;
    padding: 4px 8px 0 8px;
}
.sk-org-meta {
    color: #000;
    font-family: Arial, sans-serif;
    font-size: 10.5pt;
    line-height: 1.25;
    padding: 6px 8px 0 8px;
}
.sk-green-line {
    background: linear-gradient(
        to bottom,
        #7ca77d 0,
        #7ca77d 1px,
        transparent 1px,
        transparent 3px,
        #7ca77d 3px,
        #7ca77d 7px,
        transparent 7px,
        transparent 9px,
        #7ca77d 9px,
        #7ca77d 10px
    );
    height: 10px;
    margin: 0 0 22px 0;
}
.sk-title {
    font-weight: 700;
    text-align: center;
    text-decoration: underline;
    text-transform: uppercase;
}
.sk-number {
    margin-bottom: 14px;
    text-align: center;
}
.sk-subject {
    font-weight: 700;
    margin: 0 0 10px 0;
}
.sk-table {
    border-collapse: collapse;
    width: 100%;
}
.sk-table td {
    padding: 0 5px 3px 0;
    vertical-align: top;
}
.sk-label {
    width: 145px;
}
.sk-colon {
    text-align: center;
    width: 20px;
}
.sk-decision {
    font-weight: 700;
    margin: 24px 0 8px 0;
    text-align: center;
}
.sk-person-table {
    border-collapse: collapse;
    margin: 7px 0 8px 25px;
    width: 88%;
}
.sk-person-table td {
    padding: 0 5px 2px 0;
    vertical-align: top;
}
.sk-person-no {
    width: 22px;
}
.sk-person-label {
    width: 230px;
}
.sk-signature {
    margin-left: auto;
    margin-top: 26px;
    width: 360px;
}
.sk-signature-name {
    font-weight: 700;
    margin-top: 68px;
    text-decoration: underline;
}
.sk-copy {
    margin-top: 48px;
}
.sk-copy-title {
    text-decoration: underline;
}
</style>
<div class="sk-full-document" data-sk-full-document="1">
    <table class="sk-letterhead">
        <tr>
            <td class="sk-logo-cell">
                <div class="sk-logo-box">
                    <div class="sk-logo-mark">LP<br>MA'ARIF<br>NU</div>
                    <div class="sk-logo-name">LP MA'ARIF NU</div>
                </div>
            </td>
            <td>
                <div class="sk-org-title">
                    PENGURUS WILAYAH NAHDLATUL ULAMA<br>
                    DAERAH ISTIMEWA YOGYAKARTA<br>
                    LEMBAGA PENDIDIKAN MA'ARIF
                </div>
                <div class="sk-org-meta">
                    Jl. Ibu Ruswo Nomor 60 Prawirodirjan, Gondomanan, Yogyakarta. 55121<br>
                    Website: https://lpmnudiy.id email: sekretariat@lpmnudiy.id
                </div>
            </td>
        </tr>
    </table>
    <div class="sk-green-line"></div>

    <div class="sk-title">SURAT KEPUTUSAN KETUA LP MA'ARIF NU PWNU DIY</div>
    <div class="sk-number">Nomor: @{{nomor_sk}}</div>

    <p class="sk-subject">Ketua Lembaga Pendidikan Ma'arif NU PWNU DIY</p>

    <table class="sk-table">
        <tr>
            <td class="sk-label">Menimbang</td>
            <td class="sk-colon">:</td>
            <td>Bahwa demi memantapkan pelaksanaan tugas guru dan tenaga kependidikan di @{{nama_sekolah}}, dipandang perlu mengatur perihal kepegawaian.</td>
        </tr>
        <tr>
            <td class="sk-label">Mengingat</td>
            <td class="sk-colon">:</td>
            <td>
                1. Permendiknas Nomor 16 tahun 2007;<br>
                2. Permendikbud Nomor 25 Tahun 2024;<br>
                3. Pedoman Penyelenggaraan Pendidikan LP Ma'arif NU PWNU DIY Tahun 2024;<br>
                4. Peraturan Kepegawaian LP Ma'arif NU PWNU DIY Tahun 2024.
            </td>
        </tr>
        <tr>
            <td class="sk-label">Memperhatikan</td>
            <td class="sk-colon">:</td>
            <td>Surat Permohonan Penerbitan dan Perpanjangan SK GTY, GTT, PTY dan PTT Kepala @{{nama_sekolah}}.</td>
        </tr>
    </table>

    <div class="sk-decision">MEMUTUSKAN</div>

    <table class="sk-table">
        <tr>
            <td class="sk-label">Menetapkan</td>
            <td class="sk-colon">:</td>
            <td></td>
        </tr>
        <tr>
            <td class="sk-label">Kesatu</td>
            <td class="sk-colon">:</td>
            <td>
                Guru Tetap Yayasan tersebut di bawah ini:
                <table class="sk-person-table">
                    <tr><td class="sk-person-no">1.</td><td class="sk-person-label">Nama</td><td class="sk-colon">:</td><td>@{{nama_pegawai}}</td></tr>
                    <tr><td>2.</td><td>Tempat, tanggal lahir</td><td class="sk-colon">:</td><td>@{{tempat_lahir}}, @{{tanggal_lahir}}</td></tr>
                    <tr><td>3.</td><td>NUPTK</td><td class="sk-colon">:</td><td>@{{nuptk}}</td></tr>
                    <tr><td>4.</td><td>Kartanu</td><td class="sk-colon">:</td><td>@{{nomor_kartanu}}</td></tr>
                    <tr><td>5.</td><td>NIP Ma'arif baru</td><td class="sk-colon">:</td><td>@{{nip_maarif}}</td></tr>
                    <tr><td>6.</td><td>TMT pertama</td><td class="sk-colon">:</td><td>@{{tmt_pertama}}</td></tr>
                    <tr><td>7.</td><td>Pendidikan, tahun lulus</td><td class="sk-colon">:</td><td>@{{pendidikan_terakhir}}, @{{tahun_lulus}}</td></tr>
                    <tr><td>8.</td><td>Program studi</td><td class="sk-colon">:</td><td>@{{program_studi}}</td></tr>
                    <tr><td>9.</td><td>Masa kerja</td><td class="sk-colon">:</td><td>@{{masa_kerja}}</td></tr>
                    <tr><td>10.</td><td>Penilaian perilaku kerja</td><td class="sk-colon">:</td><td>@{{penilaian_kinerja}}</td></tr>
                </table>
                diangkat kembali sebagai <strong>@{{status_kepegawaian}}</strong> tahun pelajaran @{{tahun_sk}}/@{{tahun_sk_berikutnya}}, mata pelajaran @{{mapel_tugas_yang_diampu}}; dan kepadanya diberikan Gaji Pokok serta tunjangan lain yang berlaku di @{{nama_sekolah}}.
            </td>
        </tr>
        <tr>
            <td class="sk-label">Kedua</td>
            <td class="sk-colon">:</td>
            <td>Keputusan ini berlaku terhitung mulai tanggal @{{tanggal_mulai}} sampai dengan @{{tanggal_selesai}}. Apabila di kemudian hari terdapat kekeliruan di dalamnya akan diadakan perbaikan dan perhitungan kembali sebagaimana mestinya.</td>
        </tr>
        <tr>
            <td class="sk-label">Ketiga</td>
            <td class="sk-colon">:</td>
            <td>Asli Surat Keputusan ini diberikan kepada yang bersangkutan.</td>
        </tr>
    </table>

    <div class="sk-signature">
        Ditetapkan di&nbsp;&nbsp;: Yogyakarta<br>
        Pada Tanggal&nbsp;&nbsp;: @{{tanggal_terbit}}<br><br>
        @{{jabatan_penandatangan}}<br>
        Ketua,
        <div class="sk-signature-name">@{{nama_penandatangan}}</div>
    </div>

    <div class="sk-copy">
        <div class="sk-copy-title">Tembusan Yth:</div>
        1. Kepala Dinas Pendidikan, Pemuda, dan Olahraga DIY<br>
        2. Kepala Balai Pendidikan Menengah Kabupaten Bantul<br>
        3. Arsip
    </div>
</div>
HTML;

    $samplePlaceholders = [
        '@{{nomor_sk}}' => '/SK.02/LPM.DIY/VII/2026',
        '@{{judul_sk}}' => 'SURAT KEPUTUSAN KETUA LP MA\'ARIF NU PWNU DIY',
        '@{{nama_yayasan}}' => 'Lembaga Pendidikan Ma\'arif NU PWNU DIY',
        '@{{alamat_yayasan}}' => 'Jl. Ibu Ruswo Nomor 60 Prawirodirjan, Gondomanan, Yogyakarta',
        '@{{nama_sekolah}}' => 'SMK Pembangunan Dlingo',
        '@{{nama_pegawai}}' => 'Ahmad Fathoni, S.Pd.',
        '@{{gelar}}' => 'S.Pd.',
        '@{{tempat_lahir}}' => 'Bantul',
        '@{{tanggal_lahir}}' => '12 Januari 1990',
        '@{{nip_maarif}}' => 'MIF.2026.001',
        '@{{nuptk}}' => '1234567890123456',
        '@{{nomor_kartanu}}' => 'NU.34.02.001',
        '@{{tmt_pertama}}' => '01 Juli 2020',
        '@{{masa_kerja}}' => '6 tahun',
        '@{{pendidikan_terakhir}}' => 'S1',
        '@{{tahun_lulus}}' => '2015',
        '@{{program_studi}}' => 'Pendidikan Teknik Informatika',
        '@{{mapel_tugas_yang_diampu}}' => 'XXX',
        '@{{penilaian_kinerja}}' => 'Baik',
        '@{{keterangan_sk_yayasan}}' => 'Perpanjangan SK',
        '@{{jabatan}}' => 'Guru',
        '@{{status_kepegawaian}}' => 'Guru Tetap Yayasan',
        '@{{tanggal_mulai}}' => '01 Juli 2026',
        '@{{tanggal_selesai}}' => '30 Juni 2027',
        '@{{tanggal_terbit}}' => '01 Juli 2026',
        '@{{tahun_sk}}' => '2026',
        '@{{tahun_sk_berikutnya}}' => '2027',
        '@{{nama_penandatangan}}' => 'Dr. Tadkiroatun Musfiroh, M. Hum.',
        '@{{jabatan_penandatangan}}' => 'Pengurus LP Ma\'arif NU PWNU DIY',
        '@{{catatan_pengajuan}}' => '-',
        '@{{catatan_penerbitan}}' => '-',
    ];

    $defaultTemplateConfig = [
        'baseFontSize' => 13.5,
        'logoImageData' => null,
        'orgTitleText' => "PENGURUS WILAYAH NAHDLATUL ULAMA\nDAERAH ISTIMEWA YOGYAKARTA\nLEMBAGA PENDIDIKAN MA'ARIF",
        'orgTitleFontSize' => 24,
        'orgMetaText' => "Jl. Ibu Ruswo Nomor 60 Prawirodirjan, Gondomanan, Yogyakarta. 55121\nWebsite: https://lpmnudiy.id email: sekretariat@lpmnudiy.id",
        'orgMetaFontSize' => 10.5,
        'documentTitleFontSize' => 14,
        'numberLabelText' => 'Nomor:',
        'numberFontSize' => 13.5,
        'subjectText' => "Ketua Lembaga Pendidikan Ma'arif NU PWNU DIY",
        'subjectFontSize' => 13.5,
        'menimbangLabelText' => 'Menimbang',
        'menimbangLabelFontSize' => 13.5,
        'menimbangContentText' => 'Bahwa demi memantapkan pelaksanaan tugas guru dan tenaga kependidikan di @{{nama_sekolah}}, dipandang perlu mengatur perihal kepegawaian.',
        'menimbangContentFontSize' => 13.5,
        'mengingatLabelText' => 'Mengingat',
        'mengingatLabelFontSize' => 13.5,
        'mengingat1Text' => '1. Permendiknas Nomor 16 tahun 2007;',
        'mengingat2Text' => '2. Permendikbud Nomor 25 Tahun 2024;',
        'mengingat3Text' => "3. Pedoman Penyelenggaraan Pendidikan LP Ma'arif NU PWNU DIY Tahun 2024;",
        'mengingat4Text' => "4. Peraturan Kepegawaian LP Ma'arif NU PWNU DIY Tahun 2024.",
        'mengingatContentFontSize' => 13.5,
        'memperhatikanLabelText' => 'Memperhatikan',
        'memperhatikanLabelFontSize' => 13.5,
        'memperhatikanContentText' => 'Surat Permohonan Penerbitan dan Perpanjangan SK GTY, GTT, PTY dan PTT Kepala @{{nama_sekolah}}.',
        'memperhatikanContentFontSize' => 13.5,
        'decisionText' => 'MEMUTUSKAN',
        'decisionFontSize' => 13.5,
        'menetapkanLabelText' => 'Menetapkan',
        'menetapkanLabelFontSize' => 13.5,
        'kesatuLabelText' => 'Kesatu',
        'kesatuLabelFontSize' => 13.5,
        'kesatuIntroText' => 'Guru Tetap Yayasan tersebut di bawah ini:',
        'kesatuIntroFontSize' => 13.5,
        'personRowFontSize' => 13.5,
        'person1LabelText' => 'Nama',
        'person1ValueText' => '@{{nama_pegawai}}',
        'person2LabelText' => 'Tempat, tanggal lahir',
        'person2ValueText' => '@{{tempat_lahir}}, @{{tanggal_lahir}}',
        'person3LabelText' => 'NUPTK',
        'person3ValueText' => '@{{nuptk}}',
        'person4LabelText' => 'Kartanu',
        'person4ValueText' => '@{{nomor_kartanu}}',
        'person5LabelText' => "NIP Ma'arif baru",
        'person5ValueText' => '@{{nip_maarif}}',
        'person6LabelText' => 'TMT pertama',
        'person6ValueText' => '@{{tmt_pertama}}',
        'person7LabelText' => 'Pendidikan, tahun lulus',
        'person7ValueText' => '@{{pendidikan_terakhir}}, @{{tahun_lulus}}',
        'person8LabelText' => 'Program studi',
        'person8ValueText' => '@{{program_studi}}',
        'person9LabelText' => 'Masa kerja',
        'person9ValueText' => '@{{masa_kerja}}',
        'person10LabelText' => 'Penilaian perilaku kerja',
        'person10ValueText' => '@{{penilaian_kinerja}}',
        'kesatuClosingText' => 'diangkat kembali sebagai @{{status_kepegawaian}} tahun pelajaran @{{tahun_sk}}/@{{tahun_sk_berikutnya}}, mata pelajaran @{{mapel_tugas_yang_diampu}}; dan kepadanya diberikan Gaji Pokok serta tunjangan lain yang berlaku di @{{nama_sekolah}}.',
        'kesatuClosingFontSize' => 13.5,
        'keduaLabelText' => 'Kedua',
        'keduaLabelFontSize' => 13.5,
        'keduaContentText' => 'Keputusan ini berlaku terhitung mulai tanggal @{{tanggal_mulai}} sampai dengan @{{tanggal_selesai}}. Apabila di kemudian hari terdapat kekeliruan di dalamnya akan diadakan perbaikan dan perhitungan kembali sebagaimana mestinya.',
        'keduaContentFontSize' => 13.5,
        'ketigaLabelText' => 'Ketiga',
        'ketigaLabelFontSize' => 13.5,
        'ketigaContentText' => 'Asli Surat Keputusan ini diberikan kepada yang bersangkutan.',
        'ketigaContentFontSize' => 13.5,
        'signatureFontSize' => 13.5,
        'signatureLocationLabelText' => 'Ditetapkan di',
        'signatureLocationValueText' => 'Yogyakarta',
        'signatureDateLabelText' => 'Pada Tanggal',
        'signatureRoleText' => '@{{jabatan_penandatangan}}',
        'signaturePrefixText' => 'Ketua,',
        'signatureNameText' => '@{{nama_penandatangan}}',
        'signatureNameFontSize' => 13.5,
        'copyTitleText' => 'Tembusan Yth:',
        'copyFontSize' => 13.5,
        'copy1Text' => '1. Kepala Dinas Pendidikan, Pemuda, dan Olahraga DIY',
        'copy2Text' => '2. Kepala Balai Pendidikan Menengah Kabupaten Bantul',
        'copy3Text' => '3. Arsip',
    ];

    $templateEditorGroups = [
        [
            'title' => 'Kop Surat & Logo',
            'fields' => [
                ['key' => 'logoImageData', 'label' => 'Logo PNG / JPG', 'type' => 'image'],
                ['key' => 'orgTitleText', 'label' => 'Judul Instansi', 'type' => 'textarea', 'rows' => 3, 'fontKey' => 'orgTitleFontSize'],
                ['key' => 'orgMetaText', 'label' => 'Alamat / Kontak', 'type' => 'textarea', 'rows' => 3, 'fontKey' => 'orgMetaFontSize'],
            ],
        ],
        [
            'title' => 'Judul Dokumen',
            'fields' => [
                ['key' => 'numberLabelText', 'label' => 'Label Nomor', 'type' => 'text', 'fontKey' => 'numberFontSize'],
                ['key' => 'subjectText', 'label' => 'Subjek Pembuka', 'type' => 'textarea', 'rows' => 2, 'fontKey' => 'subjectFontSize'],
            ],
        ],
        [
            'title' => 'Dasar SK',
            'fields' => [
                ['key' => 'menimbangLabelText', 'label' => 'Label Menimbang', 'type' => 'text', 'fontKey' => 'menimbangLabelFontSize'],
                ['key' => 'menimbangContentText', 'label' => 'Isi Menimbang', 'type' => 'textarea', 'rows' => 3, 'fontKey' => 'menimbangContentFontSize'],
                ['key' => 'mengingatLabelText', 'label' => 'Label Mengingat', 'type' => 'text', 'fontKey' => 'mengingatLabelFontSize'],
                ['key' => 'mengingat1Text', 'label' => 'Mengingat 1', 'type' => 'text', 'fontKey' => 'mengingatContentFontSize'],
                ['key' => 'mengingat2Text', 'label' => 'Mengingat 2', 'type' => 'text', 'fontKey' => 'mengingatContentFontSize'],
                ['key' => 'mengingat3Text', 'label' => 'Mengingat 3', 'type' => 'text', 'fontKey' => 'mengingatContentFontSize'],
                ['key' => 'mengingat4Text', 'label' => 'Mengingat 4', 'type' => 'text', 'fontKey' => 'mengingatContentFontSize'],
                ['key' => 'memperhatikanLabelText', 'label' => 'Label Memperhatikan', 'type' => 'text', 'fontKey' => 'memperhatikanLabelFontSize'],
                ['key' => 'memperhatikanContentText', 'label' => 'Isi Memperhatikan', 'type' => 'textarea', 'rows' => 3, 'fontKey' => 'memperhatikanContentFontSize'],
            ],
        ],
        [
            'title' => 'Isi Keputusan',
            'fields' => [
                ['key' => 'decisionText', 'label' => 'Judul Keputusan', 'type' => 'text', 'fontKey' => 'decisionFontSize'],
                ['key' => 'menetapkanLabelText', 'label' => 'Label Menetapkan', 'type' => 'text', 'fontKey' => 'menetapkanLabelFontSize'],
                ['key' => 'kesatuLabelText', 'label' => 'Label Kesatu', 'type' => 'text', 'fontKey' => 'kesatuLabelFontSize'],
                ['key' => 'kesatuIntroText', 'label' => 'Pembuka Kesatu', 'type' => 'textarea', 'rows' => 2, 'fontKey' => 'kesatuIntroFontSize'],
                ['key' => 'kesatuClosingText', 'label' => 'Penutup Kesatu', 'type' => 'textarea', 'rows' => 4, 'fontKey' => 'kesatuClosingFontSize'],
                ['key' => 'keduaLabelText', 'label' => 'Label Kedua', 'type' => 'text', 'fontKey' => 'keduaLabelFontSize'],
                ['key' => 'keduaContentText', 'label' => 'Isi Kedua', 'type' => 'textarea', 'rows' => 4, 'fontKey' => 'keduaContentFontSize'],
                ['key' => 'ketigaLabelText', 'label' => 'Label Ketiga', 'type' => 'text', 'fontKey' => 'ketigaLabelFontSize'],
                ['key' => 'ketigaContentText', 'label' => 'Isi Ketiga', 'type' => 'textarea', 'rows' => 2, 'fontKey' => 'ketigaContentFontSize'],
            ],
        ],
        [
            'title' => 'Data Pegawai',
            'fields' => [
                ['key' => 'person1LabelText', 'label' => 'Baris 1 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person1ValueText', 'label' => 'Baris 1 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person2LabelText', 'label' => 'Baris 2 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person2ValueText', 'label' => 'Baris 2 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person3LabelText', 'label' => 'Baris 3 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person3ValueText', 'label' => 'Baris 3 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person4LabelText', 'label' => 'Baris 4 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person4ValueText', 'label' => 'Baris 4 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person5LabelText', 'label' => 'Baris 5 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person5ValueText', 'label' => 'Baris 5 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person6LabelText', 'label' => 'Baris 6 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person6ValueText', 'label' => 'Baris 6 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person7LabelText', 'label' => 'Baris 7 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person7ValueText', 'label' => 'Baris 7 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person8LabelText', 'label' => 'Baris 8 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person8ValueText', 'label' => 'Baris 8 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person9LabelText', 'label' => 'Baris 9 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person9ValueText', 'label' => 'Baris 9 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person10LabelText', 'label' => 'Baris 10 Label', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
                ['key' => 'person10ValueText', 'label' => 'Baris 10 Isi', 'type' => 'text', 'fontKey' => 'personRowFontSize'],
            ],
        ],
        [
            'title' => 'Tanda Tangan & Tembusan',
            'fields' => [
                ['key' => 'signatureLocationLabelText', 'label' => 'Label Lokasi', 'type' => 'text', 'fontKey' => 'signatureFontSize'],
                ['key' => 'signatureLocationValueText', 'label' => 'Isi Lokasi', 'type' => 'text', 'fontKey' => 'signatureFontSize'],
                ['key' => 'signatureDateLabelText', 'label' => 'Label Tanggal', 'type' => 'text', 'fontKey' => 'signatureFontSize'],
                ['key' => 'signatureRoleText', 'label' => 'Jabatan Penandatangan', 'type' => 'text', 'fontKey' => 'signatureFontSize'],
                ['key' => 'signaturePrefixText', 'label' => 'Sapaan Penandatangan', 'type' => 'text', 'fontKey' => 'signatureFontSize'],
                ['key' => 'signatureNameText', 'label' => 'Nama Penandatangan', 'type' => 'text', 'fontKey' => 'signatureNameFontSize'],
                ['key' => 'copyTitleText', 'label' => 'Judul Tembusan', 'type' => 'text', 'fontKey' => 'copyFontSize'],
                ['key' => 'copy1Text', 'label' => 'Tembusan 1', 'type' => 'text', 'fontKey' => 'copyFontSize'],
                ['key' => 'copy2Text', 'label' => 'Tembusan 2', 'type' => 'text', 'fontKey' => 'copyFontSize'],
                ['key' => 'copy3Text', 'label' => 'Tembusan 3', 'type' => 'text', 'fontKey' => 'copyFontSize'],
            ],
        ],
    ];
@endphp

@push('css')
<style>
    .sk-editor-textarea {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        font-size: 12px;
        line-height: 1.45;
    }

    .sk-a4-note {
        color: #64748b;
        font-size: 12px;
    }

    .sk-structured-editor {
        border: 1px solid #d7dedb;
        border-radius: 14px;
        background: #fcfdfd;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .sk-structured-editor + .sk-structured-editor {
        margin-top: 10px;
    }

    .sk-structured-summary {
        align-items: center;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        list-style: none;
        padding: 14px 16px;
        user-select: none;
    }

    .sk-structured-summary::-webkit-details-marker {
        display: none;
    }

    .sk-structured-title {
        color: #0f172a;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        margin: 0;
        text-transform: uppercase;
    }

    .sk-structured-toggle {
        color: #64748b;
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
        transition: transform .18s ease;
    }

    .sk-structured-editor[open] .sk-structured-toggle {
        transform: rotate(45deg);
    }

    .sk-structured-body {
        border-top: 1px solid #e5e7eb;
        padding: 12px 16px 16px 16px;
    }

    .sk-structured-editor textarea {
        min-height: 78px;
        resize: vertical;
    }

    .sk-structured-grid {
        display: grid;
        gap: 10px 12px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sk-structured-field {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px;
    }

    .sk-structured-field .form-label {
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .sk-image-field-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .sk-image-preview-box {
        align-items: center;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        display: flex;
        height: 92px;
        justify-content: center;
        overflow: hidden;
        padding: 8px;
        width: 100%;
    }

    .sk-image-preview-box img {
        height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .sk-image-empty {
        color: #94a3b8;
        font-size: 12px;
        text-align: center;
    }

    .sk-font-input {
        max-width: 90px;
    }

    .sk-legacy-alert {
        font-size: 12px;
    }

    .sk-template-editor .form-label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .sk-template-editor .form-control,
    .sk-template-editor .form-select {
        border-radius: 10px;
        font-size: 13px;
        min-height: 40px;
        padding: .5rem .75rem;
    }

    .sk-template-editor textarea.form-control {
        min-height: 78px;
    }

    .sk-editor-raw {
        display: none;
    }

    .sk-preview-toolbar {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: space-between;
    }

    .sk-preview-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .sk-preview-card {
        position: sticky;
        top: 18px;
    }

    .sk-preview-shell {
        align-items: flex-start;
        background: linear-gradient(180deg, #eef2f7 0%, #e5e7eb 100%);
        border: 1px solid #d7dedb;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        max-height: 520px;
        overflow: auto;
        padding: 12px;
    }

    .sk-preview-page {
        align-items: flex-start;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
        margin: 0 auto;
        min-height: 160px;
        overflow: visible;
        padding: 0;
        width: 100%;
    }

    .sk-preview-canvas {
        background: #fff;
        box-shadow: 0 1px 8px rgba(16, 45, 40, .15);
        box-sizing: border-box;
        min-height: 297mm;
        padding: 14mm 18mm;
        transform: scale(var(--sk-preview-scale, 1));
        transform-origin: top center;
        width: 210mm;
    }

    @media (max-width: 991.98px) {
        .sk-structured-grid {
            grid-template-columns: 1fr;
        }

        .sk-preview-card {
            position: static;
        }

        .sk-preview-shell {
            max-height: 460px;
        }
    }
</style>
@endpush

<div class="sky-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Template SK Yayasan</div>
                <h4 class="mb-1">Kelola format dan placeholder dokumen SK</h4>
                <p class="mb-0 text-white-50">
                    Siapkan template yang fleksibel untuk guru dan pegawai agar proses generate SK konsisten dan cepat.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $templates->count() }} template</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $templates->where('is_active', true)->count() }} aktif</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Form Template</div>
                    <h6 class="mb-3">Tambah Template Baru</h6>

                    <form action="{{ route('sk-yayasan.template.store') }}" method="POST" class="sk-template-editor" data-preview-label="Template baru" data-sk-legacy-notice="off">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Template</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', 'guru') }}" placeholder="Contoh: guru, pegawai, umum">
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Judul Dokumen</label>
                                <input type="text" name="document_title" class="form-control" value="{{ old('document_title', "SURAT KEPUTUSAN KETUA LP MA'ARIF NU PWNU DIY") }}" data-sk-preview-title required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Font Judul</label>
                                <input type="number" step="0.1" min="8" class="form-control" value="{{ $defaultTemplateConfig['documentTitleFontSize'] }}" data-sk-config-key="documentTitleFontSize">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Format Nomor SK</label>
                            <input type="text" name="document_number_format" class="form-control" value="{{ old('document_number_format', '{seq}/SK.02/LPM.DIY/{month_roman}/{year}') }}" placeholder="{seq}/SK.02/LPM.DIY/{month_roman}/{year}" data-sk-preview-number-format>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Isi Template</label>
                            <div data-sk-legacy-alert class="alert alert-warning py-2 px-3 small sk-legacy-alert d-none mb-3">
                                Template lama belum memakai editor terstruktur. Setelah disimpan ulang, template ini akan mengikuti format editor teks baru.
                            </div>
                            <div data-sk-structured-fields></div>
                            <textarea name="body" rows="24" class="form-control sk-editor-textarea sk-editor-raw" data-sk-preview-body required>{{ old('body', $defaultSkBody) }}</textarea>
                            <small class="sk-a4-note d-block mt-2">HTML tidak perlu diedit lagi. Cukup ubah teks dan ukuran font di tiap bagian, preview kanan langsung mengikuti format A4.</small>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Aktifkan template</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Template</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Placeholder Tersedia</div>
                    <h6 class="mb-3">Field yang dapat dipakai di body template</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach([
                            '@{{nomor_sk}}', '@{{judul_sk}}', '@{{nama_yayasan}}', '@{{nama_sekolah}}', '@{{nama_pegawai}}',
                            '@{{alamat_yayasan}}', '@{{gelar}}', '@{{tempat_lahir}}', '@{{tanggal_lahir}}', '@{{nip_maarif}}', '@{{nuptk}}',
                            '@{{nomor_kartanu}}', '@{{tmt_pertama}}', '@{{masa_kerja}}', '@{{pendidikan_terakhir}}',
                            '@{{tahun_lulus}}', '@{{program_studi}}', '@{{mapel_tugas_yang_diampu}}', '@{{penilaian_kinerja}}',
                            '@{{keterangan_sk_yayasan}}', '@{{jabatan}}', '@{{status_kepegawaian}}', '@{{tanggal_terbit}}',
                            '@{{tanggal_mulai}}', '@{{tanggal_selesai}}', '@{{tahun_sk}}', '@{{tahun_sk_berikutnya}}',
                            '@{{nama_penandatangan}}', '@{{jabatan_penandatangan}}', '@{{catatan_pengajuan}}', '@{{catatan_penerbitan}}'
                        ] as $placeholder)
                            <span class="sky-chip">{{ $placeholder }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card sk-preview-card">
                <div class="card-body">
                    <div class="sk-preview-toolbar mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Preview Template</div>
                            <h6 class="mb-0">Preview ringkas A4</h6>
                        </div>
                        <div class="sk-preview-actions">
                            <span class="sky-chip" id="sk-preview-source-label">Template baru</span>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="sk-preview-pdf-button">
                                Preview PDF
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-info py-2 px-3 small">
                        Preview diperkecil agar fokus edit lebih nyaman. Hasil PDF tetap memakai format A4 penuh.
                    </div>
                    <div class="sk-preview-shell">
                        <div class="sk-preview-page" id="sk-template-preview"></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Daftar Template</div>
                            <h6 class="mb-0">Template aktif dan nonaktif</h6>
                        </div>
                        <span class="sky-chip">{{ $templates->count() }} data</span>
                    </div>

                    @if($templates->isNotEmpty())
                        <div class="accordion" id="templateAccordion">
                            @foreach($templates as $template)
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="heading{{ $template->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $template->id }}">
                                            <div class="w-100 d-flex justify-content-between align-items-center me-3">
                                                <div>
                                                    <div class="fw-semibold">{{ $template->name }}</div>
                                                    <small class="text-muted">{{ $template->document_title }}</small>
                                                </div>
                                                <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }}-subtle text-{{ $template->is_active ? 'success' : 'secondary' }}">
                                                    {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $template->id }}" class="accordion-collapse collapse" data-bs-parent="#templateAccordion">
                                        <div class="accordion-body">
                                            <form action="{{ route('sk-yayasan.template.update', $template) }}" method="POST" class="mb-3 sk-template-editor" data-preview-label="{{ $template->name }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Template</label>
                                                    <input type="text" name="name" value="{{ $template->name }}" class="form-control" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Kategori</label>
                                                        <input type="text" name="category" value="{{ $template->category }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-5 mb-3">
                                                        <label class="form-label">Judul Dokumen</label>
                                                        <input type="text" name="document_title" value="{{ $template->document_title }}" class="form-control" data-sk-preview-title required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Font Judul</label>
                                                        <input type="number" step="0.1" min="8" class="form-control" value="{{ $defaultTemplateConfig['documentTitleFontSize'] }}" data-sk-config-key="documentTitleFontSize">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Format Nomor</label>
                                                    <input type="text" name="document_number_format" value="{{ $template->document_number_format }}" class="form-control" data-sk-preview-number-format>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="description" rows="2" class="form-control">{{ $template->description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Isi Template</label>
                                                    <div data-sk-legacy-alert class="alert alert-warning py-2 px-3 small sk-legacy-alert d-none mb-3">
                                                        Template lama belum memakai editor terstruktur. Setelah disimpan ulang, template ini akan mengikuti format editor teks baru.
                                                    </div>
                                                    <div data-sk-structured-fields></div>
                                                    <textarea name="body" rows="14" class="form-control sk-editor-textarea sk-editor-raw" data-sk-preview-body required>{{ $template->body }}</textarea>
                                                    <small class="sk-a4-note d-block mt-2">Cukup edit teks dan ukuran font per bagian, lalu preview A4 di kanan akan ter-update otomatis.</small>
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($template->is_active)>
                                                    <label class="form-check-label">Aktifkan template</label>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('sk-yayasan.template.destroy', $template) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-notepad"></i>
                            <strong>Belum ada template SK Yayasan</strong>
                            <small>Tambahkan template pertama agar proses generate SK bisa berjalan.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    (function () {
        const preview = document.getElementById('sk-template-preview');
        const previewShell = preview?.closest('.sk-preview-shell');
        const sourceLabel = document.getElementById('sk-preview-source-label');
        const previewPdfButton = document.getElementById('sk-preview-pdf-button');
        const samplePlaceholders = @json($samplePlaceholders);
        const defaultTemplateConfig = @json($defaultTemplateConfig);
        const templateEditorGroups = @json($templateEditorGroups);
        const metaPrefix = '<!--SK_TEMPLATE_META:';
        const metaSuffix = '-->';
        let currentPdfPreviewUrl = null;
        const romanMonths = {
            '01': 'I',
            '02': 'II',
            '03': 'III',
            '04': 'IV',
            '05': 'V',
            '06': 'VI',
            '07': 'VII',
            '08': 'VIII',
            '09': 'IX',
            '10': 'X',
            '11': 'XI',
            '12': 'XII',
        };

        function formatDocumentNumber(format) {
            const selectedFormat = format || '{seq}/SK.02/LPM.DIY/{month_roman}/{year}';

            return selectedFormat
                .replaceAll('{seq}', '001')
                .replaceAll('{school_code}', 'SMK-DLINGO')
                .replaceAll('{month}', '07')
                .replaceAll('{month_roman}', romanMonths['07'])
                .replaceAll('{year}', '2026');
        }

        function legacyWrapper(renderedBody, editor, placeholders) {
            const title = editor.querySelector('[data-sk-preview-title]')?.value || placeholders['@{{judul_sk}}'];

            return `
                <div style="font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #111827;">
                    <div style="text-align:center; margin-bottom:24px;">
                        <h2 style="margin:0;">${title}</h2>
                        <div>Nomor: ${placeholders['@{{nomor_sk}}']}</div>
                    </div>
                    <div style="margin-bottom:18px;">
                        <div>Nama Sekolah: ${placeholders['@{{nama_sekolah}}']}</div>
                        <div>Nama Pegawai/Guru: ${placeholders['@{{nama_pegawai}}']}</div>
                        <div>Status Kepegawaian: ${placeholders['@{{status_kepegawaian}}']}</div>
                    </div>
                    <div style="text-align:justify;">${renderedBody}</div>
                    <div style="margin-top:40px; margin-left:auto; width:260px; text-align:left;">
                        <div>${placeholders['@{{tanggal_terbit}}']}</div>
                        <div>${placeholders['@{{jabatan_penandatangan}}']}</div>
                        <br><br><br>
                        <div><strong>${placeholders['@{{nama_penandatangan}}']}</strong></div>
                    </div>
                </div>
            `;
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function nl2br(value) {
            return escapeHtml(value).replace(/\n/g, '<br>');
        }

        function safeFontSize(value, fallback = 13.5) {
            const parsed = Number.parseFloat(value);
            return Number.isFinite(parsed) && parsed > 0 ? parsed : fallback;
        }

        function encodeMeta(config) {
            return btoa(unescape(encodeURIComponent(JSON.stringify(config))));
        }

        function decodeMeta(value) {
            try {
                return JSON.parse(decodeURIComponent(escape(atob(value))));
            } catch (error) {
                return null;
            }
        }

        function extractTemplateConfig(body) {
            const matcher = (body || '').match(/<!--SK_TEMPLATE_META:([\s\S]*?)-->/);

            if (!matcher) {
                return {
                    config: { ...defaultTemplateConfig },
                    isLegacy: true,
                };
            }

            const parsed = decodeMeta(matcher[1]?.trim());

            return {
                config: {
                    ...defaultTemplateConfig,
                    ...(parsed || {}),
                },
                isLegacy: false,
            };
        }

        function buildStructuredHtml(config, title, numberText) {
            const logoMarkup = config.logoImageData
                ? `<img src="${escapeHtml(config.logoImageData)}" alt="Logo Yayasan" style="height:96px; max-width:170px; object-fit:contain;">`
                : `<div style="color:#94a3b8; font-family:Arial,sans-serif; font-size:12px; padding-top:38px;">Logo</div>`;
            const personRows = Array.from({ length: 10 }, (_, index) => {
                const rowNumber = index + 1;

                return `
                    <tr>
                        <td class="sk-person-no">${rowNumber}.</td>
                        <td class="sk-person-label">${escapeHtml(config[`person${rowNumber}LabelText`])}</td>
                        <td class="sk-colon">:</td>
                        <td>${nl2br(config[`person${rowNumber}ValueText`])}</td>
                    </tr>
                `;
            }).join('');

            const mengingatItems = [1, 2, 3, 4]
                .map((index) => config[`mengingat${index}Text`])
                .filter(Boolean)
                .map((item) => nl2br(item))
                .join('<br>');

            const copyItems = [1, 2, 3]
                .map((index) => config[`copy${index}Text`])
                .filter(Boolean)
                .map((item) => `${nl2br(item)}<br>`)
                .join('');

            return `
<style>
@page { margin: 14mm 18mm 14mm 18mm; }
.sk-full-document {
    color: #000;
    font-family: "Times New Roman", Times, serif;
    font-size: ${safeFontSize(config.baseFontSize)}pt;
    line-height: 1.18;
}
.sk-letterhead {
    border-collapse: collapse;
    margin: 0 auto 4px auto;
    width: 96%;
}
.sk-letterhead td { vertical-align: middle; }
.sk-logo-cell { padding: 7px 18px 7px 4px; width: 92px; }
.sk-logo-box {
    align-items: center;
    border: none;
    color: #2f6f45;
    display: flex;
    font-family: Arial, sans-serif;
    font-weight: 700;
    height: 60px;
    justify-content: center;
    text-align: center;
    width: 72px;
}
.sk-org-title {
    color: #000;
    font-family: Georgia, "Times New Roman", serif;
    font-weight: 700;
    line-height: 1.14;
    padding: 4px 8px 0 8px;
}
.sk-org-meta {
    color: #000;
    font-family: Arial, sans-serif;
    line-height: 1.25;
    padding: 6px 8px 0 8px;
}
.sk-green-line {
    background: linear-gradient(
        to bottom,
        #7ca77d 0,
        #7ca77d 1px,
        transparent 1px,
        transparent 3px,
        #7ca77d 3px,
        #7ca77d 7px,
        transparent 7px,
        transparent 9px,
        #7ca77d 9px,
        #7ca77d 10px
    );
    height: 10px;
    margin: 0 0 22px 0;
}
.sk-title {
    font-weight: 700;
    text-align: center;
    text-decoration: underline;
    text-transform: uppercase;
}
.sk-number { margin-bottom: 14px; text-align: center; }
.sk-subject { font-weight: 700; margin: 0 0 10px 0; }
.sk-table, .sk-person-table { border-collapse: collapse; width: 100%; }
.sk-table td { padding: 0 5px 3px 0; vertical-align: top; }
.sk-label { width: 145px; }
.sk-colon { text-align: center; width: 20px; }
.sk-decision { font-weight: 700; margin: 24px 0 8px 0; text-align: center; }
.sk-person-table { margin: 7px 0 8px 25px; width: 88%; }
.sk-person-table td { padding: 0 5px 2px 0; vertical-align: top; }
.sk-person-no { width: 22px; }
.sk-person-label { width: 230px; }
.sk-signature { margin-left: auto; margin-top: 26px; width: 360px; }
.sk-signature-name { font-weight: 700; margin-top: 68px; text-decoration: underline; }
.sk-copy { margin-top: 48px; }
.sk-copy-title { text-decoration: underline; }
</style>
<div class="sk-full-document" data-sk-full-document="1">
    <table class="sk-letterhead">
        <tr>
            <td class="sk-logo-cell">
                <div class="sk-logo-box">
                    ${logoMarkup}
                </div>
            </td>
            <td>
                <div class="sk-org-title" style="font-size:${safeFontSize(config.orgTitleFontSize)}pt;">${nl2br(config.orgTitleText)}</div>
                <div class="sk-org-meta" style="font-size:${safeFontSize(config.orgMetaFontSize)}pt;">${nl2br(config.orgMetaText)}</div>
            </td>
        </tr>
    </table>
    <div class="sk-green-line"></div>

    <div class="sk-title" style="font-size:${safeFontSize(config.documentTitleFontSize)}pt;">${escapeHtml(title)}</div>
    <div class="sk-number" style="font-size:${safeFontSize(config.numberFontSize)}pt;">${escapeHtml(config.numberLabelText)} ${escapeHtml(numberText)}</div>

    <p class="sk-subject" style="font-size:${safeFontSize(config.subjectFontSize)}pt;">${nl2br(config.subjectText)}</p>

    <table class="sk-table">
        <tr>
            <td class="sk-label" style="font-size:${safeFontSize(config.menimbangLabelFontSize)}pt;">${escapeHtml(config.menimbangLabelText)}</td>
            <td class="sk-colon" style="font-size:${safeFontSize(config.menimbangLabelFontSize)}pt;">:</td>
            <td style="font-size:${safeFontSize(config.menimbangContentFontSize)}pt;">${nl2br(config.menimbangContentText)}</td>
        </tr>
        <tr>
            <td class="sk-label" style="font-size:${safeFontSize(config.mengingatLabelFontSize)}pt;">${escapeHtml(config.mengingatLabelText)}</td>
            <td class="sk-colon" style="font-size:${safeFontSize(config.mengingatLabelFontSize)}pt;">:</td>
            <td style="font-size:${safeFontSize(config.mengingatContentFontSize)}pt;">${mengingatItems}</td>
        </tr>
        <tr>
            <td class="sk-label" style="font-size:${safeFontSize(config.memperhatikanLabelFontSize)}pt;">${escapeHtml(config.memperhatikanLabelText)}</td>
            <td class="sk-colon" style="font-size:${safeFontSize(config.memperhatikanLabelFontSize)}pt;">:</td>
            <td style="font-size:${safeFontSize(config.memperhatikanContentFontSize)}pt;">${nl2br(config.memperhatikanContentText)}</td>
        </tr>
    </table>

    <div class="sk-decision" style="font-size:${safeFontSize(config.decisionFontSize)}pt;">${escapeHtml(config.decisionText)}</div>

    <table class="sk-table">
        <tr>
            <td class="sk-label" style="font-size:${safeFontSize(config.menetapkanLabelFontSize)}pt;">${escapeHtml(config.menetapkanLabelText)}</td>
            <td class="sk-colon" style="font-size:${safeFontSize(config.menetapkanLabelFontSize)}pt;">:</td>
            <td></td>
        </tr>
        <tr>
            <td class="sk-label" style="font-size:${safeFontSize(config.kesatuLabelFontSize)}pt;">${escapeHtml(config.kesatuLabelText)}</td>
            <td class="sk-colon" style="font-size:${safeFontSize(config.kesatuLabelFontSize)}pt;">:</td>
            <td style="font-size:${safeFontSize(config.kesatuIntroFontSize)}pt;">
                ${nl2br(config.kesatuIntroText)}
                <table class="sk-person-table" style="font-size:${safeFontSize(config.personRowFontSize)}pt;">${personRows}</table>
                ${nl2br(config.kesatuClosingText)}
            </td>
        </tr>
        <tr>
            <td class="sk-label" style="font-size:${safeFontSize(config.keduaLabelFontSize)}pt;">${escapeHtml(config.keduaLabelText)}</td>
            <td class="sk-colon" style="font-size:${safeFontSize(config.keduaLabelFontSize)}pt;">:</td>
            <td style="font-size:${safeFontSize(config.keduaContentFontSize)}pt;">${nl2br(config.keduaContentText)}</td>
        </tr>
        <tr>
            <td class="sk-label" style="font-size:${safeFontSize(config.ketigaLabelFontSize)}pt;">${escapeHtml(config.ketigaLabelText)}</td>
            <td class="sk-colon" style="font-size:${safeFontSize(config.ketigaLabelFontSize)}pt;">:</td>
            <td style="font-size:${safeFontSize(config.ketigaContentFontSize)}pt;">${nl2br(config.ketigaContentText)}</td>
        </tr>
    </table>

    <div class="sk-signature" style="font-size:${safeFontSize(config.signatureFontSize)}pt;">
        ${escapeHtml(config.signatureLocationLabelText)}&nbsp;&nbsp;: ${nl2br(config.signatureLocationValueText)}<br>
        ${escapeHtml(config.signatureDateLabelText)}&nbsp;&nbsp;: @{{tanggal_terbit}}<br><br>
        ${nl2br(config.signatureRoleText)}<br>
        ${nl2br(config.signaturePrefixText)}
        <div class="sk-signature-name" style="font-size:${safeFontSize(config.signatureNameFontSize)}pt;">${nl2br(config.signatureNameText)}</div>
    </div>

    <div class="sk-copy" style="font-size:${safeFontSize(config.copyFontSize)}pt;">
        <div class="sk-copy-title">${nl2br(config.copyTitleText)}</div>
        ${copyItems}
    </div>
</div>`.trim();
        }

        function syncStructuredBody(editor) {
            const bodyInput = editor.querySelector('[data-sk-preview-body]');
            const titleInput = editor.querySelector('[data-sk-preview-title]');
            const numberFormatInput = editor.querySelector('[data-sk-preview-number-format]');
            const config = { ...defaultTemplateConfig };

            editor.querySelectorAll('[data-sk-config-key]').forEach((input) => {
                config[input.dataset.skConfigKey] = input.type === 'number'
                    ? safeFontSize(input.value, defaultTemplateConfig[input.dataset.skConfigKey] ?? 13.5)
                    : input.value;
            });

            const rendered = buildStructuredHtml(
                config,
                titleInput?.value || samplePlaceholders['@{{judul_sk}}'],
                `${config.numberLabelText} ${formatDocumentNumber(numberFormatInput?.value)}`.replace(`${config.numberLabelText} `, '')
            );

            bodyInput.value = `${metaPrefix}${encodeMeta(config)}${metaSuffix}\n${rendered}`;
        }

        function imageFieldControl(field, value) {
            const preview = value
                ? `<img src="${escapeHtml(value)}" alt="${escapeHtml(field.label)}">`
                : `<div class="sk-image-empty">Belum ada logo<br>Upload PNG atau JPG</div>`;

            return `
                <div class="sk-structured-field">
                    <label class="form-label">${field.label}</label>
                    <div class="sk-image-field-actions">
                        <input type="hidden" value="${escapeHtml(value || '')}" data-sk-config-key="${field.key}">
                        <input type="file" class="form-control" accept="image/png,image/jpeg,.png,.jpg,.jpeg" data-sk-image-input="${field.key}">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-sk-image-clear="${field.key}">Hapus</button>
                    </div>
                    <div class="sk-image-preview-box mt-2" data-sk-image-preview="${field.key}">
                        ${preview}
                    </div>
                </div>
            `;
        }

        function fieldControl(field, value, fontValue) {
            if (field.type === 'image') {
                return imageFieldControl(field, value);
            }

            const inputControl = field.type === 'textarea'
                ? `<textarea class="form-control" rows="${field.rows || 2}" data-sk-config-key="${field.key}">${escapeHtml(value)}</textarea>`
                : `<input type="text" class="form-control" value="${escapeHtml(value)}" data-sk-config-key="${field.key}">`;

            return `
                <div class="sk-structured-field">
                    <label class="form-label">${field.label}</label>
                    <div class="row g-3 align-items-start">
                        <div class="col-lg-9">${inputControl}</div>
                        <div class="col-lg-3">
                            <label class="form-label">Font</label>
                            <input type="number" step="0.1" min="8" class="form-control sk-font-input" value="${escapeHtml(fontValue)}" data-sk-config-key="${field.fontKey}">
                        </div>
                    </div>
                </div>
            `;
        }

        function renderStructuredFields(editor, config, isLegacy) {
            const container = editor.querySelector('[data-sk-structured-fields]');
            const legacyAlert = editor.querySelector('[data-sk-legacy-alert]');

            if (!container) {
                return;
            }

            if (legacyAlert) {
                const shouldShowLegacy = isLegacy && editor.dataset.skLegacyNotice !== 'off';
                legacyAlert.classList.toggle('d-none', !shouldShowLegacy);
            }

            container.innerHTML = templateEditorGroups.map((group) => `
                <details class="sk-structured-editor mb-3" ${group === templateEditorGroups[0] ? 'open' : ''}>
                    <summary class="sk-structured-summary">
                        <span class="sk-structured-title">${group.title}</span>
                        <span class="sk-structured-toggle">+</span>
                    </summary>
                    <div class="sk-structured-body">
                        <div class="sk-structured-grid">
                            ${group.fields.map((field) => fieldControl(field, config[field.key] ?? '', config[field.fontKey] ?? 13.5)).join('')}
                        </div>
                    </div>
                </details>
            `).join('');

            container.querySelectorAll('[data-sk-config-key]').forEach((input) => {
                input.addEventListener('input', () => {
                    syncStructuredBody(editor);
                    renderPreview(editor);
                });
                input.addEventListener('focus', () => renderPreview(editor));
            });

            container.querySelectorAll('[data-sk-image-input]').forEach((input) => {
                input.addEventListener('change', (event) => {
                    const file = event.target.files?.[0];
                    const key = event.target.dataset.skImageInput;
                    const hiddenInput = container.querySelector(`[data-sk-config-key="${key}"]`);
                    const previewBox = container.querySelector(`[data-sk-image-preview="${key}"]`);

                    if (!file || !hiddenInput || !previewBox) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = () => {
                        hiddenInput.value = String(reader.result || '');
                        previewBox.innerHTML = `<img src="${escapeHtml(hiddenInput.value)}" alt="Logo preview">`;
                        syncStructuredBody(editor);
                        renderPreview(editor);
                    };
                    reader.readAsDataURL(file);
                });
            });

            container.querySelectorAll('[data-sk-image-clear]').forEach((button) => {
                button.addEventListener('click', () => {
                    const key = button.dataset.skImageClear;
                    const hiddenInput = container.querySelector(`[data-sk-config-key="${key}"]`);
                    const previewBox = container.querySelector(`[data-sk-image-preview="${key}"]`);
                    const fileInput = container.querySelector(`[data-sk-image-input="${key}"]`);

                    if (!hiddenInput || !previewBox) {
                        return;
                    }

                    hiddenInput.value = '';
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    previewBox.innerHTML = '<div class="sk-image-empty">Belum ada logo<br>Upload PNG atau JPG</div>';
                    syncStructuredBody(editor);
                    renderPreview(editor);
                });
            });
        }

        function hydrateStandaloneConfigInputs(editor, config) {
            editor.querySelectorAll('[data-sk-config-key]').forEach((input) => {
                const key = input.dataset.skConfigKey;

                if (Object.prototype.hasOwnProperty.call(config, key)) {
                    input.value = config[key];
                }
            });
        }

        function renderPreview(editor) {
            if (!editor || !preview) {
                return;
            }

            const bodyInput = editor.querySelector('[data-sk-preview-body]');
            const titleInput = editor.querySelector('[data-sk-preview-title]');
            const numberFormatInput = editor.querySelector('[data-sk-preview-number-format]');
            const placeholders = {
                ...samplePlaceholders,
                '@{{judul_sk}}': titleInput?.value || samplePlaceholders['@{{judul_sk}}'],
                '@{{nomor_sk}}': formatDocumentNumber(numberFormatInput?.value),
            };

            let rendered = bodyInput?.value || '';
            Object.entries(placeholders).forEach(([placeholder, value]) => {
                rendered = rendered.split(placeholder).join(value ?? '');
            });

            if (!rendered.includes('data-sk-full-document="1"')) {
                rendered = legacyWrapper(rendered, editor, placeholders);
            }

            preview.innerHTML = `<div class="sk-preview-canvas">${rendered}</div>`;
            updatePreviewScale();

            if (sourceLabel) {
                sourceLabel.textContent = editor.dataset.previewLabel || 'Template';
            }
        }

        function updatePreviewScale() {
            if (!preview || !previewShell) {
                return;
            }

            const canvas = preview.querySelector('.sk-preview-canvas');

            if (!canvas) {
                return;
            }

            const naturalWidth = canvas.offsetWidth;
            const naturalHeight = canvas.offsetHeight;
            const availableWidth = Math.max(previewShell.clientWidth - 24, 240);
            const scale = Math.min(1, availableWidth / naturalWidth);

            preview.style.setProperty('--sk-preview-scale', scale.toFixed(4));
            preview.style.width = `${naturalWidth * scale}px`;
            preview.style.minHeight = `${naturalHeight * scale}px`;
        }

        function openPdfPreview() {
            if (!preview) {
                return;
            }

            const canvas = preview.querySelector('.sk-preview-canvas');

            if (!canvas) {
                return;
            }

            const html = `
                <!doctype html>
                <html lang="id">
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>Preview PDF SK Yayasan</title>
                    <style>
                        body {
                            margin: 0;
                            background: #f1f5f9;
                            font-family: Arial, sans-serif;
                            padding: 24px;
                        }
                        .pdf-preview-sheet {
                            background: #fff;
                            box-shadow: 0 10px 30px rgba(15, 23, 42, .12);
                            margin: 0 auto;
                            min-height: 297mm;
                            width: 210mm;
                        }
                        @media print {
                            body {
                                background: #fff;
                                padding: 0;
                            }
                            .pdf-preview-sheet {
                                box-shadow: none;
                                margin: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="pdf-preview-sheet">${canvas.innerHTML}</div>
                </body>
                </html>
            `;

            if (currentPdfPreviewUrl) {
                URL.revokeObjectURL(currentPdfPreviewUrl);
            }

            const blob = new Blob([html], { type: 'text/html' });
            currentPdfPreviewUrl = URL.createObjectURL(blob);

            const previewWindow = window.open(currentPdfPreviewUrl, '_blank');

            if (!previewWindow) {
                const anchor = document.createElement('a');
                anchor.href = currentPdfPreviewUrl;
                anchor.target = '_blank';
                anchor.rel = 'noopener noreferrer';
                anchor.click();
            }
        }

        const editors = document.querySelectorAll('.sk-template-editor');

        editors.forEach((editor) => {
            const { config, isLegacy } = extractTemplateConfig(editor.querySelector('[data-sk-preview-body]')?.value || '');
            hydrateStandaloneConfigInputs(editor, config);
            renderStructuredFields(editor, config, isLegacy);
            syncStructuredBody(editor);
            editor.addEventListener('input', (event) => {
                if (!event.target.matches('[data-sk-config-key]')) {
                    syncStructuredBody(editor);
                    renderPreview(editor);
                }
            });
            editor.addEventListener('focusin', () => renderPreview(editor));
        });

        renderPreview(editors[0]);
        window.addEventListener('resize', updatePreviewScale);
        previewPdfButton?.addEventListener('click', openPdfPreview);
    })();
</script>
@endsection
