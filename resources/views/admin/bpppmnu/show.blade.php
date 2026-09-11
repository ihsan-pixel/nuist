@extends('admin.bpppmnu.layout')
@section('bpp-content')
<style>
.bpp-admin-detail .card{border:0;border-radius:14px;box-shadow:0 3px 14px rgba(31,55,45,.08);margin-bottom:18px}.bpp-admin-detail .card-body{padding:22px}.bpp-admin-detail .page-title{font-size:20px;font-weight:600;color:#183d32;margin:0}.bpp-admin-detail .summary-card{border:1px solid #e5ece8;box-shadow:none}.bpp-admin-detail .summary-card .text-muted{font-size:12px}.bpp-admin-detail .summary-card strong{font-size:24px;color:#14513d}.bpp-admin-detail .table{margin-bottom:0}.bpp-admin-detail .table thead th{font-size:12px;text-transform:uppercase;letter-spacing:.3px;color:#5b6d64;background:#f5f8f6}.bpp-admin-detail .table td{font-size:13px;vertical-align:middle}.bpp-admin-detail .action-bar{display:flex;flex-wrap:wrap;gap:8px}.bpp-admin-detail .action-bar .btn{border-radius:8px}
</style>
<div class="bpp-admin-detail">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="card"><div class="card-body"><div class="d-flex justify-content-between align-items-start gap-3 flex-wrap"><div><h1 class="page-title">{{ $event->name }}</h1><div class="text-muted small mt-1">Detail agenda dan rekap presensi</div></div><span class="badge bg-secondary">{{ $event->status }}</span></div>
@include('bpppmnu.event-details')
@if($event->attachment)<a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.bpppmnu.events.attachment', $event) }}">Unduh lampiran</a>@endif
<div class="action-bar">
@if(!$event->isLocked() && $event->status !== 'cancelled')<a class="btn btn-outline-primary" href="{{ route('admin.bpppmnu.events.edit', $event) }}">Edit Agenda & Undangan</a>@endif
@if($event->status === 'draft')<form method="post" action="{{ route('admin.bpppmnu.events.publish', $event) }}">@csrf<button class="btn btn-success">Terbitkan</button></form>@endif
@if($event->status === 'published' && now()->lte($event->attendance_close_at))
<form method="post" action="{{ route('admin.bpppmnu.events.qr', $event) }}">@csrf<button class="btn btn-primary">Generate & Tampilkan QR Baru</button></form>
<form method="post" action="{{ route('admin.bpppmnu.events.revoke', $event) }}" onsubmit="return confirm('Cabut semua QR aktif kegiatan ini?')">@csrf<button class="btn btn-outline-warning">Cabut QR</button></form>
@endif
@if($event->status !== 'cancelled' && !$event->isFinished() && $recap['present'] === 0)
<form method="post" action="{{ route('admin.bpppmnu.events.cancel', $event) }}" onsubmit="return confirm('Batalkan kegiatan ini?')">@csrf<button class="btn btn-outline-danger">Batalkan Kegiatan</button></form>
@endif
</div></div></div>
@if($event->status === 'published' && now()->betweenIncluded($event->attendance_open_at, $event->attendance_close_at))
<div class="card"><div class="card-body">
<h3 class="h5">Scan Barcode Peserta</h3><p class="text-muted small">Arahkan kamera ke barcode identitas pengurus yang terdaftar dalam agenda ini.</p>
<video id="bpp-member-video" class="w-100 rounded bg-dark" style="max-height:320px" muted playsinline hidden></video>
<div id="bpp-member-status" class="alert alert-secondary py-2" role="status">Kamera belum dibuka.</div>
<a href="{{ route('admin.bpppmnu.events.scanner', $event) }}" id="bpp-member-open" class="btn btn-primary" data-no-loader="true">Buka Kamera</a>
<button type="button" id="bpp-member-stop" class="btn btn-outline-secondary" hidden>Tutup Kamera</button>
</div></div>
<script src="{{ asset('vendor/jsqr/jsQR.js') }}"></script>
<script>
(()=>{const v=document.getElementById('bpp-member-video'),s=document.getElementById('bpp-member-status'),b=document.getElementById('bpp-member-start'),x=document.getElementById('bpp-member-stop');let stream,timer,busy=false;const c=document.createElement('canvas'),g=c.getContext('2d',{willReadFrequently:true});const msg=(t,ok=false)=>{s.textContent=t;s.className='alert py-2 '+(ok?'alert-success':'alert-secondary')};const stop=()=>{clearTimeout(timer);stream?.getTracks().forEach(t=>t.stop());stream=null;v.srcObject=null;v.hidden=true;x.hidden=true;b.disabled=false};const tick=async()=>{if(!stream||busy)return;if(v.readyState>=2){c.width=Math.min(v.videoWidth,800);c.height=Math.round(v.videoHeight*c.width/v.videoWidth);g.drawImage(v,0,0,c.width,c.height);const q=window.jsQR(g.getImageData(0,0,c.width,c.height).data,c.width,c.height,{inversionAttempts:'dontInvert'});if(q){busy=true;stop();msg('Mencatat presensi…');try{const r=await fetch('{{ route('admin.bpppmnu.events.scan-member',$event) }}',{method:'POST',headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({nuist_id:q.data.trim()})});const d=await r.json();if(!r.ok)throw new Error(d.message||'Barcode tidak dapat diproses.');msg(d.message+' '+d.name+' · '+d.attended_at,true);if(window.Swal)Swal.fire({icon:'success',title:'Presensi berhasil',text:d.message+' '+d.name,confirmButtonText:'OK',confirmButtonColor:'#00553f'}).then(()=>location.reload());else setTimeout(()=>location.reload(),1200)}catch(e){busy=false;msg(e.message)}}}timer=setTimeout(tick,180)};b.addEventListener('click',async()=>{try{busy=false;stream=await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}},audio:false});v.srcObject=stream;v.hidden=false;x.hidden=false;b.disabled=true;msg('Arahkan kamera ke barcode peserta.');await v.play();tick()}catch(e){msg('Kamera tidak dapat dibuka. Pastikan izin kamera diberikan.')}});x.addEventListener('click',stop)})();
</script>
@endif
<div class="row g-3 mb-3">
@foreach(['Undangan'=>$recap['total'], 'Hadir'=>$recap['present'], ($event->status === 'cancelled' ? 'Dibatalkan / belum hadir' : ($event->isFinished() && $event->status === 'published' ? 'Tidak Hadir' : 'Belum Presensi'))=>$recap['remaining'], 'Kehadiran'=>$recap['percentage'].'%'] as $label=>$value)
<div class="col-6 col-lg-3"><div class="card summary-card h-100 mb-0"><div class="card-body"><div class="text-muted">{{ $label }}</div><strong>{{ $value }}</strong></div></div></div>
@endforeach
</div>
<div class="card"><div class="card-body"><div class="d-flex justify-content-between mb-3"><h3 class="h5">Rekap Kehadiran</h3><a href="{{ route('admin.bpppmnu.events.export', $event) }}" class="btn btn-success btn-sm">Export Excel</a></div>
<div class="table-responsive"><table class="table table-bordered dt-responsive nowrap w-100"><thead class="table-light"><tr><th>Nama</th><th>ID NUIST</th><th>Jabatan</th><th>Status</th><th>Waktu hadir (WIB)</th></tr></thead><tbody>
@forelse($recap['rows'] as $row)<tr><td>{{ $row->name }}</td><td>{{ $row->nuist_id }}</td><td>{{ $row->jabatan ?: '—' }}</td><td>{{ $row->status }}</td><td>{{ $row->attended_at ?: '—' }}</td></tr>@empty<tr><td colspan="5">Belum ada undangan.</td></tr>@endforelse
</tbody></table></div><small class="text-muted">Rekap diperbarui setelah presensi berhasil dicatat.</small></div></div>
</div>
@endsection
