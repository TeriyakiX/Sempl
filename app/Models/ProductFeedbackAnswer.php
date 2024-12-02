<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeedbackAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['feedback_id', 'question_id', 'answer_id', 'product_id'];

    public function feedback()
    {
        return $this->belongsTo(ProductFeedback::class, 'feedback_id');
    }

    public function question()
    {
        return $this->belongsTo(FeedbackQuestion::class, 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo(FeedbackAnswer::class, 'answer_id');
    }
}
