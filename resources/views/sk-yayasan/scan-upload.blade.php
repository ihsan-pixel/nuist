@extends('layouts.master')

@section('title')Upload Scan SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Upload Scan SK Yayasan @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h4 class="mb-1">Upload Scan SK Yayasan</h4>
                        <p class="text-muted mb-0">Seret hingga ratusan file PDF. Sistem mencocokkan nama file dengan nama user, NUIST ID, atau nomor SK.</p>
                    </div>
                    <div class="text-end small text-muted">{{ number_format($uploadedCount) }} sudah terunggah<br>{{ number_format($documentCount) }} dokumen tahun 2026</div>
                </div>

                <div id="dropzone" class="border border-2 border-primary border-dashed rounded-4 p-5 text-center bg-light" style="cursor:pointer">
                    <i class="bx bx-cloud-upload fs-1 text-primary"></i>
                    <h5 class="mt-2 mb-1">Drop file PDF di sini</h5>
                    <p class="text-muted mb-3">atau klik untuk memilih banyak file sekaligus</p>
                    <button type="button" class="btn btn-primary px-4">Pilih File PDF</button>
                    <input id="fileInput" type="file" accept="application/pdf,.pdf" multiple hidden>
                </div>

                <div id="progressPanel" class="d-none mt-4">
                    <div class="d-flex justify-content-between mb-2"><strong>Progress upload</strong><span id="progressText">0%</span></div>
                    <div class="progress" style="height:12px"><div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div></div>
                    <div id="summary" class="small text-muted mt-2"></div>
                </div>

                <div id="resultPanel" class="d-none mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2"><strong>Hasil pencocokan</strong><button id="clearResults" class="btn btn-sm btn-outline-secondary">Bersihkan</button></div>
                    <div id="results" class="list-group small" style="max-height:330px;overflow:auto"></div>
                </div>

                <div class="alert alert-info mt-4 mb-0 small"><i class="bx bx-info-circle me-1"></i> Format wajib PDF, maksimal 30 MB per file. Nama file sebaiknya memuat nomor SK atau nama/NUIST ID user. File dengan nama sama akan menggantikan scan sebelumnya.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
(() => {
    const dropzone = document.getElementById('dropzone'), input = document.getElementById('fileInput');
    const progressPanel = document.getElementById('progressPanel'), resultPanel = document.getElementById('resultPanel');
    const bar = document.getElementById('progressBar'), progressText = document.getElementById('progressText');
    const summary = document.getElementById('summary'), results = document.getElementById('results');
    let files = [], done = 0, ok = 0, failed = 0;
    dropzone.addEventListener('click', () => input.click());
    ['dragenter','dragover'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('bg-primary-subtle'); }));
    ['dragleave','drop'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('bg-primary-subtle'); }));
    dropzone.addEventListener('drop', ev => start([...ev.dataTransfer.files]));
    input.addEventListener('change', () => start([...input.files]));
    document.getElementById('clearResults').addEventListener('click', () => { results.innerHTML = ''; resultPanel.classList.add('d-none'); });
    function start(selected) {
        files = selected.filter(file => file.name.toLowerCase().endsWith('.pdf'));
        if (!files.length) return;
        done = ok = failed = 0; progressPanel.classList.remove('d-none'); resultPanel.classList.remove('d-none'); results.innerHTML = '';
        summary.textContent = `Menyiapkan ${files.length} file...`; uploadNext();
    }
    function uploadNext() {
        if (done >= files.length) { summary.textContent = `Selesai: ${ok} berhasil, ${failed} gagal dari ${files.length} file.`; return; }
        const file = files[done], form = new FormData(); form.append('file', file);
        const xhr = new XMLHttpRequest(); xhr.open('POST', @json(route('sk-yayasan.scan-upload.file')));
        xhr.setRequestHeader('X-CSRF-TOKEN', @json(csrf_token())); xhr.setRequestHeader('Accept', 'application/json');
        xhr.upload.onprogress = e => { if (e.lengthComputable) { const pct = Math.round(((done + e.loaded / e.total) / files.length) * 100); bar.style.width = pct+'%'; progressText.textContent = pct+'%'; } };
        xhr.onload = () => { let data = {}; try { data = JSON.parse(xhr.responseText); } catch(e) {} const success = xhr.status >= 200 && xhr.status < 300 && data.ok; done++; success ? ok++ : failed++; addResult(file.name, success, success ? `${data.user || '-'} · ${data.number || '-'}` : (data.message || 'Upload gagal')); uploadNext(); };
        xhr.onerror = () => { done++; failed++; addResult(file.name, false, 'Koneksi gagal'); uploadNext(); };
        xhr.send(form);
    }
    function addResult(name, success, detail) { const item = document.createElement('div'); item.className = 'list-group-item d-flex justify-content-between gap-3'; item.innerHTML = `<span class="text-truncate"><i class="bx ${success ? 'bx-check-circle text-success' : 'bx-x-circle text-danger'} me-1"></i>${escapeHtml(name)}</span><span class="text-muted text-end">${escapeHtml(detail)}</span>`; results.appendChild(item); }
    function escapeHtml(value) { const div = document.createElement('div'); div.textContent = value; return div.innerHTML; }
})();
</script>
@endsection
