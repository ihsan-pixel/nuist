@extends('layouts.mobile')

@section('title', 'Monitoring Presensi')
@section('subtitle', 'Pantau Kehadiran Tenaga Pendidik')

@section('content')
<div class="container py-3" style="max-width:420px;margin:auto;">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Monitoring Presensi</h6>
        <form method="GET" action="{{ route('mobile.monitor-presensi') }}">
            <input type="date" name="date" value="{{ $selectedDate->toDateString() }}" onchange="this.form.submit()" class="form-control form-control-sm">
        </form>
    </div>

    <div style="background:#fff;padding:12px;border-radius:8px;margin-bottom:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <h6 class="mb-2">Sudah Presensi ({{ $presensis->total() }})</h6>
        @if($presensis->isEmpty())
            <p class="text-muted">Belum ada presensi untuk tanggal ini.</p>
        @else
            <ul style="list-style:none;padding:0;margin:0;">
                @foreach($presensis as $p)
                    <li style="padding:8px 0;border-bottom:1px solid #f1f1f1;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;">{{ $p->user->name ?? '-' }}</div>
                                <small class="text-muted">{{ $p->user->statusKepegawaian?->name ?? '' }}</small>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-weight:600;">{{ $p->waktu_masuk?->format('H:i') ?? '-' }}</div>
                                @if($p->waktu_keluar)
                                    <small class="text-muted">Keluar {{ $p->waktu_keluar->format('H:i') }}</small>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-2">
                {{ $presensis->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <div style="background:#fff;padding:12px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <h6 class="mb-2">Belum Presensi ({{ $belumPresensi->total() }})</h6>
        @if($belumPresensi->isEmpty())
            <p class="text-muted">Semua tenaga pendidik telah melakukan presensi.</p>
        @else
            <ul style="list-style:none;padding:0;margin:0;">
                @foreach($belumPresensi as $u)
                    <li style="padding:8px 0;border-bottom:1px solid #f1f1f1;display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-weight:600;">{{ $u->name }}</div>
                            <small class="text-muted">{{ $u->statusKepegawaian?->name ?? '' }}</small>
                        </div>
                        <div>
                            <small class="text-muted">{{ $u->nuist_id ?? '' }}</small>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-2">
                {{ $belumPresensi->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
