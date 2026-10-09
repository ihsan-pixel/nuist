@extends('layouts.master')

@section('title', 'Upload Banyak Berkas GTK')

@section('css')
<style>
    .upload-card { border: 1px solid #e9edf4; border-radius: 1rem; background: #fff; }
    .upload-table { min-width: 1180px; }
    .upload-table th { white-space: nowrap; font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; color: #64748b; }
    .upload-table td { vertical-align: middle; }
    .teacher-cell { min-width: 230px; position: sticky; left: 0; z-index: 2; background: #fff; }
    thead .teacher-cell { z-index: 3; background: #f8fafc; }
    .drop-zone { min-width: 175px; min-height: 110px; border: 2px dashed #cbd5e1; border-radius: .75rem; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: .6rem; text-align: center; cursor: pointer; transition: .15s ease; background: #f8fafc; }
    .drop-zone { position: relative; }
    .drop-zone:hover, .drop-zone.dragging { border-color: #0d6efd; background: #eff6ff; }
    .drop-zone.has-file, .drop-zone.has-stored-file { border-color: #22c55e; background: #f0fdf4; }
    .drop-zone.has-stored-file { box-shadow: inset 0 0 0 1px rgba(34, 197, 94, .08); }
    .drop-zone input { display: none; }
    .drop-zone .file-name, .drop-zone .stored-file-name { max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: .72rem; color: #15803d; }
    .drop-zone .stored-file-name { color: #64748b; }
    .existing { font-size: .7rem; color: #15803d; }
    .document-preview { font-size: .7rem; font-weight: 600; text-decoration: none; }
    .stored-files { width: 100%; margin-top: .4rem; display: grid; gap: .3rem; }
    .stored-file { display: flex; align-items: center; gap: .25rem; padding: .25rem .35rem; border-radius: .4rem; background: rgba(255,255,255,.8); }
    .stored-file .document-preview { min-width: 0; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: left; }
    #uploadProgressBar { background-color: #198754 !important; }
    .teacher-avatar { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; display: grid; place-items: center; background: #eff6ff; color: #2563eb; font-weight: 700; flex: 0 0 auto; }
    .save-bar { position: sticky; bottom: 1rem; z-index: 5; }
    .pdf-page-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: .75rem; max-height: 60vh; overflow-y: auto; padding: .25rem; }
    .pdf-page-option { position: relative; display: block; padding: .45rem; border: 2px solid #dbe3ec; border-radius: .7rem; cursor: pointer; background: #fff; }
    .pdf-page-option:has(input:checked) { border-color: #198754; background: #f0fdf4; }
    .pdf-page-option canvas { display: block; width: 100%; height: auto; border-radius: .35rem; box-shadow: 0 1px 5px rgba(15, 23, 42, .15); }
    .pdf-page-option input { position: absolute; top: .65rem; right: .65rem; width: 20px; height: 20px; }
    .pdf-page-number { display: block; margin-top: .4rem; font-size: .75rem; font-weight: 600; text-align: center; }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Pendataan GTK @endslot
    @slot('title') Upload Banyak Berkas GTK @endslot
@endcomponent

<div class="upload-card p-3 p-md-4 mb-3">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
        <div>
            <h4 class="mb-1">Upload Berkas per Guru</h4>
            <div class="text-muted">{{ $madrasah->name }}</div>
            <div class="alert alert-info mt-3 mb-0 py-2">
                Jatuhkan atau pilih beberapa file sekaligus pada kotak yang sama. File lama tidak akan tertimpa dan dapat dihapus satu per satu.
            </div>
        </div>
        <div class="d-flex align-items-start gap-2">
            <a href="{{ route('pendataan-gtk.show', $madrasah) }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
        </div>
    </div>
</div>

<form action="{{ route('pendataan-gtk.documents.manage.store', $madrasah) }}" method="POST" enctype="multipart/form-data" id="documentsGridForm">
    @csrf
    <div class="upload-card p-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div><strong>{{ $gtk->count() }} GTK</strong><span class="text-muted ms-2">Pilih atau drop beberapa file sebelum menyimpan.</span></div>
            <input type="search" class="form-control" id="teacherSearch" placeholder="Cari nama guru..." style="max-width:260px">
        </div>
        <div class="table-responsive">
            <table class="table upload-table align-middle" id="uploadTable">
                <thead class="table-light">
                    <tr>
                        <th class="teacher-cell">Nama GTK</th>
                        <th>KTP</th>
                        <th>SK Awal</th>
                        <th>SK Akhir</th>
                        <th>Foto Resmi</th>
                        <th>Foto Bebas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gtk as $user)
                        @php
                            $data = $user->gtkPendataan;
                        @endphp
                        <tr data-teacher-name="{{ strtolower($user->name.' '.$user->nuist_id) }}">
                            <td class="teacher-cell">
                                <div class="d-flex align-items-center gap-2">
                                    @if($user->avatar)
                                        <img class="teacher-avatar" src="{{ asset('storage/'.ltrim($user->avatar, '/')) }}" alt="Foto {{ $user->name }}">
                                    @else
                                        <div class="teacher-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    @endif
                                    <div><div class="fw-semibold">{{ $user->name }}</div><small class="text-muted">{{ $user->nuist_id ?: 'Tanpa NUIST ID' }}</small></div>
                                </div>
                            </td>
                            @php
                                $documentTypes = [
                                    'ktp' => ['PDF/JPG/PNG', '.pdf,image/jpeg,image/png,image/webp', $data?->ktp_path],
                                    'sk_awal' => ['PDF/foto maks. 10 MB', '.pdf,application/pdf,image/jpeg,image/png,image/webp', $data?->sk_awal_path],
                                    'sk_akhir' => ['PDF/foto maks. 10 MB', '.pdf,application/pdf,image/jpeg,image/png,image/webp', $data?->sk_akhir_path],
                                    'foto_resmi' => ['JPG/PNG/WebP', 'image/jpeg,image/png,image/webp', $user->avatar],
                                    'foto_bebas' => ['JPG/PNG/WebP', 'image/jpeg,image/png,image/webp', $data?->foto_bebas_path],
                                ];
                            @endphp
                            @foreach($documentTypes as $type => $documentType)
                                @php
                                    [$hint, $accept, $storedPath] = $documentType;
                                    $storedFiles = collect();
                                    foreach ($user->gtkDocuments->where('type', $type) as $document) {
                                        $storedFiles->push([
                                            'name' => $document->original_name ?: basename($document->path),
                                            'view_url' => route('pendataan-gtk.documents.manage.view', [$madrasah, $document]),
                                            'delete_url' => route('pendataan-gtk.documents.manage.file.destroy', [$madrasah, $document]),
                                        ]);
                                    }
                                    if ($storedPath) {
                                        $storedFiles->prepend([
                                            'name' => basename($storedPath),
                                            'view_url' => route('pendataan-gtk.documents.view', [$user, str_replace('_', '-', $type)]),
                                            'delete_url' => route('pendataan-gtk.documents.manage.destroy', [$madrasah, $user, $type]),
                                        ]);
                                    }
                                @endphp
                                <td>
                                    <label class="drop-zone {{ $storedFiles->isNotEmpty() ? 'has-stored-file' : '' }}" tabindex="0">
                                        <input type="file" name="documents[{{ $user->id }}][{{ $type }}][]" accept="{{ $accept }}" multiple>
                                        <i class="bx bx-cloud-upload fs-4 text-primary"></i>
                                        <span class="small">Drop atau pilih beberapa</span>
                                        <span class="text-muted" style="font-size:.68rem">{{ $hint }}</span>
                                        @if($storedFiles->isNotEmpty())
                                            <span class="existing"><i class="bx bx-check-circle"></i> {{ $storedFiles->count() }} file tersimpan</span>
                                            <span class="stored-files">
                                                @foreach($storedFiles as $storedFile)
                                                    <span class="stored-file">
                                                        <a href="{{ $storedFile['view_url'] }}" target="_blank" rel="noopener" class="document-preview view-stored-file" title="{{ $storedFile['name'] }}"><i class="bx bx-show me-1"></i>{{ $storedFile['name'] }}</a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-document" title="Hapus berkas" data-delete-url="{{ $storedFile['delete_url'] }}" data-document-label="{{ $storedFile['name'] }}"><i class="bx bx-trash"></i></button>
                                                    </span>
                                                @endforeach
                                            </span>
                                        @endif
                                        <span class="file-name d-none"></span>
                                        <a href="#" target="_blank" rel="noopener" class="document-preview preview-selected d-none"><i class="bx bx-show me-1"></i>Preview file baru</a>
                                    </label>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Belum ada GTK pada madrasah ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($gtk->isNotEmpty())
        <div class="save-bar d-flex justify-content-end mt-3">
            <div class="bg-white border rounded-3 shadow-sm p-2 d-flex align-items-center gap-3">
                <span class="text-muted small"><strong id="selectedCount">0</strong> file baru dipilih</span>
                <button type="submit" class="btn btn-success" id="saveButton" disabled><i class="bx bx-save me-1"></i> Simpan Semua Berkas</button>
            </div>
        </div>
    @endif
</form>
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script type="module">
window.pdfJsReady = import(@json(asset('build/libs/pdfjs/pdf.min.js'))).then(pdfjs => {
    pdfjs.GlobalWorkerOptions.workerSrc = @json(asset('build/libs/pdfjs/pdf.worker.min.js'));
    return pdfjs;
});
</script>
@if(session('success'))
<script>document.addEventListener('DOMContentLoaded', () => Swal.fire({icon:'success', title:'Berhasil', text:@json(session('success')), confirmButtonColor:'#0d6efd'}));</script>
@endif
@if($errors->any())
<script>document.addEventListener('DOMContentLoaded', () => Swal.fire({icon:'error', title:'Berkas belum tersimpan', html:@json(implode('<br>', $errors->all())), confirmButtonColor:'#dc3545'}));</script>
@endif
<script>
(() => {
    const form = document.getElementById('documentsGridForm');
    const inputs = [...form.querySelectorAll('input[type="file"]')];
    const count = document.getElementById('selectedCount');
    const save = document.getElementById('saveButton');
    const pdfPageSelections = new WeakMap();

    async function selectPdfPages(file) {
        const pdfjs = await window.pdfJsReady;
        const pdf = await pdfjs.getDocument({data: await file.arrayBuffer()}).promise;
        if (pdf.numPages === 1) {
            pdfPageSelections.set(file, '1');
            return true;
        }

        const grid = document.createElement('div');
        grid.className = 'pdf-page-grid';
        for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
            const page = await pdf.getPage(pageNumber);
            const viewport = page.getViewport({scale: 0.32});
            const option = document.createElement('label');
            option.className = 'pdf-page-option';
            option.innerHTML = `<input type="checkbox" value="${pageNumber}" checked><canvas></canvas><span class="pdf-page-number">Halaman ${pageNumber}</span>`;
            const canvas = option.querySelector('canvas');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            grid.appendChild(option);
            await page.render({canvasContext: canvas.getContext('2d'), viewport}).promise;
        }

        const result = await Swal.fire({
            title: 'Pilih halaman PDF',
            html: `<div class="text-muted small mb-3">${file.name} — centang halaman yang ingin disimpan.</div>`,
            width: 960,
            showCancelButton: true,
            confirmButtonText: 'Gunakan halaman terpilih',
            cancelButtonText: 'Batalkan file',
            confirmButtonColor: '#198754',
            didOpen: () => Swal.getHtmlContainer().appendChild(grid),
            preConfirm: () => {
                const pages = [...grid.querySelectorAll('input:checked')].map(item => item.value);
                if (!pages.length) {
                    Swal.showValidationMessage('Pilih minimal satu halaman.');
                    return false;
                }
                return pages.join(',');
            },
        });

        if (!result.isConfirmed) return false;
        pdfPageSelections.set(file, result.value);
        return true;
    }

    async function preparePdfSelections(input) {
        const accepted = [];
        for (const file of input.files) {
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                accepted.push(file);
                continue;
            }
            try {
                if (pdfPageSelections.has(file) || await selectPdfPages(file)) accepted.push(file);
            } catch (error) {
                await Swal.fire({icon:'error', title:'PDF tidak dapat dibuka', text:`${file.name}: ${error.message}`, confirmButtonColor:'#dc3545'});
            }
        }

        if (accepted.length !== input.files.length) {
            const transfer = new DataTransfer();
            accepted.forEach(file => transfer.items.add(file));
            input.files = transfer.files;
        }
    }

    function refresh(input) {
        const zone = input.closest('.drop-zone');
        const name = zone.querySelector('.file-name');
        const storedName = zone.querySelector('.stored-file-name');
        const preview = zone.querySelector('.preview-selected');
        const hasFile = input.files.length > 0;
        zone.classList.toggle('has-file', hasFile);
        name.classList.toggle('d-none', !hasFile);
        storedName?.classList.toggle('d-none', hasFile);
        name.textContent = hasFile ? `${input.files.length} file: ${[...input.files].map(file => file.name).join(', ')}` : '';
        if (preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
        preview.classList.toggle('d-none', !hasFile);
        if (hasFile) {
            preview.dataset.objectUrl = URL.createObjectURL(input.files[0]);
            preview.href = preview.dataset.objectUrl;
            preview.innerHTML = '<i class="bx bx-show me-1"></i>Preview file pertama';
        } else {
            preview.removeAttribute('href');
            delete preview.dataset.objectUrl;
        }
        const total = inputs.reduce((sum, item) => sum + item.files.length, 0);
        count.textContent = total;
        save.disabled = total === 0;
    }

    inputs.forEach(input => {
        const zone = input.closest('.drop-zone');
        input.addEventListener('change', async () => {
            await preparePdfSelections(input);
            refresh(input);
        });
        ['dragenter', 'dragover'].forEach(event => zone.addEventListener(event, e => { e.preventDefault(); zone.classList.add('dragging'); }));
        ['dragleave', 'drop'].forEach(event => zone.addEventListener(event, e => { e.preventDefault(); zone.classList.remove('dragging'); }));
        zone.addEventListener('drop', async e => {
            if (!e.dataTransfer.files.length) return;
            const transfer = new DataTransfer();
            [...input.files, ...e.dataTransfer.files].forEach(file => transfer.items.add(file));
            input.files = transfer.files;
            await preparePdfSelections(input);
            refresh(input);
        });
        zone.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); }
        });
    });

    document.querySelectorAll('.document-preview').forEach(link => {
        link.addEventListener('click', event => event.stopPropagation());
    });

    document.querySelectorAll('.remove-document').forEach(button => {
        button.addEventListener('click', async event => {
            event.preventDefault();
            event.stopPropagation();
            const confirmation = await Swal.fire({
                icon: 'warning',
                title: 'Hapus berkas?',
                text: button.dataset.documentLabel + ' akan dihapus permanen.',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
            });
            if (!confirmation.isConfirmed) return;

            button.disabled = true;
            try {
                const response = await fetch(button.dataset.deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Berkas gagal dihapus.');

                const zone = button.closest('.drop-zone');
                button.closest('.stored-file')?.remove();
                const remaining = zone.querySelectorAll('.stored-file').length;
                const existing = zone.querySelector('.existing');
                if (remaining) existing.innerHTML = `<i class="bx bx-check-circle"></i> ${remaining} file tersimpan`;
                else { existing?.remove(); zone.querySelector('.stored-files')?.remove(); zone.classList.remove('has-stored-file'); }
                Swal.fire({icon:'success', title:'Berhasil dihapus', text:result.message, timer:2200, showConfirmButton:false});
            } catch (error) {
                button.disabled = false;
                Swal.fire({icon:'error', title:'Gagal menghapus', text:error.message, confirmButtonColor:'#dc3545'});
            }
        });
    });

    document.getElementById('teacherSearch').addEventListener('input', e => {
        const query = e.target.value.toLowerCase().trim();
        document.querySelectorAll('#uploadTable tbody tr[data-teacher-name]').forEach(row => {
            row.classList.toggle('d-none', !row.dataset.teacherName.includes(query));
        });
    });

    form.addEventListener('submit', event => {
        event.preventDefault();
        if (!save) return;
        save.disabled = true;
        save.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        Swal.fire({
            title: 'Mengunggah berkas GTK',
            html: `
                <div class="text-muted mb-3" id="uploadProgressStatus">Menyiapkan berkas...</div>
                <div class="progress" style="height:18px">
                    <div id="uploadProgressBar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%;background-color:#198754" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                const progressBar = document.getElementById('uploadProgressBar');
                const progressStatus = document.getElementById('uploadProgressStatus');
                const selectedFiles = inputs
                    .filter(input => input.files.length)
                    .flatMap(input => [...input.files].map(file => ({name: input.name, file})));
                const totalBytes = selectedFiles.reduce((sum, item) => sum + item.file.size, 0);
                let confirmedBytes = 0;
                let confirmedFiles = 0;

                const setProgress = (loadedBytes, fileIndex) => {
                    // Tahan pada 99% sampai semua respons server sudah mengonfirmasi penyimpanan.
                    const percentage = Math.min(99, Math.round(((confirmedBytes + loadedBytes) / totalBytes) * 100));
                    progressBar.style.width = percentage + '%';
                    progressBar.setAttribute('aria-valuenow', percentage);
                    progressStatus.textContent = `Mengunggah file ${fileIndex + 1} dari ${selectedFiles.length}... ${percentage}%`;
                };

                const uploadOne = (item, index) => new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    const payload = new FormData();
                    payload.append('_token', @json(csrf_token()));
                    payload.append(item.name, item.file, item.file.name);
                    const selectedPages = pdfPageSelections.get(item.file);
                    if (selectedPages) payload.append('selected_pages', selectedPages);

                    xhr.open('POST', form.action);
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.upload.addEventListener('progress', uploadEvent => {
                        if (uploadEvent.lengthComputable) setProgress(Math.min(uploadEvent.loaded, item.file.size), index);
                    });
                    xhr.addEventListener('load', () => {
                        let result = {};
                        try { result = JSON.parse(xhr.responseText); } catch (_) {}
                        if (xhr.status >= 200 && xhr.status < 300 && Number(result.uploaded) === 1) {
                            resolve(result);
                            return;
                        }
                        const message = result.errors
                            ? Object.values(result.errors).flat().join('\n')
                            : (result.message || `Server tidak mengonfirmasi file ${item.file.name}.`);
                        reject(new Error(message));
                    });
                    xhr.addEventListener('error', () => reject(new Error('Koneksi terputus saat mengunggah '+item.file.name+'.')));
                    xhr.send(payload);
                });

                (async () => {
                    try {
                        for (let index = 0; index < selectedFiles.length; index++) {
                            const item = selectedFiles[index];
                            await uploadOne(item, index);
                            confirmedBytes += item.file.size;
                            confirmedFiles++;
                            progressStatus.textContent = `${confirmedFiles} dari ${selectedFiles.length} file berhasil disimpan.`;
                        }

                        progressBar.style.width = '100%';
                        progressBar.setAttribute('aria-valuenow', '100');
                        progressBar.classList.remove('progress-bar-animated');
                        progressStatus.textContent = `100% - semua ${confirmedFiles} file berhasil disimpan.`;
                        await Swal.fire({
                            icon: 'success',
                            title: 'Upload selesai',
                            text: `Semua ${confirmedFiles} file berhasil disimpan.`,
                            confirmButtonText: 'Selesai',
                            confirmButtonColor: '#198754',
                        });
                        window.location.reload();
                    } catch (error) {
                        progressBar.classList.remove('progress-bar-animated');
                        await Swal.fire({
                            icon: 'error',
                            title: 'Upload belum lengkap',
                            text: `${confirmedFiles} dari ${selectedFiles.length} file tersimpan. ${error.message}`,
                            confirmButtonText: 'Muat ulang dan periksa',
                            confirmButtonColor: '#dc3545',
                        });
                        window.location.reload();
                    }
                })();
            },
        });
    });
})();
</script>
@endsection
