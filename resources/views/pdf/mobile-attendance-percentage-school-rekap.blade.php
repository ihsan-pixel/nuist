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
        <p class="muted">Laporan kepala sekolah untuk seluruh tenaga pendidik dalam satu sekolah.</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="140">Sekolah</td>
            <td width="10">:</td>
            <td>{{ $school->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Periode Mingguan</td>
            <td>:</td>
            <td>{{ $selectedWeekLabel }}</td>
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

    <div class="section-title">Ringkasan Sekolah</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Total Guru</th>
                <th>Rata-rata Mingguan</th>
                <th>Rata-rata Bulanan</th>
                <th>Total Hadir Mingguan</th>
                <th>Total Hadir Bulanan</th>
                <th>Total Belum Mingguan</th>
                <th>Total Belum Bulanan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $schoolOverview['teacher_count'] }}</td>
                <td>{{ number_format($schoolOverview['weekly_average'], 1) }}%</td>
                <td>{{ number_format($schoolOverview['monthly_average'], 1) }}%</td>
                <td>{{ $schoolOverview['weekly_hadir_total'] }}</td>
                <td>{{ $schoolOverview['monthly_hadir_total'] }}</td>
                <td>{{ $schoolOverview['weekly_belum_total'] }}</td>
                <td>{{ $schoolOverview['monthly_belum_total'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Rekap Seluruh Tenaga Pendidik</div>
    <table class="teacher-table">
        <thead>
            <tr>
                <th width="170">Nama</th>
                <th width="110">Ketugasan</th>
                <th class="text-center">Mingguan %</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Belum</th>
                <th class="text-center">Bulanan %</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Belum</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schoolTeacherSummaries as $teacherSummary)
                <tr>
                    <td>{{ $teacherSummary['teacher']->name }}</td>
                    <td>{{ $teacherSummary['teacher']->ketugasan ?? 'Tenaga pendidik' }}</td>
                    <td class="text-center">{{ number_format($teacherSummary['weekly']['persentase_kehadiran'], 1) }}%</td>
                    <td class="text-center">{{ $teacherSummary['weekly']['total_hadir'] }}</td>
                    <td class="text-center">{{ $teacherSummary['weekly']['total_izin'] }}</td>
                    <td class="text-center">{{ $teacherSummary['weekly']['total_belum_hadir'] }}</td>
                    <td class="text-center">{{ number_format($teacherSummary['monthly']['persentase_kehadiran'], 1) }}%</td>
                    <td class="text-center">{{ $teacherSummary['monthly']['total_hadir'] }}</td>
                    <td class="text-center">{{ $teacherSummary['monthly']['total_izin'] }}</td>
                    <td class="text-center">{{ $teacherSummary['monthly']['total_belum_hadir'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Tidak ada data tenaga pendidik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($selectedTeacher)
        <div class="section-title">Detail Guru Terpilih: {{ $selectedTeacher->name }}</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Persentase</th>
                    <th>Hari Kerja</th>
                    <th>Hadir</th>
                    <th>Izin Disetujui</th>
                    <th>Belum Hadir</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mingguan</td>
                    <td>{{ number_format($weeklySummary['persentase_kehadiran'], 1) }}%</td>
                    <td>{{ $weeklySummary['total_hari_kerja'] }}</td>
                    <td>{{ $weeklySummary['total_hadir'] }}</td>
                    <td>{{ $weeklySummary['total_izin'] }}</td>
                    <td>{{ $weeklySummary['total_belum_hadir'] }}</td>
                </tr>
                <tr>
                    <td>Bulanan</td>
                    <td>{{ number_format($monthlySummary['persentase_kehadiran'], 1) }}%</td>
                    <td>{{ $monthlySummary['total_hari_kerja'] }}</td>
                    <td>{{ $monthlySummary['total_hadir'] }}</td>
                    <td>{{ $monthlySummary['total_izin'] }}</td>
                    <td>{{ $monthlySummary['total_belum_hadir'] }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Detail Mingguan Guru Terpilih</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th width="80">Tanggal</th>
                    <th width="70">Hari</th>
                    <th width="110">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($weeklySummary['details'] as $item)
                    <tr>
                        <td>{{ $item['tanggal']->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($item['hari']) }}</td>
                        <td>{{ $item['status'] }}</td>
                        <td>{{ $item['keterangan'] ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada detail pada periode mingguan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</body>
</html>
