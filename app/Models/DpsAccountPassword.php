<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpsAccountPassword extends Model
{
    use HasFactory;

    protected $table = 'dps_account_passwords';

    protected $fillable = [
        'user_id',
        'password_encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

