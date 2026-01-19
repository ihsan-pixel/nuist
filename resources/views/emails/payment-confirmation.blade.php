<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran UPPM</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .payment-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .status-success {
            color: #28a745;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Pembayaran Berhasil!</h1>
        <p>Konfirmasi Pembayaran UPPM</p>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $madrasah->name }}</strong>,</p>

        <p>Kami dengan senang hati menginformasikan bahwa pembayaran Iuran Pengembangan Pendidikan Madrasah (UPPM) Anda telah berhasil diproses.</p>

        <div class="payment-details">
            <h3>Detail Pembayaran:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Nomor Invoice:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;">{{ $tagihan->nomor_invoice }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Order ID:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;">{{ $payment->order_id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Jumlah Pembayaran:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Tanggal Pembayaran:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : $tagihan->tanggal_pembayaran->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Status:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><span class="status-success">LUNAS</span></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Metode Pembayaran:</strong></td>
                    <td style="padding: 8px 0;">{{ ucfirst($payment->metode_pembayaran) }}</td>
                </tr>
            </table>
        </div>

        <p>Terlampir dalam email ini adalah file PDF invoice sebagai bukti pembayaran resmi.</p>

        <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, silakan hubungi kami.</p>

        <p>Terima kasih atas partisipasi dan dukungan Anda dalam pengembangan pendidikan madrasah.</p>

        <p>Hormat kami,<br>
        <strong>Tim NU IST NUI</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem NU IST NUI<br>
        Jl. KH. Hasyim Asy'ari No.50, Kauman, Kec. Tulungagung, Kabupaten Tulungagung, Jawa Timur 66231</p>
    </div>
</body>
</html>
