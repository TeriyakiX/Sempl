<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'product_id'];

    public function answers()
    {
        return $this->hasMany(FeedbackAnswer::class, 'question_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
