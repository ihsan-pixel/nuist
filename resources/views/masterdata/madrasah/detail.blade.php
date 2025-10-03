@extends('layouts.master')

@section('title', 'Detail Profile Madrasah')

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('li_2') Profile Madrasah/Sekolah @endslot
    @slot('title') Detail {{ $madrasah->name }} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-school me-2"></i>Detail Profile Madrasah
                </h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Data Madrasah -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        @if($madrasah->logo)
                        <img src="{{ asset('storage/app/public/' . $madrasah->logo) }}" class="rounded mx-auto d-block mb-3" alt="{{ $madrasah->name }}" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                        <div class="rounded mx-auto d-block mb-3 bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                            <i class="bx bx-school bx-lg text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ $madrasah->name }}</h3>
                        <p class="text-muted">{{ $madrasah->alamat ?? 'Alamat tidak tersedia' }}</p>
                        @if($madrasah->map_link)
                        <a href="{{ $madrasah->map_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-map me-1"></i> Lihat di Peta
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Kepala Sekolah -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-2">Kepala Sekolah/Madrasah</h5>
                        @if($kepalaSekolah)
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($kepalaSekolah->avatar)
                                <img src="{{ asset('storage/' . $kepalaSekolah->avatar) }}" class="rounded-circle" alt="{{ $kepalaSekolah->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    {{ substr($kepalaSekolah->name, 0, 1) }}
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $kepalaSekolah->name }}</h6>
                                <small class="text-muted">{{ $kepalaSekolah->email }}</small>
                            </div>
                        </div>
                        @else
                        <p class="text-muted">Kepala sekolah belum ditetapkan.</p>
                        @endif
                    </div>
                </div>

                <!-- Jumlah TP berdasarkan Status Kepegawaian -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-3">Jumlah Tenaga Pendidik berdasarkan Status Kepegawaian</h5>
                        <div class="row">
                            @forelse($tpByStatus as $status => $count)
                            <div class="col-md-3 mb-2">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted mb-1">{{ $status ?? 'Tidak Diketahui' }}</h6>
                                        <h4 class="fw-bold text-primary">{{ $count }}</h4>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <p class="text-muted">Belum ada data tenaga pendidik.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Data Table Tenaga Pendidik -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-3">Data Tenaga Pendidik</h5>
                        <div class="table-responsive">
                            <table id="tenaga-pendidik-table" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status Kepegawaian</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($madrasah->tenagaPendidikUsers as $tp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tp->name }}</td>
                                        <td>{{ $tp->email }}</td>
                                        <td>{{ $tp->statusKepegawaian->name ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info view-btn"
                                                    data-name="{{ $tp->name }}"
                                                    data-email="{{ $tp->email }}"
                                                    data-status="{{ $tp->statusKepegawaian->name ?? '-' }}"
                                                    data-no_hp="{{ $tp->no_hp ?? '-' }}"
                                                    data-nip="{{ $tp->nip ?? '-' }}"
                                                    data-nuptk="{{ $tp->nuptk ?? '-' }}"
                                                    data-avatar="{{ $tp->avatar ? asset('storage/app/public/' . $tp->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                                                    data-tempat_lahir="{{ $tp->tempat_lahir ?? '-' }}"
                                                    data-tanggal_lahir="{{ $tp->tanggal_lahir ? $tp->tanggal_lahir->format('d-m-Y') : '-' }}"
                                                    data-alamat="{{ $tp->alamat ?? '-' }}"
                                                    data-kartanu="{{ $tp->kartanu ?? '-' }}"
                                                    data-npk="{{ $tp->npk ?? '-' }}"
                                                    data-pendidikan_terakhir="{{ $tp->pendidikan_terakhir ?? '-' }}"
                                                    data-tahun_lulus="{{ $tp->tahun_lulus ?? '-' }}"
                                                    data-program_studi="{{ $tp->program_studi ?? '-' }}"
                                                    data-tmt="{{ $tp->tmt ? $tp->tmt->format('d-m-Y') : '-' }}"
                                                    data-ketugasan="{{ $tp->ketugasan ?? '-' }}"
                                                    data-mengajar="{{ $tp->mengajar ?? '-' }}"
                                                    data-jabatan="{{ $tp->jabatan ?? '-' }}">
                                                <i class="bx bx-show"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">
                                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                                <strong>Belum ada data tenaga pendidik</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Viewing Tenaga Pendidik Details -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewModalLabel">
                    <i class="bx bx-user-circle me-2"></i>Detail Tenaga Pendidik
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <img id="modal-avatar" src="" alt="Avatar" class="img-fluid rounded-circle mb-3 border" style="width: 150px; height: 150px; object-fit: cover; border-width: 3px !important; border-color: #007bff !important;">
                                <h5 id="modal-name" class="card-title text-primary fw-bold"></h5>
                                <p class="text-muted mb-2"><i class="bx bx-envelope me-1"></i><span id="modal-email"></span></p>
                                <span class="badge bg-success fs-6" id="modal-status"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-phone me-1"></i>No HP</h6>
                                        <p class="mb-0 fw-semibold" id="modal-no_hp">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-id-card me-1"></i>NIP</h6>
                                        <p class="mb-0 fw-semibold" id="modal-nip">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-id-card me-1"></i>NUPTK</h6>
                                        <p class="mb-0 fw-semibold" id="modal-nuptk">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-id-card me-1"></i>NPK</h6>
                                        <p class="mb-0 fw-semibold" id="modal-npk">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-map-pin me-1"></i>Tempat Lahir</h6>
                                        <p class="mb-0 fw-semibold" id="modal-tempat_lahir">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-calendar me-1"></i>Tanggal Lahir</h6>
                                        <p class="mb-0 fw-semibold" id="modal-tanggal_lahir">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-home me-1"></i>Alamat</h6>
                                        <p class="mb-0 fw-semibold" id="modal-alamat">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-card me-1"></i>Kartanu</h6>
                                        <p class="mb-0 fw-semibold" id="modal-kartanu">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-graduation me-1"></i>Pendidikan Terakhir</h6>
                                        <p class="mb-0 fw-semibold" id="modal-pendidikan_terakhir">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-calendar-check me-1"></i>Tahun Lulus</h6>
                                        <p class="mb-0 fw-semibold" id="modal-tahun_lulus">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-book me-1"></i>Program Studi</h6>
                                        <p class="mb-0 fw-semibold" id="modal-program_studi">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-calendar-event me-1"></i>TMT</h6>
                                        <p class="mb-0 fw-semibold" id="modal-tmt">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-briefcase me-1"></i>Ketugasan</h6>
                                        <p class="mb-0 fw-semibold" id="modal-ketugasan">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-chalkboard me-1"></i>Mengajar</h6>
                                        <p class="mb-0 fw-semibold" id="modal-mengajar">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-2"><i class="bx bx-crown me-1"></i>Jabatan</h6>
                                        <p class="mb-0 fw-semibold" id="modal-jabatan">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function () {
    let table = $("#tenaga-pendidik-table").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#tenaga-pendidik-table_wrapper .col-md-6:eq(0)');

    // Handle View Button Click
    $('#tenaga-pendidik-table').on('click', '.view-btn', function() {
        const data = $(this).data();
        $('#modal-name').text(data.name);
        $('#modal-email').text(data.email);
        $('#modal-status').text(data.status);
        $('#modal-no_hp').text(data.no_hp);
        $('#modal-nip').text(data.nip);
        $('#modal-nuptk').text(data.nuptk);
        $('#modal-npk').text(data.npk);
        $('#modal-tempat_lahir').text(data.tempat_lahir);
        $('#modal-tanggal_lahir').text(data.tanggal_lahir);
        $('#modal-alamat').text(data.alamat);
        $('#modal-kartanu').text(data.kartanu);
        $('#modal-pendidikan_terakhir').text(data.pendidikan_terakhir);
        $('#modal-tahun_lulus').text(data.tahun_lulus);
        $('#modal-program_studi').text(data.program_studi);
        $('#modal-tmt').text(data.tmt);
        $('#modal-ketugasan').text(data.ketugasan);
        $('#modal-mengajar').text(data.mengajar);
        $('#modal-jabatan').text(data.jabatan);
        $('#modal-avatar').attr('src', data.avatar);
        $('#viewModal').modal('show');
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
