@extends('layouts.master-without-nav')

@section('title', 'Manajemen Soal')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
{{-- @include('talenta.partials.hero')
@include('talenta.navbar') --}}

<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('talenta.dashboard') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a; margin-top: 20px;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px; margin-top: 20px">Kembali</span>
</div>

<section class="section-clean">
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Manajemen Soal</h2>
                <a href="{{ route('talenta.questions.create') }}" class="btn btn-primary">Tambah Soal</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Dimensi</th>
                                    <th>Pertanyaan</th>
                                    <th>Opsi (A..E)</th>
                                    <th style="width:160px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $q)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $q->kategori }}</td>
                                    <td style="max-width:480px">{{ $q->pertanyaan }}</td>
                                    <td>
                                        @php $texts = $q->choice_texts ?? []; $scores = $q->choice_scores ?? []; @endphp
                                        <ul class="mb-0 small">
                                            @foreach(['A','B','C','D','E'] as $letter)
                                                <li><strong>{{ $letter }}</strong>. {{

                                                    (
                                                        isset($texts[$letter]) ?
                                                            \Illuminate\Support\Str::limit($texts[$letter], 60) : '-'
                                                    )
                                                }} <span class="text-muted">({{ $scores[$letter] ?? '-' }})</span></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="{{ route('talenta.questions.edit', $q) }}" class="btn btn-sm btn-outline-warning me-1">Edit</a>
                                        <form action="{{ route('talenta.questions.destroy', $q) }}" method="POST" style="display:inline-block">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $questions->links() }}
            </div>

        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm border-0 sticky-top" style="top:90px;">
                <div class="card-body">
                    <h6 class="mb-2">Statistik Soal</h6>
                    <p class="small text-muted mb-2">Total Soal: <strong>{{ $questions->total() ?? $questions->count() }}</strong></p>
                    <ul class="list-unstyled small">
                            @php
                                $cats = ['Struktur','Kompetensi','Perilaku','Keterpaduan'];
                            @endphp
                            @foreach($cats as $cat)
                                <li class="mb-1">{{ $cat }}: <strong>{{ \App\Models\Question::where('kategori', $cat)->count() }}</strong></li>
                            @endforeach
                    </ul>
                    <p class="small text-muted">Gunakan tombol Tambah Soal untuk menambah pertanyaan baru.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
