@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

@section('content')
<div class="presensi-container">
    <style>
        /* Base Styles */
        body {
            background: #f5f7fa;
            font-family: 'Inter', 'Poppins', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #2d3748;
        }

        /* Layout Container */
        .presensi-container {
            max-width: 420px;
            margin: 0 auto;
            padding: 16px;
        }

        /* Header Section */
        .presensi-header {
            background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%);
            color: #fff;
            border-radius: 16px;
            padding: 20px 16px;
            box-shadow: 0 4px 20px rgba(43, 108, 176, 0.25);
            margin-bottom: 16px;
        }

        .presensi-header .d-flex {
            align-items: center;
        }

        .presensi-header h6 {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 4px;
            opacity: 0.9;
        }

        .presensi-header h5 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 0;
        }

        .presensi-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        /* Card Components */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f7fafc;
        }

        .card-icon {
            width: 32px;
            height: 32px;
            background: #f0f9ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .card-icon i {
            color: #3182ce;
            font-size: 16px;
        }

        .card-title {
            font-weight: 600;
            font-size: 14px;
            margin: 0;
            color: #2d3748;
        }

        /* Status Components */
        .status-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 16px;
            border-left: 4px solid #48bb78;
        }

        .status-card.success {
            border-left-color: #48bb78;
        }

        .status-card.warning {
            border-left-color: #ed8936;
        }

        .status-card .status-icon {
            background: #f0fff4;
            color: #48bb78;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
            color: #4a5568;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 6px;
            color: #a0aec0;
            font-size: 12px;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        }

        .form-input[readonly] {
            background: #f7fafc;
            cursor: not-allowed;
        }

        .coordinate-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        /* Map Container */
        .map-container {
            position: relative;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            margin-bottom: 12px;
        }

        .map-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 1;
        }

        .map-placeholder i {
            font-size: 32px;
            color: #cbd5e0;
            margin-bottom: 8px;
        }

        .map-placeholder span {
            font-size: 12px;
            color: #718096;
            text-align: center;
            font-weight: 500;
        }

        /* Selfie Section */
        .selfie-container {
            position: relative;
            margin-bottom: 12px;
        }

        .selfie-placeholder {
            width: 100%;
            max-width: 280px;
            height: 360px;
            border-radius: 12px;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border: 2px dashed #cbd5e0;
            margin: 0 auto;
        }

        .selfie-placeholder i {
            font-size: 48px;
            color: #a0aec0;
            margin-bottom: 12px;
        }

        .selfie-placeholder span {
            color: #718096;
            font-size: 14px;
            font-weight: 500;
        }

        .selfie-video, .selfie-preview {
            width: 100%;
            max-width: 280px;
            height: 360px;
            border-radius: 12px;
            object-fit: cover;
            margin: 0 auto;
            display: none;
        }

        .selfie-video {
            transform: scaleX(-1);
        }

        .selfie-canvas {
            display: none;
        }

        .selfie-btn {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            background: #3182ce;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 600;
            font-size: 13px;
            display: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(49, 130, 206, 0.3);
        }

        .selfie-btn.retake {
            right: 12px;
            left: auto;
            transform: none;
            background: #718096;
        }

        /* Status Messages */
        .status-message {
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .status-message i {
            margin-right: 8px;
            flex-shrink: 0;
        }

        .status-message.success {
            background: #f0fff4;
            border: 1px solid #9ae6b4;
            color: #22543d;
        }

        .status-message.success i {
            color: #48bb78;
        }

        .status-message.error {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            color: #742a2a;
        }

        .status-message.error i {
            color: #e53e3e;
        }

        .status-message.info {
            background: #ebf8ff;
            border: 1px solid #90cdf4;
            color: #2a4365;
        }

        .status-message.info i {
            color: #3182ce;
        }

        .status-message.warning {
            background: #fef5e7;
            border: 1px solid #fbd38d;
            color: #744210;
        }

        .status-message.warning i {
            color: #ed8936;
        }

        /* Buttons */
        .btn {
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%);
            color: #fff;
            width: 100%;
            margin-top: 12px;
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #2c5282 0%, #2c5282 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(43, 108, 176, 0.3);
        }

        .btn-primary:disabled {
            background: #a0aec0;
            cursor: not-allowed;
            transform: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
            color: #fff;
            width: 100%;
            display: none;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
            position: absolute;
            bottom: 12px;
            right: 12px;
            display: none;
        }

        /* Schedule Section */
        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
        }

        .schedule-item {
            background: #f7fafc;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            border-left: 3px solid #3182ce;
        }

        .schedule-item.pulang {
            border-left-color: #48bb78;
        }

        .schedule-item i {
            font-size: 16px;
            margin-bottom: 6px;
            display: block;
        }

        .schedule-item.masuk i {
            color: #3182ce;
        }

        .schedule-item.pulang i {
            color: #48bb78;
        }

        .schedule-item h6 {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #2d3748;
        }

        .schedule-item p {
            font-size: 11px;
            margin-bottom: 2px;
            color: #4a5568;
        }

        .schedule-item small {
            font-size: 10px;
            color: #718096;
        }

        /* Alert Components */
        .alert {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 16px;
            border-left: 4px solid #3182ce;
        }

        .alert.warning {
            border-left-color: #ed8936;
        }

        .alert.danger {
            border-left-color: #e53e3e;
        }

        .alert.info {
            border-left-color: #4299e1;
        }

        .alert.success {
            border-left-color: #48bb78;
        }

        /* Navigation Links */
        .nav-link {
            background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%);
            color: #fff;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(43, 108, 176, 0.3);
        }

        .nav-link i {
            margin-right: 8px;
        }

        /* Monitoring Section */
        .monitoring-map {
            height: 280px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: #f0fff4;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #9ae6b4;
        }

        .stat-card.absent {
            background: #fed7d7;
            border-color: #feb2b2;
        }

        .stat-number {
            font-weight: 700;
            font-size: 16px;
            color: #22543d;
            margin-bottom: 4px;
        }

        .stat-card.absent .stat-number {
            color: #742a2a;
        }

        .stat-label {
            font-size: 11px;
            color: #22543d;
            font-weight: 500;
        }

        .stat-card.absent .stat-label {
            color: #742a2a;
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #4a5568;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .legend-dot.present {
            background: #48bb78;
        }

        .legend-dot.absent {
            background: #e53e3e;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .presensi-container {
                padding: 12px;
            }

            .card {
                padding: 12px;
            }

            .schedule-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Header -->
    <div class="presensi-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Presensi Digital</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- User Location Map -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="bx bx-map-pin"></i>
            </div>
            <h6 class="card-title">Lokasi Anda Saat Ini</h6>
        </div>
        <div class="map-container">
            <div id="map-placeholder" class="map-placeholder">
                <i class="bx bx-map"></i>
                <span>Menunggu data lokasi...<br>Peta akan muncul setelah GPS aktif</span>
            </div>
            <div id="user-location-map" style="height: 100%; width: 100%;"></div>
        </div>
        <div class="text-center" style="margin-top: 12px;">
            <small style="color: #718096; font-size: 12px;">
                <i class="bx bx-info-circle" style="margin-right: 4px;"></i>
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
    <div class="alert warning">
        <div class="d-flex align-items-center">
            <i class="bx bx-calendar-x" style="margin-right: 8px;"></i>
            <div>
                <h6 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600;">Hari Libur</h6>
                <p style="margin: 0; font-size: 13px;">{{ $holiday->name ?? 'Hari ini libur' }}</p>
            </div>
        </div>
    </div>

    @elseif(($presensiHariIni && $presensiHariIni->count() > 0) || ($isPenjagaSekolah && isset($openPresensi)))
    <div class="status-card">
        <div class="d-flex align-items-center">
            <div class="card-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div style="flex: 1;">
                <h6 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600;">Presensi Sudah Dicatat</h6>
                @if($isPenjagaSekolah && isset($openPresensi))
                    <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid rgba(72, 187, 120, 0.2);">
                        <small style="color: #68d391;">{{ $openPresensi->madrasah?->name ?? 'Madrasah' }}</small>
                        <p style="margin: 4px 0;">Masuk: <strong>{{ $openPresensi->waktu_masuk->format('H:i') }}</strong> ({{ \Carbon\Carbon::parse($openPresensi->tanggal)->format('d/m/Y') }})</p>
                        <p style="margin: 0; color: #a0aec0; font-size: 13px;">Belum presensi keluar</p>
                    </div>
                    <p style="margin: 0; color: #a0aec0; font-size: 13px;">Lakukan presensi keluar jika sudah selesai.</p>
                @else
                    @foreach($presensiHariIni as $presensi)
                    <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid rgba(72, 187, 120, 0.2);">
                        <small style="color: #68d391;">{{ $presensi->madrasah?->name ?? 'Madrasah' }} ({{ \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') }})</small>
                        @if($presensi->waktu_masuk)
                        <p style="margin: 4px 0;">Masuk: <strong>{{ $presensi->waktu_masuk->format('H:i') }}</strong></p>
                        @if($presensi->waktu_keluar)
                        <p style="margin: 0;">Keluar: <strong>{{ $presensi->waktu_keluar->format('H:i') }}</strong></p>
                        @else
                        <p style="margin: 0; color: #a0aec0; font-size: 13px;">Belum presensi keluar</p>
                        @endif
                        @else
                        <p style="margin: 4px 0;">Masuk: <strong>-</strong></p>
                        <p style="margin: 0; color: #a0aec0; font-size: 13px;">Belum presensi masuk</p>
                        @endif
                    </div>
                    @endforeach
                    @if($presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count())
                    <div class="alert success" style="margin-top: 12px; padding: 8px;">
                        <small><i class="bx bx-check" style="margin-right: 4px;"></i> Semua presensi hari ini lengkap!</small>
                    </div>
                    @else
                    <p style="margin: 0; color: #718096; font-size: 13px;">Lakukan presensi keluar jika sudah selesai.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Presensi Form -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
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
        <h6 class="card-title">{{ $showKeluar ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
        </div>

        <!-- Location Status -->
        <div class="form-group">
            <div id="location-info" class="status-message info">
                <i class="bx bx-loader-alt bx-spin"></i>
                <div>
                    <strong>Mengumpulkan data lokasi...</strong>
                    <br><small>Reading 1/1 - Pastikan GPS aktif</small>
                </div>
            </div>
        </div>

        <!-- Coordinates -->
        <div class="form-group">
            <label class="form-label">
                <i class="bx bx-target-lock"></i>
                Koordinat Lokasi
            </label>
            <div class="coordinate-grid">
                <input type="text" id="latitude" class="form-input" placeholder="Latitude" readonly>
                <input type="text" id="longitude" class="form-input" placeholder="Longitude" readonly>
            </div>
        </div>

        <!-- Address -->
        <div class="form-group">
            <label class="form-label">
                <i class="bx bx-home"></i>
                Alamat Lokasi
            </label>
            <input type="text" id="lokasi" class="form-input" placeholder="Alamat akan muncul otomatis" readonly>
        </div>

        <!-- Selfie Section -->
        <div class="form-group">
            <label class="form-label">
                <i class="bx bx-camera"></i>
                Foto Selfie
            </label>
            <div class="alert info" style="margin-bottom: 12px;">
                <small><i class="bx bx-info-circle" style="margin-right: 4px;"></i><strong>Wajib:</strong> Pastikan selfie diambil di lingkungan madrasah/sekolah.</small>
            </div>
            <div class="selfie-container">
                <div class="selfie-placeholder">
                    <i class="bx bx-camera"></i>
                    <span>Kamera akan muncul di sini</span>
                </div>
                <video id="selfie-video" autoplay playsinline></video>
                <canvas id="selfie-canvas"></canvas>
                <img id="selfie-preview" alt="Selfie Preview">
                <button type="button" id="btn-capture-selfie" class="selfie-btn">
                    <i class="bx bx-camera"></i>Ambil Foto
                </button>
                <button type="button" id="btn-retake-selfie" class="btn-secondary">
                    <i class="bx bx-refresh"></i>Ulang
                </button>
            </div>
            <input type="hidden" id="selfie-data" name="selfie_data">
            <div id="selfie-status" class="status-message info" style="margin-top: 12px;">
                <i class="bx bx-camera-off"></i>
                <div>
                    <strong>Selfie belum diambil</strong>
                    <br><small>Klik tombol presensi untuk mengaktifkan kamera</small>
                </div>
            </div>
        </div>

        <!-- Presensi Button -->
        @php
            $isDisabled = false;
            $buttonText = 'Ambil Selfie';
            $buttonIcon = 'check-circle';

            if ($isPenjagaSekolah) {
                // For penjaga sekolah, always allow presensi
                $isDisabled = false;
                $buttonText = isset($openPresensi) ? 'Presensi Keluar' : 'Presensi Masuk';
            } elseif ($isHoliday) {
                $isDisabled = true;
                $buttonText = 'Hari Libur - Presensi Ditutup';
                $buttonIcon = 'calendar-x';
            } elseif ($presensiHariIni && $presensiHariIni->count() > 0) {
                $allComplete = $presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count();
                $isDisabled = $allComplete;
                $buttonText = $allComplete ? 'Presensi Lengkap' : 'Presensi Keluar';
            }
        @endphp

        <button type="button" id="btn-presensi"
                class="btn-primary"
                disabled
                {{ $isDisabled ? 'disabled' : '' }}>
            <i class="bx bx-{{ $buttonIcon }}"></i>
            {{ $buttonText }}
        </button>

        <!-- Submit Button (hidden initially) -->
        <button type="button" id="btn-submit-presensi"
                class="btn-success">
            <i class="bx bx-send"></i>
            Kirim Presensi
        </button>
    </div>

    <!-- Time Information -->
    @if($isPenjagaSekolah)
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="bx bx-calendar-check"></i>
            </div>
            <h6 class="card-title">Jadwal Presensi Penjaga Sekolah</h6>
        </div>
        <div class="alert info">
            <small>
                <i class="bx bx-info-circle" style="margin-right: 4px;"></i>
                <strong>Penjaga Sekolah:</strong> Dapat melakukan presensi masuk dan keluar kapan saja dalam 24 jam. Presensi keluar dapat dilakukan pada tanggal berbeda dengan presensi masuk.
            </small>
        </div>
    </div>
    @elseif(isset($timeRanges) && $timeRanges)
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="bx bx-calendar-check"></i>
            </div>
            <h6 class="card-title">Jadwal Presensi</h6>
        </div>
        <div class="schedule-grid">
            <div class="schedule-item masuk">
                <i class="bx bx-log-in-circle"></i>
                <h6>Masuk</h6>
                <p>{{ $timeRanges['masuk_start'] }} - 07:00</p>
                <small>Terlambat setelah 07:00</small>
            </div>
            <div class="schedule-item pulang">
                <i class="bx bx-log-out-circle"></i>
                <h6>Pulang</h6>
                <p>{{ $timeRanges['pulang_start'] }} - {{ $timeRanges['pulang_end'] }}</p>
                @if($user->madrasah && $user->madrasah->hari_kbm == '6' && \Carbon\Carbon::parse($selectedDate)->dayOfWeek == 5)
                <small>Jumat khusus: mulai 14:30</small>
                @else
                <small>Mulai pukul {{ $timeRanges['pulang_start'] }}</small>
                @endif
            </div>
        </div>
        <div class="alert info" style="margin-top: 12px;">
            <small>
                <i class="bx bx-info-circle" style="margin-right: 4px;"></i>
                <strong>Catatan:</strong>
                @if($user->madrasah && $user->madrasah->hari_kbm == '6' && \Carbon\Carbon::parse($selectedDate)->dayOfWeek == 5)
                Pulang dapat dilakukan mulai pukul 14:30 hingga 22:00 (khusus Jumat).
                @else
                Pulang dapat dilakukan mulai pukul 15:00 hingga 22:00.
                @endif
            </small>
        </div>
    </div>
    @else
    <div class="alert warning">
        <i class="bx bx-info-circle" style="margin-right: 4px;"></i>
        <strong>Pengaturan Presensi:</strong> Hari KBM belum diatur. Hubungi admin.
    </div>
    @endif



    <!-- Important Notice -->
    <div class="alert-custom info">
        <div class="d-flex">
            <i class="bx bx-info-circle text-info me-1"></i>
            <div>
                <strong class="text-info">Informasi Sistem</strong>
                <p class="mb-0 text-muted">Pastikan Anda berada di lingkungan madrasah saat melakukan presensi. Sistem menggunakan validasi lokasi koordinat madrasah.</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Presensi Button -->
    <a href="{{ route('mobile.riwayat-presensi') }}" class="nav-link">
        <i class="bx bx-history"></i>
        Riwayat Presensi
    </a>

    <!-- Izin: single button to mobile izin menu -->
    <a href="{{ route('mobile.izin') }}" class="nav-link">
        <i class="bx bx-calendar-minus"></i>
        Izin
    </a>

    <!-- Monitor Map: dedicated button for kepala madrasah -->
    @if(Auth::user()->ketugasan === 'kepala madrasah/sekolah')
    <a href="{{ route('mobile.monitor-map') }}" class="nav-link">
        <i class="bx bx-map"></i>
        Monitor Map Presensi
    </a>
    @endif

    <!-- Monitoring Presensi: Map View -->
    @if(Auth::user()->ketugasan === 'kepala madrasah/sekolah')
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="bx bx-map"></i>
            </div>
            <h6 class="card-title">Monitoring Lokasi Presensi</h6>
        </div>

        <!-- Map Container -->
        <div id="presensi-map" class="monitoring-map"></div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-dot present"></div>
                <span>Sudah Presensi</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot absent"></div>
                <span>Belum Presensi</span>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $presensis->count() }}</div>
                <div class="stat-label">Sudah Presensi</div>
            </div>
            <div class="stat-card absent">
                <div class="stat-number">{{ $belumPresensi->count() }}</div>
                <div class="stat-label">Belum Presensi</div>
            </div>
        </div>
    </div>
    @endif
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
    let latitude, longitude, lokasi;
    let locationReadings = [];
    let readingCount = 0;
    const totalReadings = 1; // Single location reading only
    const readingInterval = 5000; // 5 seconds



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
                    const progressText = isComplete ? 'Data lengkap!' : 'Mengumpulkan...';
                    const iconClass = isComplete ? 'bx bx-check-circle text-success me-2' : 'bx bx-loader-alt bx-spin me-2';
                    const infoClass = isComplete ? 'location-info success' : 'location-info info';

                    $('#location-info').html(`
                        <div class="${infoClass}">
                            <div class="d-flex align-items-center">
                                <i class="${iconClass}"></i>
                                <div>
                                    <strong class="small">Reading ${readingCount}/${totalReadings} - ${progressText}</strong>
                                    <br><small class="text-muted">Akurasi: ${Math.round(position.coords.accuracy)}m</small>
                                </div>
                            </div>
                        </div>
                    `);

                    // Update coordinates display with latest reading
                    $('#latitude').val(reading.latitude.toFixed(6));
                    $('#longitude').val(reading.longitude.toFixed(6));

                    // Update user location map
                    updateUserLocationMap(reading.latitude, reading.longitude);

                    // Get address from latest reading
                    getAddressFromCoordinates(reading.latitude, reading.longitude);

                    // Enable selfie camera after first successful location reading
                    if (readingNumber === 1 && !selfieCaptured) {
                        setTimeout(() => {
                            initializeSelfieCamera();
                        }, 1000); // Small delay to ensure UI is updated
                    }

                    resolve(reading);
                },
                function(error) {
                    clearTimeout(timeoutId); // Clear timeout on error
                    console.warn(`Reading ${readingNumber} failed:`, error);

                    // Provide user-friendly error message
                    const errorMessage = error.code === 1 ? 'Izin lokasi ditolak' :
                                       error.code === 2 ? 'Sinyal GPS lemah' :
                                       error.code === 3 ? 'Waktu habis' : 'Error tidak diketahui';

                    $('#location-info').html(`
                        <div class="location-info warning">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error-circle me-2"></i>
                                <div>
                                <strong class="small">Reading ${readingNumber} gagal</strong>
                                <br><small class="text-muted">${errorMessage} - Melanjutkan...</small>
                                </div>
                            </div>
                        </div>
                    `);

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
                        var hasPresensi = {{ $presensiHariIni && $presensiHariIni->count() > 0 ? 'true' : 'false' }};
                        var allPresensiComplete = {{ ($presensiHariIni && $presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count()) ? 'true' : 'false' }};
                        var buttonText = hasPresensi && !allPresensiComplete ? "Presensi Keluar" : "Ambil Selfie";
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-camera me-1"></i>' + buttonText);
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

                const successMessage = successfulReadings === totalReadings ?
                    'Semua reading berhasil!' : `${successfulReadings}/${totalReadings} reading berhasil`;

                $('#location-info').html(`
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle text-success me-2"></i>
                            <div>
                                <strong class="small">Data lokasi lengkap!</strong>
                                <br><small class="text-muted">${successMessage}</small>
                            </div>
                        </div>
                    </div>
                `);
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
                    $('#location-info').html(`
                        <div class="location-info success">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                <div>
                                <strong class="small">Reading ${readingNumber} berhasil (alt)</strong>
                                <br><small class="text-muted">Akurasi: ${Math.round(position.coords.accuracy)}m</small>
                                </div>
                            </div>
                        </div>
                    `);

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
        $('#location-info').html(`
            <div class="location-info error">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-2"></i>
                    <div>
                        <strong class="small">GPS Tidak Tersedia</strong>
                        <br><small class="text-muted">Coba langkah berikut:</small>
                    </div>
                </div>
                <div style="margin-top: 8px; font-size: 11px;">
                    <div style="margin-bottom: 4px;"><i class="bx bx-check-circle text-success me-1"></i> Pastikan GPS aktif</div>
                    <div style="margin-bottom: 4px;"><i class="bx bx-check-circle text-success me-1"></i> Berikan izin lokasi ke browser</div>
                    <div style="margin-bottom: 4px;"><i class="bx bx-check-circle text-success me-1"></i> Coba di luar ruangan</div>
                    <div style="margin-bottom: 4px;"><i class="bx bx-refresh text-primary me-1"></i> Refresh halaman</div>
                </div>
            </div>
        `);

        $('#btn-presensi').prop('disabled', true).html('<i class="bx bx-error me-1"></i>GPS Error');

        // Auto-retry after 10 seconds
        setTimeout(() => {
            if (locationReadings.length === 0) {
                console.log('Auto-retrying GPS collection...');
                $('#location-info').html(`
                    <div class="location-info info">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-loader-alt bx-spin me-2"></i>
                            <div>
                                <strong class="small">Mencoba lagi...</strong>
                                <br><small class="text-muted">Reading 1/1 - Auto retry</small>
                            </div>
                        </div>
                    </div>
                `);
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
            $('#location-info').html(`
                <div class="location-info error">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle me-2"></i>
                        <div>
                            <strong class="small">Browser tidak mendukung GPS</strong>
                            <br><small class="text-muted">Silakan gunakan browser modern dengan dukungan GPS</small>
                        </div>
                    </div>
                </div>
            `);
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
            const placeholder = container.querySelector('.text-center');
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
                            <br><small class="text-muted">Klik tombol "Ambil Foto" untuk mengambil selfie</small>
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

        // Stop camera stream
        if (selfieStream) {
            selfieStream.getTracks().forEach(track => track.stop());
            selfieStream = null;
        }

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
                // Show submit button and hide presensi button
                $('#btn-presensi').hide();
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
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-camera me-1"></i>Ambil Selfie');
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
        // Hide submit button and show presensi button again
        $('#btn-submit-presensi').hide();
        $('#btn-presensi').show();
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
        // First, request camera access and show camera interface
        if (!selfieCaptured) {
            try {
                await initializeSelfieCamera();
                // Camera initialized, user can now click the capture button manually
                return;
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Dapat Diakses',
                    text: 'Tidak dapat mengakses kamera. Pastikan memberikan izin kamera dan coba lagi.',
                    confirmButtonText: 'Oke'
                });
                return;
            }
        }
    });

    // Handle submit presensi button
    $('#btn-submit-presensi').click(async function() {
        // If selfie is already captured, proceed with location validation
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

                $('#location-info').html(`
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle text-success me-2"></i>
                            <div>
                                <strong class="small">Lokasi berhasil didapatkan!</strong>
                            </div>
                        </div>
                    </div>
                `);

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
                                // Instead of full reload, update the UI dynamically
                                updatePresensiUI(resp);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: resp.message || 'Gagal melakukan presensi. Coba lagi.',
                            });
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
                $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 });
    });
});

// Function to update presensi UI after successful submission
function updatePresensiUI(resp) {
    // Update the status card to show presensi has been recorded
    const statusCardHtml = `
        <div class="status-card">
            <div class="d-flex align-items-center">
                <div class="card-icon">
                    <i class="bx bx-check-circle"></i>
                </div>
                <div style="flex: 1;">
                    <h6 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600;">Presensi Sudah Dicatat</h6>
                    <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid rgba(72, 187, 120, 0.2);">
                        <small style="color: #68d391;">${resp.madrasah_name || 'Madrasah'}</small>
                        <p style="margin: 4px 0;">Masuk: <strong>${resp.waktu_masuk || new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}</strong></p>
                        <p style="margin: 0; color: #a0aec0; font-size: 13px;">Belum presensi keluar</p>
                    </div>
                    <p style="margin: 0; color: #a0aec0; font-size: 13px;">Lakukan presensi keluar jika sudah selesai.</p>
                </div>
            </div>
        </div>
    `;

    // Find and replace the status card
    const statusCardContainer = document.querySelector('.alert-custom.warning, .alert-custom.success, .status-card');
    if (statusCardContainer) {
        statusCardContainer.outerHTML = statusCardHtml;
    }

    // Update the presensi button to show "Presensi Keluar"
    const presensiBtn = document.getElementById('btn-presensi');
    if (presensiBtn) {
        presensiBtn.innerHTML = '<i class="bx bx-log-out-circle"></i>Presensi Keluar';
        presensiBtn.disabled = false;
    }

    // Hide submit button
    const submitBtn = document.getElementById('btn-submit-presensi');
    if (submitBtn) {
        submitBtn.style.display = 'none';
    }

    // Reset selfie section for next use
    resetSelfieSection();

    // Update the header subtitle if needed
    const subtitle = document.querySelector('.presensi-header h5');
    if (subtitle && resp.madrasah_name) {
        subtitle.textContent = resp.madrasah_name;
    }
}

// Function to reset selfie section
function resetSelfieSection() {
    // Hide video and preview
    const video = document.getElementById('selfie-video');
    const preview = document.getElementById('selfie-preview');
    const canvas = document.getElementById('selfie-canvas');

    if (video) video.style.display = 'none';
    if (preview) preview.style.display = 'none';
    if (canvas) canvas.style.display = 'none';

    // Hide buttons
    const captureBtn = document.getElementById('btn-capture-selfie');
    const retakeBtn = document.getElementById('btn-retake-selfie');

    if (captureBtn) captureBtn.style.display = 'none';
    if (retakeBtn) retakeBtn.style.display = 'none';

    // Show placeholder
    const container = document.getElementById('selfie-container');
    if (container) {
        const placeholder = container.querySelector('.text-center');
        if (placeholder) {
            placeholder.style.display = 'block';
        }
    }

    // Reset status
    const statusElement = document.getElementById('selfie-status');
    if (statusElement) {
        statusElement.innerHTML = `
            <div class="status-message info">
                <i class="bx bx-camera-off"></i>
                <div>
                    <strong>Selfie belum diambil</strong>
                    <br><small>Klik tombol presensi untuk mengaktifkan kamera</small>
                </div>
            </div>
        `;
    }

    // Clear selfie data
    const selfieDataInput = document.getElementById('selfie-data');
    if (selfieDataInput) {
        selfieDataInput.value = '';
    }

    // Reset flag
    selfieCaptured = false;

    // Stop any active camera stream
    if (selfieStream) {
        selfieStream.getTracks().forEach(track => track.stop());
        selfieStream = null;
    }
}

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
@endsection
