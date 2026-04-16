<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Persentase Presensi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h2, h4, p { margin: 0; }
        .header { margin-bottom: 16px; }
        .meta { margin-top: 6px; color: #4b5563; }
        .summary-box { margin: 12px 0 18px; padding: 10px 12px; border: 1px solid #d1d5db; background: #f9fafb; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #9ca3af; padding: 6px; vertical-align: top; }
        th { background: #e5e7eb; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .small { font-size: 10px; color: #4b5563; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Persentase Presensi {{ $summaryLabel }}</h2>
        <h4>{{ $madrasah->name }}</h4>
        <p class="meta">
            Periode: {{ $summaryStartDate->translatedFormat('d M Y') }} - {{ $effectiveEndDate->translatedFormat('d M Y') }}
            | Dicetak: {{ $generatedAt->translatedFormat('d M Y H:i') }}
        </p>
    </div>

    <div class="summary-box">
        <p><strong>Jenis Rekap:</strong> {{ $summaryLabel }}</p>
        <p><strong>Total Tenaga Pendidik:</strong> {{ $attendancePercentageRows->count() }} orang</p>
        @if($search)
            <p><strong>Filter Pencarian:</strong> {{ $search }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 22%;">Nama</th>
                <th style="width: 16%;">Status Kepegawaian</th>
                <th style="width: 9%;">Hari Kerja</th>
                <th style="width: 9%;">Hadir</th>
                <th style="width: 9%;">Izin</th>
                <th style="width: 11%;">Belum Hadir</th>
                <th style="width: 10%;">Persentase</th>
                <th style="width: 10%;">Identitas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendancePercentageRows as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['status_kepegawaian'] }}</td>
                    <td class="text-center">{{ $row['total_hari_kerja'] }}</td>
                    <td class="text-center">{{ $row['total_hadir'] }}</td>
                    <td class="text-center">{{ $row['total_izin'] }}</td>
                    <td class="text-center">{{ $row['total_belum_hadir'] }}</td>
                    <td class="text-center">{{ number_format($row['persentase_kehadiran'], 1) }}%</td>
                    <td class="small">
                        NIP: {{ $row['nip'] ?: '-' }}<br>
                        NUPTK: {{ $row['nuptk'] ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data untuk diexport.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
