<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkYayasanDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'template_id',
        'generated_by',
        'published_by',
        'document_number',
        'issued_date',
        'signer_name',
        'signer_position',
        'publication_notes',
        'rendered_content',
        'status',
        'generated_at',
        'published_at',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'generated_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(SkYayasanRequest::class, 'request_id');
    }

    public function template()
    {
        return $this->belongsTo(SkYayasanTemplate::class, 'template_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
