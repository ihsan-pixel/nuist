@extends('layouts.master-without-nav')

@section('title', 'Formulir PPDB ' . $ppdb->nama_sekolah)

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Formulir Pendaftaran — {{ $ppdb->nama_sekolah }}</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('ppdb.store', $ppdb->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" required>
                    @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilihan Jurusan</label>
                    <select name="jurusan_pilihan" class="form-select">
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="Akuntansi">Akuntansi</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Upload Kartu Keluarga (PDF/JPG)</label>
                        <input type="file" name="berkas_kk" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Upload Ijazah (PDF/JPG)</label>
                        <input type="file" name="berkas_ijazah" class="form-control">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-send"></i> Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
