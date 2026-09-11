@extends('mobile.bpppmnu.layout')
@section('title', 'Riwayat Presensi')
@section('bpp-content')
<form method="get" class="card"><div class="card-body"><div class="row g-2">
<div class="col-6"><label class="form-label" for="month">Bulan</label><select class="form-select" id="month" name="month"><option value="">Semua bulan</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}" @selected(request('month') == $m)>{{ $m }}</option>@endfor</select></div>
<div class="col-6"><label class="form-label" for="year">Tahun</label><input class="form-control" type="number" id="year" name="year" min="2000" max="2200" placeholder="Semua tahun" value="{{ request('year') }}"></div>
<div class="col-12"><label class="form-label" for="status">Status kehadiran</label><select class="form-select" id="status" name="status"><option value="">Semua status</option><option value="hadir" @selected(request('status') === 'hadir')>Hadir</option><option value="tidak_hadir" @selected(request('status') === 'tidak_hadir')>Tidak Hadir</option></select></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Terapkan Filter</button><a class="btn btn-link" href="{{ route('mobile.bpppmnu.history') }}">Reset</a></div></div></div></form>
@forelse($history as $row)
<article class="card"><div class="card-body"><span class="badge bg-{{ $row->attended_at ? 'success' : 'secondary' }} mb-2">{{ $row->attended_at ? 'Hadir' : 'Tidak Hadir' }}</span><h2>{{ $row->name }}</h2><p class="bpp-meta">{{ \Carbon\Carbon::parse($row->start_at)->format('d-m-Y H:i') }} WIB</p><p class="bpp-meta">Waktu presensi: {{ $row->attended_at ? \Carbon\Carbon::parse($row->attended_at)->format('d-m-Y H:i:s').' WIB' : '—' }}</p><a href="{{ route('mobile.bpppmnu.events.show', $row->id) }}">Detail Kegiatan</a></div></article>
@empty
<div class="card"><div class="card-body">Belum ada riwayat sesuai filter. Riwayat tampil setelah kegiatan selesai dan presensi ditutup.</div></div>
@endforelse
{{ $history->links() }}
@endsection
