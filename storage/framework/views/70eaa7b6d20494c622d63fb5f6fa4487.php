<?php $__env->startSection('title', 'Data Sekolah'); ?>
<?php $__env->startSection('subtitle', 'Daftar Madrasah'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 520px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        .stats-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 12px;
        }

        .sekolah-card {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
            border-left: 4px solid #004b4c;
        }

        .sekolah-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .sekolah-logo {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .search-box {
            background: #fff;
            border-radius: 10px;
            padding: 10px 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 12px;
        }

        .search-box input {
            border: none;
            outline: none;
            font-size: 13px;
            width: 100%;
            padding: 4px 8px;
        }

        .search-box input::placeholder {
            color: #adb5bd;
        }

        .search-box button {
            background: none;
            border: none;
            color: #004b4c;
            cursor: pointer;
        }

        .badge-status {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .badge-aktif {
            background: #d4edda;
            color: #155724;
        }

        .badge-nonaktif {
            background: #f8d7da;
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: #dee2e6;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 16px;
            gap: 8px;
        }

        .pagination-wrapper .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #fff;
            color: #004b4c;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            border: none;
        }

        .pagination-wrapper .page-btn:hover {
            background: #004b4c;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
        }

        .pagination-wrapper .page-btn.disabled {
            opacity: 0.5;
            pointer-events: none;
            background: #f8f9fa;
            color: #adb5bd;
        }

        .pagination-wrapper .page-btn.active {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
        }

        .pagination-wrapper .page-info {
            font-size: 12px;
            color: #6c757d;
            margin: 0 8px;
        }
    </style>

    <!-- Page Title -->
    <div class="text-center mb-3">
        <h5 class="mb-0 fw-semibold text-dark" style="font-size: 16px;">
            <i class="bx bx-building me-2" style="color: #004b4c;"></i>
            Data Sekolah
        </h5>
        <small class="text-muted" style="font-size: 11px;"><?php echo e($totalSekolah); ?> Madrasah Terdaftar</small>
    </div>

    <!-- Search Box -->
    <form action="<?php echo e(route('mobile.pengurus.sekolah')); ?>" method="GET" class="search-box d-flex align-items-center">
        <button type="submit">
            <i class="bx bx-search" style="font-size: 18px;"></i>
        </button>
        <input
            type="text"
            name="search"
            placeholder="Cari nama sekolah, kabupaten, atau alamat..."
            value="<?php echo e($search ?? ''); ?>"
            autocomplete="off"
        >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
        <a href="<?php echo e(route('mobile.pengurus.sekolah')); ?>" class="text-muted ms-2">
            <i class="bx bx-x" style="font-size: 18px;"></i>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </form>

    <!-- Schools List -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahs->count() > 0): ?>
    <div class="sekolah-list">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('mobile.pengurus.sekolah.show', $madrasah->id)); ?>" class="text-decoration-none">
            <div class="sekolah-card d-flex align-items-start">
                <div class="me-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->logo): ?>
                    <img
                        src="<?php echo e(asset('storage/' . $madrasah->logo)); ?>"
                        alt="<?php echo e($madrasah->name); ?>"
                        class="sekolah-logo"
                        onerror="this.src='<?php echo e(asset('build/images/logo-light.png')); ?>'"
                    >
                    <?php else: ?>
                    <img
                        src="<?php echo e(asset('build/images/logo-light.png')); ?>"
                        alt="<?php echo e($madrasah->name); ?>"
                        class="sekolah-logo"
                    >
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="grow min-width-0">
                    <div class="d-flex align-items-center mb-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->scod): ?>
                        <span class="badge bg-primary me-2" style="font-size: 9px;"><?php echo e($madrasah->scod); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <h6 class="mb-0 fw-semibold text-dark" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo e($madrasah->name); ?>

                        </h6>
                    </div>
                    <p class="mb-1 text-muted" style="font-size: 11px; line-height: 1.3;">
                        <i class="bx bx-map me-1" style="color: #0e8549;"></i>
                        <?php echo e($madrasah->kabupaten ?: 'Kabupaten belum diisi'); ?>

                    </p>
                    <p class="mb-0 text-muted" style="font-size: 11px; line-height: 1.3;">
                        <i class="bx bx-group me-1" style="color: #6c5ce7;"></i>
                        Siswa: <?php echo e(number_format($jumlahSiswaData[$madrasah->id] ?? 0)); ?>

                    </p>
                </div>
                <div class="ms-2">
                    <i class="bx bx-chevron-right text-muted" style="font-size: 18px;"></i>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahs->hasPages()): ?>
    <div class="pagination-wrapper">
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahs->onFirstPage()): ?>
        <span class="page-btn disabled">
            <i class="bx bx-chevron-left"></i>
        </span>
        <?php else: ?>
        <a href="<?php echo e($madrasahs->previousPageUrl() . ($search ? '&search=' . $search : '')); ?>" class="page-btn">
            <i class="bx bx-chevron-left"></i>
        </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <span class="page-info">
            <?php echo e($madrasahs->currentPage()); ?>/<?php echo e($madrasahs->lastPage()); ?>

        </span>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahs->hasMorePages()): ?>
        <a href="<?php echo e($madrasahs->nextPageUrl() . ($search ? '&search=' . $search : '')); ?>" class="page-btn">
            <i class="bx bx-chevron-right"></i>
        </a>
        <?php else: ?>
        <span class="page-btn disabled">
            <i class="bx bx-chevron-right"></i>
        </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php else: ?>
    <div class="empty-state">
        <i class="bx bx-building"></i>
        <h6 class="mb-2" style="font-size: 14px;">Tidak ada data sekolah</h6>
        <p class="mb-0" style="font-size: 12px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
            Tidak ditemukan sekolah dengan kata kunci "<?php echo e($search); ?>"
            <?php else: ?>
            Belum ada data madrasah yang terdaftar
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification badge
    fetchUnreadNotifications();

    async function fetchUnreadNotifications() {
        try {
            const response = await fetch('<?php echo e(route("mobile.notifications.unread-count")); ?>');
            const data = await response.json();
            const badge = document.getElementById('notificationBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'block';
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mobile-pengurus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/sekolah.blade.php ENDPATH**/ ?>