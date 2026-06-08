<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkYayasanTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'document_title',
        'document_number_format',
        'description',
        'body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function requests()
    {
        return $this->hasMany(SkYayasanRequest::class, 'template_id');
    }

    public function documents()
    {
        return $this->hasMany(SkYayasanDocument::class, 'template_id');
    }
}
