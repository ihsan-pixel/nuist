<?php $__env->startSection('title', 'Barcode Identitas'); ?>

<?php $__env->startSection('content'); ?>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0e8549, #004b4c);
        min-height: 100vh;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    .id-card {
        max-width: 380px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        overflow: hidden;
        position: relative;
    }

    .id-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 120px;
        background: linear-gradient(135deg, #ff9a56 0%, #ff6b35 100%);
        z-index: 1;
    }

    .card-header {
        position: relative;
        z-index: 2;
        padding: 30px 24px 20px;
        text-align: center;
        color: white;
    }

    .logo-section {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        backdrop-filter: blur(10px);
    }

    .card-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-subtitle {
        font-size: 14px;
        opacity: 0.9;
        margin: 4px 0 0 0;
        font-weight: 400;
    }

    .card-body {
        padding: 24px;
        position: relative;
    }

    .user-profile {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f2f5;
    }

    .user-avatar {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #ff9a56;
        margin-right: 18px;
        box-shadow: 0 8px 25px rgba(255,154,86,0.3);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff9a56, #ff6b35);
        z-index: -1;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 6px;
        line-height: 1.2;
    }

    .user-role {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 10px;
        text-transform: capitalize;
        font-weight: 500;
        background: #f8f9fa;
        padding: 4px 10px;
        border-radius: 12px;
        display: inline-block;
    }

    .user-id-section {
        background: linear-gradient(135deg, #ff9a56, #ff6b35);
        color: white;
        padding: 12px 16px;
        border-radius: 12px;
        text-align: center;
    }

    .user-id-label {
        font-size: 11px;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .user-id {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 1px;
        font-family: 'Courier New', monospace;
    }

    .qr-section {
        text-align: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e8ecf1 100%);
        padding: 28px 24px;
        border-radius: 16px;
        margin-top: 20px;
        border: 2px solid #e1e8ed;
        position: relative;
    }

    .qr-section::before {
        content: '';
        position: absolute;
        top: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #ff9a56, #ff6b35);
        border-radius: 2px;
    }

    .qr-title {
        font-size: 16px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .qr-container {
        background: white;
        padding: 18px;
        border-radius: 12px;
        display: inline-block;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border: 2px solid #e1e8ed;
        position: relative;
    }

    .qr-container::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #ff9a56, #ff6b35);
        border-radius: 12px;
        z-index: -1;
        opacity: 0.1;
    }

    .qr-image {
        width: 100px;
        height: 100px;
        display: block;
        border-radius: 8px;
    }

    .qr-text {
        font-size: 12px;
        color: #6c757d;
        margin-top: 14px;
        font-weight: 600;
        letter-spacing: 1px;
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 8px;
        display: inline-block;
    }

    .print-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        border-radius: 50%;
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(79,172,254,0.4);
        z-index: 1000;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .print-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(79,172,254,0.5);
    }

    .print-btn:active {
        transform: scale(0.95);
    }

    .print-btn i {
        font-size: 22px;
    }

    @media print {
        body {
            background: white !important;
            padding: 0 !important;
        }

        .id-card {
            box-shadow: none !important;
            border: 3px solid #000 !important;
            max-width: none !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }

        .id-card::before {
            display: none !important;
        }

        .print-btn {
            display: none !important;
        }

        .card-header {
            background: #ff9a56 !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .qr-section {
            background: white !important;
            border: 2px solid #000 !important;
        }

        .qr-container {
            border: 2px solid #000 !important;
        }

        .user-avatar {
            border: 3px solid #000 !important;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 15px;
        }

        .id-card {
            border-radius: 16px;
        }

        .card-header {
            padding: 24px 20px 16px;
        }

        .card-body {
            padding: 20px;
        }

        .user-avatar {
            width: 75px;
            height: 75px;
        }

        .user-name {
            font-size: 20px;
        }

        .qr-image {
            width: 160px;
            height: 160px;
        }

        .qr-container {
            padding: 16px;
        }
    }

    /* Animation for card entrance */
    @keyframes cardSlideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .id-card {
        animation: cardSlideIn 0.6s ease-out;
    }
</style>

<div class="id-card">
    <!-- Card Header -->
    <div class="card-header">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="bx bx-qr-scan" style="font-size: 20px; color: white;"></i>
            </div>
        </div>
        <h1 class="card-title">Digital ID Card</h1>
        
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <!-- User Profile -->
        <div class="user-profile">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentUser->avatar): ?>
                <img src="<?php echo e(asset('storage/app/public/' . $currentUser->avatar)); ?>"
                     alt="Avatar" class="user-avatar">
            <?php else: ?>
                <div class="user-avatar user-avatar-icon">
                    <i class="bx bx-user" style="font-size: 40px; color: #ff9a56;"></i>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="user-details">
                <div class="user-name"><?php echo e($currentUser->name); ?></div>
                
                <div class="user-id-section">
                    <div class="user-id-label">Nuist_ID</div>
                    <div class="user-id"><?php echo e($currentUser->nuist_id); ?></div>
                </div>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-title">Scan QR Code</div>
            <div class="qr-container">
                <img id="qris-current-user" src="<?php echo e($qrCodeDataUri); ?>"
                     alt="QR Code Identitas"
                     class="qr-image"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                
            </div>
            
        </div>
    </div>
</div>

<!-- Print Button -->


<div style="height: 100px;"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state for print
    const printBtn = document.querySelector('.print-btn');
    const originalPrintHtml = printBtn.innerHTML;

    printBtn.addEventListener('click', function() {
        this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
        this.disabled = true;

        setTimeout(() => {
            this.innerHTML = originalPrintHtml;
            this.disabled = false;
        }, 2000);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile-pengurus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/barcode.blade.php ENDPATH**/ ?>