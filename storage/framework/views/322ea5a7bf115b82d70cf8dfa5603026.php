<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($document->document_number); ?></title>
    <?php
        $isFullDocumentTemplate = str_contains($document->rendered_content ?? '', 'data-sk-full-document="1"');
    ?>
    <style>
        <?php if($isFullDocumentTemplate): ?>
        body {
            margin: 0;
            padding: 0;
        }
        <?php else: ?>
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
        <?php endif; ?>
    </style>
</head>
<body>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFullDocumentTemplate): ?>
        <?php echo $document->rendered_content; ?>

    <?php else: ?>
    <div class="header">
        <h2 style="margin:0;"><?php echo e($document->template?->document_title ?? 'Surat Keputusan Yayasan'); ?></h2>
        <div>Nomor: <?php echo e($document->document_number); ?></div>
    </div>

    <div class="meta">
        <div>Nama Sekolah: <?php echo e($submission->madrasah?->name ?? '-'); ?></div>
        <div>Nama Pegawai/Guru: <?php echo e($submission->employee?->name ?? '-'); ?></div>
        <div>Status Kepegawaian: <?php echo e($submission->employee?->statusKepegawaian?->name ?? ($submission->employment_category ?? '-')); ?></div>
    </div>

    <div class="content">
        <?php echo $document->rendered_content; ?>

        </div>

        <div class="footer">
            <div class="signature">
            <div><?php echo e(optional($document->issued_date)->locale('id')->translatedFormat('d F Y')); ?></div>
                <div><?php echo e($document->signer_position ?? 'Ketua Yayasan'); ?></div>
                <br><br><br>
                <div><strong><?php echo e($document->signer_name); ?></strong></div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/sk-yayasan-template.blade.php ENDPATH**/ ?>