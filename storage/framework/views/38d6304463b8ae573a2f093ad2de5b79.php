<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Operator SPP</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937; background: #f3f4f6; margin: 0; padding: 24px; }
        .card { max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 18px; overflow: hidden; }
        .hero { background: linear-gradient(135deg, #0f766e, #166534); color: #ffffff; padding: 32px; }
        .content { padding: 32px; }
        .detail-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px 20px; margin: 20px 0; }
        .detail-box ul { padding-left: 18px; margin: 0; }
        .footer { color: #64748b; font-size: 12px; padding: 0 32px 32px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="hero">
            <h1 style="margin:0 0 8px;">Pendaftaran Operator SPP Diterima</h1>
            <p style="margin:0;">Permohonan akun Anda sudah masuk ke sistem dan menunggu review Super Admin.</p>
        </div>
        <div class="content">
            <p>Yth. <?php echo e($registration->name); ?>,</p>
            <p>Terima kasih. Pengajuan akun <strong>Admin SPP</strong> untuk sekolah Anda berhasil dikirim.</p>

            <div class="detail-box">
                <ul>
                    <li><strong>Sekolah:</strong> <?php echo e($registration->madrasah->name ?? '-'); ?></li>
                    <li><strong>Email:</strong> <?php echo e($registration->email); ?></li>
                    <li><strong>Jabatan:</strong> <?php echo e($registration->jabatan); ?></li>
                    <li><strong>Waktu Pengajuan:</strong> <?php echo e(optional($registration->submitted_at)->format('d M Y H:i')); ?></li>
                </ul>
            </div>

            <p>Setelah disetujui, sistem akan membuat akun otomatis dan mengirimkan kredensial login ke email ini.</p>
            <p>Jika sekolah sudah pernah mengajukan akun operator SPP, sistem tidak akan menerima pendaftaran tambahan.</p>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh NUist. Mohon tidak membalas email ini.
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/emails/spp-operator-registration-submitted.blade.php ENDPATH**/ ?>