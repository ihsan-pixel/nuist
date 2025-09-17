@extends('layouts.master')

@section('title')
    Madrasah/Sekolah
@endsection

@section('css')
    <link href="{{ URL::asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin']);
@endphp
@if($isAllowed)
    @component('components.breadcrumb')
        @slot('li_1') Master Data @endslot
        @slot('title') Madrasah/Sekolah @endslot
    @endcomponent

    <div class="card mb-4">
        <div class="card-body">

            <div class="mb-3 d-flex justify-content-end gap-2 @if(strtolower(auth()->user()->role) == 'admin') d-none @endif">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMadrasah">
                    <i class="bx bx-plus"></i> Tambah Madrasah
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportMadrasah">
                    <i class="bx bx-upload"></i> Import Data
                </button>
            </div>

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
                            <th>Logo</th>
                            <th>Nama Madrasah/Sekolah</th>
                            <th>Alamat</th>
                            <th>Lokasi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($madrasahs as $index => $madrasah)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($madrasah->logo)
                                        <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="Logo" width="50">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $madrasah->name }}</td>
                                <td>{{ $madrasah->alamat ?? '-' }}</td>
                                <td>
                                    @if($madrasah->latitude && $madrasah->longitude)
                                        <small>{{ $madrasah->latitude }}, {{ $madrasah->longitude }}</small>
                                        @if($madrasah->map_link)
                                            <br><a href="{{ $madrasah->map_link }}" target="_blank" class="btn btn-sm btn-info">Lihat Map</a>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditMadrasah{{ $madrasah->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('madrasah.destroy', $madrasah->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger @if(strtolower(auth()->user()->role) == 'admin') d-none @endif"
                                            onclick="return confirm('Yakin hapus madrasah ini?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Madrasah -->
                            <div class="modal fade" id="modalEditMadrasah{{ $madrasah->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('madrasah.update', $madrasah->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Madrasah</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama Madrasah/Sekolah</label>
                            <input type="text" name="name" class="form-control" value="{{ $madrasah->name }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ $madrasah->alamat }}</textarea>
                            @error('alamat')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Latitude</label>
                            <input type="text" name="latitude" class="form-control" value="{{ $madrasah->latitude }}">
                            @error('latitude')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Longitude</label>
                            <input type="text" name="longitude" class="form-control" value="{{ $madrasah->longitude }}">
                            @error('longitude')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Link Map</label>
                            <input type="text" name="map_link" class="form-control" value="{{ $madrasah->map_link }}">
                            @error('map_link')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin diubah</small>
                        </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>Belum ada data Madrasah</strong><br>
                                        <small>Silakan tambahkan data madrasah terlebih dahulu untuk melanjutkan.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Madrasah -->
    <div class="modal fade" id="modalTambahMadrasah" tabindex="-1" aria-labelledby="modalTambahMadrasahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('madrasah.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahMadrasahLabel">Tambah Madrasah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Madrasah/Sekolah</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Latitude</label>
                            <input type="text" name="latitude" class="form-control" placeholder="Contoh: -7.7956">
                        </div>
                        <div class="mb-3">
                            <label>Longitude</label>
                            <input type="text" name="longitude" class="form-control" placeholder="Contoh: 110.3695">
                        </div>
                        <div class="mb-3">
                            <label>Link Map</label>
                            <input type="text" name="map_link" class="form-control" placeholder="https://maps.app.goo.gl/xxxx">
                        </div>
                        <div class="mb-3">
                            <label>Logo Madrasah/Sekolah</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted">Opsional, boleh dikosongkan</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Import Madrasah -->
<div class="modal fade" id="modalImportMadrasah" tabindex="-1" aria-labelledby="modalImportMadrasahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('madrasah.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImportMadrasahLabel">Import Data Madrasah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Pilih File (Excel/CSV)</label>
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                Gunakan template file sesuai format data madrasah.
                                <a href="{{ asset('template/madrasah_template.xlsx') }}" download>Download Template Excel</a>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
            </form>
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
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"]
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
