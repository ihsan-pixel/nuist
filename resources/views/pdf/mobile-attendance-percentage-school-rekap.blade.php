<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Persentase Kehadiran Sekolah</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 16px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .muted {
            color: #6b7280;
        }

        .meta-table,
        .summary-table,
        .teacher-table,
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .section-title {
            margin: 16px 0 8px;
            font-size: 12px;
            font-weight: bold;
        }

        .summary-table th,
        .summary-table td,
        .teacher-table th,
        .teacher-table td,
        .detail-table th,
        .detail-table td {
            border: 1px solid #cfd8dc;
            padding: 6px 5px;
            text-align: left;
        }

        .summary-table th,
        .teacher-table th,
        .detail-table th {
            background: #eef5f3;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Persentase Presensi Kehadiran</h2>
        <p class="muted">Rekap bulanan seluruh tenaga pendidik.</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="140">Sekolah</td>
            <td width="10">:</td>
            <td>{{ $school->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Periode Bulanan</td>
            <td>:</td>
            <td>{{ ucfirst($selectedMonthLabel) }}</td>
        </tr>
        <tr>
            <td>Dicetak Pada</td>
            <td>:</td>
            <td>{{ $generatedAt->translatedFormat('d M Y H:i') }}</td>
        </tr>
    </table>

    <div class="section-title">Rekap Seluruh Tenaga Pendidik</div>
    <table class="teacher-table">
        <thead>
            <tr>
                <th width="190">Nama</th>
                <th width="120">Ketugasan</th>
                <th class="text-center">Persentase</th>
                <th class="text-center">Hari Kerja</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Belum Hadir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schoolTeacherSummaries as $teacherSummary)
                <tr>
                    <td>{{ $teacherSummary['teacher']->name }}</td>
                    <td>{{ $teacherSummary['teacher']->ketugasan ?? 'Tenaga pendidik' }}</td>
                    <td class="text-center">{{ number_format($teacherSummary['monthly']['persentase_kehadiran'], 1) }}%</td>
                    <td class="text-center">{{ $teacherSummary['monthly']['total_hari_kerja'] }}</td>
                    <td class="text-center">{{ $teacherSummary['monthly']['total_hadir'] }}</td>
                    <td class="text-center">{{ $teacherSummary['monthly']['total_izin'] }}</td>
                    <td class="text-center">{{ $teacherSummary['monthly']['total_belum_hadir'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data tenaga pendidik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
