<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmiEvidence extends Model
{
    use SoftDeletes;
    protected $fillable = ['ami_school_response_id', 'title', 'evidence_type', 'google_drive_url', 'description', 'document_year', 'responsible_name', 'verification_status', 'auditor_notes'];

    public function schoolResponse()
    {
        return $this->belongsTo(AmiSchoolResponse::class, 'ami_school_response_id');
    }
}
