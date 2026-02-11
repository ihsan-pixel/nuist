<div id="{{ $config['slug'] }}-onsite"
     class="sub-tab-content active">

<div class="card">

<form action="{{ route('talenta.tugas-level-1.simpan') }}"
      method="POST"
      enctype="multipart/form-data"
      id="form-onsite-{{ $config['slug'] }}">

@csrf

<input type="hidden" name="area" value="{{ $config['slug'] }}">
<input type="hidden" name="jenis_tugas" value="onsite">

<input type="file"
       name="lampiran"
       class="form-control"
       required>

<button class="btn btn-primary mt-3" type="submit">
    Upload On Site
</button>

</form>
</div>
</div>
