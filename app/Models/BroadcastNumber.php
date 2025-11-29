<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id',
        'whatsapp_number',
        'description',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class);
    }
}
