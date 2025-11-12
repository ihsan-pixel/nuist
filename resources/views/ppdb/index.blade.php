@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h3>PPDB NUIST 2025</h3>
    <p>Daftar Madrasah/Sekolah yang membuka PPDB:</p>

    <div class="list-group">
        @foreach($sekolah as $item)
        <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="list-group-item list-group-item-action">
            <strong>{{ $item->nama_sekolah }}</strong> — Tahun {{ $item->tahun }}
        </a>
        @endforeach
    </div>
</div>
@endsection
