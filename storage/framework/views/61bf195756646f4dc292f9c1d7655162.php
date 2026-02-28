<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Baru</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #00393a 0%, #005555 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .info-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            color: #00393a;
            width: 120px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
        }
        .message-box {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid #00393a;
        }
        .message-text {
            white-space: pre-wrap;
            color: #333;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📬 Pesan Baru dari Website</h1>
        </div>
        <div class="content">
            <p>Anda menerima pesan baru dari formulir kontak di halaman sekolah:</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value"><?php echo e($details['name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo e($details['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Subjek:</span>
                    <span class="info-value"><?php echo e($details['subject']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sekolah:</span>
                    <span class="info-value"><?php echo e($details['school_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu:</span>
                    <span class="info-value"><?php echo e($details['created_at']); ?></span>
                </div>
            </div>

            <div class="message-box">
                <strong>Pesan:</strong>
                <p class="message-text"><?php echo e($details['message']); ?></p>
            </div>
        </div>
        <div class="footer">
            <p>Pesan ini dikirim secara otomatis dari sistem website NUIST.</p>
        </div>
    </div>
</body>
</html>

<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/emails/contact-form.blade.php ENDPATH**/ ?>