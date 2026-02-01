@extends('layouts.master')

@section('title')
    Pending Registrations
@endsection

@section('css')
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'pengurus']);
@endphp
@if($isAllowed)
    @component('components.breadcrumb')
        @slot('li_1') Master Data @endslot
        @slot('title') Pending Registrations @endslot
    @endcomponent

    <div class="card mb-4">
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

            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Jabatan</th>
                            <th>Asal Sekolah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingRegistrations as $index => $registration)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $registration->name }}</td>
                                <td>{{ $registration->email }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ ucfirst($registration->role) }}</span>
                                </td>
                                <td>{{ $registration->jabatan ?: '-' }}</td>
                                <td>{{ optional($registration->madrasah)->name ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.pending-registrations.approve', $registration->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Are you sure you want to approve this registration?')">Approve</button>
                                    </form>

                                    <form action="{{ route('admin.pending-registrations.reject', $registration->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to reject this registration?')">Reject</button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr class="empty-row">
                                <td colspan="7" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>No pending registrations found</strong><br>
                                        <small>All registrations have been processed.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @else
    <div class="alert alert-danger text-center">
        <h4>Akses Ditolak</h4>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    </div>
    @endif
    @endsection
    @section('script')
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
                if ($('#datatable-buttons tbody tr').not('.empty-row').length > 0) {
                    let table = $("#datatable-buttons").DataTable({
                        responsive: true,
                        lengthChange: true,
                        autoWidth: false,
                        dom: 'Bfrtip',
                        buttons: ["copy", "excel", "pdf", "print", "colvis"]
                    });
                }
            });
        </script>
    @endsection
