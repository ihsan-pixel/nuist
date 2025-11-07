<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pesan Baru</title>
</head>
<body>
    <h2>Pesan Baru dari {{ $sender->name }}</h2>

    <p>Anda menerima pesan baru di sistem chat:</p>

    <div style="border: 1px solid #ddd; padding: 15px; margin: 15px 0; background-color: #f9f9f9;">
        <strong>Dari:</strong> {{ $sender->name }} ({{ $sender->role }})<br>
        <strong>Pesan:</strong><br>
        {{ $chat->message }}
    </div>

    <p>
        <a href="{{ url('/chat') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Buka Chat
        </a>
    </p>

    <p>
        <small>Email ini dikirim secara otomatis oleh sistem.</small>
    </p>
</body>
</html>
