<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Jawaban - {{ optional($score->school)->nama ?? 'Sekolah' }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; }
        .header { text-align: center; margin-bottom: 18px; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f7; text-align: left; }
        .small { font-size: 12px; color: #666; }
        .score { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Jawaban - {{ optional($score->school)->nama ?? 'Sekolah' }}</h2>
        <div class="small">Pengirim: {{ optional($score->submittedBy)->name ?? '-' }} &nbsp; | &nbsp; Sekolah: {{ optional($score->school)->nama ?? '-' }}</div>
    </div>

    <div class="meta">
        <table style="margin-bottom:12px;">
            <tr>
                <th style="width:40%">Dimensi</th>
                <th style="width:15%">Skor</th>
            </tr>
            <tr>
                <td>Struktur</td>
                <td class="score">{{ $score->struktur ?? $score->layanan ?? 0 }}</td>
            </tr>
            <tr>
                <td>Kompetensi</td>
                <td class="score">{{ $score->kompetensi ?? $score->tata_kelola ?? 0 }}</td>
            </tr>
            <tr>
                <td>Perilaku</td>
                <td class="score">{{ $score->perilaku ?? $score->jumlah_siswa ?? 0 }}</td>
            </tr>
            <tr>
                <td>Keterpaduan</td>
                <td class="score">{{ $score->keterpaduan ?? $score->jumlah_penghasilan ?? 0 }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <th class="score">{{ $score->total_skor }}</th>
            </tr>
            <tr>
                <th>Level Sekolah</th>
                <th class="score">{{ $score->level_sekolah }}</th>
            </tr>
        </table>
    </div>

    <h4>Jawaban per Pertanyaan</h4>
    <table>
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <th>Pertanyaan</th>
                <th style="width:70px">Jawaban</th>
                <th style="width:350px">Teks Pilihan</th>
                <th style="width:70px">Skor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($answers as $i => $ans)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ optional($ans->question)->pertanyaan ?? '-' }}</td>
                    <td>{{ strtoupper($ans->jawaban ?? '-') }}</td>
                    <td>{{ $ans->choice_text ?? (optional($ans->question)->choice_texts[$ans->jawaban ?? ''] ?? '-') }}</td>
                    <td class="score">{{ $ans->skor ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Tidak ada jawaban tersimpan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:18px; font-size:12px; color:#666;">Dicetak: {{ \Carbon\Carbon::now()->toDateTimeString() }}</div>
</body>
</html>
