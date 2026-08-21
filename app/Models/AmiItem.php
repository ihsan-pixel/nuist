<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['ami_component_id', 'code', 'name', 'description', 'sort_order'];

    public function component()
    {
        return $this->belongsTo(AmiComponent::class, 'ami_component_id');
    }
}
