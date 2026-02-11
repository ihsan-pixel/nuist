@extends('layouts.master')

@section('title', 'Input Data Fasilitator - Instrument Talenta')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Input Data Fasilitator</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('instumen-talenta.index') }}">Instrument Talenta</a></li>
                    <li class="breadcrumb-item active">Input Fasilitator</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Input Data Fasilitator</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('instumen-talenta.store-fasilitator') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kode_fasilitator" class="form-label">Kode Fasilitator <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kode_fasilitator" name="kode_fasilitator" value="{{ $nextKodeFasilitator ?? 'T-F-01.001' }}" readonly required>
                                        <small class="form-text text-muted">Kode fasilitator akan di-generate otomatis</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="pilih_materi" class="form-label">Pilih Materi <span class="text-danger">*</span></label>
                                        <select class="form-select" id="pilih_materi" name="pilih_materi[]" multiple required style="height: 120px;">
                                            @foreach($materis as $materi)
                                                <option value="{{ $materi->id }}">{{ $materi->judul_materi }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Tekan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu materi</small>
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
                                        Masukkan detail fasilitator. Kode fasilitator akan di-generate otomatis. Pastikan nama dan materi dipilih dengan benar.
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
                <h4 class="card-title mb-0">Data Fasilitator Terdaftar</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Fasilitator</th>
                                <th>Nama Fasilitator</th>
                                <th>Materi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\TalentaFasilitator::with('materis')->get() as $index => $fasilitator)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $fasilitator->kode_fasilitator }}</td>
                                    <td>{{ $fasilitator->nama }}</td>
                                    <td>
                                        @foreach($fasilitator->materis as $materi)
                                            <span class="badge bg-primary">
                                                {{ $materi->judul_materi }}
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data fasilitator</td>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate kode fasilitator otomatis
    generateKodeFasilitator();
});

function generateKodePeserta() {
    // Format: T-01.001, T-01.002, dst.
    // Jika belum ada data di database, mulai dari 001
    // Nomor akan diupdate ketika data berhasil terinput
    var baseCode = 'T-F-01.';
    var nextNumber = 1; // Default mulai dari 1 jika belum ada data

    // Dalam implementasi nyata, nextNumber didapatkan dari database
    // Misalnya: SELECT COUNT(*) + 1 FROM talenta_peserta

    // Format nomor dengan leading zeros (3 digit)
    var formattedNumber = nextNumber.toString().padStart(3, '0');
    var kodePeserta = baseCode + formattedNumber;

    document.getElementById('kode_peserta').value = kodePeserta;
}
</script>
@endsection
