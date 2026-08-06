<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Operator SPP Disetujui</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937; background: #f3f4f6; margin: 0; padding: 24px; }
        .card { max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 18px; overflow: hidden; }
        .hero { background: linear-gradient(135deg, #14532d, #15803d); color: #ffffff; padding: 32px; }
        .content { padding: 32px; }
        .credential-box { background: #f0fdf4; border: 1px solid #86efac; border-radius: 14px; padding: 20px; margin: 22px 0; }
        .password { display: inline-block; padding: 10px 14px; background: #ffffff; border-radius: 10px; border: 1px dashed #16a34a; font-family: monospace; font-size: 20px; font-weight: 700; letter-spacing: 1px; }
        .footer { color: #64748b; font-size: 12px; padding: 0 32px 32px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="hero">
            <h1 style="margin:0 0 8px;">Akun Operator SPP Disetujui</h1>
            <p style="margin:0;">Akun login Admin SPP untuk sekolah Anda sudah aktif.</p>
        </div>
        <div class="content">
            <p>Yth. <?php echo e($user->name); ?>,</p>
            <p>Permohonan akun Operator SPP untuk <strong><?php echo e($registration->madrasah->name ?? '-'); ?></strong> telah disetujui.</p>

            <div class="credential-box">
                <p style="margin:0 0 10px;"><strong>Email Login:</strong> <?php echo e($user->email); ?></p>
                <p style="margin:0 0 10px;"><strong>Password Sementara:</strong></p>
                <div class="password"><?php echo e($plainPassword); ?></div>
            </div>

            <p>Silakan login melalui <a href="<?php echo e(route('login.operator-spp')); ?>"><?php echo e(route('login.operator-spp')); ?></a>.</p>
            <p>Untuk keamanan, segera ubah password setelah login pertama.</p>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh NUist. Mohon tidak membalas email ini.
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/emails/spp-operator-approved.blade.php ENDPATH**/ ?>