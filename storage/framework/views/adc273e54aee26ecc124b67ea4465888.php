<?php $__env->startSection('title'); ?>Perhitungan Iuran UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
/* Modern Card Grid Design */
.history-card {
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

.history-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.history-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.history-card.migration::before { background: linear-gradient(90deg, #6c757d, #495057); }
.history-card.feature::before { background: linear-gradient(90deg, #28a745, #20c997); }
.history-card.update::before { background: linear-gradient(90deg, #17a2b8, #138496); }
.history-card.bugfix::before { background: linear-gradient(90deg, #ffc107, #e0a800); }
.history-card.enhancement::before { background: linear-gradient(90deg, #6f42c1, #5a32a3); }

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

.card-icon.migration { background: linear-gradient(135deg, #6c757d, #495057); }
.card-icon.feature { background: linear-gradient(135deg, #28a745, #20c997); }
.card-icon.update { background: linear-gradient(135deg, #17a2b8, #138496); }
.card-icon.bugfix { background: linear-gradient(135deg, #ffc107, #e0a800); }
.card-icon.enhancement { background: linear-gradient(135deg, #6f42c1, #5a32a3); }

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
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 8px 30px rgba(245, 87, 108, 0.3);
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
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> UPPM <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Perhitungan Iuran <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-calculator me-2"></i>
                    Perhitungan Iuran UPPM
                </h4>
                <p class="text-white-50 mb-0">
                    Hitung dan pantau iuran UPPM berdasarkan data sekolah untuk tahun <?php echo e(request('tahun', date('Y'))); ?>

                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-calculator"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Perhitungan</p>
                        <h5 class="mb-0"><?php echo e(count($perhitungan)); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-group"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Siswa</p>
                        <h5 class="mb-0"><?php echo e(array_sum(array_map(function($item) { return $item['data']->jumlah_siswa ?? 0; }, $perhitungan))); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Guru</p>
                        <h5 class="mb-0"><?php echo e(array_sum(array_map(function($item) { return ($item['data']->jumlah_pns_sertifikasi ?? 0) + ($item['data']->jumlah_pns_non_sertifikasi ?? 0) + ($item['data']->jumlah_gty_sertifikasi ?? 0) + ($item['data']->jumlah_gty_sertifikasi_inpassing ?? 0) + ($item['data']->jumlah_gty_non_sertifikasi ?? 0) + ($item['data']->jumlah_gtt ?? 0); }, $perhitungan))); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bx bx-briefcase"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Karyawan</p>
                        <h5 class="mb-0"><?php echo e(array_sum(array_map(function($item) { return ($item['data']->jumlah_pty ?? 0) + ($item['data']->jumlah_ptt ?? 0); }, $perhitungan))); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Bulanan</p>
                        <h5 class="mb-0">Rp <?php echo e(number_format(array_sum(array_column($perhitungan, 'nominal_bulanan')))); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Tahunan</p>
                        <h5 class="mb-0">Rp <?php echo e(number_format(array_sum(array_column($perhitungan, 'total_tahunan')))); ?></h5>
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
                <form method="GET" action="<?php echo e(route('uppm.perhitungan-iuran')); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="tahun" class="form-label">Tahun Anggaran</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php for($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(request('tahun', date('Y')) == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="<?php echo e(route('uppm.perhitungan-iuran')); ?>" class="btn btn-secondary">
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
                <?php if(count($perhitungan) > 0): ?>
                    <div class="table-responsive">
                        <table class="table-modern table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sekolah</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Jumlah PNS Sertifikasi</th>
                                    <th>Jumlah PNS Non Sertifikasi</th>
                                    <th>Jumlah GTY Sertifikasi</th>
                                    <th>Jumlah GTY Sertifikasi Inpassing</th>
                                    <th>Jumlah GTY Non Sertifikasi</th>
                                    <th>Jumlah GTT</th>
                                    <th>Jumlah PTY</th>
                                    <th>Jumlah PTT</th>
                                    <th>Total Nominal UPPM per Tahun</th>
                                    <th>Status Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $perhitungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <?php echo e($item['madrasah']->name); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e(number_format($item['data']->jumlah_siswa)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_pns_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_pns_non_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_gty_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_gty_sertifikasi_inpassing ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_gty_non_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_gtt ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_pty ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item['data']->jumlah_ptt ?? 0)); ?></td>
                                    <td>
                                        <strong class="text-success">Rp <?php echo e(number_format($item['total_tahunan'])); ?></strong>
                                    </td>
                                    <td>
                                        <?php if($item['tagihan']): ?>
                                            <span class="badge badge-modern bg-<?php echo e($item['tagihan']->status == 'lunas' ? 'success' : ($item['tagihan']->status == 'sebagian' ? 'warning' : 'danger')); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $item['tagihan']->status))); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern bg-secondary">
                                                Belum Dibuat
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('uppm.invoice', ['madrasah_id' => $item['madrasah']->id, 'tahun' => request('tahun', date('Y'))])); ?>" class="btn-modern btn-sm">
                                            <i class="bx bx-receipt me-1"></i> Invoice
                                        </a>
                                        <?php if($item['tagihan_exists']): ?>
                                            <span class="badge badge-modern bg-info ms-1">
                                                <i class="bx bx-check me-1"></i> Tagihan Sudah Dibuat
                                            </span>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-success btn-sm ms-1" onclick="openBuatTagihanModal(<?php echo e($item['madrasah']->id); ?>, '<?php echo e($item['madrasah']->name); ?>', <?php echo e($item['total_tahunan']); ?>)">
                                                <i class="bx bx-plus me-1"></i> Buat Tagihan
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bx bx-calculator"></i>
                        </div>
                        <h5>Tidak Ada Data Perhitungan</h5>
                        <p class="text-muted">Belum ada data perhitungan iuran untuk tahun <?php echo e(request('tahun', date('Y'))); ?>.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Tagihan -->
<div class="modal fade" id="buatTagihanModal" tabindex="-1" aria-labelledby="buatTagihanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buatTagihanModalLabel">Buat Tagihan UPPM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="buatTagihanForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_madrasah_name" class="form-label">Nama Madrasah</label>
                                <input type="text" class="form-control" id="modal_madrasah_name" readonly>
                                <input type="hidden" id="modal_madrasah_id" name="madrasah_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_tahun_anggaran" class="form-label">Tahun Anggaran</label>
                                <input type="number" class="form-control" id="modal_tahun_anggaran" name="tahun_anggaran"
                                       value="<?php echo e(request('tahun', date('Y'))); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_nominal" class="form-label">Nominal Tagihan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="modal_nominal" name="nominal"
                                           placeholder="0" required min="0" step="1000">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_jatuh_tempo" class="form-label">Jatuh Tempo</label>
                                <input type="date" class="form-control" id="modal_jatuh_tempo" name="jatuh_tempo" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="modal_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="modal_keterangan" name="keterangan" rows="3"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Buat Tagihan</button>
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

function openBuatTagihanModal(madrasahId, madrasahName, nominal) {
    document.getElementById('modal_madrasah_id').value = madrasahId;
    document.getElementById('modal_madrasah_name').value = madrasahName;
    document.getElementById('modal_nominal').value = nominal;

    // Set default jatuh tempo to end of current year
    const currentYear = new Date().getFullYear();
    const defaultDueDate = `${currentYear}-12-31`;
    document.getElementById('modal_jatuh_tempo').value = defaultDueDate;

    // Reset form
    document.getElementById('modal_keterangan').value = '';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('buatTagihanModal'));
    modal.show();
}

document.getElementById('buatTagihanForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('<?php echo e(route("uppm.store-tagihan")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal membuat tagihan. Silakan coba lagi.',
        });
    });

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('buatTagihanModal'));
    modal.hide();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/perhitungan-iuran.blade.php ENDPATH**/ ?>