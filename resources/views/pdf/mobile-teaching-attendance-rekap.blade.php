<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Mengajar</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 18px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .meta-table,
        .summary-table,
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
            font-size: 13px;
            font-weight: bold;
        }

        .summary-table th,
        .summary-table td,
        .detail-table th,
        .detail-table td {
            border: 1px solid #cfd8dc;
            padding: 7px 6px;
            text-align: left;
            vertical-align: top;
        }

        .summary-table th,
        .detail-table th {
            background: #eef5f3;
            font-weight: bold;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Presensi Mengajar</h2>
        <p class="muted">
            {{ $scope === 'all' ? 'Rekap Keseluruhan' : 'Rekap Bulanan' }}
        </p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="120">Nama</td>
            <td width="10">:</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td>Madrasah</td>
            <td>:</td>
            <td>{{ $user->madrasah->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td>{{ $periodLabel }}</td>
        </tr>
    </table>

    <div class="section-title">Ringkasan</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Total Presensi</th>
                <th>Total Siswa Hadir</th>
                <th>Total Siswa Kelas</th>
                <th>Rata-rata Kehadiran Siswa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $summary['total_entries'] ?? 0 }}</td>
                <td>{{ $summary['total_present_students'] ?? 0 }}</td>
                <td>{{ $summary['total_class_students'] ?? 0 }}</td>
                <td>{{ $summary['average_student_attendance'] ?? 0 }}%</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Detail Riwayat</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th width="76">Tanggal</th>
                <th width="45">Jam</th>
                <th width="95">Mapel</th>
                <th width="70">Kelas</th>
                <th width="80">Sekolah</th>
                <th width="42">Hadir</th>
                <th width="42">Total</th>
                <th width="50">Persen</th>
                <th>Materi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record['date_label'] ?? '-' }}</td>
                    <td>{{ $record['time'] ?? '-' }}</td>
                    <td>{{ $record['subject'] ?? '-' }}</td>
                    <td>{{ $record['class_name'] ?? '-' }}</td>
                    <td>{{ $record['school_name'] ?? '-' }}</td>
                    <td>{{ $record['present_students'] ?? 0 }}</td>
                    <td>{{ $record['class_total_students'] ?? 0 }}</td>
                    <td>{{ $record['student_attendance_percentage'] ?? 0 }}%</td>
                    <td>{{ $record['materi'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
