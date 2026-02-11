{{-- resources/views/mgmp/data-anggota.blade.php --}}
@extends('layouts.master')

@section('title') Data Anggota MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('li_2') Data Anggota @endslot
    @slot('title') Data Anggota MGMP @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <!-- Header Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1 text-dark">
                            <i class="mdi mdi-account-group text-primary me-2"></i>
                            Data Anggota MGMP
                        </h4>
                        <p class="text-muted mb-0">Kelola data anggota Musyawarah Guru Mata Pelajaran</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="exportData()">
                            <i class="mdi mdi-download me-1"></i>
                            Export
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal">
                            <i class="mdi mdi-plus me-1"></i>
                            Tambah Anggota
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
                                <i class="mdi mdi-account-group fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $totalAnggota }}</h5>
                        <small class="text-muted">Total Anggota</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                <i class="mdi mdi-school fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $anggotaAktif }}</h5>
                        <small class="text-muted">Anggota Aktif</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="mdi mdi-map-marker fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $totalSekolah }}</h5>
                        <small class="text-muted">Sekolah</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                <i class="mdi mdi-account-tie fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $totalGuru }}</h5>
                        <small class="text-muted">Guru</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">Anggota</th>
                                <th class="border-0 fw-semibold text-dark py-3">Sekolah</th>
                                <th class="border-0 fw-semibold text-dark py-3">Mata Pelajaran</th>
                                <th class="border-0 fw-semibold text-dark py-3">Status</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota as $member)
                            <tr class="border-bottom border-light">
                                <td class="py-3 ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <img src="{{ isset($member->avatar) ? asset('storage/' . $member->avatar) : asset('build/images/avatar-1.jpg') }}"
                                                 alt="Foto {{ $member->name }}"
                                                 class="rounded-circle">
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $member->name }}</h6>
                                            <small class="text-muted">{{ $member->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div>
                                        <div class="fw-medium">{{ $member->madrasah ? $member->madrasah->name : '-' }}</div>
                                        <small class="text-muted">{{ $member->madrasah ? $member->madrasah->alamat : '-' }}</small>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-info bg-opacity-10 text-info">{{ $member->ketugasan ?? '-' }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                </td>
                                <td class="py-3 pe-4">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="lihatDetail({{ $member->id }})">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" onclick="editAnggota({{ $member->id }})">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="hapusAnggota({{ $member->id }})">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="mdi mdi-account-group-off fs-1"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted">Belum ada data anggota</h6>
                                    <p class="text-muted small">Data anggota MGMP akan muncul di sini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($anggota->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $anggota->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Anggota MGMP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambahAnggota">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NUPTK</label>
                            <input type="text" class="form-control" name="nuptk">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NPK</label>
                            <input type="text" class="form-control" name="npk">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sekolah</label>
                            <select class="form-control" name="madrasah_id" required>
                                <option value="">Pilih Sekolah</option>
                                @foreach($sekolah as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" class="form-control" name="ketugasan" placeholder="Contoh: Matematika, Bahasa Indonesia">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Kepegawaian</label>
                            <select class="form-control" name="status_kepegawaian_id">
                                <option value="">Pilih Status</option>
                                @foreach($statusKepegawaian as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir">
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
    console.log('View detail for member:', id);
}

function editAnggota(id) {
    // Implement edit functionality
    console.log('Edit member:', id);
}

function hapusAnggota(id) {
    if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
        // Implement delete functionality
        console.log('Delete member:', id);
    }
}

function exportData() {
    // Implement export functionality
    console.log('Export data');
}

document.getElementById('formTambahAnggota').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implement form submission
    console.log('Form submitted');
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('tambahAnggotaModal'));
    modal.hide();
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
@endsection
