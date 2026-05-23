<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
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
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f9f0 100%);
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .payment-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #48bb78;
            box-shadow: 0 2px 10px rgba(72, 187, 120, 0.1);
        }
        .status-success {
            color: #38a169;
            font-weight: bold;
            background: rgba(72, 187, 120, 0.1);
            padding: 2px 8px;
            border-radius: 4px;
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
        <p>Yth. <strong><?php echo e($madrasah->name); ?></strong>,</p>

        <p>Kami dengan senang hati menginformasikan bahwa pembayaran Anda telah berhasil diproses.</p>

        <div class="payment-details">
            <h3>Detail Pembayaran:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Nomor Invoice:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><?php echo e($tagihan->nomor_invoice); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Order ID:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><?php echo e($payment->order_id); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Jumlah Pembayaran:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;">Rp <?php echo e(number_format($payment->nominal, 0, ',', '.')); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Tanggal Pembayaran:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><?php echo e($payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : $tagihan->tanggal_pembayaran->format('d/m/Y')); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Status:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><span class="status-success">LUNAS</span></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Metode Pembayaran:</strong></td>
                    <td style="padding: 8px 0;"><?php echo e(ucfirst($payment->metode_pembayaran)); ?></td>
                </tr>
            </table>
        </div>

        <p>Terlampir dalam email ini adalah file PDF invoice sebagai bukti pembayaran resmi.</p>

        <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, silakan hubungi kami.</p>

        <p>Terima kasih atas partisipasi dan dukungan Anda dalam pengembangan pendidikan madrasah.</p>

        <p>Hormat kami,<br>
        <strong>Nuist LP. Ma'arif NU PWNU DIY</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem Nuist LP. Ma'arif NU PWNU DIY <br>
       Jl. Ibu Ruswo No.60, Prawirodirjan, Kec. Gondomanan, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55121</p>
    </div>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/emails/payment-confirmation.blade.php ENDPATH**/ ?>