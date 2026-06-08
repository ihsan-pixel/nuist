@extends('layouts.master')

@section('title')Template SK Yayasan @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') SK Yayasan @endslot
    @slot('title') Template SK Yayasan @endslot
@endcomponent

@include('sk-yayasan.partials.ui-styles')

<div class="sky-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Template SK Yayasan</div>
                <h4 class="mb-1">Kelola format dan placeholder dokumen SK</h4>
                <p class="mb-0 text-white-50">
                    Siapkan template yang fleksibel untuk guru dan pegawai agar proses generate SK konsisten dan cepat.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $templates->count() }} template</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white">{{ $templates->where('is_active', true)->count() }} aktif</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Form Template</div>
                    <h6 class="mb-3">Tambah Template Baru</h6>

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
<li>Gelar: <strong>{{gelar}}</strong></li>
<li>Jabatan: <strong>{{jabatan}}</strong></li>
<li>Status Kepegawaian: <strong>{{status_kepegawaian}}</strong></li>
<li>Penilaian Kinerja: <strong>{{penilaian_kinerja}}</strong></li>
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

            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Placeholder Tersedia</div>
                    <h6 class="mb-3">Field yang dapat dipakai di body template</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach([
                            '{{nomor_sk}}', '{{judul_sk}}', '{{nama_yayasan}}', '{{nama_sekolah}}', '{{nama_pegawai}}',
                            '{{gelar}}', '{{tempat_lahir}}', '{{tanggal_lahir}}', '{{nip_maarif}}', '{{nuptk}}',
                            '{{nomor_kartanu}}', '{{tmt_pertama}}', '{{masa_kerja}}', '{{pendidikan_terakhir}}',
                            '{{tahun_lulus}}', '{{program_studi}}', '{{mapel_tugas_yang_diampu}}', '{{penilaian_kinerja}}',
                            '{{keterangan_sk_yayasan}}', '{{jabatan}}', '{{status_kepegawaian}}', '{{tanggal_terbit}}',
                            '{{nama_penandatangan}}', '{{jabatan_penandatangan}}'
                        ] as $placeholder)
                            <span class="sky-chip">{{ $placeholder }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Daftar Template</div>
                            <h6 class="mb-0">Template aktif dan nonaktif</h6>
                        </div>
                        <span class="sky-chip">{{ $templates->count() }} data</span>
                    </div>

                    @if($templates->isNotEmpty())
                        <div class="accordion" id="templateAccordion">
                            @foreach($templates as $template)
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="heading{{ $template->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $template->id }}">
                                            <div class="w-100 d-flex justify-content-between align-items-center me-3">
                                                <div>
                                                    <div class="fw-semibold">{{ $template->name }}</div>
                                                    <small class="text-muted">{{ $template->document_title }}</small>
                                                </div>
                                                <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }}-subtle text-{{ $template->is_active ? 'success' : 'secondary' }}">
                                                    {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
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
                                                <div class="d-flex flex-wrap gap-2">
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
                            @endforeach
                        </div>
                    @else
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-notepad"></i>
                            <strong>Belum ada template SK Yayasan</strong>
                            <small>Tambahkan template pertama agar proses generate SK bisa berjalan.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
