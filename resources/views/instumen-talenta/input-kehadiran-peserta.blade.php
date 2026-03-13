@extends('layouts.master')

@section('title', 'Input Kehadiran Peserta - Instrument Talenta')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Input Kehadiran Peserta Talenta</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('instumen-talenta.index') }}">Instrument Talenta</a></li>
                    <li class="breadcrumb-item active">Input Kehadiran Peserta</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="alert alert-info mb-4">
            Anda dapat mencatat peserta dengan status <strong>hadir</strong>, <strong>izin</strong>, <strong>sakit</strong>, <strong>telat</strong>, atau lainnya per materi. Jika peserta tidak tercatat, sistem tetap menganggap peserta <strong>hadir pada semua sesi</strong>.
        </div>
    </div>
</div>

@if(session('import_errors'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-warning">
                <strong>Beberapa baris gagal diimport:</strong>
                <ul class="mb-0 mt-2">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Input Kehadiran</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('instumen-talenta.store-kehadiran-peserta') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $formDate) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="talenta_peserta_id" class="form-label">Peserta <span class="text-danger">*</span></label>
                        <select class="form-select @error('talenta_peserta_id') is-invalid @enderror" id="talenta_peserta_id" name="talenta_peserta_id" required>
                            <option value="">Pilih peserta</option>
                            @foreach($pesertas as $peserta)
                                <option value="{{ $peserta->id }}" {{ old('talenta_peserta_id') == $peserta->id ? 'selected' : '' }}>
                                    {{ $peserta->user->name ?? 'N/A' }} - {{ $peserta->asal_sekolah }}
                                </option>
                            @endforeach
                        </select>
                        @error('talenta_peserta_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="materi_id" class="form-label">Materi <span class="text-danger">*</span></label>
                        @if($supportsMateri)
                            <select class="form-select @error('materi_id') is-invalid @enderror" id="materi_id" name="materi_id" required>
                                <option value="">Pilih materi</option>
                                @foreach($materis as $materi)
                                    <option value="{{ $materi->id }}" {{ old('materi_id', $selectedMateriId) == $materi->id ? 'selected' : '' }}>
                                        {{ $materi->judul_materi }} @if($materi->tanggal_materi) - {{ $materi->tanggal_materi->format('d-m-Y') }} @endif
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="Kolom materi_id belum ada di database. Jalankan migrate terlebih dahulu." readonly>
                        @endif
                        @error('materi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status_kehadiran" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status_kehadiran') is-invalid @enderror" id="status_kehadiran" name="status_kehadiran" required>
                            <option value="">Pilih status</option>
                            <option value="hadir" {{ old('status_kehadiran') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="telat" {{ old('status_kehadiran') === 'telat' ? 'selected' : '' }}>Telat</option>
                            <option value="izin" {{ old('status_kehadiran') === 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ old('status_kehadiran') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="tidak_hadir" {{ old('status_kehadiran') === 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                            <option value="lainnya" {{ old('status_kehadiran') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('status_kehadiran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Sesi Terdampak <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            @foreach(['1', '2', '3', '4'] as $sesi)
                                <div class="col-6">
                                    <div class="form-check border rounded px-3 py-2">
                                        <input class="form-check-input" type="checkbox" name="sesi[]" value="{{ $sesi }}" id="sesi_{{ $sesi }}" {{ in_array($sesi, old('sesi', []), true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sesi_{{ $sesi }}">
                                            Sesi {{ $sesi }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('sesi')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('sesi.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="catatan" class="form-label">Catatan Tambahan</label>
                        <input type="text" class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" value="{{ old('catatan') }}" placeholder="Contoh: datang pukul 08.30">
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('instumen-talenta.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="reset" class="btn btn-outline-danger">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title mb-0">Import Excel Kehadiran</h4>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('instumen-talenta.template-kehadiran-peserta') }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i> Download Template Excel
                    </a>
                </div>

                <form action="{{ route('instumen-talenta.import-kehadiran-peserta') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">File Excel / CSV</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Import File
                    </button>
                </form>

                <div class="mt-3 text-muted small">
                    Gunakan sheet <strong>template_import</strong> pada file template. Referensi peserta dan materi tersedia di sheet terpisah.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4 class="card-title mb-0">Rekap Kehadiran Peserta</h4>
                <form method="GET" action="{{ route('instumen-talenta.input-kehadiran-peserta') }}" class="d-flex gap-2">
                    <input type="date" name="tanggal" value="{{ $selectedDate ?? '' }}" class="form-control form-control-sm">
                    @if($supportsMateri)
                        <select name="materi_id" class="form-select form-select-sm">
                            <option value="">Semua materi</option>
                            @foreach($materis as $materi)
                                <option value="{{ $materi->id }}" {{ (string) $selectedMateriId === (string) $materi->id ? 'selected' : '' }}>
                                    {{ $materi->judul_materi }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('instumen-talenta.input-kehadiran-peserta') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </form>
            </div>
            <div class="card-body">
                <div class="mb-3 text-muted small">
                    @if($selectedDate || $selectedMateriId)
                        Menampilkan data sesuai filter
                        @if($selectedDate)
                            tanggal <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</strong>
                        @endif
                        @if($selectedMateriId && $supportsMateri)
                            dan materi <strong>{{ optional($materis->firstWhere('id', (int) $selectedMateriId))->judul_materi ?? $selectedMateriId }}</strong>
                        @endif
                        .
                    @else
                        Menampilkan seluruh data kehadiran peserta yang tersimpan di database.
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Hari, tanggal</th>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Asal Sekolah/Madrasah</th>
                                @if($supportsMateri)
                                    <th>Materi</th>
                                @endif
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kehadiranPesertas as $index => $item)
                                <tr>
                                    <td>
                                        {{ $item->nama_hari }},
                                        {{ optional($item->tanggal)->format('d-m-Y') }}
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->peserta->user->name ?? 'N/A' }}</td>
                                    <td>{{ $item->peserta->user->madrasah->name ?? $item->peserta->asal_sekolah }}</td>
                                    @if($supportsMateri)
                                        <td>{{ $item->materi->judul_materi ?? '-' }}</td>
                                    @endif
                                    <td>{{ $item->keterangan_label }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('instumen-talenta.delete-kehadiran-peserta', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data kehadiran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $supportsMateri ? '7' : '6' }}" class="text-center py-4">
                                        Belum ada data kehadiran yang tersimpan{{ ($selectedDate || $selectedMateriId) ? ' pada filter ini' : '' }}.<br>
                                        <small class="text-muted">Peserta yang tidak dicatat tetap dianggap hadir di semua sesi.</small>
                                    </td>
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
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Perhatian',
            text: '{{ session('error') }}',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
@endsection
