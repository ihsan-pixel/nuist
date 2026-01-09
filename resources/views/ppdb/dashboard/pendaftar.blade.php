@extends('layouts.master')

@section('title', 'Data Pendaftar - ' . $ppdbSetting->nama_sekolah)

@push('css')
<style>
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-verifikasi {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .status-lulus {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-tidak_lulus {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 1rem 0.75rem;
    }

    .table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .action-buttons .btn {
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .pendaftar-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .pendaftar-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .pendaftar-body {
        padding: 1.25rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        color: #495057;
        font-weight: 600;
    }

    .document-links {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .document-link {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: background-color 0.15s ease-in-out;
    }

    .document-link:hover {
        background-color: #0056b3;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .filter-section {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .pendaftar-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="mdi mdi-account-multiple me-2"></i>
                Data Pendaftar PPDB
            </h2>
            <p class="text-muted mb-0">{{ $ppdbSetting->nama_sekolah }} - Tahun {{ $ppdbSetting->tahun }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('ppdb.lp.dashboard') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i>Kembali
            </a>
            {{-- <a href="{{ route('chat.index') }}" class="btn btn-info">
                <i class="mdi mdi-chat me-1"></i>Live Chat
            </a> --}}
            <button onclick="window.print()" class="btn btn-outline-primary">
                <i class="mdi mdi-printer me-1"></i>Cetak
            </button>
            <button onclick="exportToExcel()" class="btn btn-success">
                <i class="mdi mdi-download me-1"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ number_format($statistik['total']) }}</h4>
                        <p class="mb-0 opacity-75">Total Pendaftar</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-account-multiple fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ number_format($statistik['pending']) }}</h4>
                        <p class="mb-0 opacity-75">Menunggu Verifikasi</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-clock-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ number_format($statistik['verifikasi']) }}</h4>
                        <p class="mb-0 opacity-75">Dalam Verifikasi</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-magnify fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ number_format($statistik['lulus']) }}</h4>
                        <p class="mb-0 opacity-75">Lulus Seleksi</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="status_filter" class="form-label">Filter Status</label>
                <select id="status_filter" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu Verifikasi</option>
                    <option value="verifikasi">Dalam Verifikasi</option>
                    <option value="lulus">Lulus</option>
                    <option value="tidak_lulus">Tidak Lulus</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="berkas_filter" class="form-label">Filter Pengiriman Data</label>
                <select id="berkas_filter" class="form-select">
                    <option value="">Semua Data</option>
                    <option value="sudah">Sudah Dikirim (Upload Berkas)</option>
                    <option value="belum">Belum Dikirim</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="jalur_filter" class="form-label">Filter Jalur</label>
                <select id="jalur_filter" class="form-select">
                    <option value="">Semua Jalur</option>
                    @foreach($jalarList as $jalur)
                        <option value="{{ $jalur }}">{{ $jalur }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Nama/NISN</label>
                <input type="text" id="search" class="form-control" placeholder="Ketik nama atau NISN...">
            </div>
            <div class="col-md-2">
                <button onclick="resetFilters()" class="btn btn-outline-secondary w-100">
                    <i class="mdi mdi-refresh me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

<script>
// Filter pengiriman data (berkas)
document.getElementById('berkas_filter').addEventListener('change', function() {
    const value = this.value;
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const sudahBerkas = row.getAttribute('data-sudah-berkas');
        if (value === '' || (value === 'sudah' && sudahBerkas === '1') || (value === 'belum' && sudahBerkas === '0')) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

    <!-- Pendaftar List -->
    @if($pendaftars->count() > 0)
        <!-- Table View (Default) -->
        <div id="table-view" class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ranking</th>
                        <th>Nama Lengkap</th>
                        <th>NISN</th>
                        <th>Asal Sekolah</th>
                        <th>Jurusan</th>
                        <th>Jalur</th>
                        <th>Skor Total</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftars as $index => $pendaftar)
                    <tr data-status="{{ $pendaftar->status }}" data-jalur="{{ $pendaftar->ppdbJalur->nama_jalur ?? '' }}" data-nama="{{ $pendaftar->nama_lengkap }}" data-nisn="{{ $pendaftar->nisn }}" data-sudah-berkas="{{ ($pendaftar->berkas_kk || $pendaftar->berkas_ijazah || $pendaftar->berkas_akta_kelahiran || $pendaftar->berkas_ktp_ayah || $pendaftar->berkas_ktp_ibu || $pendaftar->berkas_raport || $pendaftar->berkas_sertifikat_prestasi || $pendaftar->berkas_kip_pkh || $pendaftar->berkas_bukti_domisili || $pendaftar->berkas_surat_mutasi || $pendaftar->berkas_surat_keterangan_lulus || $pendaftar->berkas_skl) ? '1' : '0' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $pendaftar->nama_lengkap }}</div>
                            <small class="text-muted">{{ $pendaftar->nomor_pendaftaran }}</small>
                        </td>
                        <td>{{ $pendaftar->nisn }}</td>
                        <td>{{ $pendaftar->asal_sekolah }}</td>
                        <td>{{ $pendaftar->jurusan_pilihan }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $pendaftar->ppdbJalur->nama_jalur ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $pendaftar->skor_total ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $pendaftar->status }}">
                                @if($pendaftar->status == 'pending')
                                    Menunggu Verifikasi
                                @elseif($pendaftar->status == 'verifikasi')
                                    Dalam Verifikasi
                                @elseif($pendaftar->status == 'lulus')
                                    Lulus
                                @else
                                    Tidak Lulus
                                @endif
                            </span>
                        </td>
                        <td>{{ $pendaftar->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="showDetail({{ $pendaftar->id }})" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                                @if($pendaftar->berkas_kk)
                                    <a href="{{ asset($pendaftar->berkas_kk) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat KK">
                                        <i class="mdi mdi-file-document"></i>
                                    </a>
                                @endif
                                @if($pendaftar->berkas_ijazah)
                                    <a href="{{ asset($pendaftar->berkas_ijazah) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Ijazah">
                                        <i class="mdi mdi-file-document-outline"></i>
                                    </a>
                                @endif
                                @if($pendaftar->berkas_sertifikat_prestasi)
                                    @php
                                        $sertifikatFiles = is_array($pendaftar->berkas_sertifikat_prestasi) ? $pendaftar->berkas_sertifikat_prestasi : json_decode($pendaftar->berkas_sertifikat_prestasi, true);
                                        if (!is_array($sertifikatFiles)) {
                                            $sertifikatFiles = [$pendaftar->berkas_sertifikat_prestasi];
                                        }
                                    @endphp
                                    @foreach($sertifikatFiles as $index => $file)
                                        <a href="{{ asset($file) }}" target="_blank" class="btn btn-sm btn-outline-warning" title="Lihat Sertifikat {{ count($sertifikatFiles) > 1 ? ($index + 1) : '' }}">
                                            <i class="mdi mdi-trophy-outline"></i>{{ count($sertifikatFiles) > 1 ? ($index + 1) : '' }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pendaftars->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="mdi mdi-account-multiple-outline"></i>
            <h5>Belum ada pendaftar</h5>
            <p class="mb-0">Belum ada siswa yang mendaftar di sekolah ini.</p>
        </div>
    @endif
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-account-card-details me-2"></i>Detail Pendaftar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent" style="max-height: 70vh; overflow-y: auto;">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <!-- Verification buttons will be loaded here -->
                    <div id="verificationButtons"></div>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Berkas Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Berkas Tambahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="upload_pendaftar_id" name="pendaftar_id">

                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        Upload berkas tambahan yang belum lengkap untuk melengkapi data pendaftar.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Akta Kelahiran</label>
                                <input type="file" class="form-control" name="berkas_akta_kelahiran" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KTP Ayah</label>
                                <input type="file" class="form-control" name="berkas_ktp_ayah" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KTP Ibu</label>
                                <input type="file" class="form-control" name="berkas_ktp_ibu" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Sertifikat Prestasi</label>
                                <input type="file" class="form-control" name="berkas_sertifikat_prestasi" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KIP/PKH</label>
                                <input type="file" class="form-control" name="berkas_kip_pkh" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload Berkas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('warning'))
<script>
Swal.fire({
    icon: 'warning',
    title: 'Tidak Ada Pendaftar',
    text: '{{ session("warning") }}',
    confirmButtonText: 'OK'
});
</script>
@endif
<script>
function showDetail(pendaftarId) {
    // Load detail content via AJAX
    fetch(`/ppdb/lp/pendaftar-detail/${pendaftarId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('detailContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        })
        .catch(error => {
            console.error('Error loading detail:', error);
            alert('Gagal memuat detail pendaftar');
        });
}

function showUploadModal(pendaftarId, namaPendaftar) {
    document.getElementById('upload_pendaftar_id').value = pendaftarId;
    document.querySelector('#uploadModal .modal-title').textContent = `Upload Berkas Tambahan - ${namaPendaftar}`;
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
}

// Handle upload form submission
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/ppdb/admin/upload-berkas', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Berkas berhasil diupload!');
            bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
            location.reload(); // Reload page to show updated data
        } else {
            alert('Gagal upload berkas: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat upload berkas');
    });
});

function exportToExcel() {
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("ppdb.sekolah.export") }}';

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function resetFilters() {
    document.getElementById('status_filter').value = '';
    document.getElementById('jalur_filter').value = '';
    document.getElementById('search').value = '';
    filterTable();
}

function filterTable() {
    const statusFilter = document.getElementById('status_filter').value.toLowerCase();
    const jalurFilter = document.getElementById('jalur_filter').value.toLowerCase();
    const searchTerm = document.getElementById('search').value.toLowerCase();

    const rows = document.querySelectorAll('#table-view tbody tr');

    rows.forEach(row => {
        const status = row.dataset.status.toLowerCase();
        const jalur = row.dataset.jalur.toLowerCase();
        const nama = row.dataset.nama.toLowerCase();
        const nisn = row.dataset.nisn.toLowerCase();

        const statusMatch = !statusFilter || status === statusFilter;
        const jalurMatch = !jalurFilter || jalur.includes(jalurFilter);
        const searchMatch = !searchTerm || nama.includes(searchTerm) || nisn.includes(searchTerm);

        if (statusMatch && jalurMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Add event listeners for filters
document.getElementById('status_filter').addEventListener('change', filterTable);
document.getElementById('jalur_filter').addEventListener('change', filterTable);
document.getElementById('search').addEventListener('input', filterTable);

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    filterTable();
});

function verifikasiData(pendaftarId) {
    setStatus(pendaftarId, 'verifikasi');
}

function setStatus(pendaftarId, status) {
    if (confirm('Apakah Anda yakin ingin mengubah status menjadi ' + (status === 'verifikasi' ? 'Dalam Verifikasi' : status === 'lulus' ? 'Lulus' : 'Tidak Lulus') + '?')) {
        fetch('/ppdb/lp/pendaftar/' + pendaftarId + '/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status berhasil diupdate');
                location.reload();
            } else {
                alert('Gagal update status: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat update status');
        });
    }
}
</script>
@endpush
@endsection
