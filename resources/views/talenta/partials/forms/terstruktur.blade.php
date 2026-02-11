<div id="{{ $config['slug'] }}-terstruktur"
     class="sub-tab-content">

<div class="card">

<form action="{{ route('talenta.tugas-level-1.simpan') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<input type="hidden" name="area" value="{{ $config['slug'] }}">
<input type="hidden" name="jenis_tugas" value="terstruktur">

<input type="file"
       name="lampiran"
       class="form-control">

<button class="btn btn-primary mt-3">
    Upload Terstruktur
</button>

</form>
</div>
</div>
