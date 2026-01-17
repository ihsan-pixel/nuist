@extends('layouts.master')

@section('title') Data Guru per Tahun  @endsection

@section('css')
<style>
/* Modern Card Grid Design */
.setting-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.setting-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.setting-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.setting-card.active::before { background: linear-gradient(90deg, #28a745, #20c997); }
.setting-card.inactive::before { background: linear-gradient(90deg, #6c757d, #495057); }

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.card-icon.active { background: linear-gradient(135deg, #28a745, #20c997); }
.card-icon.inactive { background: linear-gradient(135deg, #6c757d, #495057); }

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.card-date {
    color: #718096;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.badge-modern {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-modern.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
.badge-modern.bg-success { background: linear-gradient(135deg, #48bb78, #38a169) !important; }
.badge-modern.bg-info { background: linear-gradient(135deg, #4299e1, #3182ce) !important; }
.badge-modern.bg-warning { background: linear-gradient(135deg, #ed8936, #dd6b20) !important; }
.badge-modern.bg-secondary { background: linear-gradient(135deg, #a0aec0, #718096) !important; }

.card-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 15px;
}

.card-details {
    background: #f7fafc;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #667eea;
    margin-bottom: 15px;
}

.card-details small {
    color: #718096;
    font-size: 0.8rem;
}

.nominal-info {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
}

.nominal-info .nominal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.nominal-info .nominal-item:last-child {
    margin-bottom: 0;
}

.nominal-info .nominal-label {
    font-size: 0.8rem;
    opacity: 0.9;
}

.nominal-info .nominal-value {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Statistics Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Action Buttons */
.action-buttons {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 8px 30px rgba(72, 187, 120, 0.3);
}

.action-buttons h5 {
    color: white;
    margin-bottom: 1rem;
}

.btn-group-custom .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-group-custom .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(5px);
}

.loading-content {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .btn-group-custom .btn {
        width: 100%;
        margin-right: 0;
    }

    .stats-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .card-description {
        font-size: 0.9rem;
    }

    .card-title {
        font-size: 1.1rem;
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 15px;
    border: 2px dashed #cbd5e0;
}

.empty-state .empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

/* Modern Form Select Styling */
.form-select {
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 0.95rem;
    font-weight: 500;
    color: #2d3748;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
    transform: translateY(-1px);
}

.form-select:hover {
    border-color: #cbd5e0;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filter Card Modern Styling */
.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.card-body {
    padding: 2rem;
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('li_2') Data Sekolah @endslot
    @slot('title') Data Guru per Tahun @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-user"></i>
                    Data Guru per Tahun
                </h4>
                <p class="text-white-50 mb-0">
                    Kelola dan pantau data guru berdasarkan status kepegawaian untuk setiap sekolah mulai dari tahun 2023
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-buildings"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Sekolah</p>
                        <h5 class="mb-0">{{ count($data) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Guru {{ request('tahun', date('Y')) }}</p>
                        <h5 class="mb-0">{{ collect($data)->sum('total_guru') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-user-check"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Status Kepegawaian {{ request('tahun', date('Y')) }}</p>
                        <h5 class="mb-0">{{ collect($data)->sum('total_guru') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Tahun</p>
                        <h5 class="mb-0">{{ request('tahun', date('Y')) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('data-sekolah.guru') }}">
                    <div class="row g-3 align-items-end">
                        <!-- Tahun Anggaran -->
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                @for($i = 2023; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Tombol -->
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('data-sekolah.guru') }}" class="btn btn-secondary px-4">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(count($data) > 0)
                    <div class="table-responsive">
                        <table class="table-modern table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sekolah</th>
                                    <th>PNS Sertifikasi</th>
                                    <th>PNS Non Sertifikasi</th>
                                    <th>GTY Sertifikasi</th>
                                    <th>GTY Sertifikasi Inpassing</th>
                                    <th>GTY Non Sertifikasi</th>
                                    <th>GTT</th>
                                    <th>PTY</th>
                                    <th>PTT</th>
                                    <th>Total Guru</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                {{ $item['madrasah']->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['jumlah_pns_sertifikasi']) }}</td>
                                    <td>{{ number_format($item['jumlah_pns_non_sertifikasi']) }}</td>
                                    <td>{{ number_format($item['jumlah_gty_sertifikasi']) }}</td>
                                    <td>{{ number_format($item['jumlah_gty_sertifikasi_inpassing']) }}</td>
                                    <td>{{ number_format($item['jumlah_gty_non_sertifikasi']) }}</td>
                                    <td>{{ number_format($item['jumlah_gtt']) }}</td>
                                    <td>{{ number_format($item['jumlah_pty']) }}</td>
                                    <td>{{ number_format($item['jumlah_ptt']) }}</td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($item['total_guru']) }}</strong>
                                    </td>
                                    <td>{{ $item['tahun'] }}</td>
                                    <td>
                                        <button class="btn-modern btn-sm" onclick="editGuru({{ $item['madrasah']->id }}, '{{ addslashes($item['madrasah']->name) }}', {{ $item['tahun'] }}, {{ $item['jumlah_pns_sertifikasi'] }}, {{ $item['jumlah_pns_non_sertifikasi'] }}, {{ $item['jumlah_gty_sertifikasi'] }}, {{ $item['jumlah_gty_sertifikasi_inpassing'] }}, {{ $item['jumlah_gty_non_sertifikasi'] }}, {{ $item['jumlah_gtt'] }}, {{ $item['jumlah_pty'] }}, {{ $item['jumlah_ptt'] }})">
                                            <i class="bx bx-edit me-1"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bx bx-user"></i>
                    </div>
                    <h5>Tidak Ada Data Guru</h5>
                    <p class="text-muted">Belum ada data guru berdasarkan status kepegawaian untuk tahun {{ request('tahun', date('Y')) }}.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Guru -->
<div class="modal fade" id="editGuruModal" tabindex="-1" aria-labelledby="editGuruModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGuruModalLabel">
                    <i class="bx bx-edit me-2"></i>Edit Data Guru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editGuruForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="edit_guru_madrasah_name" class="form-label">Nama Madrasah/Sekolah</label>
                            <input type="text" class="form-control" id="edit_guru_madrasah_name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_guru_tahun" class="form-label">Tahun</label>
                            <input type="text" class="form-control" id="edit_guru_tahun" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit_pns_sertifikasi" class="form-label">PNS Sertifikasi</label>
                            <input type="number" class="form-control" id="edit_pns_sertifikasi" name="jumlah_pns_sertifikasi" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_pns_non_sertifikasi" class="form-label">PNS Non Sertifikasi</label>
                            <input type="number" class="form-control" id="edit_pns_non_sertifikasi" name="jumlah_pns_non_sertifikasi" min="0" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit_gty_sertifikasi" class="form-label">GTY Sertifikasi</label>
                            <input type="number" class="form-control" id="edit_gty_sertifikasi" name="jumlah_gty_sertifikasi" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_gty_sertifikasi_inpassing" class="form-label">GTY Sertifikasi Inpassing</label>
                            <input type="number" class="form-control" id="edit_gty_sertifikasi_inpassing" name="jumlah_gty_sertifikasi_inpassing" min="0" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit_gty_non_sertifikasi" class="form-label">GTY Non Sertifikasi</label>
                            <input type="number" class="form-control" id="edit_gty_non_sertifikasi" name="jumlah_gty_non_sertifikasi" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_gtt" class="form-label">GTT</label>
                            <input type="number" class="form-control" id="edit_gtt" name="jumlah_gtt" min="0" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit_pty" class="form-label">PTY</label>
                            <input type="number" class="form-control" id="edit_pty" name="jumlah_pty" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_ptt" class="form-label">PTT</label>
                            <input type="number" class="form-control" id="edit_ptt" name="jumlah_ptt" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
@endif

// Function to edit guru data
function editGuru(madrasahId, madrasahName, tahun, pnsSert, pnsNonSert, gtySert, gtySertInpassing, gtyNonSert, gtt, pty, ptt) {
    // Set modal data
    document.getElementById('edit_guru_madrasah_name').value = madrasahName;
    document.getElementById('edit_guru_tahun').value = tahun;
    document.getElementById('edit_pns_sertifikasi').value = pnsSert;
    document.getElementById('edit_pns_non_sertifikasi').value = pnsNonSert;
    document.getElementById('edit_gty_sertifikasi').value = gtySert;
    document.getElementById('edit_gty_sertifikasi_inpassing').value = gtySertInpassing;
    document.getElementById('edit_gty_non_sertifikasi').value = gtyNonSert;
    document.getElementById('edit_gtt').value = gtt;
    document.getElementById('edit_pty').value = pty;
    document.getElementById('edit_ptt').value = ptt;

    // Store IDs for form submission
    document.getElementById('editGuruForm').setAttribute('data-madrasah-id', madrasahId);
    document.getElementById('editGuruForm').setAttribute('data-tahun', tahun);

    // Show modal
    new bootstrap.Modal(document.getElementById('editGuruModal')).show();
}

// Handle form submission
document.getElementById('editGuruForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const madrasahId = this.getAttribute('data-madrasah-id');
    const tahun = this.getAttribute('data-tahun');

    const formData = {
        tahun: tahun,
        jumlah_pns_sertifikasi: document.getElementById('edit_pns_sertifikasi').value,
        jumlah_pns_non_sertifikasi: document.getElementById('edit_pns_non_sertifikasi').value,
        jumlah_gty_sertifikasi: document.getElementById('edit_gty_sertifikasi').value,
        jumlah_gty_sertifikasi_inpassing: document.getElementById('edit_gty_sertifikasi_inpassing').value,
        jumlah_gty_non_sertifikasi: document.getElementById('edit_gty_non_sertifikasi').value,
        jumlah_gtt: document.getElementById('edit_gtt').value,
        jumlah_pty: document.getElementById('edit_pty').value,
        jumlah_ptt: document.getElementById('edit_ptt').value
    };

    // Show loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Send AJAX request
    fetch(`{{ route('data-sekolah.update-guru', ':madrasahId') }}`.replace(':madrasahId', madrasahId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data guru berhasil diperbarui',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat menyimpan data'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan pada server'
        });
    });

    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('editGuruModal')).hide();
});
</script>
@endsection
