<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AcademicaProposal extends Model
{
    use HasFactory;

    protected $table = 'academica_proposals';

    protected $fillable = [
        'user_id',
        'filename',
        'path',
        'mime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resetUpdates()
    {
        return $this->hasMany(AcademicaResetUpdate::class, 'academica_proposal_id');
    }
}
