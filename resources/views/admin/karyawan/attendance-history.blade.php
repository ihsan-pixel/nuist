@extends('layouts.master')
@section('title', 'Riwayat Presensi Karyawan')
@section('css')
<style>
    .mgmp-page{--dash-ink:#192d37;padding:8px 0 24px}.mgmp-page:before{content:"";position:absolute;z-index:-1;width:280px;height:280px;right:-90px;top:-80px;border-radius:50%;background:rgba(42,190,153,.15);filter:blur(2px)}body:has(.mgmp-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}.mgmp-page .mgmp-hero-strip{position:relative;overflow:hidden;background:linear-gradient(135deg,#005b58,#08745a);border:1px solid rgba(255,255,255,.35);border-radius:22px;padding:24px;box-shadow:0 14px 34px rgba(32,70,78,.14)}.mgmp-page .mgmp-hero-strip:after{content:"";position:absolute;width:220px;height:220px;right:-90px;top:-100px;border-radius:50%;background:rgba(255,255,255,.13);filter:blur(2px)}.mgmp-page .mgmp-hero-strip>*{position:relative;z-index:1}.mgmp-page>.card{border:1px solid rgba(255,255,255,.86)!important;border-radius:20px!important;background:rgba(255,255,255,.7)!important;backdrop-filter:blur(16px);box-shadow:0 8px 26px rgba(32,70,78,.08)!important}.mgmp-page .card-body{padding:20px 24px}.mgmp-page .form-control,.mgmp-page .form-select{border-radius:10px;border-color:#e5edef}.mgmp-page .karyawan-table{min-width:700px}.mgmp-page .karyawan-table thead th{background:#f1f6f6;border:0;border-bottom:1px solid #e5edef;color:var(--dash-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.mgmp-page .karyawan-table tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle}.mgmp-page .btn{border-radius:10px}.mgmp-page .btn-primary{background:linear-gradient(90deg,#0f766e,#14b8a6);border:0}@media(max-width:767.98px){.mgmp-page .mgmp-hero-strip{padding:18px}.mgmp-page .card-body{padding:14px}}
</style>
@endsection
@section('content')
<div class="container-fluid py-3 mgmp-page">
    <div class="mgmp-hero-strip mb-4"><div class="mgmp-kicker mb-2">Admin · Karyawan Yayasan</div><h4 class="mb-1 text-white">Riwayat Presensi</h4><p class="mb-0 text-white-50">Pantau presensi masuk dan pulang seluruh karyawan yayasan.</p></div>
    <div class="card"><div class="card-body">
        <form class="row g-2 align-items-end mb-4" method="GET">
            <div class="col-12 col-md-4"><label class="form-label small fw-semibold">Karyawan</label><select name="employee_id" class="form-select"><option value="">Semua karyawan</option>@foreach($employees as $employee)<option value="{{ $employee->id }}" @selected(request('employee_id') == $employee->id)>{{ $employee->name }}{{ $employee->nip ? ' · '.$employee->nip : '' }}</option>@endforeach</select></div>
            <div class="col-12 col-md-3"><label class="form-label small fw-semibold">Bulan</label><input type="month" name="month" value="{{ request('month') }}" class="form-control"></div>
            <div class="col-auto"><button class="btn btn-primary"><i class="bx bx-filter-alt me-1"></i>Filter</button></div>
            <div class="col-auto"><a href="{{ route('admin.karyawan.attendance-history') }}" class="btn btn-light">Reset</a></div>
        </form>
        <div class="table-responsive"><table class="table align-middle karyawan-table mb-0"><thead><tr><th>Tanggal</th><th>Karyawan</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr></thead><tbody>
            @forelse($records as $record)<tr><td class="fw-semibold">{{ optional($record->tanggal)->format('d M Y') ?? $record->tanggal }}</td><td>{{ $record->user?->name ?? '-' }}</td><td>{{ $record->waktu_masuk ? \Carbon\Carbon::parse($record->waktu_masuk)->format('H:i') : '-' }}</td><td>{{ $record->waktu_keluar ? \Carbon\Carbon::parse($record->waktu_keluar)->format('H:i') : '-' }}</td><td><span class="badge bg-success-subtle text-success">{{ $record->status ?? 'Hadir' }}</span></td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat presensi karyawan.</td></tr>@endforelse
        </tbody></table></div><div class="mt-3">{{ $records->links() }}</div>
    </div></div>
</div>
@endsection
