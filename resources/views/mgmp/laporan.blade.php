{{-- resources/views/mgmp/laporan.blade.php --}}
@extends('layouts.master')

@section('title') Laporan Kegiatan MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('li_2') Laporan Kegiatan @endslot
    @slot('title') Laporan Kegiatan MGMP @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <!-- Header Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1 text-dark">
                            <i class="mdi mdi-file-document text-primary me-2"></i>
                            Laporan Kegiatan MGMP
                        </h4>
                        <p class="text-muted mb-0">Kelola laporan kegiatan Musyawarah Guru Mata Pelajaran</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahLaporanModal">
                            <i class="mdi mdi-plus me-1"></i>
                            Tambah Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                <i class="mdi mdi-file-document fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $totalLaporan }}</h5>
                        <small class="text-muted">Total Laporan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                <i class="mdi mdi-calendar-check fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $laporanBulanIni }}</h5>
                        <small class="text-muted">Bulan Ini</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="mdi mdi-account-group fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $totalPeserta }}</h5>
                        <small class="text-muted">Total Peserta</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                <i class="mdi mdi-clock fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $rataRataDurasi }}</h5>
                        <small class="text-muted">Jam Rata-rata</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">No</th>
                                <th class="border-0 fw-semibold text-dark py-3">Nama Kegiatan</th>
                                <th class="border-0 fw-semibold text-dark py-3">Tanggal</th>
                                <th class="border-0 fw-semibold text-dark py-3">Dokumentasi</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Jumlah Anggota Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $report)
                            <tr class="border-bottom border-light">
                                <td class="py-3 ps-4">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <div>
                                        <h6 class="mb-1">{{ $report->judul }}</h6>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div>
                                        @if(isset($report->tanggal) && $report->tanggal)
                                            <div class="fw-medium">{{ \Carbon\Carbon::parse($report->tanggal)->format('d M Y') }}</div>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if(!empty($report->dokumentasi))
                                        {{-- dokumentasi could be a file path or array; show thumbnail or link --}}
                                        @if(is_array($report->dokumentasi))
                                            @foreach($report->dokumentasi as $doc)
                                                <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="me-2">Lihat</a>
                                            @endforeach
                                        @else
                                            <a href="{{ asset('storage/' . $report->dokumentasi) }}" target="_blank">Lihat</a>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 pe-4">
                                    @if(!empty($report->peserta) && (is_array($report->peserta) || $report->peserta instanceof \Illuminate\Support\Collection))
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($report->peserta as $p)
                                                @php
                                                    // Normalize $p to a displayable name
                                                    if (is_object($p)) {
                                                        $pName = $p->name ?? ($p->nama ?? null);
                                                    } elseif (is_array($p)) {
                                                        $pName = $p['name'] ?? ($p['nama'] ?? null);
                                                    } else {
                                                        $pName = $p;
                                                    }
                                                @endphp
                                                @if($pName)
                                                    <span class="badge bg-info bg-opacity-10 text-info">{{ $pName }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @elseif(!empty($report->jumlah_peserta))
                                        {{ $report->jumlah_peserta }} orang
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="mdi mdi-file-document-off fs-1"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted">Belum ada laporan kegiatan</h6>
                                    <p class="text-muted small">Laporan kegiatan MGMP akan muncul di sini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (only if paginated) -->
                @if(method_exists($laporan, 'hasPages') && $laporan->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $laporan->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Laporan -->
<div class="modal fade" id="tambahLaporanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Laporan Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambahLaporan">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Judul Kegiatan</label>
                            <input type="text" class="form-control" name="judul" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" name="waktu_mulai" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" name="waktu_selesai" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pilih Nama Peserta yang Hadir</label>
                            <select class="form-select" name="peserta[]" id="selectPeserta" multiple aria-label="Pilih peserta">
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">{{ $m->name ?? $m->nama ?? ('User #' . $m->id) }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tekan Ctrl/Cmd + klik untuk memilih beberapa peserta</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Materi</label>
                            <textarea class="form-control" name="materi" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Hasil Kegiatan</label>
                            <textarea class="form-control" name="hasil" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function lihatDetail(id) {
    // Implement view detail functionality
    console.log('View detail for report:', id);
}

function editLaporan(id) {
    // Implement edit functionality
    console.log('Edit report:', id);
}

function hapusLaporan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
        // Implement delete functionality
        console.log('Delete report:', id);
    }
}

document.getElementById('formTambahLaporan').addEventListener('submit', function(e) {
    e.preventDefault();
    // Collect selected peserta names and ids
    const select = document.getElementById('selectPeserta');
    let selected = [];
    if (select) {
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].selected) {
                selected.push({ id: select.options[i].value, name: select.options[i].text });
            }
        }
    }

    // For now we just log the payload (replace with AJAX or form submit to server)
    console.log('Form submitted. Peserta:', selected);
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('tambahLaporanModal'));
    modal.hide();
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
@endsection
