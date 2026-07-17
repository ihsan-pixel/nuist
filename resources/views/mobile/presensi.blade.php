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

        .selfie-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.82);
            z-index: 2100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }

        .selfie-modal.show {
            display: flex;
        }

        .selfie-modal.face-scan-modal {
            padding: 0;
            align-items: stretch;
            justify-content: stretch;
            background: #000;
        }

        .selfie-modal-dialog {
            position: relative;
            width: min(100%, 420px);
            max-height: min(92vh, 760px);
            min-height: 0;
            background: #000;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.42);
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(148, 163, 184, 0.16);
        }

        .face-scan-mode.selfie-modal-dialog {
            width: 100vw;
            max-width: 100vw;
            height: 100vh;
            max-height: 100vh;
            min-height: 100vh;
            border-radius: 0;
            border: 0;
            box-shadow: none;
        }

        .selfie-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            padding: 18px 18px 12px;
            border-bottom: none;
        }

        .selfie-modal-title {
            font-size: 17px;
            line-height: 1.25;
            font-weight: 700;
            color: #f8fafc;
            margin: 0;
        }

        .selfie-modal-subtitle {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            line-height: 1.45;
            color: rgba(226, 232, 240, 0.74);
        }

        .selfie-modal-actions {
            display: inline-flex;
            align-items: center;
            gap: 0;
            margin-left: auto;
        }

        .selfie-modal-close {
            width: auto;
            height: auto;
            border: 0;
            background: transparent;
            border-radius: 0;
            color: #f87171;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            padding: 0;
            line-height: 1;
            font-weight: 500;
        }

        .selfie-modal-retry {
            width: auto;
            height: auto;
            border: 0;
            background: transparent;
            border-radius: 0;
            color: #34d399;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            padding: 0;
            line-height: 1;
            font-weight: 600;
            gap: 4px;
        }

        .selfie-modal-retry[hidden] {
            display: none !important;
        }

        .face-scan-mode .selfie-modal-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            display: block;
            z-index: 5;
            padding: 18px 16px 0;
            min-height: 56px;
        }

        .face-scan-mode .selfie-modal-close {
            position: absolute;
            top: 18px;
            left: 16px;
            z-index: 6;
            color: #fff;
        }

        .face-scan-mode .selfie-modal-actions {
            position: absolute;
            top: 18px;
            right: 16px;
            z-index: 6;
        }

        .face-scan-mode .selfie-modal-title {
            font-size: 0;
        }

        .face-scan-mode .selfie-modal-subtitle {
            display: none;
        }

        .selfie-fullscreen-mode .selfie-modal-subtitle {
            display: none;
        }

        .selfie-modal-body {
            padding: 0 18px 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            flex: 1;
            gap: 14px;
        }

        .face-scan-mode .selfie-modal-body {
            padding: 0;
            overflow: hidden;
            align-items: stretch;
            gap: 0;
        }

        .face-scan-onboarding {
            position: absolute;
            inset: 0;
            z-index: 12;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 18px;
            background: rgba(0, 0, 0, 0.72);
            backdrop-filter: blur(10px);
        }

        .face-scan-onboarding[hidden] {
            display: none !important;
        }

        .face-scan-onboarding-panel {
            width: min(100%, 420px);
            border-radius: 24px;
            background: #f8fafc;
            color: #0f172a;
            padding: 20px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.32);
        }

        .face-scan-onboarding-title {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0;
        }

        .face-scan-onboarding-subtitle {
            margin: 0 0 16px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .face-scan-onboarding-steps {
            display: grid;
            gap: 10px;
            margin-bottom: 16px;
        }

        .face-scan-onboarding-step {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            line-height: 1.45;
            color: #334155;
        }

        .face-scan-onboarding-step i {
            margin-top: 1px;
            color: #059669;
            font-size: 18px;
            flex: 0 0 auto;
        }

        .face-scan-example-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 18px;
        }

        .face-scan-example {
            min-height: 132px;
            border-radius: 18px;
            padding: 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .face-scan-example.is-good {
            border-color: rgba(16, 185, 129, 0.34);
            background: #ecfdf5;
        }

        .face-scan-example.is-bad {
            border-color: rgba(248, 113, 113, 0.32);
            background: #fef2f2;
        }

        .face-scan-example-oval {
            width: 58px;
            height: 78px;
            border-radius: 48% 48% 44% 44% / 38% 38% 54% 54%;
            border: 2px solid #10b981;
            position: relative;
        }

        .face-scan-example.is-bad .face-scan-example-oval {
            border-color: #ef4444;
        }

        .face-scan-example-face {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 34px;
            height: 44px;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            background: #cbd5e1;
        }

        .face-scan-example.is-bad .face-scan-example-face {
            transform: translate(-22%, -64%) rotate(-12deg);
            opacity: 0.82;
        }

        .face-scan-example-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
        }

        .face-scan-example.is-good .face-scan-example-label {
            color: #047857;
        }

        .face-scan-example.is-bad .face-scan-example-label {
            color: #b91c1c;
        }

        .face-scan-onboarding-button {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 14px 18px;
            color: #fff;
            background: linear-gradient(135deg, #10b981 0%, #16a34a 100%);
            font-weight: 700;
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.24);
        }

        .face-scan-help-button {
            position: absolute;
            right: 18px;
            bottom: 24px;
            z-index: 8;
            width: 44px;
            height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.58);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.24);
            backdrop-filter: blur(12px);
        }

        .face-scan-help-button:active {
            transform: scale(0.96);
        }

        .selfie-note {
            display: none;
        }

        .selfie-status-banner {
            min-height: 18px;
            color: rgba(226, 232, 240, 0.68);
            text-align: center;
            font-size: 12px;
            margin-top: 0;
        }

        .selfie-status-banner span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 16px;
            width: 100%;
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.5);
            color: rgba(255, 255, 255, 0.94);
            line-height: 1.4;
            backdrop-filter: blur(14px);
        }

        .selfie-stage {
            position: relative;
            width: 100%;
            overflow: hidden;
            background: #020617;
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 24px;
            min-height: 0;
            aspect-ratio: 3 / 4;
            max-height: min(56vh, 520px);
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .face-scan-mode .selfie-status-banner {
            display: none;
        }

        .selfie-fullscreen-mode .selfie-status-banner {
            display: none;
        }

        .face-scan-mode .selfie-stage {
            min-height: 100vh;
            max-height: none;
            aspect-ratio: auto;
            border-radius: 0;
            border: 0;
            padding: 0;
        }

        .selfie-camera-layer {
            position: relative;
            width: 100%;
            min-height: 0;
            height: 100%;
            z-index: 0;
        }

        .face-scan-mode .selfie-camera-layer {
            min-height: 100vh;
        }

        .face-scan-mode .selfie-placeholder {
            background: #000;
        }

        .face-scan-mode .selfie-placeholder i,
        .face-scan-mode .selfie-placeholder strong,
        .face-scan-mode .selfie-placeholder span {
            display: none;
        }

        .selfie-fullscreen-mode .selfie-placeholder strong,
        .selfie-fullscreen-mode .selfie-placeholder span {
            display: none;
        }

        .selfie-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: rgba(255, 255, 255, 0.88);
            background: radial-gradient(circle at top, rgba(71, 85, 105, 0.46) 0%, rgba(30, 41, 59, 0.82) 100%);
            text-align: center;
            padding: 28px;
        }

        .selfie-placeholder i {
            font-size: 42px;
            margin-bottom: 10px;
            color: rgba(134, 239, 172, 0.92);
        }

        #selfie-video,
        #selfie-canvas,
        #selfie-preview {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .selfie-guide {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }

        .selfie-guide-oval {
            position: absolute;
            left: 50%;
            top: 22%;
            width: min(74vw, 332px);
            height: min(102vw, 448px);
            transform: translateX(-50%);
            border: 3px solid rgba(255, 255, 255, 0.92);
            border-radius: 48% 48% 44% 44% / 38% 38% 54% 54%;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, 0.42);
            transition: border-color 280ms cubic-bezier(0.22, 1, 0.36, 1), box-shadow 280ms cubic-bezier(0.22, 1, 0.36, 1), transform 280ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .selfie-guide-pill {
            position: absolute;
            top: calc(22% - 108px);
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: min(80vw, 296px);
            padding: 0;
            border-radius: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            color: rgba(255, 255, 255, 0.96);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.01em;
            backdrop-filter: none;
            text-align: center;
            transition: color 220ms cubic-bezier(0.22, 1, 0.36, 1), transform 220ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .selfie-guide-pill i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: auto;
            height: auto;
            border-radius: 0;
            font-size: 34px;
            flex: 0 0 auto;
            color: rgba(255, 255, 255, 0.94);
            background: transparent;
            box-shadow: none;
        }

        .selfie-guide-pill span {
            display: none;
            width: 100%;
            text-align: center;
            font-size: 18px;
            line-height: 1.25;
            font-weight: 700;
            letter-spacing: 0.01em;
            max-width: 240px;
            text-shadow: 0 10px 24px rgba(0, 0, 0, 0.48);
        }

        .selfie-guide-pill small {
            display: block;
            width: 100%;
            text-align: center;
            font-size: 16px;
            line-height: 1.45;
            font-weight: 700;
            max-width: 300px;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 10px 24px rgba(0, 0, 0, 0.44);
        }

        #btn-capture-selfie.retry-scan {
            display: inline-flex !important;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 16px 36px rgba(220, 38, 38, 0.3);
        }

        .selfie-progress-orb {
            --progress-angle: 0deg;
            --progress-fill: #4ade80;
            --progress-track: rgba(255, 255, 255, 0.16);
            position: absolute;
            left: 50%;
            top: 79%;
            transform: translateX(-50%);
            z-index: 3;
            width: 74px;
            height: 74px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at center, rgba(255, 255, 255, 0.04) 52%, transparent 53%),
                conic-gradient(var(--progress-fill) 0deg, var(--progress-fill) var(--progress-angle), var(--progress-track) var(--progress-angle), var(--progress-track) 360deg);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.32);
            transition: transform 220ms ease, box-shadow 220ms ease;
        }

        .selfie-progress-orb::before {
            content: '';
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .selfie-progress-orb-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: transparent;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.01em;
        }

        .selfie-guide-detail {
            position: absolute;
            left: 50%;
            top: calc(79% + 92px);
            transform: translateX(-50%);
            z-index: 4;
            width: min(86vw, 308px);
            text-align: center;
            font-size: 11px;
            line-height: 1.45;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.82);
            text-shadow: 0 10px 24px rgba(0, 0, 0, 0.5);
        }

        .selfie-progress-value,
        .selfie-progress-success,
        .selfie-progress-error {
            transition: opacity 180ms ease, transform 220ms ease;
        }

        .selfie-progress-success,
        .selfie-progress-error {
            position: absolute;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.58);
            font-size: 34px;
            color: #fff;
        }

        .selfie-progress-success {
            text-shadow: 0 0 16px rgba(134, 239, 172, 0.38), 0 0 30px rgba(74, 222, 128, 0.28);
        }

        .selfie-progress-error {
            text-shadow: 0 0 16px rgba(248, 113, 113, 0.34), 0 0 30px rgba(239, 68, 68, 0.24);
        }

        .selfie-progress-orb.is-complete .selfie-progress-value,
        .selfie-progress-orb.is-error .selfie-progress-value {
            opacity: 0;
            transform: scale(0.72);
        }

        .selfie-progress-orb.is-complete .selfie-progress-success {
            opacity: 1;
            transform: scale(1);
            animation: selfie-progress-success-pop 820ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .selfie-progress-orb.is-error .selfie-progress-error {
            opacity: 1;
            transform: scale(1);
            animation: selfie-progress-error-pop 720ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .selfie-progress-orb.is-complete {
            animation: selfie-progress-orb-pulse 900ms cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 0 0 10px rgba(74, 222, 128, 0.14), 0 18px 40px rgba(74, 222, 128, 0.34);
        }

        .selfie-progress-orb.is-complete::before {
            border-color: rgba(134, 239, 172, 0.42);
            animation: selfie-progress-orb-ring 900ms ease-out;
        }

        .selfie-progress-orb.is-error {
            animation: selfie-progress-orb-error-pulse 760ms cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 0 0 10px rgba(248, 113, 113, 0.14), 0 18px 40px rgba(239, 68, 68, 0.28);
        }

        .selfie-progress-orb.is-error::before {
            border-color: rgba(248, 113, 113, 0.42);
            animation: selfie-progress-orb-ring 760ms ease-out;
        }

        .selfie-stage[data-guide-state="searching"] .selfie-guide-oval,
        .selfie-stage[data-guide-state="too-far"] .selfie-guide-oval,
        .selfie-stage[data-guide-state="too-close"] .selfie-guide-oval,
        .selfie-stage[data-guide-state="tilted"] .selfie-guide-oval,
        .selfie-stage[data-guide-state="off-center"] .selfie-guide-oval,
        .selfie-stage[data-guide-state="warning"] .selfie-guide-oval {
            border-color: #f87171;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, 0.4), 0 0 34px rgba(248, 113, 113, 0.24);
        }

        .selfie-stage[data-guide-state="steady"] .selfie-guide-oval,
        .selfie-stage[data-guide-state="aligned"] .selfie-guide-oval,
        .selfie-stage[data-guide-state="success"] .selfie-guide-oval {
            border-color: #4ade80;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, 0.34), 0 0 34px rgba(74, 222, 128, 0.26);
        }

        .selfie-stage[data-guide-state="searching"] .selfie-guide-pill,
        .selfie-stage[data-guide-state="too-far"] .selfie-guide-pill,
        .selfie-stage[data-guide-state="too-close"] .selfie-guide-pill,
        .selfie-stage[data-guide-state="tilted"] .selfie-guide-pill,
        .selfie-stage[data-guide-state="off-center"] .selfie-guide-pill,
        .selfie-stage[data-guide-state="warning"] .selfie-guide-pill {
            color: rgba(254, 202, 202, 0.98);
        }

        .selfie-stage[data-guide-state="steady"] .selfie-guide-pill,
        .selfie-stage[data-guide-state="aligned"] .selfie-guide-pill,
        .selfie-stage[data-guide-state="success"] .selfie-guide-pill {
            color: rgba(220, 252, 231, 0.98);
        }

        .selfie-stage[data-guide-state="steady"] .selfie-guide-oval {
            transform: translateX(-50%) scale(0.996);
        }

        .selfie-stage[data-guide-state="success"] .selfie-guide-oval {
            animation: selfie-oval-pulse 700ms ease;
        }

        @keyframes selfie-oval-pulse {
            0% {
                transform: translateX(-50%) scale(0.98);
            }

            55% {
                transform: translateX(-50%) scale(1.02);
            }

            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        @keyframes selfie-progress-orb-pulse {
            0% {
                transform: translateX(-50%) scale(0.92);
            }

            48% {
                transform: translateX(-50%) scale(1.14);
            }

            72% {
                transform: translateX(-50%) scale(0.98);
            }

            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        @keyframes selfie-progress-orb-ring {
            0% {
                transform: scale(0.86);
                opacity: 0;
            }

            35% {
                transform: scale(1.08);
                opacity: 1;
            }

            100% {
                transform: scale(1.18);
                opacity: 0;
            }
        }

        @keyframes selfie-progress-success-pop {
            0% {
                opacity: 0;
                transform: scale(0.3) rotate(-18deg);
            }

            42% {
                opacity: 1;
                transform: scale(1.34) rotate(8deg);
            }

            68% {
                opacity: 1;
                transform: scale(1.02) rotate(-4deg);
            }

            100% {
                opacity: 1;
                transform: scale(1.14) rotate(0deg);
            }
        }

        @keyframes selfie-progress-orb-error-pulse {
            0% {
                transform: translateX(-50%) scale(0.92);
            }

            32% {
                transform: translateX(calc(-50% - 4px)) scale(1.04);
            }

            58% {
                transform: translateX(calc(-50% + 4px)) scale(1.02);
            }

            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        @keyframes selfie-progress-error-pop {
            0% {
                opacity: 0;
                transform: scale(0.34) rotate(-24deg);
            }

            46% {
                opacity: 1;
                transform: scale(1.28) rotate(10deg);
            }

            72% {
                opacity: 1;
                transform: scale(0.96) rotate(-6deg);
            }

            100% {
                opacity: 1;
                transform: scale(1.08) rotate(0deg);
            }
        }

        .selfie-stage-copy {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            padding: 0 12px 18px;
            display: flex;
            justify-content: center;
        }

        .face-scan-mode .selfie-stage-copy {
            display: none;
        }

        .selfie-fullscreen-mode .selfie-stage-copy {
            display: none;
        }

        .selfie-feedback {
            border: 0;
            background: transparent;
            color: #f8fafc;
            text-align: center;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .selfie-feedback strong {
            display: none;
        }

        .selfie-feedback span {
            display: block;
            font-size: 14px;
            line-height: 1.45;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.94);
        }

        .selfie-progress {
            display: none;
        }

        .selfie-progress-item,
        .selfie-quality-item {
            display: none;
        }

        .selfie-progress-meter {
            width: 100%;
            height: 6px;
            border-radius: 999px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.12);
            margin-bottom: 12px;
        }

        .selfie-progress-fill {
            width: 0%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #22c55e 0%, #4ade80 100%);
            transition: width 220ms ease;
        }

        .selfie-section-label,
        .selfie-quality-grid {
            display: none;
        }

        .selfie-footer-title {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            text-align: center;
            margin-bottom: 12px;
        }

        .selfie-modal-footer {
            padding: 0 18px 18px;
            border-top: none;
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            background: transparent;
        }

        .face-scan-mode .selfie-modal-footer {
            position: absolute;
            left: 16px;
            right: 16px;
            bottom: 22px;
            z-index: 5;
            padding: 0;
            background: transparent;
            backdrop-filter: none;
            gap: 12px;
        }

        .selfie-footer-title,
        .selfie-progress-meter,
        .face-scan-mode .selfie-footer-title,
        .face-scan-mode .selfie-progress-meter {
            display: none;
        }

        .face-scan-mode #btn-submit-presensi {
            display: none !important;
        }

        .swal2-container {
            z-index: 2600 !important;
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

            .selfie-overlay-actions {
                gap: 8px;
            }

            .selfie-stage,
            .selfie-placeholder,
            #selfie-video,
            #selfie-canvas,
            #selfie-preview {
                min-height: 0;
            }

            .selfie-stage {
                min-height: 500px;
            }

            .selfie-feedback span {
                font-size: 16px;
            }

            .selfie-guide-oval {
                width: min(78vw, 286px);
                height: min(104vw, 396px);
                top: 23%;
            }

            .selfie-progress-orb {
                top: 81%;
                width: 68px;
                height: 68px;
            }

            .selfie-guide-pill {
                top: calc(23% - 100px);
                width: min(84vw, 286px);
            }

            .selfie-guide-pill i {
                width: auto;
                height: auto;
                font-size: 30px;
            }

            .selfie-guide-pill span {
                display: none;
            }

            .selfie-guide-pill small {
                font-size: 15px;
            }

            .selfie-progress-orb-inner {
                width: 48px;
                height: 48px;
                font-size: 14px;
            }

            .selfie-guide-detail {
                top: calc(81% + 82px);
                width: min(88vw, 292px);
                font-size: 10px;
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
        $hasApprovedPicketToday = !empty($approvedPicketSubmission);

        // For penjaga sekolah, check for any open presensi regardless of date
        if ($isPenjagaSekolah) {
            $openPresensi = \App\Models\Presensi::where('user_id', $user->id)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->orderBy('tanggal', 'desc')
                ->first();
        }
    @endphp

    @if($isHoliday && !$isPenjagaSekolah && !$hasApprovedPicketToday)
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

    @elseif($hasApprovedPicketToday && !$isPenjagaSekolah && (!$presensiHariIni || $presensiHariIni->count() === 0))
    <div class="status-card success">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-calendar-check"></i>
            </div>
            <div class="w-100">
                <h6 class="mb-1">Jadwal Piket Disetujui</h6>
                <div class="status-detail-list">
                    <div class="status-detail-item">
                        <small>{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</small>
                        <p>Hari ini termasuk jadwal piket Anda dan presensi tetap dibuka.</p>
                    </div>
                    <div class="status-detail-item">
                        <small>Periode</small>
                        <p><strong>{{ $approvedPicketSubmission->period->name ?? 'Jadwal Piket' }}</strong></p>
                    </div>
                </div>
                <p class="status-inline-note mb-0">Silakan lakukan presensi seperti biasa pada hari piket yang telah disetujui.</p>
            </div>
        </div>
    </div>

    @elseif($approvedBlockingIzin && (!$presensiHariIni || $presensiHariIni->count() === 0))
    <div class="status-card success">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-file"></i>
            </div>
            <div class="w-100">
                <h6 class="mb-1">Izin {{ ucfirst(str_replace('_', ' ', $approvedBlockingIzin->type)) }} Disetujui</h6>
                <div class="status-detail-list">
                    <div class="status-detail-item">
                        <small>{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</small>
                        <p>Hari ini otomatis tercatat sebagai <strong>izin</strong>.</p>
                    </div>
                    @if($approvedBlockingIzin->tanggal_selesai)
                    <div class="status-detail-item">
                        <small>Periode izin</small>
                        <p><strong>{{ $approvedBlockingIzin->tanggal->format('d/m/Y') }} - {{ $approvedBlockingIzin->tanggal_selesai->format('d/m/Y') }}</strong></p>
                    </div>
                    @endif
                    @if($approvedBlockingIzin->alasan)
                    <div class="status-detail-item">
                        <small>Keterangan</small>
                        <p><strong>{{ $approvedBlockingIzin->alasan }}</strong></p>
                    </div>
                    @endif
                </div>
                <p class="status-inline-note mb-0">Presensi manual dinonaktifkan selama izin ini berlaku.</p>
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

        <!-- Presensi Button -->
        @php
            $isDisabled = false;
            $buttonText = 'Presensi Sekarang';
            $buttonIcon = 'check-circle';
            $verificationMode = $faceVerificationState['mode'] ?? 'selfie';
            $verificationLabel = $faceVerificationState['label'] ?? 'Selfie';
            $faceEnrollmentRequired = $faceVerificationState['requires_face_scan'] ?? false;
            $faceEnrollmentReady = $faceVerificationState['enrolled'] ?? false;

            if ($faceEnrollmentRequired && !$faceEnrollmentReady) {
                $isDisabled = true;
                $buttonText = 'Daftarkan Wajah Terlebih Dahulu';
                $buttonIcon = 'scan';
            } elseif ($isPenjagaSekolah) {
                // For penjaga sekolah, always allow presensi
                $isDisabled = false;
                $buttonText = 'Presensi Sekarang';
            } elseif ($isHoliday && !$hasApprovedPicketToday) {
                $isDisabled = true;
                $buttonText = 'Hari Libur - Presensi Ditutup';
                $buttonIcon = 'calendar-x';
            } elseif($approvedBlockingIzin && (!$presensiHariIni || $presensiHariIni->count() === 0)) {
                $isDisabled = true;
                $buttonText = 'Izin Disetujui';
                $buttonIcon = 'file';
            } elseif ($presensiHariIni && $presensiHariIni->count() > 0) {
                $allComplete = $presensiHariIni->where('waktu_keluar', '!=', null)->count() == $presensiHariIni->count();
                $isDisabled = $allComplete;
                $buttonText = $allComplete ? 'Presensi Lengkap' : 'Presensi Sekarang';
            }
        @endphp

        @if($faceEnrollmentRequired && !$faceEnrollmentReady)
        <div class="alert-custom warning">
            <div class="d-flex">
                <i class="bx bx-scan text-warning me-2"></i>
                <div>
                    <strong>Scan wajah belum aktif</strong>
                    <p class="mb-2 text-muted">Wajah Anda belum terdaftar. Presensi kehadiran sekarang memakai scan wajah sebagai pengganti selfie.</p>
                    <a href="{{ route('mobile.face.enrollment') }}" class="btn-primary-custom">Daftar Wajah</a>
                </div>
            </div>
        </div>
        @endif

        <div class="alert-custom info">
            <div class="d-flex">
                <i class="bx {{ $verificationMode === 'face_scan' ? 'bx-scan' : 'bx-camera' }} text-info me-2"></i>
                <div>
                    <strong>Metode verifikasi mobile aktif: {{ $verificationLabel }}</strong>
                    <p class="mb-0 text-muted">{{ $faceVerificationState['description'] ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <button type="button" id="btn-presensi"
                    class="presensi-btn"
                    disabled
                    {{ $isDisabled ? 'disabled' : '' }}>
                <i class="bx bx-{{ $buttonIcon }} me-1"></i>
                {{ $buttonText }}
            </button>
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
    {{-- @if(Auth::user()->ketugasan === 'kepala madrasah/sekolah')
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
    @endif --}}
</div>

<div id="selfie-modal" class="selfie-modal {{ $verificationMode === 'face_scan' ? 'face-scan-modal' : 'selfie-fullscreen-modal' }}" aria-hidden="true">
    <div class="selfie-modal-dialog {{ $verificationMode === 'face_scan' ? 'face-scan-mode' : 'selfie-fullscreen-mode' }}" role="dialog" aria-modal="true" aria-labelledby="selfie-modal-title">
        <div class="selfie-modal-header">
            <button type="button" id="btn-close-selfie-modal" class="selfie-modal-close" aria-label="Tutup">
                Batal
            </button>
            <div>
                <div id="selfie-modal-title" class="selfie-modal-title">{{ $verificationMode === 'face_scan' ? 'Scan Wajah' : 'Selfie Presensi' }}</div>
                <div id="selfie-modal-subtitle" class="selfie-modal-subtitle">
                    {{ $verificationMode === 'face_scan' ? 'Posisikan wajah lalu ikuti arahan singkat sampai presensi diproses.' : 'Ambil foto selfie sebelum presensi dikirim.' }}
                </div>
            </div>
            <div class="selfie-modal-actions">
                <button type="button" id="btn-retry-selfie-modal" class="selfie-modal-retry" aria-label="Ulangi scan" {{ $verificationMode === 'face_scan' ? '' : 'hidden' }}>
                    <i class="bx bx-refresh"></i>Ulangi
                </button>
            </div>
        </div>

        <div class="selfie-modal-body">
            @if($verificationMode === 'face_scan')
            <div class="face-scan-onboarding" id="face-scan-onboarding" role="dialog" aria-modal="true" aria-labelledby="face-scan-onboarding-title">
                <div class="face-scan-onboarding-panel">
                    <h2 class="face-scan-onboarding-title" id="face-scan-onboarding-title">Panduan Scan Wajah</h2>
                    <p class="face-scan-onboarding-subtitle">Ikuti panduan singkat ini agar wajah cepat cocok dan presensi bisa diproses.</p>

                    <div class="face-scan-onboarding-steps">
                        <div class="face-scan-onboarding-step">
                            <i class="bx bx-check-circle"></i>
                            <span>Posisikan satu wajah tepat di dalam oval.</span>
                        </div>
                        <div class="face-scan-onboarding-step">
                            <i class="bx bx-low-vision"></i>
                            <span>Ikuti instruksi seperti kedip atau arah wajah yang tampil.</span>
                        </div>
                        <div class="face-scan-onboarding-step">
                            <i class="bx bx-shield-quarter"></i>
                            <span>Pastikan wajah asli sesuai data yang sudah didaftarkan.</span>
                        </div>
                    </div>

                    <div class="face-scan-example-grid" aria-label="Contoh scan wajah presensi">
                        <div class="face-scan-example is-good">
                            <div class="face-scan-example-oval">
                                <span class="face-scan-example-face"></span>
                            </div>
                            <div class="face-scan-example-label"><i class="bx bx-check"></i> Benar</div>
                        </div>
                        <div class="face-scan-example is-bad">
                            <div class="face-scan-example-oval">
                                <span class="face-scan-example-face"></span>
                            </div>
                            <div class="face-scan-example-label"><i class="bx bx-x"></i> Salah</div>
                        </div>
                    </div>

                    <button type="button" class="face-scan-onboarding-button" id="btn-face-scan-onboarding-continue">
                        Lanjutkan
                    </button>
                </div>
            </div>
            @endif

            <div id="selfie-status" class="selfie-status-banner">
                <span>{{ $verificationMode === 'face_scan' ? 'Kamera akan aktif otomatis saat modal dibuka.' : 'Kamera akan aktif otomatis saat modal dibuka.' }}</span>
            </div>

            <div id="selfie-container" class="selfie-stage" data-guide-state="searching">
                @if($verificationMode === 'face_scan')
                <div class="selfie-guide">
                    <div class="selfie-guide-oval"></div>
                    <div class="selfie-guide-pill" id="selfie-guide-pill">
                        <i class="bx bx-scan"></i>
                        <span id="selfie-guide-text">Pusatkan Wajah di Oval</span>
                        <small id="selfie-guide-instruction">Ikuti instruksi scan yang tampil.</small>
                    </div>
                    <div class="selfie-progress-orb" id="selfie-progress-orb" aria-hidden="true">
                        <div class="selfie-progress-orb-inner">
                            <span id="selfie-progress-value" class="selfie-progress-value">0%</span>
                            <span class="selfie-progress-success" aria-hidden="true">
                                <i class="bx bx-check"></i>
                            </span>
                            <span class="selfie-progress-error" aria-hidden="true">
                                <i class="bx bx-x"></i>
                            </span>
                        </div>
                    </div>
                    <div class="selfie-guide-detail" id="selfie-guide-detail">Pusatkan wajah di dalam oval agar scan terbaca.</div>
                </div>
                @endif

                <div class="selfie-camera-layer">
                    <div class="selfie-placeholder">
                        @if($verificationMode !== 'face_scan')
                        <i class="bx bx-camera"></i>
                        <strong class="mb-1">Menyiapkan kamera selfie</strong>
                        <span>Izinkan akses kamera jika diminta.</span>
                        @endif
                    </div>
                    <video id="selfie-video" autoplay playsinline style="display: none; object-fit: cover;"></video>
                    <canvas id="selfie-canvas" style="display: none;"></canvas>
                    <img id="selfie-preview" style="object-fit: cover; display: none;" alt="Preview Scan Wajah">
                </div>

                <div class="selfie-stage-copy">
                    <div id="face-instruction-box" class="selfie-feedback">
                        <strong>{{ $verificationMode === 'face_scan' ? 'Instruksi' : 'Selfie' }}</strong>
                        <span id="face-instruction-text">
                            {{ $verificationMode === 'face_scan'
                                ? 'Aktifkan kamera lalu ikuti arahan scan wajah.'
                                : 'Aktifkan kamera lalu ambil selfie untuk presensi.' }}
                        </span>
                    </div>

                    @if($verificationMode === 'face_scan')
                    <div class="selfie-progress">
                        <div class="selfie-progress-item" data-step="align">Posisikan</div>
                        <div class="selfie-progress-item" data-step="blink">Kedip</div>
                        <div class="selfie-progress-item" data-step="challenge">Challenge</div>
                        <div class="selfie-progress-item" data-step="done">Selesai</div>
                    </div>
                    @endif
                </div>

                @if($verificationMode === 'face_scan')
                <button type="button" id="btn-face-scan-help" class="face-scan-help-button" aria-label="Buka panduan scan wajah">
                    <i class="bx bx-info-circle"></i>
                </button>
                @endif
            </div>

            <input type="hidden" id="selfie-data" name="selfie_data">
            <input type="hidden" id="face-descriptor" name="face_descriptor">
            <input type="hidden" id="liveness-score" name="liveness_score">
            <input type="hidden" id="liveness-challenges" name="liveness_challenges">
        </div>

        <div class="selfie-modal-footer">
            <div class="selfie-footer-title">{{ $verificationMode === 'face_scan' ? 'Scan Wajah' : 'Selfie Presensi' }}</div>
            <div class="selfie-progress-meter">
                <div class="selfie-progress-fill" id="selfie-progress-fill"></div>
            </div>
            <button type="button" id="btn-capture-selfie" class="presensi-btn" style="display: none;">
                <i class="bx {{ $verificationMode === 'face_scan' ? 'bx-scan' : 'bx-camera' }} me-1"></i>{{ $verificationMode === 'face_scan' ? 'Mulai Scan' : 'Ambil Foto' }}
            </button>
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
<script src="{{ asset('models/face-api.js') }}"></script>
<script src="{{ asset('js/face-recognition.js') }}"></script>
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

    let lastFormalAlertSignature = '';
    let lastFormalAlertShownAt = 0;

    function showFormalAlert(options = {}) {
        const signature = [
            options.icon || '',
            options.title || '',
            options.text || ''
        ].join('|');
        const now = Date.now();

        if (signature === lastFormalAlertSignature && now - lastFormalAlertShownAt < 2500) {
            return Promise.resolve({ isDismissed: true, isDuplicate: true });
        }

        lastFormalAlertSignature = signature;
        lastFormalAlertShownAt = now;

        if (window.Swal && typeof Swal.isVisible === 'function' && Swal.isVisible()) {
            Swal.close();
        }

        return Swal.fire({
            confirmButtonText: 'Tutup',
            ...options
        });
    }

    function showFormalErrorAlert(title, text, options = {}) {
        return showFormalAlert({
            icon: 'error',
            title,
            text,
            ...options
        });
    }

    function showFormalSuccessAlert(title, text, options = {}) {
        return showFormalAlert({
            icon: 'success',
            title,
            text,
            ...options
        });
    }

    function showFormalLoadingAlert(title, text) {
        return Promise.resolve({ title, text });
    }

    function showFormalRejectMessage(message, fallback = 'Permintaan presensi tidak dapat diproses saat ini.') {
        return (message && String(message).trim()) || fallback;
    }

    function syncLatestLocationState() {
        const lastReading = locationReadings.length > 0 ? locationReadings[locationReadings.length - 1] : null;

        if ((!latitude || !longitude) && lastReading) {
            latitude = Number(lastReading.latitude);
            longitude = Number(lastReading.longitude);
        }

        if (!lokasi) {
            const lokasiInput = document.getElementById('lokasi');
            if (lokasiInput && lokasiInput.value) {
                lokasi = lokasiInput.value;
            }
        }

        return {
            latitude,
            longitude,
            lokasi,
            lastReading
        };
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
                    latitude = reading.latitude;
                    longitude = reading.longitude;

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

                    locationReadings.push(reading);
                    readingCount++;
                    latitude = reading.latitude;
                    longitude = reading.longitude;

                    $('#latitude').val(reading.latitude.toFixed(6));
                    $('#longitude').val(reading.longitude.toFixed(6));
                    updateUserLocationMap(reading.latitude, reading.longitude);
                    getAddressFromCoordinates(reading.latitude, reading.longitude);

                    if (!presensiActionLocked) {
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-1"></i>Presensi Sekarang');
                    }

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

















    // Face scan variables
    let selfieStream = null;
    let selfieCaptured = false;
    let pendingSelfieData = '';
    let earlyCheckoutConfirmed = false;
    let presensiSubmitInFlight = false;
    let presensiFinalAlertShown = false;
    const selfieModal = document.getElementById('selfie-modal');
    const selfieModalSubtitle = document.getElementById('selfie-modal-subtitle');
    const faceScanOnboarding = document.getElementById('face-scan-onboarding');
    const faceScanOnboardingContinue = document.getElementById('btn-face-scan-onboarding-continue');
    const faceScanHelpButton = document.getElementById('btn-face-scan-help');
    const faceScanner = new window.FaceRecognition();
    let faceVerificationResult = null;
    const verificationMode = @json($faceVerificationState['mode'] ?? 'selfie');
    const verificationLabel = @json($faceVerificationState['label'] ?? 'Selfie');
    const faceVerifyUrl = @json(route('mobile.face.verify'));
    const faceScanRequired = verificationMode === 'face_scan';
    const faceEnrollmentReady = @json($faceVerificationState['enrolled'] ?? false);
    const selfieContainer = document.getElementById('selfie-container');
    const selfieGuideText = document.getElementById('selfie-guide-text');
    const selfieGuideInstruction = document.getElementById('selfie-guide-instruction');
    const selfieGuideDetail = document.getElementById('selfie-guide-detail');
    const selfieProgressItems = Array.from(document.querySelectorAll('.selfie-progress-item'));
    const selfieProgressFill = document.getElementById('selfie-progress-fill');
    const selfieProgressOrb = document.getElementById('selfie-progress-orb');
    const selfieProgressValue = document.getElementById('selfie-progress-value');
    let currentSelfieProgress = 0;
    let targetSelfieProgress = 0;
    let selfieProgressAnimationFrame = null;
    let faceScanAutoStarted = false;
    let faceScanOnboardingAccepted = false;
    let currentFaceInstructionIcon = 'bx-scan';
    let currentFaceGuideInstruction = 'Pusatkan wajah di dalam oval.';

    function stopSelfieStream() {
        faceScanner.stopCamera(document.getElementById('selfie-video'));
        if (selfieStream && typeof selfieStream.getTracks === 'function') {
            selfieStream.getTracks().forEach(track => track.stop());
        }
        selfieStream = null;
    }

    async function waitForVideoFrame(video, timeoutMs = 4000) {
        if (!video) {
            throw new Error('Kamera tidak tersedia.');
        }

        const startedAt = Date.now();
        while (Date.now() - startedAt < timeoutMs) {
            const hasDimensions = Number(video.videoWidth) > 0 && Number(video.videoHeight) > 0;
            const hasCurrentFrame = Number(video.readyState) >= 2;

            if (hasDimensions && hasCurrentFrame) {
                return;
            }

            await new Promise((resolve) => window.setTimeout(resolve, 120));
        }

        throw new Error('Kamera belum siap mengambil foto. Tunggu sebentar lalu coba lagi.');
    }

    async function waitForVideoPlaybackReady(video, timeoutMs = 5000) {
        if (!video) {
            throw new Error('Kamera tidak tersedia.');
        }

        const isReady = () => (
            Number(video.videoWidth) > 0
            && Number(video.videoHeight) > 0
            && Number(video.readyState) >= 1
        );

        if (isReady()) {
            return;
        }

        await new Promise((resolve, reject) => {
            let settled = false;
            let pollTimer = null;
            let timeoutTimer = null;

            const cleanup = () => {
                ['loadedmetadata', 'loadeddata', 'canplay', 'playing'].forEach((eventName) => {
                    video.removeEventListener(eventName, handleReady);
                });

                if (pollTimer !== null) {
                    window.clearInterval(pollTimer);
                }

                if (timeoutTimer !== null) {
                    window.clearTimeout(timeoutTimer);
                }
            };

            const finish = (callback) => {
                if (settled) {
                    return;
                }

                settled = true;
                cleanup();
                callback();
            };

            const handleReady = () => {
                if (isReady()) {
                    finish(resolve);
                }
            };

            ['loadedmetadata', 'loadeddata', 'canplay', 'playing'].forEach((eventName) => {
                video.addEventListener(eventName, handleReady);
            });

            pollTimer = window.setInterval(handleReady, 120);
            timeoutTimer = window.setTimeout(() => {
                finish(() => reject(new Error('Kamera belum siap. Tutup lalu buka lagi kamera selfie.')));
            }, timeoutMs);

            handleReady();
        });
    }

    function setSelfieStatus(message, type = 'info', title = null) {
        const statusElement = document.getElementById('selfie-status');
        if (!statusElement) {
            return;
        }
        statusElement.className = 'selfie-status-banner';
        statusElement.innerHTML = `<span>${message}</span>`;
    }

    async function confirmEarlyCheckoutIfNeeded() {
        if (!isPresensiKeluar || !pulangStartSeconds || earlyCheckoutConfirmed) {
            return true;
        }

        const now = new Date();
        const nowSeconds = now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds();

        if (nowSeconds >= pulangStartSeconds) {
            return true;
        }

        const res = await showFormalAlert({
            title: 'Konfirmasi Presensi Pulang',
            text: 'Anda akan melakukan presensi pulang sebelum waktu yang ditetapkan. Lanjutkan proses presensi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        });

        if (res?.isConfirmed) {
            earlyCheckoutConfirmed = true;
            return true;
        }

        return false;
    }

    function isRetryableFaceScanError(message = '') {
        return typeof message === 'string'
            && (message.includes('Gerakan tidak sesuai instruksi') || message.includes('Gerakan belum sesuai instruksi'));
    }

    function isFaceMismatchRejection(message = '', notes = '') {
        const normalizedMessage = String(message || '').toLowerCase();
        const normalizedNotes = String(notes || '').toLowerCase();

        return normalizedNotes === 'face_similarity_below_threshold'
            || normalizedMessage.includes('wajah tidak cocok');
    }

    function resolveFaceInstructionMeta(message = '') {
        const normalized = String(message || '').toLowerCase();

        if (normalized.includes('memeriksa kecocokan') || normalized.includes('mencocokkan wajah')) {
            return { icon: 'bx-loader-alt', title: 'Memeriksa Kecocokan' };
        }
        if (normalized.includes('wajah tidak cocok')) {
            return { icon: 'bx-x-circle', title: 'Wajah Tidak Cocok' };
        }
        if (normalized.includes('kedip')) {
            return { icon: 'bx-low-vision', title: 'Kedipkan Mata' };
        }
        if (normalized.includes('kiri')) {
            return { icon: 'bx-left-arrow-alt', title: 'Menengok Sedikit ke Kiri' };
        }
        if (normalized.includes('kanan')) {
            return { icon: 'bx-right-arrow-alt', title: 'Menengok Sedikit ke Kanan' };
        }
        if (normalized.includes('atas')) {
            return { icon: 'bx-up-arrow-alt', title: 'Menengok Sedikit ke Atas' };
        }
        if (normalized.includes('bawah')) {
            return { icon: 'bx-down-arrow-alt', title: 'Menengok Sedikit ke Bawah' };
        }
        if (normalized.includes('mulut')) {
            return { icon: 'bx-message-rounded-detail', title: 'Buka Mulut Sedikit' };
        }
        if (normalized.includes('berhasil') || normalized.includes('selesai') || normalized.includes('terverifikasi')) {
            return { icon: 'bx-check-circle', title: 'Berhasil' };
        }
        if (normalized.includes('ulang') || normalized.includes('gagal') || normalized.includes('tidak sesuai')) {
            return { icon: 'bx-refresh', title: 'Ulangi Scan' };
        }
        if (normalized.includes('memuat') || normalized.includes('menyiapkan') || normalized.includes('memindai')) {
            return { icon: 'bx-loader-alt', title: 'Memproses' };
        }
        if (normalized.includes('posisi') || normalized.includes('pusatkan') || normalized.includes('wajah')) {
            return { icon: 'bx-user-circle', title: 'Pusatkan Wajah di Oval' };
        }

        return { icon: 'bx-scan', title: 'Instruksi' };
    }

    function resolveFaceGuideLabel(message = '') {
        const normalized = String(message || '').toLowerCase();

        if (normalized.includes('memeriksa kecocokan') || normalized.includes('mencocokkan wajah')) {
            return 'Memeriksa Kecocokan';
        }
        if (normalized.includes('wajah tidak cocok')) {
            return 'Wajah Tidak Cocok';
        }
        if (normalized.includes('berhasil') || normalized.includes('selesai') || normalized.includes('terverifikasi')) {
            return 'Verifikasi Selesai';
        }
        if (normalized.includes('kedip')) {
            return 'Kedipkan Mata';
        }
        if (normalized.includes('kiri')) {
            return 'Menengok Sedikit ke Kiri';
        }
        if (normalized.includes('kanan')) {
            return 'Menengok Sedikit ke Kanan';
        }
        if (normalized.includes('atas')) {
            return 'Menengok Sedikit ke Atas';
        }
        if (normalized.includes('bawah')) {
            return 'Menengok Sedikit ke Bawah';
        }
        if (normalized.includes('mulut')) {
            return 'Buka Mulut Sedikit';
        }
        if (normalized.includes('ulang') || normalized.includes('gagal') || normalized.includes('tidak sesuai')) {
            return 'Ulangi Scan';
        }
        if (normalized.includes('memuat') || normalized.includes('menyiapkan') || normalized.includes('memindai')) {
            return 'Menyiapkan Scan';
        }
        if (normalized.includes('posisi') || normalized.includes('pusatkan') || normalized.includes('wajah') || normalized.includes('geser')) {
            return 'Pusatkan Wajah di Oval';
        }

        return 'Ikuti Instruksi';
    }

    function resolveFaceGuideInstruction(message = '') {
        const normalized = String(message || '').toLowerCase();

        if (normalized.includes('memeriksa kecocokan') || normalized.includes('mencocokkan wajah')) {
            return 'Tunggu proses pencocokan dengan wajah terdaftar.';
        }
        if (normalized.includes('wajah tidak cocok')) {
            return 'Presensi ditolak karena wajah tidak cocok.';
        }
        if (normalized.includes('kedip')) {
            return 'Kedip satu kali dengan jelas.';
        }
        if (normalized.includes('kiri')) {
            return 'Sedikit menengok ke arah kiri.';
        }
        if (normalized.includes('kanan')) {
            return 'Sedikit menengok ke arah kanan.';
        }
        if (normalized.includes('atas')) {
            return 'Sedikit menengok ke arah atas.';
        }
        if (normalized.includes('bawah')) {
            return 'Sedikit menengok ke arah bawah.';
        }
        if (normalized.includes('mulut')) {
            return 'Buka mulut sedikit sebentar.';
        }
        if (normalized.includes('ulang') || normalized.includes('gagal') || normalized.includes('tidak sesuai')) {
            return 'Ulangi proses scan dari awal.';
        }
        if (normalized.includes('berhasil') || normalized.includes('selesai') || normalized.includes('terverifikasi')) {
            return 'Verifikasi sudah selesai.';
        }
        if (normalized.includes('posisi') || normalized.includes('pusatkan') || normalized.includes('wajah') || normalized.includes('geser')) {
            return 'Pusatkan wajah di dalam oval.';
        }

        return 'Ikuti instruksi scan yang tampil.';
    }

    function updateFaceInstruction(message, title = null) {
        const instructionElement = document.getElementById('face-instruction-box');
        const instructionText = document.getElementById('face-instruction-text');
        if (!instructionElement || !instructionText) return;

        const instructionMeta = faceScanRequired
            ? resolveFaceInstructionMeta(message)
            : { icon: 'bx-camera', title: 'Selfie' };
        currentFaceInstructionIcon = instructionMeta.icon;
        const heading = title || (faceScanRequired ? instructionMeta.title : 'Selfie');
        instructionElement.querySelector('strong').textContent = heading;
        instructionText.textContent = message;

        if (faceScanRequired && selfieGuideText) {
            selfieGuideText.textContent = resolveFaceGuideLabel(message);
            currentFaceGuideInstruction = resolveFaceGuideInstruction(message);
            if (selfieGuideInstruction) {
                selfieGuideInstruction.textContent = currentFaceGuideInstruction;
            }
            if (selfieGuideDetail) {
                selfieGuideDetail.textContent = message;
            }
            updateSelfieGuideTone(selfieContainer?.dataset.guideState || 'searching');
        }
    }

    function resetSelfieProgress() {
        selfieProgressItems.forEach((item) => item.classList.remove('active', 'done'));
        currentSelfieProgress = 0;
        targetSelfieProgress = 0;
        if (selfieProgressAnimationFrame !== null) {
            window.cancelAnimationFrame(selfieProgressAnimationFrame);
            selfieProgressAnimationFrame = null;
        }
        setSelfieProgressOrbState('default');
        renderSelfieProgress(0);
    }

    function setSelfieProgressOrbState(state = 'default') {
        if (!selfieProgressOrb) {
            return;
        }

        const palette = {
            default: '#4ade80',
            success: '#4ade80',
            error: '#f87171',
        };

        selfieProgressOrb.style.setProperty('--progress-fill', palette[state] || palette.default);
        selfieProgressOrb.classList.toggle('is-complete', state === 'success');
        selfieProgressOrb.classList.toggle('is-error', state === 'error');
    }

    function renderSelfieProgress(progress) {
        const normalized = Math.max(0, Math.min(progress, 1));
        if (selfieProgressFill) {
            selfieProgressFill.style.width = `${normalized * 100}%`;
        }

        if (selfieProgressOrb && selfieProgressValue) {
            const percent = Math.round(normalized * 100);
            selfieProgressOrb.style.setProperty('--progress-angle', `${normalized * 360}deg`);
            selfieProgressValue.textContent = `${percent}%`;

            if (!selfieProgressOrb.classList.contains('is-error')) {
                setSelfieProgressOrbState(percent >= 100 ? 'success' : 'default');
            }
        }
    }

    function animateSelfieProgress() {
        const delta = targetSelfieProgress - currentSelfieProgress;

        if (Math.abs(delta) < 0.003) {
            currentSelfieProgress = targetSelfieProgress;
            renderSelfieProgress(currentSelfieProgress);
            selfieProgressAnimationFrame = null;
            return;
        }

        currentSelfieProgress += delta * 0.18;
        renderSelfieProgress(currentSelfieProgress);
        selfieProgressAnimationFrame = window.requestAnimationFrame(animateSelfieProgress);
    }

    function updateSelfieProgress(step, state) {
        const target = document.querySelector(`.selfie-progress-item[data-step="${step}"]`);
        if (!target) {
            return;
        }

        target.classList.remove('active', 'done');
        if (state === 'active') {
            target.classList.add('active');
        }
        if (state === 'done') {
            target.classList.add('done');
        }

        updateSelfieProgressRing();
    }

    function updateSelfieProgressRing() {
        if (!selfieProgressFill && !selfieProgressOrb) {
            return;
        }

        const totalSteps = selfieProgressItems.length || 1;
        const doneCount = selfieProgressItems.filter((item) => item.classList.contains('done')).length;
        const activeIndex = selfieProgressItems.findIndex((item) => item.classList.contains('active'));
        let progress = doneCount / totalSteps;

        if (activeIndex >= 0 && doneCount < totalSteps) {
            progress = Math.max(progress, (activeIndex + 0.5) / totalSteps);
        }

        if (selfieContainer?.dataset.guideState === 'success') {
            progress = 1;
        }

        targetSelfieProgress = Math.max(0, Math.min(progress, 1));

        if (selfieProgressAnimationFrame !== null) {
            return;
        }

        selfieProgressAnimationFrame = window.requestAnimationFrame(animateSelfieProgress);
    }

    function setSelfieQualityState(key, state = 'idle') {
        const target = document.querySelector(`.selfie-quality-item[data-quality="${key}"]`);
        if (!target) {
            return;
        }

        target.classList.remove('active', 'done');
        if (state === 'active') {
            target.classList.add('active');
        }
        if (state === 'done') {
            target.classList.add('done');
        }
    }

    function updateSelfieQualityIndicators(guideState) {
        if (!faceScanRequired) {
            return;
        }

        const qualityMap = {
            searching: { detected: 'idle', centered: 'idle', stable: 'idle' },
            'too-far': { detected: 'active', centered: 'idle', stable: 'idle' },
            'too-close': { detected: 'active', centered: 'idle', stable: 'idle' },
            tilted: { detected: 'active', centered: 'active', stable: 'idle' },
            'off-center': { detected: 'active', centered: 'idle', stable: 'idle' },
            warning: { detected: 'done', centered: 'active', stable: 'idle' },
            steady: { detected: 'done', centered: 'active', stable: 'active' },
            aligned: { detected: 'done', centered: 'done', stable: 'done' },
            success: { detected: 'done', centered: 'done', stable: 'done' },
        };

        const current = qualityMap[guideState] || qualityMap.searching;
        Object.entries(current).forEach(([key, state]) => setSelfieQualityState(key, state));
    }

    function inferSelfieStatusType(message) {
        if (!message) {
            return 'info';
        }

        if (message.includes('berhasil')) {
            return 'success';
        }

        if (message.includes('Menunggu aksi') || message.includes('Pertahankan') || message.includes('Posisi')) {
            return 'warning';
        }

        return 'info';
    }

    function updateSelfieGuideTone(state) {
        if (!faceScanRequired) {
            return;
        }

        const iconEl = document.querySelector('#selfie-guide-pill i');
        if (!iconEl) {
            return;
        }

        const palette = {
            success: '#86efac',
            aligned: '#86efac',
            steady: '#86efac',
            processing: '#fef08a',
            warning: '#fca5a5',
            'too-far': '#fca5a5',
            'too-close': '#fca5a5',
            tilted: '#fca5a5',
            'off-center': '#fca5a5',
            searching: '#fca5a5',
        };

        const resolvedIcon = state === 'success' ? 'bx-check-circle' : (currentFaceInstructionIcon || 'bx-scan');
        iconEl.className = `bx ${resolvedIcon}`;
        iconEl.style.color = palette[state] || palette.searching;
    }

    function showFaceScanRetryButton(label = 'Ulangi Scan') {
        const captureBtn = document.getElementById('btn-capture-selfie');
        if (!captureBtn || !faceScanRequired) {
            return;
        }

        captureBtn.disabled = false;
        captureBtn.classList.add('retry-scan');
        captureBtn.style.display = 'inline-flex';
        captureBtn.innerHTML = `<i class="bx bx-refresh me-1"></i>${label}`;
    }

    function hideFaceScanRetryButton() {
        const captureBtn = document.getElementById('btn-capture-selfie');
        if (!captureBtn) {
            return;
        }

        captureBtn.classList.remove('retry-scan');
        captureBtn.style.display = faceScanRequired ? 'none' : 'block';
        captureBtn.innerHTML = faceScanRequired
            ? '<i class="bx bx-scan me-1"></i>Mulai Scan'
            : '<i class="bx bx-camera me-1"></i>Ambil Foto';
    }

    function handleFaceScanVerificationRejection(message, notes = null) {
        if (!faceScanRequired) {
            return false;
        }

        if (!isFaceMismatchRejection(message, notes)) {
            return false;
        }

        const rejectionMessage = 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.';
        presensiSubmitInFlight = false;
        selfieCaptured = false;
        faceVerificationResult = null;

        const selfieDataInput = document.getElementById('selfie-data');
        const faceDescriptorInput = document.getElementById('face-descriptor');
        const livenessScoreInput = document.getElementById('liveness-score');
        const livenessChallengesInput = document.getElementById('liveness-challenges');

        if (selfieDataInput) {
            selfieDataInput.value = '';
        }
        if (faceDescriptorInput) {
            faceDescriptorInput.value = '';
        }
        if (livenessScoreInput) {
            livenessScoreInput.value = '';
        }
        if (livenessChallengesInput) {
            livenessChallengesInput.value = '';
        }

        updateSelfieGuideState({
            state: 'warning',
            message: rejectionMessage,
        });
        setSelfieProgressOrbState('error');
        setSelfieStatus(rejectionMessage, 'error');
        updateFaceInstruction(rejectionMessage);
        showFaceScanRetryButton('Scan Ulang');

        $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');

        return true;
    }

    async function requestFaceMatchVerification(descriptor, livenessScore = 1, livenessChallenges = []) {
        const response = await fetch(faceVerifyUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                face_descriptor: descriptor,
                liveness_score: livenessScore,
                liveness_challenges: livenessChallenges,
            }),
        });

        let payload = null;
        try {
            payload = await response.json();
        } catch (error) {
            payload = null;
        }

        if (!response.ok || !payload?.face_verified) {
            return {
                face_verified: false,
                message: payload?.message || 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.',
                notes: payload?.notes || 'face_similarity_below_threshold',
                similarity: payload?.similarity ?? null,
            };
        }

        return payload;
    }

    async function verifyFaceMatchBeforeChallenges(descriptor) {
        return requestFaceMatchVerification(descriptor, 1, []);
    }

    async function verifyFaceMatchAfterChallenges(result) {
        updateSelfieGuideState({
            state: 'processing',
            message: 'Mencocokkan wajah dengan data terdaftar.',
        });
        setSelfieProgressOrbState('default');
        updateFaceInstruction('Mencocokkan wajah dengan data terdaftar.');
        setSelfieStatus('Mencocokkan wajah hasil scan dengan data yang terdaftar.');

        const verification = await requestFaceMatchVerification(
            result?.face_descriptor || [],
            result?.liveness_score ?? 0,
            result?.liveness_challenges || []
        );

        if (!verification?.face_verified) {
            const message = verification?.message || 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.';
            const error = new Error(message);
            error.notes = verification?.notes || 'face_similarity_below_threshold';
            error.similarity = verification?.similarity ?? null;
            throw error;
        }

        updateSelfieGuideState({
            state: 'success',
            message: 'Wajah cocok dengan data terdaftar.',
        });
        updateFaceInstruction('Wajah cocok dengan data terdaftar.');
        setSelfieStatus('Wajah cocok. Presensi sedang dikirim.', 'success');

        return verification;
    }

    function resetSelfieModalState() {
        const video = document.getElementById('selfie-video');
        const preview = document.getElementById('selfie-preview');
        const canvas = document.getElementById('selfie-canvas');
        const captureBtn = document.getElementById('btn-capture-selfie');
        const submitBtn = document.getElementById('btn-submit-presensi');
        const selfieDataInput = document.getElementById('selfie-data');
        const faceDescriptorInput = document.getElementById('face-descriptor');
        const livenessScoreInput = document.getElementById('liveness-score');
        const livenessChallengesInput = document.getElementById('liveness-challenges');
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
            captureBtn.style.display = faceScanRequired ? 'none' : 'block';
            captureBtn.disabled = !faceScanRequired;
            captureBtn.innerHTML = faceScanRequired
                ? '<i class="bx bx-scan me-1"></i>Mulai Scan'
                : '<i class="bx bx-loader-alt bx-spin me-1"></i>Menyiapkan Kamera...';
        }
        if (submitBtn) {
            submitBtn.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Kirim Presensi';
        }
        if (selfieDataInput) {
            selfieDataInput.value = '';
        }
        if (faceDescriptorInput) {
            faceDescriptorInput.value = '';
        }
        if (livenessScoreInput) {
            livenessScoreInput.value = '';
        }
        if (livenessChallengesInput) {
            livenessChallengesInput.value = '';
        }
        if (placeholder) {
            placeholder.style.display = 'flex';
        }

        stopSelfieStream();
        selfieCaptured = false;
        pendingSelfieData = '';
        earlyCheckoutConfirmed = false;
        faceVerificationResult = null;
        faceScanAutoStarted = false;
        faceScanOnboardingAccepted = !faceScanRequired;
        currentFaceGuideInstruction = 'Pusatkan wajah di dalam oval.';
        if (faceScanOnboarding) {
            faceScanOnboarding.hidden = !faceScanRequired;
        }
        resetSelfieProgress();
        updateSelfieQualityIndicators('searching');
        updateSelfieGuideTone('searching');
        setSelfieStatus(
            faceScanRequired
                ? 'Kamera akan aktif otomatis saat modal dibuka.'
                : 'Kamera akan aktif otomatis saat modal dibuka.',
            'info',
            faceScanRequired ? 'Siapkan scan' : 'Siapkan selfie'
        );
        updateFaceInstruction(faceScanRequired
            ? 'Aktifkan kamera lalu ikuti arahan scan wajah.'
            : 'Aktifkan kamera lalu ambil selfie untuk presensi.');
        updateSelfieGuideState({
            state: 'searching',
            message: 'Pusatkan wajah di dalam oval.',
        });
    }

    function openSelfieModal() {
        if (!selfieModal) return;

        resetSelfieModalState();
        selfieModal.classList.add('show');
        selfieModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('selfie-modal-open');

        if (selfieModalSubtitle) {
            if (faceScanRequired) {
                selfieModalSubtitle.textContent = isPresensiKeluar
                    ? 'Posisikan wajah lalu ikuti arahan singkat untuk presensi keluar.'
                    : 'Posisikan wajah lalu ikuti arahan singkat untuk presensi masuk.';
            } else {
                selfieModalSubtitle.textContent = isPresensiKeluar
                 //   ? 'Ambil selfie untuk presensi keluar, lalu kirim.'
                 //   : 'Ambil selfie untuk presensi masuk, lalu kirim.';
            }
        }

        if (faceScanRequired && faceScanOnboardingContinue) {
            window.setTimeout(() => faceScanOnboardingContinue.focus(), 80);
        }
    }

    async function retryFaceScanFromHeader() {
        if (!faceScanRequired || !selfieModal?.classList.contains('show') || presensiSubmitInFlight) {
            return;
        }

        resetSelfieModalState();

        if (faceScanOnboarding) {
            faceScanOnboarding.hidden = true;
        }
        faceScanOnboardingAccepted = true;

        try {
            await initializeSelfieCamera();
        } catch (error) {
            closeSelfieModal();
            showFormalErrorAlert(
                'Akses Kamera Tidak Tersedia',
                'Kamera tidak dapat diakses. Pastikan izin kamera telah diberikan, lalu coba kembali.'
            );
        }
    }

    async function startFaceScanAfterOnboarding() {
        if (!faceScanRequired) {
            return;
        }

        if (faceScanOnboardingAccepted) {
            if (faceScanOnboarding) {
                faceScanOnboarding.hidden = true;
            }
            return;
        }

        faceScanOnboardingAccepted = true;
        if (faceScanOnboarding) {
            faceScanOnboarding.hidden = true;
        }

        try {
            await initializeSelfieCamera();
        } catch (error) {
            closeSelfieModal();
            showFormalErrorAlert(
                'Akses Kamera Tidak Tersedia',
                'Kamera tidak dapat diakses. Pastikan izin kamera telah diberikan, lalu coba kembali.'
            );
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

    function updateSelfieGuideState(payload = {}) {
        if (!faceScanRequired || !selfieContainer) {
            return;
        }

        if (payload.state) {
            selfieContainer.dataset.guideState = payload.state;
            updateSelfieQualityIndicators(payload.state);
            updateSelfieGuideTone(payload.state);
            updateSelfieProgressRing();
        }

        if (selfieGuideText && payload.message) {
            selfieGuideText.textContent = resolveFaceGuideLabel(payload.message);
        }

        if (selfieGuideInstruction) {
            selfieGuideInstruction.textContent = currentFaceGuideInstruction || resolveFaceGuideInstruction(payload.message);
        }

        if (selfieGuideDetail && payload.message) {
            selfieGuideDetail.textContent = payload.message;
        }
    }

    // Initialize face scan camera
    async function initializeSelfieCamera() {
        try {
            const video = document.getElementById('selfie-video');
            const container = document.getElementById('selfie-container');
            const captureBtn = document.getElementById('btn-capture-selfie');

            if (!video || !container || !captureBtn) {
                console.error('Required DOM elements for selfie camera not found');
                throw new Error('DOM elements not ready');
            }

            stopSelfieStream();

            if (faceScanRequired) {
                updateFaceInstruction('Memuat model scan wajah. Mohon tunggu sebentar.');
                await faceScanner.loadModels();
                await faceScanner.initializeCamera(video);
            } else {
                captureBtn.disabled = true;
                captureBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Menyiapkan Kamera...';
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
                await waitForVideoPlaybackReady(video);
                await video.play();
                await waitForVideoFrame(video);
                //updateFaceInstruction('Kamera siap. Tekan Ambil Foto untuk menyimpan selfie presensi.');
            }
            video.style.display = 'block';

            const placeholder = container.querySelector('.selfie-placeholder');
            if (placeholder) {
                placeholder.style.display = 'none';
            }

            if (faceScanRequired) {
                captureBtn.style.display = 'none';
                setSelfieStatus('Kamera aktif. Tahan posisi wajah sebentar.');
                updateFaceInstruction('Posisikan wajah di dalam oval lalu ikuti arahan scan wajah.');
                updateSelfieGuideState({
                    state: 'steady',
                    message: 'Pusatkan wajah di dalam oval.',
                });

                if (!faceScanAutoStarted && selfieModal?.classList.contains('show')) {
                    faceScanAutoStarted = true;
                    window.setTimeout(() => {
                        if (!selfieModal?.classList.contains('show') || selfieCaptured) {
                            return;
                        }
                        captureSelfie();
                    }, 220);
                }
            } else {
                captureBtn.style.display = 'block';
                captureBtn.disabled = false;
                captureBtn.innerHTML = '<i class="bx bx-camera me-1"></i>Ambil Foto';
                //setSelfieStatus('Kamera siap digunakan untuk selfie presensi.');
            }

        } catch (error) {
            console.error('Error accessing camera:', error);
            setSelfieStatus('Pastikan izin kamera diberikan lalu coba lagi.');
            throw error; // Re-throw to handle in calling function
        }
    }

    async function captureSelfie() {
        const video = document.getElementById('selfie-video');
        const canvas = document.getElementById('selfie-canvas');
        const selfieDataInput = document.getElementById('selfie-data');
        const faceDescriptorInput = document.getElementById('face-descriptor');
        const livenessScoreInput = document.getElementById('liveness-score');
        const livenessChallengesInput = document.getElementById('liveness-challenges');
        const selfiePreview = document.getElementById('selfie-preview');
        const captureBtn = document.getElementById('btn-capture-selfie');

        if (faceScanRequired) {
            hideFaceScanRetryButton();
        }

        if (!faceScanRequired && selfieCaptured && pendingSelfieData.length >= 100) {
            setSelfieStatus('Selfie sudah siap. Tekan Kirim Presensi untuk lanjut.');
            return;
        }

        if (captureBtn) {
            captureBtn.disabled = true;
            captureBtn.innerHTML = faceScanRequired
                ? '<i class="bx bx-loader-alt bx-spin me-1"></i>Memindai...'
                : '<i class="bx bx-loader-alt bx-spin me-1"></i>Mengambil...';
        }

        try {
            if (faceScanRequired) {
                resetSelfieProgress();
                setSelfieProgressOrbState('default');
                faceVerificationResult = await faceScanner.performAttendanceScan(video, {
                    onInstruction: (message) => updateFaceInstruction(message),
                    onChallengeState: (step, state) => updateSelfieProgress(step, state),
                    onStatus: (message) => {
                        setSelfieStatus(message, inferSelfieStatusType(message));
                    },
                    onGuideState: (payload) => updateSelfieGuideState(payload),
                    onFaceMatchCheck: (descriptor) => verifyFaceMatchBeforeChallenges(descriptor),
                });
                await verifyFaceMatchAfterChallenges(faceVerificationResult);
            } else {
                await waitForVideoFrame(video);
                const ctx = canvas.getContext('2d');
                const sourceWidth = Number(video.videoWidth) || 0;
                const sourceHeight = Number(video.videoHeight) || 0;
                const aspectRatio = 3 / 4;
                if (!ctx || sourceWidth <= 0 || sourceHeight <= 0) {
                    throw new Error('Frame kamera belum siap. Silakan ambil ulang selfie.');
                }

                const canvasWidth = Math.round(Math.min(sourceWidth, sourceHeight * aspectRatio));
                const canvasHeight = Math.round(canvasWidth / aspectRatio);
                canvas.width = canvasWidth;
                canvas.height = canvasHeight;

                const sourceX = (sourceWidth - canvasWidth) / 2;
                const sourceY = (sourceHeight - canvasHeight) / 2;

                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
                ctx.drawImage(video, sourceX, sourceY, canvasWidth, canvasHeight, 0, 0, canvasWidth, canvasHeight);
                ctx.setTransform(1, 0, 0, 1, 0, 0);

                faceVerificationResult = {
                    captured_image: canvas.toDataURL('image/jpeg', 0.85),
                    face_descriptor: null,
                    liveness_score: null,
                    liveness_challenges: null,
                };
            }

            if (!faceVerificationResult?.captured_image || faceVerificationResult.captured_image.length < 100) {
                throw new Error(
                    faceScanRequired
                        ? 'Hasil scan wajah belum berhasil diproses. Silakan ulangi scan.'
                        : 'Foto selfie belum berhasil diproses. Silakan ambil ulang selfie.'
                );
            }

            if (selfieDataInput) {
                selfieDataInput.value = faceVerificationResult.captured_image;
            }
            pendingSelfieData = faceVerificationResult.captured_image;
            if (faceDescriptorInput) {
                faceDescriptorInput.value = faceVerificationResult.face_descriptor ? JSON.stringify(faceVerificationResult.face_descriptor) : '';
            }
            if (livenessScoreInput) {
                livenessScoreInput.value = faceVerificationResult.liveness_score !== null ? String(faceVerificationResult.liveness_score) : '';
            }
            if (livenessChallengesInput) {
                livenessChallengesInput.value = faceVerificationResult.liveness_challenges ? JSON.stringify(faceVerificationResult.liveness_challenges) : '';
            }

            if (selfiePreview) {
                selfiePreview.src = faceVerificationResult.captured_image;
                selfiePreview.style.display = 'block';
            }
            if (canvas) {
                canvas.style.display = 'none';
            }

            if (video) {
                video.style.display = 'none';
            }
            if (captureBtn) {
                captureBtn.style.display = 'none';
            }

            stopSelfieStream();
            selfieCaptured = true;
            if (faceScanRequired) {
                updateSelfieGuideState({
                    state: 'success',
                    message: 'Wajah cocok. Mengirim presensi.',
                });
                setSelfieProgressOrbState('success');
                updateFaceInstruction('Wajah cocok. Mengirim presensi.');
                setSelfieStatus('Wajah cocok dengan data terdaftar. Presensi sedang dikirim.', 'success');
            } else {
                updateSelfieGuideState({
                    state: 'processing',
                    message: 'Selfie berhasil diambil. Tekan Kirim Presensi untuk lanjut.',
                });
                updateFaceInstruction('Selfie berhasil diambil. Tekan Kirim Presensi untuk lanjut.');
                setSelfieStatus('Selfie berhasil diambil.');
            }

            const submitPresensiBtn = $('#btn-submit-presensi');
            submitPresensiBtn.prop('disabled', false);
            if (faceScanRequired) {
                window.setTimeout(() => {
                    if (!presensiSubmitInFlight) {
                        submitPresensiBtn.trigger('click');
                    }
                }, 260);
            } else {
                submitPresensiBtn.show();
            }
        } catch (error) {
            console.error('Face scan failed:', error);
            const errorMessage = error?.message || (faceScanRequired ? 'Scan wajah belum berhasil.' : 'Selfie belum berhasil diambil.');
            const shouldAutoRetry = faceScanRequired && isRetryableFaceScanError(errorMessage);
            const verificationRejected = faceScanRequired && (
                isFaceMismatchRejection(errorMessage, error?.notes)
                || error?.notes === 'liveness_below_threshold'
                || error?.notes === 'challenge_payload_invalid'
            );

            if (!faceScanRequired) {
                showFormalErrorAlert(
                    'Pengambilan Selfie Gagal',
                    errorMessage || 'Selfie belum berhasil diambil. Silakan ulangi.'
                );
            }
            setSelfieStatus(
                shouldAutoRetry
                    ? 'Instruksi salah. Scan diulang dari awal.'
                    : errorMessage,
                'error'
            );
            if (faceScanRequired) {
                setSelfieProgressOrbState('error');
            }
            updateFaceInstruction(faceScanRequired
                ? (shouldAutoRetry ? 'Instruksi salah. Mengulang scan dari awal.' : errorMessage)
                : 'Ulangi pengambilan selfie.');

            if (verificationRejected) {
                updateSelfieGuideState({
                    state: 'warning',
                    message: errorMessage,
                });
                showFaceScanRetryButton('Scan Ulang');
            }

            if (shouldAutoRetry) {
                window.setTimeout(() => {
                    if (!selfieModal?.classList.contains('show')) {
                        return;
                    }

                    resetSelfieProgress();
                    updateSelfieGuideState({
                        state: 'steady',
                        message: 'Pusatkan wajah di dalam oval.',
                    });
                    updateFaceInstruction('Mengulang scan dari awal. Ikuti instruksi berikutnya.');
                    setSelfieStatus('Scan diulang dari awal.', 'info');
                }, 520);

                window.setTimeout(() => {
                    if (!selfieModal?.classList.contains('show') || !captureBtn) {
                        return;
                    }

                    captureSelfie();
                }, 860);
            }
        } finally {
            if (captureBtn) {
                captureBtn.disabled = false;
                if (!captureBtn.classList.contains('retry-scan')) {
                    captureBtn.innerHTML = faceScanRequired
                        ? '<i class="bx bx-scan me-1"></i>Mulai Scan'
                        : '<i class="bx bx-camera me-1"></i>Ambil Foto';
                }
            }
        }
    }

    // Event listeners for selfie buttons
    const captureBtn = document.getElementById('btn-capture-selfie');

    if (captureBtn) {
        captureBtn.addEventListener('click', captureSelfie);
    }

    if (faceScanOnboardingContinue) {
        faceScanOnboardingContinue.addEventListener('click', startFaceScanAfterOnboarding);
    }

    if (faceScanHelpButton) {
        faceScanHelpButton.addEventListener('click', function () {
            if (!faceScanRequired || !faceScanOnboarding) {
                return;
            }

            faceScanOnboarding.hidden = false;
            if (faceScanOnboardingContinue) {
                faceScanOnboardingContinue.focus();
            }
        });
    }

    $('#btn-presensi').click(async function() {
        if (faceScanRequired && !faceEnrollmentReady) {
            showFormalErrorAlert(
                'Wajah Belum Terdaftar',
                'Wajah Anda belum terdaftar. Tekan tombol "Daftar Wajah" pada peringatan presensi terlebih dahulu.'
            );
            return;
        }

        try {
            const earlyCheckoutAllowed = await confirmEarlyCheckoutIfNeeded();
            if (!earlyCheckoutAllowed) {
                return;
            }

            openSelfieModal();
            if (isPresensiKeluar && pulangStartSeconds) {
                earlyCheckoutConfirmed = true;
            }
            if (faceScanRequired) {
                return;
            }
            await initializeSelfieCamera();
        } catch (error) {
            closeSelfieModal();
            showFormalErrorAlert(
                'Akses Kamera Tidak Tersedia',
                'Kamera tidak dapat diakses. Pastikan izin kamera telah diberikan, lalu coba kembali.'
            );
        }
    });

    $('#btn-close-selfie-modal').click(function() {
        closeSelfieModal();
    });

    $('#btn-retry-selfie-modal').click(function() {
        retryFaceScanFromHeader();
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
        if (presensiSubmitInFlight) {
            return;
        }

        presensiSubmitInFlight = true;
        presensiFinalAlertShown = false;
        const submitButton = $(this);
        submitButton.prop('disabled', true);

        const latestLocationState = syncLatestLocationState();
        const presensiMode = isPresensiKeluar ? 'keluar' : 'masuk';

        const earlyCheckoutAllowed = await confirmEarlyCheckoutIfNeeded();
        if (!earlyCheckoutAllowed) {
            presensiSubmitInFlight = false;
            submitButton.prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
            return;
        }

        if (!latestLocationState.latitude || !latestLocationState.longitude) {
            showFormalErrorAlert(
                'Lokasi Belum Siap',
                'Data lokasi belum lengkap. Pastikan GPS aktif dan tunggu hingga proses pembacaan lokasi selesai.'
            );
            presensiSubmitInFlight = false;
            submitButton.prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
            return;
        }

        const selfieDataValue = pendingSelfieData || document.getElementById('selfie-data').value;
        const faceDescriptorValue = document.getElementById('face-descriptor').value;
        const livenessScoreValue = document.getElementById('liveness-score').value;
        const livenessChallengesValue = document.getElementById('liveness-challenges').value;

        const faceDataIncomplete = faceScanRequired && (!faceDescriptorValue || !livenessScoreValue || !livenessChallengesValue);
        if (!selfieDataValue || selfieDataValue.length < 100 || faceDataIncomplete) {
            presensiSubmitInFlight = false;
            submitButton.prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
            showFormalErrorAlert(
                faceScanRequired ? 'Scan Wajah Belum Lengkap' : 'Selfie Belum Lengkap',
                faceScanRequired
                //    ? 'Silakan selesaikan scan wajah terlebih dahulu sebelum mengirim presensi.'
                //   : 'Silakan ambil selfie terlebih dahulu sebelum mengirim presensi.'
            );
            return;
        }

        showFormalLoadingAlert(
            'Sedang Memproses Presensi',
            'Mohon menunggu. Data presensi sedang dikirim ke sistem.'
        );

        submitButton.html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

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

                let postData = {
                    _token: '{{ csrf_token() }}',
                    presensi_mode: presensiMode,
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

                if (faceScanRequired) {
                    postData.face_descriptor = JSON.parse(faceDescriptorValue);
                    postData.liveness_score = parseFloat(livenessScoreValue);
                    postData.liveness_challenges = JSON.parse(livenessChallengesValue);
                }

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
                            if (faceScanRequired) {
                                updateSelfieGuideState({
                                    state: 'success',
                                    message: 'Verifikasi wajah berhasil.',
                                });
                                setSelfieProgressOrbState('success');
                                updateFaceInstruction('Verifikasi wajah berhasil.');
                                setSelfieStatus('Presensi berhasil. Wajah cocok dengan data terdaftar.', 'success');
                            }
                            if (presensiFinalAlertShown) {
                                return;
                            }
                            presensiFinalAlertShown = true;
                            showFormalSuccessAlert(
                                'Presensi Berhasil Direkam',
                                resp.message || 'Data presensi telah berhasil dicatat.',
                                {
                                    timer: 1500,
                                    showConfirmButton: false
                                }
                            ).then(() => {
                                presensiSubmitInFlight = false;
                                closeSelfieModal();
                                window.location.reload();
                            });
                        } else {
                            presensiSubmitInFlight = false;
                            if (handleFaceScanVerificationRejection(resp?.message, resp?.notes)) {
                                return;
                            }
                            if (!presensiFinalAlertShown) {
                                presensiFinalAlertShown = true;
                                showFormalErrorAlert(
                                    'Presensi Ditolak',
                                    showFormalRejectMessage(resp.message, 'Presensi tidak dapat diproses. Silakan periksa kembali data yang dikirim.')
                                );
                            }
                            $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                        }
                    },
                    error: function(xhr, status, err) {
                        let message = 'Sistem tidak dapat dihubungi saat ini. Silakan coba beberapa saat lagi.';
                        let title = 'Permintaan Presensi Gagal';
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                            title = xhr.status >= 400 && xhr.status < 500 ? 'Presensi Ditolak' : 'Permintaan Presensi Gagal';
                        }
                        presensiSubmitInFlight = false;
                        if (handleFaceScanVerificationRejection(message, xhr?.responseJSON?.notes)) {
                            return;
                        }
                        if (!presensiFinalAlertShown) {
                            presensiFinalAlertShown = true;
                            showFormalErrorAlert(
                                title,
                                showFormalRejectMessage(message, 'Permintaan presensi tidak dapat diproses saat ini.')
                            );
                        }
                        $('#btn-submit-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                    }
                });

            },
            function(err){
                presensiSubmitInFlight = false;
                if (!presensiFinalAlertShown) {
                    presensiFinalAlertShown = true;
                    showFormalErrorAlert(
                        'Pembacaan Lokasi Gagal',
                        showFormalRejectMessage(err.message, 'Lokasi terakhir tidak dapat diperoleh. Silakan pastikan GPS aktif, lalu coba kembali.')
                    );
                }
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
