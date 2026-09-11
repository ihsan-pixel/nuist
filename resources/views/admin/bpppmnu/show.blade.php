@extends('admin.bpppmnu.layout')
@section('bpp-content')
<div class="card"><div class="card-body"><h2>{{ $event->name }}</h2><span class="badge bg-secondary">{{ $event->status }}</span>
@include('bpppmnu.event-details')
@if($event->attachment)<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.bpppmnu.events.attachment', $event) }}">Unduh lampiran</a>@endif
<div class="d-flex gap-2 flex-wrap">
@if(!$event->isLocked() && $event->status !== 'cancelled')<a class="btn btn-outline-primary" href="{{ route('admin.bpppmnu.events.edit', $event) }}">Edit Agenda & Undangan</a>@endif
@if($event->status === 'draft')<form method="post" action="{{ route('admin.bpppmnu.events.publish', $event) }}">@csrf<button class="btn btn-success">Terbitkan</button></form>@endif
@if($event->status === 'published' && now()->lte($event->attendance_close_at))
<form method="post" action="{{ route('admin.bpppmnu.events.qr', $event) }}">@csrf<button class="btn btn-primary">Generate & Tampilkan QR Baru</button></form>
<form method="post" action="{{ route('admin.bpppmnu.events.revoke', $event) }}" onsubmit="return confirm('Cabut semua QR aktif kegiatan ini?')">@csrf<button class="btn btn-outline-warning">Cabut QR</button></form>
@endif
@if($event->status !== 'cancelled' && !$event->isFinished() && $recap['present'] === 0)
<form method="post" action="{{ route('admin.bpppmnu.events.cancel', $event) }}" onsubmit="return confirm('Batalkan kegiatan ini?')">@csrf<button class="btn btn-outline-danger">Batalkan Kegiatan</button></form>
@endif
</div></div></div>
<div class="row g-3 mb-3">
@foreach(['Undangan'=>$recap['total'], 'Hadir'=>$recap['present'], ($event->status === 'cancelled' ? 'Dibatalkan / belum hadir' : ($event->isFinished() && $event->status === 'published' ? 'Tidak Hadir' : 'Belum Presensi'))=>$recap['remaining'], 'Kehadiran'=>$recap['percentage'].'%'] as $label=>$value)
<div class="col-6 col-lg-3"><div class="card h-100 mb-0"><div class="card-body"><div class="text-muted">{{ $label }}</div><strong class="fs-3">{{ $value }}</strong></div></div></div>
@endforeach
</div>
<div class="card"><div class="card-body"><div class="d-flex justify-content-between mb-3"><h3 class="h5">Rekap Kehadiran</h3><a href="{{ route('admin.bpppmnu.events.export', $event) }}" class="btn btn-success btn-sm">Export Excel</a></div>
<div class="table-responsive"><table class="table"><thead><tr><th>Nama</th><th>ID NUIST</th><th>Jabatan</th><th>Status</th><th>Waktu hadir (WIB)</th></tr></thead><tbody>
@forelse($recap['rows'] as $row)<tr><td>{{ $row->name }}</td><td>{{ $row->nuist_id }}</td><td>{{ $row->jabatan ?: '—' }}</td><td>{{ $row->status }}</td><td>{{ $row->attended_at ?: '—' }}</td></tr>@empty<tr><td colspan="5">Belum ada undangan.</td></tr>@endforelse
</tbody></table></div><small class="text-muted">Muat ulang halaman untuk memperbarui rekap.</small></div></div>
@endsection
