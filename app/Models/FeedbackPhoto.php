<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback_id',
        'photo_path',
    ];

    public function feedback()
    {
        return $this->belongsTo(ProductFeedback::class);
    }
}
