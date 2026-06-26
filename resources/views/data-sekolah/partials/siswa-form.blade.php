<div class="row g-3">
    <div class="col-12">
        <h6 class="mb-1">Sekolah</h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-4">
        <label class="form-label">Madrasah/Sekolah</label>
        <select
            name="madrasah_id"
            class="form-select js-student-school-select"
            required
            {{ in_array($userRole, ['admin', 'admin_spp']) ? 'disabled' : '' }}
        >
            <option value="">Pilih Madrasah</option>
            @foreach($madrasahOptions as $madrasah)
                <option
                    value="{{ $madrasah->id }}"
                    data-scod="{{ $madrasah->scod }}"
                    data-name="{{ $madrasah->name }}"
                    {{ (string) old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId) === (string) $madrasah->id ? 'selected' : '' }}
                >
                    {{ $madrasah->name }}
                </option>
            @endforeach
        </select>
        @if(in_array($userRole, ['admin', 'admin_spp']))
            <input type="hidden" name="madrasah_id" value="{{ old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId) }}">
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">SCOD</label>
        <input type="text" name="scod" class="form-control js-student-scod" value="{{ old('scod', $siswa->scod ?? '') }}" readonly>
    </div>
    <div class="col-md-4">
        <label class="form-label">ASAL SEKOLAH/MADRASAH</label>
        <input type="text" name="asal_sekolah_madrasah" class="form-control js-student-school-name" value="{{ old('asal_sekolah_madrasah', $siswa->nama_madrasah ?? '') }}" readonly>
    </div>

    <div class="col-12 pt-2">
        <h6 class="mb-1">Identitas Siswa</h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-3">
        <label class="form-label">NIS</label>
        <input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">NISN</label>
        <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control" value="{{ old('nik', $siswa->nik ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">NO_KK</label>
        <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk', $siswa->no_kk ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">NAMA_PESERTA_DIDIK</label>
        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $siswa->nama_lengkap ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">JENIS_KELAMIN</label>
        <select name="jenis_kelamin" class="form-select">
            <option value="">Pilih</option>
            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>L</option>
            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>P</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">TEMPAT_LAHIR</label>
        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">TANGGAL_LAHIR</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', isset($siswa) && $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">AGAMA</label>
        <input type="text" name="agama" class="form-control" value="{{ old('agama', $siswa->agama ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">KELAS</label>
        <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $siswa->kelas ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">JURUSAN</label>
        <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $siswa->jurusan ?? '') }}">
    </div>

    <div class="col-12 pt-2">
        <h6 class="mb-1">Alamat dan Kontak</h6>
        <hr class="mt-0">
    </div>

    <div class="col-12">
        <label class="form-label">ALAMAT</label>
        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">DUSUN</label>
        <input type="text" name="dusun" class="form-control" value="{{ old('dusun', $siswa->dusun ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">KELURAHAN</label>
        <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $siswa->kelurahan ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">KECAMATAN</label>
        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $siswa->kecamatan ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">NO_HP_SISWA</label>
        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $siswa->no_hp ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">EMAIL_SISWA</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $siswa->email ?? '') }}">
    </div>

    <div class="col-12 pt-2">
        <h6 class="mb-1">Orang Tua</h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-4">
        <label class="form-label">NAMA_AYAH</label>
        <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->nama_ayah ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">NAMA_IBU</label>
        <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->nama_ibu ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">NO_HP_ORANG_TUA_WALI</label>
        <input type="text" name="no_hp_orang_tua_wali" class="form-control" value="{{ old('no_hp_orang_tua_wali', $siswa->no_hp_orang_tua_wali ?? '') }}">
    </div>
</div>
