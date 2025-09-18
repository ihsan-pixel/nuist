@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Upload Surat Izin</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="surat_izin">Upload Surat Izin (PDF, JPG, PNG)</label>
            <input type="file" name="surat_izin" id="surat_izin" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
@endsection
