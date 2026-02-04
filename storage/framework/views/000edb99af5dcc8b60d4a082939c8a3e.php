<?php $__env->startSection('title', 'Profil'); ?>
<?php $__env->startSection('subtitle', 'Profil Pengurus'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
        }

        .profile-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 16px;
            padding: 20px 16px;
            box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
            margin-bottom: 16px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
        }

        .profile-avatar i {
            font-size: 36px;
            color: white;
        }

        .profile-header h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
            text-align: center;
        }

        .profile-header p {
            font-size: 12px;
            opacity: 0.9;
            text-align: center;
            margin: 0;
        }

        .info-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 12px;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 12px;
        }

        .info-card-header i {
            font-size: 20px;
            color: #004b4c;
            margin-right: 10px;
        }

        .info-card-header h6 {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .info-value {
            font-size: 12px;
            color: #333;
            font-weight: 600;
            text-align: right;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-action:last-child {
            margin-bottom: 0;
        }

        .btn-action i {
            font-size: 18px;
            margin-right: 8px;
        }

        .btn-password {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
        }

        .btn-password:hover {
            background: linear-gradient(135deg, #003838 0%, #0a6b3d 100%);
            color: white;
        }

        .btn-pwa {
            background: linear-gradient(135deg, #fdbd57 0%, #f89a3c 100%);
            color: white;
        }

        .btn-pwa:hover {
            background: linear-gradient(135deg, #e67e22 0%, #f89a3c 100%);
            color: white;
        }

        .btn-logout {
            background: #fff;
            color: #dc3545;
            border: 1px solid #dc3545 !important;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: white !important;
        }

        .btn-pwa-installed {
            background: #28a745 !important;
            color: white !important;
            pointer-events: none;
            opacity: 0.7;
        }

        .alert-custom {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.2);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 11px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .alert-custom i {
            margin-right: 8px;
            font-size: 16px;
        }

        <?php if(session('success')): ?>
        .alert-success-custom {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 11px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .alert-success-custom i {
            margin-right: 8px;
            font-size: 16px;
        }
        <?php endif; ?>

        <?php if(session('error')): ?>
        .alert-error-custom {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 11px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .alert-error-custom i {
            margin-right: 8px;
            font-size: 16px;
        }
        <?php endif; ?>
    </style>

    <!-- Header -->
    <div class="text-center mb-4">
        <h5 class="fw-bold text-dark mb-1" style="font-size: 18px;">Profil</h5>
        <small class="text-muted" style="font-size: 12px;">Profil Pengurus</small>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert-success-custom">
        <i class="bx bx-check-circle"></i><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert-error-custom">
        <i class="bx bx-error-circle"></i><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="bx bx-user"></i>
        </div>
        <h4><?php echo e(Auth::user()->name); ?></h4>
        <p><?php echo e(Auth::user()->email); ?></p>
    </div>

    <!-- Informasi Akun -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-id-card"></i>
            <h6>Informasi Akun</h6>
        </div>
        <div class="info-item">
            <span class="info-label">Nama</span>
            <span class="info-value"><?php echo e(Auth::user()->name); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Email</span>
            <span class="info-value"><?php echo e(Auth::user()->email); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Role</span>
            <span class="info-value"><?php echo e(ucfirst(Auth::user()->role)); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Status Password</span>
            <span class="info-value">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->password_changed): ?>
                <span style="color: #28a745;">Sudah diubah</span>
                <?php else: ?>
                <span style="color: #ffc107;">Default</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </span>
        </div>
    </div>

    <!-- Pengaturan -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-cog"></i>
            <h6>Pengaturan</h6>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!Auth::user()->password_changed): ?>
        <div class="alert-custom">
            <i class="bx bx-info-circle"></i>
            <span>Password Anda masih default. Silakan ubah untuk keamanan akun.</span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <a href="<?php echo e(route('mobile.pengurus.ubah-password')); ?>" class="btn-action btn-password">
            <i class="bx bx-key"></i>
            Update Password
        </a>

        <button type="button" id="install-pwa-btn" class="btn-action btn-pwa">
            <i class="bx bx-download"></i>
            Install PWA
        </button>
    </div>

    <!-- Logout -->
    <div class="info-card">
        <button type="button" class="btn-action btn-logout" onclick="logoutConfirm()">
            <i class="bx bx-log-out"></i>
            Keluar dari Aplikasi
        </button>
    </div>

    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>
</div>

<!-- PWA Install Script -->
<script>
let deferredPrompt = null;
const installBtn = document.getElementById('install-pwa-btn');

function isInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;
}

function updateInstalledUI() {
    installBtn.innerHTML = '<i class="bx bx-check"></i> PWA Terinstall';
    installBtn.classList.remove('btn-pwa');
    installBtn.classList.add('btn-pwa-installed');
}

document.addEventListener('DOMContentLoaded', () => {
    if (isInstalled()) {
        updateInstalledUI();
    }
});

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
});

installBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    if (!deferredPrompt) {
        Swal.fire({
            title: 'Info',
            text: 'Aplikasi tidak bisa diinstall pada perangkat ini.',
            icon: 'info',
            confirmButtonColor: '#004b4c'
        });
        return;
    }

    deferredPrompt.prompt();
    const choice = await deferredPrompt.userChoice;

    if (choice.outcome === 'accepted') {
        updateInstalledUI();
    }

    deferredPrompt = null;
});

window.addEventListener('appinstalled', () => {
    updateInstalledUI();
});

// Logout confirmation
function logoutConfirm() {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar dari aplikasi?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#004b4c',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mobile-pengurus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/profile.blade.php ENDPATH**/ ?>