@extends('layouts.mobile')

@section('title', 'Notifikasi')
@section('subtitle', 'Pesan & Pengingat')

@section('content')
<div class="container py-2" style="max-width: 420px; margin: auto;">
    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3">
        <button onclick="window.history.back()" class="btn btn-outline-secondary btn-sm me-2" style="border-radius: 6px; padding: 6px 10px;">
            <i class="bx bx-arrow-back"></i>
        </button>
        <h6 class="mb-0 fw-bold">Kembali</h6>
    </div>

    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
        }

        .notification-item {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            border-left: 3px solid #556ee6;
            position: relative;
        }

        .notification-item.unread {
            border-left-color: #0e8549;
            background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
        }

        .notification-item.read {
            opacity: 0.7;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }

        .notification-title {
            font-weight: 600;
            font-size: 12px;
            color: #333;
            margin: 0;
        }

        .notification-time {
            font-size: 10px;
            color: #999;
            margin: 0;
        }

        .notification-message {
            font-size: 11px;
            color: #666;
            line-height: 1.3;
            margin: 0;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
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
            background: none;
            border: none;
            color: #999;
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 4px;
            cursor: pointer;
        }

        .mark-read-btn:hover {
            background: #f8f9fa;
        }

        .empty-state {
            text-align: center;
            padding: 30px 15px;
            color: #999;
        }

        .empty-state i {
            font-size: 36px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 12px;
            margin: 0;
        }

        .mark-all-read {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 500;
            width: 100%;
            margin-bottom: 12px;
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
            color: #0e8549;
        }
    </style>

    <!-- Mark All as Read Button -->
    @if($notifications->count() > 0)
    <button id="markAllReadBtn" class="mark-all-read">
        <i class="bx bx-check-double me-2"></i>Tandai Semua Sudah Dibaca
    </button>
    @endif

    <!-- Notifications List -->
    <div id="notificationsContainer">
        @forelse($notifications as $notification)
        <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}"
             data-id="{{ $notification->id }}">
            <div class="d-flex">
                <div class="notification-icon {{ $notification->type === 'presensi_reminder' ? 'warning' : ($notification->type === 'presensi_success' ? 'presensi' : ($notification->type === 'teaching_success' ? 'teaching' : ($notification->type === 'izin_submitted' || $notification->type === 'izin_approved' || $notification->type === 'izin_rejected' ? 'izin' : 'presensi'))) }}">
                    <i class="{{ $notification->type === 'presensi_reminder' ? 'bx bx-time-five' : ($notification->type === 'presensi_success' ? 'bx bx-check-circle' : ($notification->type === 'teaching_success' ? 'bx bx-chalkboard' : ($notification->type === 'izin_submitted' ? 'bx bx-file' : ($notification->type === 'izin_approved' ? 'bx bx-check' : ($notification->type === 'izin_rejected' ? 'bx bx-x' : 'bx bx-bell'))))) }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <h6 class="notification-title">{{ $notification->title }}</h6>
                        <small class="notification-time">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>
                    @if(!$notification->is_read)
                    <button class="mark-read-btn mt-2" onclick="markAsRead({{ $notification->id }})">
                        <i class="bx bx-check"></i> Tandai Dibaca
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="bx bx-bell-off"></i>
            <p>Belum ada notifikasi</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection

@section('script')
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
@endsection
