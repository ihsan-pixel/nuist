<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Pending Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .highlight {
            background: #e8f5e8;
            padding: 15px;
            border-left: 4px solid #004b4c;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LP. Ma'arif NU PWNU DIY</h1>
        <h2>Registration Submitted Successfully</h2>
    </div>

    <div class="content">
        <p>Dear <?php echo e($pendingRegistration->name); ?>,</p>

        <div class="highlight">
            <strong>✅ Registration berhasil dikirim!</strong><br>
            Silahkan menunggu approval dari tim NUist.
        </div>

        <p>Terima kasih telah mendaftar di Sistem Informasi Digital LP. Ma'arif NU PWNU DIY.</p>

        <p>Detail pendaftaran Anda:</p>
        <ul>
            <li><strong>Nama:</strong> <?php echo e($pendingRegistration->name); ?></li>
            <li><strong>Email:</strong> <?php echo e($pendingRegistration->email); ?></li>
            <li><strong>sebagai:</strong> <?php echo e($pendingRegistration->role === 'tenaga_pendidik' ? 'Tenaga Pendidik' : ucfirst($pendingRegistration->role)); ?></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingRegistration->jabatan): ?>
                <li><strong>Jabatan:</strong> <?php echo e($pendingRegistration->jabatan); ?></li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingRegistration->asal_sekolah): ?>
                <li><strong>Asal Sekolah:</strong> <?php echo e($pendingRegistration->madrasah ? $pendingRegistration->madrasah->name : 'N/A'); ?></li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>

        <p>Anda akan menerima email konfirmasi setelah akun Anda disetujui oleh administrator. Proses verifikasi biasanya memakan waktu 1-2 hari kerja.</p>

        <p>Jika Anda memiliki pertanyaan, silakan hubungi tim support kami.</p>

        <p>Salam,<br>
        Tim NUist<br>
        LP. Ma'arif NU PWNU DIY</p>
    </div>

    <div class="footer">
        <p>© <?php echo e(date('Y')); ?> LP. Ma'arif NU PWNU DIY. All rights reserved.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/emails/registration_pending.blade.php ENDPATH**/ ?>