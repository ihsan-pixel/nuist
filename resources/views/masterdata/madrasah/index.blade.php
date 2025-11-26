@extends('layouts.master')

@section('title')
    Madrasah/Sekolah
@endsection

@section('css')

    {{-- Template Base --}}
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />

    {{-- DataTables --}}
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

    <style>
        .polygon-checklist {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 10px;
            margin-top: 10px;
        }

        .polygon-checklist-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            font-size: 0.875rem;
        }

        .polygon-checklist-item i {
            margin-right: 8px;
            width: 16px;
        }

        .polygon-checklist-item.success i {
            color: #28a745;
        }

        .polygon-checklist-item.warning i {
            color: #ffc107;
        }

        .polygon-checklist-item.danger i {
            color: #dc3545;
        }

        .leaflet-draw-toolbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2) !important;
        }

        .leaflet-draw-toolbar a {
            background-color: #3388ff !important;
            color: white !important;
            border-radius: 0.375rem !important;
            margin: 2px !important;
            width: 30px !important;
            height: 30px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .leaflet-draw-toolbar a:hover {
            background-color: #0066cc !important;
        }

        .leaflet-draw-toolbar a.leaflet-draw-edit-edit,
        .leaflet-draw-toolbar a.leaflet-draw-edit-remove {
            background-color: #ffc107 !important;
        }

        .leaflet-draw-toolbar a.leaflet-draw-edit-edit:hover,
        .leaflet-draw-toolbar a.leaflet-draw-edit-remove:hover {
            background-color: #e0a800 !important;
        }

        .leaflet-draw-toolbar a.leaflet-draw-edit-remove {
            background-color: #dc3545 !important;
        }

        .leaflet-draw-toolbar a.leaflet-draw-edit-remove:hover {
            background-color: #c82333 !important;
        }
    </style>

    <style>
        .polygon-map-container {
            height: 400px !important;
            width: 100% !important;
            border-radius: 8px !important;
            border: 2px solid #dee2e6 !important;
            overflow: hidden !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
            background: #f8f9fa !important;
            position: relative !important;
        }

        .polygon-map-container .leaflet-container {
            height: 100% !important;
            width: 100% !important;
            border-radius: 0.5rem;
        }

        .polygon-info {
            margin-top: 10px;
            padding: 6px;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            border-left: 4px solid #3388ff;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        .polygon-coordinates {
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 6px;
            border-radius: 0.5rem;
            max-height: 100px;
            overflow-y: auto;
            margin-top: 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.7rem;
            word-break: break-all;
            line-height: 1.3;
        }

        #map-add,
        [id^="map-edit-"] {
            height: 300px !important;
            width: 100% !important;
            border-radius: 0.5rem;
            position: relative !important;
            min-height: 300px !important;
            display: block !important;
            visibility: visible !important;
        }

        .leaflet-container {
            z-index: 1;
            border-radius: 0.5rem;
        }

        .modal .leaflet-container {
            z-index: 1051 !important;
            border-radius: 0.5rem;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        /* Toolbar styling */
        .leaflet-toolbar {
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 1000 !important;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2) !important;
        }

        .leaflet-toolbar button {
            padding: 10px 12px !important;
            margin: 0 !important;
            border: none !important;
            border-radius: 0.375rem !important;
            cursor: pointer !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            white-space: nowrap !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15) !important;
            min-width: 90px !important;
        }

        .leaflet-toolbar button:hover {
        }

        .leaflet-toolbar button:active {
            transform: translateY(0);
        }
    </style>
@endsection

@section('content')
@php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus']);
@endphp

@if($isAllowed)
    @component('components.breadcrumb')
        @slot('li_1') Master Data @endslot
        @slot('title') Madrasah/Sekolah @endslot
    @endcomponent

    <div class="card mb-4">
        <div class="card-body">

            <div class="mb-3 d-flex justify-content-end gap-2 @if($userRole == 'admin') d-none @endif">
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
                            <th>Kabupaten</th>
                            <th>Alamat</th>
                            <th>Hari KBM</th>
                            <th>Lokasi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($madrasahs as $madrasah)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($madrasah->logo)
                                        <img src="{{ asset('storage/' . $madrasah->logo) }}"
                                            alt="Logo {{ $madrasah->name }}"
                                            width="50" class="img-thumbnail" style="object-fit: contain;">
                                    @else
                                        <span class="text-muted">
                                            <i class="bx bx-image-alt"></i> Tidak ada logo
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $madrasah->name }}</td>
                                <td>{{ $madrasah->kabupaten ?? '-' }}</td>
                                <td>{{ $madrasah->alamat ?? '-' }}</td>
                                <td>{{ $madrasah->hari_kbm ? $madrasah->hari_kbm . ' hari' : '-' }}</td>
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
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $madrasah->id }}">Edit</button>

                                    <form action="{{ route('madrasah.destroy', $madrasah->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger @if($userRole == 'admin') d-none @endif" onclick="return confirm('Yakin hapus madrasah ini?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center p-4">
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

    {{-- MODALS (diletakkan di luar tabel agar tidak merusak layout/map) --}}

    {{-- Modal Tambah Madrasah --}}
    <div class="modal fade" id="modalTambahMadrasah" tabindex="-1" aria-labelledby="modalTambahMadrasahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('madrasah.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahMadrasahLabel">Tambah Madrasah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Nama Madrasah/Sekolah</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Kabupaten</label>
                                    <select name="kabupaten" class="form-select">
                                        <option value="">Pilih Kabupaten</option>
                                        <option value="Kabupaten Bantul">Kabupaten Bantul</option>
                                        <option value="Kabupaten Gunungkidul">Kabupaten Gunungkidul</option>
                                        <option value="Kabupaten Kulon Progo">Kabupaten Kulon Progo</option>
                                        <option value="Kabupaten Sleman">Kabupaten Sleman</option>
                                        <option value="Kota Yogyakarta">Kota Yogyakarta</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3"></textarea>
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
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Hari KBM</label>
                                    <select name="hari_kbm" class="form-select">
                                        <option value="">Pilih Hari KBM</option>
                                        <option value="5">5 Hari</option>
                                        <option value="6">6 Hari</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Area Polygon Presensi</label>
                                    <div id="map-add" class="polygon-map-container" style="height:300px;">
                                        <!-- Loading indicator -->
                                        <div id="loading-map-add" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; color: #6c757d; text-align: center;">
                                            <i class="mdi mdi-loading mdi-spin" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-2 small">Memuat peta...</p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="polygon_koordinat" id="polygon_koordinat_add" value="[]">
                                    <small class="text-muted">Gunakan toolbar pada peta untuk menggambar area polygon presensi.</small>
                                </div>

                                <div class="mb-3">
                                    <label>Logo Madrasah/Sekolah</label>
                                    <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                                    <small class="text-muted">Opsional, Maks 2MB (JPG, PNG, JPEG)</small>
                                    <div id="logoPreview" class="mt-2" style="display:none;">
                                        <img id="previewImage" src="" alt="Preview Logo" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="clearLogoPreview()">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
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

    {{-- Modal Import Madrasah --}}
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

    {{-- Generate Edit Modals (rendered at bottom to keep DOM stable). We render one modal per madrasah but keep them outside the table to avoid layout/map issues. --}}
    @foreach($madrasahs as $madrasah)
    <div class="modal fade" id="modalEditMadrasah{{ $madrasah->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('madrasah.update', $madrasah->id) }}" method="POST" enctype="multipart/form-data" class="form-edit-madrasah">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $madrasah->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Madrasah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Nama Madrasah/Sekolah</label>
                                    <input type="text" name="name" class="form-control" value="{{ $madrasah->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Kabupaten</label>
                                    <select name="kabupaten" class="form-select">
                                        <option value="">Pilih Kabupaten</option>
                                        <option value="Kabupaten Bantul" {{ $madrasah->kabupaten == 'Kabupaten Bantul' ? 'selected' : '' }}>Kabupaten Bantul</option>
                                        <option value="Kabupaten Gunungkidul" {{ $madrasah->kabupaten == 'Kabupaten Gunungkidul' ? 'selected' : '' }}>Kabupaten Gunungkidul</option>
                                        <option value="Kabupaten Kulon Progo" {{ $madrasah->kabupaten == 'Kabupaten Kulon Progo' ? 'selected' : '' }}>Kabupaten Kulon Progo</option>
                                        <option value="Kabupaten Sleman" {{ $madrasah->kabupaten == 'Kabupaten Sleman' ? 'selected' : '' }}>Kabupaten Sleman</option>
                                        <option value="Kota Yogyakarta" {{ $madrasah->kabupaten == 'Kota Yogyakarta' ? 'selected' : '' }}>Kota Yogyakarta</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="2">{{ $madrasah->alamat }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control" value="{{ $madrasah->latitude }}">
                                </div>
                                <div class="mb-3">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control" value="{{ $madrasah->longitude }}">
                                </div>
                                <div class="mb-3">
                                    <label>Link Map</label>
                                    <input type="text" name="map_link" class="form-control" value="{{ $madrasah->map_link }}">
                                </div>
                                <div class="mb-3">
                                    <label>Hari KBM</label>
                                    <select name="hari_kbm" class="form-select">
                                        <option value="">Pilih Hari KBM</option>
                                        <option value="5" {{ $madrasah->hari_kbm == '5' ? 'selected' : '' }}>5 Hari</option>
                                        <option value="6" {{ $madrasah->hari_kbm == '6' ? 'selected' : '' }}>6 Hari</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Logo</label>
                                    <input type="file" name="logo" id="editLogoInput{{ $madrasah->id }}" class="form-control edit-logo-input" accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin diubah. Maks 2MB.</small>
                                    @if($madrasah->logo)
                                        <div class="mt-2">
                                            <label class="form-label">Logo Saat Ini:</label><br>
                                            <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="Current Logo" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                        </div>
                                    @endif
                                    <div id="editLogoPreview{{ $madrasah->id }}" class="mt-2" style="display:none;">
                                        <label class="form-label">Preview Logo Baru:</label><br>
                                        <img id="editPreviewImage{{ $madrasah->id }}" src="" alt="Preview Logo" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="clearEditLogoPreview({{ $madrasah->id }})">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                {{-- Polygon Koordinat Section --}}
                                <div class="mb-3">
                                    <label>Area Polygon Presensi Utama</label>
                                    <input type="hidden" name="polygon_koordinat" id="polygon_koordinat-edit-{{ $madrasah->id }}" value="{{ $madrasah->polygon_koordinat ?? '[]' }}">
                                    <div class="polygon-map-container" id="map-edit-{{ $madrasah->id }}" style="height: 300px; width: 100%; border-radius: 8px; border: 2px solid #dee2e6; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: #f8f9fa; position: relative;">
                                        <!-- Loading indicator -->
                                        <div id="loading-map-edit-{{ $madrasah->id }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; color: #6c757d; text-align: center;">
                                            <i class="mdi mdi-loading mdi-spin" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-2 small">Memuat peta...</p>
                                        </div>
                                        <!-- Map will be initialized here -->
                                    </div>
                                    <div class="polygon-info mt-2">
                                        <div id="polygon-display-{{ $madrasah->id }}">
                                            @if($madrasah->polygon_koordinat)
                                                @php
                                                    $coords = json_decode($madrasah->polygon_koordinat, true);
                                                    $pointCount = isset($coords['coordinates'][0]) ? count($coords['coordinates'][0]) : 0;
                                                @endphp
                                                <strong>Jumlah titik:</strong> {{ $pointCount }}<br>
                                                <strong>Format:</strong> GeoJSON (Longitude, Latitude)<br>
                                                <strong>Data JSON:</strong><br>
                                                {{-- <code>{{ $madrasah->polygon_koordinat }}</code> --}}
                                            @else
                                                <small class="text-muted">Belum ada poligon. Gunakan tool drawing untuk menambahkan.</small>
                                            @endif
                                        </div>
                                    </div>
                                    <small class="text-muted">Gunakan toolbar pada peta untuk menggambar, mengedit, atau menghapus area polygon presensi utama.</small>
                                </div>

                                @if($madrasah->polygon_koordinat_2 || in_array($madrasah->id, [24, 26, 33]))
                                {{-- Dual Polygon Section --}}
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="enable_dual_polygon" id="enable_dual_polygon-edit-{{ $madrasah->id }}" value="1" {{ $madrasah->enable_dual_polygon ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_dual_polygon-edit-{{ $madrasah->id }}">
                                            Aktifkan Poligon Kedua
                                        </label>
                                    </div>
                                    <small class="text-muted">Centang untuk mengaktifkan area poligon presensi kedua.</small>
                                </div>

                                <div class="mb-3" id="polygon2-container-edit-{{ $madrasah->id }}" style="display: {{ $madrasah->enable_dual_polygon ? 'block' : 'none' }};">
                                    <label>Area Polygon Presensi Kedua</label>
                                    <input type="hidden" name="polygon_koordinat_2" id="polygon_koordinat_2-edit-{{ $madrasah->id }}" value="{{ $madrasah->polygon_koordinat_2 ?? '[]' }}">
                                    <div class="polygon-map-container" id="map2-edit-{{ $madrasah->id }}" style="height: 300px; width: 100%; border-radius: 8px; border: 2px solid #dee2e6; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: #f8f9fa; position: relative;">
                                        <!-- Loading indicator -->
                                        <div id="loading-map2-edit-{{ $madrasah->id }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; color: #6c757d; text-align: center;">
                                            <i class="mdi mdi-loading mdi-spin" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-2 small">Memuat peta kedua...</p>
                                        </div>
                                        <!-- Map will be initialized here -->
                                    </div>
                                    <div class="polygon-info mt-2">
                                        <div id="polygon2-display-{{ $madrasah->id }}">
                                            @if($madrasah->polygon_koordinat_2)
                                                @php
                                                    $coords2 = json_decode($madrasah->polygon_koordinat_2, true);
                                                    $pointCount2 = isset($coords2['coordinates'][0]) ? count($coords2['coordinates'][0]) : 0;
                                                @endphp
                                                <strong>Jumlah titik:</strong> {{ $pointCount2 }}<br>
                                                <strong>Format:</strong> GeoJSON (Longitude, Latitude)<br>
                                                <strong>Data JSON:</strong><br>
                                                {{-- <code>{{ $madrasah->polygon_koordinat_2 }}</code> --}}
                                            @else
                                                <small class="text-muted">Belum ada poligon kedua. Gunakan tool drawing untuk menambahkan.</small>
                                            @endif
                                        </div>
                                        <div class="polygon-checklist" id="polygon2-checklist-{{ $madrasah->id }}">
                                            <div class="polygon-checklist-item {{ $madrasah->enable_dual_polygon ? 'success' : 'warning' }}">
                                                <i class="bx bx-{{ $madrasah->enable_dual_polygon ? 'check-circle' : 'info-circle' }}"></i>
                                                <span>Dual Polygon: {{ $madrasah->enable_dual_polygon ? 'Aktif' : 'Tidak aktif' }}</span>
                                            </div>
                                            <div class="polygon-checklist-item {{ $madrasah->polygon_koordinat_2 ? 'success' : 'danger' }}">
                                                <i class="bx bx-{{ $madrasah->polygon_koordinat_2 ? 'check-circle' : 'x-circle' }}"></i>
                                                <span>Poligon Kedua: {{ $madrasah->polygon_koordinat_2 ? 'Tersedia' : 'Belum ada' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">Gunakan toolbar pada peta untuk menggambar, mengedit, atau menghapus area polygon presensi kedua.</small>
                                </div>
                                @endif
                            </div>
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
    @endforeach

@else
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</div>
@endif
@endsection

@section('script')
    {{-- DataTables scripts --}}
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

    {{-- Leaflet scripts for polygon editing --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js" integrity="sha512-ozq65x0Vw2rFeEbSpgDJUb+xBzvfqn6U9G3fjEWr0y+qdcWVgJRapgKoEHLmZKLEd5X6lxCOJLQKFkRDuM+C8A==" crossorigin=""></script>

    <script>
        // Flag to indicate Leaflet is loaded
        window.leafletDraw = true;
    </script>

    <script>
    (function(){
        // ---------------------- Helper utilities ----------------------
        const el = (selector, ctx = document) => ctx.querySelector(selector);        const els = (selector, ctx = document) => Array.from(ctx.querySelectorAll(selector));

        // ---------------------- Logo preview (add) ----------------------
        const logoInput = el('#logoInput');
        if (logoInput) {
            logoInput.addEventListener('change', function(e){
                const file = this.files[0];
                const preview = el('#logoPreview');
                const previewImage = el('#previewImage');
                if (!file) { if(preview) preview.style.display='none'; return; }
                if (file.size > 2 * 1024 * 1024) { alert('Ukuran file terlalu besar. Maks 2MB.'); this.value=''; return; }
                if (!file.type.match('image.*')) { alert('File harus berupa gambar.'); this.value=''; return; }
                const reader = new FileReader();
                reader.onload = function(evt){ previewImage.src = evt.target.result; preview.style.display = 'block'; }
                reader.readAsDataURL(file);
            });
        }
        window.clearLogoPreview = function(){ if(el('#logoInput')) el('#logoInput').value=''; if(el('#logoPreview')) el('#logoPreview').style.display='none'; }

        // ---------------------- Edit logo previews (delegated) ----------------------
        document.addEventListener('change', function(e){
            const t = e.target;
            if (t && t.classList.contains('edit-logo-input')){
                const id = t.id.replace('editLogoInput','');
                const file = t.files[0];
                const preview = el('#editLogoPreview'+id);
                const previewImage = el('#editPreviewImage'+id);
                if (!file) { if(preview) preview.style.display='none'; return; }
                if (file.size > 2 * 1024 * 1024) { alert('Ukuran file terlalu besar. Maks 2MB.'); t.value=''; return; }
                if (!file.type.match('image.*')) { alert('File harus berupa gambar.'); t.value=''; return; }
                const reader = new FileReader();
                reader.onload = function(evt){ if(previewImage) previewImage.src = evt.target.result; if(preview) preview.style.display='block'; }
                reader.readAsDataURL(file);
            }
        });

        window.clearEditLogoPreview = function(id){ const input = el('#editLogoInput'+id); const preview = el('#editLogoPreview'+id); if(input) input.value=''; if(preview) preview.style.display='none'; }

        // ---------------------- DataTable init (prevent double init) ----------------------
        let dataTableInstance = null;
        const initDataTable = () => {
            if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
                dataTableInstance = $('#datatable-buttons').DataTable();
                return;
            }
            dataTableInstance = $('#datatable-buttons').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"]
            });
            dataTableInstance.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        };

        // ---------------------- Polygon Map Management ----------------------
        // Store maps by madrasah ID to prevent conflicts
        const polygonMaps = {};
        const polygonEditableLayers = {};
        const polygonDrawingMode = {};
        const dualPolygonMaps = {};
        const dualPolygonEditableLayers = {};

        /**
         * Universal Leaflet Map Fix - Force multiple invalidates to stabilize layout
         */
        function forceFixLeafletMap(map) {
            // Trigger invalidates multiple times to stabilize layout
            setTimeout(() => map.invalidateSize(true), 200);   // early stage
            setTimeout(() => map.invalidateSize(true), 600);   // layout stabilized
            setTimeout(() => map.invalidateSize(true), 1000);  // tile load stabilize
            setTimeout(() => map.invalidateSize(true), 1500);  // final correction
        }

        /**
         * Wait for Leaflet to be available
         */
        function waitForLeaflet() {
            return new Promise((resolve) => {
                if (typeof L !== 'undefined') {
                    resolve();
                } else {
                    const checkInterval = setInterval(() => {
                        if (typeof L !== 'undefined') {
                            clearInterval(checkInterval);
                            resolve();
                        }
                    }, 100);
                    // Timeout after 5 seconds
                    setTimeout(() => {
                        clearInterval(checkInterval);
                        resolve();
                    }, 5000);
                }
            });
        }

        /**
         * Initialize dual polygon map for a specific madrasah (second polygon)
         * @param {number} madrasahId - ID of the madrasah
         */
        async function initDualPolygonMap(madrasahId) {
            await waitForLeaflet();

            const mapContainer = el('#map2-edit-' + madrasahId);
            const polygonInput = el('#polygon_koordinat_2-edit-' + madrasahId);
            const polygonDisplay = el('#polygon2-display-' + madrasahId);
            const checklist = el('#polygon2-checklist-' + madrasahId);

            if (!mapContainer || !polygonInput || typeof L === 'undefined') {
                console.error('Dual polygon map container or Leaflet not found for madrasah ID:', madrasahId);
                return;
            }

            // Check if dual map is already initialized on this container
            if (dualPolygonMaps[madrasahId]) {
                console.warn('Dual map already exists for madrasah ID:', madrasahId, '- skipping initialization');
                return;
            }

            // Double-check that the container doesn't already have a map
            if (mapContainer._leaflet_id) {
                console.warn('Dual map container already has a map for madrasah ID:', madrasahId, '- skipping initialization');
                return;
            }

            try {
                // Create map centered on Indonesia (default)
                const map = L.map('map2-edit-' + madrasahId, {
                    center: [-7.7956, 110.3695],
                    zoom: 13,
                    zoomControl: true
                });

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);

                // Initialize FeatureGroup to store editable layers
                const drawnItems = new L.FeatureGroup();
                map.addLayer(drawnItems);

                // Initialize the draw control and pass it the FeatureGroup of editable layers
                console.log('Adding draw control to dual map for madrasah ID:', madrasahId);
                const drawControl = new L.Control.Draw({
                    position: 'topleft',
                    edit: {
                        featureGroup: drawnItems,
                        remove: true
                    },
                    draw: {
                        polygon: {
                            allowIntersection: false,
                            showArea: true,
                            drawError: {
                                color: '#e1e100',
                                message: '<strong>Error:</strong> Shape edges cannot cross!'
                            },
                            shapeOptions: {
                                color: '#28a745',
                                fillOpacity: 0.2,
                                weight: 2
                            }
                        },
                        polyline: false,
                        rectangle: false,
                        circle: false,
                        marker: false,
                        circlemarker: false
                    }
                });

                try {
                    map.addControl(drawControl);
                    console.log('Draw control added successfully to dual map for madrasah ID:', madrasahId);
                } catch (drawError) {
                    console.error('Failed to add draw control to dual map:', drawError);
                }

                // Load existing polygon from database if available
                const existingCoordinates = polygonInput.value;
                console.log('Loading existing dual polygon data for madrasah ID:', madrasahId, 'Data:', existingCoordinates);
                if (existingCoordinates && existingCoordinates !== '[]' && existingCoordinates !== '' && existingCoordinates !== 'null') {
                    try {
                        const coordData = JSON.parse(existingCoordinates);
                        console.log('Parsed dual coordinate data for madrasah ID:', madrasahId, coordData);
                        if (coordData && coordData.coordinates && coordData.coordinates.length > 0 && coordData.coordinates[0].length > 0) {
                            // GeoJSON format: { type: "Polygon", coordinates: [[[lon,lat], [lon,lat], ...]] }
                            // Convert [lon,lat] to [lat,lon] for Leaflet
                            const geoJSONCoords = coordData.coordinates[0];
                            const leafletCoords = geoJSONCoords.map(coord => [coord[1], coord[0]]);
                            console.log('Converted dual polygon to Leaflet coords for madrasah ID:', madrasahId, leafletCoords);

                            const polygon = L.polygon(leafletCoords, {
                                color: '#28a745',
                                fillOpacity: 0.2,
                                weight: 2
                            });
                            drawnItems.addLayer(polygon);
                            console.log('Dual polygon added to map for madrasah ID:', madrasahId, polygon);

                            // Fit map to polygon bounds
                            setTimeout(() => {
                                try {
                                    map.fitBounds(polygon.getBounds());
                                    console.log('Dual map fitted to polygon bounds for madrasah ID:', madrasahId);
                                } catch (boundsError) {
                                    console.warn('Could not fit dual polygon bounds for madrasah ID:', madrasahId, boundsError);
                                }
                            }, 100);
                            updatePolygonDisplay(coordData, polygonDisplay, checklist);
                            console.log('Dual polygon display updated for madrasah ID:', madrasahId);
                        } else {
                            console.log('No valid coordinates found in dual polygon data for madrasah ID:', madrasahId);
                        }
                    } catch (e) {
                        console.warn('Failed to parse existing dual polygon coordinates for madrasah ID:', madrasahId, e);
                        console.warn('Raw dual polygon data for madrasah ID:', madrasahId, existingCoordinates);
                    }
                } else {
                    console.log('No existing dual polygon data to load for madrasah ID:', madrasahId);
                    // Add a test polygon to verify dual map is working
                    console.log('Adding test dual polygon to verify map functionality for madrasah ID:', madrasahId);
                    const testDualCoords = [
                        [-7.7956, 110.3715],
                        [-7.7966, 110.3725],
                        [-7.7976, 110.3715],
                        [-7.7966, 110.3705]
                    ];

                    const testDualPolygon = L.polygon(testDualCoords, {
                        color: '#4ecdc4',
                        fillOpacity: 0.1,
                        weight: 2,
                        dashArray: '5, 5'
                    });
                    drawnItems.addLayer(testDualPolygon);

                    // Add a label to indicate this is a test polygon
                    const testDualLabel = L.marker([-7.7966, 110.3715]).addTo(map)
                        .bindPopup('Test Dual Polygon - Map is working!')
                        .openPopup();

                    console.log('Test dual polygon added for verification for madrasah ID:', madrasahId);
                }

                // Hide loading indicator for dual map
                const dualLoadingIndicator = el('#loading-map2-edit-' + madrasahId);
                if (dualLoadingIndicator) {
                    dualLoadingIndicator.style.display = 'none';
                }

                // Helper function to update polygon data for dual polygon
                function updatePolygonData(polygonInput, polygonDisplay, checklist) {
                    const layers = drawnItems.getLayers();
                    if (layers.length > 0 && layers[0] instanceof L.Polygon) {
                        const polygon = layers[0];
                        // Leaflet uses [lat,lng], convert to GeoJSON format [lon,lat]
                        const coordinates = polygon.getLatLngs()[0].map(latlng => [latlng.lng, latlng.lat]);
                        const geoJSON = {
                            type: 'Polygon',
                            coordinates: [coordinates]
                        };
                        polygonInput.value = JSON.stringify(geoJSON);
                        updatePolygonDisplay(geoJSON, polygonDisplay, checklist);
                    } else {
                        polygonInput.value = '[]';
                        if (polygonDisplay) {
                            polygonDisplay.innerHTML = '<small class="text-muted">Belum ada poligon kedua. Gunakan tool drawing untuk menambahkan.</small>';
                        }
                        if (checklist) {
                            const checklistItems = checklist.querySelectorAll('.polygon-checklist-item');
                            checklistItems.forEach(item => {
                                if (item.innerHTML.includes('Poligon Kedua:')) {
                                    const icon = item.querySelector('i');
                                    const span = item.querySelector('span');
                                    if (icon && span) {
                                        icon.className = 'bx bx-x-circle';
                                        item.classList.remove('success');
                                        item.classList.add('danger');
                                        span.textContent = 'Poligon Kedua: Belum ada';
                                    }
                                }
                            });
                        }
                    }
                }

                // Event handlers for draw actions
                map.on(L.Draw.Event.CREATED, function (event) {
                    const layer = event.layer;
                    // Clear existing layers to ensure only one polygon at a time
                    drawnItems.clearLayers();
                    drawnItems.addLayer(layer);
                    updatePolygonData(polygonInput, polygonDisplay, checklist);
                });

                map.on(L.Draw.Event.EDITED, function (event) {
                    const layers = event.layers;
                    layers.eachLayer(function (layer) {
                        // Layer has been edited
                    });
                    updatePolygonData(polygonInput, polygonDisplay, checklist);
                });

                map.on(L.Draw.Event.DELETED, function (event) {
                    const layers = event.layers;
                    layers.eachLayer(function (layer) {
                        // Layer has been deleted
                    });
                    updatePolygonData(polygonInput, polygonDisplay, checklist);
                });

                // Store map and layer references
                dualPolygonMaps[madrasahId] = map;
                dualPolygonEditableLayers[madrasahId] = drawnItems;

                // Apply universal map fix after initialization
                setTimeout(() => {
                    if (map) forceFixLeafletMap(map);
                }, 300);
            } catch (e) {
                console.error('Error initializing dual polygon map:', e);
            }
        }

        /**
         * Initialize polygon map for a specific madrasah (primary polygon)
         * @param {number} madrasahId - ID of the madrasah
         */
        async function initPolygonMap(madrasahId) {
            await waitForLeaflet();

            const mapContainer = el('#map-edit-' + madrasahId);
            const polygonInput = el('#polygon_koordinat-edit-' + madrasahId);
            const polygonDisplay = el('#polygon-display-' + madrasahId);

            if (!mapContainer || !polygonInput || typeof L === 'undefined') {
                console.error('Map container or Leaflet not found for madrasah ID:', madrasahId);
                console.error('Map container:', mapContainer);
                console.error('Polygon input:', polygonInput);
                console.error('Leaflet available:', typeof L !== 'undefined');
                return;
            }

            // Check if primary map is already initialized on this container
            if (polygonMaps[madrasahId]) {
                console.warn('Primary map already exists for madrasah ID:', madrasahId, '- skipping initialization');
                return;
            }

            // Double-check that the container doesn't already have a map
            if (mapContainer._leaflet_id) {
                console.warn('Map container already has a map for madrasah ID:', madrasahId, '- skipping initialization');
                return;
            }

            console.log('Initializing polygon map for madrasah ID:', madrasahId);

            // Helper function to update polygon data for primary polygon
            function updatePolygonData(polygonInput, polygonDisplay) {
                const layers = drawnItems.getLayers();
                if (layers.length > 0 && layers[0] instanceof L.Polygon) {
                    const polygon = layers[0];
                    // Leaflet uses [lat,lng], convert to GeoJSON format [lon,lat]
                    const coordinates = polygon.getLatLngs()[0].map(latlng => [latlng.lng, latlng.lat]);
                    const geoJSON = {
                        type: 'Polygon',
                        coordinates: [coordinates]
                    };
                    polygonInput.value = JSON.stringify(geoJSON);
                    updatePolygonDisplay(geoJSON, polygonDisplay);
                } else {
                    polygonInput.value = '[]';
                    if (polygonDisplay) {
                        polygonDisplay.innerHTML = '<small class="text-muted">Belum ada poligon. Gunakan tool drawing untuk menambahkan.</small>';
                    }
                }
            }

            try {
                // Create map centered on Indonesia (default)
                const map = L.map('map-edit-' + madrasahId, {
                    center: [-7.7956, 110.3695],
                    zoom: 13,
                    zoomControl: true
                });

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);

                // Initialize FeatureGroup to store editable layers
                const drawnItems = new L.FeatureGroup();
                map.addLayer(drawnItems);

                // Initialize the draw control and pass it the FeatureGroup of editable layers
                console.log('Adding draw control to primary map for madrasah ID:', madrasahId);
                const drawControl = new L.Control.Draw({
                    position: 'topleft',
                    edit: {
                        featureGroup: drawnItems,
                        remove: true
                    },
                    draw: {
                        polygon: {
                            allowIntersection: false,
                            showArea: true,
                            drawError: {
                                color: '#e1e100',
                                message: '<strong>Error:</strong> Shape edges cannot cross!'
                            },
                            shapeOptions: {
                                color: '#3388ff',
                                fillOpacity: 0.2,
                                weight: 2
                            }
                        },
                        polyline: false,
                        rectangle: false,
                        circle: false,
                        marker: false,
                        circlemarker: false
                    }
                });

                try {
                    map.addControl(drawControl);
                    console.log('Draw control added successfully to primary map for madrasah ID:', madrasahId);
                } catch (drawError) {
                    console.error('Failed to add draw control to primary map:', drawError);
                }

                // Helper function to update polygon data for primary polygon
                function updatePolygonData(polygonInput, polygonDisplay) {
                    const layers = drawnItems.getLayers();
                    if (layers.length > 0 && layers[0] instanceof L.Polygon) {
                        const polygon = layers[0];
                        // Leaflet uses [lat,lng], convert to GeoJSON format [lon,lat]
                        const coordinates = polygon.getLatLngs()[0].map(latlng => [latlng.lng, latlng.lat]);
                        const geoJSON = {
                            type: 'Polygon',
                            coordinates: [coordinates]
                        };
                        polygonInput.value = JSON.stringify(geoJSON);
                        updatePolygonDisplay(geoJSON, polygonDisplay);
                    } else {
                        polygonInput.value = '[]';
                        if (polygonDisplay) {
                            polygonDisplay.innerHTML = '<small class="text-muted">Belum ada poligon. Gunakan tool drawing untuk menambahkan.</small>';
                        }
                    }
                }

                // Load existing polygon from database if available
                const existingCoordinates = polygonInput.value;
                console.log('Loading existing polygon data for madrasah ID:', madrasahId, 'Data:', existingCoordinates);
                if (existingCoordinates && existingCoordinates !== '[]' && existingCoordinates !== '' && existingCoordinates !== 'null') {
                    try {
                        const coordData = JSON.parse(existingCoordinates);
                        console.log('Parsed coordinate data for madrasah ID:', madrasahId, coordData);
                        if (coordData && coordData.coordinates && coordData.coordinates.length > 0 && coordData.coordinates[0].length > 0) {
                            // GeoJSON format: { type: "Polygon", coordinates: [[[lon,lat], [lon,lat], ...]] }
                            // Convert [lon,lat] to [lat,lon] for Leaflet
                            const geoJSONCoords = coordData.coordinates[0];
                            const leafletCoords = geoJSONCoords.map(coord => [coord[1], coord[0]]);
                            console.log('Converted to Leaflet coords for madrasah ID:', madrasahId, leafletCoords);

                            const polygon = L.polygon(leafletCoords, {
                                color: '#3388ff',
                                fillOpacity: 0.2,
                                weight: 2
                            });
                            drawnItems.addLayer(polygon);
                            console.log('Polygon added to map for madrasah ID:', madrasahId, polygon);

                            // Fit map to polygon bounds
                            setTimeout(() => {
                                try {
                                    map.fitBounds(polygon.getBounds());
                                    console.log('Map fitted to polygon bounds for madrasah ID:', madrasahId);
                                } catch (boundsError) {
                                    console.warn('Could not fit bounds for madrasah ID:', madrasahId, boundsError);
                                }
                            }, 100);
                            updatePolygonDisplay(coordData, polygonDisplay);
                            console.log('Polygon display updated for madrasah ID:', madrasahId);
                        } else {
                            console.log('No valid coordinates found in data for madrasah ID:', madrasahId);
                        }
                    } catch (e) {
                        console.warn('Failed to parse existing polygon coordinates for madrasah ID:', madrasahId, e);
                        console.warn('Raw data for madrasah ID:', madrasahId, existingCoordinates);
                    }
                } else {
                    console.log('No existing polygon data to load for madrasah ID:', madrasahId);
                }

                // Event handlers for draw actions
                map.on(L.Draw.Event.CREATED, function (event) {
                    const layer = event.layer;
                    // Clear existing layers to ensure only one polygon at a time
                    drawnItems.clearLayers();
                    drawnItems.addLayer(layer);
                    updatePolygonData(polygonInput, polygonDisplay);
                });

                map.on(L.Draw.Event.EDITED, function (event) {
                    const layers = event.layers;
                    layers.eachLayer(function (layer) {
                        // Layer has been edited
                    });
                    updatePolygonData(polygonInput, polygonDisplay);
                });

                map.on(L.Draw.Event.DELETED, function (event) {
                    const layers = event.layers;
                    layers.eachLayer(function (layer) {
                        // Layer has been deleted
                    });
                    updatePolygonData(polygonInput, polygonDisplay);
                });

                // Store map and layer references
                polygonMaps[madrasahId] = map;
                polygonEditableLayers[madrasahId] = drawnItems;

                // Hide loading indicator for primary map
                const primaryLoadingIndicator = el('#loading-map-edit-' + madrasahId);
                if (primaryLoadingIndicator) {
                    primaryLoadingIndicator.style.display = 'none';
                }

                // Apply universal map fix after initialization
                setTimeout(() => {
                    if (map) forceFixLeafletMap(map);
                }, 300);
            } catch (e) {
                console.error('Error initializing polygon map:', e);
            }
        }

        /**
         * Update the polygon coordinate display
         */
        function updatePolygonDisplay(geoJSON, displayElement, checklist = null) {
            if (!displayElement) return;
            if (geoJSON && geoJSON.coordinates && geoJSON.coordinates.length > 0) {
                const coords = geoJSON.coordinates[0];
                let html = `<strong>Jumlah titik:</strong> ${coords.length}<br>`;
                html += `<strong>Format:</strong> GeoJSON (Longitude, Latitude)<br>`;
                html += `<strong>Data JSON:</strong><br>`;
                html += `<code>${JSON.stringify(geoJSON, null, 2)}</code>`;
                displayElement.innerHTML = html;

                // Update checklist if provided
                if (checklist) {
                    const checklistItems = checklist.querySelectorAll('.polygon-checklist-item');
                    checklistItems.forEach(item => {
                        if (item.innerHTML.includes('Poligon Kedua:')) {
                            const icon = item.querySelector('i');
                            const span = item.querySelector('span');
                            if (icon && span) {
                                icon.className = 'bx bx-check-circle';
                                item.classList.remove('danger');
                                item.classList.add('success');
                                span.textContent = 'Poligon Kedua: Tersedia';
                            }
                        }
                    });
                }
            }
        }



        // Initialize DataTable and wire up edit buttons
        document.addEventListener('DOMContentLoaded', function(){
            initDataTable();
        });

        // Use event delegation for edit buttons (works with dynamically loaded content)
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-edit')) {
                const id = e.target.getAttribute('data-id');
                const modalId = 'modalEditMadrasah' + id;
                const modalEl = document.getElementById(modalId);
                if (!modalEl) return;
                try {
                    const bsModal = new bootstrap.Modal(modalEl);

                    // Attach shown handler before showing modal. Use { once: true } so it auto-removes.
                    const onShown = async function() {
                        // Longer delay to ensure DOM inside modal is fully painted and stable
                        await new Promise(resolve => setTimeout(resolve, 500));

                        // Check if map container exists before initializing
                        const mapContainer = document.getElementById('map-edit-' + id);
                        if (!mapContainer) {
                            console.error('Map container not found for madrasah ID:', id);
                            return;
                        }

                        // Initialize or refresh map
                        if (!polygonMaps[id]) {
                            console.log('Initializing new primary map for ID:', id);
                            await initPolygonMap(id);
                        } else {
                            console.log('Refreshing existing primary map for ID:', id);
                            polygonMaps[id].invalidateSize();
                        }

                        // Jika dual polygon aktif, initialize map kedua
                        const enableDual = document.getElementById('enable_dual_polygon-edit-' + id);
                        if (enableDual && enableDual.checked) {
                            if (!dualPolygonMaps[id]) {
                                await initDualPolygonMap(id);
                            } else {
                                dualPolygonMaps[id].invalidateSize();
                            }
                        }

                        // Additional invalidate after everything is set up
                        setTimeout(() => {
                            if (polygonMaps[id]) {
                                polygonMaps[id].invalidateSize();
                            }
                            if (dualPolygonMaps[id]) {
                                dualPolygonMaps[id].invalidateSize();
                            }
                        }, 200);
                    };

                    // Attach hidden handler to destroy maps when modal is closed
                    const onHidden = function() {
                        console.log('Destroying maps for madrasah ID:', id);

                        // Properly destroy primary map
                        if (polygonMaps[id]) {
                            try {
                                polygonMaps[id].remove();
                                console.log('Primary map removed for ID:', id);
                            } catch (e) {
                                console.warn('Error removing primary map:', e);
                            }
                            delete polygonMaps[id];
                            delete polygonEditableLayers[id];
                        }

                        // Properly destroy dual map
                        if (dualPolygonMaps[id]) {
                            try {
                                dualPolygonMaps[id].remove();
                                console.log('Dual map removed for ID:', id);
                            } catch (e) {
                                console.warn('Error removing dual map:', e);
                            }
                            delete dualPolygonMaps[id];
                            delete dualPolygonEditableLayers[id];
                        }

                        // Clear any remaining references
                        const mapContainer = document.getElementById('map-edit-' + id);
                        if (mapContainer) {
                            // Remove any leftover Leaflet elements
                            const leafletElements = mapContainer.querySelectorAll('.leaflet-container, .leaflet-control-container, .leaflet-pane, .leaflet-tile-pane');
                            leafletElements.forEach(el => el.remove());
                        }

                        const dualMapContainer = document.getElementById('map2-edit-' + id);
                        if (dualMapContainer) {
                            // Remove any leftover Leaflet elements
                            const leafletElements = dualMapContainer.querySelectorAll('.leaflet-container, .leaflet-control-container, .leaflet-pane, .leaflet-tile-pane');
                            leafletElements.forEach(el => el.remove());
                        }
                    };

                    modalEl.addEventListener('shown.bs.modal', onShown, { once: true });
                    modalEl.addEventListener('hidden.bs.modal', onHidden, { once: true });
                    modalEl.addEventListener('shown.bs.modal', function() {
                        // Fix accessibility issue: remove aria-hidden when modal is shown
                        modalEl.removeAttribute('aria-hidden');
                        // Focus trap for accessibility
                        const focusableElements = modalEl.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                        if (focusableElements.length > 0) {
                            focusableElements[0].focus();
                        }
                    }, { once: true });
                    bsModal.show();
                } catch (e) {
                    console.warn('Bootstrap modal not available or failed to show', e);
                }
            }
        });


        // Make mapAdd accessible within this scope and initialize the 'Tambah' modal map here
        let mapAdd = null;

        // Initialize modal maps and other UI wiring after DOM ready
        document.addEventListener('DOMContentLoaded', function(){
            // DataTable already initialized above in the DOMContentLoaded handler; ensure map-add is handled too
            const modalTambah = document.getElementById('modalTambahMadrasah');
            if (modalTambah) {
                modalTambah.addEventListener('shown.bs.modal', function() {
                    setTimeout(async () => {
                        await waitForLeaflet();
                        if (!mapAdd && typeof L !== 'undefined') {
                            console.log('Initializing add map');
                            // Double-check that the container doesn't already have a map
                            const addContainer = document.getElementById('map-add');
                            if (addContainer && addContainer._leaflet_id) {
                                console.warn('Add map container already has a map - skipping initialization');
                                return;
                            }
                            try {
                                mapAdd = L.map('map-add').setView([-7.7956, 110.3695], 12);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAdd);

                                // Initialize FeatureGroup to store editable layers
                                const drawnItems = new L.FeatureGroup();
                                mapAdd.addLayer(drawnItems);

                                // Initialize the draw control and pass it the FeatureGroup of editable layers
                                console.log('Adding draw control to add map');
                                const drawControl = new L.Control.Draw({
                                    position: 'topleft',
                                    edit: {
                                        featureGroup: drawnItems,
                                        remove: true
                                    },
                                    draw: {
                                        polygon: {
                                            allowIntersection: false,
                                            showArea: true,
                                            drawError: {
                                                color: '#e1e100',
                                                message: '<strong>Error:</strong> Shape edges cannot cross!'
                                            },
                                            shapeOptions: {
                                                color: '#3388ff',
                                                fillOpacity: 0.2,
                                                weight: 2
                                            }
                                        },
                                        polyline: false,
                                        rectangle: false,
                                        circle: false,
                                        marker: false,
                                        circlemarker: false
                                    }
                                });

                                try {
                                    mapAdd.addControl(drawControl);
                                    console.log('Draw control added successfully to add map');
                                } catch (drawError) {
                                    console.error('Failed to add draw control to add map:', drawError);
                                }

                                // Add a test polygon to verify add map is working
                                console.log('Adding test polygon to add map for verification');
                                const testAddCoords = [
                                    [-7.7956, 110.3675],
                                    [-7.7966, 110.3685],
                                    [-7.7976, 110.3675],
                                    [-7.7966, 110.3665]
                                ];

                                const testAddPolygon = L.polygon(testAddCoords, {
                                    color: '#ffd93d',
                                    fillOpacity: 0.1,
                                    weight: 2,
                                    dashArray: '5, 5'
                                });
                                drawnItems.addLayer(testAddPolygon);

                                // Add a label to indicate this is a test polygon
                                const testAddLabel = L.marker([-7.7966, 110.3675]).addTo(mapAdd)
                                    .bindPopup('Test Add Polygon - Map is working!')
                                    .openPopup();

                                console.log('Test polygon added to add map for verification');

                                // Hide loading indicator for add map
                                const addLoadingIndicator = el('#loading-map-add');
                                if (addLoadingIndicator) {
                                    addLoadingIndicator.style.display = 'none';
                                }

                                // Event handlers for draw actions
                                mapAdd.on(L.Draw.Event.CREATED, function (event) {
                                    const layer = event.layer;
                                    // Clear existing layers to ensure only one polygon at a time
                                    drawnItems.clearLayers();
                                    drawnItems.addLayer(layer);
                                    updateAddPolygonData();
                                });

                                mapAdd.on(L.Draw.Event.EDITED, function (event) {
                                    updateAddPolygonData();
                                });

                                mapAdd.on(L.Draw.Event.DELETED, function (event) {
                                    updateAddPolygonData();
                                });

                                // Function to update polygon data for add modal
                                function updateAddPolygonData() {
                                    const layers = drawnItems.getLayers();
                                    if (layers.length > 0 && layers[0] instanceof L.Polygon) {
                                        const polygon = layers[0];
                                        // Leaflet uses [lat,lng], convert to GeoJSON format [lon,lat]
                                        const coordinates = polygon.getLatLngs()[0].map(latlng => [latlng.lng, latlng.lat]);
                                        const geoJSON = {
                                            type: 'Polygon',
                                            coordinates: [coordinates]
                                        };
                                        document.getElementById('polygon_koordinat_add').value = JSON.stringify(geoJSON);
                                    } else {
                                        document.getElementById('polygon_koordinat_add').value = '[]';
                                    }
                                }

                            } catch (e) {
                                console.error('Failed to init add-map', e);
                            }
                        }
                        if (mapAdd) forceFixLeafletMap(mapAdd);
                    }, 200);
                }, { once: false });

                // Destroy add map when modal is hidden
                modalTambah.addEventListener('hidden.bs.modal', function() {
                    console.log('Destroying add map');
                    if (mapAdd) {
                        try {
                            mapAdd.remove();
                            console.log('Add map removed');
                        } catch (e) {
                            console.warn('Error removing add map:', e);
                        }
                        mapAdd = null;
                    }

                    // Clear any remaining Leaflet elements from add map container
                    const addMapContainer = document.getElementById('map-add');
                    if (addMapContainer) {
                        const leafletElements = addMapContainer.querySelectorAll('.leaflet-container, .leaflet-control-container, .leaflet-pane, .leaflet-tile-pane');
                        leafletElements.forEach(el => el.remove());
                    }
                }, { once: false });
            }

            // Handle dual polygon toggle for edit modals
            document.addEventListener('change', function(e) {
                const target = e.target;
                if (target && target.id && target.id.startsWith('enable_dual_polygon-edit-')) {
                    const id = target.id.replace('enable_dual_polygon-edit-', '');
                    const container = document.getElementById('polygon2-container-edit-' + id);
                    const checklist = document.getElementById('polygon2-checklist-' + id);

                    // Check if this madrasah is allowed to use dual polygon
                    const allowedMadrasahIds = [24, 26, 33];
                    const isAllowed = allowedMadrasahIds.includes(parseInt(id));

                    if (!isAllowed) {
                        target.checked = false;
                        alert('Fitur dual polygon hanya tersedia untuk madrasah tertentu (ID: 24, 26, 33).');
                        return;
                    }

                    if (target.checked) {
                        container.style.display = 'block';
                        // Update checklist
                        if (checklist) {
                            const checklistItems = checklist.querySelectorAll('.polygon-checklist-item');
                            checklistItems.forEach(item => {
                                if (item.innerHTML.includes('Dual Polygon:')) {
                                    const icon = item.querySelector('i');
                                    const span = item.querySelector('span');
                                    if (icon && span) {
                                        icon.className = 'bx bx-check-circle';
                                        item.classList.remove('warning');
                                        item.classList.add('success');
                                        span.textContent = 'Dual Polygon: Aktif';
                                    }
                                }
                            });
                        }
                        // Initialize map if not already done
                        if (!dualPolygonMaps[id]) {
                            initDualPolygonMap(id).then(() => {
                                setTimeout(() => {
                                    dualPolygonMaps[id].invalidateSize();
                                }, 300);
                            });
                        } else {
                            setTimeout(() => {
                                dualPolygonMaps[id].invalidateSize();
                            }, 300);
                        }

                    } else {
                        container.style.display = 'none';
                        // Clear the polygon data
                        const polygonInput = document.getElementById('polygon_koordinat_2-edit-' + id);
                        if (polygonInput) polygonInput.value = '[]';
                        // Update checklist
                        if (checklist) {
                            const checklistItems = checklist.querySelectorAll('.polygon-checklist-item');
                            checklistItems.forEach(item => {
                                if (item.innerHTML.includes('Dual Polygon:')) {
                                    const icon = item.querySelector('i');
                                    const span = item.querySelector('span');
                                    if (icon && span) {
                                        icon.className = 'bx bx-info-circle';
                                        item.classList.remove('success');
                                        item.classList.add('warning');
                                        span.textContent = 'Dual Polygon: Tidak aktif';
                                    }
                                }
                                if (item.innerHTML.includes('Poligon Kedua:')) {
                                    const icon = item.querySelector('i');
                                    const span = item.querySelector('span');
                                    if (icon && span) {
                                        icon.className = 'bx bx-x-circle';
                                        item.classList.remove('success');
                                        item.classList.add('danger');
                                        span.textContent = 'Poligon Kedua: Belum ada';
                                    }
                                }
                            });
                        }
                    }
                }
            });
        });

        })();
    </script>
@endsection
