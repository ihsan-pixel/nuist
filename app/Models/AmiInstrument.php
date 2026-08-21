<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiInstrument extends Model
{
    use SoftDeletes;

    protected $fillable = ['ami_period_id', 'name', 'code', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function period()
    {
        return $this->belongsTo(AmiPeriod::class, 'ami_period_id');
    }
}
