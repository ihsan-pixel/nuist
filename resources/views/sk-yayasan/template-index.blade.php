@extends('layouts.master')

@section('title')Template SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Template SK Yayasan @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-xl-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Tambah Template</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sk-yayasan.template.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Template</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" placeholder="Contoh: guru, pegawai, umum">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Dokumen</label>
                        <input type="text" name="document_title" class="form-control" value="Surat Keputusan Yayasan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format Nomor SK</label>
                        <input type="text" name="document_number_format" class="form-control" placeholder="{seq}/SKY/{school_code}/{month}/{year}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Template</label>
                        <textarea name="body" rows="12" class="form-control" required>@verbatim
<p style="text-align:center;"><strong>{{judul_sk}}</strong></p>
<p style="text-align:center;"><strong>Nomor: {{nomor_sk}}</strong></p>
<p>Yayasan {{nama_yayasan}} menetapkan bahwa:</p>
<ol>
<li>Nama: <strong>{{nama_pegawai}}</strong></li>
<li>Jabatan: <strong>{{jabatan}}</strong></li>
<li>Status Kepegawaian: <strong>{{status_kepegawaian}}</strong></li>
<li>Masa berlaku SK: <strong>{{tanggal_mulai}}</strong> s.d. <strong>{{tanggal_selesai}}</strong></li>
</ol>
<p>SK ini diterbitkan untuk dipergunakan sebagaimana mestinya.</p>
@endverbatim</textarea>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Aktifkan template</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Template</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Placeholder Tersedia</h5>
            </div>
            <div class="card-body">
                <div class="small text-muted">
                    <code>{{ '{{nomor_sk}}' }}</code>,
                    <code>{{ '{{judul_sk}}' }}</code>,
                    <code>{{ '{{nama_yayasan}}' }}</code>,
                    <code>{{ '{{nama_sekolah}}' }}</code>,
                    <code>{{ '{{nama_pegawai}}' }}</code>,
                    <code>{{ '{{jabatan}}' }}</code>,
                    <code>{{ '{{status_kepegawaian}}' }}</code>,
                    <code>{{ '{{tanggal_mulai}}' }}</code>,
                    <code>{{ '{{tanggal_selesai}}' }}</code>,
                    <code>{{ '{{tanggal_terbit}}' }}</code>,
                    <code>{{ '{{nama_penandatangan}}' }}</code>,
                    <code>{{ '{{jabatan_penandatangan}}' }}</code>,
                    <code>{{ '{{catatan_pengajuan}}' }}</code>.
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Daftar Template</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="templateAccordion">
                    @forelse($templates as $template)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $template->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $template->id }}">
                                    <div class="w-100 d-flex justify-content-between align-items-center me-3">
                                        <span>{{ $template->name }}</span>
                                        <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }}">{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $template->id }}" class="accordion-collapse collapse" data-bs-parent="#templateAccordion">
                                <div class="accordion-body">
                                    <form action="{{ route('sk-yayasan.template.update', $template) }}" method="POST" class="mb-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Nama Template</label>
                                            <input type="text" name="name" value="{{ $template->name }}" class="form-control" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Kategori</label>
                                                <input type="text" name="category" value="{{ $template->category }}" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Judul Dokumen</label>
                                                <input type="text" name="document_title" value="{{ $template->document_title }}" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Format Nomor</label>
                                            <input type="text" name="document_number_format" value="{{ $template->document_number_format }}" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="description" rows="2" class="form-control">{{ $template->description }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Isi Template</label>
                                            <textarea name="body" rows="10" class="form-control" required>{{ $template->body }}</textarea>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($template->is_active)>
                                            <label class="form-check-label">Aktifkan template</label>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                    <form action="{{ route('sk-yayasan.template.destroy', $template) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light border mb-0">Belum ada template SK Yayasan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
