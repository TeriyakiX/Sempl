<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'answer',];

    public function question()
    {
        return $this->belongsTo(ProductQuestion::class, 'question_id');
    }
}
