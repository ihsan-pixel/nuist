@extends('layouts.master')

@section('title')
    Madrasah/Sekolah
@endsection

@section('css')
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
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
                            <th>Kabupaten</th>
                            <th>Alamat</th>
                            <th>Hari KBM</th>
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
                                                    <label>Kabupaten</label>
                                                    <select name="kabupaten" class="form-select">
                                                        <option value="">Pilih Kabupaten</option>
                                                        <option value="Kabupaten Bantul" {{ $madrasah->kabupaten == 'Kabupaten Bantul' ? 'selected' : '' }}>Kabupaten Bantul</option>
                                                        <option value="Kabupaten Gunungkidul" {{ $madrasah->kabupaten == 'Kabupaten Gunungkidul' ? 'selected' : '' }}>Kabupaten Gunungkidul</option>
                                                        <option value="Kabupaten Kulon Progo" {{ $madrasah->kabupaten == 'Kabupaten Kulon Progo' ? 'selected' : '' }}>Kabupaten Kulon Progo</option>
                                                        <option value="Kabupaten Sleman" {{ $madrasah->kabupaten == 'Kabupaten Sleman' ? 'selected' : '' }}>Kabupaten Sleman</option>
                                                        <option value="Kota Yogyakarta" {{ $madrasah->kabupaten == 'Kota Yogyakarta' ? 'selected' : '' }}>Kota Yogyakarta</option>
                                                    </select>
                                                    @error('kabupaten')
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
                                                    <label>Hari KBM</label>
                                                    <select name="hari_kbm" class="form-select">
                                                        <option value="">Pilih Hari KBM</option>
                                                        <option value="5" {{ $madrasah->hari_kbm == '5' ? 'selected' : '' }}>5 Hari</option>
                                                        <option value="6" {{ $madrasah->hari_kbm == '6' ? 'selected' : '' }}>6 Hari</option>
                                                    </select>
                                                    @error('hari_kbm')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label>Area Poligon Presensi Utama</label>
                                                    <div id="map-{{ $madrasah->id }}" style="height: 300px; width: 100%;"></div>
                                                    <input type="hidden" name="polygon_koordinat" id="polygon_koordinat-{{ $madrasah->id }}" value="{{ $madrasah->polygon_koordinat }}">
                                                    <small class="text-muted">Gambarkan area poligon utama pada peta. Jika sudah ada, bisa diedit.</small>
                                                </div>
                                                @if(in_array($madrasah->id, [24, 26, 33, 25]))
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="enable_dual_polygon" id="enable_dual_polygon-{{ $madrasah->id }}" value="1" {{ $madrasah->enable_dual_polygon ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="enable_dual_polygon-{{ $madrasah->id }}">
                                                            Aktifkan Poligon Kedua
                                                        </label>
                                                    </div>
                                                    <small class="text-muted">Centang untuk mengaktifkan area poligon presensi kedua.</small>
                                                </div>
                                                <div class="mb-3" id="polygon2-container-{{ $madrasah->id }}" style="display: {{ $madrasah->enable_dual_polygon ? 'block' : 'none' }};">
                                                    <label>Area Poligon Presensi Kedua</label>
                                                    <div id="map2-{{ $madrasah->id }}" style="height: 300px; width: 100%;"></div>
                                                    <input type="hidden" name="polygon_koordinat_2" id="polygon_koordinat_2-{{ $madrasah->id }}" value="{{ $madrasah->polygon_koordinat_2 }}">
                                                    <small class="text-muted">Gambarkan area poligon kedua pada peta. Jika sudah ada, bisa diedit.</small>
                                                </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label>Logo</label>
                                                    <input type="file" name="logo" id="editLogoInput{{ $madrasah->id }}" class="form-control" accept="image/*">
                                                    <small class="text-muted">Kosongkan jika tidak ingin diubah. Maksimal 2MB, format: JPG, PNG, JPEG</small>
                                                    @if($madrasah->logo)
                                                        <div class="mt-2">
                                                            <label class="form-label">Logo Saat Ini:</label><br>
                                                            <img src="{{ asset('storage/app/public/' . $madrasah->logo) }}" alt="Current Logo" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                        </div>
                                                    @endif
                                                    <div id="editLogoPreview{{ $madrasah->id }}" class="mt-2" style="display: none;">
                                                        <label class="form-label">Preview Logo Baru:</label><br>
                                                        <img id="editPreviewImage{{ $madrasah->id }}" src="" alt="Preview Logo" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="clearEditLogoPreview({{ $madrasah->id }})">
                                                            <i class="bx bx-trash"></i> Hapus
                                                        </button>
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
                            <label>Hari KBM</label>
                            <select name="hari_kbm" class="form-select">
                                <option value="">Pilih Hari KBM</option>
                                <option value="5">5 Hari</option>
                                <option value="6">6 Hari</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Area Poligon Presensi Utama</label>
                            <div id="map-add" style="height: 300px; width: 100%;"></div>
                            <input type="hidden" name="polygon_koordinat" id="polygon_koordinat-add">
                            <small class="text-muted">Gambarkan area poligon utama pada peta.</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="enable_dual_polygon" id="enable_dual_polygon-add" value="1" disabled>
                                <label class="form-check-label" for="enable_dual_polygon-add">
                                    Aktifkan Poligon Kedua
                                </label>
                            </div>
                            <small class="text-muted">Fitur dual polygon hanya tersedia untuk madrasah tertentu (ID: 24, 26, 33, 25).</small>
                        </div>
                        <div class="mb-3" id="polygon2-container-add" style="display: none;">
                            <label>Area Poligon Presensi Kedua</label>
                            <div id="map2-add" style="height: 300px; width: 100%;"></div>
                            <input type="hidden" name="polygon_koordinat_2" id="polygon_koordinat_2-add">
                            <small class="text-muted">Gambarkan area poligon kedua pada peta.</small>
                        </div>
                        <div class="mb-3">
                            <label>Logo Madrasah/Sekolah</label>
                            <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                            <small class="text-muted">Opsional, boleh dikosongkan. Maksimal 2MB, format: JPG, PNG, JPEG</small>
                            <div id="logoPreview" class="mt-2" style="display: none;">
                                <img id="previewImage" src="" alt="Preview Logo" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="clearLogoPreview()">
                                    <i class="bx bx-trash"></i> Hapus
                                </button>
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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script>
        // Fungsi untuk preview logo di form tambah
        document.getElementById('logoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logoPreview');
            const previewImage = document.getElementById('previewImage');

            if (file) {
                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    return;
                }

                // Validasi tipe file
                if (!file.type.match('image.*')) {
                    alert('File harus berupa gambar.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Fungsi untuk clear preview logo di form tambah
        function clearLogoPreview() {
            document.getElementById('logoInput').value = '';
            document.getElementById('logoPreview').style.display = 'none';
        }

        // Fungsi untuk preview logo di form edit
        @foreach($madrasahs as $madrasah)
        document.getElementById('editLogoInput{{ $madrasah->id }}').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('editLogoPreview{{ $madrasah->id }}');
            const previewImage = document.getElementById('editPreviewImage{{ $madrasah->id }}');

            if (file) {
                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    return;
                }

                // Validasi tipe file
                if (!file.type.match('image.*')) {
                    alert('File harus berupa gambar.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Fungsi untuk clear preview logo di form edit
        function clearEditLogoPreview(madrasahId) {
            document.getElementById('editLogoInput' + madrasahId).value = '';
            document.getElementById('editLogoPreview' + madrasahId).style.display = 'none';
        }
        @endforeach

        $(document).ready(function () {
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"]
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

            // --- Leaflet Map for Polygon Drawing ---
            const initializeMap = (mapId, polygonInputId, lat, lon, existingPolygon = null) => {
                let mapElement = document.getElementById(mapId);
                if (mapElement && !mapElement._leaflet_id) {
                    let map = L.map(mapId).setView([lat, lon], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    let drawnItems = new L.FeatureGroup();
                    map.addLayer(drawnItems);

                    // Load existing polygon
                    if (existingPolygon) {
                        try {
                            let geometry = JSON.parse(existingPolygon);
                            let layer = L.geoJSON(geometry);
                            layer.eachLayer(l => drawnItems.addLayer(l));
                            if (drawnItems.getLayers().length > 0) {
                                map.fitBounds(drawnItems.getBounds());
                            }
                        } catch (e) {
                            console.error("Invalid GeoJSON data for polygon:", e);
                        }
                    }

                    let drawControl = new L.Control.Draw({
                        edit: {
                            featureGroup: drawnItems,
                            poly: { allowIntersection: false }
                        },
                        draw: {
                            polygon: {
                                allowIntersection: false,
                                showArea: true
                            },
                            polyline: false, rectangle: false, circle: false, marker: false, circlemarker: false
                        }
                    });
                    map.addControl(drawControl);

                    const updatePolygonInput = () => {
                        let geojson = drawnItems.toGeoJSON();
                        if (geojson.features.length > 0) {
                            $('#' + polygonInputId).val(JSON.stringify(geojson.features[0].geometry));
                        } else {
                            $('#' + polygonInputId).val('');
                        }
                    };

                    map.on(L.Draw.Event.CREATED, function (e) {
                        drawnItems.clearLayers();
                        drawnItems.addLayer(e.layer);
                        updatePolygonInput();
                    });

                    map.on(L.Draw.Event.EDITED, updatePolygonInput);
                    map.on(L.Draw.Event.DELETED, updatePolygonInput);

                    window[mapId] = map;
                    return map;
                }
                return null;
            };

            // Initialize map for add modal
            $('#modalTambahMadrasah').on('shown.bs.modal', function () {
                let lat = -7.7956;
                let lon = 110.3695;
                initializeMap('map-add', 'polygon_koordinat-add', lat, lon);
                setTimeout(() => {
                    if (window['map-add']) window['map-add'].invalidateSize();
                }, 400);
            });

            // Initialize maps for edit modals
            $('div.modal.fade').on('shown.bs.modal', function (event) {
                let modal = $(this);
                if (!modal.attr('id') || !modal.attr('id').startsWith('modalEditMadrasah')) {
                    return;
                }

                let madrasahId = modal.attr('id').replace('modalEditMadrasah', '');
                let lat = modal.find('input[name="latitude"]').val() || -7.7956;
                let lon = modal.find('input[name="longitude"]').val() || 110.3695;

                // Initialize first map
                let existingPolygon1 = $('#polygon_koordinat-' + madrasahId).val();
                initializeMap('map-' + madrasahId, 'polygon_koordinat-' + madrasahId, lat, lon, existingPolygon1);

                window['map-' + madrasahId] = initializeMap(
                    'map-' + madrasahId,
                    'polygon_koordinat-' + madrasahId,
                    lat, lon,
                    existingPolygon1
                );

                if ($('#enable_dual_polygon-' + madrasahId).is(':checked')) {
                    let existingPolygon2 = $('#polygon_koordinat_2-' + madrasahId).val();

                    window['map2-' + madrasahId] = initializeMap(
                        'map2-' + madrasahId,
                        'polygon_koordinat_2-' + madrasahId,
                        lat, lon,
                        existingPolygon2
                    );
                }

                setTimeout(() => {
                    if (window['map-' + madrasahId]) window['map-' + madrasahId].invalidateSize();
                    if (window['map2-' + madrasahId]) window['map2-' + madrasahId].invalidateSize();
                }, 400);
            });

            // Toggle dual polygon functionality
            $(document).on('change', '[id^="enable_dual_polygon"]', function() {
                let id = $(this).attr('id').replace('enable_dual_polygon-', '');
                let container = $('#polygon2-container' + (id ? '-' + id : '-add'));
                let mapId = 'map2' + (id ? '-' + id : '-add');
                let polygonInputId = 'polygon_koordinat_2' + (id ? '-' + id : '-add');

                // Check if this madrasah is allowed to use dual polygon (only for IDs 24, 26, 33)
                let allowedMadrasahIds = [24, 26, 33, 25];
                let isAllowed = id === 'add' || allowedMadrasahIds.includes(parseInt(id));

                if (!isAllowed) {
                    $(this).prop('checked', false);
                    alert('Fitur dual polygon hanya tersedia untuk madrasah tertentu (ID: 24, 26, 33, 25).');
                    return;
                }

                if ($(this).is(':checked')) {
                    container.show();
                    // Initialize map if not already done
                    if (!document.getElementById(mapId)._leaflet_id) {
                        let lat = -7.7956;
                        let lon = 110.3695;
                        window['map2-' + id] = initializeMap(
                            mapId,
                            polygonInputId,
                            lat, lon
                        );

                        setTimeout(() => {
                            if (window['map2-' + id]) window['map2-' + id].invalidateSize();
                        }, 400);
                    }
                } else {
                    container.hide();
                    $('#' + polygonInputId).val('');
                }
            });
        });
    </script>
@endsection

