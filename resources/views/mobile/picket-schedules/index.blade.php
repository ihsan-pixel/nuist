@extends('layouts.mobile')

@section('title', 'Jadwal Piket')
@section('subtitle', 'Pengajuan Izin Jadwal Piket')

@section('content')
<div class="container py-3" style="max-width: 720px; margin: auto;">
    <style>
        .picket-card {
            border-radius: 16px;
            overflow: hidden;
        }

        .picket-date-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            max-height: 320px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .picket-date-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #edf0f2;
        }

        .picket-date-item.disabled {
            opacity: 0.6;
            background: #f1f3f5;
        }

        .picket-date-label {
            font-size: 12px;
            color: #495057;
            line-height: 1.35;
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
            <small class="text-muted">Pilih hari piket Anda selama periode libur semester.</small>
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
                    $selectedDates = collect($submission?->selected_dates ?? []);
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
                                <span class="badge bg-light text-dark border">Belum diajukan</span>
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

                        @if($submission && $submission->approval_status === \App\Models\PicketScheduleSubmission::APPROVAL_APPROVED)
                            <div class="mb-2 fw-semibold" style="font-size: 13px;">Hari piket yang disetujui</div>
                            <div class="selected-list">
                                @foreach($submission->selected_date_labels as $label)
                                    <div class="selected-list-item">{{ $label }}</div>
                                @endforeach
                            </div>
                        @else
                            <form method="POST" action="{{ route('mobile.picket-schedules.submit', $period) }}">
                                @csrf
                                <div class="picket-helper mb-2">
                                    Centang tanggal yang menjadi jadwal piket Anda. Hari Minggu tidak dapat dipilih.
                                </div>
                                <div class="picket-date-grid mb-3">
                                    @foreach($period->date_choices as $choice)
                                        <label class="picket-date-item {{ $choice['is_sunday'] ? 'disabled' : '' }}">
                                            <input
                                                type="checkbox"
                                                name="selected_dates[]"
                                                value="{{ $choice['date'] }}"
                                                @checked($selectedDates->contains($choice['date']))
                                                @disabled($choice['is_sunday'])
                                            >
                                            <div class="picket-date-label">
                                                {{ $choice['label'] }}
                                                @if($choice['is_sunday'])
                                                    <div class="text-danger small">Hari Minggu</div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bx bx-send me-1"></i>{{ $submission ? 'Perbarui Pengajuan' : 'Ajukan Jadwal Piket' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
