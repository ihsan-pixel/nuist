<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Ringkasan Aktivitas'); ?>

<?php $__env->startSection('content'); ?>
<?php

date_default_timezone_set('Asia/Jakarta');

$b = time();
$hour = date('G', $b);

if ($hour >= 0 && $hour <= 11) {
    $congrat = 'Selamat Pagi';
} elseif ($hour >= 12 && $hour <= 14) {
    $congrat = 'Selamat Siang ';
} elseif ($hour >= 15 && $hour <= 17) {
    $congrat = 'Selamat Sore ';
} elseif ($hour >= 17 && $hour <= 18) {
    $congrat = 'Selamat Petang ';
} elseif ($hour >= 19 && $hour <= 23) {
    $congrat = 'Selamat Malam ';
}

// Calculate progress color from red to bright green based on percentage
$red = 255 - (int)($kinerjaPercent * 2.55);
$green = (int)($kinerjaPercent * 2.55);
$progressColor = "rgb($red, $green, 0)";
$avatarPath = Auth::user()->avatar ?? null;
$showAvatarImage = $avatarPath
    && !in_array($avatarPath, ['build/images/users/avatar-11.jpg', 'build/images/avatar-1.jpg'], true)
    && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatarPath);

?>
<header class="mobile-header d-md-none" style="position: sticky; top: 0; z-index: 1050;">
    <div class="container-fluid px-0 py-0" style="background: transparent; padding-top: 4px; padding-bottom: 4px;">
        <div class="mobile-topbar-row">
            <!-- User Avatar (Left) -->
            <div class="avatar-sm ms-2 me-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showAvatarImage): ?>
                    <img
                        src="<?php echo e(asset('storage/' . $avatarPath)); ?>"
                        class="avatar-img rounded-circle"
                        style="border: 1px solid #ffffff;"
                        alt="User"
                    >
                <?php else: ?>
                    <div class="avatar-fallback" style="border: 1px solid #ffffff;">
                        <i class="bx bx-user" style="font-size: 20px; color: white;"></i>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- School Name -->
            <div class="text-start flex-grow-1" style="margin-left: 2px; min-width: 0;">
                <small class="header-text fw-medium d-block" style="font-size: 11px; line-height: 1.2;">
                    <?php echo e(Auth::user()->madrasah?->name ?? 'Asal sekolah belum diatur'); ?>

                </small>
                <h6 class="header-text mb-0 fw-semibold" style="font-size: 14px; line-height: 1.2;"><?php echo e(Auth::user()->name); ?></h6>
            </div>

            <!-- Notification and Menu Buttons (Right) -->
            <div class="d-flex align-items-center flex-shrink-0 mobile-topbar-actions">
                <!-- Notification Bell -->
                <a href="<?php echo e(route('mobile.notifications')); ?>" class="btn btn-link text-decoration-none p-0 me-2 position-relative">
                    <i class="bx bx-bell header-icon" style="font-size: 22px;"></i>
                    <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 9px; padding: 2px 5px; top: -4px; right: -4px; display: none;">0</span>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded header-icon" style="font-size: 22px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item py-2" href="<?php echo e(route('mobile.notifications')); ?>"><i class="bx bx-bell me-2"></i>Notifikasi</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-home me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out me-2"></i>Logout
                            </a>
                        </li>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container py-3" style="max-width: 520px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #ffffff;
            position: relative;
            min-height: 100vh; /* 🔥 minimal tinggi layar */
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 34vh;
            pointer-events: none;
            z-index: -1;
            background:
                linear-gradient(135deg, rgba(4, 63, 49, 0.92) 0%, rgba(9, 83, 65, 0.86) 38%, rgba(251, 181, 36, 0.28) 72%, rgba(251, 181, 36, 0) 100%),
                linear-gradient(155deg, rgba(4, 63, 49, 0.20) 0 16%, transparent 16% 30%, rgba(251, 181, 36, 0.24) 30% 44%, transparent 44% 100%),
                linear-gradient(25deg, transparent 0 24%, rgba(4, 63, 49, 0.14) 24% 31%, transparent 31% 57%, rgba(251, 181, 36, 0.18) 57% 64%, transparent 64% 100%),
                linear-gradient(118deg, transparent 0 46%, rgba(4, 63, 49, 0.12) 46% 52%, transparent 52% 77%, rgba(251, 181, 36, 0.16) 77% 83%, transparent 83% 100%);
            opacity: 1;
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

        @media (max-width: 768px) {
            body {
                background-size: 100% auto;
                background-attachment: scroll; /* 🔥 hindari bug mobile */
            }
        }

        .dashboard-header {
            background: #f8f9fb url('<?php echo e(asset("images/qwe1.png")); ?>') no-repeat center center;
            background-size: cover;
            border-radius: 14px;
            padding: 12px;
            color: #004b4c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 16px;
        }

        .id-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .id-card-photo {
            width: 56px;
            height: 76px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            /* border: 2px solid rgba(255,255,255,0.4); */
        }

        .id-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .id-card-details {
            flex: 1;
        }

        .row-item {
            display: grid;
            grid-template-columns: 70px 10px 1fr;
            align-items: center;
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .row-item:last-child {
            margin-bottom: 0;
        }

        .label_text {
            color: #004b4c;
            text-align: left;
            font-size: 10px;
        }

        .label {
            color: #004b4c;
            text-align: left;
        }

        .colon {
            color: #004b4c;
            text-align: center;
        }

        .value {
            font-weight: 600;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #004b4c;
        }

        .badge-status {
            background: rgba(0,75,76,0.1);
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 10px;
            color: #004b4c;
            border: 1px solid rgba(0,75,76,0.2);
        }

        .id-card-title {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #004b4c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(0,75,76,0.3);
            padding-bottom: 4px;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
            background-color: transparent !important;
            -webkit-backdrop-filter: none !important;
            backdrop-filter: none !important;
            transition: background-color 0.25s ease, box-shadow 0.25s ease;
            min-height: 68px;
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .mobile-header.scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08) !important;
            min-height: 68px;
        }

        .mobile-header .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .mobile-topbar-row {
            width: 100%;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            column-gap: 4px;
        }

        .mobile-topbar-actions {
            justify-self: end;
            margin-left: auto;
        }

        .mobile-header .header-text,
        .mobile-header .header-icon {
            color: #ffffff !important;
            transition: color 0.25s ease;
        }

        .mobile-header.scrolled .header-text,
        .mobile-header.scrolled .header-icon {
            color: #000000 !important;
        }

        .name-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 0px;
            display: inline-block;
        }

        .stats-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .stat-item {
            text-align: center;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .stat-item .icon-container {
            margin-bottom: 4px;
        }

        .stat-item i {
            font-size: 18px;
        }

        .stat-item h6 {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .stat-item small {
            font-size: 9px;
            color: #6c757d;
        }

        .services-form {
            border-radius: 14px;
            padding: 4px 0 2px;
            margin-bottom: 12px;
            min-height: 50px;
        }

        .services-grid {
            display: flex;
            gap: 10px;
            text-align: center;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 4px 2px 8px;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x proximity;
            scrollbar-width: none;
        }

        .services-grid::-webkit-scrollbar {
            display: none;
        }

        .service-wrapper {
            text-align: center;
            min-width: 60px;
            flex: 0 0 60px;
            scroll-snap-align: start;
        }

        .service-item {
            position: relative;
            border-radius: 16px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
            height: 60px;
            width: 60px;
            box-sizing: border-box;
            border: 1px solid rgba(251, 181, 36, 0.14);
            box-shadow: 0 8px 18px rgba(251, 181, 36, 0.10);
            overflow: hidden;
            background: linear-gradient(135deg, #FBB524 0%, #e09f17 100%);
        }

        .service-item.icon-card {
            background: linear-gradient(135deg, #FBB524 0%, #e09f17 100%);
        }

        .service-item.icon-card i {
            font-size: 28px;
            color: #ffffff;
            position: relative;
            z-index: 1;
        }

        .service-item.menu-all-card {
            background: #ffffff;
            border: 1px solid rgba(251, 181, 36, 0.16);
            color: #FBB524;
        }

        .service-item.menu-all-card i {
            color: #FBB524;
            font-size: 32px;
        }

        .service-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .service-label {
            font-size: 7.5px;
            font-weight: 600;
            margin-top: 6px;
            color: #333;
            line-height: 1.25;
            min-height: 20px;
        }

        .services-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .services-see-all {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(4, 63, 49, 0.08);
            color: #043F31;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .services-see-all:hover {
            color: #043F31;
            background: rgba(4, 63, 49, 0.12);
        }

        .info-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .info-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-item small {
            color: #6c757d;
            font-size: 10px;
        }

        .info-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-section {
            background: linear-gradient(135deg, #043F31 0%, #095341 100%);
            border-radius: 16px;
            padding: 14px;
            border: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 12px;
            box-shadow: 0 10px 24px rgba(4, 63, 49, 0.14);
        }

        .schedule-section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .schedule-section-title {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
        }

        .schedule-section-subtitle {
            margin: 2px 0 0;
            font-size: 10px;
            color: rgba(255,255,255,0.82);
            line-height: 1.45;
        }

        .schedule-period-pill {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 8px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .schedule-list {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 2px 2px 10px;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x proximity;
        }

        .schedule-list::-webkit-scrollbar {
            display: none;
        }

        .schedule-row {
            flex: 0 0 260px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 12px;
            border-radius: 16px;
            background: rgba(255,255,255,0.96);
            border: 1px solid rgba(255,255,255,0.20);
            scroll-snap-align: start;
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        }

        .schedule-row.is-complete {
            border-left: 4px solid #16a34a;
        }

        .schedule-row.is-excused {
            border-left: 4px solid #f59e0b;
        }

        .schedule-row.is-pending {
            border-left: 4px solid #0f766e;
        }

        .schedule-row-marker {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 2px;
            flex-shrink: 0;
            background: #0f766e;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
        }

        .schedule-row.is-complete .schedule-row-marker {
            background: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.12);
        }

        .schedule-row.is-excused .schedule-row-marker {
            background: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12);
        }

        .schedule-row-body {
            min-width: 0;
            flex: 1;
        }

        .schedule-row-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .schedule-row-subject {
            font-size: 13px;
            font-weight: 700;
            color: #173a3a;
            line-height: 1.35;
            margin-bottom: 2px;
        }

        .schedule-row-class {
            font-size: 11px;
            color: #5a6770;
            line-height: 1.4;
        }

        .schedule-row-school {
            font-size: 11px;
            color: #5a6770;
            line-height: 1.4;
            margin-top: 2px;
        }

        .schedule-row-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .schedule-meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(4, 63, 49, 0.06);
            border: 1px solid rgba(4, 63, 49, 0.08);
            font-size: 10px;
            color: #36515b;
            line-height: 1;
        }

        .schedule-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        .schedule-status-badge.is-complete {
            background: rgba(22, 163, 74, 0.14);
            color: #166534;
        }

        .schedule-status-badge.is-excused {
            background: rgba(245, 158, 11, 0.16);
            color: #92400e;
        }

        .schedule-status-badge.is-pending {
            background: rgba(15, 118, 110, 0.14);
            color: #0f766e;
        }

        .schedule-empty-row {
            flex: 0 0 260px;
            background: rgba(255,255,255,0.96);
            border-radius: 16px;
            padding: 14px;
            border: 1px solid rgba(255,255,255,0.20);
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            scroll-snap-align: start;
        }

        .schedule-empty-row i {
            font-size: 24px;
            color: #095341;
            margin-bottom: 8px;
        }

        .schedule-empty-row p {
            margin: 0 0 4px;
            font-size: 12px;
            font-weight: 700;
            color: #173a3a;
        }

        .schedule-empty-row small {
            color: #5a6770;
            font-size: 10px;
            line-height: 1.4;
        }

        .schedule-see-all {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .schedule-see-all:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.20);
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .schedule-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .schedule-item strong {
            font-size: 11px;
            color: #333;
        }

        .schedule-item small {
            color: #6c757d;
            font-size: 10px;
        }

        /* Schedule Carousel Styles */
        .schedule-carousel {
            display: flex;
            overflow-x: auto;
            gap: 12px;
            padding: 0 12px;
            scrollbar-width: none; /* hide scrollbar for better mobile look */
            -ms-overflow-style: none; /* IE and Edge */
        }

        .schedule-carousel::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .schedule-card {
            flex: 0 0 60vw; /* 60% of viewport width */
            padding: 16px;
            background: linear-gradient(to bottom, #fdbd57, #f89a3c);
            border-radius: 8px;
            text-align: left;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .attendance-indicator {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .schedule-header strong {
            font-size: 14px;
            color: white;
            margin-bottom: 4px;
        }

        .schedule-header small {
            font-size: 12px;
            color: white;
        }

        .schedule-time {
            margin: 8px 0;
        }

        .schedule-time small {
            font-size: 11px;
            color: white;
        }

        .schedule-status .badge {
            font-size: 10px;
            padding: 4px 8px;
        }

        .quick-actions {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .quick-actions-header {
            background: #f8f9fa;
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .quick-actions-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .quick-actions-content {
            padding: 12px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border-radius: 8px;
            padding: 12px 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }

        .action-button:hover {
            background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
            color: white;
            transform: translateY(-1px);
        }

        .action-button i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .section-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }

        .no-schedule {
            text-align: center;
            padding: 16px;
            color: #999;
        }

        .no-schedule i {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }

        .no-schedule small {
            display: block;
            margin-top: 4px;
            font-size: 10px;
            color: #98a6ad;
        }

        /* Calendar Styles */
        .calendar-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 60px;
        }

        .calendar-container {
            width: 100%;
        }

        .calendar-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .calendar-title {
            font-weight: 600;
            font-size: 12px;
            color: #333;
            margin: 0;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-bottom: 8px;
        }

        .weekday-label {
            text-align: center;
            font-size: 9px;
            font-weight: 600;
            color: #666;
            padding: 4px 0;
            text-transform: uppercase;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .calendar-day {
            aspect-ratio: 1;
            border-radius: 6px;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
            min-height: 40px;
        }

        .calendar-day.empty {
            background: transparent;
            border: none;
        }

        .calendar-day.today {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border-color: #004b4c;
        }

        .calendar-day.has-presensi {
            border-width: 2px;
        }

        .calendar-day.status-hadir {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .calendar-day.status-izin {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        .calendar-day.status-alpha {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .calendar-day.today.status-hadir,
        .calendar-day.today.status-izin,
        .calendar-day.today.status-alpha {
            color: white;
        }

        .day-number {
            font-size: 11px;
            font-weight: 600;
            line-height: 1;
            margin-bottom: 1px;
        }

        .day-name {
            font-size: 8px;
            text-transform: uppercase;
            opacity: 0.8;
            line-height: 1;
        }

        .presensi-indicator {
            position: absolute;
            bottom: 2px;
            left: 2px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
        }

        .calendar-day.status-hadir .presensi-indicator {
            background: #28a745;
            color: white;
        }

        .calendar-day.status-izin .presensi-indicator {
            background: #ffc107;
            color: #856404;
        }

        .calendar-day.status-alpha .presensi-indicator {
            background: #dc3545;
            color: white;
        }

        .calendar-day.today .presensi-indicator {
            background: rgba(255, 255, 255, 0.9);
            color: #004b4c;
        }

        .calendar-day.holiday {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            color: #d63031;
            border-color: #fdcb6e;
        }

        .calendar-day.holiday.today {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
        }

        .holiday-indicator {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 10px;
            height: 10px;
            background: #e17055;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
            color: white;
            border: 1px solid #d63031;
        }

        .calendar-day.holiday .holiday-indicator {
            background: #e17055;
        }

        .calendar-day.holiday.today .holiday-indicator {
            background: rgba(255, 255, 255, 0.9);
            color: #004b4c;
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Month Navigation Button Styles */
        .month-nav-btn {
            background: transparent;
            border: none;
            color: #004b4c;
            font-size: 18px;
            padding: 5px;
            cursor: pointer;
            border-radius: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .month-nav-btn:hover {
            color: #0e8549;
        }

        .month-nav-btn:focus {
            outline: none;
        }

        /* Banner Modal Styles */
        .modal-content {
            border-radius: 15px;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            overflow: hidden;
            border-radius: 50%;
        }

        .avatar-sm .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;      /* 🔥 kunci anti gepeng */
            object-position: center;
            display: block;
        }

        .performance-card {
            background: linear-gradient(135deg, #043F31 0%, #095341 100%);
            border-radius: 14px;
            padding: 14px 14px 12px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,.15);
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .performance-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.2);
        }

        /* LEFT */
        .performance-left {
            flex: 1;
        }

        .performance-level {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 2px;
        }

        .level-badge {
            font-size: 9px;
            background: rgba(255,255,255,0.16);
            color: white;
            padding: 3px 8px;
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .performance-level strong {
            font-size: 11px;
            color: white;
            text-align: right;
        }

        /* TIMELINE */
        .timeline {
            display: none;
        }

        .timeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            color: #adb5bd;
            font-size: 9px;
            flex: 1;
        }

        .timeline-item i {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .timeline-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #dee2e6;
            margin-bottom: 4px;
        }

        .timeline-item.done {
            color: #095341;
        }

        .timeline-item.done .dot {
            background: #095341;
        }

        /* RIGHT */
        .performance-right {
            width: 460px;
            display: flex;
            justify-content: center;
        }

        .progress-bar {
            width: 100%;
            height: 7px;
            background: rgba(255,255,255,0.18);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: <?php echo e($kinerjaPercent); ?>%;
            background: #095341;
            border-radius: 999px;
            transition: width .4s ease;
        }

        .progress-text {
            text-align: center;
            min-width: 38px;
        }

        .progress-text strong {
            font-size: 13px;
            color: #ffffff;
        }

        .progress-text small {
            font-size: 9px;
            color: #ffffff;
        }

        .performance-progress {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
        }

        .progress-bar {
            flex: 1;
        }

        .timeline-accordion {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding-left: 22px;
        }

        .timeline-accordion::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.55), rgba(255,255,255,0.2));
            border-radius: 1px;
        }

        .timeline-item-accordion {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.96);
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .timeline-item-accordion:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .timeline-item-accordion.done {
            background: rgba(9, 83, 65, 0.12);
            border-color: rgba(9, 83, 65, 0.2);
            border-left: 4px solid #095341;
        }

        .timeline-item-accordion .timeline-icon {
            position: absolute;
            left: -20px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1;
            font-size: 12px;
        }

        .timeline-item-accordion.done .timeline-icon {
            background: #095341;
            color: white;
        }

        .timeline-item-accordion .timeline-content {
            flex: 1;
            min-width: 0;
        }

        .timeline-item-accordion .timeline-content strong {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #172a24;
            margin-bottom: 3px;
        }

        .timeline-item-accordion .timeline-content small {
            display: block;
            font-size: 9px;
            color: #66737a;
            line-height: 1.35;
        }

        .timeline-item-accordion.done .timeline-content strong {
            color: #095341;
        }

        .timeline-item-accordion.done .timeline-content small {
            color: #095341;
        }

        .timeline-item-accordion.excused {
            background: rgba(251, 181, 36, 0.12);
            border-color: rgba(251, 181, 36, 0.22);
            border-left: 4px solid #FBB524;
        }

        .timeline-item-accordion.excused .timeline-icon {
            background: #FBB524;
            color: white;
        }

        .timeline-item-accordion.excused .timeline-content strong,
        .timeline-item-accordion.excused .timeline-content small {
            color: #a16207;
        }

        .timeline-item-accordion .timeline-pill {
            display: inline-flex;
            align-items: center;
            margin-top: 4px;
            padding: 2px 7px;
            border-radius: 999px;
            background: #ffffff;
            color: #60717b;
            font-size: 8px;
            font-weight: 600;
            border: 1px solid #e6edf2;
        }

        .timeline-item-accordion.done .timeline-pill {
            background: #f6fff8;
            border-color: #bfe5c9;
            color: #095341;
        }

        .timeline-item-accordion.excused .timeline-pill {
            background: #f8fbff;
            border-color: #f5d27a;
            color: #a16207;
        }

    </style>

    <!-- Show banner modal on page load -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBanner): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeBannerModal'), {
                backdrop: 'static',
                keyboard: false
            });
            welcomeModal.show();

            // Auto hide after 3 seconds
            setTimeout(function() {
                welcomeModal.hide();
            }, 3000);
        });
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Header -->
    

    <!-- Banner Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bannerImage): ?>
    <div class="modal fade" id="welcomeBannerModal" tabindex="-1" aria-labelledby="welcomeBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="background: transparent;">
                <div class="modal-body p-0">
                    <div class="text-center">
                        <img src="<?php echo e($bannerImage); ?>" alt="Welcome Banner" class="img-fluid rounded" style="max-height: 60vh; width: auto;">
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-transparent">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    

    <div class="stats-form">
        <div class="performance-card">
            <div class="performance-level">
                <span class="level-badge">LEVEL HARI INI</span>
                <strong><?php echo e($kinerjaPercent >= 100 ? 'Teladan' : ($kinerjaPercent >= 80 ? 'Baik Sekali' : ($kinerjaPercent >= 50 ? 'Baik' : ($kinerjaPercent >= 10 ? 'Cukup Baik' : 'Belum Ada Progress')))); ?></strong>
            </div>

            <div class="performance-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="background: <?php echo e($progressColor); ?>"></div>
                </div>
                <div class="progress-text">
                    <strong><?php echo e($kinerjaPercent); ?>%</strong>
                </div>
            </div>

            <div class="text-center mt-2" style="font-size: 10px">
                <a href="#" class="text-light text-decoration-none" data-bs-toggle="collapse" data-bs-target="#performanceAccordion" aria-expanded="false" aria-controls="performanceAccordion" style="font-size: 10px; font-weight: 500;">
                    Lihat Detail <i class="bx bx-chevron-down" id="detailArrow"></i>
                </a>
            </div>
            <div class="collapse" id="performanceAccordion">
                <div class="accordion-content" style="background: rgba(255, 255, 255, 0); border-radius: 0 0 14px 14px; padding: 16px 14px 12px 14px; margin-top: 8px; border-top: 1px solid rgba(255,255,255,0.2);">
                    <h6 class="accordion-title" style="font-size: 10px; font-weight: 600; color: white; margin-bottom: 12px; text-align: center;">Detail Aktivitas Hari Ini</h6>
                    <div class="timeline-accordion">
                        <!-- Presensi Masuk -->
                        <div class="timeline-item-accordion <?php echo e($presensiMasukStatus === 'sudah' ? 'done' : ''); ?>">
                            <div class="timeline-icon">
                                <i class="bx bx-log-in"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Presensi Masuk</strong>
                                <small><?php echo e($presensiMasukStatus === 'sudah' ? 'Sudah dilakukan' : 'Belum dilakukan'); ?></small>
                                <span class="timeline-pill"><?php echo e($presensiMasukStatus === 'sudah' ? 'Aktivitas selesai' : 'Menunggu presensi'); ?></span>
                            </div>
                        </div>

                        <!-- Presensi Mengajar - tampilkan per jadwal -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($teachingSteps) > 0): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachingSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="timeline-item-accordion <?php echo e($step['status'] === 'completed' ? 'done' : ($step['status'] === 'excused' ? 'excused' : '')); ?>">
                                <div class="timeline-icon">
                                    <i class="bx bx-chalkboard"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong><?php echo e($step['label']); ?></strong>
                                    <small><?php echo e($step['status'] === 'completed' ? 'Sudah dilakukan' : ($step['status'] === 'excused' ? 'Izin disetujui' : 'Belum dilakukan')); ?></small>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($step['subtitle'])): ?>
                                        <span class="timeline-pill"><?php echo e($step['subtitle']); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Presensi Keluar -->
                        <div class="timeline-item-accordion <?php echo e($presensiKeluarStatus === 'sudah' ? 'done' : ''); ?>">
                            <div class="timeline-icon">
                                <i class="bx bx-log-out"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Presensi Keluar</strong>
                                <small><?php echo e($presensiKeluarStatus === 'sudah' ? 'Sudah dilakukan' : 'Belum dilakukan'); ?></small>
                                <span class="timeline-pill"><?php echo e($presensiKeluarStatus === 'sudah' ? 'Aktivitas selesai' : 'Menunggu presensi'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="text-center" style="margin-bottom: 10px; font-style: italic; font-size: 11px;">Aktivitas Presensi Bulan <?php echo e(\Carbon\Carbon::create($currentYear, $currentMonth, 1)->locale('id')->monthName); ?> <?php echo e($currentYear); ?></div>

        <div class="stats-grid">
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-check-circle text-success"></i>
                </div>
                <h6><?php echo e($kehadiranPercent); ?>%</h6>
                <small>Ketertiban</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-calendar text-primary"></i>
                </div>
                <h6><?php echo e($hadir); ?></h6>
                <small>Presensi</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-time text-warning"></i>
                </div>
                <h6><?php echo e($izin); ?></h6>
                <small>Izin</small>
            </div>
            <div class="stat-item">
                <div class="icon-container">
                    <i class="bx bx-x text-danger"></i>
                </div>
                <h6><?php echo e($alpha); ?></h6>
                <small>
                    <a href="<?php echo e(route('mobile.riwayat-presensi-alpha')); ?>" class="text-decoration-none text-danger" style="font-size: 9px;">Tidak Hadir</a>
                </small>
            </div>
        </div>
    </div>

    <div class="services-header">
        <small>Layanan</small>
        <a href="<?php echo e(url('/mobile/menu-layanan')); ?>" class="services-see-all" data-no-loader="true" onclick="event.preventDefault(); window.location.href='<?php echo e(url('/mobile/menu-layanan')); ?>'; return false;">See All</a>
    </div>

    <!-- Services Form -->
    <div class="services-form">
        <div class="services-grid" id="servicesGrid">
            <div class="service-wrapper">
                <a href="<?php echo e(route('mobile.presensi')); ?>" class="service-item icon-card">
                    <i class="bx bx-qr-scan"></i>
                </a>
                <div class="service-label">Presensi</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('mobile.teaching-attendances')); ?>" class="service-item icon-card">
                    <i class="bx bx-chalkboard"></i>
                </a>
                <div class="service-label">Mengajar</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('mobile.izin', ['type' => 'tidak_masuk'])); ?>" class="service-item icon-card">
                    <i class="bx bx-user-x"></i>
                </a>
                <div class="service-label">Izin</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(route('mobile.profile')); ?>" class="service-item icon-card">
                    <i class="bx bx-user"></i>
                </a>
                <div class="service-label">Profil</div>
            </div>
            <div class="service-wrapper">
                <a href="<?php echo e(url('/mobile/menu-layanan')); ?>" class="service-item menu-all-card" data-no-loader="true" onclick="event.preventDefault(); window.location.href='<?php echo e(url('/mobile/menu-layanan')); ?>'; return false;">
                    <i class="bx bx-grid-alt"></i>
                </a>
                <div class="service-label">See All</div>
            </div>
        </div>
    </div>

    <script>
        function togglePerformanceDetails() {
            const modal = new bootstrap.Modal(document.getElementById('performanceModal'));
            modal.show();
        }

        function prefetchMenuLayanan() {
            const href = <?php echo json_encode(url('/mobile/menu-layanan'), 15, 512) ?>;
            if (document.querySelector('link[data-prefetch-menu-layanan="true"]')) {
                return;
            }

            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.as = 'document';
            link.href = href;
            link.setAttribute('data-prefetch-menu-layanan', 'true');
            document.head.appendChild(link);
        }

        // Handle accordion toggle text change and icon rotation
        document.addEventListener('DOMContentLoaded', function() {
            const accordion = document.getElementById('performanceAccordion');
            const detailLink = document.querySelector('.performance-card a[data-bs-toggle="collapse"]');
            const detailIcon = document.getElementById('detailArrow');
            const header = document.querySelector('.mobile-header');
            const servicesSeeAll = document.querySelector('.services-see-all');
            const servicesSeeAllCard = document.querySelector('.menu-all-card');

            const syncHeaderState = function () {
                if (!header) {
                    return;
                }

                header.classList.toggle('scrolled', window.scrollY > 10);
            };

            if (accordion && detailLink && detailIcon) {
                accordion.addEventListener('show.bs.collapse', function() {
                    detailLink.innerHTML = 'Tutup Detail <i class="bx bx-chevron-up" id="detailArrow"></i>';
                });

                accordion.addEventListener('hide.bs.collapse', function() {
                    detailLink.innerHTML = 'Lihat Detail <i class="bx bx-chevron-down" id="detailArrow"></i>';
                });
            }

            syncHeaderState();
            window.addEventListener('scroll', syncHeaderState, { passive: true });

            if (servicesSeeAll) {
                servicesSeeAll.addEventListener('mouseenter', prefetchMenuLayanan, { once: true });
                servicesSeeAll.addEventListener('touchstart', prefetchMenuLayanan, { once: true, passive: true });
            }

            if (servicesSeeAllCard) {
                servicesSeeAllCard.addEventListener('mouseenter', prefetchMenuLayanan, { once: true });
                servicesSeeAllCard.addEventListener('touchstart', prefetchMenuLayanan, { once: true, passive: true });
            }
        });

        // Add click event listener to performance card
        document.addEventListener('DOMContentLoaded', function() {
            const performanceCard = document.querySelector('.performance-card');
            if (performanceCard) {
                performanceCard.addEventListener('click', togglePerformanceDetails);
            }
        });
    </script>

    <!-- Teacher Info -->
    

    <small>Jadwal Hari Ini</small>

    <div class="schedule-section">
        <div class="schedule-section-header">
            <div>
                <h6 class="schedule-section-title">Jadwal Mengajar Aktif Hari Ini</h6>
            </div>
            <a href="<?php echo e(route('mobile.jadwal')); ?>" class="schedule-see-all">See All</a>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($todaySchedulesWithAttendance->count() > 0): ?>
            <div class="schedule-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $todaySchedulesWithAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php
                        $statusClass = $schedule->attendance_status === 'sudah'
                            ? 'is-complete'
                            : ($schedule->attendance_status === 'izin' ? 'is-excused' : 'is-pending');
                    ?>
                    <div class="schedule-row <?php echo e($statusClass); ?>">
                        <div class="schedule-row-marker"></div>
                        <div class="schedule-row-body">
                            <div class="schedule-row-top">
                                <div>
                                    <div class="schedule-row-time">
                                        <i class="bx bx-time-five"></i>
                                        <span><?php echo e($schedule->time_range); ?></span>
                                    </div>
                                    <div class="schedule-row-subject"><?php echo e($schedule->subject ?: 'Mata pelajaran belum diisi'); ?></div>
                                    <div class="schedule-row-class"><?php echo e($schedule->class_label ?: 'Kelas belum diatur'); ?></div>
                                    <div class="schedule-row-school"><?php echo e($schedule->school_name ?? Auth::user()->madrasah?->name ?? 'Asal sekolah belum diatur'); ?></div>
                                </div>
                                <span class="schedule-status-badge <?php echo e($statusClass); ?>">
                                    <i class="bx <?php echo e($schedule->attendance_status === 'sudah' ? 'bx-check-circle' : ($schedule->attendance_status === 'izin' ? 'bx-info-circle' : 'bx-time-five')); ?>"></i>
                                    <?php echo e($schedule->attendance_status_label); ?>

                                </span>
                            </div>
                            <div class="schedule-row-meta">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($schedule->period)): ?>
                                    <span class="schedule-meta-chip">
                                        <i class="bx bx-calendar"></i>
                                        <?php echo e($schedule->period->summary_label); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="schedule-meta-chip">
                                        <i class="bx bx-buildings"></i>
                                        <?php echo e(Auth::user()->madrasah?->name ?? 'Asal sekolah belum diatur'); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="schedule-list">
                <div class="schedule-empty-row">
                    <i class="bx bx-calendar-x"></i>
                    <p>Tidak ada jadwal hari ini</p>
                    <small>
                        <?php echo e(!empty($activeTeachingPeriod) ? 'Periode aktif sudah tersedia, tetapi belum ada jadwal untuk hari ini.' : 'Belum ada periode jadwal mengajar yang aktif saat ini.'); ?>

                    </small>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <small>Kalender Presensi Bulan Ini</small>

    <!-- Calendar Section -->
    <div class="calendar-section">
        <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-title">
                <button class="month-nav-btn" onclick="navigateMonth(<?php echo e($prevYear); ?>, <?php echo e($prevMonth); ?>)">
                    <i class="bx bx-chevron-left"></i>
                </button>
                <span><?php echo e(\Carbon\Carbon::create($currentYear, $currentMonth, 1)->locale('id')->monthName); ?> <?php echo e($currentYear); ?></span>
                <button class="month-nav-btn" onclick="navigateMonth(<?php echo e($nextYear); ?>, <?php echo e($nextMonth); ?>)">
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="calendar-weekdays">
            <div class="weekday-label">Min</div>
            <div class="weekday-label">Sen</div>
            <div class="weekday-label">Sel</div>
            <div class="weekday-label">Rab</div>
            <div class="weekday-label">Kam</div>
            <div class="weekday-label">Jum</div>
            <div class="weekday-label">Sab</div>
        </div>
        <div class="calendar-grid">
                <?php
                    $daysInMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->daysInMonth;
                    $firstDayOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->dayOfWeek; // 0=Sunday, 6=Saturday
                    $today = \Carbon\Carbon::now()->day;
                    $currentMonthCheck = \Carbon\Carbon::now()->month;
                    $currentYearCheck = \Carbon\Carbon::now()->year;
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $firstDayOfMonth; $i++): ?>
                    <div class="calendar-day empty"></div>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($day = 1; $day <= $daysInMonth; $day++): ?>
                    <?php
                        $dateKey = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->toDateString();
                        $presensiStatus = $calendarStatuses[$dateKey] ?? null;
                        $isToday = ($currentMonth == $currentMonthCheck && $currentYear == $currentYearCheck && $day == $today);
                        $dayName = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->locale('id')->dayName;
                        $shortDayName = substr($dayName, 0, 3);
                        $isHoliday = isset($monthlyHolidays[$dateKey]);

                        // Jika hari libur, jangan tampilkan status presensi
                        if ($isHoliday) {
                            $presensiStatus = null;
                        }
                    ?>

                    <div class="calendar-day <?php echo e($isToday ? 'today' : ''); ?> <?php echo e(($presensiStatus && !$isHoliday) ? 'status-' . $presensiStatus : ''); ?> <?php echo e(($presensiStatus && !$isHoliday) ? 'has-presensi' : ''); ?> <?php echo e($isHoliday ? 'holiday' : ''); ?>">
                        <div class="day-number"><?php echo e($day); ?></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isHoliday): ?>
                            <div class="holiday-indicator">
                                <i class="bx bx-star"></i>
                            </div>
                        <?php elseif($presensiStatus): ?>
                            <div class="presensi-indicator">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($presensiStatus == 'hadir'): ?>
                                    <i class="bx bx-check"></i>
                                <?php elseif($presensiStatus == 'izin'): ?>
                                    <i class="bx bx-time-five"></i>
                                <?php elseif($presensiStatus == 'alpha'): ?>
                                    <i class="bx bx-x"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php
                    $totalCells = $firstDayOfMonth + $daysInMonth;
                    $emptyAtEnd = (7 - ($totalCells % 7)) % 7;
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $emptyAtEnd; $i++): ?>
                    <div class="calendar-day empty"></div>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($monthlyHolidays) > 0): ?>
            <div class="holiday-list">
                <small style="color: #666; font-weight: 500;">Hari Libur Nasional:</small>
                <div style="margin-top: 4px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $monthlyHolidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <small style="display: block; color: #d63031; margin-bottom: 2px;">
                            • <?php echo e(\Carbon\Carbon::parse($date)->locale('id')->format('d F Y')); ?> - <?php echo e($name); ?>

                        </small>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Performance Details Modal -->
    

    <style>
        .timeline-modal {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .timeline-item-modal {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.2s ease;
        }

        .timeline-item-modal.done {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .timeline-item-modal.done .timeline-icon {
            background: #28a745;
            color: white;
        }

        .timeline-content strong {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 2px;
        }

        .timeline-content small {
            font-size: 12px;
            color: #6c757d;
        }

        .timeline-item-modal.done .timeline-content strong,
        .timeline-item-modal.done .timeline-content small {
            color: #155724;
        }
    </style>
</div>
<?php $__env->stopSection(); ?>

<script>
function navigateMonth(year, month) {
    // Show loading state
    const calendarContainer = document.querySelector('.calendar-container');
    const originalContent = calendarContainer.innerHTML;
    calendarContainer.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="bx bx-loader-alt bx-spin" style="font-size: 24px;"></i><br><small>Loading...</small></div>';

    // Fetch new calendar data via AJAX
    fetch(`<?php echo e(route('mobile.dashboard.calendar-data')); ?>?year=${year}&month=${month}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('year', year);
        url.searchParams.set('month', month);
        window.history.pushState({}, '', url);

        // Re-render calendar with new data
        renderCalendar(data);

        // Update stats data for the new month
        updateStatsData(year, month);

        // Update month name in the stats header
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        document.querySelector('.name-form').textContent = `Aktivitas Presensi Bulan ${monthNames[month - 1]} ${year}`;
    })
    .catch(error => {
        console.error('Error loading calendar data:', error);
        // Restore original content on error
        calendarContainer.innerHTML = originalContent;
        alert('Gagal memuat data kalender. Silakan coba lagi.');
    });
}

function updateStatsData(year, month) {
    // Fetch new stats data via AJAX
    fetch(`<?php echo e(route('mobile.dashboard.stats-data')); ?>?year=${year}&month=${month}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update stats display
        document.querySelector('.stat-item:nth-child(1) h6').textContent = data.kehadiranPercent + '%';
        document.querySelector('.stat-item:nth-child(2) h6').textContent = data.hadir;
        document.querySelector('.stat-item:nth-child(3) h6').textContent = data.izin;
        document.querySelector('.stat-item:nth-child(4) h6').textContent = data.alpha;
    })
    .catch(error => {
        console.error('Error loading stats data:', error);
    });
}

function renderCalendar(data) {
    const calendarContainer = document.querySelector('.calendar-container');

    // Calculate calendar grid
    const daysInMonth = new Date(data.currentYear, data.currentMonth, 0).getDate();
    const firstDayOfMonth = new Date(data.currentYear, data.currentMonth - 1, 1).getDay();
    const today = new Date();
    const isCurrentMonth = today.getMonth() + 1 === data.currentMonth && today.getFullYear() === data.currentYear;
    const currentDay = today.getDate();

    // Indonesian month names
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Day names
    const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    let html = `
        <div class="calendar-header">
            <div class="calendar-title">
                <button class="month-nav-btn" onclick="navigateMonth(${data.prevYear}, ${data.prevMonth})">
                    <i class="bx bx-chevron-left"></i>
                </button>
                <span>${monthNames[data.currentMonth - 1]} ${data.currentYear}</span>
                <button class="month-nav-btn" onclick="navigateMonth(${data.nextYear}, ${data.nextMonth})">
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="calendar-weekdays">
    `;

    // Weekday headers
    dayNames.forEach(day => {
        html += `<div class="weekday-label">${day}</div>`;
    });

    html += `</div><div class="calendar-grid">`;

    // Empty cells for days before first day of month
    for (let i = 0; i < firstDayOfMonth; i++) {
        html += `<div class="calendar-day empty"></div>`;
    }

    // Calendar days
    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = `${data.currentYear}-${String(data.currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        let presensiStatus = data.calendarStatuses[dateKey] || null;
        const isHoliday = data.monthlyHolidays[dateKey] !== undefined;
        const isToday = isCurrentMonth && day === currentDay;

        let statusClass = '';
        if (presensiStatus) {
            statusClass = `status-${presensiStatus}`;
        } else if (isHoliday) {
            statusClass = 'holiday';
        }

        const hasPresensi = presensiStatus ? 'has-presensi' : '';

        html += `
            <div class="calendar-day ${isToday ? 'today' : ''} ${statusClass} ${hasPresensi} ${isHoliday ? 'holiday' : ''}">
                <div class="day-number">${day}</div>
        `;

        if (isHoliday) {
            html += `<div class="holiday-indicator"><i class="bx bx-star"></i></div>`;
        } else if (presensiStatus) {
            let icon = '';
            if (presensiStatus === 'hadir') icon = 'bx-check';
            else if (presensiStatus === 'izin') icon = 'bx-time-five';
            else if (presensiStatus === 'alpha') icon = 'bx-x';

            html += `<div class="presensi-indicator"><i class="bx ${icon}"></i></div>`;
        }

        html += `</div>`;
    }

    html += `</div>`;

    // Holiday list
    if (Object.keys(data.monthlyHolidays).length > 0) {
        html += `<div class="holiday-list"><small style="color: #666; font-weight: 500;">Hari Libur Nasional:</small><div style="margin-top: 4px;">`;

        Object.entries(data.monthlyHolidays).forEach(([date, name]) => {
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            html += `<small style="display: block; color: #d63031; margin-bottom: 2px;">• ${formattedDate} - ${name}</small>`;
        });

        html += `</div></div>`;
    }

    html += `</div>`;

    calendarContainer.innerHTML = html;
}

// Handle accordion arrow toggle
document.addEventListener('DOMContentLoaded', function() {
    const accordion = document.getElementById('performanceAccordion');
    const arrowIcon = document.getElementById('detailArrow');

    if (accordion && arrowIcon) {
        // Listen to Bootstrap collapse events
        accordion.addEventListener('show.bs.collapse', function() {
            arrowIcon.className = 'bx bx-chevron-up';
        });

        accordion.addEventListener('hide.bs.collapse', function() {
            arrowIcon.className = 'bx bx-chevron-down';
        });
    }
});
</script>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/dashboard.blade.php ENDPATH**/ ?>