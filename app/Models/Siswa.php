<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'siswa';

    protected $fillable = [
        'madrasah_id',
        'scod',
        'nis',
        'nisn',
        'nik',
        'no_kk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'nama_orang_tua_wali',
        'email',
        'email_orang_tua_wali',
        'no_hp',
        'no_hp_orang_tua_wali',
        'kelas',
        'jurusan',
        'tahun_masuk',
        'jenis_tinggal',
        'alat_transportasi',
        'nama_madrasah',
        'alamat',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'nama_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'nama_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'nama_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'password',
        'is_active',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }

    public function sppBills()
    {
        return $this->hasMany(SppSiswaBill::class);
    }

    public function sppTransactions()
    {
        return $this->hasMany(SppSiswaTransaction::class);
    }

    public function setNamaLengkapAttribute($value): void
    {
        $normalized = $this->normalizeNullableString($value);
        $this->attributes['nama_lengkap'] = $normalized ? Str::upper($normalized) : null;
    }

    public function setNisAttribute($value): void
    {
        $this->attributes['nis'] = $this->normalizeNullableString($value);
    }

    public function setScodAttribute($value): void
    {
        $this->attributes['scod'] = $this->normalizeNullableString($value);
    }

    public function setNisnAttribute($value): void
    {
        $this->attributes['nisn'] = $this->normalizeNullableString($value);
    }

    public function setNikAttribute($value): void
    {
        $this->attributes['nik'] = $this->normalizeNullableString($value);
    }

    public function setNoKkAttribute($value): void
    {
        $this->attributes['no_kk'] = $this->normalizeNullableString($value);
    }

    public function setJenisKelaminAttribute($value): void
    {
        $normalized = strtoupper(trim((string) $value));
        $this->attributes['jenis_kelamin'] = in_array($normalized, ['L', 'P'], true) ? $normalized : null;
    }

    public function setTempatLahirAttribute($value): void
    {
        $this->attributes['tempat_lahir'] = $this->normalizeNullableString($value);
    }

    public function setAgamaAttribute($value): void
    {
        $this->attributes['agama'] = $this->normalizeNullableString($value);
    }

    public function setNamaOrangTuaWaliAttribute($value): void
    {
        $this->attributes['nama_orang_tua_wali'] = $this->normalizeNullableString($value);
    }

    public function setEmailAttribute($value): void
    {
        $normalized = $this->normalizeNullableString($value);
        $this->attributes['email'] = $normalized ? Str::lower($normalized) : null;
    }

    public function setEmailOrangTuaWaliAttribute($value): void
    {
        $normalized = $this->normalizeNullableString($value);
        $this->attributes['email_orang_tua_wali'] = $normalized ? Str::lower($normalized) : null;
    }

    public function setNoHpAttribute($value): void
    {
        $this->attributes['no_hp'] = $this->normalizeNullableString($value);
    }

    public function setNoHpOrangTuaWaliAttribute($value): void
    {
        $this->attributes['no_hp_orang_tua_wali'] = $this->normalizeNullableString($value);
    }

    public function setKelasAttribute($value): void
    {
        $normalized = $this->normalizeNullableString($value);
        $this->attributes['kelas'] = $normalized ? Str::upper($normalized) : null;
    }

    public function setJurusanAttribute($value): void
    {
        $normalized = $this->normalizeNullableString($value);
        $this->attributes['jurusan'] = $normalized ? Str::upper($normalized) : null;
    }

    public function setTahunMasukAttribute($value): void
    {
        $this->attributes['tahun_masuk'] = $this->normalizeNullableString($value);
    }

    public function setJenisTinggalAttribute($value): void
    {
        $this->attributes['jenis_tinggal'] = $this->normalizeNullableString($value);
    }

    public function setAlatTransportasiAttribute($value): void
    {
        $this->attributes['alat_transportasi'] = $this->normalizeNullableString($value);
    }

    public function setNamaMadrasahAttribute($value): void
    {
        $this->attributes['nama_madrasah'] = $this->normalizeNullableString($value);
    }

    public function setAlamatAttribute($value): void
    {
        $this->attributes['alamat'] = $this->normalizeNullableString($value);
    }

    public function setDusunAttribute($value): void
    {
        $this->attributes['dusun'] = $this->normalizeNullableString($value);
    }

    public function setKelurahanAttribute($value): void
    {
        $this->attributes['kelurahan'] = $this->normalizeNullableString($value);
    }

    public function setKecamatanAttribute($value): void
    {
        $this->attributes['kecamatan'] = $this->normalizeNullableString($value);
    }

    public function setKodePosAttribute($value): void
    {
        $this->attributes['kode_pos'] = $this->normalizeNullableString($value);
    }

    public function setNamaAyahAttribute($value): void
    {
        $this->attributes['nama_ayah'] = $this->normalizeNullableString($value);
    }

    public function setPendidikanAyahAttribute($value): void
    {
        $this->attributes['pendidikan_ayah'] = $this->normalizeNullableString($value);
    }

    public function setPekerjaanAyahAttribute($value): void
    {
        $this->attributes['pekerjaan_ayah'] = $this->normalizeNullableString($value);
    }

    public function setPenghasilanAyahAttribute($value): void
    {
        $this->attributes['penghasilan_ayah'] = $this->normalizeNullableString($value);
    }

    public function setNamaIbuAttribute($value): void
    {
        $this->attributes['nama_ibu'] = $this->normalizeNullableString($value);
    }

    public function setPendidikanIbuAttribute($value): void
    {
        $this->attributes['pendidikan_ibu'] = $this->normalizeNullableString($value);
    }

    public function setPekerjaanIbuAttribute($value): void
    {
        $this->attributes['pekerjaan_ibu'] = $this->normalizeNullableString($value);
    }

    public function setPenghasilanIbuAttribute($value): void
    {
        $this->attributes['penghasilan_ibu'] = $this->normalizeNullableString($value);
    }

    public function setNamaWaliAttribute($value): void
    {
        $this->attributes['nama_wali'] = $this->normalizeNullableString($value);
    }

    public function setPendidikanWaliAttribute($value): void
    {
        $this->attributes['pendidikan_wali'] = $this->normalizeNullableString($value);
    }

    public function setPekerjaanWaliAttribute($value): void
    {
        $this->attributes['pekerjaan_wali'] = $this->normalizeNullableString($value);
    }

    public function setPenghasilanWaliAttribute($value): void
    {
        $this->attributes['penghasilan_wali'] = $this->normalizeNullableString($value);
    }

    private function normalizeNullableString($value): ?string
    {
        $normalized = trim((string) $value);

        return $normalized !== '' ? $normalized : null;
    }
}
