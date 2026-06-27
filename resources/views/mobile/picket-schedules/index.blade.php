@extends('layouts.mobile')

@section('title', 'Jadwal Piket')
@section('subtitle', 'Status Izin Jadwal Piket')

@section('content')
<div class="container py-3" style="max-width: 720px; margin: auto;">
    <style>
        .picket-card {
            border-radius: 16px;
            overflow: hidden;
        }

        .picket-helper {
            font-size: 12px;
            color: #6c757d;
        }

        .selected-list {
            display: grid;
            gap: 6px;
        }

        .selected-list-item {
            font-size: 12px;
            color: #495057;
            padding: 9px 11px;
            border-radius: 10px;
            background: #f8f9fa;
        }
    </style>

    <div class="d-flex align-items-center mb-3">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <div>
            <div class="fw-bold" style="color: #004b4c; font-size: 16px;">Izin Jadwal Piket</div>
            <small class="text-muted">Jadwal piket disusun oleh admin sekolah dan menunggu approval kepala sekolah.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    @if($periods->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bx bx-calendar-x fs-1 text-muted"></i>
                <h6 class="mt-3 mb-1">Belum ada periode aktif</h6>
                <small class="text-muted">Admin sekolah belum membuka periode pengajuan jadwal piket.</small>
            </div>
        </div>
    @else
        <div class="d-grid gap-3">
            @foreach($periods as $period)
                @php
                    $submission = $period->getRelation('currentSubmission');
                    $badgeClass = match ($submission?->approval_status) {
                        \App\Models\PicketScheduleSubmission::APPROVAL_APPROVED => 'bg-success',
                        \App\Models\PicketScheduleSubmission::APPROVAL_REJECTED => 'bg-danger',
                        \App\Models\PicketScheduleSubmission::APPROVAL_PENDING => 'bg-warning text-dark',
                        default => 'bg-secondary',
                    };
                @endphp
                <div class="card border-0 shadow-sm picket-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="pe-2">
                                <h6 class="mb-1" style="font-size: 14px;">{{ $period->name }}</h6>
                                <div class="text-muted small">{{ $period->school->name ?? '-' }}</div>
                            </div>
                            @if($submission)
                                <span class="badge {{ $badgeClass }}">{{ $submission->approval_status_label }}</span>
                            @else
                                <span class="badge bg-light text-dark border">Belum disusun admin</span>
                            @endif
                        </div>

                        <div class="small text-muted mb-2">
                            <i class="bx bx-calendar me-1"></i>{{ $period->date_range_label }}
                        </div>

                        @if($period->description)
                            <div class="picket-helper mb-3">{{ $period->description }}</div>
                        @endif

                        @if($submission && $submission->approval_notes)
                            <div class="alert alert-light border mb-3" style="font-size: 12px;">
                                <strong>Catatan approval:</strong><br>
                                {{ $submission->approval_notes }}
                            </div>
                        @endif

                        @if($submission)
                            <div class="mb-2 fw-semibold" style="font-size: 13px;">
                                {{ $submission->approval_status === \App\Models\PicketScheduleSubmission::APPROVAL_APPROVED ? 'Hari piket yang disetujui' : 'Hari piket yang diajukan admin' }}
                            </div>
                            <div class="selected-list">
                                @foreach($submission->selected_date_labels as $label)
                                    <div class="selected-list-item">{{ $label }}</div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light border mb-0" style="font-size: 12px;">
                                Admin sekolah belum menyusun jadwal piket Anda pada periode ini.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
