@extends('layouts.master')

@section('title', 'Pengajuan Tenaga Pendidik')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Pengajuan Tenaga Pendidik @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div><h4 class="card-title mb-1">Pengajuan Tenaga Pendidik</h4><p class="text-muted mb-0">Pantau proses pengajuan, review, dan persetujuan akun tenaga pendidik.</p></div>
                    <a href="{{ route('tenaga-pendidik.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Data Tenaga Pendidik</a>
                </div>

                <div class="row g-3 mb-4">
                    @foreach([['menunggu_review','Menunggu Review','info'],['perlu_perbaikan','Perlu Perbaikan','warning'],['disetujui','Disetujui','success']] as [$key,$label,$color])
                        <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">{{ $label }}</div><div class="fs-3 fw-semibold text-{{ $color }}">{{ $counts[$key] ?? 0 }}</div></div></div>
                    @endforeach
                </div>

                <form method="GET" class="row g-2 align-items-end mb-4">
                    <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="">Semua status</option>@foreach(['menunggu_review'=>'Menunggu Review','perlu_perbaikan'=>'Perlu Perbaikan','disetujui'=>'Disetujui'] as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select></div>
                    @if($isSuperAdmin)<div class="col-md-5"><label class="form-label">Madrasah</label><select name="madrasah_id" class="form-select"><option value="">Semua madrasah</option>@foreach($madrasahs as $madrasah)<option value="{{ $madrasah->id }}" @selected((string) request('madrasah_id') === (string) $madrasah->id)>{{ $madrasah->name }}</option>@endforeach</select></div>@endif
                    <div class="col-md-auto"><button class="btn btn-primary"><i class="bx bx-filter-alt me-1"></i> Filter</button></div>
                </form>

                <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Nama / Email</th><th>Madrasah</th><th>Pengaju</th><th>Status</th><th>Catatan Review</th><th>Diperbarui</th><th>Aksi</th></tr></thead><tbody>
                    @forelse($submissions as $submission)
                        @php($payload = $submission->payload ?? [])
                        <tr><td><strong>{{ $payload['nama'] ?? '-' }}</strong><br><small class="text-muted">{{ $payload['email'] ?? '-' }}</small></td><td>{{ optional($submission->madrasah)->name ?: '-' }}</td><td>{{ optional($submission->submitter)->name ?: '-' }}</td><td><span class="badge bg-{{ $submission->status === 'disetujui' ? 'success' : ($submission->status === 'perlu_perbaikan' ? 'warning text-dark' : 'info') }}">{{ str_replace('_', ' ', ucfirst($submission->status)) }}</span></td><td class="small">{{ $submission->review_note ?: '-' }}</td><td>{{ $submission->updated_at?->format('d/m/Y H:i') }}</td><td class="text-nowrap">
                            @if($isSuperAdmin && $submission->status !== 'disetujui')<form method="POST" action="{{ route('tenaga-pendidik.submissions.approve', $submission) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success" onclick="return confirm('Approve pengajuan ini?')"><i class="bx bx-check"></i></button></form><button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reject{{ $submission->id }}"><i class="bx bx-x"></i></button>@elseif(!$isSuperAdmin && $submission->status === 'perlu_perbaikan')<form method="POST" action="{{ route('tenaga-pendidik.submissions.resubmit', $submission) }}" class="d-inline">@csrf<input type="hidden" name="review_note" value="Perbaikan telah dilakukan dan data diajukan kembali."><button class="btn btn-sm btn-primary">Ajukan Ulang</button></form>@endif
                        </td></tr>
                        @if($isSuperAdmin && $submission->status !== 'disetujui')<div class="modal fade" id="reject{{ $submission->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="{{ route('tenaga-pendidik.submissions.reject', $submission) }}">@csrf<div class="modal-header"><h5 class="modal-title">Minta Perbaikan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><textarea name="review_note" class="form-control" rows="4" placeholder="Tuliskan kekurangan data..." required></textarea></div><div class="modal-footer"><button class="btn btn-danger">Kirim Catatan</button></div></form></div></div></div>@endif
                    @empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan.</td></tr>@endforelse
                </tbody></table></div>
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
