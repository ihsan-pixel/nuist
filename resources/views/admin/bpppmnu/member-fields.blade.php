<div class="row g-2">
@foreach(['name'=>'Nama','email'=>'Email','jabatan'=>'Jabatan','instansi_asal'=>'Instansi Asal'] as $field=>$label)
<div class="col-md-6"><label class="form-label" for="member-{{ $member?->id ?? 'new' }}-{{ $field }}">{{ $label }}</label><input id="member-{{ $member?->id ?? 'new' }}-{{ $field }}" name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}" class="form-control" value="{{ $member?->$field }}" @required(in_array($field,['name','email']))></div>
@endforeach
<div class="col-md-6"><label class="form-label">Status akun<select name="is_active" class="form-select"><option value="1" @selected(!$member || $member->is_active)>Aktif</option><option value="0" @selected($member && !$member->is_active)>Nonaktif</option></select></label></div>
<div class="col-md-6"><label class="form-label">{{ $member ? 'Reset password (kosongkan jika tetap)' : 'Password awal' }}<input name="password" type="password" class="form-control" autocomplete="new-password" minlength="8" @required(!$member)></label></div>
<div class="col-md-6"><label class="form-label">Konfirmasi password<input name="password_confirmation" type="password" class="form-control" autocomplete="new-password" minlength="8" @required(!$member)></label></div>
<div class="col-12"><small class="text-muted">Password minimal 8 karakter: huruf besar, huruf kecil, angka, dan simbol (@$!%*?&).</small></div>
<div class="col-12"><button class="btn btn-primary mt-2">Simpan Pengurus</button></div></div>
