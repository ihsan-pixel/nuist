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
        'alamat',
        'pemenuhan_beban_kerja_lain',
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
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->nuist_id)) {
                do {
                    $nuistId = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                } while (self::where('nuist_id', $nuistId)->exists());

                $user->nuist_id = $nuistId;
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

    public function presensis()
    {
        return $this->hasMany(\App\Models\Presensi::class, 'user_id');
    }
}
