@extends('layouts.master')

@section('title', 'Detail Profile Madrasah')

@section('css')
<!-- Bootstrap & Icons -->
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />

<!-- DataTables -->
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />

<style>
    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        background: linear-gradient(90deg, #007bff 0%, #00a1ff 100%);
        color: #fff;
        border-radius: 1rem 1rem 0 0;
    }
    .profile-logo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid #f1f5f9;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .profile-logo:hover {
        transform: scale(1.05);
    }
    .table th {
        background-color: #f8fafc;
        color: #374151;
        font-weight: 600;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-primary, .btn-outline-primary {
        border-radius: 8px;
    }
    .modal-content {
        border-radius: 1rem;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
    }
    .modal-header {
        background: linear-gradient(90deg, #007bff 0%, #00a1ff 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
    }
    .badge {
        padding: 0.45em 0.8em;
        border-radius: 6px;
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('li_2') Profile Madrasah/Sekolah @endslot
    @slot('title') Detail {{ $madrasah->name }} @endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card overflow-hidden">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><i class="bx bx-school me-2"></i> Detail Profil Madrasah</h4>
            </div>

            <div class="card-body">
                {{-- ALERT --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- SECTION: Profile Madrasah -->
                <div class="row align-items-center mb-5">
                    <div class="col-md-4 text-center">
                        @if($madrasah->logo)
                        <img src="{{ asset('storage/app/public/' . $madrasah->logo) }}" class="profile-logo" alt="{{ $madrasah->name }}">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle mx-auto" style="width:150px; height:150px;">
                            <i class="bx bx-school bx-lg text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold mb-1 text-primary">{{ $madrasah->name }}</h3>
                        <p class="text-muted mb-1">{{ $madrasah->kabupaten ? 'Kabupaten ' . $madrasah->kabupaten : '' }}</p>
                        <p class="text-muted mb-2"><i class="bx bx-map me-1"></i>{{ $madrasah->alamat ?? 'Alamat tidak tersedia' }}</p>
                        <p class="mb-2"><strong>Hari KBM:</strong> {{ $madrasah->hari_kbm ? $madrasah->hari_kbm . ' hari' : 'Tidak ditentukan' }}</p>
                        @if($madrasah->map_link)
                        <a href="{{ $madrasah->map_link }}" target="_blank" class="btn btn-outline-primary btn-sm shadow-sm">
                            <i class="bx bx-map me-1"></i> Lihat di Peta
                        </a>
                        @endif
                    </div>
                </div>

                <!-- SECTION: Kepala Sekolah -->
                <div class="border-top pt-4 mb-5">
                    <h5 class="fw-semibold mb-3"><i class="bx bx-user-circle me-2 text-primary"></i>Kepala Sekolah / Madrasah</h5>
                    @if($kepalaSekolah)
                    <div class="d-flex align-items-center p-3 rounded shadow-sm bg-light">
                        <div class="flex-shrink-0">
                            @if($kepalaSekolah->avatar)
                            <img src="{{ asset('storage/' . $kepalaSekolah->avatar) }}" class="rounded-circle" alt="{{ $kepalaSekolah->name }}" style="width: 55px; height: 55px; object-fit: cover;">
                            @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:55px; height:55px;">
                                {{ substr($kepalaSekolah->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 fw-bold">{{ $kepalaSekolah->name }}</h6>
                            <small class="text-muted">{{ $kepalaSekolah->email }}</small>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">Kepala sekolah belum ditetapkan.</p>
                    @endif
                </div>

                <!-- SECTION: Statistik Tenaga Pendidik -->
                <div class="border-top pt-4 mb-4">
                    <h5 class="fw-semibold mb-3"><i class="bx bx-bar-chart-alt me-2 text-primary"></i>Statistik Tenaga Pendidik</h5>
                    <div class="row">
                        @forelse($tpByStatus as $status => $count)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-0 bg-light text-center h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-1">{{ $status ?? 'Tidak Diketahui' }}</h6>
                                    <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted py-3">Belum ada data tenaga pendidik.</div>
                        @endforelse
                    </div>
                </div>

                <!-- SECTION: Table Tenaga Pendidik -->
                <div class="border-top pt-4">
                    <h5 class="fw-semibold mb-3"><i class="bx bx-group me-2 text-primary"></i>Data Tenaga Pendidik</h5>
                    <div class="table-responsive">
                        <table id="tenaga-pendidik-table" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status Kepegawaian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($madrasah->tenagaPendidikUsers as $tp)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-medium">{{ $tp->name }}</td>
                                    <td>{{ $tp->email }}</td>
                                    <td><span class="badge bg-info">{{ $tp->statusKepegawaian->name ?? '-' }}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary view-btn"
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
                                            data-mengajar="{{ $tp->mengajar ?? '-' }}">
                                            <i class="bx bx-show-alt"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bx bx-info-circle me-2"></i>Belum ada data tenaga pendidik
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

<!-- Modal Detail -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-user me-2"></i> Detail Tenaga Pendidik</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="modal-avatar" src="" alt="Avatar" class="rounded-circle mb-3" style="width:120px; height:120px; object-fit:cover;">
                        <h5 id="modal-name" class="fw-bold text-primary"></h5>
                        <p class="text-muted mb-1"><i class="bx bx-envelope me-1"></i><span id="modal-email"></span></p>
                        <span class="badge bg-success" id="modal-status"></span>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-6"><strong>No HP:</strong><br><span id="modal-no_hp">-</span></div>
                            <div class="col-6"><strong>NIP:</strong><br><span id="modal-nip">-</span></div>
                            <div class="col-6"><strong>NUPTK:</strong><br><span id="modal-nuptk">-</span></div>
                            <div class="col-6"><strong>NPK:</strong><br><span id="modal-npk">-</span></div>
                            <div class="col-6"><strong>Tempat Lahir:</strong><br><span id="modal-tempat_lahir">-</span></div>
                            <div class="col-6"><strong>Tanggal Lahir:</strong><br><span id="modal-tanggal_lahir">-</span></div>
                            <div class="col-12"><strong>Alamat:</strong><br><span id="modal-alamat">-</span></div>
                            <div class="col-6"><strong>Kartanu:</strong><br><span id="modal-kartanu">-</span></div>
                            <div class="col-6"><strong>Pendidikan:</strong><br><span id="modal-pendidikan_terakhir">-</span></div>
                            <div class="col-6"><strong>Tahun Lulus:</strong><br><span id="modal-tahun_lulus">-</span></div>
                            <div class="col-6"><strong>Program Studi:</strong><br><span id="modal-program_studi">-</span></div>
                            <div class="col-6"><strong>TMT:</strong><br><span id="modal-tmt">-</span></div>
                            <div class="col-6"><strong>Ketugasan:</strong><br><span id="modal-ketugasan">-</span></div>
                            <div class="col-12"><strong>Mengajar:</strong><br><span id="modal-mengajar">-</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Scripts -->
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(function() {
    const table = $("#tenaga-pendidik-table").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["excel", "pdf", "print"]
    });

    table.buttons().container()
        .appendTo('#tenaga-pendidik-table_wrapper .col-md-6:eq(0)');

    $('#tenaga-pendidik-table').on('click', '.view-btn', function() {
        const data = $(this).data();
        for (const key in data) {
            $('#modal-' + key).text(data[key]);
        }
        $('#modal-avatar').attr('src', data.avatar);
        $('#viewModal').modal('show');
    });
});
</script>
@endsection
