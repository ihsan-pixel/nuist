<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        img { width: 120px; height: auto; border-radius: 5px; }
    </style>
</head>
<body>

<h3>Rekap Presensi Bulan: <?php echo e($bulan); ?></h3>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Nama Guru</th>
            <th>Status Kepegawaian</th>
            
            
            <th>Status Presensi</th>
            <th>Masuk</th>
            <th>Foto Masuk</th>
            <th>Keluar</th>
            <th>Foto Keluar</th>
            <th>Keterangan</th>
        </tr>
    </thead>

    <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($item['tanggal']); ?></td>
            <td><?php echo e($item['hari']); ?></td>
            <td><?php echo e($item['user']->name); ?></td>
            <td><?php echo e($item['user']->statusKepegawaian->name ?? '-'); ?></td>
            
            
            <td><?php echo e($item['status']); ?></td>
            <td><?php echo e($item['presensi'] ? $item['presensi']->waktu_masuk : '-'); ?></td>
            <td>
               <?php
                    $fotoMasuk = null;

                    if ($item['presensi'] && !empty($item['presensi']->selfie_masuk_path)) {
                        // Path sesuai screenshot Boss
                        $pathMasuk = base_path('../public_html/storage/' . $item['presensi']->selfie_masuk_path);

                        if (is_file($pathMasuk)) {
                            $fotoMasuk = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($pathMasuk));
                        }
                    }
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoMasuk): ?>
                    <img src="<?php echo e($fotoMasuk); ?>" width="120">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
            <td><?php echo e($item['presensi'] ? $item['presensi']->waktu_keluar : '-'); ?></td>
            <td>
                <?php
                    $fotoKeluar = null;

                    if ($item['presensi'] && !empty($item['presensi']->selfie_keluar_path)) {
                        $pathKeluar = base_path('../public_html/storage/' . $item['presensi']->selfie_keluar_path);

                        if (is_file($pathKeluar)) {
                            $fotoKeluar = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($pathKeluar));
                        }
                    }
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoKeluar): ?>
                    <img src="<?php echo e($fotoKeluar); ?>" width="120">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
            <td><?php echo e($item['presensi'] ? $item['presensi']->keterangan : '-'); ?></td>
            
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>

</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/presensi-rekap.blade.php ENDPATH**/ ?>