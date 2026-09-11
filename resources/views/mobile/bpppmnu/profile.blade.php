@extends('mobile.bpppmnu.layout')
@section('title', 'Profil')
@section('bpp-content')
<div class="card"><div class="card-body">
@if($user->avatar)<img class="rounded-circle mb-3" src="{{ asset('storage/'.ltrim($user->avatar, '/')) }}" width="72" height="72" alt="Foto profil" style="object-fit:cover">@else<i class="bx bxs-user-circle fs-1"></i>@endif
<h2>{{ $user->name }}</h2><dl>
@foreach(['nuist_id'=>'ID NUIST','email'=>'Email','no_hp'=>'Nomor HP','alamat'=>'Alamat'] as $field=>$label)
<dt>{{ $label }}</dt><dd>{{ $user->$field ?: '—' }}</dd>
@endforeach
<dt>Jabatan</dt><dd>{{ $user->jabatan ?: ($user->ketugasan ?: '—') }}</dd>
</dl></div></div>
<div class="card"><div class="card-body"><details @if($errors->any()) open @endif><summary class="fw-semibold">Ganti Password</summary>
<form action="{{ route('mobile.bpppmnu.password') }}" method="post" class="mt-3">@csrf
@include('mobile.partials.password-fields')
<button class="btn btn-primary w-100">Simpan Password</button></form></details></div></div>
<form method="post" action="{{ route('mobile.bpppmnu.logout') }}">@csrf<button class="btn btn-outline-danger w-100">Keluar Akun</button></form>
@endsection
