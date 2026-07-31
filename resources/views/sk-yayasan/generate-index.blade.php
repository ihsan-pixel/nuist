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

    @if($uppmValidationEnabled)
        <div class="alert alert-info border-0 shadow-sm">
            Antrean generate saat ini hanya menampilkan sekolah yang sudah <strong>lunas UPPM periode {{ $uppmValidationPeriodLabel }} tahun {{ $uppmValidationYear }}</strong>.
            @if($uppmBlockedSchoolCount > 0)
                <span class="d-block mt-1">{{ number_format($uppmBlockedSchoolCount) }} sekolah tersinkron yang belum lunas ditampilkan pada div terpisah di bawah dan tetap bisa diproses generate dari sana.</span>
            @endif
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <div class="sky-panel-label mb-1">Pengaturan Nomor Dipindah</div>
                    <h6 class="mb-1">Manajemen nomor SK sekarang dipusatkan di menu Nomor SK Yayasan</h6>
                    <p class="text-muted mb-0">
                        Setting nomor mulai, format nomor, rapikan nomor, hingga kunci nomor per sekolah sekarang dikelola dari satu halaman agar lebih rapi dan tidak tersebar.
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('sk-yayasan.numbers.index') }}" class="btn btn-primary">
                        <i class="bx bx-hash me-1"></i>Buka Nomor SK Yayasan
                    </a>
                </div>
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
                        <span class="sky-chip">{{ $schools->count() }} sekolah dari {{ $syncedSchoolCount }} sekolah tersinkron</span>
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
                                        <th>Nomor Surat Pengajuan</th>
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
                                        @php($storedNumberSummary = $school->stored_number_summary ?? null)
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
                                                    @if($storedNumberSummary && $storedNumberSummary['range_label'])
                                                        <div class="mt-1">
                                                            <span class="fw-semibold text-dark">Rentang tersimpan:</span>
                                                            <span class="text-muted">{{ $storedNumberSummary['range_label'] }}/{{ $storedNumberSummary['status_label'] }}</span>
                                                        </div>
                                                        <div class="mt-1 {{ ($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                                            <span class="fw-semibold">Status duplikat:</span>
                                                            <span>{{ ($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'ada (' . $storedNumberSummary['duplicate_count'] . ' data)' : 'tidak ada' }}</span>
                                                        </div>
                                                        @if($storedNumberSummary['validation_note'])
                                                            <div class="mt-1 text-success">
                                                                <span class="fw-semibold">Validasi:</span>
                                                                <span>{{ $storedNumberSummary['validation_note'] }}</span>
                                                            </div>
                                                        @endif
                                                        @if(!$storedNumberSummary['is_sequential'] && $storedNumberSummary['missing_preview'])
                                                            <div class="mt-1 text-danger">
                                                                <span class="fw-semibold">Nomor loncat:</span>
                                                                <span>{{ $storedNumberSummary['missing_preview'] }}</span>
                                                            </div>
                                                        @endif
                                                    @endif
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
                                            <td class="small">{{ $school->submission_letter_reference['submission_letter_number'] ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="{{ route('sk-yayasan.generate.school', $school) }}" class="btn btn-sm btn-primary">
                                                        Lihat Pengajuan
                                                    </a>
                                                    <a href="{{ route('sk-yayasan.numbers.index', ['madrasah_id' => $school->id]) }}#document-list" class="btn btn-sm btn-outline-primary">
                                                        Kelola Nomor
                                                    </a>
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

        @if($uppmValidationEnabled)
            <div class="col-12">
                <div class="card border-warning-subtle">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="sky-panel-label mb-1">Antrean Generate Belum Lunas</div>
                                <h6 class="mb-0">Sekolah sudah mengajukan, tetapi status UPPM periode {{ $uppmValidationPeriodLabel }} belum lunas</h6>
                            </div>
                            <span class="sky-chip">{{ $blockedSchools->count() }} sekolah</span>
                        </div>

                        @if($blockedSchools->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nama Sekolah</th>
                                            <th>SCOD</th>
                                            <th>Antrean</th>
                                            <th>Status Nomor SK</th>
                                            <th>Nomor Surat Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($blockedSchools as $school)
                                            @php($generatedDocumentsCount = (int) ($school->generated_documents_count ?? 0))
                                            @php($lockedDocumentsCount = (int) ($school->locked_documents_count ?? 0))
                                            @php($readyLockCount = (int) ($school->ready_lock_count ?? 0))
                                            @php($readyLockRange = $school->ready_lock_range)
                                            @php($storedNumberSummary = $school->stored_number_summary ?? null)
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
                                                    <span class="badge bg-warning-subtle text-warning">
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
                                                        @if($storedNumberSummary && $storedNumberSummary['range_label'])
                                                            <div class="mt-1">
                                                                <span class="fw-semibold text-dark">Rentang tersimpan:</span>
                                                                <span class="text-muted">{{ $storedNumberSummary['range_label'] }}/{{ $storedNumberSummary['status_label'] }}</span>
                                                            </div>
                                                            <div class="mt-1 {{ ($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                                                <span class="fw-semibold">Status duplikat:</span>
                                                                <span>{{ ($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'ada (' . $storedNumberSummary['duplicate_count'] . ' data)' : 'tidak ada' }}</span>
                                                            </div>
                                                            @if($storedNumberSummary['validation_note'])
                                                                <div class="mt-1 text-success">
                                                                    <span class="fw-semibold">Validasi:</span>
                                                                    <span>{{ $storedNumberSummary['validation_note'] }}</span>
                                                                </div>
                                                            @endif
                                                            @if(!$storedNumberSummary['is_sequential'] && $storedNumberSummary['missing_preview'])
                                                                <div class="mt-1 text-danger">
                                                                    <span class="fw-semibold">Nomor loncat:</span>
                                                                    <span>{{ $storedNumberSummary['missing_preview'] }}</span>
                                                                </div>
                                                            @endif
                                                        @endif
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
                                                <td class="small">{{ $school->submission_letter_reference['submission_letter_number'] ?? '-' }}</td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="{{ route('sk-yayasan.generate.school', $school) }}" class="btn btn-sm btn-warning">
                                                            Lihat Pengajuan
                                                        </a>
                                                        <a href="{{ route('sk-yayasan.numbers.index', ['madrasah_id' => $school->id]) }}#document-list" class="btn btn-sm btn-outline-primary">
                                                            Kelola Nomor
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="sky-empty-state py-5">
                                <i class="bx bx-check-shield"></i>
                                <strong>Semua sekolah tersinkron pada periode ini sudah lunas</strong>
                                <small>Tidak ada antrean generate terpisah untuk sekolah belum lunas.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Guru Pengangkatan</div>
                            <h6 class="mb-0">Daftar pengajuan dengan keterangan Pengangkatan PTY dan Pengangkatan GTY dengan TMT 2 tahun ke atas</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip">{{ $appointmentRequests->count() }} pengajuan</span>
                            <span class="sky-chip">{{ $appointmentRequests->where('nipm_validated', false)->count() }} belum tervalidasi</span>
                        </div>
                    </div>

                    @php($appointmentRows = $appointmentRequests->values())
                    @if($appointmentRequests->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan</th>
                                        <th>NIPM Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointmentRows as $appointmentData)
                                        @php($teacherId = data_get($appointmentData, 'teacher_id'))
                                        @php($nipmSynced = (bool) data_get($appointmentData, 'nipm_synced', false))
                                        @php($nipmValidated = (bool) data_get($appointmentData, 'nipm_validated', false))
                                        @php($selectedMode = $nipmSynced ? 'system' : old('rows.' . $teacherId . '.nipm_mode', data_get($appointmentData, 'default_nipm_mode', 'system')))
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ data_get($appointmentData, 'submission_year', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'school_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'teacher_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tmt_label', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tenure_label', '-') }}</td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">{{ data_get($appointmentData, 'keterangan', '-') }}</span>
                                                @if(data_get($appointmentData, 'rejection_keterangan'))
                                                    <small class="text-muted d-block mt-1">
                                                        Jika ditolak: <strong>{{ data_get($appointmentData, 'rejection_keterangan') }}</strong>
                                                    </small>
                                                @endif
                                            </td>
                                            <td style="min-width: 280px;">
                                                <form id="appointment-nipm-sync-{{ $teacherId }}" method="POST" action="{{ route('sk-yayasan.generate.appointment-nipm-sync') }}" class="d-none">
                                                    @csrf
                                                </form>
                                                <input type="hidden"
                                                       id="appointment-decision-{{ $teacherId }}"
                                                       form="appointment-nipm-sync-{{ $teacherId }}"
                                                       name="rows[{{ $teacherId }}][decision]"
                                                       value="approve">
                                                <input type="hidden"
                                                       form="appointment-nipm-sync-{{ $teacherId }}"
                                                       name="rows[{{ $teacherId }}][teacher_id]"
                                                       value="{{ $teacherId }}">
                                                @if(!$nipmSynced && data_get($appointmentData, 'has_nipm_source_choice', false))
                                                    <select name="rows[{{ data_get($appointmentData, 'teacher_id') }}][nipm_mode]"
                                                            form="appointment-nipm-sync-{{ $teacherId }}"
                                                            class="form-select form-select-sm mb-2 js-nipm-mode"
                                                            data-existing-nipm="{{ data_get($appointmentData, 'existing_nipm_value', '') }}"
                                                            data-system-nipm="{{ data_get($appointmentData, 'system_nipm_value', '') }}">
                                                        <option value="existing" @selected($selectedMode === 'existing')>Gunakan NIPM yang ada</option>
                                                        <option value="system" @selected($selectedMode === 'system')>Gunakan NIPM sistem</option>
                                                    </select>
                                                @else
                                                    <input type="hidden"
                                                           form="appointment-nipm-sync-{{ $teacherId }}"
                                                           name="rows[{{ $teacherId }}][nipm_mode]"
                                                           value="{{ $nipmSynced ? 'system' : $selectedMode }}">
                                                @endif
                                                <input type="text"
                                                       form="appointment-nipm-sync-{{ $teacherId }}"
                                                       name="rows[{{ $teacherId }}][nipm]"
                                                       class="form-control form-control-sm js-nipm-input {{ $nipmValidated ? 'border-success bg-success-subtle text-success-emphasis' : '' }}"
                                                       value="{{ old('rows.' . $teacherId . '.nipm', data_get($appointmentData, 'nipm_value', '')) }}"
                                                       placeholder="NIPM otomatis"
                                                       inputmode="numeric"
                                                       data-existing-nipm="{{ data_get($appointmentData, 'existing_nipm_value', '') }}"
                                                       data-system-nipm="{{ data_get($appointmentData, 'system_nipm_value', '') }}"
                                                       @readonly($nipmSynced || $selectedMode === 'existing')>
                                                @if($nipmValidated)
                                                    <small class="text-success d-block mt-1 fw-semibold">NIPM tervalidasi</small>
                                                @endif
                                            </td>
                                            <td style="width: 180px;">
                                                <div class="d-grid gap-2">
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-{{ $teacherId }}"
                                                            class="btn btn-sm btn-outline-danger w-100"
                                                            onclick="document.getElementById('appointment-decision-{{ $teacherId }}').value='reject'">
                                                        Tolak
                                                    </button>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-{{ $teacherId }}"
                                                            class="btn btn-sm {{ $nipmValidated ? 'btn-outline-success' : 'btn-primary' }} w-100"
                                                            onclick="document.getElementById('appointment-decision-{{ $teacherId }}').value='approve'">
                                                        {{ $nipmValidated ? 'Setujui Ulang' : 'Setujui' }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-table"></i>
                            <strong>Belum ada data pengangkatan PTY/GTY</strong>
                            <small>Data akan muncul di sini jika ada pengajuan tersinkronisasi dengan keterangan Pengangkatan PTY atau Pengangkatan GTY.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Guru Pengangkatan</div>
                            <h6 class="mb-0">Daftar pengajuan dengan TMT di bawah 2 tahun</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip">{{ $appointmentRequestsUnderTwoYears->count() }} pengajuan</span>
                            <span class="sky-chip">{{ $appointmentRequestsUnderTwoYears->where('nipm_validated', false)->count() }} belum tervalidasi</span>
                        </div>
                    </div>

                    @php($appointmentRowsUnderTwoYears = $appointmentRequestsUnderTwoYears->values())
                    @if($appointmentRequestsUnderTwoYears->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan</th>
                                        <th>NIPM Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointmentRowsUnderTwoYears as $appointmentData)
                                        @php($teacherId = data_get($appointmentData, 'teacher_id'))
                                        @php($nipmSynced = (bool) data_get($appointmentData, 'nipm_synced', false))
                                        @php($nipmValidated = (bool) data_get($appointmentData, 'nipm_validated', false))
                                        @php($selectedMode = $nipmSynced ? 'system' : old('rows.' . $teacherId . '.nipm_mode', data_get($appointmentData, 'default_nipm_mode', 'system')))
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ data_get($appointmentData, 'submission_year', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'school_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'teacher_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tmt_label', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tenure_label', '-') }}</td>
                                            <td>
                                                <span class="badge bg-warning-subtle text-warning">{{ data_get($appointmentData, 'keterangan', '-') }}</span>
                                                @if(data_get($appointmentData, 'rejection_keterangan'))
                                                    <small class="text-muted d-block mt-1">
                                                        Jika ditolak: <strong>{{ data_get($appointmentData, 'rejection_keterangan') }}</strong>
                                                    </small>
                                                @endif
                                            </td>
                                            <td style="min-width: 280px;">
                                                <form id="appointment-nipm-sync-under-two-years-{{ $teacherId }}" method="POST" action="{{ route('sk-yayasan.generate.appointment-nipm-sync') }}" class="d-none">
                                                    @csrf
                                                </form>
                                                <input type="hidden"
                                                       id="appointment-decision-under-two-years-{{ $teacherId }}"
                                                       form="appointment-nipm-sync-under-two-years-{{ $teacherId }}"
                                                       name="rows[{{ $teacherId }}][decision]"
                                                       value="approve">
                                                <input type="hidden"
                                                       form="appointment-nipm-sync-under-two-years-{{ $teacherId }}"
                                                       name="rows[{{ $teacherId }}][teacher_id]"
                                                       value="{{ $teacherId }}">
                                                @if(!$nipmSynced && data_get($appointmentData, 'has_nipm_source_choice', false))
                                                    <select name="rows[{{ data_get($appointmentData, 'teacher_id') }}][nipm_mode]"
                                                            form="appointment-nipm-sync-under-two-years-{{ $teacherId }}"
                                                            class="form-select form-select-sm mb-2 js-nipm-mode"
                                                            data-existing-nipm="{{ data_get($appointmentData, 'existing_nipm_value', '') }}"
                                                            data-system-nipm="{{ data_get($appointmentData, 'system_nipm_value', '') }}">
                                                        <option value="existing" @selected($selectedMode === 'existing')>Gunakan NIPM yang ada</option>
                                                        <option value="system" @selected($selectedMode === 'system')>Gunakan NIPM sistem</option>
                                                    </select>
                                                @else
                                                    <input type="hidden"
                                                           form="appointment-nipm-sync-under-two-years-{{ $teacherId }}"
                                                           name="rows[{{ $teacherId }}][nipm_mode]"
                                                           value="{{ $nipmSynced ? 'system' : $selectedMode }}">
                                                @endif
                                                <input type="text"
                                                       form="appointment-nipm-sync-under-two-years-{{ $teacherId }}"
                                                       name="rows[{{ $teacherId }}][nipm]"
                                                       class="form-control form-control-sm js-nipm-input {{ $nipmValidated ? 'border-success bg-success-subtle text-success-emphasis' : '' }}"
                                                       value="{{ old('rows.' . $teacherId . '.nipm', data_get($appointmentData, 'nipm_value', '')) }}"
                                                       placeholder="NIPM otomatis"
                                                       inputmode="numeric"
                                                       data-existing-nipm="{{ data_get($appointmentData, 'existing_nipm_value', '') }}"
                                                       data-system-nipm="{{ data_get($appointmentData, 'system_nipm_value', '') }}"
                                                       @readonly($nipmSynced || $selectedMode === 'existing')>
                                                @if($nipmValidated)
                                                    <small class="text-success d-block mt-1 fw-semibold">NIPM tervalidasi</small>
                                                @endif
                                            </td>
                                            <td style="width: 180px;">
                                                <div class="d-grid gap-2">
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-under-two-years-{{ $teacherId }}"
                                                            class="btn btn-sm btn-outline-danger w-100"
                                                            onclick="document.getElementById('appointment-decision-under-two-years-{{ $teacherId }}').value='reject'">
                                                        Tolak
                                                    </button>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-under-two-years-{{ $teacherId }}"
                                                            class="btn btn-sm {{ $nipmValidated ? 'btn-outline-success' : 'btn-primary' }} w-100"
                                                            onclick="document.getElementById('appointment-decision-under-two-years-{{ $teacherId }}').value='approve'">
                                                        {{ $nipmValidated ? 'Setujui Ulang' : 'Setujui' }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-table"></i>
                            <strong>Belum ada data guru pengangkatan dengan TMT di bawah 2 tahun</strong>
                            <small>Jika ada pengajuan Pengangkatan PTY atau GTY dengan TMT kurang dari 2 tahun, datanya akan tampil di sini.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Sudah Disetujui</div>
                            <h6 class="mb-0">Daftar guru pengangkatan yang NIPM-nya sudah divalidasi</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip">{{ $approvedAppointmentRequests->count() }} data</span>
                        </div>
                    </div>

                    @if($approvedAppointmentRequests->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan</th>
                                        <th>NIPM</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedAppointmentRequests as $appointmentData)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ data_get($appointmentData, 'submission_year', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'school_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'teacher_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tmt_label', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tenure_label', '-') }}</td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">{{ data_get($appointmentData, 'keterangan', '-') }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-semibold">{{ data_get($appointmentData, 'nipm_value', '-') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">Disetujui</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-check-circle"></i>
                            <strong>Belum ada data yang disetujui</strong>
                            <small>Guru yang sudah disetujui akan tetap tampil di sini sebagai riwayat validasi NIPM.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Sudah Ditolak</div>
                            <h6 class="mb-0">Daftar guru pengangkatan yang dialihkan ke keterangan GTT/PTT</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip">{{ $rejectedAppointmentRequests->count() }} data</span>
                        </div>
                    </div>

                    @if($rejectedAppointmentRequests->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan Pengajuan</th>
                                        <th>Keterangan Setelah Ditolak</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedAppointmentRequests as $appointmentData)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ data_get($appointmentData, 'submission_year', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'school_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'teacher_name', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tmt_label', '-') }}</td>
                                            <td>{{ data_get($appointmentData, 'tenure_label', '-') }}</td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">{{ data_get($appointmentData, 'keterangan', '-') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger">{{ data_get($appointmentData, 'rejection_keterangan', '-') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-x-circle"></i>
                            <strong>Belum ada data yang ditolak</strong>
                            <small>Guru yang ditolak dari antrean pengangkatan akan tetap tampil di sini dengan hasil keterangan GTT/PTT.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-nipm-mode').forEach(function (select) {
        var input = select.parentElement.querySelector('.js-nipm-input');
        if (!input) {
            return;
        }

        var applyMode = function () {
            var useExisting = select.value === 'existing';
            input.value = useExisting
                ? (select.dataset.existingNipm || '')
                : (select.dataset.systemNipm || '');
            input.readOnly = useExisting;
        };

        select.addEventListener('change', applyMode);
        applyMode();
    });
});
</script>
@endsection
