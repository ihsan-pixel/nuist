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
                    Pilih nama sekolah untuk melihat daftar pengajuan SK Yayasan yang sudah tersinkronisasi dan siap dibuat draft PDF sesuai template masing-masing. Urutan sekolah mengikuti SCOD dari yang terendah ke tertinggi.
                    </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $schools->count() }} sekolah</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $syncedBatchCount }} batch tersinkron</span>
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
                        Tembusan 1 dan 2 tetap dihitung otomatis per sekolah berdasarkan ID madrasah dan kabupaten. Gunakan antrean sekolah sesuai urutan SCOD agar penomoran global berjalan berurutan.
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Data Pokok SK Global</button>
                </div>
            </form>
            <div class="d-flex justify-content-end mt-2">
                <form method="POST" action="{{ route('sk-yayasan.generate.regenerate-all') }}">
                    @csrf
                    <button type="submit"
                            class="btn btn-outline-primary"
                            @disabled($schools->isEmpty())
                            onclick="return confirm('Generate ulang semua sekolah akan menyusun ulang nomor SK sesuai urutan SCOD. Nomor yang sudah dikunci tidak akan diubah. Lanjutkan?')">
                        Generate Ulang All
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Sekolah</div>
                            <h6 class="mb-0">Klik sekolah untuk membuka daftar pengajuan tersinkronisasi</h6>
                        </div>
                        <span class="sky-chip">{{ $schools->count() }} sekolah dari {{ $syncedBatchCount }} batch tersinkron</span>
                    </div>

                    @if($schools->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Sekolah</th>
                                        <th>SCOD</th>
                                        <th>Antrean</th>
                                        <th>Status Nomor SK</th>
                                        <th>Tembusan Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schools as $school)
                                        @php($coreData = $school->core_data ?? [])
                                        @php($generatedDocumentsCount = (int) ($school->generated_documents_count ?? 0))
                                        @php($lockedDocumentsCount = (int) ($school->locked_documents_count ?? 0))
                                        @php($readyLockCount = (int) ($school->ready_lock_count ?? 0))
                                        @php($readyLockRange = $school->ready_lock_range)
                                        @php($allGeneratedLocked = $generatedDocumentsCount > 0 && $generatedDocumentsCount === $lockedDocumentsCount)
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
                                                @if(!$numberLockSupported)
                                                    <div class="text-muted">Fitur lock menunggu migration database</div>
                                                @elseif($generatedDocumentsCount > 0)
                                                    <div class="fw-semibold text-dark">{{ $lockedDocumentsCount }}/{{ $generatedDocumentsCount }} nomor terkunci</div>
                                                    <div class="text-muted mt-1">
                                                        {{ $allGeneratedLocked ? 'Semua draft/generate sekolah ini sudah final.' : 'Nomor yang sudah dikunci tidak akan berubah saat generate ulang.' }}
                                                    </div>
                                                    @if($readyLockCount > 0 && $readyLockRange)
                                                        <div class="mt-1">
                                                            <span class="fw-semibold text-dark">Rentang siap dikunci (urut SCOD):</span>
                                                            <span class="text-muted">{{ $readyLockRange }}</span>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="text-muted">Belum ada dokumen yang digenerate</div>
                                                @endif
                                            </td>
                                            <td class="small">
                                                <div>{{ $coreData['copy_recipient_1'] ?? '-' }}</div>
                                                <div class="text-muted mt-1">{{ $coreData['copy_recipient_2'] ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="{{ route('sk-yayasan.generate.school', $school) }}" class="btn btn-sm btn-primary">
                                                        Lihat Pengajuan
                                                    </a>
                                                    <form method="POST" action="{{ route('sk-yayasan.generate.school.lock-number', $school) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-dark"
                                                                @disabled(!$numberLockSupported || $generatedDocumentsCount === 0 || $allGeneratedLocked)
                                                                onclick="return confirm('Kunci semua nomor SK yang sudah tergenerate untuk sekolah ini? Nomor yang sudah dikunci akan tetap dipakai dan tidak akan diubah saat generate ulang.')">
                                                            Kunci Nomor SK
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-buildings"></i>
                            <strong>Belum ada sekolah dengan pengajuan tersinkronisasi</strong>
                            <small>Sekolah akan muncul di sini setelah pengajuan SK Yayasannya berhasil melalui proses sinkronisasi batch.</small>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
