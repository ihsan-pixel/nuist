<?php $__env->startSection('bpp-content'); ?>
<style>
.bpp-admin-detail .card{border:0;border-radius:14px;box-shadow:0 3px 14px rgba(31,55,45,.08);margin-bottom:18px}.bpp-admin-detail .card-body{padding:22px}.bpp-admin-detail .page-title{font-size:20px;font-weight:600;color:#183d32;margin:0}.bpp-admin-detail .summary-card{border:1px solid #e5ece8;box-shadow:none}.bpp-admin-detail .summary-card .text-muted{font-size:12px}.bpp-admin-detail .summary-card strong{font-size:24px;color:#14513d}.bpp-admin-detail .table{margin-bottom:0}.bpp-admin-detail .table thead th{font-size:12px;text-transform:uppercase;letter-spacing:.3px;color:#5b6d64;background:#f5f8f6}.bpp-admin-detail .table td{font-size:13px;vertical-align:middle}.bpp-admin-detail .action-bar{display:flex;flex-wrap:wrap;gap:8px}.bpp-admin-detail .action-bar .btn{border-radius:8px}
</style>
<div class="bpp-admin-detail">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="card"><div class="card-body"><div class="d-flex justify-content-between align-items-start gap-3 flex-wrap"><div><h1 class="page-title"><?php echo e($event->name); ?></h1><div class="text-muted small mt-1">Detail agenda dan rekap presensi</div></div><span class="badge bg-secondary"><?php echo e($event->status); ?></span></div>
<?php echo $__env->make('bpppmnu.event-details', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->attachment): ?><a class="btn btn-outline-secondary btn-sm mb-3" href="<?php echo e(route('admin.agenda.attachment', $event)); ?>">Unduh lampiran</a><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<div class="action-bar">
<a class="btn btn-outline-success" href="<?php echo e(route('admin.agenda.login-qr', $event)); ?>">Tampilkan QR Login NUIST Mobile</a>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status !== 'cancelled'): ?><a class="btn btn-outline-primary" href="<?php echo e(route('admin.agenda.edit', $event)); ?>">Edit Agenda & Undangan</a><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'draft'): ?><form method="post" action="<?php echo e(route('admin.agenda.publish', $event)); ?>"><?php echo csrf_field(); ?><button class="btn btn-success">Terbitkan</button></form><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status === 'published' && now()->lte($event->attendance_close_at)): ?>
<form method="post" action="<?php echo e(route('admin.agenda.qr', $event)); ?>"><?php echo csrf_field(); ?><button class="btn btn-primary">Generate & Tampilkan QR Baru</button></form>
<form method="post" action="<?php echo e(route('admin.agenda.revoke', $event)); ?>" onsubmit="return confirm('Cabut semua QR aktif kegiatan ini?')"><?php echo csrf_field(); ?><button class="btn btn-outline-warning">Cabut QR</button></form>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($event->status !== 'cancelled' && !$event->isFinished() && $recap['present'] === 0): ?>
<form method="post" action="<?php echo e(route('admin.agenda.cancel', $event)); ?>" onsubmit="return confirm('Batalkan kegiatan ini?')"><?php echo csrf_field(); ?><button class="btn btn-outline-danger">Batalkan Kegiatan</button></form>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div></div></div>
<?php if($event->status === 'published' && now()->betweenIncluded($event->attendance_open_at, $event->attendance_close_at)): ?>
<div class="card"><div class="card-body">
<h3 class="h5">Scan Barcode Peserta</h3><p class="text-muted small">Arahkan kamera ke barcode identitas pengurus yang terdaftar dalam agenda ini.</p>
<video id="bpp-member-video" class="w-100 rounded bg-dark" style="max-height:320px" muted playsinline hidden></video>
<div id="bpp-member-status" class="alert alert-secondary py-2" role="status">Kamera belum dibuka.</div>
<a href="<?php echo e(route('admin.agenda.scanner', $event)); ?>" id="bpp-member-open" class="btn btn-primary" data-no-loader="true">Buka Kamera</a>
<button type="button" id="bpp-member-start" hidden aria-hidden="true"></button>
<button type="button" id="bpp-member-stop" class="btn btn-outline-secondary" hidden>Tutup Kamera</button>
</div></div>
<script src="<?php echo e(asset('vendor/jsqr/jsQR.js')); ?>"></script>
<script>
(()=>{const v=document.getElementById('bpp-member-video'),s=document.getElementById('bpp-member-status'),b=document.getElementById('bpp-member-start'),x=document.getElementById('bpp-member-stop');let stream,timer,busy=false;const c=document.createElement('canvas'),g=c.getContext('2d',{willReadFrequently:true});const msg=(t,ok=false)=>{s.textContent=t;s.className='alert py-2 '+(ok?'alert-success':'alert-secondary')};const stop=()=>{clearTimeout(timer);stream?.getTracks().forEach(t=>t.stop());stream=null;v.srcObject=null;v.hidden=true;x.hidden=true;b.disabled=false};const tick=async()=>{if(!stream||busy)return;if(v.readyState>=2){c.width=Math.min(v.videoWidth,800);c.height=Math.round(v.videoHeight*c.width/v.videoWidth);g.drawImage(v,0,0,c.width,c.height);const q=window.jsQR(g.getImageData(0,0,c.width,c.height).data,c.width,c.height,{inversionAttempts:'dontInvert'});if(q){busy=true;stop();msg('Mencatat presensi…');try{const r=await fetch('<?php echo e(route('admin.agenda.scan-member',$event)); ?>',{method:'POST',headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'},body:JSON.stringify({nuist_id:q.data.trim()})});const d=await r.json();if(!r.ok)throw new Error(d.message||'Barcode tidak dapat diproses.');msg(d.message+' '+d.name+' · '+d.attended_at,true);if(window.Swal)Swal.fire({icon:'success',title:'Presensi berhasil',text:d.message+' '+d.name,confirmButtonText:'OK',confirmButtonColor:'#00553f'}).then(()=>location.reload());else setTimeout(()=>location.reload(),1200)}catch(e){busy=false;msg(e.message)}}}timer=setTimeout(tick,180)};b.addEventListener('click',async()=>{try{busy=false;stream=await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}},audio:false});v.srcObject=stream;v.hidden=false;x.hidden=false;b.disabled=true;msg('Arahkan kamera ke barcode peserta.');await v.play();tick()}catch(e){msg('Kamera tidak dapat dibuka. Pastikan izin kamera diberikan.')}});x.addEventListener('click',stop)})();
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<div class="row g-3 mb-3">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['registered_invited','Pengguna diundang',$recap['total']], ['guest_invited','Tamu diundang',$recap['guest_invited']], ['registered_present','Hadir terdaftar',$recap['present']], ['guest_present','Tamu hadir',$recap['guests']], ['remaining',($event->status === 'cancelled' ? 'Dibatalkan / belum hadir' : ($event->isFinished() && $event->status === 'published' ? 'Tidak Hadir' : 'Belum Presensi')),$recap['remaining']], ['percentage','Kehadiran pengguna',$recap['percentage'].'%']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$metric,$label,$value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
<div class="col-6 col-lg-3"><div class="card summary-card h-100 mb-0"><div class="card-body"><div class="text-muted"><?php echo e($label); ?></div><strong data-recap-metric="<?php echo e($metric); ?>"><?php echo e($value); ?></strong></div></div></div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<div class="card"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3 gap-2"><div><h3 class="h5 mb-0">Rekap Kehadiran</h3><small id="recap-live-status" class="text-success">● Pembaruan otomatis aktif</small></div><a href="<?php echo e(route('admin.agenda.export', $event)); ?>" class="btn btn-success btn-sm">Export Excel</a></div>
<div class="table-responsive"><table class="table table-bordered dt-responsive nowrap w-100"><thead class="table-light"><tr><th>Nama</th><th>Kategori</th><th>ID NUIST</th><th>Jabatan/Instansi</th><th>Status</th><th>Waktu hadir (WIB)</th></tr></thead><tbody id="recap-table-body">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recap['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?><tr><td><?php echo e($row->name); ?></td><td><?php echo e($row->participant_type); ?></td><td><?php echo e($row->nuist_id ?: '—'); ?></td><td><?php echo e($row->jabatan ?: '—'); ?></td><td><?php echo e($row->status); ?></td><td><?php echo e($row->attended_at ?: '—'); ?></td></tr><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><tr><td colspan="6">Belum ada peserta.</td></tr><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody></table></div><small class="text-muted">Seluruh peserta terdaftar dan tamu undangan ditampilkan. Status diperbarui setelah presensi berhasil dicatat.</small></div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const body = document.getElementById('recap-table-body');
    const status = document.getElementById('recap-live-status');
    let loading = false;

    const cell = (value) => {
        const element = document.createElement('td');
        element.textContent = value ?? '—';
        return element;
    };

    const refreshRecap = async () => {
        if (loading || document.hidden) return;
        loading = true;
        try {
            const response = await fetch(<?php echo json_encode(route('admin.agenda.recap-status', $event), 512) ?>, {
                headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                cache: 'no-store'
            });
            if (!response.ok) throw new Error('Gagal memperbarui rekap');
            const data = await response.json();
            Object.entries(data.metrics).forEach(([key, value]) => {
                const target = document.querySelector(`[data-recap-metric="${key}"]`);
                if (target) target.textContent = value;
            });
            const fragment = document.createDocumentFragment();
            data.rows.forEach(row => {
                const tr = document.createElement('tr');
                [row.name, row.participant_type, row.nuist_id, row.position, row.status, row.attended_at]
                    .forEach(value => tr.appendChild(cell(value)));
                fragment.appendChild(tr);
            });
            if (!data.rows.length) {
                const tr = document.createElement('tr');
                const td = cell('Belum ada peserta.');
                td.colSpan = 6;
                tr.appendChild(td);
                fragment.appendChild(tr);
            }
            body.replaceChildren(fragment);
            status.textContent = `● Diperbarui ${data.updated_at}`;
            status.className = 'text-success';
        } catch (_) {
            status.textContent = 'Pembaruan otomatis terputus, mencoba kembali…';
            status.className = 'text-warning';
        } finally {
            loading = false;
        }
    };

    setInterval(refreshRecap, 3000);
    document.addEventListener('visibilitychange', () => { if (!document.hidden) refreshRecap(); });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.bpppmnu.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/admin/bpppmnu/show.blade.php ENDPATH**/ ?>