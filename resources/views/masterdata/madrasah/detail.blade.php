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

<!-- Lucide Icons -->
<link rel="stylesheet" href="https://unpkg.com/lucide@latest/dist/umd/lucide.css">

<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; }

/* Custom modal styles */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); z-index: 1050; display: none; }
.modal-content { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 90vw; max-height: 90vh; overflow-y: auto; z-index: 1051; display: none; animation: modalFadeIn 0.3s ease-out; }
@keyframes modalFadeIn { from { opacity: 0; transform: translate(-50%, -60%); } to { opacity: 1; transform: translate(-50%, -50%); } }
.modal-header { background: #16A085; color: white; padding: 1rem; border-radius: 1rem 1rem 0 0; display: flex; justify-content: space-between; align-items: center; }
.modal-body { padding: 1.5rem; display: flex; gap: 2rem; }
.modal-footer { padding: 1rem; border-top: 1px solid #e9ecef; display: flex; justify-content: flex-end; gap: 0.5rem; }

.btn-green { background: #16A085; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.75rem; transition: all 0.3s; }
.btn-green:hover { background: linear-gradient(135deg, #16A085, #117A65); }
.btn-gray { background: #F8F9FA; color: #333; border: none; padding: 0.5rem 1rem; border-radius: 0.75rem; transition: all 0.3s; }
.btn-gray:hover { background: #e9ecef; }

.profile-photo { width: 120px; height: 120px; border-radius: 50%; border: 4px solid transparent; background: linear-gradient(45deg, #16A085, #117A65) border-box; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; }
.profile-photo:hover { transform: scale(1.05); box-shadow: 0 6px 15px rgba(22, 160, 133, 0.3); }

.status-badge { background: #A8E6CF; color: white; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer; }
.status-badge:hover { opacity: 0.8; }

.grid-data { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
.grid-data > div { display: flex; align-items: center; gap: 0.5rem; }

@media (max-width: 768px) { .modal-body { flex-direction: column; } .grid-data { grid-template-columns: 1fr; } .modal-content { width: 95vw; } }
</style>
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
                        <p class="text-muted">{{ $madrasah->kabupaten ? 'Kabupaten ' . $madrasah->kabupaten : '' }}</p>
                        <p class="text-muted">{{ $madrasah->alamat ?? 'Alamat tidak tersedia' }}</p>
                        <p class="text-muted"><strong>Hari KBM:</strong> {{ $madrasah->hari_kbm ? $madrasah->hari_kbm . ' hari' : 'Tidak ditentukan' }}</p>
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
<div class="modal-overlay" id="modal-overlay"></div>
<div class="modal-content" id="viewModal">
    <div class="modal-header">
        <h5>Detail Tenaga Pendidik</h5>
        <button id="close-modal" aria-label="Close"><i data-lucide="x"></i></button>
    </div>
    <div class="modal-body">
        <div class="left-column" style="flex: 1; text-align: center;">
            <img id="modal-avatar" src="" alt="Avatar" class="profile-photo" style="object-fit: cover;">
            <h5 id="modal-name" style="color: #117A65; font-weight: 600; margin: 1rem 0;"></h5>
            <p style="margin: 0.5rem 0;"><i data-lucide="mail"></i> <span id="modal-email"></span></p>
            <span class="status-badge" id="modal-status" data-bs-toggle="tooltip" title="Status Sertifikasi"></span>
        </div>
        <div class="right-column" style="flex: 2;">
            <div class="grid-data">
                <div><i data-lucide="smartphone"></i> <strong>No HP:</strong> <span id="modal-no_hp">-</span></div>
                <div><i data-lucide="credit-card"></i> <strong>NIP:</strong> <span id="modal-nip">-</span> <button onclick="copyToClipboard('modal-nip')" style="font-size: 0.75rem; padding: 0.25rem; border: none; background: #16A085; color: white; border-radius: 0.25rem;">Copy</button></div>
                <div><i data-lucide="credit-card"></i> <strong>NUPTK:</strong> <span id="modal-nuptk">-</span> <button onclick="copyToClipboard('modal-nuptk')" style="font-size: 0.75rem; padding: 0.25rem; border: none; background: #16A085; color: white; border-radius: 0.25rem;">Copy</button></div>
                <div><i data-lucide="credit-card"></i> <strong>NPK:</strong> <span id="modal-npk">-</span></div>
                <div><i data-lucide="map-pin"></i> <strong>Tempat Lahir:</strong> <span id="modal-tempat_lahir">-</span></div>
                <div><i data-lucide="calendar"></i> <strong>Tanggal Lahir:</strong> <span id="modal-tanggal_lahir">-</span></div>
                <div style="grid-column: span 2;"><i data-lucide="home"></i> <strong>Alamat:</strong> <span id="modal-alamat">-</span></div>
                <div><i data-lucide="graduation-cap"></i> <strong>Pendidikan:</strong> <span id="modal-pendidikan_terakhir">-</span></div>
                <div><i data-lucide="book-open"></i> <strong>Program Studi:</strong> <span id="modal-program_studi">-</span></div>
                <div><i data-lucide="id-card"></i> <strong>Kartanu:</strong> <span id="modal-kartanu">-</span></div>
                <div><i data-lucide="calendar-check"></i> <strong>Tahun Lulus:</strong> <span id="modal-tahun_lulus">-</span></div>
                <div><i data-lucide="calendar-event"></i> <strong>TMT:</strong> <span id="modal-tmt">-</span></div>
                <div><i data-lucide="chalkboard-teacher"></i> <strong>Mengajar:</strong> <span id="modal-mengajar">-</span></div>
                <div><i data-lucide="briefcase"></i> <strong>Ketugasan:</strong> <span id="modal-ketugasan">-</span></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn-green">Edit Data</button>
        <button class="btn-gray" id="close-modal-footer">Tutup</button>
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
        $('#modal-avatar').attr('src', data.avatar);

        // Show modal
        $('#modal-overlay').show();
        $('#viewModal').show();

        // Initialize tooltips
        $('#modal-status').tooltip();

        // Initialize Lucide icons
        lucide.createIcons();
    });

    // Close modal handlers
    $('#close-modal, #close-modal-footer').click(function() {
        $('#modal-overlay').hide();
        $('#viewModal').hide();
    });

    $('#modal-overlay').click(function() {
        $('#modal-overlay').hide();
        $('#viewModal').hide();
    });

    // Copy to clipboard function
    window.copyToClipboard = function(id) {
        const text = document.getElementById(id).textContent;
        navigator.clipboard.writeText(text).then(() => {
            // Simple feedback
            alert('Copied to clipboard!');
        });
    };

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
