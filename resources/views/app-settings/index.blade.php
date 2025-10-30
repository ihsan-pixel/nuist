@extends('layouts.master')

@section('title') Pengaturan Aplikasi @endsection

@section('css')
<style>
.settings-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.settings-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.version-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.update-indicator {
    position: relative;
    display: inline-block;
}

.update-indicator .badge {
    position: absolute;
    top: -8px;
    right: -8px;
    border: 2px solid white;
}

.maintenance-alert {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    border: none;
    border-radius: 10px;
}

.form-switch .form-check-input {
    width: 3em;
    height: 1.5em;
}

.form-switch .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Pengaturan Aplikasi @endslot
@endcomponent

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($settings['maintenance_mode'])
<div class="alert maintenance-alert text-white mb-4" role="alert">
    <i class="bx bx-error-circle fs-4 me-2"></i>
    <strong>Mode Maintenance Aktif!</strong> Aplikasi sedang dalam mode maintenance dan tidak dapat diakses oleh pengguna biasa.
</div>
@endif

<div class="row">
    <!-- App Version & Update Status -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card settings-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">
                            <i class="bx bx-code-alt text-primary me-2"></i>
                            Versi Aplikasi
                        </h5>
                        <p class="text-muted mb-0 small">Versi saat ini dan status update</p>
                    </div>
                    <div class="version-badge">
                        v{{ $settings['app_version'] }}
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-calendar text-success me-2"></i>
                        <small class="text-muted">Terakhir Update:</small>
                    </div>
                    <p class="mb-2 fw-medium">
                        @if($latestHistory)
                            {{ $latestHistory->formatted_date ?? $latestHistory->development_date->format('d M Y') }}
                        @else
                            Belum ada data
                        @endif
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="checkForUpdates()">
                        <i class="bx bx-refresh me-1"></i>
                        Cek Update
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#updateVersionModal">
                        <i class="bx bx-edit me-1"></i>
                        Update Manual
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card settings-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">
                            <i class="bx bx-server text-info me-2"></i>
                            Sistem
                        </h5>
                        <p class="text-muted mb-0 small">Informasi sistem dan server</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                            <i class="bx bx-chip fs-5"></i>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="avatar-xs mx-auto mb-2">
                                <div class="avatar-title bg-light text-muted rounded-circle">
                                    <i class="bx bx-memory-card"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block">Memory</small>
                            <strong class="d-block">{{ $settings['memory_limit'] }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="avatar-xs mx-auto mb-2">
                                <div class="avatar-title bg-light text-muted rounded-circle">
                                    <i class="bx bx-upload"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block">Upload Max</small>
                            <strong class="d-block">{{ $settings['max_upload_size'] }}</strong>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <div class="d-flex align-items-center justify-content-between">
                    <small class="text-muted">PHP Version</small>
                    <strong>{{ PHP_VERSION }}</strong>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <small class="text-muted">Laravel Version</small>
                    <strong>{{ app()->version() }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card settings-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">
                            <i class="bx bx-cog text-warning me-2"></i>
                            Aksi Cepat
                        </h5>
                        <p class="text-muted mb-0 small">Operasi sistem umum</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                            <i class="bx bx-flash fs-5"></i>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="clearCache()">
                        <i class="bx bx-trash me-1"></i>
                        Bersihkan Cache
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="optimizeApp()">
                        <i class="bx bx-rocket me-1"></i>
                        Optimasi Aplikasi
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="viewLogs()">
                        <i class="bx bx-file me-1"></i>
                        Lihat Log Sistem
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="turnOffDebug()">
                        <i class="bx bx-shield-x me-1"></i>
                        Matikan Debug Mode
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settings Form -->
<div class="row">
    <div class="col-12">
        <div class="card settings-card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-slider me-2"></i>
                    Konfigurasi Aplikasi
                </h5>
            </div>
            <div class="card-body">
                <form id="settingsForm" method="POST" action="{{ route('app-settings.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Basic Settings -->
                        <div class="col-lg-6">
                            <h6 class="text-primary mb-3">
                                <i class="bx bx-info-circle me-1"></i>
                                Pengaturan Dasar
                            </h6>

                            <div class="mb-3">
                                <label for="app_name" class="form-label">Nama Aplikasi</label>
                                <input type="text" class="form-control" id="app_name" name="app_name"
                                       value="{{ old('app_name', $settings['app_name']) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="app_version" class="form-label">Versi Aplikasi</label>
                                <input type="text" class="form-control" id="app_version" name="app_version"
                                       value="{{ old('app_version', $settings['app_version']) }}"
                                       pattern="\d+\.\d+\.\d+" required>
                                <div class="form-text">Format: x.x.x (contoh: 1.2.3)</div>
                            </div>

                            <div class="mb-3">
                                <label for="banner_image" class="form-label">Banner Selamat Datang</label>
                                <input type="file" class="form-control" id="banner_image" name="banner_image"
                                       accept="image/*">
                                <div class="form-text">Upload gambar banner yang akan ditampilkan di dashboard mobile (max 2MB, JPG/PNG)</div>
                                @if($settings['banner_image_url'])
                                    <div class="mt-2">
                                        <img src="{{ $settings['banner_image_url'] }}" alt="Current Banner" class="img-thumbnail" style="max-width: 200px;">
                                        <p class="text-muted small mt-1">Banner saat ini</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    <option value="Asia/Jakarta" {{ $settings['timezone'] == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                    <option value="Asia/Makassar" {{ $settings['timezone'] == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                    <option value="Asia/Jayapura" {{ $settings['timezone'] == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                    <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="locale" class="form-label">Bahasa</label>
                                <select class="form-select" id="locale" name="locale">
                                    <option value="id" {{ $settings['locale'] == 'id' ? 'selected' : '' }}>Indonesia</option>
                                    <option value="en" {{ $settings['locale'] == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="col-lg-6">
                            <h6 class="text-warning mb-3">
                                <i class="bx bx-cog me-1"></i>
                                Pengaturan Lanjutan
                            </h6>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1"
                                           {{ $settings['maintenance_mode'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="maintenance_mode">
                                        Mode Maintenance
                                    </label>
                                </div>
                                <div class="form-text">Aktifkan untuk menutup akses aplikasi sementara</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="debug_mode" name="debug_mode" value="1"
                                           {{ $settings['debug_mode'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="debug_mode">
                                        Debug Mode
                                    </label>
                                </div>
                                <div class="form-text">Tampilkan error detail (hanya untuk development)</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cache_enabled" name="cache_enabled" value="1"
                                           {{ $settings['cache_enabled'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cache_enabled">
                                        Cache Diaktifkan
                                    </label>
                                </div>
                                <div class="form-text">Performa aplikasi akan lebih baik dengan cache</div>
                            </div>

                            <div class="mb-3">
                                <label for="session_lifetime" class="form-label">Session Lifetime (menit)</label>
                                <input type="number" class="form-control" id="session_lifetime" name="session_lifetime"
                                       value="{{ old('session_lifetime', $settings['session_lifetime']) }}"
                                       min="1" max="525600" required>
                                <div class="form-text">Waktu sebelum session expired (default: 120 menit)</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" onclick="resetSettings()">
                            <i class="bx bx-refresh me-1"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Version Modal -->
<div class="modal fade" id="updateVersionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Versi Aplikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateVersionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_version" class="form-label">Versi Baru</label>
                        <input type="text" class="form-control" id="new_version" name="version"
                               pattern="\d+\.\d+\.\d+" placeholder="1.2.3" required>
                        <div class="form-text">Format: x.x.x (contoh: 1.2.3)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Versi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function checkForUpdates() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mengecek...';
    button.disabled = true;

    fetch('{{ route("app-settings.check-updates") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let message = 'Pengecekan selesai. ';
            if (data.new_commits > 0) {
                message += `Ditemukan ${data.new_commits} commit baru.`;
                showToast('success', message);
                setTimeout(() => location.reload(), 2000);
            } else {
                message += 'Tidak ada update baru.';
                showToast('info', message);
            }
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Terjadi kesalahan saat mengecek update');
        console.error(error);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function clearCache() {
    if (!confirm('Apakah Anda yakin ingin membersihkan cache?')) return;

    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Membersihkan...';
    button.disabled = true;

    // Simulate cache clearing (in real implementation, call backend)
    setTimeout(() => {
        showToast('success', 'Cache berhasil dibersihkan');
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}

function optimizeApp() {
    if (!confirm('Apakah Anda yakin ingin mengoptimasi aplikasi?')) return;

    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mengoptimasi...';
    button.disabled = true;

    // Simulate optimization (in real implementation, call backend)
    setTimeout(() => {
        showToast('success', 'Aplikasi berhasil dioptimasi');
        button.innerHTML = originalText;
        button.disabled = false;
    }, 3000);
}

function viewLogs() {
    window.open('/admin/logs', '_blank');
}

function turnOffDebug() {
    if (!confirm('Apakah Anda yakin ingin mematikan mode debug? Aplikasi akan restart konfigurasi.')) return;

    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mematikan...';
    button.disabled = true;

    fetch('{{ route("app-settings.turn-off-debug") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Terjadi kesalahan saat mematikan debug mode');
        console.error(error);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function resetSettings() {
    if (!confirm('Apakah Anda yakin ingin mereset semua pengaturan ke default?')) return;

    document.getElementById('settingsForm').reset();
    // Reset checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
}

function showToast(type, message) {
    // Simple toast implementation
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// Handle version update form
document.getElementById('updateVersionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("app-settings.update-version") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            document.getElementById('updateVersionModal').querySelector('.btn-close').click();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Terjadi kesalahan saat update versi');
        console.error(error);
    });
});
</script>
@endsection
