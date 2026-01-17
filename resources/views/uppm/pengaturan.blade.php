@extends('layouts.master')

@section('title')Pengaturan UPPM @endsection

@section('css')
<style>
/* Modern Card Grid Design */
.setting-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.setting-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.setting-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.setting-card.active::before { background: linear-gradient(90deg, #28a745, #20c997); }
.setting-card.inactive::before { background: linear-gradient(90deg, #6c757d, #495057); }

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.card-icon.active { background: linear-gradient(135deg, #28a745, #20c997); }
.card-icon.inactive { background: linear-gradient(135deg, #6c757d, #495057); }

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.card-date {
    color: #718096;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.badge-modern {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-modern.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
.badge-modern.bg-success { background: linear-gradient(135deg, #48bb78, #38a169) !important; }
.badge-modern.bg-info { background: linear-gradient(135deg, #4299e1, #3182ce) !important; }
.badge-modern.bg-warning { background: linear-gradient(135deg, #ed8936, #dd6b20) !important; }
.badge-modern.bg-secondary { background: linear-gradient(135deg, #a0aec0, #718096) !important; }

.card-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 15px;
}

.card-details {
    background: #f7fafc;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #667eea;
    margin-bottom: 15px;
}

.card-details small {
    color: #718096;
    font-size: 0.8rem;
}

.nominal-info {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
}

.nominal-info .nominal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.nominal-info .nominal-item:last-child {
    margin-bottom: 0;
}

.nominal-info .nominal-label {
    font-size: 0.8rem;
    opacity: 0.9;
}

.nominal-info .nominal-value {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Statistics Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Action Buttons */
.action-buttons {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 8px 30px rgba(72, 187, 120, 0.3);
}

.action-buttons h5 {
    color: white;
    margin-bottom: 1rem;
}

.btn-group-custom .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-group-custom .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(5px);
}

.loading-content {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .btn-group-custom .btn {
        width: 100%;
        margin-right: 0;
    }

    .stats-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .card-description {
        font-size: 0.9rem;
    }

    .card-title {
        font-size: 1.1rem;
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 15px;
    border: 2px dashed #cbd5e0;
}

.empty-state .empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}
</style>
@endsection

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
<div class="row mb-2">
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
            {{-- <h5 class="mb-3">
                <i class="bx bx-plus-circle me-2"></i>Aksi Pengaturan
            </h5> --}}
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


