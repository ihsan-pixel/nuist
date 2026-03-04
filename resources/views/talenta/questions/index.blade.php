@extends('layouts.master-without-nav')

@section('title', 'Manajemen Soal')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')

<section class="section-clean">
<div class="container">
    <h2>Manajemen Soal</h2>
    <a href="{{ route('talenta.questions.create') }}" class="btn btn-primary">Tambah Soal</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Pertanyaan</th>
                <th>Skor Ya</th>
                <th>Skor Tidak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $q)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $q->kategori }}</td>
                <td>{{ $q->pertanyaan }}</td>
                <td>{{ $q->skor_ya }}</td>
                <td>{{ $q->skor_tidak }}</td>
                <td>
                    <a href="{{ route('talenta.questions.edit', $q) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('talenta.questions.destroy', $q) }}" method="POST" style="display:inline-block">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $questions->links() }}
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
