@extends('layouts.master')

@section('title', 'Input Data Peserta - Instrument Talenta')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Input Data Peserta</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('instumen-talenta.index') }}">Instrument Talenta</a></li>
                    <li class="breadcrumb-item active">Input Peserta</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Input Data Peserta</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('instumen-talenta.store-peserta') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kode_peserta" class="form-label">Kode Peserta <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kode_peserta" name="kode_peserta" value="{{ $nextKodePeserta ?? 'T-01.001' }}" readonly required>
                                        <small class="form-text text-muted">Kode peserta akan di-generate otomatis</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Nama Peserta <span class="text-danger">*</span></label>
                                        <select class="form-select" id="user_id" name="user_id" required>
                                            <option value="">Pilih Peserta</option>
                                            @foreach(
                                                \App\Models\User::where('role', 'tenaga_pendidik')
                                                    ->with('madrasah')
                                                    ->orderBy('name', 'asc') // ← tambahkan ini
                                                    ->get() as $user
                                            )
                                                <option value="{{ $user->id }}" data-madrasah="{{ $user->madrasah->name ?? 'N/A' }}">
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="asal_sekolah" class="form-label">Asal Sekolah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" readonly required>
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
                                        Pilih nama peserta dari daftar tenaga pendidik yang tersedia. Asal sekolah akan terisi otomatis berdasarkan data madrasah pengguna tersebut.
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

<!-- Form Pembuatan Kelompok -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Pembuatan Kelompok Peserta</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('instumen-talenta.store-kelompok') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kelompok" class="form-label">Nama Kelompok <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_kelompok" name="nama_kelompok" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_ids" class="form-label">Pilih Peserta <span class="text-danger">*</span></label>
                                        <select class="form-select" id="user_ids" name="user_ids[]" multiple required>
                                            @foreach($pesertas as $peserta)
                                                <option value="{{ $peserta->user_id }}">{{ $peserta->user->name ?? 'N/A' }} - {{ $peserta->kode_peserta }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Tekan Ctrl (Windows) atau Cmd (Mac) untuk memilih multiple peserta</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-users me-1"></i> Buat Kelompok
                                </button>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-users me-2"></i>Informasi Kelompok</h6>
                                    <p class="text-muted mb-0 small">
                                        Buat kelompok peserta dengan memilih nama kelompok dan peserta yang akan dimasukkan ke dalam kelompok tersebut.
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
                <h4 class="card-title mb-0">Data Peserta Terdaftar</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Peserta</th>
                                <th>Nama Peserta</th>
                                <th>Asal Sekolah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesertas as $index => $peserta)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $peserta->kode_peserta }}</td>
                                    <td>{{ $peserta->user->name ?? 'N/A' }}</td>
                                    <td>{{ $peserta->asal_sekolah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data peserta</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Data Kelompok Peserta</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kelompok</th>
                                <th>Jumlah Peserta</th>
                                <th>Daftar Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompoks as $index => $kelompok)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $kelompok->nama_kelompok }}</td>
                                    <td>{{ $kelompok->users->count() }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($kelompok->users as $user)
                                                <li>{{ $user->name ?? 'N/A' }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data kelompok</td>
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
    // Event listener untuk user_id change
    document.getElementById('user_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var asalSekolah = selectedOption.getAttribute('data-madrasah');
        document.getElementById('asal_sekolah').value = asalSekolah || '';
    });

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

// Kode peserta sekarang di-generate dari server-side
// Tidak perlu JavaScript untuk generate kode peserta
</script>
@endsection
