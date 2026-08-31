<?php $__env->startSection('title', 'Notifikasi'); ?>
<?php $__env->startSection('subtitle', 'Pesan & Pengingat'); ?>

<?php $__env->startSection('content'); ?>
<div class="notifications-page">
    <style>
        body {
            background: #f4f7f5;
            font-family: 'Poppins', sans-serif;
        }

        .notifications-page {
            max-width: 420px;
            margin: 0 auto;
            padding: 6px 2px 0;
        }

        .notification-topbar {
            display: grid;
            grid-template-columns: 40px 1fr auto;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding: 0 2px;
        }

        .notification-back {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid rgba(4, 63, 49, 0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #043F31;
            text-decoration: none;
            background: #fff;
            box-shadow: 0 3px 10px rgba(4, 63, 49, 0.04);
        }

        .notification-back i {
            font-size: 16px;
        }

        .notification-title {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.2;
            color: #17312c;
            text-align: center;
            justify-self: center;
        }

        .notification-topbar-spacer {
            width: 1px;
            height: 34px;
        }

        .notification-subtitle {
            display: none;
        }

        .notification-toolbar {
            display: none;
        }

        .notification-item {
            background: #fff;
            border-radius: 12px;
            padding: 9px 10px;
            margin-bottom: 7px;
            box-shadow: 0 3px 10px rgba(4, 63, 49, 0.04);
            border: 1px solid rgba(4, 63, 49, 0.04);
            position: relative;
            overflow: hidden;
        }

        .notification-item.unread {
            border-color: rgba(4, 63, 49, 0.12);
            background: linear-gradient(135deg, #f7fffb 0%, #ffffff 52%);
        }

        .notification-item.read {
            opacity: 0.9;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
            gap: 8px;
        }

        .notification-title {
            font-weight: 600;
            font-size: 0.74rem;
            color: #17312c;
            margin: 0;
        }

        .notification-time {
            font-size: 0.62rem;
            color: #7a8a8f;
            margin: 0;
            white-space: nowrap;
        }

        .notification-message {
            font-size: 0.68rem;
            color: #5e6d72;
            line-height: 1.34;
            margin: 0;
        }

        .notification-icon {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 7px;
            flex-shrink: 0;
        }

        .notification-icon.presensi {
            background: linear-gradient(135deg, #556ee6 0%, #764ba2 100%);
            color: white;
        }

        .notification-icon.teaching {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
        }

        .notification-icon.izin {
            background: linear-gradient(135deg, #fd7e14 0%, #e8680d 100%);
            color: white;
        }

        .notification-icon.warning {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .notification-content {
            flex: 1;
        }

        .mark-read-btn {
            background: transparent;
            border: none;
            color: #095341;
            font-size: 0.66rem;
            padding: 4px 0 0;
            border-radius: 0;
            cursor: pointer;
            font-weight: 600;
        }

        .mark-read-btn:hover {
            background: transparent;
        }

        .empty-state {
            text-align: center;
            padding: 22px 14px;
            color: #7a8a8f;
            background: #fff;
            border-radius: 14px;
            border: 1px dashed rgba(4, 63, 49, 0.12);
        }

        .empty-state i {
            font-size: 36px;
            margin-bottom: 10px;
            opacity: 0.65;
            color: #095341;
        }

        .empty-state p {
            font-size: 0.74rem;
            margin: 0;
        }

        .mark-all-read {
            background: linear-gradient(135deg, #043F31 0%, #095341 100%);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            min-width: 30px;
            min-height: 30px;
            max-width: 30px;
            max-height: 30px;
            padding: 0;
            font-size: 0;
            font-weight: 500;
            margin-bottom: 0;
            box-shadow: 0 8px 18px rgba(4, 63, 49, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 30px;
            aspect-ratio: 1 / 1;
            line-height: 1;
            overflow: hidden;
        }

        .mark-all-read i {
            font-size: 15px;
            line-height: 1;
            margin: 0 !important;
            display: block;
        }

        .mark-all-read:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading {
            text-align: center;
            padding: 15px;
        }

        .loading i {
            font-size: 18px;
            color: #095341;
        }

        @media (max-width: 420px) {
            .notifications-page {
                padding-inline: 2px;
            }

            .notification-hero {
                border-radius: 16px;
                padding: 11px 11px 10px;
            }

            .notification-item {
                border-radius: 13px;
                padding: 9px 10px;
            }
        }
    </style>

    <div class="notification-topbar">
        <a href="javascript:void(0)" onclick="window.history.back()" class="notification-back" aria-label="Kembali">
                <i class="bx bx-arrow-back"></i>
            </a>
        <h1 class="notification-title">Notifikasi</h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notifications->count() > 0): ?>
            <button id="markAllReadBtn" class="mark-all-read" style="width:auto; margin:0;">
                <i class="bx bx-check-double"></i>
            </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="notification-topbar-spacer" aria-hidden="true"></div>
    </div>

    <!-- Notifications List -->
    <div id="notificationsContainer">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div class="notification-item <?php echo e($notification->is_read ? 'read' : 'unread'); ?>"
             data-id="<?php echo e($notification->id); ?>">
            <div class="d-flex">
                <div class="notification-icon <?php echo e($notification->type === 'presensi_reminder' ? 'warning' : ($notification->type === 'presensi_success' ? 'presensi' : ($notification->type === 'teaching_success' ? 'teaching' : ($notification->type === 'izin_submitted' || $notification->type === 'izin_approved' || $notification->type === 'izin_rejected' ? 'izin' : 'presensi')))); ?>">
                    <i class="<?php echo e($notification->type === 'presensi_reminder' ? 'bx bx-time-five' : ($notification->type === 'presensi_success' ? 'bx bx-check-circle' : ($notification->type === 'teaching_success' ? 'bx bx-chalkboard' : ($notification->type === 'izin_submitted' ? 'bx bx-file' : ($notification->type === 'izin_approved' ? 'bx bx-check' : ($notification->type === 'izin_rejected' ? 'bx bx-x' : 'bx bx-bell')))))); ?>"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <h6 class="notification-title"><?php echo e($notification->title); ?></h6>
                        <small class="notification-time"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                    </div>
                    <p class="notification-message"><?php echo e(\Illuminate\Support\Str::limit($notification->message, 72)); ?></p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->is_read): ?>
                    <button class="mark-read-btn mt-2" onclick="markAsRead(<?php echo e($notification->id); ?>)">
                        <i class="bx bx-check"></i> Tandai Dibaca
                    </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="empty-state">
            <i class="bx bx-bell-off"></i>
            <p>Belum ada notifikasi</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notifications->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($notifications->links()); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
function markAsRead(notificationId) {
    fetch(`/mobile/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-id="${notificationId}"]`);
            item.classList.remove('unread');
            item.classList.add('read');
            const markBtn = item.querySelector('.mark-read-btn');
            if (markBtn) markBtn.remove();

            // Update badge count
            updateNotificationBadge();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    const btn = document.getElementById('markAllReadBtn');
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Menyimpan...';
    btn.disabled = true;

    fetch('/mobile/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                item.classList.add('read');
                const markBtn = item.querySelector('.mark-read-btn');
                if (markBtn) markBtn.remove();
            });

            btn.style.display = 'none';
            updateNotificationBadge();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.innerHTML = '<i class="bx bx-check-double me-2"></i>Tandai Semua Sudah Dibaca';
        btn.disabled = false;
    });
}

function updateNotificationBadge() {
    // This will be called to update the header badge
    if (window.parent && window.parent.updateNotificationBadge) {
        window.parent.updateNotificationBadge();
    }
}

// Event listeners
document.getElementById('markAllReadBtn')?.addEventListener('click', markAllAsRead);

// Auto-refresh notifications every 30 seconds
setInterval(() => {
    // Optional: implement auto-refresh if needed
}, 30000);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/notifications.blade.php ENDPATH**/ ?>