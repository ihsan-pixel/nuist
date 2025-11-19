@extends('layouts.master')

@section('title')
    Madrasah/Sekolah
@endsection

@section('css')
<style>
    /* Fix map inside bootstrap modal */
    .modal .leaflet-container {
        z-index: 9999 !important;
    }

    /* Hapus overlay putih yang menutupi map */
    #map-add,
    [id^="map-"],
    [id^="map2-"] {
        background: transparent !important;
    }

    /* Force modal to show content without clipping map */
    .modal-dialog {
        overflow: visible !important;
    }

    .modal-content {
        overflow: visible !important;
    }

    .modal-body {
        overflow: visible !important;
    }

    .leaflet-container {
        height: 320px !important;
        z-index: 9999 !important;
    }

    .modal-body {
        overflow: visible !important;
    }

</style>
    {{-- Template Base --}}
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />

    {{-- DataTables --}}
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
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

                                <div class="mb-3">
                                    <label>Area Poligon Presensi Utama</label>
                                    <div id="map-{{ $madrasah->id }}" style="height: 320px; width: 100%;"></div>
                                    <input type="hidden" name="polygon_koordinat" id="polygon_koordinat-{{ $madrasah->id }}" value="{{ $madrasah->polygon_koordinat }}">
                                    <small class="text-muted">Gambarkan area poligon utama pada peta. Jika sudah ada, bisa diedit.</small>
                                </div>

                                @if(in_array($madrasah->id, [24,26,33,25]))
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input enable-dual" type="checkbox" name="enable_dual_polygon" id="enable_dual_polygon-{{ $madrasah->id }}" value="1" {{ $madrasah->enable_dual_polygon ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_dual_polygon-{{ $madrasah->id }}">Aktifkan Poligon Kedua</label>
                                    </div>
                                    <small class="text-muted">Centang untuk mengaktifkan area poligon presensi kedua.</small>
                                </div>
                                <div class="mb-3" id="polygon2-container-{{ $madrasah->id }}" style="display: {{ $madrasah->enable_dual_polygon ? 'block' : 'none' }};">
                                    <label>Area Poligon Presensi Kedua</label>
                                    <div id="map2-{{ $madrasah->id }}" style="height: 320px; width: 100%;"></div>
                                    <input type="hidden" name="polygon_koordinat_2" id="polygon_koordinat_2-{{ $madrasah->id }}" value="{{ $madrasah->polygon_koordinat_2 }}">
                                    <small class="text-muted">Gambarkan area poligon kedua pada peta. Jika sudah ada, bisa diedit.</small>
                                </div>
                                @endif

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

    {{-- Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

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

        // ---------------------- Leaflet map manager ----------------------
        window.maps = {};
        window.featureGroups = {};

        const inIndonesia = (lat, lng) => (lat >= -15 && lat <= 6 && lng >= 95 && lng <= 141);

        const safeParse = (v) => {
            if (!v) return null;
            try{ return JSON.parse(v); } catch(e){ return null; }
        };

        // Swap coordinates helper (recursive)
        const swapCoords = (coords) => {
            if (!Array.isArray(coords)) return coords;
            if (coords.length > 0 && typeof coords[0] === 'number' && typeof coords[1] === 'number') {
                return [coords[1], coords[0]];
            }
            return coords.map(c => swapCoords(c));
        };

        const createLayerFromGeometry = (geom) => {
            try { return L.geoJSON(geom); } catch(e){ return null; }
        };

        const addExistingGeometryToFeatureGroup = (featureGroup, geom) => {
            if (!geom) return;
            try {
                let geometry = (typeof geom === 'string') ? JSON.parse(geom) : geom;
                let layer = createLayerFromGeometry(geometry);
                if (layer && layer.getLayers().length > 0) {
                    const bounds = layer.getBounds();
                    const center = bounds.getCenter();
                    if (!inIndonesia(center.lat, center.lng)) {
                        // try swapped
                        try {
                            let swapped = JSON.parse(JSON.stringify(geometry));
                            if (swapped.type === 'FeatureCollection') {
                                swapped.features = swapped.features.map(f => ({ ...f, geometry: { ...f.geometry, coordinates: swapCoords(f.geometry.coordinates) } }));
                            } else if (swapped.type === 'Feature') {
                                swapped.geometry.coordinates = swapCoords(swapped.geometry.coordinates);
                            } else if (swapped.coordinates) {
                                swapped.coordinates = swapCoords(swapped.coordinates);
                            }
                            const swappedLayer = createLayerFromGeometry(swapped);
                            if (swappedLayer && swappedLayer.getLayers().length > 0) {
                                layer = swappedLayer;
                            }
                        } catch(e){ console.warn('swap failed', e); }
                    }
                    layer.eachLayer(l => featureGroup.addLayer(l));
                }
            } catch(e){ console.warn('addExistingGeometry error', e); }
        };

        const initializeMap = (mapId, polygonInputId, lat = -7.7956, lon = 110.3695, existingPolygon = null) => {
            const mapEl = document.getElementById(mapId);
            if (!mapEl) return null;
            if (window.maps[mapId]) return window.maps[mapId];

            const _lat = parseFloat(lat) || -7.7956;
            const _lon = parseFloat(lon) || 110.3695;

            const map = L.map(mapId).setView([_lat, _lon], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const featureGroup = new L.FeatureGroup();
            map.addLayer(featureGroup);

            // Load existing polygon geometry
            addExistingGeometryToFeatureGroup(featureGroup, existingPolygon);
            if (featureGroup.getLayers().length > 0) {
                map.fitBounds(featureGroup.getBounds());
            }

            const drawControl = new L.Control.Draw({
                edit: { featureGroup: featureGroup, poly: { allowIntersection: false } },
                draw: { polygon: { allowIntersection: false, showArea: true }, polyline: false, rectangle: false, circle: false, marker: false, circlemarker: false }
            });
            map.addControl(drawControl);

            const updatePolygonInput = () => {
                const geojson = featureGroup.toGeoJSON();
                if (geojson.features && geojson.features.length > 0) {
                    const geom = geojson.features[0].geometry;
                    const input = document.getElementById(polygonInputId);
                    if (input) input.value = JSON.stringify(geom);
                } else {
                    const input = document.getElementById(polygonInputId);
                    if (input) input.value = '';
                }
            };

            map.on(L.Draw.Event.CREATED, function(e){
                featureGroup.clearLayers();
                featureGroup.addLayer(e.layer);
                updatePolygonInput();
            });
            map.on(L.Draw.Event.EDITED, updatePolygonInput);
            map.on(L.Draw.Event.DELETED, updatePolygonInput);

            window.maps[mapId] = map;
            window.featureGroups[mapId] = featureGroup;

            return map;
        };

        // ---------------------- Modal handling ----------------------
        document.addEventListener('DOMContentLoaded', function(){
            initDataTable();

            // Initialize add-map when add modal shown
            const addModal = document.getElementById('modalTambahMadrasah');
            if (addModal) {
                addModal.addEventListener('shown.bs.modal', function(){
                    const lat = -7.7956, lon = 110.3695;
                    window.maps['map-add'] = null; // reset map setiap modal dibuka
                    const map = initializeMap('map-add', 'polygon_koordinat-add');
                    setTimeout(()=>{ if(map) map.invalidateSize(); }, 200);
                });
            }

            // When an edit button clicked - open the corresponding modal
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.getAttribute('data-id');
                    const modalId = 'modalEditMadrasah' + id;
                    const modalEl = document.getElementById(modalId);
                    if (!modalEl) return;

                    // Before showing modal, ensure map init values are read from inputs inside modal
                    const lat = (modalEl.querySelector('input[name="latitude"]') || {}).value || -7.7956;
                    const lon = (modalEl.querySelector('input[name="longitude"]') || {}).value || 110.3695;
                    const polygonInputId = 'polygon_koordinat-' + id;
                    const existingPolygon = (document.getElementById(polygonInputId) || {}).value || null;

                    // Initialize main polygon map
                    initializeMap('map-' + id, polygonInputId, lat, lon, existingPolygon);

                    // If dual polygon exists and is enabled for this madrasah
                    const enableDual = modalEl.querySelector('.enable-dual');
                    if (enableDual && enableDual.checked) {
                        const polygon2Id = 'polygon_koordinat_2-' + id;
                        const existingPolygon2 = (document.getElementById(polygon2Id) || {}).value || null;
                        initializeMap('map2-' + id, polygon2Id, lat, lon, existingPolygon2);
                    }

                    // Show Bootstrap modal programmatically
                    const bsModal = new bootstrap.Modal(modalEl);

                    modalEl.addEventListener('shown.bs.modal', function () {
                        const lat = modalEl.querySelector('input[name="latitude"]').value || -7.7956;
                        const lon = modalEl.querySelector('input[name="longitude"]').value || 110.3695;

                        initializeMap('map-' + id, polygonInputId, lat, lon, existingPolygon);

                        setTimeout(() => {
                            if (window.maps['map-' + id]) window.maps['map-' + id].invalidateSize();
                        }, 200);

                        // for dual polygon
                        if (enableDual && enableDual.checked) {
                            initializeMap('map2-' + id, polygonInputId2, lat, lon, existingPolygon2);
                            setTimeout(() => {
                                if (window.maps['map2-' + id]) window.maps['map2-' + id].invalidateSize();
                            }, 200);
                        }
                    }, { once:true });

                    bsModal.show();


                    // after modal shown, invalidate sizes
                    setTimeout(()=>{
                        if (window.maps['map-' + id]) window.maps['map-' + id].invalidateSize();
                        if (window.maps['map2-' + id]) window.maps['map2-' + id].invalidateSize();
                    }, 300);
                });
            });

            // Toggle dual polygon container when checkbox changed (delegated)
            document.addEventListener('change', function(e){
                const t = e.target;
                if (t && t.classList.contains('enable-dual')){
                    const id = t.id.replace('enable_dual_polygon-','');
                    const container = document.getElementById('polygon2-container-' + id);
                    const map2id = 'map2-' + id;
                    const polygonInput2 = 'polygon_koordinat_2-' + id;
                    if (t.checked) {
                        if (container) container.style.display = 'block';
                        if (!window.maps[map2id]) {
                            const lat = (document.querySelector('#modalEditMadrasah'+id+' input[name="latitude"]') || {}).value || -7.7956;
                            const lon = (document.querySelector('#modalEditMadrasah'+id+' input[name="longitude"]') || {}).value || 110.3695;
                            initializeMap(map2id, polygonInput2, lat, lon, (document.getElementById(polygonInput2) || {}).value || null);
                            setTimeout(()=>{ if(window.maps[map2id]) window.maps[map2id].invalidateSize(); }, 300);
                        }
                    } else {
                        if (container) container.style.display = 'none';
                        const input = document.getElementById(polygonInput2);
                        if (input) input.value = '';
                    }
                }
            });

        }); // DOMContentLoaded end

    })();
    </script>
@endsection

{{-- reference image (screenshot): /mnt/data/Screen Shot 2025-11-19 at 14.30.57.png --}}
