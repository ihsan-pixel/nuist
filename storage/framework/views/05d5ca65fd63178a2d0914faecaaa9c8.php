<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AMI NUIST</title>
    <style>
        :root{--bg:#f4f8f3;--panel:#fff;--primary:#136f3a;--text:#17311f;--muted:#607062;--border:#d8e2d8}
        body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;background:linear-gradient(180deg,#eef6ee 0%,#f9fbf8 100%);color:var(--text)}
        .shell{display:grid;grid-template-columns:260px 1fr;min-height:100vh}
        aside{background:var(--panel);border-right:1px solid var(--border);padding:24px}
        main{padding:24px}
        .card{background:var(--panel);border:1px solid var(--border);border-radius:16px;padding:18px;margin-bottom:16px}
        .nav a{display:block;padding:10px 12px;border-radius:10px;color:var(--text);text-decoration:none}
        .nav a:hover{background:#eef6ef}
        .title{font-size:28px;margin:0 0 8px}
        .muted{color:var(--muted)}
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px}
        .stat{padding:14px;border-radius:14px;background:linear-gradient(135deg,#ffffff,#edf7ef);border:1px solid var(--border)}
        @media(max-width:900px){.shell{grid-template-columns:1fr}aside{border-right:none;border-bottom:1px solid var(--border)}}
    </style>
</head>
<body>
<div class="shell">
    <aside>
        <h2>AMI NUIST</h2>
        <div class="muted"><?php echo e(auth()->user()->name ?? ''); ?></div>
        <nav class="nav" style="margin-top:16px">
            <a href="<?php echo e(route('ami.dashboard')); ?>">Dashboard</a>
            <a href="<?php echo e(route('ami.instrumen')); ?>">Instrumen</a>
            <a href="<?php echo e(route('ami.evaluasi-diri')); ?>">Evaluasi Diri</a>
            <a href="<?php echo e(route('ami.monitoring')); ?>">Monitoring</a>
            <a href="<?php echo e(route('ami.penugasan')); ?>">Penugasan</a>
            <a href="<?php echo e(route('ami.audit')); ?>">Audit</a>
            <a href="<?php echo e(route('ami.klarifikasi')); ?>">Klarifikasi</a>
            <a href="<?php echo e(route('ami.temuan')); ?>">Temuan</a>
            <a href="<?php echo e(route('ami.tindak-lanjut')); ?>">Tindak Lanjut</a>
            <a href="<?php echo e(route('ami.peta-mutu')); ?>">Peta Mutu</a>
            <a href="<?php echo e(route('ami.laporan')); ?>">Laporan</a>
        </nav>
    </aside>
    <main><?php echo $__env->yieldContent('content'); ?></main>
</div>
</body>
</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/ami/layout.blade.php ENDPATH**/ ?>