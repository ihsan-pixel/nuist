@extends('layouts.master')
@section('title', 'Agenda Kegiatan')
@section('content')
<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4"><h1 class="h3 mb-0">Agenda Kegiatan</h1><div class="d-flex gap-2"><a class="btn btn-outline-primary" href="{{ route('admin.agenda.index') }}">Semua Agenda</a><a class="btn btn-outline-primary" href="{{ route('admin.bpppmnu.members.index') }}">Data Pengurus BPPPMNU</a></div></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
@yield('bpp-content')
</div>
@endsection
