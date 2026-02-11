<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MgmpMember extends Model
{
    use HasFactory;

    protected $table = 'mgmp_members';

    protected $fillable = [
        'user_id',
        'mgmp_group_id',
        'name',
        'sekolah',
        'madrasah_id',
        'email'
    ];

    public function mgmpGroup()
    {
        return $this->belongsTo(MgmpGroup::class, 'mgmp_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
