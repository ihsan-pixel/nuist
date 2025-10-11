@extends('layouts.master')

@section('title') Detail Tenaga Pendidik @endsection

@section('content')
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Tenaga Pendidik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="modal-avatar" src="" alt="Avatar" class="rounded-circle mb-3" width="100" height="100">
                        <h5 id="modal-name"></h5>
                        <p class="text-muted"><i class="bx bx-envelope me-1"></i><span id="modal-email"></span></p>
                        <span class="badge bg-success" id="modal-status"></span>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-phone text-primary me-1"></i>No HP:</strong><br>
                                <span id="modal-no_hp">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NIP:</strong><br>
                                <span id="modal-nip">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NUPTK:</strong><br>
                                <span id="modal-nuptk">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-id-card text-primary me-1"></i>NPK:</strong><br>
                                <span id="modal-npk">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-map-pin text-primary me-1"></i>Tempat Lahir:</strong><br>
                                <span id="modal-tempat_lahir">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar text-primary me-1"></i>Tanggal Lahir:</strong><br>
                                <span id="modal-tanggal_lahir">-</span>
                            </div>
                            <div class="col-12 mb-2">
                                <strong><i class="bx bx-home text-primary me-1"></i>Alamat:</strong><br>
                                <span id="modal-alamat">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-card text-primary me-1"></i>Kartanu:</strong><br>
                                <span id="modal-kartanu">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-graduation text-primary me-1"></i>Pendidikan:</strong><br>
                                <span id="modal-pendidikan_terakhir">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar-check text-primary me-1"></i>Tahun Lulus:</strong><br>
                                <span id="modal-tahun_lulus">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-book text-primary me-1"></i>Program Studi:</strong><br>
                                <span id="modal-program_studi">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-calendar-event text-primary me-1"></i>TMT:</strong><br>
                                <span id="modal-tmt">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-briefcase text-primary me-1"></i>Ketugasan:</strong><br>
                                <span id="modal-ketugasan">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="bx bx-chalkboard text-primary me-1"></i>Mengajar:</strong><br>
                                <span id="modal-mengajar">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
