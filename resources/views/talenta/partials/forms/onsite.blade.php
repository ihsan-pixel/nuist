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

<input type="file"
       name="lampiran"
       class="form-control"
       required>

<div class="d-flex justify-content-end mt-3">
    @if(!isset($existingTasks[$config['slug']]))
        <button class="btn btn-primary me-2" type="submit">
            Upload On Site
        </button>
    @endif
    @if(isset($existingTasks[$config['slug']]))
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
