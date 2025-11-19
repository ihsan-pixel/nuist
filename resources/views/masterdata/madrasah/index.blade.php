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

    {{-- Leaflet for polygon editing on edit modal --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-draw/1.0.4/leaflet.draw.css" />
    
    <style>
        .polygon-map-container {
            position: relative;
            height: 400px;
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .polygon-info {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        .polygon-coordinates {
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 8px;
            border-radius: 0.25rem;
            max-height: 150px;
            overflow-y: auto;
            margin-top: 5px;
            font-family: monospace;
            font-size: 0.75rem;
            word-break: break-all;
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
                                    <label>Area Poligon Presensi Utama</label>
                                    <div id="map-add" style="height: 320px; width: 100%;"></div>
                                    <input type="hidden" name="polygon_koordinat" id="polygon_koordinat-add">
                                    <small class="text-muted">Gambarkan area poligon utama pada peta.</small>
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
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Hari KBM</label>
                                    <select name="hari_kbm" class="form-select">
                                        <option value="">Pilih Hari KBM</option>
                                        <option value="5" {{ $madrasah->hari_kbm == '5' ? 'selected' : '' }}>5 Hari</option>
                                        <option value="6" {{ $madrasah->hari_kbm == '6' ? 'selected' : '' }}>6 Hari</option>
                                    </select>
                                </div>

                                {{-- Area Poligon Koordinat untuk Edit Modal --}}
                                <div class="mb-3">
                                    <label>Area Poligon Presensi (Koordinat)</label>
                                    <div class="polygon-map-container" id="map-edit-{{ $madrasah->id }}"></div>
                                    <input type="hidden" name="polygon_koordinat" id="polygon_koordinat-edit-{{ $madrasah->id }}" 
                                           value="{{ $madrasah->polygon_koordinat ?? '[]' }}">
                                    <div class="polygon-info">
                                        <strong>Instruksi:</strong>
                                        <small class="d-block">
                                            • Gunakan tool drawing di peta untuk menggambar poligon area presensi<br>
                                            • Klik tombol edit untuk mengubah poligon yang sudah ada<br>
                                            • Format: GeoJSON Standard (Longitude, Latitude)<br>
                                            • Koordinat akan tersimpan otomatis dalam format JSON
                                        </small>
                                    </div>
                                    <div class="polygon-coordinates" id="polygon-display-{{ $madrasah->id }}">
                                        <small class="text-muted">Koordinat akan ditampilkan di sini...</small>
                                    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-draw/1.0.4/leaflet.draw.umd.js"></script>
    <script>
        // Flag to indicate Leaflet Draw is loaded
        window.leafletDrawReady = function() {
            window.leafletDraw = true;
        };
        // Call immediately if Leaflet Draw is already in global scope
        if (typeof L !== 'undefined' && L.Control && L.Control.Draw) {
            window.leafletDraw = true;
        }
        // Set flag after a delay to ensure async loading is complete
        setTimeout(() => {
            if (typeof L !== 'undefined' && L.Control && L.Control.Draw) {
                window.leafletDraw = true;
            }
        }, 500);
    </script>

    <script>
    (function(){
        // ---------------------- Helper utilities ----------------------
        const el = (selector, ctx = document) => ctx.querySelector(selector);
        const els = (selector, ctx = document) => Array.from(ctx.querySelectorAll(selector));

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

        /**
         * Wait for Leaflet Draw to be loaded
         */
        function waitForLeafletDraw(callback, attempts = 0) {
            if (typeof L !== 'undefined' && L.Control && L.Control.Draw) {
                callback();
            } else if (attempts < 50) {
                setTimeout(() => waitForLeafletDraw(callback, attempts + 1), 100);
            } else {
                console.error('Leaflet Draw failed to load. Using basic polygon drawing instead.');
                callback(true); // true = fallback mode
            }
        }

        /**
         * Initialize polygon map for a specific madrasah
         * @param {number} madrasahId - ID of the madrasah
         */
        function initPolygonMap(madrasahId) {
            const mapContainer = el('#map-edit-' + madrasahId);
            const polygonInput = el('#polygon_koordinat-edit-' + madrasahId);
            const polygonDisplay = el('#polygon-display-' + madrasahId);
            
            if (!mapContainer || !polygonInput) return;

            // Wait for Leaflet Draw to load
            waitForLeafletDraw(() => {
                try {
                    // Create map centered on Indonesia (default)
                    const map = L.map('map-edit-' + madrasahId).setView([-7.7956, 110.3695], 13);
                    
                    // Add tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(map);

                    // Initialize draw feature group
                    const drawnItems = new L.FeatureGroup();
                    map.addLayer(drawnItems);

                    // Initialize draw control with proper error handling
                    let drawControl;
                    if (L.Control && L.Control.Draw) {
                        drawControl = new L.Control.Draw({
                            position: 'topleft',
                            draw: {
                                polygon: {
                                    allowIntersection: false,
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
                            },
                            edit: {
                                featureGroup: drawnItems,
                                edit: true,
                                remove: true
                            }
                        });
                        map.addControl(drawControl);
                    } else {
                        // Fallback: Add basic instructions if Draw control not available
                        const infoControl = L.control({position: 'topleft'});
                        infoControl.onAdd = function(map) {
                            const div = L.DomUtil.create('div', 'leaflet-draw-info');
                            div.innerHTML = '<small style="background:white;padding:5px;border-radius:3px;display:inline-block;">Leaflet Draw tidak tersedia</small>';
                            return div;
                        };
                        map.addControl(infoControl);
                    }

                    // Load existing polygon from database if available
                    const existingCoordinates = polygonInput.value;
                    if (existingCoordinates && existingCoordinates !== '[]' && existingCoordinates !== '') {
                        try {
                            const coordData = JSON.parse(existingCoordinates);
                            if (coordData && coordData.coordinates && coordData.coordinates.length > 0) {
                                // GeoJSON format: { type: "Polygon", coordinates: [[[lon,lat], [lon,lat], ...]] }
                                // Convert [lon,lat] to [lat,lon] for Leaflet
                                const geoJSONCoords = coordData.coordinates[0];
                                const leafletCoords = geoJSONCoords.map(coord => [coord[1], coord[0]]);
                                
                                const polygon = L.polygon(leafletCoords, {
                                    color: '#3388ff',
                                    fillOpacity: 0.2,
                                    weight: 2
                                });
                                drawnItems.addLayer(polygon);
                                
                                // Fit map to polygon bounds
                                setTimeout(() => {
                                    map.fitBounds(polygon.getBounds());
                                }, 100);
                                updatePolygonDisplay(coordData, polygonDisplay);
                            }
                        } catch (e) {
                            console.warn('Failed to parse existing polygon coordinates:', e);
                        }
                    }

                    // Handle draw and edit events
                    const updatePolygonData = () => {
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
                    };

                    if (typeof L !== 'undefined' && L.Draw) {
                        map.on('draw:created', updatePolygonData);
                        map.on('draw:edited', updatePolygonData);
                        map.on('draw:deleted', updatePolygonData);
                    }

                    // Store map and layer references
                    polygonMaps[madrasahId] = map;
                    polygonEditableLayers[madrasahId] = drawnItems;
                } catch (e) {
                    console.error('Error initializing polygon map:', e);
                }
            });
        }

        /**
         * Update the polygon coordinate display
         */
        function updatePolygonDisplay(geoJSON, displayElement) {
            if (!displayElement) return;
            if (geoJSON && geoJSON.coordinates && geoJSON.coordinates.length > 0) {
                const coords = geoJSON.coordinates[0];
                let html = `<strong>Jumlah titik:</strong> ${coords.length}<br>`;
                html += `<strong>Format:</strong> GeoJSON (Longitude, Latitude)<br>`;
                html += `<strong>Data JSON:</strong><br>`;
                html += `<code>${JSON.stringify(geoJSON, null, 2)}</code>`;
                displayElement.innerHTML = html;
            }
        }

        /**
         * Reinitialize map when modal is shown (fixes Leaflet sizing issues)
         */
        function reinitializeMapOnModalShow(madrasahId) {
            const modalId = 'modalEditMadrasah' + madrasahId;
            const modalEl = el('#' + modalId);
            if (!modalEl) return;

            // Use Bootstrap modal events
            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalEl.addEventListener('shown.bs.modal', function() {
                // Small delay to allow modal to fully render
                setTimeout(() => {
                    if (polygonMaps[madrasahId]) {
                        polygonMaps[madrasahId].invalidateSize();
                    } else {
                        initPolygonMap(madrasahId);
                    }
                }, 100);
            });
        }

        // Initialize DataTable and wire up edit buttons
        document.addEventListener('DOMContentLoaded', function(){
            initDataTable();

            // Ensure Leaflet libraries are loaded
            function ensureLibrariesLoaded(callback) {
                let attempts = 0;
                const checkInterval = setInterval(() => {
                    if (typeof L !== 'undefined' && window.leafletDraw !== undefined) {
                        clearInterval(checkInterval);
                        callback();
                    } else if (attempts > 100) {
                        clearInterval(checkInterval);
                        console.warn('Leaflet libraries took too long to load');
                        callback(); // Proceed anyway, graceful fallback in initPolygonMap
                    }
                    attempts++;
                }, 50);
            }

            // Open edit modal when Edit button clicked and initialize map
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.getAttribute('data-id');
                    const modalId = 'modalEditMadrasah' + id;
                    const modalEl = document.getElementById(modalId);
                    if (!modalEl) return;
                    try {
                        const bsModal = new bootstrap.Modal(modalEl);
                        bsModal.show();
                        
                        // Initialize map after modal is shown
                        modalEl.addEventListener('shown.bs.modal', function() {
                            ensureLibrariesLoaded(() => {
                                setTimeout(() => {
                                    initPolygonMap(id);
                                }, 150);
                            });
                        }, { once: true }); // Use once to avoid duplicate listeners
                    } catch (e) {
                        console.warn('Bootstrap modal not available or failed to show', e);
                    }
                });
            });
        });

        })();
    </script>
@endsection

{{-- reference image (screenshot): /mnt/data/Screen Shot 2025-11-19 at 14.30.57.png --}}
