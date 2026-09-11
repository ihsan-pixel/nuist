@extends('admin.bpppmnu.layout')
@section('bpp-content')
<div class="card"><div class="card-body"><h2 class="h4">{{ $event->exists ? 'Edit' : 'Buat' }} Kegiatan</h2>
<p class="text-muted">Seluruh waktu menggunakan WIB. Agenda dan undangan dikunci setelah periode presensi dibuka.</p>
<form method="post" enctype="multipart/form-data" action="{{ $event->exists ? route('admin.bpppmnu.events.update', $event) : route('admin.bpppmnu.events.store') }}">@csrf @if($event->exists) @method('PUT') @endif
<div class="row g-3">
@foreach(['name'=>'Nama kegiatan','type'=>'Jenis kegiatan','organizer'=>'Penyelenggara','location_name'=>'Nama lokasi'] as $field=>$label)
<div class="col-md-6"><label class="form-label" for="{{ $field }}">{{ $label }}</label><input class="form-control" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $event->$field) }}" maxlength="255" required></div>
@endforeach
@foreach(['start_at'=>'Tanggal & waktu mulai','end_at'=>'Tanggal & waktu selesai','attendance_open_at'=>'Presensi dibuka','attendance_close_at'=>'Presensi ditutup'] as $field=>$label)
<div class="col-md-6"><label class="form-label" for="{{ $field }}">{{ $label }} (WIB)</label><input type="datetime-local" class="form-control" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $event->$field?->format('Y-m-d\TH:i')) }}" required></div>
@endforeach
<div class="col-md-6"><label class="form-label" for="meeting_url">Link alamat/lokasi kegiatan (opsional)</label><input type="url" class="form-control" id="meeting_url" name="meeting_url" value="{{ old('meeting_url', $event->meeting_url) }}" placeholder="https://maps.google.com/..." aria-describedby="meeting-url-help"><small id="meeting-url-help" class="form-text text-muted">Masukkan link Google Maps atau alamat lokasi kegiatan.</small></div>
<div class="col-md-6"><label class="form-label" for="attachment">Lampiran undangan (PDF/JPG/PNG, maks. 5 MB)</label><input type="file" accept=".pdf,.jpg,.jpeg,.png" class="form-control" id="attachment" name="attachment">@if($event->attachment)<small>Lampiran saat ini tersimpan. Unggah untuk mengganti.</small>@endif</div>
<div class="col-12"><fieldset><legend class="h5">Pilih pengurus yang diundang</legend><div style="max-height:320px;overflow:auto" class="border rounded p-3">
@forelse($members as $member)<div class="form-check mb-2"><input class="form-check-input" type="checkbox" id="invitee-{{ $member->id }}" name="invitees[]" value="{{ $member->id }}" @checked(in_array($member->id, old('invitees', $selected)))><label class="form-check-label" for="invitee-{{ $member->id }}">{{ $member->name }} · {{ $member->nuist_id }} · {{ $member->ketugasan }}</label></div>@empty<p>Belum ada akun pengurus aktif. Buat akun melalui menu Pengurus BPPPMNU.</p>@endforelse
</div></fieldset></div>
<div class="col-12"><button class="btn btn-primary">{{ $event->exists ? 'Simpan Perubahan' : 'Simpan Draft' }}</button></div>
</div></form></div></div>
@endsection
