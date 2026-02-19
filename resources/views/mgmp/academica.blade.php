@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Academica - Proposal</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5>Upload Proposal PDF</h5>
            @if($userHasUploaded)
                <p class="text-muted">Anda sudah mengupload proposal. Hanya satu upload per user diperbolehkan.</p>
                @if($userProposal)
                    <p>Nama file: <a href="{{ url('/uploads/' . $userProposal->path) }}" target="_blank">{{ $userProposal->filename }}</a></p>
                @endif
            @else
                <form method="POST" action="{{ route('mgmp.academica.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="proposal">Pilih file PDF proposal</label>
                        <input type="file" name="proposal" id="proposal" accept="application/pdf" class="form-control" required>
                        @error('proposal') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-primary mt-2">Upload Proposal</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Daftar Proposal</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengupload</th>
                            <th>File</th>
                            <th>Diunggah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposals as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->user->name ?? 'User ID ' . $p->user_id }}</td>
                            <td>{{ $p->filename }}</td>
                            <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="{{ url('/uploads/' . $p->path) }}" target="_blank">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada proposal.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
