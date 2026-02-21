<div id="{{ $config['slug'] }}-terstruktur"
     class="sub-tab-content">

<div class="card">

<form action="{{ route('talenta.tugas-level-1.simpan') }}"
      method="POST"
      enctype="multipart/form-data"
      id="form-terstruktur-{{ $config['slug'] }}">

@csrf

<input type="hidden" name="area" value="{{ $config['slug'] }}">
<input type="hidden" name="jenis_tugas" value="terstruktur">

@php
    $soalsForArea = $soalsByArea[$config['slug']] ?? collect();
    $soalList = $soalsForArea['terstruktur'] ?? collect();
@endphp

@if($soalList->isNotEmpty())
    <div class="mb-3">
        @foreach($soalList as $soal)
            <div class="card bg-light p-2 mb-2">
                <div class="fw-semibold">Soal:</div>
                <div class="small">{!! nl2br(e($soal->pertanyaan)) !!}</div>
                @if($soal->instruksi)
                    <div class="small text-muted mt-1">{!! nl2br(e($soal->instruksi)) !!}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

<input type="file"
       name="lampiran"
       class="form-control"
       required>

<div class="d-flex justify-content-end mt-3">
    @if(!isset($existingTasks[$config['slug'] . '-terstruktur']))
        <button class="btn btn-primary me-2" type="submit">
            Upload Terstruktur
        </button>
    @endif
    @if(isset($existingTasks[$config['slug'] . '-terstruktur']))
        <a href="{{ asset($existingTasks[$config['slug'] . '-terstruktur']->file_path) }}"
           target="_blank"
           class="btn btn-secondary">
            <i class="bx bx-file"></i> Lihat File Terupload
        </a>
        <form action="{{ route('talenta.tugas-level-1.reset') }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Yakin ingin menghapus file terupload?');">
            @csrf
            <input type="hidden" name="area" value="{{ $config['slug'] }}">
            <input type="hidden" name="jenis_tugas" value="terstruktur">
            <button type="submit" class="btn btn-danger">
                <i class="bx bx-trash"></i> Reset
            </button>
        </form>
    @endif
</div>

</form>
</div>
</div>
