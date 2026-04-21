<?php $__env->startSection('title', 'Profil Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="siswa-shell">
    <?php echo $__env->make('mobile.siswa.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('mobile.siswa.partials.header', ['title' => 'Profil Siswa', 'subtitle' => 'Informasi akun dan sekolah'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="section-card profile-card">
        <div class="siswa-avatar"><?php echo e(strtoupper(substr($studentUser->name ?? 'S', 0, 1))); ?></div>
        <h5 class="mb-1"><?php echo e($studentUser->name); ?></h5>
        <p class="text-soft mb-0"><?php echo e($studentUser->email); ?></p>
    </section>

    <section class="section-card">
        <div class="detail-grid">
            <div class="detail-box">
                <small>NIS</small>
                <strong><?php echo e($studentRecord->nis ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Role</small>
                <strong><?php echo e(ucfirst(str_replace('_', ' ', $studentUser->role ?? 'siswa'))); ?></strong>
            </div>
            <div class="detail-box">
                <small>Kelas</small>
                <strong><?php echo e($studentRecord->kelas ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Jurusan</small>
                <strong><?php echo e($studentRecord->jurusan ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>No. HP</small>
                <strong><?php echo e($studentRecord->no_hp ?? $studentUser->no_hp ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Madrasah</small>
                <strong><?php echo e($studentSchool->name ?? '-'); ?></strong>
            </div>
            <div class="detail-box">
                <small>Alamat</small>
                <strong><?php echo e($studentRecord->alamat ?? $studentUser->alamat ?? '-'); ?></strong>
            </div>
        </div>
    </section>

    <section class="section-card">
        <form id="siswaLogoutForm" action="<?php echo e(route('logout')); ?>" method="POST" style="text-align:center;">
            <?php echo csrf_field(); ?>
            <button id="siswaLogoutButton" type="button" class="ghost-btn" style="width:auto; padding:9px 14px; background: linear-gradient(135deg, #dc3545 0%, #a61e2f 100%); color:#fff; margin:0 auto; display:inline-flex; justify-content:center; align-items:center;">
                <i class="bx bx-log-out"></i>Logout
            </button>
        </form>
    </section>
</div>

<?php echo $__env->make('mobile.siswa.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var logoutButton = document.getElementById('siswaLogoutButton');
    var logoutForm = document.getElementById('siswaLogoutForm');

    if (!logoutButton || !logoutForm) return;

    logoutButton.addEventListener('click', function () {
        Swal.fire({
            title: 'Yakin ingin logout?',
            text: 'Sesi login Anda akan diakhiri.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#a61e2f',
            cancelButtonColor: '#6c757d'
        }).then(function (result) {
            if (result.isConfirmed) {
                logoutForm.submit();
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/profile.blade.php ENDPATH**/ ?>