@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

@section('content')
<div class="container py-3 presensi-screen" style="max-width: 420px; margin: auto;">
    <meta name="presensi-endpoint" content="{{ route('mobile.presensi.store') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background: #f8f9fb;
            min-height: 100vh;
        }

        body.selfie-modal-open {
            overflow: hidden;
        }

        .presensi-screen {
            padding-bottom: 170px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1200;
            background: #f8f9fb;
            padding-bottom: 14px;
        }

        .user-location-map-container,
        #presensi-map,
        .leaflet-container {
            position: relative;
            z-index: 1;
        }

        .leaflet-pane,
        .leaflet-top,
        .leaflet-bottom,
        .leaflet-control,
        .leaflet-tooltip,
        .leaflet-popup {
            z-index: 10;
        }

        .page-heading {
            text-align: center;
            margin-bottom: 14px;
        }

        .page-heading h5 {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .page-heading small {
            font-size: 12px;
            color: #6b7280;
        }

        .realtime-clock-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            text-align: center;
            box-shadow: 0 10px 24px rgba(14, 133, 73, 0.18);
            margin-bottom: 12px;
        }

        .clock-time {
            font-size: 28px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: 0.06em;
            font-variant-numeric: tabular-nums;
            margin-bottom: 4px;
        }

        .clock-caption {
            font-size: 11px;
            opacity: 0.84;
        }

        .presensi-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 8px 20px rgba(0, 75, 76, 0.18);
            margin-bottom: 12px;
        }

        .presensi-header h6 {
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            opacity: 0.85;
        }

        .presensi-header h5 {
            font-size: 16px;
        }

        .school-meta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 6px;
        }

        .status-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
            margin-bottom: 12px;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .status-card.success {
            border-left: 4px solid #0e8549;
        }

        .status-card.warning {
            border-left: 4px solid #ffc107;
        }

        .status-icon {
            width: 34px;
            height: 34px;
            background: rgba(14, 133, 73, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .status-icon i {
            color: #0e8549;
            font-size: 16px;
        }

        .presensi-form {
            background: #fff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
            margin-bottom: 12px;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .user-location-map-container {
            position: relative;
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid rgba(14, 133, 73, 0.14);
            background: #f8fafc;
        }

        .map-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 1;
        }

        .map-placeholder i {
            font-size: 32px;
            color: #adb5bd;
            margin-bottom: 8px;
        }

        .map-placeholder span {
            font-size: 11px;
            color: #6c757d;
            text-align: center;
        }

        .form-section {
            margin-bottom: 14px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .section-header-main {
            display: flex;
            align-items: center;
            min-width: 0;
        }

        .section-title {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
            color: #1f2937;
        }

        #location-info.location-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 10px;
            margin-bottom: 0;
            font-size: 11px;
            line-height: 1;
            white-space: nowrap;
            flex-shrink: 0;
        }

        #location-info .badge-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        #location-info .badge-title {
            font-weight: 600;
            color: #1f2937;
        }

        #location-info.info .badge-title {
            color: #0c5460;
        }

        #location-info.success .badge-title {
            color: #0e8549;
        }

        #location-info.warning .badge-title {
            color: #9a6700;
        }

        #location-info.error .badge-title {
            color: #b42318;
        }

        .location-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 6px;
            word-wrap: break-word;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .location-info.success {
            background: rgba(14, 133, 73, 0.1);
            border: 1px solid rgba(14, 133, 73, 0.2);
        }

        .location-info.error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .location-info.info {
            background: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.2);
        }

        .coordinate-input {
            background: #fff;
            border-radius: 10px;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            font-size: 12px;
            width: 100%;
        }

        .address-input {
            background: #fff;
            border-radius: 10px;
            padding: 8px 10px;
            border: none;
            font-size: 11px;
            width: 100%;
            word-wrap: break-word;
            color: #475467;
            background: transparent;
        }

        .presensi-btn {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 14px;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            width: 100%;
            margin-top: 8px;
            box-shadow: 0 8px 18px rgba(14, 133, 73, 0.16);
        }

        .presensi-btn:disabled {
            background: #6c757d;
            box-shadow: none;
        }

        .schedule-section {
            background: #fff;
            border-radius: 14px;
            padding: 12px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
            margin-bottom: 12px;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .schedule-item {
            background: rgba(248, 250, 252, 0.95);
            border-radius: 12px;
            padding: 9px 10px;
            text-align: left;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .schedule-item.masuk {
            border-top: 3px solid #0d6efd;
        }

        .schedule-item.pulang {
            border-top: 3px solid #0e8549;
        }

        .schedule-item i {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .schedule-item h6 {
            font-size: 11px;
            margin-bottom: 1px;
            font-weight: 600;
        }

        .schedule-item p {
            font-size: 11px;
            margin-bottom: 1px;
            font-weight: 600;
            color: #1f2937;
        }

        .schedule-item small {
            font-size: 9px;
            color: #6c757d;
        }

        .compact-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .compact-section-head .section-title {
            margin-bottom: 0;
            font-size: 12px;
        }

        .compact-note {
            font-size: 10px;
            color: #667085;
            line-height: 1.4;
            margin-top: 6px;
        }

        .alert-custom {
            background: #fff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
            margin-bottom: 12px;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .alert-custom.warning {
            border-left: 4px solid #ffc107;
        }

        .alert-custom.danger {
            border-left: 4px solid #dc3545;
        }

        .alert-custom.info {
            border-left: 4px solid #0dcaf0;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary-custom:hover {
            color: #fff;
            background: linear-gradient(135deg, #003d3e 0%, #0c6a42 100%);
        }

        #selfie-video {
            transform: scaleX(-1); /* tampilan jadi seperti cermin */
        }

        .status-detail-list {
            display: grid;
            gap: 10px;
            width: 100%;
        }

        .status-detail-item {
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
        }

        .status-detail-item small {
            display: block;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .status-detail-item p {
            margin-bottom: 0;
            color: #1f2937;
            font-size: 12px;
        }

        .status-inline-note {
            margin-top: 10px;
            color: #6b7280;
            font-size: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .metric-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
        }

        .metric-card .metric-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .metric-card .metric-value {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .action-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 5px;
            min-height: 74px;
            padding: 10px 8px;
            border-radius: 12px;
            text-decoration: none;
            color: #1f2937;
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
        }

        .action-tile i {
            font-size: 18px;
            color: #0e8549;
        }

        .action-tile strong {
            font-size: 11px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.2;
        }

        .action-tile span {
            display: none;
        }

        .address-compact {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px;
        }

        .address-compact i {
            color: #0ea5e9;
            font-size: 16px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .selfie-callout {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            margin-top: 8px;
        }

        .selfie-callout i {
            font-size: 18px;
            color: #0e8549;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .selfie-callout strong {
            display: block;
            font-size: 12px;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .selfie-callout span {
            display: block;
            font-size: 11px;
            color: #6b7280;
        }

        .presensi-action-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 82px;
            z-index: 1045;
            padding: 0 14px 14px;
            pointer-events: none;
        }

        .presensi-action-bar-inner {
            max-width: 420px;
            margin: 0 auto;
            background: rgba(248, 249, 251, 0.92);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 10px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(226, 232, 240, 0.9);
            pointer-events: auto;
        }

        .presensi-action-hint {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 8px;
            text-align: center;
        }

        .selfie-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.58);
            z-index: 2100;
            display: none;
            align-items: flex-end;
            justify-content: center;
            padding: 12px;
        }

        .selfie-modal.show {
            display: flex;
        }

        .selfie-modal-dialog {
            width: min(100%, 420px);
            max-height: calc(100vh - 24px);
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.28);
            display: flex;
            flex-direction: column;
        }

        .selfie-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 16px 16px 12px;
            border-bottom: 1px solid #eef2f7;
        }

        .selfie-modal-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .selfie-modal-subtitle {
            font-size: 11px;
            color: #6b7280;
        }

        .selfie-modal-close {
            width: 34px;
            height: 34px;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 50%;
            color: #475467;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .selfie-modal-body {
            padding: 14px 16px;
            overflow-y: auto;
        }

        .selfie-note {
            background: rgba(14, 133, 73, 0.08);
            border: 1px solid rgba(14, 133, 73, 0.14);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 11px;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .selfie-note i {
            color: #0e8549;
            margin-right: 6px;
        }

        .selfie-stage {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            min-height: 420px;
        }

        .selfie-placeholder,
        #selfie-video,
        #selfie-canvas,
        #selfie-preview {
            width: 100%;
            height: 420px;
            border-radius: 16px;
        }

        .selfie-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #6b7280;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            text-align: center;
            padding: 20px;
        }

        .selfie-placeholder i {
            font-size: 44px;
            margin-bottom: 10px;
            color: #94a3b8;
        }

        .selfie-overlay-actions {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 14px;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 0 14px;
        }

        .selfie-overlay-actions .btn {
            padding: 10px 14px;
            font-size: 12px;
        }

        .selfie-modal-footer {
            padding: 14px 16px 16px;
            border-top: 1px solid #eef2f7;
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        @media (max-width: 380px) {
            .action-grid,
            .info-grid,
            .schedule-grid {
                grid-template-columns: 1fr;
            }

            .action-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .selfie-stage,
            .selfie-placeholder,
            #selfie-video,
            #selfie-canvas,
            #selfie-preview {
                height: 360px;
                min-height: 360px;
            }
        }
    </style>

    @php
        $headerDateLabel = $selectedDate->locale('id')->isoFormat('dddd, D MMMM YYYY');
    @endphp

    <div class="sticky-header">
        <div class="page-heading">
            <h5>Presensi</h5>
            <small>{{ $headerDateLabel }}</small>
        </div>

        <div class="realtime-clock-card">
            <div class="clock-time" id="realtimeClock">--:--:--</div>
            {{-- <div class="clock-caption">Waktu lokal Asia/Jakarta</div> --}}
        </div>
    </div>

    <!-- User Location Map -->
    <div class="presensi-form">
        <!-- Header -->
        {{-- <div class="presensi-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Presensi Digital</h6>
                    <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
                    <div class="school-meta">
                        <i class="bx bx-user-circle"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                </div>
                <img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg') }}"
                     class="rounded-circle border border-white" width="42" height="42" alt="User">
            </div>
        </div> --}}
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-map-pin"></i>
            </div>
            <h6 class="section-title mb-0">Lokasi Anda Saat Ini</h6>
        </div>
        <div class="user-location-map-container" style="height: 220px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 2px solid rgba(14, 133, 73, 0.1);">
            <div id="map-placeholder" class="map-placeholder">
                <i class="bx bx-map"></i>
                <span>Menunggu data lokasi...<br>Peta akan muncul setelah GPS aktif</span>
            </div>
            <div id="user-location-map" style="height: 100%; width: 100%;"></div>
        </div>
        <div class="mt-2 text-center">
            <small class="text-muted" style="font-size: 10px;">
                <i class="bx bx-info-circle me-1"></i>
                Titik hijau menunjukkan lokasi Anda saat ini
            </small>
        </div>
    </div>

    <!-- Status Card -->
    @php
        $user = Auth::user();
        $isPenjagaSekolah = $user->ketugasan === 'penjaga sekolah';

        // For penjaga sekolah, check for any open presensi regardless of date
        if ($isPenjagaSekolah) {
            $openPresensi = \App\Models\Presensi::where('user_id', $user->id)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->orderBy('tanggal', 'desc')
                ->first();
        }
    @endphp

    @if($isHoliday && !$isPenjagaSekolah)
    <div class="alert-custom warning">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-calendar-x"></i>
            </div>
            <div>
                <h6 class="mb-0">Hari Libur</h6>
                <p class="mb-0">{{ $holiday->name ?? 'Hari ini libur' }}</p>
            </div>
        </div>
    </div>

    @elseif(($presensiHariIni && $presensiHariIni->count() > 0) || ($isPenjagaSekolah && isset($openPresensi)))
    <div class="status-card success">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div class="w-100">
                <h6 class="mb-1">Presensi Sudah Dicatat</h6>
                @if($isPenjagaSekolah && isset($openPresensi))
                    <div class="status-detail-list">
                        <div class="status-detail-item">
                            <small>{{ $openPresensi->madrasah?->name ?? 'Madrasah' }} • {{ \Carbon\Carbon::parse($openPresensi->tanggal)->format('d/m/Y') }}</small>
                            <p>Masuk: <strong>{{ $openPresensi->waktu_masuk->format('H:i') }}</strong></p>
                        </div>
                        @if($openPresensi->keterangan)
                        <div class="status-detail-item">
                            <small>Keterangan</small>
                            <p><strong>{{ $openPresensi->keterangan }}</strong></p>
                        </div>
                        @endif
                    </div>
                    <p class="status-inline-note mb-0">Belum presensi keluar. Lakukan presensi keluar jika sudah selesai.</p>
                @else
                    <div class="status-detail-list">
                        @foreach($presensiHariIni as $presensi)
                        <div class="status-detail-item">
                            <small>{{ $presensi->madrasah?->name ?? 'Madrasah' }} • {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') }}</small>
                            @if($presensi->waktu_masuk)
                            <p>Masuk: <strong>{{ $presensi->waktu_masuk->format('H:i') }}</strong></p>
                            @if($presensi->waktu_keluar)
                            <p>Keluar: <strong>{{ $presensi->waktu_keluar->format('H:i') }}</strong></p>
                            @else
                            <p class="text-muted">Belum presensi keluar</p>
                            @endif
                            @if($presensi->keterangan)
                            <p class="text-muted">Keterangan: <strong>{{ $presensi->keterangan }}</strong></p>
                            @endif
                            @else
                            <p>Masuk: <strong>-</strong></p>
                            <p class="text-muted">Belum presensi masuk</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @if($presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count())
                    <div class="alert-custom success" style="margin-top: 6px; padding: 4px;">
                        <small><i class="bx bx-check me-1"></i> Semua presensi hari ini lengkap!</small>
                    </div>
                    @else
                    <p class="status-inline-note mb-0">Lakukan presensi keluar jika sudah selesai.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Presensi Form -->
    <div class="presensi-form">
        <div class="section-header">
            <div class="section-header-main">
                <div class="status-icon">
                    <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }}"></i>
                </div>
        @php
            $showKeluar = false;
            if ($isPenjagaSekolah && isset($openPresensi)) {
                $showKeluar = true;
            } elseif ($presensiHariIni && $presensiHariIni->count() > 0) {
                $showKeluar = $presensiHariIni->where('waktu_keluar', null)->count() > 0;
            }
        @endphp
                <h6 class="section-title mb-0">{{ $showKeluar ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
            </div>
            <div id="location-info" class="location-info location-badge info">
                <span class="badge-icon"><i class="bx bx-loader-alt bx-spin"></i></span>
                <span class="badge-title">GPS aktif</span>
            </div>
        </div>

        <!-- Coordinates -->
        {{-- <div class="form-section">
            <div class="d-flex align-items-center mb-1">
                <i class="bx bx-target-lock text-success me-1"></i>
                <label class="section-title mb-0">Koordinat Lokasi</label>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                <input type="text" id="latitude" class="coordinate-input" placeholder="Latitude" readonly>
                <input type="text" id="longitude" class="coordinate-input" placeholder="Longitude" readonly>
            </div>
        </div> --}}

        <!-- Address -->
        <div class="form-section">
            <div class="compact-section-head">
                <label class="section-title">Alamat Lokasi</label>
            </div>
            <div class="address-compact">
                <i class="bx bx-map-pin"></i>
                <input type="text" id="lokasi" class="address-input" placeholder="Alamat akan muncul otomatis" readonly>
            </div>
        </div>

        <!-- Selfie Section -->
        {{-- <div class="form-section">
            <div class="d-flex align-items-center mb-1">
                <i class="bx bx-camera text-primary me-1"></i>
                <label class="section-title mb-0">Presensi Selfie </label>
            </div>
            <div class="selfie-callout">
                <i class="bx bx-camera"></i>
                <div>
                    <strong>Selfie dilakukan di modal</strong>
                    <span>Tekan tombol presensi di bagian bawah, ambil foto, lalu kirim untuk menyelesaikan presensi.</span>
                </div>
            </div>
        </div> --}}

        <!-- Presensi Button -->
        @php
            $isDisabled = false;
            $buttonText = 'Presensi Sekarang';
            $buttonIcon = 'check-circle';

            if ($isPenjagaSekolah) {
                // For penjaga sekolah, always allow presensi
                $isDisabled = false;
                $buttonText = 'Presensi Sekarang';
            } elseif ($isHoliday) {
                $isDisabled = true;
                $buttonText = 'Hari Libur - Presensi Ditutup';
                $buttonIcon = 'calendar-x';
            } elseif ($presensiHariIni && $presensiHariIni->count() > 0) {
                $allComplete = $presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count();
                $isDisabled = $allComplete;
                $buttonText = $allComplete ? 'Presensi Lengkap' : 'Presensi Sekarang';
            }
        @endphp
    </div>

    <!-- Time Information -->
    @if($isPenjagaSekolah)
    <div class="schedule-section">
        <div class="compact-section-head">
            <h6 class="section-title">Jadwal Presensi</h6>
        </div>
        <div class="schedule-item pulang">
            <h6 class="text-success mb-1">Penjaga Sekolah</h6>
            <p>24 Jam</p>
            <small>Masuk dan keluar dapat dilakukan kapan saja.</small>
        </div>
    </div>
    @elseif(isset($timeRanges) && $timeRanges)
    <div class="schedule-section">
        <div class="compact-section-head">
            <h6 class="section-title">Jadwal Presensi</h6>
        </div>
        @php
            // prefer madrasah-specific values when present
            $ms = $user->madrasah ?? null;
            $masukStart = $ms && $ms->presensi_masuk_start ? \Carbon\Carbon::parse($ms->presensi_masuk_start)->format('H:i') : ($timeRanges['masuk_start'] ? \Carbon\Carbon::parse($timeRanges['masuk_start'])->format('H:i') : '-');
            $masukEnd = $ms && $ms->presensi_masuk_end ? \Carbon\Carbon::parse($ms->presensi_masuk_end)->format('H:i') : '07:00';
            $pulangStart = null;
            $pulangEnd = $ms && $ms->presensi_pulang_end ? \Carbon\Carbon::parse($ms->presensi_pulang_end)->format('H:i') : ($timeRanges['pulang_end'] ? \Carbon\Carbon::parse($timeRanges['pulang_end'])->format('H:i') : '22:00');

            // Day specific overrides
            $dayOfWeek = \Carbon\Carbon::parse($selectedDate)->dayOfWeek; // 0=Sun,5=Fri,6=Sat
            if ($ms) {
                if ($dayOfWeek == 5 && $ms->presensi_pulang_jumat) {
                    $pulangStart = \Carbon\Carbon::parse($ms->presensi_pulang_jumat)->format('H:i');
                } elseif ($dayOfWeek == 6 && $ms->presensi_pulang_sabtu) {
                    $pulangStart = \Carbon\Carbon::parse($ms->presensi_pulang_sabtu)->format('H:i');
                } elseif ($ms->presensi_pulang_start) {
                    $pulangStart = \Carbon\Carbon::parse($ms->presensi_pulang_start)->format('H:i');
                }
            }

            // fallback to controller-provided range if still null
            if (!$pulangStart) {
                $pulangStart = $timeRanges['pulang_start'] ? \Carbon\Carbon::parse($timeRanges['pulang_start'])->format('H:i') : '-';
            }
        @endphp

        <div class="schedule-grid">
            <div class="schedule-item masuk">
                <h6 class="text-primary">Masuk</h6>
                <p>{{ $masukStart }} - {{ $masukEnd }}</p>
                <small>Terlambat setelah 07:00</small>
            </div>
            <div class="schedule-item pulang">
                <h6 class="text-success">Pulang</h6>
                <p>{{ $pulangStart }} - {{ $pulangEnd }}</p>
                <small>Mulai pukul {{ $pulangStart }}</small>
            </div>
        </div>
        <div class="compact-note">
            @if($ms && $ms->hari_kbm == '6' && $dayOfWeek == 6 && !$ms->presensi_pulang_sabtu)
                Jam pulang Sabtu belum diatur pada data madrasah.
            @elseif($ms && $ms->hari_kbm == '6' && $dayOfWeek == 5 && !$ms->presensi_pulang_jumat)
                Jam pulang Jumat masih memakai pengaturan umum.
            @else
                Jadwal ditampilkan sesuai pengaturan madrasah untuk hari ini.
            @endif
        </div>
    </div>
    @else
    <div class="alert-custom warning">
        <i class="bx bx-info-circle me-1"></i>
        <strong>Pengaturan Presensi:</strong> Hari KBM belum diatur. Hubungi admin.
    </div>
    @endif



    <!-- Important Notice -->
    {{-- <div class="alert-custom info">
        <div class="d-flex">
            <i class="bx bx-info-circle text-info me-1"></i>
            <div>
                <strong class="text-info">Informasi Sistem</strong>
                <p class="mb-0 text-muted">Pastikan Anda berada di lingkungan madrasah saat melakukan presensi. Sistem menggunakan validasi lokasi koordinat madrasah.</p>
            </div>
        </div>
    </div> --}}

    <div class="action-grid">
        <a href="{{ route('mobile.riwayat-presensi') }}" class="action-tile">
            <i class="bx bx-history"></i>
            <strong>Riwayat</strong>
        </a>

        <a href="{{ route('mobile.izin') }}" class="action-tile">
            <i class="bx bx-calendar-minus"></i>
            <strong>Izin</strong>
        </a>
        @if(Auth::user()->ketugasan === 'kepala madrasah/sekolah')
        <a href="{{ route('mobile.monitor-map') }}" class="action-tile">
            <i class="bx bx-map"></i>
            <strong>Monitor Map</strong>
        </a>
        @endif
    </div>

    <!-- Monitoring Presensi: Map View -->
    @if(Auth::user()->ketugasan === 'kepala madrasah/sekolah')
    <div class="presensi-form">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-map"></i>
            </div>
            <h6 class="section-title mb-0">Monitoring Lokasi Presensi</h6>
        </div>

        <!-- Map Container -->
        <div id="presensi-map" style="height: 300px; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>

        <!-- Legend -->
        <div class="d-flex justify-content-center mt-2" style="gap: 12px;">
            <div class="d-flex align-items-center">
                <div style="width: 12px; height: 12px; background: #0e8549; border-radius: 50%; margin-right: 4px;"></div>
                <small style="font-size: 10px;">Sudah Presensi</small>
            </div>
            <div class="d-flex align-items-center">
                <div style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%; margin-right: 4px;"></div>
                <small style="font-size: 10px;">Belum Presensi</small>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="info-grid mt-2">
            <div class="metric-card">
                <div class="metric-label">Sudah Presensi</div>
                <div class="metric-value" style="color: #0e8549;">{{ $presensis->count() }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Belum Presensi</div>
                <div class="metric-value" style="color: #dc3545;">{{ $belumPresensi->count() }}</div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="presensi-action-bar">
    <div class="presensi-action-bar-inner">
        {{-- <div class="presensi-action-hint">Selfie dan konfirmasi presensi dilakukan pada langkah berikutnya.</div> --}}
        <button type="button" id="btn-presensi"
                class="presensi-btn"
                disabled
                {{ $isDisabled ? 'disabled' : '' }}>
            <i class="bx bx-{{ $buttonIcon }} me-1"></i>
            {{ $buttonText }}
        </button>
    </div>
</div>

<div id="selfie-modal" class="selfie-modal" aria-hidden="true">
    <div class="selfie-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="selfie-modal-title">
        <div class="selfie-modal-header">
            <div>
                <div id="selfie-modal-title" class="selfie-modal-title">Selfie Presensi</div>
                {{-- <div id="selfie-modal-subtitle" class="selfie-modal-subtitle">Ambil foto selfie lalu kirim presensi.</div> --}}
            </div>
            <button type="button" id="btn-close-selfie-modal" class="selfie-modal-close" aria-label="Tutup">
                <i class="bx bx-x"></i>
            </button>
        </div>

        <div class="selfie-modal-body">
            <div class="selfie-note">
                <i class="bx bx-info-circle"></i>
                Pastikan wajah terlihat jelas dan foto diambil di lingkungan madrasah/sekolah.
            </div>

            <div id="selfie-container" class="selfie-stage">
                <div class="selfie-placeholder">
                    <i class="bx bx-camera"></i>
                    <strong class="mb-1">Menyiapkan kamera</strong>
                    <span>Izinkan akses kamera jika diminta.</span>
                </div>
                <video id="selfie-video" autoplay playsinline style="display: none; object-fit: cover;"></video>
                <canvas id="selfie-canvas" style="display: none;"></canvas>
                <img id="selfie-preview" style="object-fit: cover; display: none;" alt="Selfie Preview">

                <div class="selfie-overlay-actions">
                    <button type="button" id="btn-capture-selfie" class="btn btn-primary-custom" style="display: none;">
                        <i class="bx bx-camera me-1"></i>Ambil Foto
                    </button>
                    <button type="button" id="btn-retake-selfie" class="btn btn-outline-secondary" style="display: none;">
                        <i class="bx bx-refresh me-1"></i>Ulang
                    </button>
                </div>
            </div>

            <input type="hidden" id="selfie-data" name="selfie_data">
            <div id="selfie-status" class="location-info info" style="margin-top: 12px;">
                <div class="d-flex align-items-center">
                    <i class="bx bx-camera-off me-1"></i>
                    <div>
                        <strong>Selfie belum diambil</strong>
                        <br><small class="text-muted">Kamera akan aktif otomatis saat modal dibuka.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="selfie-modal-footer">
            <button type="button" id="btn-submit-presensi"
                    class="presensi-btn"
                    style="display: none; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <i class="bx bx-send me-1"></i>
                Kirim Presensi
            </button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
window.addEventListener('load', function() {
    function updateRealtimeClock() {
        const clockEl = document.getElementById('realtimeClock');
        if (!clockEl) return;

        const formatter = new Intl.DateTimeFormat('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
            timeZone: 'Asia/Jakarta'
        });

        clockEl.textContent = formatter.format(new Date());
    }

    updateRealtimeClock();
    setInterval(updateRealtimeClock, 1000);

    function setLocationIndicator(state, title, iconClass = 'bx bx-loader-alt bx-spin') {
        const indicator = document.getElementById('location-info');
        if (!indicator) return;

        indicator.className = `location-info location-badge ${state}`;
        indicator.innerHTML = `
            <span class="badge-icon"><i class="${iconClass}"></i></span>
            <span class="badge-title">${title}</span>
        `;
    }

    setLocationIndicator('info', 'GPS aktif', 'bx bx-loader-alt bx-spin');

    let latitude, longitude, lokasi;
    let locationReadings = [];
    let readingCount = 0;
    const totalReadings = 3;
    const readingInterval = 1200;
    const presensiActionLocked = {{ $isDisabled ? 'true' : 'false' }};

    // Presensi mode: apakah tombol saat ini adalah untuk keluar (checkout)
    const isPresensiKeluar = {{ isset($showKeluar) && $showKeluar ? 'true' : 'false' }};
    // Determine pulang start time to be used for early-check detection.
    // Prefer madrasah-specific overrides, else use controller-provided timeRanges
    let pulangStartStr = null;
    @php
        $ms = Auth::user()->madrasah ?? null;
        $dayOfWeek = \Carbon\Carbon::parse($selectedDate)->dayOfWeek;
        if ($ms) {
            if ($dayOfWeek == 5 && $ms->presensi_pulang_jumat) {
                $ps = \Carbon\Carbon::parse($ms->presensi_pulang_jumat)->format('H:i');
            } elseif ($dayOfWeek == 6 && $ms->presensi_pulang_sabtu) {
                $ps = \Carbon\Carbon::parse($ms->presensi_pulang_sabtu)->format('H:i');
            } elseif ($ms->presensi_pulang_start) {
                $ps = \Carbon\Carbon::parse($ms->presensi_pulang_start)->format('H:i');
            } else {
                $ps = null;
            }
        } else {
            $ps = null;
        }
    @endphp
    pulangStartStr = @json($ps ?? ($timeRanges['pulang_start'] ?? null));

    function timeStringToSeconds(t) {
        if (!t) return null;
        // accepts HH:mm or HH:mm:ss
        const parts = t.split(':').map(Number);
        if (parts.length === 2) return parts[0]*3600 + parts[1]*60;
        if (parts.length === 3) return parts[0]*3600 + parts[1]*60 + parts[2];
        return null;
    }
    const pulangStartSeconds = timeStringToSeconds(pulangStartStr);

    // --- Notification helpers: request permission and show a local notification ---
    async function requestNotificationPermissionOnStart() {
        try {
            const isNative = window.Capacitor && window.Capacitor.getPlatform && window.Capacitor.getPlatform() !== 'web';

            if (isNative) {
                try {
                    const LocalNotifications = window.Capacitor?.Plugins?.LocalNotifications;
                    if (LocalNotifications && LocalNotifications.requestPermissions) {
                        const perm = await LocalNotifications.requestPermissions();
                        console.log('Capacitor LocalNotifications.requestPermissions ->', perm);
                        return;
                    }
                } catch (e) {
                    console.warn('Capacitor LocalNotifications request failed:', e);
                }
            }

            // Fallback to Web Notification API
            if ('Notification' in window) {
                if (Notification.permission === 'default') {
                    const p = await Notification.requestPermission();
                    console.log('Browser Notification permission ->', p);
                } else {
                    console.log('Browser Notification permission already:', Notification.permission);
                }
            }
        } catch (err) {
            console.warn('Notification permission request error:', err);
        }
    }

    // Prompt the user for notification permission on load (native first, then web fallback)
    try {
        requestNotificationPermissionOnStart();
    } catch (e) {
        console.warn('Request notification permission invocation failed:', e);
    }

    async function showLocalNotification(title, body) {
        try {
            const isNative = window.Capacitor && window.Capacitor.getPlatform && window.Capacitor.getPlatform() !== 'web';

            // Use Capacitor LocalNotifications when available (native)
            if (isNative) {
                try {
                    const LocalNotifications = window.Capacitor?.Plugins?.LocalNotifications;
                    if (LocalNotifications) {
                        // Create Android channel if API available (safer for Android 8+)
                        if (LocalNotifications.createChannel) {
                            try {
                                await LocalNotifications.createChannel({
                                    id: 'nuist-channel',
                                    name: 'Nuist Notifications',
                                    importance: 5,
                                    description: 'Notifikasi dari aplikasi Nuist'
                                });
                                console.log('LocalNotifications: channel nuist-channel created');
                            } catch (chErr) {
                                console.warn('LocalNotifications.createChannel failed:', chErr);
                            }
                        }

                        const id = Date.now() % 2147483647;
                        // include channelId for Android when supported
                        const notif = { id: id, title: title, body: body };
                        if (window.Capacitor.getPlatform() === 'android') notif.channelId = 'nuist-channel';

                        await LocalNotifications.schedule({ notifications: [notif] });
                        console.log('Capacitor LocalNotifications scheduled ->', notif);
                        return;
                    }
                } catch (e) {
                    console.warn('Capacitor LocalNotifications schedule failed:', e);
                }
            }

            // Web Notification fallback
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(title, { body: body });
                console.log('Web Notification shown');
            } else {
                console.log('Web Notification not shown: permission=', (window.Notification && Notification.permission));
            }
        } catch (err) {
            console.warn('showLocalNotification error:', err);
        }
    }



    // Map variables
    let userLocationMap = null;
    let userLocationMarker = null;

    // Function to collect location readings
    function collectLocationReading(readingNumber) {
        return new Promise((resolve, reject) => {
            // Add timeout wrapper for additional safety
            const timeoutId = setTimeout(() => {
                reject(new Error(`Reading ${readingNumber} timed out`));
            }, 15000); // 15 second total timeout

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    clearTimeout(timeoutId); // Clear timeout on success

                    const reading = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        timestamp: Date.now(),
                        accuracy: position.coords.accuracy,
                        altitude: position.coords.altitude,
                        speed: position.coords.speed
                    };

                    // Store in sessionStorage
                    sessionStorage.setItem(`reading${readingNumber}_latitude`, reading.latitude);
                    sessionStorage.setItem(`reading${readingNumber}_longitude`, reading.longitude);
                    sessionStorage.setItem(`reading${readingNumber}_timestamp`, reading.timestamp);
                    sessionStorage.setItem(`reading${readingNumber}_accuracy`, reading.accuracy);
                    sessionStorage.setItem(`reading${readingNumber}_altitude`, reading.altitude || null);
                    sessionStorage.setItem(`reading${readingNumber}_speed`, reading.speed || null);

                    locationReadings.push(reading);
                    readingCount++;

                    // Update UI with smooth progress
                    const isComplete = readingCount >= totalReadings;
                    setLocationIndicator(
                        isComplete ? 'success' : 'info',
                        isComplete ? 'Lokasi siap' : `GPS ${readingCount}/${totalReadings}`,
                        isComplete ? 'bx bx-check-circle' : 'bx bx-loader-alt bx-spin'
                    );

                    // Update coordinates display with latest reading
                    $('#latitude').val(reading.latitude.toFixed(6));
                    $('#longitude').val(reading.longitude.toFixed(6));

                    // Update user location map
                    updateUserLocationMap(reading.latitude, reading.longitude);

                    // Get address from latest reading
                    getAddressFromCoordinates(reading.latitude, reading.longitude);

                    resolve(reading);
                },
                function(error) {
                    clearTimeout(timeoutId); // Clear timeout on error
                    console.warn(`Reading ${readingNumber} failed:`, error);

                    // Provide user-friendly error message
                    const errorMessage = error.code === 1 ? 'Izin lokasi ditolak' :
                                       error.code === 2 ? 'Sinyal GPS lemah' :
                                       error.code === 3 ? 'Waktu habis' : 'Error tidak diketahui';

                    setLocationIndicator('warning', 'GPS lemah', 'bx bx-error-circle');

                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000, // 10 second timeout for each reading
                    maximumAge: 30000
                }
            );
        });
    }

    // Start collecting multiple readings - enhanced for reliability with GPS fallback
    async function startLocationCollection() {
        let successfulReadings = 0;
        let lastSuccessfulReading = null;
        let consecutiveFailures = 0;
        const maxConsecutiveFailures = 3;

        try {
            for (let i = 1; i <= totalReadings; i++) {
                try {
                    const reading = await collectLocationReading(i);
                    successfulReadings++;
                    lastSuccessfulReading = reading;
                    consecutiveFailures = 0; // Reset failure counter

                    // Enable presensi button after first successful reading
                    if (successfulReadings === 1) {
                        latitude = reading.latitude;
                        longitude = reading.longitude;

                        // Enable presensi button early
                        if (!presensiActionLocked) {
                            $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-1"></i>Presensi Sekarang');
                        }
                    }

                    // Wait between readings (except for the last one)
                    if (i < totalReadings) {
                        await new Promise(resolve => setTimeout(resolve, readingInterval));
                    }
                } catch (readingError) {
                    console.warn(`Reading ${i} failed:`, readingError);
                    consecutiveFailures++;

                    // If too many consecutive failures, try alternative approach
                    if (consecutiveFailures >= maxConsecutiveFailures) {
                        console.log('Too many consecutive failures, trying alternative GPS settings...');
                        await tryAlternativeGPSApproach(i);
                        consecutiveFailures = 0; // Reset after alternative attempt
                        i--; // Retry the same reading number
                        continue;
                    }

                    // Continue to next reading instead of failing completely
                    // Add a small delay before next attempt
                    if (i < totalReadings) {
                        await new Promise(resolve => setTimeout(resolve, 2000));
                    }
                    continue;
                }
            }

            // Final status update based on successful readings
            if (successfulReadings > 0) {
                latitude = lastSuccessfulReading.latitude;
                longitude = lastSuccessfulReading.longitude;

                setLocationIndicator('success', 'Lokasi siap', 'bx bx-check-circle');
            } else {
                // No successful readings at all - provide detailed troubleshooting
                await showGPSTroubleshootingGuide();
                return;
            }

        } catch (error) {
            console.error('Critical error in location collection:', error);
            await showGPSTroubleshootingGuide();
        }
    }

    // Alternative GPS approach for when standard geolocation fails
    async function tryAlternativeGPSApproach(readingNumber) {
        return new Promise((resolve, reject) => {
            // Try with different settings
            const alternativeTimeout = setTimeout(() => {
                reject(new Error('Alternative GPS approach timed out'));
            }, 20000);

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    clearTimeout(alternativeTimeout);
                    console.log('Alternative GPS approach succeeded');

                    const reading = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        timestamp: Date.now(),
                        accuracy: position.coords.accuracy,
                        altitude: position.coords.altitude,
                        speed: position.coords.speed
                    };

                    // Update UI with success message
                    setLocationIndicator('success', 'GPS alternatif', 'bx bx-check-circle');

                    resolve(reading);
                },
                function(error) {
                    clearTimeout(alternativeTimeout);
                    reject(error);
                },
                {
                    enableHighAccuracy: false, // Try without high accuracy first
                    timeout: 15000,
                    maximumAge: 60000 // Allow older cached positions
                }
            );
        });
    }

    // Comprehensive GPS troubleshooting guide
    async function showGPSTroubleshootingGuide() {
        setLocationIndicator('error', 'GPS error', 'bx bx-error-circle');

        $('#btn-presensi').prop('disabled', true).html('<i class="bx bx-error me-1"></i>GPS Error');

        // Auto-retry after 10 seconds
        setTimeout(() => {
            if (locationReadings.length === 0) {
                console.log('Auto-retrying GPS collection...');
                setLocationIndicator('info', 'Coba lagi', 'bx bx-loader-alt bx-spin');
                startLocationCollection();
            }
        }, 10000);
    }

    // Initialize user location map
    function initializeUserLocationMap() {
        // Defensive: avoid initializing the same Leaflet container more than once.
        const container = document.getElementById('user-location-map');
        if (!container) return;
        // If Leaflet already attached an id to the element, skip initialization
        if (container._leaflet_id) {
            // Remove existing map instance if it exists
            if (userLocationMap) {
                userLocationMap.remove();
                userLocationMap = null;
            }
        }
        if (userLocationMap) return; // Already initialized

        userLocationMap = L.map('user-location-map').setView([-6.2, 106.816666], 13); // Default to Jakarta

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(userLocationMap);

        // Hide placeholder
        $('#map-placeholder').hide();
    }

    // Update user location map
    function updateUserLocationMap(lat, lng) {
        if (!userLocationMap) {
            initializeUserLocationMap();
        }

        if (!userLocationMap) return; // Still not initialized

        // Remove existing marker
        if (userLocationMarker) {
            userLocationMap.removeLayer(userLocationMarker);
        }

        // Add new marker
        userLocationMarker = L.marker([lat, lng]).addTo(userLocationMap)
            .bindPopup('Lokasi Anda saat ini')
            .openPopup();

        // Center map on location
        userLocationMap.setView([lat, lng], 16);
    }

        // Check if geolocation is supported
        if (navigator.geolocation) {
            startLocationCollection();
        } else {
            setLocationIndicator('error', 'GPS tidak didukung', 'bx bx-error-circle');
            $('#btn-presensi').prop('disabled', true).html('<i class="bx bx-error me-1"></i>GPS Tidak Didukung');
        }

    // Get address from coordinates
    function getAddressFromCoordinates(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    lokasi = data.display_name;
                    $('#lokasi').val(lokasi);
                }
            })
            .catch(error => {
                console.error('Error getting address:', error);
                $('#lokasi').val('Tidak dapat mendapatkan alamat');
            });
    }

















    // Selfie variables
    let selfieStream = null;
    let selfieCaptured = false;
    const selfieModal = document.getElementById('selfie-modal');
    const selfieModalSubtitle = document.getElementById('selfie-modal-subtitle');

    function stopSelfieStream() {
        if (selfieStream) {
            selfieStream.getTracks().forEach(track => track.stop());
            selfieStream = null;
        }
    }

    function resetSelfieModalState() {
        const video = document.getElementById('selfie-video');
        const preview = document.getElementById('selfie-preview');
        const canvas = document.getElementById('selfie-canvas');
        const captureBtn = document.getElementById('btn-capture-selfie');
        const retakeBtn = document.getElementById('btn-retake-selfie');
        const submitBtn = document.getElementById('btn-submit-presensi');
        const selfieDataInput = document.getElementById('selfie-data');
        const statusElement = document.getElementById('selfie-status');
        const placeholder = document.querySelector('#selfie-container .selfie-placeholder');

        if (video) {
            video.style.display = 'none';
            video.srcObject = null;
        }
        if (preview) {
            preview.style.display = 'none';
            preview.src = '';
        }
        if (canvas) {
            canvas.style.display = 'none';
        }
        if (captureBtn) {
            captureBtn.style.display = 'none';
        }
        if (retakeBtn) {
            retakeBtn.style.display = 'none';
        }
        if (submitBtn) {
            submitBtn.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Kirim Presensi';
        }
        if (selfieDataInput) {
            selfieDataInput.value = '';
        }
        if (placeholder) {
            placeholder.style.display = 'flex';
        }
        if (statusElement) {
            statusElement.innerHTML = `
                <div class="location-info info">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-camera-off me-1"></i>
                        <div>
                            <strong>Selfie belum diambil</strong>
                            <br><small class="text-muted">Kamera akan aktif otomatis saat modal dibuka.</small>
                        </div>
                    </div>
                </div>
            `;
        }

        stopSelfieStream();
        selfieCaptured = false;
    }

    function openSelfieModal() {
        if (!selfieModal) return;

        resetSelfieModalState();
        selfieModal.classList.add('show');
        selfieModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('selfie-modal-open');

        if (selfieModalSubtitle) {
            selfieModalSubtitle.textContent = isPresensiKeluar
                ? 'Ambil foto selfie untuk presensi keluar, lalu kirim.'
                : 'Ambil foto selfie untuk presensi masuk, lalu kirim.';
        }
    }

    function closeSelfieModal(resetState = true) {
        if (!selfieModal) return;

        if (resetState) {
            resetSelfieModalState();
        } else {
            stopSelfieStream();
        }
        selfieModal.classList.remove('show');
        selfieModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('selfie-modal-open');
    }

    // Initialize selfie camera
    async function initializeSelfieCamera() {
        try {
            // Check if required DOM elements exist
            const video = document.getElementById('selfie-video');
            const container = document.getElementById('selfie-container');
            const captureBtn = document.getElementById('btn-capture-selfie');
            const statusElement = document.getElementById('selfie-status');

            if (!video || !container || !captureBtn || !statusElement) {
                console.error('Required DOM elements for selfie camera not found');
                throw new Error('DOM elements not ready');
            }

            stopSelfieStream();

            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 480 },
                    height: { ideal: 640 },
                    aspectRatio: 3/4
                }
            });
            selfieStream = stream;

            video.srcObject = stream;
            video.style.display = 'block';

            // Hide the placeholder and show video
            const placeholder = container.querySelector('.selfie-placeholder');
            if (placeholder) {
                placeholder.style.display = 'none';
            }

            // Show capture button
            captureBtn.style.display = 'block';

            // Update status to show camera is ready
            statusElement.innerHTML = `
                <div class="location-info success">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-camera me-1"></i>
                        <div>
                            <strong>Kamera aktif</strong>
                        </div>
                    </div>
                </div>
            `;

        } catch (error) {
            console.error('Error accessing camera:', error);
            const statusElement = document.getElementById('selfie-status');
            if (statusElement) {
                statusElement.innerHTML = `
                    <div class="location-info error">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error-circle me-1"></i>
                            <div>
                                <strong>Kamera tidak dapat diakses</strong>
                                <br><small class="text-muted">Pastikan memberikan izin kamera</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            throw error; // Re-throw to handle in calling function
        }
    }

    // Capture selfie
    function captureSelfie() {
        const video = document.getElementById('selfie-video');
        const canvas = document.getElementById('selfie-canvas');
        const ctx = canvas.getContext('2d');

        // Set canvas to portrait dimensions (3:4 aspect ratio)
        const aspectRatio = 3/4;
        const canvasWidth = Math.min(video.videoWidth, video.videoHeight * aspectRatio);
        const canvasHeight = canvasWidth / aspectRatio;

        canvas.width = canvasWidth;
        canvas.height = canvasHeight;

    // Center the image on canvas for portrait crop
    const sourceX = (video.videoWidth - canvasWidth) / 2;
    const sourceY = (video.videoHeight - canvasHeight) / 2;

    // Balik horizontal sebelum menggambar agar hasil tidak terbalik
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, sourceX, sourceY, canvasWidth, canvasHeight, 0, 0, canvasWidth, canvasHeight);
    ctx.setTransform(1, 0, 0, 1, 0, 0); // reset transform setelah menggambar

        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        const selfieDataInput = document.getElementById('selfie-data');
        const selfiePreview = document.getElementById('selfie-preview');

        if (selfieDataInput) {
            selfieDataInput.value = imageData;
        }

        if (selfiePreview) {
            selfiePreview.src = imageData;
            selfiePreview.style.display = 'block';
        }

        // Hide video and show preview
        if (video) {
            video.style.display = 'none';
        }

        // Hide capture button and show retake button
        const captureBtn = document.getElementById('btn-capture-selfie');
        const retakeBtn = document.getElementById('btn-retake-selfie');

        if (captureBtn) {
            captureBtn.style.display = 'none';
        }

        if (retakeBtn) {
            retakeBtn.style.display = 'block';
        }

        // Stop camera stream after capture to freeze result
        stopSelfieStream();

        selfieCaptured = true;
        document.getElementById('selfie-status').innerHTML = `
            <div class="location-info success">
                <div class="d-flex align-items-center">
                    <i class="bx bx-check-circle me-1"></i>
                    <div>
                        <strong>Selfie berhasil diambil</strong>
                        <br><small class="text-muted">Klik tombol "Kirim Presensi" untuk menyelesaikan</small>
                    </div>
                </div>
            </div>
        `;

        // Show submit button after selfie is captured
        setTimeout(() => {
            // Verify selfie data is set before proceeding
            const selfieData = document.getElementById('selfie-data').value;
            console.log('Selfie captured with data length:', selfieData.length);

            if (selfieData && selfieData.length > 100) {
                $('#btn-submit-presensi').show();
                $('#btn-submit-presensi').prop('disabled', false);
            } else {
                console.error('Selfie data not properly set, length:', selfieData.length);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Foto selfie tidak berhasil diambil. Silakan coba lagi.',
                    confirmButtonText: 'Oke'
                });
            }
        }, 1000);
    }

    // Retake selfie
    function retakeSelfie() {
        const selfiePreview = document.getElementById('selfie-preview');
        const selfieDataInput = document.getElementById('selfie-data');

        if (selfiePreview) {
            selfiePreview.style.display = 'none';
        }

        if (selfieDataInput) {
            selfieDataInput.value = '';
        }

        selfieCaptured = false;
        $('#btn-submit-presensi').hide();
        initializeSelfieCamera();
    }

    // Event listeners for selfie buttons
    const captureBtn = document.getElementById('btn-capture-selfie');
    const retakeBtn = document.getElementById('btn-retake-selfie');

    if (captureBtn) {
        captureBtn.addEventListener('click', captureSelfie);
    }

    if (retakeBtn) {
        retakeBtn.addEventListener('click', retakeSelfie);
    }

    // Handle presensi button (Ambil Selfie)
    $('#btn-presensi').click(async function() {
        try {
            openSelfieModal();
            await initializeSelfieCamera();
        } catch (error) {
            closeSelfieModal();
            Swal.fire({
                icon: 'error',
                title: 'Kamera Tidak Dapat Diakses',
                text: 'Tidak dapat mengakses kamera. Pastikan memberikan izin kamera dan coba lagi.',
                confirmButtonText: 'Oke'
            });
        }
    });

    $('#btn-close-selfie-modal').click(function() {
        closeSelfieModal();
    });

    if (selfieModal) {
        selfieModal.addEventListener('click', function(event) {
            if (event.target === selfieModal) {
                closeSelfieModal();
            }
        });
    }

    // Handle submit presensi button
    $('#btn-submit-presensi').click(async function() {
        // If selfie is already captured, proceed with location validation
        // If this action is a checkout and current time is before pulangStart, ask for confirmation
        if (isPresensiKeluar && pulangStartSeconds) {
            const now = new Date();
            const nowSeconds = now.getHours()*3600 + now.getMinutes()*60 + now.getSeconds();
            if (nowSeconds < pulangStartSeconds) {
                const res = await Swal.fire({
                    title: 'Pulang Awal',
                    text: 'Apakah Anda yakin ingin melakukan presensi pulang sebelum waktunya?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, saya yakin',
                    cancelButtonText: 'Batal'
                });
                if (!res.isConfirmed) {
                    return; // user cancelled early checkout
                }
            }
        }
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Data lokasi belum lengkap. Pastikan GPS aktif dan tunggu proses pengumpulan data selesai.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        // Allow presensi even if location reading failed (single reading is enough)
        if (locationReadings.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Tidak dapat mendapatkan lokasi. Pastikan GPS aktif dan coba lagi.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        closeSelfieModal(false);
        Swal.fire({
            title: 'Memproses Presensi',
            text: 'Mohon tunggu, data sedang dikirim.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Get final location reading (button click) as reading4
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let reading4Lat = position.coords.latitude;
                let reading4Lng = position.coords.longitude;
                let reading4Timestamp = Date.now();

                // Build location readings array - include multiple readings for validation
                let allReadings = locationReadings.slice(); // Copy existing readings

                // Add final reading on button click
                allReadings.push({
                    latitude: reading4Lat,
                    longitude: reading4Lng,
                    timestamp: reading4Timestamp,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed
                });

                const selfieDataInput = document.getElementById('selfie-data');
                let selfieDataValue = selfieDataInput ? selfieDataInput.value : '';
                console.log('Final selfie data length:', selfieDataValue.length);
                console.log('Final selfie data starts with:', selfieDataValue.substring(0, 50));

                // Ensure selfie data is valid before sending
                if (!selfieDataValue || selfieDataValue.length < 100) {
                    console.error('Selfie data validation failed, length:', selfieDataValue.length);
                    $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Data foto selfie tidak valid. Silakan ambil foto lagi.',
                        confirmButtonText: 'Oke'
                    });
                    return;
                }

                let postData = {
                    _token: '{{ csrf_token() }}',
                    latitude: reading4Lat,
                    longitude: reading4Lng,
                    lokasi: lokasi,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed,
                    device_info: navigator.userAgent,
                    location_readings: JSON.stringify(allReadings),
                    selfie_data: selfieDataValue
                };

                // Update UI with final location data
                $('#latitude').val(reading4Lat.toFixed(6));
                $('#longitude').val(reading4Lng.toFixed(6));

                // Update user location map with final position
                if (userLocationMap) {
                    updateUserLocationMap(reading4Lat, reading4Lng);
                }

                // Get address
                getAddressFromCoordinates(reading4Lat, reading4Lng);

                setLocationIndicator('success', 'Lokasi valid', 'bx bx-check-circle');

                $.ajax({
                    url: '{{ route("mobile.presensi.store") }}',
                    method: 'POST',
                    data: postData,
                    timeout: 30000,
                    success: function(resp) {
                        if (resp && resp.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: resp.message || 'Presensi berhasil dicatat',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                resetSelfieModalState();
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: resp.message || 'Gagal melakukan presensi. Coba lagi.',
                            });
                            resetSelfieModalState();
                            $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                        }
                    },
                    error: function(xhr, status, err) {
                        let message = 'Gagal menghubungi server.';
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: message
                        });
                        resetSelfieModalState();
                        $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                    }
                });

            },
            function(err){
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan GPS',
                    text: err.message || 'Tidak dapat mengambil lokasi terakhir.'
                });
                resetSelfieModalState();
                $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 });
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && selfieModal && selfieModal.classList.contains('show')) {
            closeSelfieModal();
        }
    });
});

// Initialize map for kepala madrasah monitoring
@if(Auth::user()->ketugasan === 'kepala madrasah/sekolah' && !empty($mapData))
document.addEventListener('DOMContentLoaded', function() {
    // Check if map is already initialized
    const container = document.getElementById('presensi-map');
    if (container && container._leaflet_id) {
        return; // Map already initialized
    }

    // Initialize map
    const map = L.map('presensi-map').setView([-6.2088, 106.8456], 13); // Default Jakarta

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Custom icons
    const presensiIcon = L.divIcon({
        html: '<div style="background: #0e8549; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.3);"></div>',
        className: 'custom-marker',
        iconSize: [12, 12],
        iconAnchor: [6, 6]
    });

    const belumPresensiIcon = L.divIcon({
        html: '<div style="background: #dc3545; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.3);"></div>',
        className: 'custom-marker',
        iconSize: [12, 12],
        iconAnchor: [6, 6]
    });

    // Add markers
    const mapData = @json($mapData);
    let bounds = [];

    mapData.forEach(function(user) {
        const lat = parseFloat(user.latitude);
        const lng = parseFloat(user.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            bounds.push([lat, lng]);

            const icon = user.marker_type === 'presensi' ? presensiIcon : belumPresensiIcon;

            const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

            // Create popup content
            let popupContent = `
                <div style="font-family: 'Poppins', sans-serif; font-size: 12px; max-width: 200px;">
                    <strong>${user.name}</strong><br>
                    <small style="color: #666;">${user.status_kepegawaian}</small><br>
                    <small><strong>Status:</strong> ${user.marker_type === 'presensi' ? 'Sudah Presensi' : 'Belum Presensi'}</small><br>
            `;

            if (user.marker_type === 'presensi') {
                popupContent += `
                    <small><strong>Masuk:</strong> ${user.waktu_masuk || '-'}</small><br>
                    <small><strong>Keluar:</strong> ${user.waktu_keluar || '-'}</small><br>
                `;
            }

            popupContent += `
                    <small><strong>Lokasi:</strong> ${user.lokasi}</small>
                </div>
            `;

            marker.bindPopup(popupContent);
        }
    });

    // Fit map to show all markers
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [20, 20] });
    }

    // Set minimum zoom level
    map.setMinZoom(10);
    map.setMaxZoom(18);
});
@endif
</script>

<script type="module" src="/js/presensi-mobile.js"></script>

@endsection
