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
    .drop-zone { min-width: 155px; min-height: 88px; border: 2px dashed #cbd5e1; border-radius: .75rem; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: .6rem; text-align: center; cursor: pointer; transition: .15s ease; background: #f8fafc; }
    .drop-zone { position: relative; }
    .drop-zone:hover, .drop-zone.dragging { border-color: #0d6efd; background: #eff6ff; }
    .drop-zone.has-file, .drop-zone.has-stored-file { border-color: #22c55e; background: #f0fdf4; }
    .drop-zone.has-stored-file { box-shadow: inset 0 0 0 1px rgba(34, 197, 94, .08); }
    .drop-zone input { display: none; }
    .drop-zone .file-name, .drop-zone .stored-file-name { max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: .72rem; color: #15803d; }
    .drop-zone .stored-file-name { color: #64748b; }
    .existing { font-size: .7rem; color: #15803d; }
    .remove-document { position: absolute; top: .3rem; right: .3rem; width: 25px; height: 25px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; }
    .teacher-avatar { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; display: grid; place-items: center; background: #eff6ff; color: #2563eb; font-weight: 700; flex: 0 0 auto; }
    .save-bar { position: sticky; bottom: 1rem; z-index: 5; }
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
                Jatuhkan file ke kotak guru dan jenis berkas yang sesuai. Nama file bebas; sistem menggunakan posisi kotak untuk menentukan pemilik berkas.
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
                        @php($data = $user->gtkPendataan)
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
                            @foreach([
                                'ktp' => ['PDF/JPG/PNG', '.pdf,image/jpeg,image/png,image/webp', $data?->ktp_path],
                                'sk_awal' => ['PDF/foto maks. 10 MB', '.pdf,application/pdf,image/jpeg,image/png,image/webp', $data?->sk_awal_path],
                                'sk_akhir' => ['PDF/foto maks. 10 MB', '.pdf,application/pdf,image/jpeg,image/png,image/webp', $data?->sk_akhir_path],
                                'foto_resmi' => ['JPG/PNG/WebP', 'image/jpeg,image/png,image/webp', $user->avatar],
                                'foto_bebas' => ['JPG/PNG/WebP', 'image/jpeg,image/png,image/webp', $data?->foto_bebas_path],
                            ] as $type => [$hint, $accept, $storedPath])
                                <td>
                                    <label class="drop-zone {{ $storedPath ? 'has-stored-file' : '' }}" tabindex="0">
                                        <input type="file" name="documents[{{ $user->id }}][{{ $type }}]" accept="{{ $accept }}">
                                        @if($storedPath)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger remove-document"
                                                title="Hapus berkas"
                                                aria-label="Hapus {{ str_replace('_', ' ', $type) }} {{ $user->name }}"
                                                data-delete-url="{{ route('pendataan-gtk.documents.manage.destroy', [$madrasah, $user, $type]) }}"
                                                data-document-label="{{ str_replace('_', ' ', $type) }} - {{ $user->name }}"
                                            ><i class="bx bx-trash"></i></button>
                                        @endif
                                        <i class="bx bx-cloud-upload fs-4 text-primary"></i>
                                        <span class="small">Drop atau pilih</span>
                                        <span class="text-muted" style="font-size:.68rem">{{ $hint }}</span>
                                        @if($storedPath)
                                            <span class="existing"><i class="bx bx-check-circle"></i> Tersimpan</span>
                                            <span class="stored-file-name" title="{{ basename($storedPath) }}">{{ basename($storedPath) }}</span>
                                        @endif
                                        <span class="file-name d-none"></span>
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

    function refresh(input) {
        const zone = input.closest('.drop-zone');
        const name = zone.querySelector('.file-name');
        const storedName = zone.querySelector('.stored-file-name');
        const hasFile = input.files.length > 0;
        zone.classList.toggle('has-file', hasFile);
        name.classList.toggle('d-none', !hasFile);
        storedName?.classList.toggle('d-none', hasFile);
        name.textContent = hasFile ? input.files[0].name : '';
        const total = inputs.filter(item => item.files.length).length;
        count.textContent = total;
        save.disabled = total === 0;
    }

    inputs.forEach(input => {
        const zone = input.closest('.drop-zone');
        input.addEventListener('change', () => refresh(input));
        ['dragenter', 'dragover'].forEach(event => zone.addEventListener(event, e => { e.preventDefault(); zone.classList.add('dragging'); }));
        ['dragleave', 'drop'].forEach(event => zone.addEventListener(event, e => { e.preventDefault(); zone.classList.remove('dragging'); }));
        zone.addEventListener('drop', e => {
            if (!e.dataTransfer.files.length) return;
            const transfer = new DataTransfer();
            transfer.items.add(e.dataTransfer.files[0]);
            input.files = transfer.files;
            refresh(input);
        });
        zone.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); }
        });
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
                zone.classList.remove('has-stored-file');
                zone.querySelector('.existing')?.remove();
                zone.querySelector('.stored-file-name')?.remove();
                button.remove();
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
                    <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                const xhr = new XMLHttpRequest();
                const progressBar = document.getElementById('uploadProgressBar');
                const progressStatus = document.getElementById('uploadProgressStatus');

                xhr.open('POST', form.action);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.upload.addEventListener('progress', uploadEvent => {
                    if (!uploadEvent.lengthComputable) return;
                    const percentage = Math.min(100, Math.round((uploadEvent.loaded / uploadEvent.total) * 100));
                    progressBar.style.width = percentage + '%';
                    progressBar.textContent = percentage + '%';
                    progressBar.setAttribute('aria-valuenow', percentage);
                    progressStatus.textContent = percentage < 100
                        ? `Mengunggah berkas... ${percentage}%`
                        : 'Upload 100%. Memproses dan menyimpan berkas...';
                });

                xhr.addEventListener('load', async () => {
                    let result = {};
                    try { result = JSON.parse(xhr.responseText); } catch (_) {}

                    if (xhr.status >= 200 && xhr.status < 300) {
                        progressBar.style.width = '100%';
                        progressBar.textContent = '100%';
                        progressBar.classList.remove('progress-bar-animated');
                        progressStatus.textContent = 'Semua berkas berhasil disimpan.';
                        await Swal.fire({
                            icon: 'success',
                            title: 'Upload selesai',
                            text: result.message || 'Semua berkas berhasil disimpan.',
                            confirmButtonText: 'Selesai',
                            confirmButtonColor: '#198754',
                        });
                        window.location.reload();
                        return;
                    }

                    const validationMessages = result.errors
                        ? Object.values(result.errors).flat().join('\n')
                        : (result.message || 'Berkas gagal diunggah. Silakan coba kembali.');
                    save.disabled = false;
                    save.innerHTML = '<i class="bx bx-save me-1"></i> Simpan Semua Berkas';
                    Swal.fire({icon:'error', title:'Upload gagal', text:validationMessages, confirmButtonColor:'#dc3545'});
                });

                xhr.addEventListener('error', () => {
                    save.disabled = false;
                    save.innerHTML = '<i class="bx bx-save me-1"></i> Simpan Semua Berkas';
                    Swal.fire({icon:'error', title:'Koneksi terputus', text:'Upload gagal karena masalah jaringan.', confirmButtonColor:'#dc3545'});
                });

                xhr.send(new FormData(form));
            },
        });
    });
})();
</script>
@endsection
