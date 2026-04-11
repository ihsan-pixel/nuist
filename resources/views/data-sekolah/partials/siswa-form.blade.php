<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">NIS</label>
        <input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap Siswa</label>
        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $siswa->nama_lengkap ?? '') }}" placeholder="Akan disimpan kapital" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Orang Tua/Wali</label>
        <input type="text" name="nama_orang_tua_wali" class="form-control" value="{{ old('nama_orang_tua_wali', $siswa->nama_orang_tua_wali ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Kelas</label>
        <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $siswa->kelas ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jurusan</label>
        <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $siswa->jurusan ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email Siswa</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $siswa->email ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email Orang Tua/Wali</label>
        <input type="email" name="email_orang_tua_wali" class="form-control" value="{{ old('email_orang_tua_wali', $siswa->email_orang_tua_wali ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">No HP Siswa</label>
        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $siswa->no_hp ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">No HP Orang Tua/Wali</label>
        <input type="text" name="no_hp_orang_tua_wali" class="form-control" value="{{ old('no_hp_orang_tua_wali', $siswa->no_hp_orang_tua_wali ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Madrasah/Sekolah</label>
        <select name="madrasah_id" class="form-select" required {{ $userRole === 'admin' ? 'disabled' : '' }}>
            <option value="">Pilih Madrasah</option>
            @foreach($madrasahOptions as $madrasah)
                <option value="{{ $madrasah->id }}" {{ (string) old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId) === (string) $madrasah->id ? 'selected' : '' }}>
                    {{ $madrasah->name }}
                </option>
            @endforeach
        </select>
        @if($userRole === 'admin')
            <input type="hidden" name="madrasah_id" value="{{ old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId) }}">
        @endif
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', isset($siswa) ? $siswa->is_active : true) ? 'checked' : '' }}>
            <label class="form-check-label">Akun aktif</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
    </div>
</div>
