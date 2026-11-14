@extends('layouts.master')
@section('title', 'Tambah Karyawan')
@section('content')
<div class="container-fluid py-3"><div class="row justify-content-center"><div class="col-12 col-xl-8"><div class="card"><div class="card-body">
<h4 class="mb-1">Tambah Karyawan</h4><p class="text-muted mb-4">Akun akan dibuat dengan role <code>karyawan</code>.</p>
<form method="POST" action="{{ route('admin.karyawan.store') }}">@csrf
<div class="row g-3">
@foreach([['name','Nama lengkap','text'],['nip','NIP / kode karyawan','text'],['email','Email','email'],['no_hp','Nomor telepon','text'],['jabatan','Jabatan','text'],['instansi_asal','Unit kerja yayasan','text']] as [$field,$label,$type])<div class="col-12 col-md-6"><label class="form-label" for="{{$field}}">{{$label}}</label><input id="{{$field}}" name="{{$field}}" type="{{$type}}" value="{{ old($field) }}" class="form-control @error($field) is-invalid @enderror">@error($field)<div class="invalid-feedback">{{$message}}</div>@enderror</div>@endforeach
<div class="col-12 col-md-6"><label class="form-label">Password awal</label><input name="password" type="password" class="form-control" required></div><div class="col-12 col-md-6"><label class="form-label">Konfirmasi password</label><input name="password_confirmation" type="password" class="form-control" required></div>
</div><div class="d-flex gap-2 mt-4"><a href="{{ route('admin.karyawan.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan Karyawan</button></div></form>
</div></div></div></div></div>
@endsection
