<?php $__env->startSection('title', 'Data Anggota MGMP - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('mgmp.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #f8fafc;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HEADER */
    .page-header {
        margin-top: 100px;
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .page-header p {
        font-size: 15px;
        color: #64748b;
    }

    /* FILTER SECTION */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
    }

    .filter-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        color: #334155;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #004b4c;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 75, 76, 0.1);
    }

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 75, 76, 0.3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    /* DATA TABLE SECTION */
    .data-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
        margin-bottom: 40px;
    }

    .data-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-header h3 {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
    }

    .data-header-actions {
        display: flex;
        gap: 12px;
    }

    /* TABLE */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8fafc;
        border-radius: 8px 8px 0 0;
    }

    .data-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover td {
        background: #f8fafc;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #004b4c, #006666);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .user-details h4 {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .user-details p {
        font-size: 12px;
        color: #64748b;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #d97706;
    }

    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 14px;
        color: #64748b;
        background: transparent;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: #f1f5f9;
        color: #004b4c;
        border-color: #004b4c;
    }

    /* PAGINATION */
    .pagination-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        margin-top: 20px;
    }

    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }

    .pagination {
        display: flex;
        gap: 8px;
    }

    .pagination a,
    .pagination span {
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background: #004b4c;
        color: white;
        border-color: #004b4c;
    }

    .pagination .active span {
        background: #004b4c;
        color: white;
        border-color: #004b4c;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #94a3b8;
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            margin-top: 80px;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .data-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .data-table {
            display: block;
            overflow-x: auto;
        }

        .pagination-section {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <h1 class="animate fade-up">Data Anggota MGMP</h1>
        <p class="animate fade-up delay-1">Kelola dan lihat informasi lengkap seluruh anggota MGMP LP Ma'arif NU DIY</p>
    </div>
</section>

<!-- FILTER SECTION -->
<section class="container">
    <div class="filter-section animate fade-up delay-1">
        <form action="" method="GET" class="filter-row">
            <div class="filter-group">
                <label>Cari Anggota</label>
                <input type="text" name="search" placeholder="Nama atau email..." value="">
            </div>
            <div class="filter-group">
                <label>Mata Pelajaran</label>
                <select name="mapel">
                    <option value="">Semua Mata Pelajaran</option>
                    <option value="matematika">Matematika</option>
                    <option value="bahasa-indonesia">Bahasa Indonesia</option>
                    <option value="bahasa-inggris">Bahasa Inggris</option>
                    <option value="ipa">IPA</option>
                    <option value="ips">IPS</option>
                    <option value="pkn">PKn</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            <div class="filter-group" style="flex: 0;">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-search'></i> Cari
                </button>
            </div>
        </form>
    </div>
</section>

<!-- DATA TABLE SECTION -->
<section class="container">
    <div class="data-section animate fade-up delay-2">
        <div class="data-header">
            <h3>Daftar Anggota (<?php echo e($anggota->total()); ?> anggota)</h3>
            <div class="data-header-actions">
                <button class="btn btn-secondary">
                    <i class='bx bx-download'></i> Export
                </button>
                <button class="btn btn-primary">
                    <i class='bx bx-plus'></i> Tambah Anggota
                </button>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($anggota->count() > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th>Anggota</th>
                    <th>Mata Pelajaran</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <tr>
                    <td>
                        <input type="checkbox" class="select-item">
                    </td>
                    <td>
                        <div class="user-info">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $item->avatar)); ?>" alt="<?php echo e($item->name); ?>" class="user-avatar" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="user-avatar">
                                    <?php echo e(substr($item->name, 0, 2)); ?>

                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="user-details">
                                <h4><?php echo e($item->name); ?></h4>
                                <p><?php echo e($item->email); ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->mapel ?? false): ?>
                            <span style="color: #0f172a; font-weight: 500;"><?php echo e($item->mapel); ?></span>
                        <?php else: ?>
                            <span style="color: #94a3b8;">-</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->phone ?? false): ?>
                            <?php echo e($item->phone); ?>

                        <?php else: ?>
                            <span style="color: #94a3b8;">-</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge active">
                            <?php echo e(($item->is_active ?? true) ? 'Aktif' : 'Tidak Aktif'); ?>

                        </span>
                    </td>
                    <td>
                        <button class="action-btn" title="Lihat Detail">
                            <i class='bx bx-eye'></i>
                        </button>
                        <button class="action-btn" title="Edit">
                            <i class='bx bx-edit'></i>
                        </button>
                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="pagination-section">
            <div class="pagination-info">
                Menampilkan <?php echo e($anggota->firstItem()); ?> - <?php echo e($anggota->lastItem()); ?> dari <?php echo e($anggota->total()); ?> anggota
            </div>
            <div class="pagination">
                <?php echo e($anggota->links()); ?>

            </div>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class='bx bx-user-x'></i>
            </div>
            <h3>Belum Ada Anggota</h3>
            <p>Tidak ada anggota MGMP yang ditemukan.</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Animation observer
    const animatedElements = document.querySelectorAll(".animate");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    });

    animatedElements.forEach(el => observer.observe(el));

    // Select all checkbox
    const selectAll = document.getElementById('select-all');
    const selectItems = document.querySelectorAll('.select-item');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            selectItems.forEach(item => {
                item.checked = this.checked;
            });
        });
    }
});
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/data_anggota.blade.php ENDPATH**/ ?>