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
            <thead>
                <tr>
                    <th>Dimensi</th>
                    <th style="width:80px; text-align:center">Total Jawaban</th>
                    <th style="width:100px; text-align:center">A (cnt / %)</th>
                    <th style="width:100px; text-align:center">B (cnt / %)</th>
                    <th style="width:100px; text-align:center">C (cnt / %)</th>
                    <th style="width:100px; text-align:center">D (cnt / %)</th>
                    <th style="width:100px; text-align:center">E (cnt / %)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dimensionStats as $dim => $stat)
                    <tr>
                        <td>{{ $dim }}</td>
                        <td class="score" style="text-align:center">{{ $stat['total_answers'] }}</td>
                        <td style="text-align:center">{{ $stat['counts']['A'] ?? 0 }} / {{ $stat['percents']['A'] ?? 0 }}%</td>
                        <td style="text-align:center">{{ $stat['counts']['B'] ?? 0 }} / {{ $stat['percents']['B'] ?? 0 }}%</td>
                        <td style="text-align:center">{{ $stat['counts']['C'] ?? 0 }} / {{ $stat['percents']['C'] ?? 0 }}%</td>
                        <td style="text-align:center">{{ $stat['counts']['D'] ?? 0 }} / {{ $stat['percents']['D'] ?? 0 }}%</td>
                        <td style="text-align:center">{{ $stat['counts']['E'] ?? 0 }} / {{ $stat['percents']['E'] ?? 0 }}%</td>
                    </tr>
                @endforeach
                <tr>
                    <th>Total</th>
                    <th class="score" style="text-align:center">{{ array_sum(array_column($dimensionStats, 'total_answers')) }}</th>
                    <th colspan="5" class="small" style="text-align:left">Total Skor: <strong>{{ $score->total_skor }}</strong> &nbsp; | &nbsp; Level: <strong>{{ $score->level_sekolah }}</strong></th>
                </tr>
            </tbody>
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
