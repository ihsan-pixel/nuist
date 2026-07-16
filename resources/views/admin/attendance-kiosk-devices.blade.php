@extends('layouts.master')

@section('title', 'Komputer Presensi Sekolah')

@section('css')
<style>
    .kiosk-shell {
        border: 0;
        border-radius: 20px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
    }

    .kiosk-section {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
    }

    .kiosk-title {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .kiosk-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 0;
    }

    .device-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        white-space: nowrap;
    }

    .device-table td {
        vertical-align: top;
        font-size: 13px;
    }

    .device-name {
        font-weight: 600;
        color: #0f172a;
    }

    .device-meta {
        color: #64748b;
        font-size: 12px;
    }

    .token-box {
        padding: 12px 14px;
        border-radius: 14px;
        background: #0f172a;
        color: #e2e8f0;
        font-size: 12px;
        word-break: break-all;
    }

    .summary-box {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        padding: 16px;
        height: 100%;
    }

    .summary-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 26px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }

    .log-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        white-space: nowrap;
    }

    .log-table td {
        vertical-align: top;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Komputer Presensi Sekolah @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card kiosk-shell">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <h4 class="mb-1 fw-semibold">
                            <i class="bx bx-desktop me-2"></i>Komputer Presensi Sekolah
                        </h4>
                        <p class="text-muted mb-0 small">
                            Daftarkan komputer sekolah agar hanya perangkat dan IP yang diizinkan yang bisa dipakai untuk presensi guru.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('presensi_admin.settings') }}" class="btn btn-light">
                            <i class="bx bx-arrow-back me-1"></i>Kembali ke Pengaturan
                        </a>
                        <a href="{{ route('school-kiosk.index') }}" class="btn btn-primary">
                            <i class="bx bx-right-arrow-alt me-1"></i>Buka Mode Kiosk
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                @if(session('kiosk_registration'))
                    @php($registration = session('kiosk_registration'))
                    <div class="alert alert-info">
                        <div class="fw-semibold mb-2">Perangkat baru berhasil didaftarkan</div>
                        <div class="small text-muted mb-2">
                            Browser ini sudah diikat ke perangkat <strong>{{ $registration['device_name'] }}</strong> untuk {{ $registration['madrasah_name'] }}.
                        </div>
                        <div class="token-box">{{ $registration['plain_token'] }}</div>
                        <div class="small mt-2 mb-0 text-muted">
                            Token ini ditampilkan sekali. Simpan hanya jika Anda perlu migrasi ulang browser kiosk.
                        </div>
                    </div>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Total Perangkat</div>
                            <div class="summary-value">{{ $stats['total_devices'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Perangkat Aktif</div>
                            <div class="summary-value">{{ $stats['active_devices'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Submit Berhasil Hari Ini</div>
                            <div class="summary-value">{{ $stats['submit_success_today'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="summary-box">
                            <div class="summary-label">Akses Ditolak Hari Ini</div>
                            <div class="summary-value">{{ $stats['access_denied_today'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-4">
                        <div class="kiosk-section h-100">
                            <div class="card-body p-4">
                                <div class="kiosk-title">Daftarkan Komputer</div>
                                <p class="kiosk-subtitle mb-4">Pendaftaran dilakukan dari komputer yang akan dijadikan kiosk presensi.</p>

                                <form method="POST" action="{{ route('presensi_admin.kiosk_devices.store') }}">
                                    @csrf

                                    @if($canChooseMadrasah)
                                        <div class="mb-3">
                                            <label class="form-label">Madrasah</label>
                                            <select name="madrasah_id" class="form-select" required>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ (int) old('madrasah_id', $selectedMadrasahId) === (int) $school->id ? 'selected' : '' }}>
                                                        {{ $school->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <input type="hidden" name="madrasah_id" value="{{ $selectedMadrasahId }}">
                                        <div class="mb-3">
                                            <label class="form-label">Madrasah</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ optional($schools->firstWhere('id', $selectedMadrasahId))->name ?? 'Madrasah' }}"
                                                readonly
                                            >
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label">Nama Komputer</label>
                                        <input type="text" name="name" class="form-control" placeholder="Contoh: Front Office Kiosk 1" value="{{ old('name') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">IP yang Diizinkan</label>
                                        <textarea name="allowed_ip_addresses" class="form-control" rows="3" placeholder="Kosongkan untuk memakai IP saat ini">{{ old('allowed_ip_addresses', $currentIp) }}</textarea>
                                        <div class="form-text">Pisahkan lebih dari satu IP dengan koma atau baris baru.</div>
                                    </div>

                                    <input type="hidden" name="browser_fingerprint" id="browserFingerprintInput">

                                    <div class="alert alert-warning small mb-3">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Gunakan form ini dari browser komputer yang benar-benar akan dipakai untuk presensi.
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-save me-1"></i>Daftarkan Komputer Ini
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="kiosk-section">
                            <div class="card-body p-4">
                                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                                    <div>
                                        <div class="kiosk-title">Perangkat Terdaftar</div>
                                        <p class="kiosk-subtitle">Daftar komputer presensi yang sudah diizinkan untuk sekolah terpilih.</p>
                                    </div>

                                    @if($canChooseMadrasah)
                                        <form method="GET" action="{{ route('presensi_admin.kiosk_devices') }}">
                                            <select name="madrasah_id" class="form-select" onchange="this.form.submit()">
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ (int) $selectedMadrasahId === (int) $school->id ? 'selected' : '' }}>
                                                        {{ $school->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @endif
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 device-table">
                                        <thead>
                                            <tr>
                                                <th>Perangkat</th>
                                                <th>Madrasah</th>
                                                <th>IP Aktif</th>
                                                <th>Terakhir Aktif</th>
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($devices as $device)
                                                <tr>
                                                    <td>
                                                        <div class="device-name">{{ $device->name }}</div>
                                                        <div class="device-meta">
                                                            Didaftarkan oleh {{ $device->registeredBy?->name ?? '-' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="device-name">{{ $device->madrasah?->name ?? '-' }}</div>
                                                        <div class="device-meta">
                                                            {{ $device->madrasah?->kabupaten ?: 'Kabupaten belum diatur' }}
                                                            @if($device->madrasah?->scod)
                                                                • SCOD {{ $device->madrasah->scod }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>{{ collect($device->allowed_ip_addresses ?? [])->join(', ') ?: '-' }}</div>
                                                        <div class="device-meta">Terakhir: {{ $device->last_ip_address ?: '-' }}</div>
                                                    </td>
                                                    <td>
                                                        <div>{{ $device->last_seen_at?->format('d/m/Y H:i') ?: '-' }}</div>
                                                        <div class="device-meta text-truncate" style="max-width: 180px;">
                                                            {{ $device->last_user_agent ?: '-' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $device->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' }}">
                                                            {{ $device->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-inline-flex gap-2">
                                                            <form method="POST" action="{{ route('presensi_admin.kiosk_devices.sync_ip', $device) }}">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-light">
                                                                    Sinkronkan IP
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('presensi_admin.kiosk_devices.sync_fingerprint', $device) }}">
                                                                @csrf
                                                                <input type="hidden" name="browser_fingerprint" class="browser-fingerprint-input">
                                                                <button type="submit" class="btn btn-sm btn-light">
                                                                    Sinkronkan Fingerprint
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('presensi_admin.kiosk_devices.toggle', $device) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm {{ $device->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                                    {{ $device->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        Belum ada komputer presensi yang terdaftar.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kiosk-section mt-4">
                    <div class="card-body p-4">
                        <div class="kiosk-title">Aktivitas Kiosk Terbaru</div>
                        <p class="kiosk-subtitle mb-3">Riwayat akses perangkat dan submit presensi dari komputer sekolah.</p>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 log-table">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                        <th>Perangkat</th>
                                        <th>Operator</th>
                                        <th>Guru</th>
                                        <th>Status</th>
                                        <th>IP</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at?->format('d/m/Y H:i') ?: '-' }}</td>
                                            <td>{{ str_replace('_', ' ', $log->action) }}</td>
                                            <td>{{ $log->device?->name ?? '-' }}</td>
                                            <td>{{ $log->operator?->name ?? '-' }}</td>
                                            <td>{{ $log->targetUser?->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $log->status === 'success' ? 'bg-success-subtle text-success border border-success-subtle' : ($log->status === 'denied' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle') }}">
                                                    {{ $log->status }}
                                                </span>
                                            </td>
                                            <td>{{ $log->ip_address ?: '-' }}</td>
                                            <td class="text-muted small">
                                                {{ $log->payload_snapshot['message'] ?? ($log->payload_snapshot['mode'] ?? '-') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Belum ada aktivitas kiosk yang tercatat.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    (function () {
        const fingerprintInput = document.getElementById('browserFingerprintInput');
        const fingerprintSyncInputs = document.querySelectorAll('.browser-fingerprint-input');

        const buildFingerprint = () => {
            const parts = [
                navigator.userAgent || '',
                navigator.language || '',
                window.screen?.width || '',
                window.screen?.height || '',
                window.screen?.colorDepth || '',
                Intl.DateTimeFormat().resolvedOptions().timeZone || '',
                navigator.platform || '',
            ];

            return btoa(unescape(encodeURIComponent(parts.join('|')))).slice(0, 500);
        };

        const fingerprint = buildFingerprint();

        if (fingerprintInput) {
            fingerprintInput.value = fingerprint;
        }

        fingerprintSyncInputs.forEach((input) => {
            input.value = fingerprint;
        });

        document.cookie = `nuist_kiosk_fingerprint=${encodeURIComponent(fingerprint)}; path=/; max-age=${60 * 60 * 24 * 365}; samesite=lax`;
    })();
</script>
@endsection
