<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkYayasanImportRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'row_number',
        'excel_no',
        'source_nuist_id',
        'source_nama',
        'source_gelar',
        'source_tempat_lahir',
        'source_tanggal_lahir',
        'source_nip_maarif',
        'source_nuptk',
        'source_nomor_kartanu',
        'source_tmt_pertama',
        'source_masa_kerja',
        'source_pendidikan_terakhir',
        'source_tahun_lulus',
        'source_program_studi',
        'source_mapel_tugas',
        'source_penilaian_kinerja',
        'source_keterangan',
        'matched_user_id',
        'matched_name',
        'is_valid',
        'status_label',
        'validation_errors',
        'user_payload',
        'sk_payload',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'validation_errors' => 'array',
        'user_payload' => 'array',
        'sk_payload' => 'array',
    ];

    public function batch()
    {
        return $this->belongsTo(SkYayasanImportBatch::class, 'batch_id');
    }

    public function matchedUser()
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }
}
