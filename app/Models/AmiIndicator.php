<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiIndicator extends Model
{
    use SoftDeletes;

    protected $fillable = ['ami_item_id', 'code', 'name', 'operational_definition', 'fulfillment_criteria', 'rubric', 'sort_order', 'requires_evidence', 'minimum_evidence_count'];

    public function item()
    {
        return $this->belongsTo(AmiItem::class, 'ami_item_id');
    }
}
