<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'users'; // kalau datanya masih di tabel users

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'madrasah_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
