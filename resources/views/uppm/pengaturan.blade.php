@extends('layouts.master')

@section('title')Pengaturan UPPM @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Pengaturan UPPM @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-cog me-2"></i>
                    Pengaturan UPPM
                </h4>
                <p class="text-white-50 mb-0">
                    Kelola pengaturan pembayaran iuran UPPM untuk setiap tahun anggaran
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-data"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total</p>
                        <h5 class="mb-0">{{ $settings->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Aktif</p>
                        <h5 class="mb-0">{{ $settings->where('aktif', true)->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-secondary">
                        <i class="bx bx-x-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Tidak Aktif</p>
                        <h5 class="mb-0">{{ $settings->where('aktif', false)->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Tahun Terbaru</p>
                        <h5 class="mb-0">{{ $settings->max('tahun_anggaran') ?: '-' }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Lunas</p>
                        <h5 class="mb-0">{{ $settings->where('skema_pembayaran', 'lunas')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-time"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Cicilan</p>
                        <h5 class="mb-0">{{ $settings->where('skema_pembayaran', 'cicilan')->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="action-buttons">
            <h5 class="mb-3">
                <i class="bx bx-plus-circle me-2"></i>Aksi Pengaturan
            </h5>
            <div class="btn-group-custom">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addSettingModal">
                    <i class="bx bx-plus me-1"></i> Tambah Pengaturan Baru
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Settings Cards Grid -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($settings->count() > 0)
                    <div class="row g-4">
                        @foreach($settings as $setting)
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="setting-card {{ $setting->aktif ? 'active' : 'inactive' }}">
                                <div class="card-icon {{ $setting->aktif ? 'active' : 'inactive' }}">
                                    <i class="bx bx-cog"></i>
                                </div>

                                <div class="card-header">
                                    <h3 class="card-title">
                                        {{ $setting->tahun_anggaran }}
                                    </h3>
                                    <div class="card-meta">
                                        <div class="card-date">
                                            <i class="bx bx-calendar me-1"></i>
                                            Tahun Anggaran
                                        </div>
                                    </div>
                                </div>

                                <div class="card-description">
                                    Pengaturan pembayaran iuran UPPM untuk tahun {{ $setting->tahun_anggaran }}
                                </div>

                                <div class="card-badges mb-3">
                                    <span class="badge badge-modern {{ $setting->aktif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $setting->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    <span class="badge badge-modern bg-info">
                                        {{ ucfirst($setting->skema_pembayaran) }}
                                    </span>
                                </div>

                                <div class="card-details">
                                    <small>
                                        <i class="bx bx-time me-1"></i>
                                        <strong>Jatuh Tempo:</strong> {{ $setting->jatuh_tempo ?: 'Tidak ditentukan' }}
                                    </small>
                                </div>

                                <div class="nominal-info">
                                    <div class="nominal-item">
                                        <span class="nominal-label">Siswa</span>
                                        <span class="nominal-value">Rp {{ number_format($setting->nominal_siswa) }}</span>
                                    </div>
                                    <div class="nominal-item">
                                        <span class="nominal-label">PNS Sertifikasi</span>
                                        <span class="nominal-value">Rp {{ number_format($setting->nominal_pns_sertifikasi) }}</span>
                                    </div>
                                    <div class="nominal-item">
                                        <span class="nominal-label">Lihat detail lainnya</span>
                                        <span class="nominal-value"><i class="bx bx-edit"></i></span>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSettingModal{{ $setting->id }}">
                                        <i class="bx bx-edit me-1"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('uppm.pengaturan.destroy', $setting->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bx bx-cog"></i>
                        </div>
                        <h5>Belum Ada Pengaturan UPPM</h5>
                        <p class="text-muted">Klik tombol "Tambah Pengaturan Baru" untuk menambahkan pengaturan baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- Edit Modals -->
    @forelse($settings as $setting)
        <div class="modal fade" id="editSettingModal{{ $setting->id }}" tabindex="-1" aria-labelledby="editSettingModalLabel{{ $setting->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSettingModalLabel{{ $setting->id }}"><i class="bx bx-edit me-2"></i>Edit Pengaturan UPPM {{ $setting->tahun_anggaran }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editSettingForm{{ $setting->id }}" method="POST" action="{{ route('uppm.pengaturan.update', $setting->id) }}" enctype="multipart/form-data" data-original-year="{{ $setting->tahun_anggaran }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            @include('uppm.form', ['setting' => $setting])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
    @endforelse

    <!-- Add Modal -->
    <div class="modal fade" id="addSettingModal" tabindex="-1" aria-labelledby="addSettingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSettingModalLabel"><i class="bx bx-plus me-2"></i>Tambah Pengaturan UPPM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSettingForm" method="POST" action="{{ route('uppm.pengaturan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @include('uppm.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Collect existing tahun_anggaran into an array
    var existingYears = [];
    @foreach($settings as $setting)
        existingYears.push({{ $setting->tahun_anggaran }});
    @endforeach

    // Function to check if year is duplicate
    function isYearDuplicate(year, originalYear = null) {
        if (originalYear && year == originalYear) {
            return false; // Allow same year for edit
        }
        return existingYears.includes(parseInt(year));
    }

    // Handle add setting form
    $('#addSettingForm').on('submit', function(e) {
        e.preventDefault();

        var tahunAnggaran = $('#addSettingForm input[name="tahun_anggaran"]').val();
        if (isYearDuplicate(tahunAnggaran)) {
            Swal.fire({
                icon: 'error',
                title: 'Tahun Anggaran Sudah Ada!',
                text: 'Tahun anggaran ' + tahunAnggaran + ' sudah ada dalam database. Silakan pilih tahun yang berbeda.',
                showConfirmButton: true
            });
            return;
        }

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addSettingModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pengaturan UPPM berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    showConfirmButton: true
                });
            }
        });
    });

    // Handle edit setting forms
    $('form[id^="editSettingForm"]').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('.modal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pengaturan UPPM berhasil diperbarui',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat memperbarui data';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            }
        });
    });

    // Handle delete with SweetAlert confirmation
    $('form.d-inline').on('submit', function(e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengaturan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pengaturan UPPM berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data',
                            timer: 1500,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                    }
                });
            }
        });
    });
});


</script>
@endsection


