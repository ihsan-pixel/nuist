<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing {{ $selectedTagihan->nomor_tagihan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #eef3f7;
            color: #132238;
        }
        .page {
            max-width: 820px;
            margin: 0 auto;
            padding: 24px 16px 48px;
        }
        .toolbar {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-primary {
            background: #0f6b5c;
            color: #fff;
        }
        .btn-light {
            background: #fff;
            color: #132238;
            border: 1px solid #d7e0ea;
        }
        .sheet {
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 12px 40px rgba(19, 34, 56, 0.08);
        }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 2px solid #e8edf3;
            padding-bottom: 18px;
            margin-bottom: 24px;
        }
        .title {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 6px;
        }
        .muted {
            color: #64748b;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .box {
            background: #f8fbfd;
            border: 1px solid #e5edf5;
            border-radius: 14px;
            padding: 14px 16px;
        }
        .box small {
            display: block;
            color: #64748b;
            margin-bottom: 6px;
        }
        .box strong {
            font-size: 16px;
        }
        .va-card {
            background: linear-gradient(135deg, #0f5f57 0%, #0e8549 100%);
            color: #fff;
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .va-card small {
            color: rgba(255,255,255,.82);
        }
        .va-number {
            font-size: 28px;
            letter-spacing: 2px;
            font-weight: 800;
            margin: 10px 0;
            word-break: break-all;
        }
        .notes {
            background: #fff8e8;
            border: 1px solid #f8dd96;
            border-radius: 14px;
            padding: 16px;
        }
        .notes h4 {
            margin-top: 0;
        }
        ol {
            margin: 10px 0 0 20px;
            padding: 0;
        }
        li {
            margin-bottom: 8px;
        }
        @media print {
            body {
                background: #fff;
            }
            .toolbar {
                display: none;
            }
            .page {
                padding: 0;
                max-width: none;
            }
            .sheet {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="toolbar">
            <a href="{{ route('mobile.siswa.detail', $selectedTagihan->id) }}" class="btn btn-light">Kembali</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak Billing</button>
        </div>

        <div class="sheet">
            <div class="header">
                <div>
                    <p class="muted" style="margin:0 0 6px;">Billing Pembayaran SPP Siswa</p>
                    <h1 class="title">{{ $selectedTagihan->nomor_tagihan }}</h1>
                    <div class="muted">{{ $studentSchool->name ?? 'Madrasah' }}</div>
                </div>
                <div style="text-align:right;">
                    <div class="muted">Tanggal Cetak</div>
                    <strong>{{ now()->translatedFormat('d F Y H:i') }}</strong>
                </div>
            </div>

            <div class="grid">
                <div class="box">
                    <small>Nama Siswa</small>
                    <strong>{{ $studentRecord->nama_lengkap ?? $studentUser->name }}</strong>
                </div>
                <div class="box">
                    <small>NIS</small>
                    <strong>{{ $studentRecord->nis ?? '-' }}</strong>
                </div>
                <div class="box">
                    <small>Kelas / Jurusan</small>
                    <strong>{{ trim(($studentRecord->kelas ?? '-') . ' / ' . ($studentRecord->jurusan ?? '-')) }}</strong>
                </div>
                <div class="box">
                    <small>Periode</small>
                    <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedTagihan->periode)->translatedFormat('F Y') }}</strong>
                </div>
                <div class="box">
                    <small>Jatuh Tempo</small>
                    <strong>{{ optional($selectedTagihan->jatuh_tempo)->translatedFormat('d F Y') }}</strong>
                </div>
                <div class="box">
                    <small>Total Tagihan</small>
                    <strong>Rp {{ number_format($selectedTagihan->total_tagihan, 0, ',', '.') }}</strong>
                </div>
            </div>

            @if($billingTransaction && $billingTransaction->va_number)
                <div class="va-card">
                    <small>BNI Virtual Account</small>
                    <div class="va-number">{{ $billingTransaction->va_number }}</div>
                    <small>Berlaku sampai {{ optional($billingTransaction->va_expired_at)->translatedFormat('d F Y H:i') ?? '-' }}</small>
                </div>
            @endif

            <div class="notes">
                <h4>Instruksi Pembayaran</h4>
                @if(($selectedTagihan->setting->payment_provider ?? 'manual') === 'bni_va' && $billingTransaction)
                    <ol>
                        <li>Simpan atau cetak billing ini sebagai bukti tagihan aktif.</li>
                        <li>Lakukan pembayaran ke nomor Virtual Account BNI yang tertera.</li>
                        <li>Bayar sesuai nominal tagihan: Rp {{ number_format($selectedTagihan->total_tagihan, 0, ',', '.') }}.</li>
                        <li>Setelah pembayaran diterima dan callback diproses, status tagihan akan diperbarui otomatis.</li>
                    </ol>
                @else
                    <ol>
                        <li>Billing ini digunakan sebagai bukti tagihan aktif siswa.</li>
                        <li>Pembayaran dilakukan sesuai petunjuk admin sekolah.</li>
                        <li>Hubungi admin jika memerlukan verifikasi atau klarifikasi pembayaran.</li>
                    </ol>
                @endif

                @if($selectedTagihan->setting?->payment_notes)
                    <p style="margin-top:16px;"><strong>Catatan:</strong> {{ $selectedTagihan->setting->payment_notes }}</p>
                @endif

                @if($selectedTagihan->catatan)
                    <p style="margin-top:10px;"><strong>Keterangan Tagihan:</strong> {{ $selectedTagihan->catatan }}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
