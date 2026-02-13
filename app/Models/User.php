<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\StatusKepegawaian;
use App\Models\Madrasah;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nuist_id',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'kartanu',
        'nip',
        'nuptk',
        'npk',
        'madrasah_id',
        'pendidikan_terakhir',
        'tahun_lulus',
        'program_studi',
        'status_kepegawaian_id',
        'tmt',
        'ketugasan',
        'mengajar',
        'avatar',
        'face_data',
        'face_id',
        'face_registered_at',
        'face_verification_required',
        'alamat',
        'pemenuhan_beban_kerja_lain',
        'madrasah_id_tambahan',
        'password_changed',
        'last_seen',
        'jabatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt' => 'date',
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
        'face_data' => 'array',
        'face_registered_at' => 'datetime',
        'face_verification_required' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->nuist_id)) {
                $user->nuist_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Existing code...

    public function statusKepegawaian()
    {
        return $this->belongsTo(StatusKepegawaian::class, 'status_kepegawaian_id');
    }

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }

    public function madrasahTambahan()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id_tambahan');
    }

    public function presensis()
    {
        return $this->hasMany(\App\Models\Presensi::class, 'user_id');
    }

    /**
     * Relationship: 1 user -> 1 talenta peserta
     * Use this in views/controllers like: auth()->user()->talentaPeserta
     */
    public function talentaPeserta()
    {
        return $this->hasOne(\App\Models\Talenta::class, 'user_id');
    }
}
