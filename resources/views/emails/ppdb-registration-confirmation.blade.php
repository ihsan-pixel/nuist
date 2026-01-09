<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pendaftaran PPDB</title>
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
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
            border-radius: 3px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 3px;
            font-weight: bold;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Konfirmasi Pendaftaran PPDB</h1>
        <p>{{ $sekolah->name }}</p>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $pendaftar->nama_lengkap }}</strong>,</p>

        <p>Selamat! Data pendaftaran calon peserta didik baru Anda telah berhasil dikirim ke sistem PPDB {{ $sekolah->name }}.</p>

        <div class="info-box">
            <h3>Detail Pendaftaran:</h3>
            <p><strong>Nomor Pendaftaran:</strong> <span class="highlight">{{ $pendaftar->nomor_pendaftaran }}</span></p>
            <p><strong>Nama Lengkap:</strong> {{ $pendaftar->nama_lengkap }}</p>
            <p><strong>NISN:</strong> {{ $pendaftar->nisn }}</p>
            <p><strong>Jalur Pendaftaran:</strong> {{ $pendaftar->ppdbJalur->nama_jalur ?? 'Jalur Reguler' }}</p>
            <p><strong>Jurusan Pilihan:</strong> {{ $pendaftar->jurusan_pilihan }}</p>
            <p><strong>Tanggal Pendaftaran:</strong> {{ $pendaftar->created_at->format('d F Y H:i') }}</p>
        </div>

        <div class="info-box">
            <h3>Status Pendaftaran:</h3>
            <p>Data Anda saat ini dalam status <strong>{{ ucfirst($pendaftar->status) }}</strong>.</p>
            <p>Silakan pantau perkembangan status pendaftaran Anda melalui:</p>
            <ul>
                <li>Website resmi sekolah</li>
                <li>Nomor WhatsApp yang telah Anda daftarkan: {{ $pendaftar->ppdb_nomor_whatsapp_siswa }}</li>
            </ul>
        </div>

        <p>Untuk informasi lebih lanjut, silakan hubungi panitia PPDB {{ $sekolah->name }}.</p>

        <p>Terima kasih atas kepercayaan Anda memilih {{ $sekolah->name }} sebagai tempat melanjutkan pendidikan.</p>

        <p>Hormat kami,<br>
        <strong>Panitia PPDB {{ $sekolah->name }}</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem PPDB. Mohon jangan membalas email ini.</p>
        <p>&copy; {{ date('Y') }} {{ $sekolah->name }}. All rights reserved.</p>
    </div>
</body>
</html>
