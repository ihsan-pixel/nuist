@extends('layouts.master')

@section('title') Riwayat Pengembangan @endsection

@section('css')
<style>
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline:before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 40px;
    width: 2px;
    margin-left: -1.5px;
    content: '';
    background-color: #e9ecef;
}

.timeline > li {
    position: relative;
    margin-bottom: 50px;
    min-height: 50px;
}

.timeline > li:before,
.timeline > li:after {
    content: ' ';
    display: table;
}

.timeline > li:after {
    clear: both;
}

.timeline > li .timeline-panel {
    position: relative;
    float: right;
    width: calc(100% - 90px);
    padding: 20px;
    border: 1px solid #d4edda;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
}

.timeline > li .timeline-panel:before {
    position: absolute;
    top: 26px;
    right: -15px;
    display: inline-block;
    border-top: 15px solid transparent;
    border-left: 15px solid #ccc;
    border-right: 0 solid #ccc;
    border-bottom: 15px solid transparent;
    content: ' ';
}

.timeline > li .timeline-panel:after {
    position: absolute;
    top: 27px;
    right: -14px;
    display: inline-block;
    border-top: 14px solid transparent;
    border-left: 14px solid #fff;
    border-right: 0 solid #fff;
    border-bottom: 14px solid transparent;
    content: ' ';
}

.timeline > li .timeline-badge {
    position: absolute;
    top: 16px;
    left: 28px;
    z-index: 100;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    text-align: center;
    font-size: 1.4em;
    line-height: 25px;
    color: #fff;
}

.timeline-title {
    margin-top: 0;
    color: inherit;
    font-size: 1.1em;
    font-weight: 600;
}

.timeline-body p {
    margin-bottom: 0;
}

.timeline-body p + p {
    margin-top: 5px;
}

.stats-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.filter-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
}

.filter-card .card-body {
    padding: 2rem;
}

.action-buttons {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.btn-group-custom .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.export-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.export-section .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    border: 1px solid rgba(255,255,255,0.3);
}

.export-section .btn:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
}

.loading-content {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    text-align: center;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

@media (max-width: 767px) {
    .timeline > li .timeline-panel {
        width: calc(100% - 70px);
    }

    .timeline:before {
        left: 30px;
    }

    .timeline > li .timeline-badge {
        left: 18px;
    }

    .btn-group-custom .btn {
        width: 100%;
        margin-right: 0;
    }

    .export-section .btn {
        width: 100%;
        margin-right: 0;
    }
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Riwayat Pengembangan @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-history me-2"></i>
                    Riwayat Pengembangan Aplikasi
                </h4>
                <p class="text-white-50 mb-0">
                    Timeline lengkap perkembangan dan update aplikasi dari awal instalasi hingga sekarang
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary rounded-circle fs-3">
                            <i class="bx bx-data"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total</p>
                        <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info rounded-circle fs-3">
                            <i class="bx bx-git-commit"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Git Commits</p>
                        <h5 class="mb-0">{{ $stats['commits'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-secondary rounded-circle fs-3">
                            <i class="bx bx-edit"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Manual Entry</p>
                        <h5 class="mb-0">{{ $stats['manual_entries'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-3">
                            <i class="bx bx-plus-circle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Fitur</p>
                        <h5 class="mb-0">{{ $stats['features'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning rounded-circle fs-3">
                            <i class="bx bx-wrench"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Bug Fix</p>
                        <h5 class="mb-0">{{ $stats['bugfixes'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary rounded-circle fs-3">
                            <i class="bx bx-database"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Migration</p>
                        <h5 class="mb-0">{{ $stats['migrations'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('development-history.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="type" class="form-label">Tipe</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Semua Tipe</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="source" class="form-label">Sumber</label>
                            <select class="form-select" id="source" name="source">
                                @foreach($sources as $key => $label)
                                    <option value="{{ $key }}" {{ request('source') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('development-history.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                                @if(auth()->user()->role === 'super_admin')
                                <a href="#" class="btn btn-success" onclick="runCommitTracking()">
                                    <i class="bx bx-git-commit me-1"></i> Track Commits
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="action-buttons">
            <h5 class="mb-3">
                <i class="bx bx-cog me-2"></i>Aksi Pengembangan
            </h5>
            <div class="btn-group-custom">
                <a href="{{ route('development-history.sync') }}" class="btn btn-success" onclick="return confirm('Sinkronisasi file migration dengan riwayat pengembangan?')">
                    <i class="bx bx-sync me-1"></i> Sinkronisasi Migration
                </a>
                @if(auth()->user()->role === 'super_admin')
                <button type="button" class="btn btn-warning" onclick="regenerateDocumentation()">
                    <i class="bx bx-refresh me-1"></i> Regenerasi Dokumentasi
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Export Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="export-section">
            <h5 class="mb-3">
                <i class="bx bx-download me-2"></i>Export Riwayat Pengembangan
            </h5>
            <p class="mb-3 opacity-75">Unduh riwayat pengembangan dalam berbagai format</p>
            <div class="btn-group-custom">
                <a href="{{ route('development-history.export', ['format' => 'txt']) . '?' . request()->getQueryString() }}" class="btn btn-light">
                    <i class="bx bx-file me-1"></i> TXT
                </a>
                <a href="{{ route('development-history.export', ['format' => 'md']) . '?' . request()->getQueryString() }}" class="btn btn-light">
                    <i class="bx bx-file-blank me-1"></i> Markdown
                </a>
                <a href="{{ route('development-history.export', ['format' => 'pdf']) . '?' . request()->getQueryString() }}" class="btn btn-light">
                    <i class="bx bx-file-pdf me-1"></i> PDF
                </a>
                <a href="{{ route('development-history.export', ['format' => 'excel']) . '?' . request()->getQueryString() }}" class="btn btn-light">
                    <i class="bx bx-spreadsheet me-1"></i> Excel
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($histories->count() > 0)
                    <ul class="timeline">
                        @foreach($histories as $history)
                        <li>
                            <div class="timeline-badge {{ $history->getTypeBadgeClass() }}">
                                @if($history->details && isset($history->details['commit_hash']))
                                    <i class="bx bx-git-commit"></i>
                                @else
                                    <i class="bx {{ $history->getTypeIcon() }}"></i>
                                @endif
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4 class="timeline-title">
                                        {{ $history->title }}
                                        @if($history->details && isset($history->details['commit_hash']))
                                            <i class="bx bx-git-commit text-info ms-2" title="Git Commit"></i>
                                        @endif
                                    </h4>
                                    <p class="text-muted">
                                        <small>
                                            <i class="bx bx-calendar me-1"></i>
                                            {{ $history->formatted_date }}
                                            <span class="text-primary ms-2">({{ $history->relative_date }})</span>
                                            @if($history->version)
                                                <span class="badge bg-info ms-2">v{{ $history->version }}</span>
                                            @endif
                                            <span class="badge {{ $history->getTypeBadgeClass() }} ms-2">
                                                {{ $types[$history->type] ?? $history->type }}
                                            </span>
                                            @if($history->details && isset($history->details['commit_hash']))
                                                <span class="badge bg-success ms-2">
                                                    <i class="bx bx-git-commit me-1"></i>Git Commit
                                                </span>
                                            @endif
                                        </small>
                                    </p>
                                </div>
                                <div class="timeline-body">
                                    <p>{{ $history->description }}</p>
                                    @if($history->migration_file)
                                        <p class="text-muted mb-0">
                                            <small>
                                                <i class="bx bx-file me-1"></i>
                                                Migration: {{ $history->migration_file }}
                                            </small>
                                        </p>
                                    @endif
                                    @if($history->details && isset($history->details['commit_hash']))
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bx bx-hash me-1"></i>
                                                Commit: <code>{{ substr($history->details['commit_hash'], 0, 7) }}</code>
                                                @if(isset($history->details['commit_author']))
                                                    | Author: {{ $history->details['commit_author'] }}
                                                @endif
                                                @if(isset($history->details['webhook_processed']) && $history->details['webhook_processed'])
                                                    <span class="badge bg-primary ms-2">Auto-tracked</span>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $histories->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-light text-primary rounded-circle fs-1">
                                <i class="bx bx-history"></i>
                            </div>
                        </div>
                        <h5>Belum Ada Riwayat Pengembangan</h5>
                        <p class="text-muted">Klik tombol "Sinkronisasi Migration" untuk memuat riwayat dari file migration.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    // Auto-submit form when filter changes
    document.getElementById('type').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('source').addEventListener('change', function() {
        this.form.submit();
    });

    // Function to run commit tracking
    function runCommitTracking() {
        if (!confirm('Jalankan tracking commit Git? Proses ini akan memakan waktu beberapa saat.')) {
            return;
        }

        // Show loading
        const button = event.target.closest('a');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Processing...';
        button.classList.add('disabled');

        // Make AJAX request to run the command
        fetch('/admin/run-commit-tracking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Commit tracking berhasil! ' + data.message, 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan saat menjalankan commit tracking', 'error');
            console.error(error);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.classList.remove('disabled');
        });
    }

    // Function to regenerate documentation
    function regenerateDocumentation() {
        if (!confirm('Regenerasi file dokumentasi riwayat pengembangan?')) {
            return;
        }

        // Show loading
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Processing...';
        button.disabled = true;

        // Make AJAX request to regenerate documentation
        fetch('/admin/regenerate-documentation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Dokumentasi berhasil diregenerasi!', 'success');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan saat meregenerasi dokumentasi', 'error');
            console.error(error);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 10000; min-width: 300px;';
        notification.innerHTML = `
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'error-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Add loading overlay to page
    document.addEventListener('DOMContentLoaded', function() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'loading-overlay';
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.innerHTML = `
            <div class="loading-content">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mt-3">Memproses...</h5>
                <p class="text-muted">Mohon tunggu sebentar</p>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
    });

    // Show loading overlay
    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    // Hide loading overlay
    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }
</script>
@endsection

