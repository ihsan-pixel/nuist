@extends('layouts.master')

@section('title', 'Monitoring Jurnal Mengajar')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard Admin @endslot
    @slot('title') Monitoring Jurnal Mengajar @endslot
@endcomponent

<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h4 class="mb-1 text-white">{{ Auth::user()->madrasah->name ?? '-' }}</h4>
                        <p class="mb-0 text-white-50">Monitoring jurnal mengajar guru dan jadwal yang belum diisi</p>
                    </div>
                    <div class="text-end">
                        <div class="small text-white-50">Periode</div>
                        <div class="fw-semibold">{{ $summary['bulan'] }}</div>
                    </div>
                </div>

                @if(!empty($approvedEventName))
                    <div class="mt-3 p-3 rounded-3" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
                        <div class="d-flex align-items-start gap-2">
                            <i class="mdi mdi-information-outline fs-5"></i>
                            <div>
                                <div class="fw-semibold">{{ $approvedEventLabel ?? 'Kegiatan Sekolah' }} disetujui</div>
                                <div class="small text-white-50">{{ $approvedEventName }}</div>
                                @if(!empty($approvedEventNote))
                                    <div class="small mt-1 text-white-50">{{ $approvedEventNote }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <form method="GET" class="mt-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label text-white-50 mb-1">Bulan</label>
                            <input type="month" name="month" class="form-control form-control-sm" value="{{ $selectedMonth }}">
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label text-white-50 mb-1">Kelas</label>
                            <select name="class_name" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                @foreach($availableClasses as $className)
                                    <option value="{{ $className }}" @selected($selectedClass === $className)>{{ $className }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button type="submit" class="btn btn-light btn-sm w-100 fw-semibold">Terapkan</button>
                        </div>
                    </div>
                </form>

                <div class="row g-2 mt-3">
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Guru aktif</div>
                            <div class="fs-4 fw-bold">{{ $summary['total_guru'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Jurnal tercatat</div>
                            <div class="fs-4 fw-bold">{{ $summary['total_jurnal'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Jadwal wajib</div>
                            <div class="fs-4 fw-bold">{{ $summary['total_jadwal'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Izin kegiatan</div>
                            <div class="fs-4 fw-bold">{{ $summary['total_izin'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-3 rounded-3" style="background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.12);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Rekap harian</div>
                            <div class="fw-semibold">{{ $dailyRecaps->count() }} hari terdata</div>
                        </div>
                        <div class="text-end small text-white-50">
                            <div>Hadir {{ $completedJournals->count() }}</div>
                            <div>Belum {{ $missingJournals->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1 text-dark">Rekap Harian</h5>
                        <p class="mb-0 text-muted small">Data dikelompokkan per tanggal dan bisa direkap harian</p>
                    </div>
                    <span class="badge bg-success-subtle text-success">{{ $dailyRecaps->count() }}</span>
                </div>

                @if($dailyRecaps->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="mdi mdi-book-open-page-variant fs-1"></i>
                        <div class="mt-2">Belum ada rekap harian pada periode ini.</div>
                    </div>
                @else
                    <div class="d-grid gap-3">
                        @foreach($dailyRecaps as $daily)
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <div>
                                        <div class="fw-semibold">{{ $daily['label'] }}</div>
                                        <div class="text-muted small">Total sesi {{ $daily['total'] }}</div>
                                    </div>
                                    <div class="text-end small">
                                        <div class="text-success">Hadir {{ $daily['hadir'] }}</div>
                                        <div class="text-info">Izin {{ $daily['izin'] }}</div>
                                        <div class="text-warning">Belum {{ $daily['belum'] }}</div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    @foreach($daily['items'] as $item)
                                        @php($schedule = $item['schedule'])
                                        <div class="bg-light rounded-3 p-3">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div>
                                                    <div class="fw-semibold">{{ $schedule->teacher?->name ?? '-' }}</div>
                                                    <div class="text-muted small">{{ $schedule->classNameLabel() ?: ($schedule->class_name ?? '-') }} | {{ $schedule->subject ?? '-' }}</div>
                                                </div>
                                                <span class="badge {{ ($item['status'] ?? 'belum') === 'izin' ? 'bg-info' : (($item['status'] ?? 'belum') === 'hadir' ? 'bg-success' : 'bg-warning') }}">
                                                    {{ strtoupper($item['status'] ?? 'belum') }}
                                                </span>
                                            </div>
                                            <div class="text-muted small mt-1">{{ trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-')) }}</div>
                                            @if(($item['status'] ?? null) === 'izin' && !empty($item['event']))
                                                <div class="text-success small mt-1">{{ $item['event']?->name ?? 'Kegiatan sekolah' }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1 text-dark">Rekap Status</h5>
                        <p class="mb-0 text-muted small">Ringkasan kehadiran dan izin pada periode ini</p>
                    </div>
                    <span class="badge bg-warning-subtle text-warning">{{ $missingJournals->count() }}</span>
                </div>

                @if($missingJournals->isEmpty())
                    <div class="text-center py-4 text-muted">Semua jadwal pada periode ini sudah punya jurnal.</div>
                @else
                    <div class="d-grid gap-2">
                        @foreach($missingJournals->take(12) as $item)
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="fw-semibold">{{ $item['teacher'] }}</div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($item['date'])->format('d M') }}</div>
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ $item['class_name'] }} | {{ $item['subject'] }} | {{ $item['time'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1 text-dark">Jurnal Tercatat</h5>
                        <p class="mb-0 text-muted small">Ringkasan entri yang sudah masuk</p>
                    </div>
                    <span class="badge bg-success-subtle text-success">{{ $completedJournals->count() }}</span>
                </div>

                @if($completedJournals->isEmpty())
                    <div class="text-center py-4 text-muted">Belum ada jurnal tercatat.</div>
                @else
                    <div class="d-grid gap-2">
                        @foreach($completedJournals->take(8) as $item)
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="fw-semibold">{{ $item['teacher'] }}</div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($item['date'])->format('d M') }}</div>
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ $item['class_name'] }} | {{ $item['subject'] }} | {{ $item['time'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
