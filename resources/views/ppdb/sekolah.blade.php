@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>{{ $ppdb->nama_sekolah }}</h3>
    <p>Periode: {{ $ppdb->tahun }}</p>
    <p>Status: <span class="badge bg-{{ $ppdb->status == 'buka' ? 'success' : 'secondary' }}">{{ ucfirst($ppdb->status) }}</span></p>

    <a href="{{ route('ppdb.daftar', $ppdb->slug) }}" class="btn btn-primary mt-3">Daftar Sekarang</a>
</div>
@endsection
