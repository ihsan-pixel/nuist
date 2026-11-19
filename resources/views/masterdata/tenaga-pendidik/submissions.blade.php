@extends('layouts.master')

@section('title', 'Pengajuan Tenaga Pendidik')

@section('css')
<style>
    .tp-page{--tp-green:#08745a;--tp-ink:#192d37;--tp-muted:#6c7c86;position:relative;padding:8px 0 24px}
    body:has(.tp-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.tp-page) .main-content{background:transparent!important}
    .tp-page .tp-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden}
    .tp-page .tp-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid #e5edef}
    .tp-page .tp-eyebrow{margin:0 0 4px;color:var(--tp-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.tp-page .tp-title{margin:0;color:var(--tp-ink);font-size:22px;font-weight:700}.tp-page .tp-subtitle{margin:5px 0 0;color:var(--tp-muted);font-size:12px}
    .tp-page .tp-content{padding:20px 24px 24px}.tp-page .tp-stat{border:1px solid #dce7e9;border-radius:14px;background:rgba(255,255,255,.58);box-shadow:0 5px 14px rgba(32,70,78,.05)}
    .tp-page .tp-filter,.tp-page .tp-table-panel{border:1px solid #e5edef;border-radius:16px;background:rgba(255,255,255,.58)}.tp-page .tp-table-panel{overflow-x:auto}
    .tp-page .tp-form-section{background:#f8fafc;border:1px solid #e9edf4;border-radius:1rem;padding:1.25rem}.tp-modal-content{border:1px solid rgba(255,255,255,.9);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}.tp-modal-content .modal-header{border-bottom:1px solid #e5edef;background:#f6faf9}.tp-modal-content .modal-footer{border-top:1px solid #e5edef}.tp-modal-content .form-label{font-size:.82rem;font-weight:600;color:#334155}.tp-modal-content .form-control,.tp-modal-content .form-select{border-color:#dce7e9;border-radius:9px}
    .tp-stepper span{flex:1;border-bottom:3px solid #dce7e9;padding:.55rem .4rem;color:#8a9aa2;font-size:.75rem;font-weight:600;text-align:center}.tp-stepper span.active{border-color:#08745a;color:#08745a}
    .tp-page table thead th{border:0;border-bottom:1px solid #e5edef;background:#f1f6f6;color:var(--tp-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.tp-page table tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle}.tp-page .btn{border-radius:10px}.tp-page .tp-toolbar .btn{border-radius:12px}.tp-page .pagination{margin:16px 0 0}
    @media(max-width:767.98px){.tp-page .tp-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.tp-page .tp-content{padding:14px}.tp-page .tp-toolbar .btn{width:100%}}
</style>
@endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Pengajuan Tenaga Pendidik @endslot
@endcomponent

<div class="row tp-page">
    <div class="col-12">
        <div class="card tp-shell">
            <div class="card-header tp-toolbar">
                <div><p class="tp-eyebrow">MASTER DATA</p><h4 class="tp-title">Pengajuan Tenaga Pendidik</h4><p class="tp-subtitle">Pantau proses pengajuan, review, dan persetujuan akun tenaga pendidik.</p></div>
                <div class="d-flex flex-wrap gap-2"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTP"><i class="bx bx-plus me-1"></i> Tambah Pengajuan</button><a href="{{ route('tenaga-pendidik.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Data Tenaga Pendidik</a></div>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 4;
    const form = document.querySelector('#modalTambahTP form');
    const showStep = (step) => {
        currentStep = step;
        document.querySelectorAll('#modalTambahTP .tp-step').forEach(el => el.classList.toggle('d-none', Number(el.dataset.step) !== step));
        document.querySelectorAll('#modalTambahTP [data-step-label]').forEach(el => el.classList.toggle('active', Number(el.dataset.stepLabel) === step));
        document.querySelector('#submissionPrevious').classList.toggle('d-none', step === 1);
        document.querySelector('#submissionNext').classList.toggle('d-none', step === totalSteps);
        document.querySelector('#submissionSubmit').classList.toggle('d-none', step !== totalSteps);
    };
    document.querySelector('#submissionNext').addEventListener('click', () => {
        const fields = [...document.querySelectorAll('#modalTambahTP .tp-step:not(.d-none) input, #modalTambahTP .tp-step:not(.d-none) select')];
        if (!fields.every(field => field.reportValidity())) return;
        showStep(Math.min(totalSteps, currentStep + 1));
    });
    document.querySelector('#submissionPrevious').addEventListener('click', () => showStep(Math.max(1, currentStep - 1)));
    document.querySelectorAll('[data-toggle-password]').forEach(button => button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.togglePassword);
        input.type = input.type === 'password' ? 'text' : 'password';
        button.querySelector('i').className = input.type === 'password' ? 'bx bx-show' : 'bx bx-hide';
    }));
    form.addEventListener('submit', function (event) {
        const password = form.querySelector('[name="password"]');
        const confirmation = form.querySelector('[name="password_confirmation"]');
        if (password.value !== confirmation.value) { event.preventDefault(); confirmation.setCustomValidity('Konfirmasi password tidak sama.'); confirmation.reportValidity(); } else confirmation.setCustomValidity('');
    });
    showStep(1);
});
</script>
@endsection

<div class="modal fade" id="modalTambahTP" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content tp-modal-content">
        <form action="{{ route('tenaga-pendidik.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="modal-header"><div><h5 class="modal-title mb-1">Tambah Pengajuan Tenaga Pendidik</h5><p class="text-muted mb-0 small">Data akan dikirim untuk direview oleh Super Admin.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="tp-stepper d-flex gap-2 mb-4"><span class="active" data-step-label="1">1. Akun</span><span data-step-label="2">2. Penugasan</span><span data-step-label="3">3. Profil</span><span data-step-label="4">4. Pendidikan</span></div>
                <div class="tp-step" data-step="1"><div class="row g-3"><div class="col-md-8"><label class="form-label">Nama Lengkap *</label><input name="nama" class="form-control" required></div><div class="col-md-4"><label class="form-label">Gelar</label><input name="gelar" class="form-control" placeholder="S.Pd., M.Pd."></div><div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div><div class="col-md-6"><label class="form-label">Password *</label><div class="input-group"><input type="password" name="password" id="submission_password" class="form-control" minlength="6" required><button type="button" class="btn btn-outline-secondary" data-toggle-password="submission_password"><i class="bx bx-show"></i></button></div></div><div class="col-md-6"><label class="form-label">Konfirmasi Password *</label><div class="input-group"><input type="password" name="password_confirmation" id="submission_password_confirmation" class="form-control" required><button type="button" class="btn btn-outline-secondary" data-toggle-password="submission_password_confirmation"><i class="bx bx-show"></i></button></div></div></div></div>
                <div class="tp-step d-none" data-step="2"><div class="row g-3"><div class="col-md-6"><label class="form-label">Madrasah/Sekolah</label>@if(auth()->user()->role === 'admin')<input class="form-control" value="{{ optional(auth()->user()->madrasah)->name }}" readonly><input type="hidden" name="madrasah_id" value="{{ auth()->user()->madrasah_id }}">@else<select name="madrasah_id" class="form-select" required><option value="">Pilih Madrasah</option>@foreach($madrasahs as $madrasah)<option value="{{ $madrasah->id }}">{{ $madrasah->name }}</option>@endforeach</select>@endif</div><div class="col-md-6"><label class="form-label">Ketugasan</label><input class="form-control" value="Tenaga Pendidik" readonly><input type="hidden" name="ketugasan" value="tenaga pendidik"></div><div class="col-md-6"><label class="form-label">Mengajar</label><select name="mengajar" class="form-select"><option value="">Pilih data mengajar</option>@foreach($mengajarOptions as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select></div><div class="col-md-6"><label class="form-label">Status Kepegawaian</label><select name="status_kepegawaian_id" class="form-select"><option value="">Pilih Status</option>@foreach($statusKepegawaian as $status)<option value="{{ $status->id }}">{{ $status->name }}</option>@endforeach</select></div></div></div>
                <div class="tp-step d-none" data-step="3"><div class="row g-3"><div class="col-md-6"><label class="form-label">No. HP</label><input name="no_hp" class="form-control"></div><div class="col-md-6"><label class="form-label">Kartanu</label><input name="kartanu" class="form-control"></div><div class="col-md-6"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control"></div><div class="col-md-6"><label class="form-label">Tempat Lahir</label><input name="tempat_lahir" class="form-control"></div><div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="4"></textarea></div></div></div>
                <div class="tp-step d-none" data-step="4"><div class="row g-3"><div class="col-md-6"><label class="form-label">NIP Ma'arif</label><input name="nip" class="form-control"></div><div class="col-md-6"><label class="form-label">NUPTK</label><input name="nuptk" class="form-control"></div><div class="col-md-6"><label class="form-label">Pendidikan Terakhir</label><input name="pendidikan_terakhir" class="form-control"></div><div class="col-md-6"><label class="form-label">Program Studi</label><input name="program_studi" class="form-control"></div><div class="col-md-6"><label class="form-label">Foto Profil</label><input type="file" name="avatar" class="form-control" accept=".jpg,.jpeg,.png"></div></div></div>
            </div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-outline-secondary d-none" id="submissionPrevious">Sebelumnya</button><button type="button" class="btn btn-primary" id="submissionNext">Berikutnya</button><button type="submit" class="btn btn-success d-none" id="submissionSubmit"><i class="bx bx-send me-1"></i> Kirim Pengajuan</button></div>
        </form>
    </div></div>
</div>
