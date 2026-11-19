@extends('layouts.master')

@section('title', 'Pengajuan Tenaga Pendidik')

@section('content')
@section('css')
<style>
    .tp-page{--tp-green:#08745a;--tp-ink:#192d37;--tp-muted:#6c7c86;position:relative;padding:8px 0 24px}
    body:has(.tp-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.tp-page) .main-content{background:transparent!important}
    .tp-page .tp-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden}
    .tp-page .tp-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid #e5edef}
    .tp-page .tp-eyebrow{margin:0 0 4px;color:var(--tp-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.tp-page .tp-title{margin:0;color:var(--tp-ink);font-size:22px;font-weight:700}.tp-page .tp-subtitle{margin:5px 0 0;color:var(--tp-muted);font-size:12px}
    .tp-page .tp-content{padding:20px 24px 24px}.tp-page .tp-stat{border:1px solid #dce7e9;border-radius:14px;background:rgba(255,255,255,.58);box-shadow:0 5px 14px rgba(32,70,78,.05)}
    .tp-page .tp-filter,.tp-page .tp-table-panel{border:1px solid #e5edef;border-radius:16px;background:rgba(255,255,255,.58)}.tp-page .tp-table-panel{overflow-x:auto}
    .tp-page table thead th{border:0;border-bottom:1px solid #e5edef;background:#f1f6f6;color:var(--tp-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.tp-page table tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle}.tp-page .btn{border-radius:10px}.tp-page .tp-toolbar .btn{border-radius:12px}.tp-page .pagination{margin:16px 0 0}
    @media(max-width:767.98px){.tp-page .tp-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.tp-page .tp-content{padding:14px}.tp-page .tp-toolbar .btn{width:100%}}
</style>
@endsection
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Pengajuan Tenaga Pendidik @endslot
@endcomponent

<div class="row tp-page">
    <div class="col-12">
        <div class="card tp-shell">
            <div class="card-header tp-toolbar">
                <div><p class="tp-eyebrow">MASTER DATA</p><h4 class="tp-title">Pengajuan Tenaga Pendidik</h4><p class="tp-subtitle">Pantau proses pengajuan, review, dan persetujuan akun tenaga pendidik.</p></div>
                <a href="{{ route('tenaga-pendidik.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Data Tenaga Pendidik</a>
            </div>
            <div class="card-body tp-content">
                <div class="row g-3 mb-4">
                    @foreach([['menunggu_review','Menunggu Review','info'],['perlu_perbaikan','Perlu Perbaikan','warning'],['disetujui','Disetujui','success']] as [$key,$label,$color])
                        <div class="col-md-4"><div class="tp-stat p-3 h-100"><div class="text-muted small">{{ $label }}</div><div class="fs-3 fw-semibold text-{{ $color }}">{{ $counts[$key] ?? 0 }}</div></div></div>
                    @endforeach
                </div>

                <form method="GET" class="row g-2 align-items-end mb-4 p-3 tp-filter">
                    <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="">Semua status</option>@foreach(['menunggu_review'=>'Menunggu Review','perlu_perbaikan'=>'Perlu Perbaikan','disetujui'=>'Disetujui'] as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select></div>
                    @if($isSuperAdmin)<div class="col-md-5"><label class="form-label">Madrasah</label><select name="madrasah_id" class="form-select"><option value="">Semua madrasah</option>@foreach($madrasahs as $madrasah)<option value="{{ $madrasah->id }}" @selected((string) request('madrasah_id') === (string) $madrasah->id)>{{ $madrasah->name }}</option>@endforeach</select></div>@endif
                    <div class="col-md-auto"><button class="btn btn-primary"><i class="bx bx-filter-alt me-1"></i> Filter</button></div>
                </form>

                <div class="tp-table-panel"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Nama / Email</th><th>Madrasah</th><th>Pengaju</th><th>Status</th><th>Catatan Review</th><th>Diperbarui</th><th>Aksi</th></tr></thead><tbody>
                    @forelse($submissions as $submission)
                        @php($payload = $submission->payload ?? [])
                        <tr><td><strong>{{ $payload['nama'] ?? '-' }}</strong><br><small class="text-muted">{{ $payload['email'] ?? '-' }}</small></td><td>{{ optional($submission->madrasah)->name ?: '-' }}</td><td>{{ optional($submission->submitter)->name ?: '-' }}</td><td><span class="badge bg-{{ $submission->status === 'disetujui' ? 'success' : ($submission->status === 'perlu_perbaikan' ? 'warning text-dark' : 'info') }}">{{ str_replace('_', ' ', ucfirst($submission->status)) }}</span></td><td class="small">{{ $submission->review_note ?: '-' }}</td><td>{{ $submission->updated_at?->format('d/m/Y H:i') }}</td><td class="text-nowrap">
                            @if($isSuperAdmin && $submission->status !== 'disetujui')<form method="POST" action="{{ route('tenaga-pendidik.submissions.approve', $submission) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success" onclick="return confirm('Approve pengajuan ini?')"><i class="bx bx-check"></i></button></form><button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reject{{ $submission->id }}"><i class="bx bx-x"></i></button>@elseif(!$isSuperAdmin && $submission->status === 'perlu_perbaikan')<form method="POST" action="{{ route('tenaga-pendidik.submissions.resubmit', $submission) }}" class="d-inline">@csrf<input type="hidden" name="review_note" value="Perbaikan telah dilakukan dan data diajukan kembali."><button class="btn btn-sm btn-primary">Ajukan Ulang</button></form>@endif
                        </td></tr>
                        @if($isSuperAdmin && $submission->status !== 'disetujui')<div class="modal fade" id="reject{{ $submission->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="{{ route('tenaga-pendidik.submissions.reject', $submission) }}">@csrf<div class="modal-header"><h5 class="modal-title">Minta Perbaikan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><textarea name="review_note" class="form-control" rows="4" placeholder="Tuliskan kekurangan data..." required></textarea></div><div class="modal-footer"><button class="btn btn-danger">Kirim Catatan</button></div></form></div></div></div>@endif
                    @empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan.</td></tr>@endforelse
                </tbody></table></div></div>
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
