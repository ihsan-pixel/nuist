<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SK Yayasan - {{ $madrasah->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .sk-page {
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
@foreach($documents as $document)
    @php
        $submission = $document->request;
        $isFullDocumentTemplate = str_contains($document->rendered_content ?? '', 'data-sk-full-document="1"');
    @endphp

    @if($isFullDocumentTemplate)
        {!! $document->rendered_content !!}
    @else
        <div class="sk-page">
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
        </div>
    @endif

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
