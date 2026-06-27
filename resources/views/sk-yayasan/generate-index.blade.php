@extends('layouts.master')

@section('title')Generate SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Generate SK Yayasan @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')
@include('sk-yayasan.partials.sweet-alert')

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Generate SK Yayasan</div>
                <h4 class="mb-1">Antrean generate per sekolah</h4>
                <p class="mb-0 text-white-50">
                    Pilih nama sekolah untuk melihat daftar pengajuan SK Yayasan yang siap dibuat draft PDF sesuai template masing-masing.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $schools->total() }} sekolah</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $totalRequestsCount }} pengajuan</span>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Data Pokok SK</div>
                    <h6 class="mb-0">Metadata global untuk semua sekolah yang sudah tersinkronisasi</h6>
                </div>
                <span class="sky-chip">Global untuk seluruh antrean generate</span>
            </div>

            <form method="POST" action="{{ route('sk-yayasan.generate.settings.update') }}">
                @csrf
                @method('PATCH')
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Tahun Penerbitan SK</label>
                        <input type="text" name="sk_yayasan_school_year" class="form-control" value="{{ old('sk_yayasan_school_year', $globalSkSettings['school_year']) }}" required>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Nomor SK Mulai</label>
                        <input type="number" name="sk_yayasan_number_start" class="form-control" min="1" value="{{ old('sk_yayasan_number_start', $globalSkSettings['number_start']) }}" required>
                        <small class="text-muted">Contoh `1565` akan menghasilkan `1565/SK.02/LPM.DIY/VI/2026`.</small>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Nama Ketua Yayasan</label>
                        <input type="text" name="sk_yayasan_signer_name" class="form-control" value="{{ old('sk_yayasan_signer_name', $globalSkSettings['signer_name']) }}" required>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Jabatan Penandatangan</label>
                        <input type="text" name="sk_yayasan_signer_position" class="form-control" value="{{ old('sk_yayasan_signer_position', $globalSkSettings['signer_position']) }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Ditetapkan Di</label>
                        <input type="text" name="sk_yayasan_established_at" class="form-control" value="{{ old('sk_yayasan_established_at', $globalSkSettings['established_at']) }}" required>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Pada Tanggal Penetapan</label>
                        <input type="date" name="sk_yayasan_issued_date" class="form-control" value="{{ old('sk_yayasan_issued_date', $globalSkSettings['issued_date']) }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Format Nomor SK</label>
                        <input type="text" name="sk_yayasan_number_format_suffix" class="form-control" value="{{ old('sk_yayasan_number_format_suffix', $globalSkSettings['number_format_suffix']) }}" required>
                        <small class="text-muted">Bagian depan nomor akan diisi otomatis dari `Nomor SK Mulai` dan berlanjut global untuk semua guru.</small>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <div class="small text-muted">
                        Tembusan 1 dan 2 tetap dihitung otomatis per sekolah berdasarkan ID madrasah dan kabupaten.
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Data Pokok SK Global</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Sekolah</div>
                            <h6 class="mb-0">Klik sekolah untuk membuka daftar pengajuan</h6>
                        </div>
                        <span class="sky-chip">{{ $schools->total() }} sekolah</span>
                    </div>

                    @if($schools->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Sekolah</th>
                                        <th>SCOD</th>
                                        <th>Antrean</th>
                                        <th>Tembusan Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schools as $school)
                                        @php($coreData = $school->core_data ?? [])
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    <a href="{{ route('sk-yayasan.generate.school', $school) }}" class="text-decoration-none">
                                                        {{ $school->name }}
                                                    </a>
                                                </div>
                                                <small class="text-muted">{{ $school->kabupaten ?? 'Kabupaten belum diisi' }}</small>
                                            </td>
                                            <td>{{ $school->scod ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    {{ number_format($school->generate_requests_count) }} pengajuan
                                                </span>
                                            </td>
                                            <td class="small">
                                                <div>{{ $coreData['copy_recipient_1'] ?? '-' }}</div>
                                                <div class="text-muted mt-1">{{ $coreData['copy_recipient_2'] ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('sk-yayasan.generate.school', $school) }}" class="btn btn-sm btn-primary">
                                                    Lihat Pengajuan
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-buildings"></i>
                            <strong>Belum ada sekolah dalam antrean generate</strong>
                            <small>Sekolah akan muncul di sini setelah memiliki pengajuan yang disetujui atau batch yang sudah tersinkronisasi.</small>
                        </div>
                    @endif
                </div>

                @if($schools->hasPages())
                    <div class="card-footer bg-white">
                        {{ $schools->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Dokumen Terbit</div>
                    <h6 class="mb-3">Publikasi terbaru</h6>

                    @forelse($publishedDocuments as $document)
                        <div class="sky-document-card mb-3">
                            <div class="fw-semibold">{{ $document->document_number }}</div>
                            <div class="sky-document-meta">{{ $document->request?->employee?->name ?? '-' }} - {{ $document->request?->madrasah?->name ?? '-' }}</div>
                            <div class="small mb-3 mt-2">Terbit {{ optional($document->published_at)->format('d/m/Y H:i') }}</div>
                            <a href="{{ route('sk-yayasan.documents.download', $document) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat PDF</a>
                        </div>
                    @empty
                        <div class="sky-empty-state">
                            <i class="bx bx-printer"></i>
                            <strong>Belum ada dokumen terbit</strong>
                            <small>Dokumen yang berhasil dipublish akan tampil di panel ini.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
