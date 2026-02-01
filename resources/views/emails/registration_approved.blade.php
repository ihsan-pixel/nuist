<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Approved</title>
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
            background-color: #28a745;
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
        .password-box {
            background-color: #fff;
            border: 2px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .password {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            font-family: monospace;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Registration Approved!</h1>
        <p>Welcome to LP. Ma'arif NU PWNU DIY</p>
    </div>

    <div class="content">
        <p>Dear {{ $user->name }},</p>

        <p>Congratulations! Your registration has been approved. You can now access your account using the following credentials:</p>

        <div class="password-box">
            <p><strong>Your Login Password:</strong></p>
            <p class="password">{{ $plainPassword }}</p>
        </div>

        <p><strong>Important Security Notes:</strong></p>
        <ul>
            <li>Please change your password after first login for security purposes</li>
            <li>Keep your password confidential and do not share it with others</li>
            <li>Use a strong password combination for better security</li>
        </ul>

        <p>You can now log in to your account at: <a href="{{ url('/login') }}">{{ url('/login') }}</a></p>

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

        <p>Best regards,<br>
        LP. Ma'arif NU PWNU DIY Team</p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} LP. Ma'arif NU PWNU DIY. All rights reserved.</p>
    </div>
</body>
</html>
