<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAkhirTahunKepalaSekolah extends Model
{
    use HasFactory;

    protected $table = 'laporan_akhir_tahun_kepala_sekolah';

    protected $fillable = [
        'user_id',
        'tahun_pelaporan',
        'nama_kepala_sekolah',
        'nip',
        'nuptk',
        'nama_madrasah',
        'alamat_madrasah',
        'jumlah_guru',
        'jumlah_siswa',
        'jumlah_kelas',
        'prestasi_madrasah',
        'kendala_utama',
        'program_kerja_tahun_depan',
        'anggaran_digunakan',
        'saran_dan_masukan',
        'tanggal_laporan',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tanggal_laporan' => 'date',
    ];

    /**
     * Get the user that owns the laporan record
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
