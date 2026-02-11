<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MgmpGroup extends Model
{
    use HasFactory;

    protected $table = 'mgmp_groups';

    protected $fillable = [
        'name',
        'member_count',
        'logo',
    ];

    public function members()
    {
        return $this->hasMany(MgmpMember::class, 'mgmp_group_id');
    }

    public function reports()
    {
        return $this->hasMany(MgmpReport::class, 'mgmp_group_id');
    }
}
