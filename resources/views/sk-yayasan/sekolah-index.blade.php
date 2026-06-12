@extends('layouts.master')

@section('title')Perpanjangan SK Yayasan @endsection

@section('css')
<link href="{{ asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Perpanjangan SK @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')

<div class="sky-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                {{-- <div class="sky-kicker mb-2">Perpanjangan SK</div> --}}
                <h4 class="mb-1">Ajukan dan pantau SK guru atau pegawai</h4>
                {{-- <p class="mb-0 text-white-50">
                    Kirim pengajuan perpanjangan SK ke Yayasan dan pantau status review sampai dokumen resmi terbit.
                </p> --}}
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $submissions->total() }} total pengajuan</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $publishedDocuments->count() }} SK terbaru</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Diajukan</div>
                <div class="h4 mb-0">{{ $statusCounts['submitted'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Direview</div>
                <div class="h4 mb-0">{{ $statusCounts['reviewed'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Disetujui</div>
                <div class="h4 mb-0">{{ $statusCounts['approved'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Terbit</div>
                <div class="h4 mb-0">{{ $statusCounts['published'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Form Pengajuan</div>
                    <h6 class="mb-3">Pilih guru/pegawai dan lengkapi berkas pengajuan</h6>
                    <p class="text-muted small mb-3">
                        Pengajuan perpanjangan SK harus menyertakan file Excel data tenaga pendidik, file Pakta integritas, dan file form penilaian perilaku kinerja pegawai.
                    </p>
                    {{-- <div class="alert alert-info py-2 px-3 small mb-3">
                        Hasil import Excel akan diparsing langsung ke database staging. Super admin mereview isi data dan lampiran pendukung sebelum memutuskan sinkronisasi atau penolakan.
                    </div> --}}
                    @if($latestSyncedImport)
                        <div class="alert alert-success py-2 px-3 small mb-3">
                            Sinkronisasi terakhir dari batch #{{ $latestSyncedImport->id }} sudah berhasil. Nama guru pada form pengajuan di bawah dipilih otomatis dari batch ini.
                        </div>
                    @endif

                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('sk-yayasan.sekolah.template-import') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-file-excel-outline me-1"></i> Download Template Import Data Tenaga Pendidik
                        </a>
                    </div>

                    <form action="{{ route('sk-yayasan.sekolah.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                <label class="form-label mb-0">Guru/Pegawai</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="select-all-employees">
                                        Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-all-employees">
                                        Kosongkan
                                    </button>
                                </div>
                            </div>
                            <select name="employee_ids[]" class="form-select select2-pegawai" multiple required data-placeholder="Pilih satu atau lebih pegawai">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(in_array($employee->id, $autoSelectedEmployeeIds ?? []))>
                                        {{ $employee->name }} - {{ $employee->statusKepegawaian?->name ?? ($employee->ketugasan ?? '-') }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Bisa pilih lebih dari satu pegawai sekaligus.
                                @if(!empty($autoSelectedEmployeeIds))
                                    Data hasil import terakhir sudah dipilih otomatis.
                                @endif
                            </small>
                            @error('employee_ids')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                            @error('employee_ids.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Excel Data Tenaga Pendidik</label>
                            <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">Format: XLSX, XLS, atau CSV.</small>
                            @error('excel_file')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Pakta Integritas</label>
                            <input type="file" name="fakta_integritas_file" class="form-control" accept=".pdf,application/pdf" required>
                            <small class="text-muted">Format: PDF.</small>
                            @error('fakta_integritas_file')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Form Penilaian Perilaku Kinerja Pegawai</label>
                            <input type="file" name="penilaian_perilaku_file" class="form-control" accept=".pdf,application/pdf" required>
                            <small class="text-muted">Format: PDF.</small>
                            @error('penilaian_perilaku_file')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Pengajuan</button>
                    </form>

                    @if($importBatches->isNotEmpty())
                        <div class="border-top pt-3">
                            <div class="sky-panel-label mb-1">Riwayat Upload</div>
                            <h6 class="mb-3">Status review dan sinkronisasi file</h6>

                            @foreach($importBatches as $batch)
                                @php
                                    $batchColor = $batch->status === 'synced' ? 'success' : ($batch->status === 'rejected' ? 'danger' : 'warning');
                                @endphp
                                <div class="sky-document-card mb-3">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                                        <div>
                                            <div class="fw-semibold">Batch #{{ $batch->id }} - {{ $batch->original_filename }}</div>
                                            <div class="sky-document-meta">
                                                Upload {{ optional($batch->uploaded_at)->format('d/m/Y H:i') }} |
                                                {{ $batch->valid_rows }} valid / {{ $batch->invalid_rows }} perlu cek
                                            </div>
                                        </div>
                                        <span class="badge bg-{{ $batchColor }}-subtle text-{{ $batchColor }} text-uppercase">
                                            {{ str_replace('_', ' ', $batch->status) }}
                                        </span>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$batch, 'excel']) }}" class="btn btn-sm btn-outline-primary">Excel</a>
                                        <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$batch, 'fakta_integritas']) }}" class="btn btn-sm btn-outline-primary">Pakta Integritas</a>
                                        <a href="{{ route('sk-yayasan.import-batches.attachments.download', [$batch, 'penilaian_perilaku']) }}" class="btn btn-sm btn-outline-primary">Penilaian Perilaku</a>
                                    </div>

                                    @if(!$batch->headings_valid)
                                        <div class="small text-danger mb-2">
                                            Format kolom tidak sesuai template.
                                            @if(!empty($batch->missing_headings))
                                                Kolom kurang: {{ implode(', ', $batch->missing_headings) }}.
                                            @endif
                                        </div>
                                    @endif

                                    @if($batch->review_notes)
                                        <div class="small mb-2"><strong>Catatan review:</strong> {{ $batch->review_notes }}</div>
                                    @endif

                                    @if($batch->reviewer)
                                        <div class="small text-muted">Direview oleh {{ $batch->reviewer->name }} pada {{ optional($batch->reviewed_at)->format('d/m/Y H:i') }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Riwayat Pengajuan</div>
                            <h6 class="mb-0">Status dan perkembangan pengajuan</h6>
                        </div>
                        <form method="GET" class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua status</option>
                                @foreach(['submitted' => 'Diajukan', 'reviewed' => 'Direview', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'published' => 'Terbit'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                        </form>
                    </div>

                    @if($submissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No Pengajuan</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Catatan Review</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td class="fw-semibold">{{ $submission->request_number }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $submission->employee?->name ?? '-' }}</div>
                                                <small class="text-muted">{{ $submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? '-') }}</small>
                                                @if($submission->importBatch)
                                                    <div><small class="text-muted">Batch #{{ $submission->importBatch->id }}</small></div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary text-uppercase">{{ $submission->current_status }}</span>
                                            </td>
                                            <td>{{ $submission->review_notes ?? '-' }}</td>
                                            <td>
                                                @if($submission->document)
                                                    <a href="{{ route('sk-yayasan.documents.download', $submission->document) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        Unduh SK
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Menunggu proses Yayasan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-task-x"></i>
                            <strong>Belum ada pengajuan perpanjangan SK</strong>
                            <small>Silakan kirim pengajuan pertama Anda melalui form di sebelah kiri.</small>
                        </div>
                    @endif
                </div>

                @if($submissions->hasPages())
                    <div class="card-footer bg-white">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        const $employeeSelect = $('.select2-pegawai');

        $employeeSelect.select2({
            width: '100%',
            placeholder: $employeeSelect.data('placeholder'),
            closeOnSelect: false
        });

        $('#select-all-employees').on('click', function () {
            const allValues = $employeeSelect.find('option').map(function () {
                return $(this).val();
            }).get();

            $employeeSelect.val(allValues).trigger('change');
        });

        $('#clear-all-employees').on('click', function () {
            $employeeSelect.val(null).trigger('change');
        });
    });
</script>
@endsection
