<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $document->document_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #111827;
            margin: 30px 42px;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .meta {
            margin-bottom: 18px;
        }
        .content {
            text-align: justify;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .signature {
            width: 260px;
            margin-left: auto;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">{{ $document->template?->document_title ?? 'Surat Keputusan Yayasan' }}</h2>
        <div>Nomor: {{ $document->document_number }}</div>
    </div>

    <div class="meta">
        <div>Nama Sekolah: {{ $submission->madrasah?->name ?? '-' }}</div>
        <div>Nama Pegawai/Guru: {{ $submission->employee?->name ?? '-' }}</div>
        <div>Status Kepegawaian: {{ $submission->employee?->statusKepegawaian?->name ?? ($submission->employment_category ?? '-') }}</div>
    </div>

    <div class="content">
        {!! $document->rendered_content !!}
    </div>

    <div class="footer">
        <div class="signature">
            <div>{{ optional($document->issued_date)->translatedFormat('d F Y') }}</div>
            <div>{{ $document->signer_position ?? 'Ketua Yayasan' }}</div>
            <br><br><br>
            <div><strong>{{ $document->signer_name }}</strong></div>
        </div>
    </div>
</body>
</html>
