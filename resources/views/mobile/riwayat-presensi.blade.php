@extends('layouts.mobile')

@section('title', 'Riwayat Presensi')
@section('subtitle', 'Riwayat Presensi dan Rekap Kehadiran')

@section('content')
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
</div>

<div class="container px-0 pb-4" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background:
                radial-gradient(circle at top right, rgba(14, 133, 73, 0.12), transparent 30%),
                linear-gradient(180deg, #f5faf8 0%, #f7f8fb 100%);
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .hero-card,
        .filter-card,
        .summary-card,
        .download-card,
        .history-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.07);
            margin-bottom: 14px;
        }

        .hero-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            padding: 16px;
        }

        .hero-subtitle {
            font-size: 11px;
            opacity: 0.9;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .hero-period {
            font-size: 18px;
            font-weight: 700;
            margin-top: 2px;
        }

        .hero-caption {
            font-size: 11px;
            opacity: 0.82;
        }

        .filter-card,
        .summary-card,
        .download-card,
        .history-card {
            background: #fff;
        }

        .filter-card .card-body,
        .download-card .card-body,
        .history-card .card-body {
            padding: 14px;
        }

        .summary-header {
            padding: 14px 16px;
            color: #fff;
        }

        .summary-header.weekly {
            background: linear-gradient(135deg, #0e8549 0%, #35a86b 100%);
        }

        .summary-header.monthly {
            background: linear-gradient(135deg, #0a6170 0%, #004b4c 100%);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 14px 16px 16px;
        }

        .summary-metric {
            background: #f7faf9;
            border-radius: 14px;
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #edf2f1;
        }

        .summary-metric .value {
            font-size: 15px;
            font-weight: 700;
            color: #004b4c;
        }

        .summary-metric .label {
            font-size: 10px;
            color: #6c757d;
            margin-top: 2px;
        }

        .download-btn {
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            padding: 10px 12px;
        }

        .history-title {
            font-size: 13px;
            font-weight: 700;
            color: #004b4c;
        }

        .history-group {
            padding: 12px 0;
            border-bottom: 1px solid #edf1f5;
        }

        .history-group:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .history-date {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            background: #f9fbfc;
            border: 1px solid #edf1f5;
            border-radius: 14px;
            padding: 10px;
            margin-bottom: 8px;
        }

        .history-item:last-child {
            margin-bottom: 0;
        }

        .history-item-title {
            font-size: 12px;
            font-weight: 600;
            color: #004b4c;
        }

        .history-item-meta {
            font-size: 11px;
            color: #6c757d;
            margin-top: 2px;
        }

        .history-item-note {
            font-size: 11px;
            color: #7b8794;
            margin-top: 4px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status-hadir,
        .status-terlambat,
        .status-approved {
            background: rgba(25, 135, 84, 0.12);
            color: #198754;
        }

        .status-izin,
        .status-pending {
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
        }

        .status-sakit {
            background: rgba(255, 193, 7, 0.16);
            color: #9a6700;
        }

        .status-alpha,
        .status-rejected {
            background: rgba(220, 53, 69, 0.12);
            color: #dc3545;
        }

        .empty-state {
            text-align: center;
            padding: 28px 12px 8px;
            color: #7b8794;
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 8px;
        }
    </style>

    <div class="hero-card">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="hero-subtitle">Riwayat Presensi</div>
                <div class="hero-period">{{ ucfirst($selectedMonthLabel) }}</div>
                <div class="hero-caption">Ringkasan kehadiran mingguan dan bulanan tersedia di halaman ini.</div>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="38" height="38" alt="User">
        </div>
    </div>

    <div class="card filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('mobile.riwayat-presensi') }}">
                <div class="row g-2">
                    <div class="col-6">
                        <label for="week" class="form-label mb-1" style="font-size: 11px;">Minggu</label>
                        <input type="week" id="week" name="week" value="{{ $selectedWeekValue }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-6">
                        <label for="month" class="form-label mb-1" style="font-size: 11px;">Bulan</label>
                        <input type="month" id="month" name="month" value="{{ $selectedMonthValue }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm w-100" style="background: #004b4c; color: #fff;">Tampilkan Riwayat</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-header weekly">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size: 11px; opacity: .9;">Persentase Mingguan</div>
                    <h5 class="mb-1">{{ number_format($weeklySummary['persentase_kehadiran'], 1) }}%</h5>
                    <small>{{ $weeklySummary['periode_label'] }}</small>
                </div>
                <i class="bx bx-calendar-week" style="font-size: 28px;"></i>
            </div>
        </div>
        <div class="summary-grid">
            <div class="summary-metric">
                <div class="value">{{ $weeklySummary['total_hari_kerja'] }}</div>
                <div class="label">Hari Kerja</div>
            </div>
            <div class="summary-metric">
                <div class="value">{{ $weeklySummary['total_hadir'] }}</div>
                <div class="label">Hadir</div>
            </div>
            <div class="summary-metric">
                <div class="value">{{ $weeklySummary['total_izin'] }}</div>
                <div class="label">Izin</div>
            </div>
            <div class="summary-metric">
                <div class="value">{{ $weeklySummary['total_belum_hadir'] }}</div>
                <div class="label">Belum</div>
            </div>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-header monthly">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size: 11px; opacity: .9;">Persentase Bulanan</div>
                    <h5 class="mb-1">{{ number_format($monthlySummary['persentase_kehadiran'], 1) }}%</h5>
                    <small>{{ $monthlySummary['periode_label'] }}</small>
                </div>
                <i class="bx bx-calendar" style="font-size: 28px;"></i>
            </div>
        </div>
        <div class="summary-grid">
            <div class="summary-metric">
                <div class="value">{{ $monthlySummary['total_hari_kerja'] }}</div>
                <div class="label">Hari Kerja</div>
            </div>
            <div class="summary-metric">
                <div class="value">{{ $monthlySummary['total_hadir'] }}</div>
                <div class="label">Hadir</div>
            </div>
            <div class="summary-metric">
                <div class="value">{{ $monthlySummary['total_izin'] }}</div>
                <div class="label">Izin</div>
            </div>
            <div class="summary-metric">
                <div class="value">{{ $monthlySummary['total_belum_hadir'] }}</div>
                <div class="label">Belum</div>
            </div>
        </div>
    </div>

    <div class="card download-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="history-title">Download Rekap Presensi</div>
                    <small class="text-muted">Unduh PDF berdasarkan periode yang dibutuhkan.</small>
                </div>
                <i class="bx bx-download" style="font-size: 24px; color: #004b4c;"></i>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('mobile.riwayat-presensi.download', ['type' => 'weekly', 'week' => $selectedWeekValue, 'month' => $selectedMonthValue]) }}"
                   class="btn btn-outline-success download-btn">
                    Download PDF Mingguan
                </a>
                <a href="{{ route('mobile.riwayat-presensi.download', ['type' => 'monthly', 'week' => $selectedWeekValue, 'month' => $selectedMonthValue]) }}"
                   class="btn btn-outline-primary download-btn">
                    Download PDF Bulanan
                </a>
                <a href="{{ route('mobile.riwayat-presensi.download', ['type' => 'all']) }}"
                   class="btn btn-dark download-btn">
                    Download PDF Keseluruhan
                </a>
            </div>
        </div>
    </div>

    <div class="card history-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="history-title">Riwayat {{ ucfirst($selectedMonthLabel) }}</div>
                <small class="text-muted">{{ $presensiHistory->count() }} data</small>
            </div>

            @if($presensiHistory->count() > 0)
                @php
                    $groupedPresensi = $presensiHistory->groupBy(fn ($item) => $item->tanggal->toDateString());
                @endphp

                @foreach($groupedPresensi as $date => $presensis)
                    <div class="history-group">
                        <div class="history-date">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                            <span class="text-muted">({{ ucfirst(\Carbon\Carbon::parse($date)->locale('id')->dayName) }})</span>
                        </div>

                        @foreach($presensis as $presensi)
                            @php
                                $statusClass = strtolower($presensi->status ?? 'alpha');
                                $statusLabel = ucfirst(str_replace('_', ' ', $presensi->status ?? '-'));
                            @endphp
                            <div class="history-item">
                                <div>
                                    @if($presensi->model_type === 'presensi')
                                        <div class="history-item-title">{{ $presensi->madrasah->name ?? 'Madrasah' }}</div>
                                        <div class="history-item-meta">
                                            @if($presensi->waktu_masuk)
                                                Masuk {{ \Carbon\Carbon::parse($presensi->waktu_masuk)->format('H:i') }}
                                            @endif
                                            @if($presensi->waktu_masuk && $presensi->waktu_keluar)
                                                •
                                            @endif
                                            @if($presensi->waktu_keluar)
                                                Keluar {{ \Carbon\Carbon::parse($presensi->waktu_keluar)->format('H:i') }}
                                            @endif
                                            @if(!$presensi->waktu_masuk && !$presensi->waktu_keluar)
                                                Tidak ada jam presensi
                                            @endif
                                        </div>
                                        @if($presensi->keterangan)
                                            <div class="history-item-note">{{ $presensi->keterangan }}</div>
                                        @endif
                                    @else
                                        <div class="history-item-title">Izin {{ ucfirst(str_replace('_', ' ', $presensi->type)) }}</div>
                                        <div class="history-item-meta">
                                            Status pengajuan {{ ucfirst(str_replace('_', ' ', $presensi->status)) }}
                                        </div>
                                        @if($presensi->deskripsi_tugas || $presensi->alasan)
                                            <div class="history-item-note">{{ $presensi->deskripsi_tugas ?: $presensi->alasan }}</div>
                                        @endif
                                    @endif
                                </div>
                                <span class="status-badge status-{{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="bx bx-calendar-x"></i>
                    <p class="mb-0">Belum ada riwayat presensi pada bulan yang dipilih.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
