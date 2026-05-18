<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicaResetUpdate extends Model
{
    use HasFactory;

    protected $table = 'academica_reset_updates';

    protected $fillable = [
        'academica_proposal_id',
        'user_id',
        'title',
        'progress_percent',
        'progress_note',
    ];

    public function proposal()
    {
        return $this->belongsTo(AcademicaProposal::class, 'academica_proposal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(AcademicaResetUpdateFile::class, 'academica_reset_update_id');
    }
}
