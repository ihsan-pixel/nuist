<div id="{{ $config['slug'] }}-onsite"
     class="sub-tab-content active">

<div class="card">

<form action="{{ route('talenta.tugas-level-1.simpan') }}"
      method="POST"
      enctype="multipart/form-data"
      id="form-onsite-{{ $config['slug'] }}">

@csrf

<input type="hidden" name="area" value="{{ $config['slug'] }}">
<input type="hidden" name="jenis_tugas" value="on_site">

@if($config['slug'] === 'kepemimpinan')
    <div class="mb-3">
        <label for="konteks" class="form-label">Konteks</label>
        <textarea name="konteks" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="peran" class="form-label">Peran</label>
        <input type="text" name="peran" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="nilai_kepemimpinan" class="form-label">Nilai Kepemimpinan</label>
        <textarea name="nilai_kepemimpinan" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="masalah_kepemimpinan" class="form-label">Masalah Kepemimpinan</label>
        <textarea name="masalah_kepemimpinan" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="pelajaran_penting" class="form-label">Pelajaran Penting</label>
        <textarea name="pelajaran_penting" class="form-control" required></textarea>
    </div>
@else
    <input type="file"
           name="lampiran"
           class="form-control"
           required>
@endif

<div class="d-flex justify-content-end mt-3">
    @if($config['slug'] === 'kepemimpinan' || !isset($existingTasks[$config['slug']]))
        <button class="btn btn-primary me-2" type="submit">
            @if($config['slug'] === 'kepemimpinan')
                Simpan On Site
            @else
                Upload On Site
            @endif
        </button>
    @endif
    @if(isset($existingTasks[$config['slug']]) && $config['slug'] !== 'kepemimpinan')
        <a href="{{ asset('storage/' . $existingTasks[$config['slug']]->file_path) }}"
           target="_blank"
           class="btn btn-secondary">
            <i class="bx bx-file"></i> Lihat File Terupload
        </a>
    @endif
</div>

</form>
</div>
</div>
