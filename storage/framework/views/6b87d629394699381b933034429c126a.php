<?php $__env->startSection('title'); ?>Data Jumlah Siswa per Tahun <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Data Sekolah <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Data Jumlah Siswa per Tahun <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-group"></i>
                    Data Jumlah Siswa per Tahun
                </h4>
                <p class="text-white-50 mb-0">
                    Kelola dan pantau data jumlah siswa untuk setiap sekolah mulai dari tahun 2023
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<?php if(isset($tahunList)): ?>
    <!-- Statistics untuk Admin - menampilkan data semua tahun -->
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
                            <h5 class="mb-0"><?php echo e(count($data) / 4); ?></h5>
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
                            <i class="bx bx-group"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Total Siswa (2023-2026)</p>
                            <h5 class="mb-0"><?php echo e(collect($data)->sum('jumlah_siswa')); ?></h5>
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
                            <i class="bx bx-trending-up"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Rata-rata per Tahun</p>
                            <h5 class="mb-0"><?php echo e(count($data) > 0 ? round(collect($data)->avg('jumlah_siswa')) : 0); ?></h5>
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
                            <p class="text-muted mb-2">Periode</p>
                            <h5 class="mb-0">2023-2026</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Statistics untuk Super Admin - seperti sebelumnya -->
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
                            <h5 class="mb-0"><?php echo e(count($data)); ?></h5>
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
                            <i class="bx bx-group"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Total Siswa <?php echo e($tahun); ?></p>
                            <h5 class="mb-0"><?php echo e(collect($data)->sum('jumlah_siswa')); ?></h5>
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
                            <i class="bx bx-trending-up"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Rata-rata per Sekolah</p>
                            <h5 class="mb-0"><?php echo e(count($data) > 0 ? round(collect($data)->avg('jumlah_siswa')) : 0); ?></h5>
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
                            <h5 class="mb-0"><?php echo e($tahun); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('data-sekolah.siswa')); ?>">
                    <div class="row g-3 align-items-end">
                        <!-- Tahun Anggaran -->
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php for($i = 2023; $i <= date('Y') + 1; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(request('tahun', date('Y')) == $i ? 'selected' : ''); ?>>
                                        <?php echo e($i); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <!-- Tombol -->
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="<?php echo e(route('data-sekolah.siswa')); ?>" class="btn btn-secondary px-4">
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
                <?php if(count($data) > 0): ?>
                    <div class="table-responsive">
                        <table class="table-modern table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sekolah</th>
                                    <?php if(isset($tahunList)): ?>
                                        <!-- Header untuk Admin - kolom tahun 2023-2026 -->
                                        <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th>Jumlah Siswa <?php echo e($tahun); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <th>Total Siswa</th>
                                        <th>Aksi</th>
                                    <?php else: ?>
                                        <!-- Header untuk Super Admin - seperti sebelumnya -->
                                        <th>Jumlah Siswa</th>
                                        <th>Tahun</th>
                                        <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php if(isset($tahunList)): ?>
                                        <!-- Data untuk Admin - kolom tahun 2023-2026 -->
                                        <td><?php echo e($item['no']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <?php echo e($item['madrasah']->name); ?>

                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e($item['tahun']); ?></td>
                                        <td><?php echo e(number_format($item['jumlah_siswa'])); ?></td>
                                        <td>
                                            <button class="btn-modern btn-sm" onclick="editSiswa(<?php echo e($item['madrasah']->id); ?>, '<?php echo e(addslashes($item['madrasah']->name)); ?>', <?php echo e($item['tahun']); ?>, <?php echo e($item['jumlah_siswa']); ?>)">
                                                <i class="bx bx-edit me-1"></i> Edit
                                            </button>
                                        </td>
                                    <?php else: ?>
                                        <!-- Data untuk Super Admin - seperti sebelumnya -->
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <?php echo e($item['madrasah']->name); ?>

                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e(number_format($item['jumlah_siswa'])); ?></td>
                                        <td><?php echo e($item['tahun']); ?></td>
                                        <td>
                                            <button class="btn-modern btn-sm" onclick="editSiswa(<?php echo e($item['madrasah']->id); ?>, '<?php echo e(addslashes($item['madrasah']->name)); ?>', <?php echo e($item['tahun']); ?>, <?php echo e($item['jumlah_siswa']); ?>)">
                                                <i class="bx bx-edit me-1"></i> Edit
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bx bx-group"></i>
                        </div>
                        <h5>Tidak Ada Data Siswa</h5>
                        <?php if(isset($tahunList)): ?>
                            <p class="text-muted">Belum ada data siswa untuk periode 2023-2026.</p>
                        <?php else: ?>
                            <p class="text-muted">Belum ada data siswa untuk tahun <?php echo e(request('tahun', date('Y'))); ?>.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Siswa -->
<div class="modal fade" id="editSiswaModal" tabindex="-1" aria-labelledby="editSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSiswaModalLabel">
                    <i class="bx bx-edit me-2"></i>Edit Data Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSiswaForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="edit_madrasah_name" class="form-label">Nama Madrasah/Sekolah</label>
                            <input type="text" class="form-control" id="edit_madrasah_name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_tahun" class="form-label">Tahun</label>
                            <input type="text" class="form-control" id="edit_tahun" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="edit_jumlah_siswa" class="form-label">Jumlah Siswa</label>
                            <input type="number" class="form-control" id="edit_jumlah_siswa" name="jumlah_siswa" min="0" required>
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
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>

// Function to edit siswa data
function editSiswa(madrasahId, madrasahName, tahun, jumlahSiswa) {
    // Set modal data
    document.getElementById('edit_madrasah_name').value = madrasahName;
    document.getElementById('edit_tahun').value = tahun;
    document.getElementById('edit_jumlah_siswa').value = jumlahSiswa;

    // Store IDs for form submission
    document.getElementById('editSiswaForm').setAttribute('data-madrasah-id', madrasahId);
    document.getElementById('editSiswaForm').setAttribute('data-tahun', tahun);

    // Show modal
    new bootstrap.Modal(document.getElementById('editSiswaModal')).show();
}

// Handle form submission
document.getElementById('editSiswaForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const madrasahId = this.getAttribute('data-madrasah-id');
    const tahun = this.getAttribute('data-tahun');
    const jumlahSiswa = document.getElementById('edit_jumlah_siswa').value;

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
    fetch(`<?php echo e(route('data-sekolah.update-siswa', ':madrasahId')); ?>`.replace(':madrasahId', madrasahId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tahun: tahun,
            jumlah_siswa: jumlahSiswa
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data siswa berhasil diperbarui',
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
    bootstrap.Modal.getInstance(document.getElementById('editSiswaModal')).hide();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/data-sekolah/siswa.blade.php ENDPATH**/ ?>