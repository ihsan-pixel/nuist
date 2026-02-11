@extends('layouts.master')

@section('title', 'Input Data Layanan Teknis - Instrument Talenta')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Input Data Layanan Teknis</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('instumen-talenta.index') }}">Instrument Talenta</a></li>
                    <li class="breadcrumb-item active">Input Layanan Teknis</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Input Data Layanan Teknis</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('instumen-talenta.store-layanan-teknis') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kode_layanan_teknis" class="form-label">Kode Layanan Teknis <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kode_layanan_teknis" name="kode_layanan_teknis" value="{{ $nextKodeLayananTeknis ?? 'LT-01.001' }}" readonly required>
                                        <small class="form-text text-muted">Kode layanan teknis akan di-generate otomatis</small>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nama_layanan_teknis" class="form-label">Nama Layanan Teknis <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_layanan_teknis" name="nama_layanan_teknis" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="tugas_layanan_teknis" class="form-label">Tugas Layanan Teknis <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="tugas_layanan_teknis" name="tugas_layanan_teknis" rows="4" required></textarea>
                                        <small class="form-text text-muted">Deskripsikan tugas yang akan dilakukan oleh layanan teknis ini</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('instumen-talenta.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Data
                                </button>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                                    <p class="text-muted mb-0 small">
                                        Masukkan detail layanan teknis. Kode layanan teknis akan di-generate otomatis. Pastikan nama dan tugas layanan teknis diisi dengan benar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Data Layanan Teknis Terdaftar</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Layanan Teknis</th>
                                <th>Nama Layanan Teknis</th>
                                <th>Tugas Layanan Teknis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\TalentaLayananTeknis::all() as $index => $layanan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $layanan->kode_layanan_teknis }}</td>
                                    <td>{{ $layanan->nama_layanan_teknis }}</td>
                                    <td>{{ Str::limit($layanan->tugas_layanan_teknis, 100) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data layanan teknis</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/instumen-talenta.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert untuk pesan sukses
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
@endsection
