@extends('layouts.master')

@section('title', 'Karyawan Yayasan')

@section('css')
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .mgmp-page{--dash-green:#08745a;--dash-ink:#192d37;position:relative;isolation:isolate;padding:8px 0 24px}.mgmp-page:before,.mgmp-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:karyawan-orb 11s ease-in-out infinite}.mgmp-page:before{width:280px;height:280px;right:-90px;top:-80px;background:rgba(42,190,153,.15)}.mgmp-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.12);animation-direction:reverse;animation-duration:14s}body:has(.mgmp-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}.mgmp-page .mgmp-hero-strip{position:relative;overflow:hidden;background:linear-gradient(135deg,#005b58,#08745a);border:1px solid rgba(255,255,255,.35);border-radius:22px;padding:24px;box-shadow:0 14px 34px rgba(32,70,78,.14);animation:karyawan-in .7s both}.mgmp-page .mgmp-hero-strip:before{content:"";position:absolute;inset:-80% -30%;background:linear-gradient(110deg,transparent 38%,rgba(255,255,255,.16) 48%,transparent 58%);transform:translateX(-50%) rotate(12deg);animation:karyawan-shine 7s ease-in-out infinite}.mgmp-page .mgmp-hero-strip>*{position:relative}.mgmp-page .mgmp-kicker{font-size:11px;text-transform:uppercase;letter-spacing:.12em;font-weight:700;color:#c4f3df}.mgmp-page>.card{border:1px solid rgba(255,255,255,.86)!important;border-radius:20px!important;background:rgba(255,255,255,.7)!important;backdrop-filter:blur(16px);box-shadow:0 8px 26px rgba(32,70,78,.08)!important}.mgmp-page .card-body{padding:20px 24px}.mgmp-page .table-responsive{border:1px solid #e5edef;border-radius:16px;overflow-x:auto}.mgmp-page .karyawan-table{min-width:850px}.mgmp-page .karyawan-table thead th{background:#f1f6f6!important;border:0!important;border-bottom:1px solid #e5edef!important;color:var(--dash-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.mgmp-page .karyawan-table tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle}.mgmp-page .karyawan-table tbody tr{transition:background .18s ease,transform .18s ease}.mgmp-page .karyawan-table tbody tr:hover{background:rgba(231,246,240,.45);transform:translateX(2px)}.mgmp-page .btn{border-radius:10px;transition:transform .18s ease,box-shadow .18s ease}.mgmp-page .btn:hover{transform:translateY(-2px);box-shadow:0 7px 14px rgba(32,70,78,.14)}.mgmp-page .btn-primary{background:linear-gradient(90deg,#0f766e,#14b8a6);border:0}.mgmp-page .form-control{border-radius:10px;border-color:#e5edef}.mgmp-page .badge{border-radius:999px;padding:.45em .75em}@keyframes karyawan-shine{0%,35%{transform:translateX(-55%) rotate(12deg)}70%,100%{transform:translateX(55%) rotate(12deg)}}@keyframes karyawan-orb{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(-12px,18px,0)}}@keyframes karyawan-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@media(max-width:767.98px){.mgmp-page .mgmp-hero-strip{padding:18px}.mgmp-page .card-body{padding:14px}}
    </style>
@endsection

@section('content')
<div class="container-fluid py-3 mgmp-page">
    <div class="mgmp-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="mgmp-kicker mb-2">Admin · Master Data</div>
            <h4 class="mb-1 text-white">Karyawan Yayasan</h4>
            <p class="mb-0 text-white-50">Kelola akun karyawan dan akses presensi mobile yayasan.</p>
        </div>
        <span class="mgmp-chip bg-white text-success px-3 py-2">{{ $employees->total() }} karyawan</span>
        </div>
    </div></div>

    <div class="card mb-4"><div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div><div class="mgmp-kicker text-success mb-2">Employee Directory</div><h5 class="mb-1">Daftar Karyawan</h5><p class="text-muted mb-0">Data akun karyawan yayasan yang dapat menggunakan presensi.</p></div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKaryawanModal"><i class="bx bx-plus me-1"></i>Tambah Karyawan</button>
        </div>
    </div></div>


    <div class="card"><div class="card-body">
            <form class="row g-2 mb-3" method="GET">
                <div class="col-12 col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama, email, atau NIP"></div>
                <div class="col-auto"><button class="btn btn-outline-secondary">Cari</button></div>
            </form>
            <div class="table-responsive">
                <table class="table align-middle karyawan-table mb-0">
                    <thead><tr><th>Nama</th><th>Email</th><th>Jabatan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td><strong>{{ $employee->name }}</strong><small class="d-block text-muted">{{ $employee->nip ?: 'NIP belum diisi' }}</small></td>
                            <td>{{ $employee->email }}</td><td>{{ $employee->jabatan ?: '-' }}</td>
                            <td><span class="badge {{ $employee->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">{{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editKaryawan{{ $employee->id }}"><i class="bx bx-edit-alt"></i></button>@if($employee->is_active)<form method="POST" action="{{ route('admin.karyawan.destroy', $employee) }}" class="d-inline js-deactivate-form" data-name="{{ $employee->name }}">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Nonaktifkan</button></form>@endif</td>
                        </tr>
                    @empty <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data karyawan.</td></tr> @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $employees->links() }}</div>
        </div>
    </div>
</div>

@foreach($employees as $employee)
<div class="modal fade" id="editKaryawan{{ $employee->id }}" tabindex="-1" aria-labelledby="editKaryawanLabel{{ $employee->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content">
        <div class="modal-header"><div><div class="mgmp-kicker text-success mb-1">Employee Directory</div><h5 class="modal-title" id="editKaryawanLabel{{ $employee->id }}">Edit Data Karyawan</h5></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <form method="POST" action="{{ route('admin.karyawan.update', $employee) }}" autocomplete="off">@csrf @method('PUT')
            <div class="modal-body"><div class="row g-3">
                @foreach([['name','Nama lengkap','text'],['nip','NIP / kode karyawan','text'],['email','Email akun','email'],['no_hp','Nomor telepon','text'],['jabatan','Jabatan','text'],['instansi_asal','Unit kerja yayasan','text']] as [$field,$label,$type])
                    <div class="col-12 col-md-6"><label class="form-label" for="edit_{{$field}}_{{$employee->id}}">{{$label}}</label><input id="edit_{{$field}}_{{$employee->id}}" name="{{$field}}" type="{{$type}}" value="{{ old($field, $employee->{$field}) }}" class="form-control" @if(in_array($field, ['name','email'], true)) required @endif></div>
                @endforeach
                <div class="col-12 col-md-6"><label class="form-label">Password baru <small class="text-muted">(opsional)</small></label><input name="password" type="password" minlength="8" class="form-control"><small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small></div>
                <div class="col-12 col-md-6"><label class="form-label">Konfirmasi password baru</label><input name="password_confirmation" type="password" minlength="8" class="form-control"></div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Simpan Perubahan</button></div>
        </form>
    </div></div>
</div>
@endforeach

<div class="modal fade" id="tambahKaryawanModal" tabindex="-1" aria-labelledby="tambahKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content">
        <div class="modal-header"><div><div class="mgmp-kicker text-success mb-1">Employee Directory</div><h5 class="modal-title" id="tambahKaryawanModalLabel">Tambah Karyawan Yayasan</h5></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <form method="POST" action="{{ route('admin.karyawan.store') }}" autocomplete="off">@csrf
            <div class="modal-body"><div class="row g-3">
                @foreach([['name','Nama lengkap','text',true],['nip','NIP / kode karyawan','text',false],['email','Email akun','email',true],['no_hp','Nomor telepon','text',false],['jabatan','Jabatan','text',false],['instansi_asal','Unit kerja yayasan','text',false]] as [$field,$label,$type,$required])
                    <div class="col-12 col-md-6"><label class="form-label" for="modal_{{$field}}">{{$label}} @if($required)<span class="text-danger">*</span>@endif</label><input id="modal_{{$field}}" name="{{$field}}" type="{{$type}}" value="{{ old($field) }}" class="form-control" @if($required) required @endif></div>
                @endforeach
                <div class="col-12 col-md-6"><label class="form-label" for="modal_password">Password awal <span class="text-danger">*</span></label><input id="modal_password" name="password" type="password" minlength="8" class="form-control" required><small class="text-muted">Minimal 8 karakter.</small></div>
                <div class="col-12 col-md-6"><label class="form-label" for="modal_password_confirmation">Konfirmasi password <span class="text-danger">*</span></label><input id="modal_password_confirmation" name="password_confirmation" type="password" minlength="8" class="form-control" required></div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Simpan Karyawan</button></div>
        </form>
    </div></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const glossy = {
        customClass: { popup: 'karyawan-swal-popup', confirmButton: 'karyawan-swal-confirm', cancelButton: 'karyawan-swal-cancel' },
        buttonsStyling: false
    };

    @if(session('success'))
        Swal.fire({ ...glossy, icon: 'success', title: 'Berhasil', text: @json(session('success')), confirmButtonText: 'Oke', timer: 3200, timerProgressBar: true });
    @endif

    @if($errors->any())
        Swal.fire({ ...glossy, icon: 'error', title: 'Data belum disimpan', html: @json('<ul style="text-align:left;margin:0;padding-left:20px">'.collect($errors->all())->map(fn($error) => '<li>'.e($error).'</li>')->implode('').'</ul>'), confirmButtonText: 'Periksa kembali' });
        const modal = document.getElementById('tambahKaryawanModal');
        if (modal && window.bootstrap) bootstrap.Modal.getOrCreateInstance(modal).show();
    @endif

    document.querySelectorAll('.js-deactivate-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({ ...glossy, icon: 'warning', title: 'Nonaktifkan akun?', text: 'Akun '+form.dataset.name+' tidak dapat login setelah dinonaktifkan.', showCancelButton: true, confirmButtonText: 'Ya, nonaktifkan', cancelButtonText: 'Batal' }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
<style>
    .karyawan-swal-popup{border:1px solid rgba(255,255,255,.9)!important;border-radius:22px!important;background:rgba(255,255,255,.92)!important;backdrop-filter:blur(18px);box-shadow:0 20px 60px rgba(32,70,78,.22)!important;color:#192d37!important}
    .karyawan-swal-confirm,.karyawan-swal-cancel{border:0;border-radius:10px;padding:10px 18px;margin:0 4px;font-weight:700;transition:transform .18s ease,box-shadow .18s ease}
    .karyawan-swal-confirm{background:linear-gradient(90deg,#08745a,#14b8a6);color:#fff;box-shadow:0 7px 16px rgba(8,116,90,.2)}
    .karyawan-swal-cancel{background:#f1f5f6;color:#52656d}
    .karyawan-swal-confirm:hover,.karyawan-swal-cancel:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(32,70,78,.14)}
</style>
@endpush
