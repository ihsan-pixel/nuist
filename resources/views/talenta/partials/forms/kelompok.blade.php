<div id="{{ $config['slug'] }}-kelompok"
     class="sub-tab-content">

<div class="card">

<form action="{{ route('talenta.tugas-level-1.simpan') }}"
      method="POST"
      enctype="multipart/form-data"
      id="form-kelompok-{{ $config['slug'] }}">

@csrf

<input type="hidden" name="area" value="{{ $config['slug'] }}">
<input type="hidden" name="jenis_tugas" value="kelompok">

<input type="file"
       name="lampiran"
       class="form-control"
       required>

<button class="btn btn-primary mt-3" type="submit">
    Upload Kelompok
</button>

</form>
</div>
</div>
