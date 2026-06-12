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
    border: 1px dashed #777;
    margin: 0 auto 4px auto;
    width: 96%;
}
.sk-letterhead td {
    vertical-align: middle;
}
.sk-logo-cell {
    padding: 7px 10px;
    width: 205px;
}
.sk-logo-box {
    border: 1px solid #c9d9d7;
    color: #70ae7b;
    font-family: Arial, sans-serif;
    font-weight: 700;
    height: 102px;
    text-align: center;
    width: 190px;
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
    color: #777;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 24pt;
    font-weight: 700;
    line-height: 1.14;
    padding: 4px 8px 0 8px;
}
.sk-org-meta {
    color: #777;
    font-family: Arial, sans-serif;
    font-size: 10.5pt;
    line-height: 1.25;
    padding: 6px 8px 0 8px;
}
.sk-green-line {
    border-top: 4px double #7ca77d;
    height: 4px;
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
    <div class="sk-number">Nomor: {{nomor_sk}}</div>

    <p class="sk-subject">Ketua Lembaga Pendidikan Ma'arif NU PWNU DIY</p>

    <table class="sk-table">
        <tr>
            <td class="sk-label">Menimbang</td>
            <td class="sk-colon">:</td>
            <td>Bahwa demi memantapkan pelaksanaan tugas guru dan tenaga kependidikan di {{nama_sekolah}}, dipandang perlu mengatur perihal kepegawaian.</td>
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
            <td>Surat Permohonan Penerbitan dan Perpanjangan SK GTY, GTT, PTY dan PTT Kepala {{nama_sekolah}}.</td>
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
                    <tr><td class="sk-person-no">1.</td><td class="sk-person-label">Nama</td><td class="sk-colon">:</td><td>{{nama_pegawai}}</td></tr>
                    <tr><td>2.</td><td>Tempat, tanggal lahir</td><td class="sk-colon">:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>
                    <tr><td>3.</td><td>NUPTK</td><td class="sk-colon">:</td><td>{{nuptk}}</td></tr>
                    <tr><td>4.</td><td>Kartanu</td><td class="sk-colon">:</td><td>{{nomor_kartanu}}</td></tr>
                    <tr><td>5.</td><td>NIP Ma'arif baru</td><td class="sk-colon">:</td><td>{{nip_maarif}}</td></tr>
                    <tr><td>6.</td><td>TMT pertama</td><td class="sk-colon">:</td><td>{{tmt_pertama}}</td></tr>
                    <tr><td>7.</td><td>Pendidikan, tahun lulus</td><td class="sk-colon">:</td><td>{{pendidikan_terakhir}}, {{tahun_lulus}}</td></tr>
                    <tr><td>8.</td><td>Program studi</td><td class="sk-colon">:</td><td>{{program_studi}}</td></tr>
                    <tr><td>9.</td><td>Masa kerja</td><td class="sk-colon">:</td><td>{{masa_kerja}}</td></tr>
                    <tr><td>10.</td><td>Penilaian perilaku kerja</td><td class="sk-colon">:</td><td>{{penilaian_kinerja}}</td></tr>
                </table>
                diangkat kembali sebagai <strong>{{status_kepegawaian}}</strong> tahun pelajaran {{tahun_sk}}/{{tahun_sk_berikutnya}}, mata pelajaran {{mapel_tugas_yang_diampu}}; dan kepadanya diberikan Gaji Pokok serta tunjangan lain yang berlaku di {{nama_sekolah}}.
            </td>
        </tr>
        <tr>
            <td class="sk-label">Kedua</td>
            <td class="sk-colon">:</td>
            <td>Keputusan ini berlaku terhitung mulai tanggal {{tanggal_mulai}} sampai dengan {{tanggal_selesai}}. Apabila di kemudian hari terdapat kekeliruan di dalamnya akan diadakan perbaikan dan perhitungan kembali sebagaimana mestinya.</td>
        </tr>
        <tr>
            <td class="sk-label">Ketiga</td>
            <td class="sk-colon">:</td>
            <td>Asli Surat Keputusan ini diberikan kepada yang bersangkutan.</td>
        </tr>
    </table>

    <div class="sk-signature">
        Ditetapkan di&nbsp;&nbsp;: Yogyakarta<br>
        Pada Tanggal&nbsp;&nbsp;: {{tanggal_terbit}}<br><br>
        {{jabatan_penandatangan}}<br>
        Ketua,
        <div class="sk-signature-name">{{nama_penandatangan}}</div>
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
        '{{nomor_sk}}' => '/SK.02/LPM.DIY/VII/2026',
        '{{judul_sk}}' => 'SURAT KEPUTUSAN KETUA LP MA\'ARIF NU PWNU DIY',
        '{{nama_yayasan}}' => 'Lembaga Pendidikan Ma\'arif NU PWNU DIY',
        '{{alamat_yayasan}}' => 'Jl. Ibu Ruswo Nomor 60 Prawirodirjan, Gondomanan, Yogyakarta',
        '{{nama_sekolah}}' => 'SMK Pembangunan Dlingo',
        '{{nama_pegawai}}' => 'Ahmad Fathoni, S.Pd.',
        '{{gelar}}' => 'S.Pd.',
        '{{tempat_lahir}}' => 'Bantul',
        '{{tanggal_lahir}}' => '12 Januari 1990',
        '{{nip_maarif}}' => 'MIF.2026.001',
        '{{nuptk}}' => '1234567890123456',
        '{{nomor_kartanu}}' => 'NU.34.02.001',
        '{{tmt_pertama}}' => '01 Juli 2020',
        '{{masa_kerja}}' => '6 tahun',
        '{{pendidikan_terakhir}}' => 'S1',
        '{{tahun_lulus}}' => '2015',
        '{{program_studi}}' => 'Pendidikan Teknik Informatika',
        '{{mapel_tugas_yang_diampu}}' => 'XXX',
        '{{penilaian_kinerja}}' => 'Baik',
        '{{keterangan_sk_yayasan}}' => 'Perpanjangan SK',
        '{{jabatan}}' => 'Guru',
        '{{status_kepegawaian}}' => 'Guru Tetap Yayasan',
        '{{tanggal_mulai}}' => '01 Juli 2026',
        '{{tanggal_selesai}}' => '30 Juni 2027',
        '{{tanggal_terbit}}' => '01 Juli 2026',
        '{{tahun_sk}}' => '2026',
        '{{tahun_sk_berikutnya}}' => '2027',
        '{{nama_penandatangan}}' => 'Dr. Tadkiroatun Musfiroh, M. Hum.',
        '{{jabatan_penandatangan}}' => 'Pengurus LP Ma\'arif NU PWNU DIY',
        '{{catatan_pengajuan}}' => '-',
        '{{catatan_penerbitan}}' => '-',
    ];
@endphp

@push('css')
<style>
    .sk-editor-textarea {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        font-size: 12px;
        line-height: 1.45;
    }

    .sk-preview-toolbar {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: space-between;
    }

    .sk-preview-shell {
        background: #e7e7e7;
        border: 1px solid #d7dedb;
        border-radius: 14px;
        max-height: 760px;
        overflow: auto;
        padding: 18px;
    }

    .sk-preview-page {
        background: #fff;
        box-shadow: 0 1px 8px rgba(16, 45, 40, .15);
        margin: 0 auto;
        min-height: 1122px;
        padding: 52px 58px;
        transform-origin: top center;
        width: 794px;
    }

    .sk-preview-page .sk-full-document {
        font-size: 15px;
    }

    @media (max-width: 991.98px) {
        .sk-preview-shell {
            max-height: 620px;
        }

        .sk-preview-page {
            transform: scale(.72);
            transform-origin: top left;
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

                    <form action="{{ route('sk-yayasan.template.store') }}" method="POST" class="sk-template-editor" data-preview-label="Template baru">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Template</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', 'guru') }}" placeholder="Contoh: guru, pegawai, umum">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul Dokumen</label>
                            <input type="text" name="document_title" class="form-control" value="{{ old('document_title', "SURAT KEPUTUSAN KETUA LP MA'ARIF NU PWNU DIY") }}" data-sk-preview-title required>
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
                            <textarea name="body" rows="24" class="form-control sk-editor-textarea" data-sk-preview-body required>{{ old('body', $defaultSkBody) }}</textarea>
                            <small class="text-muted">Preview di kanan memakai data contoh dan akan mengikuti perubahan isi template ini.</small>
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
                            '{{nomor_sk}}', '{{judul_sk}}', '{{nama_yayasan}}', '{{nama_sekolah}}', '{{nama_pegawai}}',
                            '{{alamat_yayasan}}', '{{gelar}}', '{{tempat_lahir}}', '{{tanggal_lahir}}', '{{nip_maarif}}', '{{nuptk}}',
                            '{{nomor_kartanu}}', '{{tmt_pertama}}', '{{masa_kerja}}', '{{pendidikan_terakhir}}',
                            '{{tahun_lulus}}', '{{program_studi}}', '{{mapel_tugas_yang_diampu}}', '{{penilaian_kinerja}}',
                            '{{keterangan_sk_yayasan}}', '{{jabatan}}', '{{status_kepegawaian}}', '{{tanggal_terbit}}',
                            '{{tanggal_mulai}}', '{{tanggal_selesai}}', '{{tahun_sk}}', '{{tahun_sk_berikutnya}}',
                            '{{nama_penandatangan}}', '{{jabatan_penandatangan}}', '{{catatan_pengajuan}}', '{{catatan_penerbitan}}'
                        ] as $placeholder)
                            <span class="sky-chip">{{ $placeholder }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="sk-preview-toolbar mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Preview Template</div>
                            <h6 class="mb-0">Tampilan contoh hasil generate PDF</h6>
                        </div>
                        <span class="sky-chip" id="sk-preview-source-label">Template baru</span>
                    </div>
                    <div class="alert alert-info py-2 px-3 small">
                        Preview memakai data contoh. Saat PDF digenerate, placeholder akan diganti dengan data pengajuan asli.
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
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kategori</label>
                                                        <input type="text" name="category" value="{{ $template->category }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Judul Dokumen</label>
                                                        <input type="text" name="document_title" value="{{ $template->document_title }}" class="form-control" data-sk-preview-title required>
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
                                                    <textarea name="body" rows="14" class="form-control sk-editor-textarea" data-sk-preview-body required>{{ $template->body }}</textarea>
                                                    <small class="text-muted">Klik atau edit textarea ini untuk menampilkan preview template ini di panel kanan.</small>
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
        const sourceLabel = document.getElementById('sk-preview-source-label');
        const samplePlaceholders = @json($samplePlaceholders);
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
            const title = editor.querySelector('[data-sk-preview-title]')?.value || placeholders['{{judul_sk}}'];

            return `
                <div style="font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #111827;">
                    <div style="text-align:center; margin-bottom:24px;">
                        <h2 style="margin:0;">${title}</h2>
                        <div>Nomor: ${placeholders['{{nomor_sk}}']}</div>
                    </div>
                    <div style="margin-bottom:18px;">
                        <div>Nama Sekolah: ${placeholders['{{nama_sekolah}}']}</div>
                        <div>Nama Pegawai/Guru: ${placeholders['{{nama_pegawai}}']}</div>
                        <div>Status Kepegawaian: ${placeholders['{{status_kepegawaian}}']}</div>
                    </div>
                    <div style="text-align:justify;">${renderedBody}</div>
                    <div style="margin-top:40px; margin-left:auto; width:260px; text-align:left;">
                        <div>${placeholders['{{tanggal_terbit}}']}</div>
                        <div>${placeholders['{{jabatan_penandatangan}}']}</div>
                        <br><br><br>
                        <div><strong>${placeholders['{{nama_penandatangan}}']}</strong></div>
                    </div>
                </div>
            `;
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
                '{{judul_sk}}': titleInput?.value || samplePlaceholders['{{judul_sk}}'],
                '{{nomor_sk}}': formatDocumentNumber(numberFormatInput?.value),
            };

            let rendered = bodyInput?.value || '';
            Object.entries(placeholders).forEach(([placeholder, value]) => {
                rendered = rendered.split(placeholder).join(value ?? '');
            });

            if (!rendered.includes('data-sk-full-document="1"')) {
                rendered = legacyWrapper(rendered, editor, placeholders);
            }

            preview.innerHTML = rendered;

            if (sourceLabel) {
                sourceLabel.textContent = editor.dataset.previewLabel || 'Template';
            }
        }

        const editors = document.querySelectorAll('.sk-template-editor');

        editors.forEach((editor) => {
            editor.addEventListener('input', () => renderPreview(editor));
            editor.addEventListener('focusin', () => renderPreview(editor));
        });

        renderPreview(editors[0]);
    })();
</script>
@endsection
