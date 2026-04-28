<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing {{ $selectedTagihan->nomor_tagihan }}</title>
    <style>
        :root {
            --billing-bg: #eef5ef;
            --billing-surface: rgba(255, 255, 255, 0.95);
            --billing-border: rgba(12, 71, 60, 0.10);
            --billing-text: #16302d;
            --billing-soft: #6a7d7a;
            --billing-primary: #0d6b58;
            --billing-primary-deep: #093f36;
            --billing-highlight: #f7f1dc;
        }
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(23, 160, 108, 0.16), transparent 24%),
                linear-gradient(180deg, #f8fbf8 0%, var(--billing-bg) 100%);
            color: var(--billing-text);
        }
        .page {
            max-width: 820px;
            margin: 0 auto;
            padding: 22px 16px 48px;
        }
        .toolbar {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
            padding: 14px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow: 0 18px 34px rgba(16, 43, 41, 0.08);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border-radius: 16px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: 700;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--billing-primary) 0%, var(--billing-primary-deep) 100%);
            color: #fff;
            box-shadow: 0 16px 28px rgba(13, 107, 88, 0.24);
        }
        .btn-light {
            background: rgba(13, 107, 88, 0.08);
            color: var(--billing-text);
            border: 1px solid rgba(13, 107, 88, 0.08);
        }
        .sheet {
            background: var(--billing-surface);
            border-radius: 28px;
            padding: 28px;
            box-shadow: 0 24px 48px rgba(16, 43, 41, 0.08);
            border: 1px solid var(--billing-border);
        }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 1px solid rgba(16, 43, 41, 0.10);
            padding-bottom: 18px;
            margin-bottom: 24px;
        }
        .title {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 6px;
        }
        .muted {
            color: var(--billing-soft);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .box {
            background: rgba(248, 251, 249, 0.96);
            border: 1px solid rgba(16, 43, 41, 0.08);
            border-radius: 18px;
            padding: 14px 16px;
        }
        .box small {
            display: block;
            color: var(--billing-soft);
            margin-bottom: 6px;
        }
        .box strong {
            font-size: 16px;
        }
        .va-card {
            background: linear-gradient(135deg, #093c37 0%, #0d6b58 50%, #17a06c 100%);
            color: #fff;
            border-radius: 24px;
            padding: 20px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }
        .va-card small {
            color: rgba(255,255,255,.82);
        }
        .va-card::after {
            content: "";
            position: absolute;
            right: -24px;
            top: -18px;
            width: 110px;
            height: 110px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.10);
            transform: rotate(18deg);
        }
        .va-number {
            font-size: 28px;
            letter-spacing: 2px;
            font-weight: 800;
            margin: 10px 0;
            word-break: break-all;
            position: relative;
            z-index: 1;
        }
        .notes {
            background: var(--billing-highlight);
            border: 1px solid rgba(212, 133, 31, 0.18);
            border-radius: 20px;
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
                border: 0;
            }
        }
        @media (max-width: 640px) {
            .page {
                padding: 14px 12px 36px;
            }
            .sheet {
                padding: 20px 18px;
                border-radius: 24px;
            }
            .header,
            .grid {
                grid-template-columns: 1fr;
            }
            .title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="toolbar">
            <a href="{{ route('mobile.siswa.detail', $selectedTagihan->id) }}" class="btn btn-light">Kembali ke detail</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak billing</button>
        </div>

        <div class="sheet">
            <div class="header">
                <div>
                    <p class="muted" style="margin:0 0 6px;">Billing Pembayaran {{ $selectedTagihan->jenis_tagihan ?? 'SPP' }}</p>
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
                    <small>Jenis Tagihan</small>
                    <strong>{{ $selectedTagihan->jenis_tagihan ?? 'SPP' }}</strong>
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
