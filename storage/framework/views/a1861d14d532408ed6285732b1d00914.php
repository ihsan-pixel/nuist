<?php $__env->startSection('title', 'Monitoring Presensi'); ?>
<?php $__env->startSection('subtitle', 'Data Presensi Madrasah'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .monitor-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .back-btn {
            background: none;
            border: none;
            color: #004b4c;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .back-btn:hover { background: #f0f8f0; }

        .monitor-header h6 { font-weight: 600; font-size: 12px; }
        .monitor-header h5 { font-size: 14px; }

        .date-nav {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .date-nav button {
        background: #f4f8f5;
        border: 1px solid #d8e5da;
        color: #0e8549;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .date-nav button:hover {
        background: #0e8549;
        color: #fff;
        transform: scale(1.05);
    }

    .date-nav i {
        font-size: 20px;
        line-height: 1;
    }

    .date-nav .current-date {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        flex: 1;
        text-align: center;
    }

        .presensi-section {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 8px;
            color: #333;
            display: flex;
            align-items: center;
        }

        .section-title i { margin-right: 5px; }

        .presensi-list { list-style: none; padding: 0; margin: 0; }

        .presensi-item {
            padding: 8px 0;
            border-bottom: 1px solid #f1f1f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .presensi-item:last-child { border-bottom: none; }

        .presensi-info { flex: 1; }

        .presensi-info .name {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .presensi-info .status {
            font-size: 10px;
            color: #6c757d;
        }

        .presensi-time { text-align: right; }

        .presensi-time .time {
            font-weight: 600;
            font-size: 12px;
            color: #0e8549;
        }

        .presensi-time .sub-time {
            font-size: 10px;
            color: #6c757d;
        }

        .izin-badge {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: 600;
            margin-left: 4px;
        }

        .foto-btn {
            background: none;
            border: none;
            color: #0e8549;
            font-size: 10px;
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
            margin-top: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .pagination-custom {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .pagination-custom .page-link {
            color: #0e8549;
            border-color: #0e8549;
            margin: 0 2px;
            padding: 5px 10px;
            font-size: 12px;
        }

        .pagination-custom .page-link:hover {
            background-color: #0e8549;
            color: #fff;
        }

        .pagination-custom .page-item.active .page-link {
            background-color: #0e8549;
            border-color: #0e8549;
        }

        /* Modal Foto */
        .modal-backdrop { background-color: rgba(0, 0, 0, 0.6); }
        .modal-content img { width: 100%; border-radius: 8px; }
        .modal-title { font-size: 13px; font-weight: 600; }
    </style>

    <!-- Back Button -->
    <a href="<?php echo e(route('mobile.dashboard')); ?>" class="back-btn">
        <i class="bx bx-arrow-back"></i>
        <span>Kembali</span>
    </a>

    <!-- Header -->
    <div class="monitor-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Monitoring Presensi</h6>
                <h5 class="fw-bold mb-0"><?php echo e(Auth::user()->madrasah?->name ?? 'Madrasah'); ?></h5>
            </div>
            <img src="<?php echo e(isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg')); ?>"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- Date Navigation -->
    <div class="date-nav">
        <button type="button" onclick="changeDate('<?php echo e($selectedDate->copy()->subDay()->toDateString()); ?>')">
            <i class="bx bx-chevron-left"></i>
        </button>
        <div class="current-date"><?php echo e($selectedDate->format('d M Y')); ?></div>
        <button type="button" onclick="changeDate('<?php echo e($selectedDate->copy()->addDay()->toDateString()); ?>')">
            <i class="bx bx-chevron-right"></i>
        </button>
    </div>

    <!-- Sudah Presensi -->
    <div class="presensi-section">
        <h6 class="section-title">
            <i class="bx bx-check-circle text-success"></i>
            Sudah Presensi (<?php echo e($presensis->total()); ?>)
        </h6>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($presensis->isEmpty()): ?>
            <div class="empty-state">
                <i class="bx bx-calendar-x"></i>
                <p>Belum ada presensi pada tanggal ini.</p>
            </div>
        <?php else: ?>
            <ul class="presensi-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <li class="presensi-item">
                        <div class="presensi-info">
                            <div class="name">
                                <?php echo e($p->user->name ?? '-'); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->status === 'izin'): ?>
                                    <span class="izin-badge">IZIN</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="status"><?php echo e($p->user->statusKepegawaian?->name ?? '-'); ?></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->selfie_masuk_path || $p->selfie_keluar_path): ?>
                                <button class="foto-btn"
                                    onclick="showFoto(
                                        '<?php echo e(url("/foto/masuk/".$p->id)); ?>',
                                        '<?php echo e(url("/foto/keluar/".$p->id)); ?>'
                                    )">
                                    📷 Lihat Foto
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="presensi-time">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->status === 'izin'): ?>
                                <div class="time">Izin</div>
                                <div class="sub-time"><?php echo e($p->keterangan ? Str::limit($p->keterangan, 20) : 'Tanpa keterangan'); ?></div>
                            <?php else: ?>
                                <div class="time"><?php echo e($p->waktu_masuk?->format('H:i') ?? '-'); ?></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->waktu_keluar): ?>
                                    <div class="sub-time">Keluar <?php echo e($p->waktu_keluar->format('H:i')); ?></div>
                                <?php else: ?>
                                    <div class="sub-time">Belum keluar</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($presensis->hasPages()): ?>
                <div class="pagination-custom">
                    <?php echo e($presensis->appends(request()->query())->links()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Belum Presensi -->
    <div class="presensi-section">
        <h6 class="section-title">
            <i class="bx bx-x-circle text-warning"></i>
            Belum Presensi (<?php echo e($belumPresensi->total()); ?>)
        </h6>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($belumPresensi->isEmpty()): ?>
            <div class="empty-state">
                <i class="bx bx-check-circle"></i>
                <p>Semua tenaga pendidik telah melakukan presensi.</p>
            </div>
        <?php else: ?>
            <ul class="presensi-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $belumPresensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <li class="presensi-item">
                        <div class="presensi-info">
                            <div class="name"><?php echo e($u->name); ?></div>
                            <div class="status"><?php echo e($u->statusKepegawaian?->name ?? '-'); ?></div>
                        </div>
                        <div class="presensi-time">
                            <div class="time">-</div>
                            <div class="sub-time">Belum presensi</div>
                        </div>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($belumPresensi->hasPages()): ?>
                <div class="pagination-custom">
                    <?php echo e($belumPresensi->appends(request()->query())->links()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<!-- Modal Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-2">
      <div class="modal-header border-0 pb-1">
        <h6 class="modal-title" id="fotoModalLabel">Foto Presensi</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div id="fotoMasukContainer" class="mb-2"></div>
        <div id="fotoKeluarContainer"></div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
function changeDate(date) {
    window.location.href = '<?php echo e(route("mobile.monitor-presensi")); ?>?date=' + date;
}

function showFoto(masuk, keluar) {
    const masukContainer = document.getElementById('fotoMasukContainer');
    const keluarContainer = document.getElementById('fotoKeluarContainer');

    masukContainer.innerHTML = masuk && !masuk.includes('null')
        ? `<p class="fw-bold mb-1">Masuk:</p><img src="${masuk}" alt="Selfie Masuk">`
        : `<p class="text-muted">Tidak ada foto masuk.</p>`;

    keluarContainer.innerHTML = keluar && !keluar.includes('null')
        ? `<p class="fw-bold mt-2 mb-1">Keluar:</p><img src="${keluar}" alt="Selfie Keluar">`
        : `<p class="text-muted">Tidak ada foto keluar.</p>`;

    const modal = new bootstrap.Modal(document.getElementById('fotoModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/monitor-presensi.blade.php ENDPATH**/ ?>