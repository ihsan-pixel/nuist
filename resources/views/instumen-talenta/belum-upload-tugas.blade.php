@extends('layouts.master')

@section('title', 'Rekap Peserta Belum Upload Tugas')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h4 class="mb-1">Rekap Peserta Talenta Belum Upload Tugas</h4>
                    <p class="text-muted mb-0">Setiap baris mewakili satu peserta. Kolom materi menampilkan detail tugas yang belum diunggah agar monitoring lebih rapi dan mudah ditindaklanjuti.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('instumen-talenta.upload-tugas', ['area' => $selectedArea]) }}" class="btn btn-outline-secondary">
                        Kembali ke Upload Tugas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase mb-2">Total Peserta Talenta</div>
                    <div class="fs-3 fw-semibold">{{ number_format($summary['total_peserta']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase mb-2">Belum Lengkap</div>
                    <div class="fs-3 fw-semibold text-danger">{{ number_format($summary['belum_lengkap']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase mb-2">Materi Ditampilkan</div>
                    <div class="fs-3 fw-semibold">{{ number_format($summary['total_materi']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase mb-2">Filter Jenis Tugas</div>
                    <div class="fw-semibold">{{ $summary['filter_jenis'] }}</div>
                    <div class="small text-muted mt-2">
                        Area:
                        <span class="fw-medium">{{ $selectedArea ?: 'Semua Materi' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="area" class="form-label">Filter Materi / Area</label>
                            <select name="area" id="area" class="form-select">
                                <option value="">Semua Materi</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" {{ $selectedArea === $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_tugas" class="form-label">Filter Jenis Tugas</label>
                            <select name="jenis_tugas" id="jenis_tugas" class="form-select">
                                <option value="">Semua Jenis Tugas</option>
                                @foreach($jenisLabels as $jenisKey => $jenisLabel)
                                    <option value="{{ $jenisKey }}" {{ $selectedJenis === $jenisKey ? 'selected' : '' }}>{{ $jenisLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            <a href="{{ route('instumen-talenta.belum-upload-tugas') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Lengkap</span>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Ada tugas belum upload</span>
                        <span class="badge bg-light text-dark border">Ringkasan di kolom terakhir menunjukkan seluruh kekurangan peserta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($rows->count())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 60px;">#</th>
                                        <th style="min-width: 120px;">Kode</th>
                                        <th style="min-width: 220px;">Nama Peserta</th>
                                        <th style="min-width: 240px;">Sekolah / Madrasah</th>
                                        <th style="min-width: 180px;">Kelompok</th>
                                        @foreach($materis as $materi)
                                            <th style="min-width: 220px;">
                                                {{-- <div class="fw-semibold">{{ $materi->kode_materi ?? ('M-' . $materi->id) }}</div> --}}
                                                <div class="small text-muted">{{ $materi->judul_materi }}</div>
                                                {{-- <div class="small text-primary">{{ $materi->slug }}</div> --}}
                                            </th>
                                        @endforeach
                                        <th style="min-width: 280px;">Tugas Belum Terinput</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $idx => $row)
                                        <tr>
                                            <td>{{ ($rows->currentPage() - 1) * $rows->perPage() + $idx + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $row->kode_peserta }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $row->nama }}</div>
                                                <div class="small text-muted">{{ $row->email }}</div>
                                                <div class="mt-2">
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                        {{ $row->missing_task_count }} tugas belum upload
                                                    </span>
                                                </div>
                                            </td>
                                            <td>{{ $row->sekolah }}</td>
                                            <td>{{ $row->kelompok }}</td>

                                            @foreach($materis as $materi)
                                                @php($status = $row->status_per_materi[$materi->id] ?? ['missing' => [], 'done' => [], 'is_complete' => false])
                                                <td>
                                                    @if($status['is_complete'])
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Lengkap</span>
                                                    @else
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Belum lengkap</span>
                                                            @foreach($status['missing'] as $missingItem)
                                                                <span class="small">{{ $missingItem }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach($row->missing_summary as $summaryItem)
                                                        <div class="small">{{ $summaryItem }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Menampilkan {{ $rows->firstItem() }} - {{ $rows->lastItem() }} dari {{ $rows->total() }} peserta yang belum lengkap.
                            </div>
                            <div>
                                {{ $rows->withQueryString()->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success mb-0">
                            Tidak ada peserta yang cocok dengan filter atau seluruh tugas pada materi yang dipilih sudah lengkap.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
