<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppOperatorRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'madrasah_id',
        'name',
        'email',
        'no_hp',
        'jabatan',
        'status',
        'review_notes',
        'reviewed_by',
        'approved_user_id',
        'submitted_at',
        'reviewed_at',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function madrasah()
    {
        return $this->belongsTo(Madrasah::class, 'madrasah_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedUser()
    {
        return $this->belongsTo(User::class, 'approved_user_id');
    }
}
