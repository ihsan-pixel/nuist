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
            <th>Nama Guru</th>
            <th>Masuk</th>
            <th>Foto Masuk</th>
            <th>Keluar</th>
            <th>Foto Keluar</th>
            <th>Keterangan</th>
        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($p->tanggal); ?></td>
            <td><?php echo e($p->user->name); ?></td>

            <td><?php echo e($p->waktu_masuk); ?></td>
            <td>
                <?php if($p->selfie_masuk_path): ?>
                    <img src="file://<?php echo e(public_path('storage/' . $p->selfie_masuk_path)); ?>">
                <?php endif; ?>
            </td>
            <td><?php echo e($p->waktu_keluar); ?></td>
            <td>
                <?php if($p->selfie_keluar_path): ?>
                    <img src="file://<?php echo e(public_path('storage/' . $p->selfie_keluar_path)); ?>">
                <?php endif; ?>
            </td>

            <td><?php echo e($p->keterangan); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/presensi-rekap.blade.php ENDPATH**/ ?>