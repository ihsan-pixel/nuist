<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['kategori','pertanyaan','skor_ya','skor_tidak','choice_scores'];

    protected $casts = [
        'choice_scores' => 'array',
        'choice_texts' => 'array',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
