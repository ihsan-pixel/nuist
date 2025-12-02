<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPDBPendaftar extends Model
{
    use HasFactory;
    protected $table = 'ppdb_pendaftar';
    protected $fillable = [
        'ppdb_setting_id',
        'ppdb_jalur_id',
        // 'jalur', // Commented out until migration is run
        'nama_lengkap',
        'tempat_lahir',
        'jenis_kelamin',
        'agama',
        'nisn',
        'nik',
        'asal_sekolah',
        'npsn_sekolah_asal',
        'tahun_lulus',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'nomor_hp_ayah',
        'nomor_hp_ibu',
        'alamat_lengkap',
        'status_keluarga',
        'ppdb_nomor_whatsapp_siswa',
        // 'ppdb_nomor_whatsapp_wali', // Column dropped
        'ppdb_email_siswa',
        'jurusan_pilihan',
        'rencana_lulus',
        'berkas_kk',
        'berkas_ijazah',
        'berkas_akta_kelahiran',
        'berkas_ktp_ayah',
        'berkas_ktp_ibu',
        'berkas_raport',
        'berkas_sertifikat_prestasi',
        'berkas_kip_pkh',
        'berkas_bukti_domisili',
        'berkas_surat_mutasi',
        'berkas_surat_keterangan_lulus',
        'berkas_skl',
        'status',
        'nomor_pendaftaran',
        'nilai',
        'rata_rata_nilai_raport',
        'nomor_ijazah',
        'nomor_skhun',
        'nomor_peserta_un',
        'ranking',
        'skor_nilai',
        'skor_prestasi',
        'skor_domisili',
        'skor_dokumen',
        'skor_total',
        'is_inden',
        'surat_keterangan_sementara',
        'otp_code',
        'otp_expires_at',
        'otp_verified_at',
        'catatan_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_tanggal',
        'diseleksi_oleh',
        'diseleksi_tanggal',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'diverifikasi_tanggal' => 'datetime',
        'diseleksi_tanggal' => 'datetime',
        'otp_expires_at' => 'datetime',
        'otp_verified_at' => 'datetime',
    ];

    /**
     * Relasi ke PPDBSetting
     */
    public function ppdbSetting()
    {
        return $this->belongsTo(PPDBSetting::class, 'ppdb_setting_id');
    }

    /**
     * Accessor untuk jalur sebagai object
     */
    public function getJalurAttribute($value)
    {
        return (object) ['nama_jalur' => $value, 'keterangan' => ''];
    }

    /**
     * Relasi ke User (verifikator)
     */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    /**
     * Relasi ke User (penyeleksi)
     */
    public function penyeleksi()
    {
        return $this->belongsTo(User::class, 'diseleksi_oleh');
    }

    /**
     * Relasi ke Verifikasi (jika ada tabel terpisah)
     */
    public function verifikasis()
    {
        return $this->hasMany(PPDBVerifikasi::class, 'ppdb_pendaftar_id');
    }

    /**
     * Scope untuk pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk verifikasi
     */
    public function scopeVerifikasi($query)
    {
        return $query->where('status', 'verifikasi');
    }

    /**
     * Scope untuk lulus
     */
    public function scopeLulus($query)
    {
        return $query->where('status', 'lulus');
    }

    /**
     * Scope untuk tidak lulus
     */
    public function scopeTidakLulus($query)
    {
        return $query->where('status', 'tidak_lulus');
    }

    /**
     * Cek apakah sudah diverifikasi
     */
    public function isVerified()
    {
        return $this->status !== 'pending';
    }

    /**
     * Cek apakah hasil seleksi sudah keluar
     */
    public function isSelected()
    {
        return in_array($this->status, ['lulus', 'tidak_lulus']);
    }

    /**
     * Generate nomor kartu pendaftaran
     */
    public function getKartuPendaftaranAttribute()
    {
        return $this->nomor_pendaftaran ?? 'N/A';
    }

    /**
     * Hitung skor otomatis berdasarkan data yang ada
     */
    public function hitungSkor()
    {
        $skor = 0;

        // Skor Nilai Akademik
        $nilai = $this->rata_rata_nilai_raport ?? $this->nilai ?? 0;
        if ($nilai >= 90) {
            $this->skor_nilai = 40;
        } elseif ($nilai >= 85) {
            $this->skor_nilai = 35;
        } elseif ($nilai >= 80) {
            $this->skor_nilai = 30;
        } elseif ($nilai >= 70) {
            $this->skor_nilai = 20;
        } else {
            $this->skor_nilai = 0;
        }

        // Skor Prestasi
        if ($this->berkas_sertifikat_prestasi) {
            // Logika sederhana: jika ada sertifikat, berikan skor berdasarkan tingkat
            // Dalam implementasi nyata, mungkin perlu OCR atau input manual
            $this->skor_prestasi = 20; // Default kabupaten
        } else {
            $this->skor_prestasi = 0;
        }

        // Skor Afirmasi
        $this->skor_domisili = 0;
        if ($this->berkas_kip_pkh) {
            $this->skor_domisili += 20; // KIP/PKH
        }
        if ($this->berkas_bukti_domisili) {
            $this->skor_domisili += 15; // Domisili dalam radius
        }

        // Skor Dokumen Lengkap
        $dokumenWajib = ['berkas_kk', 'berkas_ijazah', 'berkas_akta_kelahiran'];
        $dokumenAda = 0;
        foreach ($dokumenWajib as $dokumen) {
            if ($this->$dokumen) {
                $dokumenAda++;
            }
        }

        if ($dokumenAda == count($dokumenWajib)) {
            $this->skor_dokumen = 10;
        } elseif ($dokumenAda >= 1) {
            $this->skor_dokumen = 5;
        } else {
            $this->skor_dokumen = 0;
        }

        // Skor Inden
        if ($this->is_inden && $this->berkas_raport) {
            $this->skor_dokumen += 15;
        }

        // Total Skor
        $this->skor_total = $this->skor_nilai + $this->skor_prestasi + $this->skor_domisili + $this->skor_dokumen;

        $this->save();
    }

    /**
     * Scope untuk ranking berdasarkan skor_total
     */
    public function scopeRanking($query)
    {
        return $query->orderByDesc('skor_total')->orderBy('created_at');
    }

    /**
     * Generate OTP code
     */
    public function generateOTP()
    {
        $this->otp_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_expires_at = now()->addMinutes(10); // OTP berlaku 10 menit
        $this->save();

        return $this->otp_code;
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP($code)
    {
        if ($this->otp_code === $code && $this->otp_expires_at > now()) {
            $this->otp_verified_at = now();
            $this->otp_code = null; // Hapus OTP setelah diverifikasi
            $this->otp_expires_at = null;
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is expired
     */
    public function isOTPExpired()
    {
        return $this->otp_expires_at && $this->otp_expires_at < now();
    }
}
