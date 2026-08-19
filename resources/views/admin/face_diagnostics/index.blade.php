@extends('layouts.master')

@section('title', 'Face Diagnostics')

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .diag-hero {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        color: #fff;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 24px rgba(0, 75, 76, 0.16);
    }

    .diag-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
    }

    .diag-stat {
        border-radius: 16px;
        color: #fff;
        padding: 18px;
        height: 100%;
    }

    .diag-stat small {
        display: block;
        opacity: 0.85;
    }

    .diag-badge {
        font-size: 11px;
        padding: 0.35rem 0.55rem;
        border-radius: 999px;
    }

    .mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        font-size: 12px;
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="diag-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="mb-1">Face Diagnostics</h4>
                <div class="opacity-75">Riwayat sukses, gagal, dan stuck untuk enrollment dan presensi wajah.</div>
            </div>
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari user / device / alasan">
                </div>
                <div class="col-auto">
                    <select name="source" class="form-select form-select-sm">
                        <option value="">Semua sumber</option>
                        <option value="presensi" @selected(request('source') === 'presensi')>Presensi</option>
                        <option value="enrollment" @selected(request('source') === 'enrollment')>Enrollment</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="outcome" class="form-select form-select-sm">
                        <option value="">Semua hasil</option>
                        <option value="success" @selected(request('outcome') === 'success')>Success</option>
                        <option value="failed" @selected(request('outcome') === 'failed')>Failed</option>
                        <option value="stuck" @selected(request('outcome') === 'stuck')>Stuck</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-light btn-sm">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #334155 0%, #1e293b 100%);">
                <small>Total</small>
                <h3 class="mb-0">{{ number_format($summary['total']) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #0e8549 0%, #22c55e 100%);">
                <small>Success</small>
                <h3 class="mb-0">{{ number_format($summary['success']) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);">
                <small>Failed</small>
                <h3 class="mb-0">{{ number_format($summary['failed']) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="diag-stat" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                <small>Stuck</small>
                <h3 class="mb-0">{{ number_format($summary['stuck']) }}</h3>
            </div>
        </div>
    </div>

    <div class="card diag-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Sumber</th>
                            <th>Hasil</th>
                            <th>Tahap</th>
                            <th>Alasan</th>
                            <th>Device / Browser</th>
                            <th>GPU / WebGL / TF</th>
                            <th>Video / Camera</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($diagnostics as $item)
                            <tr>
                                <td class="mono">{{ $item->created_at?->format('d M Y H:i:s') }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->user?->name ?? 'Unknown' }}</div>
                                    <div class="text-muted small">ID: {{ $item->user_id ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary diag-badge text-uppercase">{{ $item->source }}</span>
                                </td>
                                <td>
                                    @php
                                        $badge = match ($item->outcome) {
                                            'success' => 'bg-success',
                                            'failed' => 'bg-danger',
                                            'stuck' => 'bg-warning text-dark',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }} diag-badge text-uppercase">{{ $item->outcome }}</span>
                                </td>
                                <td class="mono">{{ $item->stage ?? '-' }}</td>
                                <td style="max-width: 260px;">
                                    <div class="small">{{ $item->reason ?? '-' }}</div>
                                </td>
                                <td style="min-width: 220px;">
                                    <div class="small fw-semibold">{{ $item->device ?? '-' }}</div>
                                    <div class="text-muted mono">{{ $item->browser ?? '-' }}</div>
                                </td>
                                <td style="min-width: 220px;">
                                    <div class="mono">GPU: {{ $item->gpu ?? '-' }}</div>
                                    <div class="mono">WEBGL: {{ $item->webgl ?? '-' }}</div>
                                    <div class="mono">TF: {{ $item->tf_backend ?? '-' }}</div>
                                </td>
                                <td style="min-width: 200px;">
                                    <div class="mono">VIDEO: {{ $item->video_size ?? '-' }}</div>
                                    <div class="mono">READY: {{ $item->ready_state ?? '-' }}</div>
                                    <div class="mono">CAMERA: {{ $item->camera_state ?? '-' }}</div>
                                </td>
                            </tr>
                            @if (!empty($item->details))
                                <tr class="table-light">
                                    <td colspan="9">
                                        <div class="mono text-muted">
                                            {{ \Illuminate\Support\Str::limit(json_encode($item->details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 320) }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">Belum ada data diagnostik.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $diagnostics->links() }}
        </div>
    </div>
</div>
@endsection
