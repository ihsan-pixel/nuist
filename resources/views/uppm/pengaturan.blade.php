@extends('layouts.master')

@section('title')Pengaturan UPPM @endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="bx bx-cog me-2"></i>Pengaturan UPPM</h2>
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addSettingModal">
                    <i class="bx bx-plus"></i> Tambah Pengaturan
                </button>
            </div>
        </div>
    </div>



    <div class="row">
        @forelse($settings as $setting)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="bx bx-calendar me-2"></i>{{ $setting->tahun_anggaran }}</h5>
                            <span class="badge {{ $setting->aktif ? 'bg-success' : 'bg-secondary' }}">
                                <i class="bx {{ $setting->aktif ? 'bx-check' : 'bx-x' }}"></i> {{ $setting->aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted">Jatuh Tempo</small>
                                <p class="mb-1">{{ $setting->jatuh_tempo ?: '-' }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Skema</small>
                                <p class="mb-1">{{ ucfirst($setting->skema_pembayaran) }}</p>
                            </div>
                        </div>
                        <hr>
                        <h6 class="text-primary mb-2"><i class="bx bx-money me-1"></i>Nominal Utama</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted">Siswa</small>
                                <p class="mb-1 fw-bold">Rp {{ number_format($setting->nominal_siswa) }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">PNS Sertifikasi</small>
                                <p class="mb-1 fw-bold">Rp {{ number_format($setting->nominal_pns_sertifikasi) }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Lihat detail nominal lainnya di edit</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSettingModal{{ $setting->id }}">
                                <i class="bx bx-edit"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('uppm.pengaturan.destroy', $setting->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bx bx-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editSettingModal{{ $setting->id }}" tabindex="-1" aria-labelledby="editSettingModalLabel{{ $setting->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSettingModalLabel{{ $setting->id }}"><i class="bx bx-edit me-2"></i>Edit Pengaturan UPPM {{ $setting->tahun_anggaran }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editSettingForm{{ $setting->id }}" method="POST" action="{{ route('uppm.pengaturan.update', $setting->id) }}" enctype="multipart/form-data">
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
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bx bx-info-circle fs-1 text-muted mb-3"></i>
                        <h5 class="card-title">Belum ada pengaturan UPPM</h5>
                        <p class="card-text">Klik tombol "Tambah Pengaturan" untuk menambahkan pengaturan baru.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

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
    // Handle add setting form
    $('#addSettingForm').on('submit', function(e) {
        e.preventDefault();

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
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true
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
                    timer: 3000,
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
                    timer: 3000,
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
                            timer: 3000,
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


