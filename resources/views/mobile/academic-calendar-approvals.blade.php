@extends('layouts.mobile')

@section('title', 'Approval Event')
@section('subtitle', 'Persetujuan Kalender Akademik')

@section('content')
<div class="container py-3" style="max-width: 720px; margin: auto;">
    <style>
        .approval-card {
            border-radius: 16px;
            overflow: hidden;
        }

        .approval-card .card-body {
            padding: 14px;
        }

        .approval-meta {
            display: grid;
            gap: 6px;
        }

        .approval-meta-item {
            font-size: 12px;
            color: #6c757d;
            line-height: 1.35;
        }

        .approval-badge {
            font-size: 10px;
            padding: 6px 9px;
            border-radius: 999px;
            white-space: normal;
            text-align: center;
            max-width: 140px;
        }

        .approval-note {
            font-size: 12px;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        .approval-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .approval-textarea {
            min-height: 68px;
            resize: vertical;
            font-size: 12px;
            margin-bottom: 8px;
        }

        @media (max-width: 576px) {
            .approval-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="d-flex align-items-center mb-3">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <div>
            <div class="fw-bold" style="color: #004b4c; font-size: 16px;">Approval Kegiatan</div>
            <small class="text-muted">{{ $school->name }}</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    @if($events->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bx bx-calendar-x fs-1 text-muted"></i>
                <h6 class="mt-3 mb-1">Belum ada event akademik</h6>
                <small class="text-muted">Admin sekolah belum mengajukan event untuk disetujui.</small>
            </div>
        </div>
    @else
        <div class="d-grid gap-3">
            @foreach($events as $event)
                @php
                    $badgeClass = match ($event->approval_status) {
                        \App\Models\AcademicCalendarEvent::APPROVAL_APPROVED => 'bg-success',
                        \App\Models\AcademicCalendarEvent::APPROVAL_REJECTED => 'bg-danger',
                        default => 'bg-warning text-dark',
                    };
                @endphp
                <div class="card border-0 shadow-sm approval-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="pe-2">
                                <h6 class="mb-1" style="font-size: 14px;">{{ $event->name }}</h6>
                                <div class="text-muted small">{{ $event->resolved_type_label }}</div>
                            </div>
                            <span class="badge {{ $badgeClass }} approval-badge">{{ $event->approval_status_label }}</span>
                        </div>

                        <div class="approval-meta mb-2">
                            <div class="approval-meta-item">
                                <i class="bx bx-calendar me-1"></i>{{ $event->date_range_label }}
                            </div>
                            <div class="approval-meta-item">
                                <i class="bx bx-time me-1"></i>{{ $event->time_range_label }}
                            </div>
                            <div class="approval-meta-item">
                                <i class="bx bx-user me-1"></i>{{ $event->creator->name ?? '-' }}
                                <span class="mx-1">•</span>
                                diperbarui {{ optional($event->updated_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                            </div>
                        </div>

                        @if($event->description)
                            <div class="approval-meta-item mb-2">
                                <i class="bx bx-note me-1"></i>{{ $event->description }}
                            </div>
                        @endif
                        @if($event->approval_notes)
                            <div class="approval-meta-item mb-2">
                                <i class="bx bx-message-detail me-1"></i>{{ $event->approval_notes }}
                            </div>
                        @endif
                        @if($event->approver)
                            <div class="approval-meta-item mb-2">
                                <i class="bx bx-check-shield me-1"></i>{{ $event->approver->name }}
                                @if($event->approved_at)
                                    pada {{ optional($event->approved_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                @endif
                            </div>
                        @endif

                        @if($event->approval_status === \App\Models\AcademicCalendarEvent::APPROVAL_PENDING)
                            <div class="alert alert-warning border-0 approval-note">
                                Setelah disetujui, semua jadwal mengajar pada tanggal event ini akan berstatus izin.
                            </div>

                            <form method="POST" action="{{ route('mobile.academic-calendar-approvals.approve', $event) }}" id="approve-form-{{ $event->id }}">
                                @csrf
                                <textarea name="approval_notes" class="form-control form-control-sm approval-textarea" placeholder="Catatan approval atau penolakan (opsional)"></textarea>
                                <div class="approval-actions">
                                    <button type="submit" class="btn btn-success">
                                            <i class="bx bx-check-circle me-1"></i>Setujui Event
                                    </button>
                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger"
                                        formaction="{{ route('mobile.academic-calendar-approvals.reject', $event) }}"
                                        formmethod="POST"
                                    >
                                            <i class="bx bx-x-circle me-1"></i>Tolak Event
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
