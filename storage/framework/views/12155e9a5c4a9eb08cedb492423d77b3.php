<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari <?php echo e($sender->name); ?></title>
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
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .message {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
            border-radius: 3px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pesan Baru dari <?php echo e($sender->name); ?></h2>
    </div>

    <div class="content">
        <p>Halo <strong><?php echo e($receiver->name); ?></strong>,</p>

        <p>Anda menerima pesan baru dari <strong><?php echo e($sender->name); ?></strong> di sistem chat admin.</p>

        <div class="message">
            <p><strong>Pesan:</strong></p>
            <p><?php echo e($chat->message); ?></p>
            <p><small>Dikirim pada: <?php echo e($chat->created_at->format('d M Y H:i')); ?></small></p>
        </div>

        <p>
            <a href="<?php echo e(route('chat.index')); ?>" class="btn">Buka Chat</a>
        </p>

        <p>Jika Anda tidak ingin menerima notifikasi email untuk pesan chat, silakan hubungi administrator sistem.</p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem NU IST.</p>
        <p>&copy; <?php echo e(date('Y')); ?> NU IST. All rights reserved.</p>
    </div>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/emails/chat-notification.blade.php ENDPATH**/ ?>