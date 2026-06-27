<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SK Yayasan - <?php echo e($madrasah->name); ?></title>
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
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <?php
        $submission = $document->request;
        $isFullDocumentTemplate = str_contains($document->rendered_content ?? '', 'data-sk-full-document="1"');
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFullDocumentTemplate): ?>
        <?php echo $document->rendered_content; ?>

    <?php else: ?>
        <div class="sk-page">
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
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
        <div class="page-break"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/sk-yayasan-school-bundle.blade.php ENDPATH**/ ?>