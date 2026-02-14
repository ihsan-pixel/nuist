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

@php
    // Determine if the materi has passed its tanggal_materi (expired)
    $expired = false;
    if (isset($materi) && $materi->tanggal_materi) {
        $expired = now()->gt($materi->tanggal_materi);
    }
@endphp

<input type="file"
       name="lampiran"
       class="form-control"
       @if($expired) disabled @else required @endif>

<div class="d-flex justify-content-end mt-3">
    @if($expired)
        <p class="text-danger me-2">Batas waktu upload telah lewat ({{ isset($materi->tanggal_materi) ? $materi->tanggal_materi->format('d M Y') : '-' }}).</p>
    @else
        @if(!isset($existingTasks[$config['slug'] . '-on_site']))
            <button class="btn btn-primary me-2" type="submit">
                Upload On Site
            </button>
        @endif
    @endif
    @if(isset($existingTasks[$config['slug'] . '-on_site']))
        <a href="{{ asset($existingTasks[$config['slug'] . '-on_site']->file_path) }}"
           target="_blank"
           class="btn btn-secondary">
            <i class="bx bx-file"></i> Lihat File Terupload
        </a>
        <form action="{{ route('talenta.tugas-level-1.reset') }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Yakin ingin menghapus file terupload?');">
            @csrf
            <input type="hidden" name="area" value="{{ $config['slug'] }}">
            <input type="hidden" name="jenis_tugas" value="on_site">
            <button type="submit" class="btn btn-danger">
                <i class="bx bx-trash"></i> Reset
            </button>
        </form>
    @endif
</div>

</form>
</div>
</div>
