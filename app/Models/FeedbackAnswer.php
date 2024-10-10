<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'answer', 'feedback_id'];

    public function question()
    {
        return $this->belongsTo(ProductQuestion::class);
    }

    public function feedback()
    {
        return $this->belongsTo(ProductFeedback::class);
    }
}
