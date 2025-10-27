<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitLog extends Model
{
    protected $fillable = [
        'hash',
        'message',
        'author',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
