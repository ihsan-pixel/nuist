@extends('layouts.mobile')

@section('title', 'Approval Event')
@section('subtitle', 'Persetujuan Kalender Akademik')

@section('content')
<div class="container py-3" style="max-width: 720px; margin: auto;">
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
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                            <div>
                                <h6 class="mb-1">{{ $event->name }}</h6>
                                <div class="text-muted small">{{ $event->resolved_type_label }}</div>
                            </div>
                            <span class="badge {{ $badgeClass }}">{{ $event->approval_status_label }}</span>
                        </div>

                        <div class="small text-muted mb-1">
                            <i class="bx bx-calendar me-1"></i>{{ $event->date_range_label }}
                        </div>
                        <div class="small text-muted mb-1">
                            <i class="bx bx-time me-1"></i>{{ $event->time_range_label }}
                        </div>
                        <div class="small text-muted mb-1">
                            <i class="bx bx-user me-1"></i>Pengaju: {{ $event->creator->name ?? '-' }}
                        </div>
                        @if($event->description)
                            <div class="small text-muted mb-1">
                                <i class="bx bx-note me-1"></i>{{ $event->description }}
                            </div>
                        @endif
                        @if($event->approval_notes)
                            <div class="small text-muted mb-2">
                                <i class="bx bx-message-detail me-1"></i>Catatan: {{ $event->approval_notes }}
                            </div>
                        @endif
                        @if($event->approver)
                            <div class="small text-muted mb-2">
                                <i class="bx bx-check-shield me-1"></i>{{ $event->approver->name }}
                                @if($event->approved_at)
                                    pada {{ optional($event->approved_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                @endif
                            </div>
                        @endif

                        @if($event->approval_status === \App\Models\AcademicCalendarEvent::APPROVAL_PENDING)
                            <div class="alert alert-warning border-0 small mb-3">
                                Setelah disetujui, semua jadwal mengajar pada tanggal event ini akan berstatus izin dan guru tidak dapat melakukan presensi mengajar pada tanggal tersebut.
                            </div>

                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <form method="POST" action="{{ route('mobile.academic-calendar-approvals.approve', $event) }}">
                                        @csrf
                                        <textarea name="approval_notes" class="form-control form-control-sm mb-2" rows="2" placeholder="Catatan persetujuan (opsional)"></textarea>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bx bx-check-circle me-1"></i>Setujui Event
                                        </button>
                                    </form>
                                </div>
                                <div class="col-12 col-md-6">
                                    <form method="POST" action="{{ route('mobile.academic-calendar-approvals.reject', $event) }}">
                                        @csrf
                                        <textarea name="approval_notes" class="form-control form-control-sm mb-2" rows="2" placeholder="Alasan penolakan (opsional)"></textarea>
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            <i class="bx bx-x-circle me-1"></i>Tolak Event
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
