<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKepegawaian extends Model
{
    use HasFactory;

    protected $table = 'status_kepegawaian';

    protected $fillable = [
        'name',
    ];
}
