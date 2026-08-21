<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiComponent extends Model
{
    use SoftDeletes;

    protected $fillable = ['ami_instrument_id', 'code', 'name', 'description', 'sort_order'];

    public function instrument()
    {
        return $this->belongsTo(AmiInstrument::class, 'ami_instrument_id');
    }
}
