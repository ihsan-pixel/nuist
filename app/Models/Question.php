<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['kategori','pertanyaan','skor_ya','skor_tidak'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
