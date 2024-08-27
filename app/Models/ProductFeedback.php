<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'fixed_question_1',
        'fixed_question_2',
        'fixed_question_3',
        'description',
        'pro_1',
        'pro_2',
        'con_1',
        'con_2',
        'rating',
        'status',
        'delivery_date',
        'photos',
        'likes',
        'dislikes',
        'liked_by_user',
        'disliked_by_user'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_AWAITING_REVIEW = 'awaiting_review';
    const STATUS_COMPLETED = 'completed';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(FeedbackQuestion::class);
    }

    public function addLike()
    {
        if (!$this->isLiked()) {
            $this->likes++;
            $this->dislikes = 0;
            $this->save();
        } else {
            $this->removeLike();
        }
    }

    public function removeLike()
    {
        if ($this->isLiked()) {
            $this->likes--;
            $this->save();
        }
    }

    public function isLiked()
    {
        return $this->likes > 0;
    }

    public function addDislike()
    {
        if (!$this->isDisliked()) {
            $this->dislikes++;
            $this->likes = 0;
            $this->save();
        } else {
            $this->removeDislike();
        }
    }

    public function removeDislike()
    {
        if ($this->isDisliked()) {
            $this->dislikes--;
            $this->save();
        }
    }

    public function isDisliked()
    {
        return $this->dislikes > 0;
    }

    public function fixedQuestion1()
    {
        return $this->belongsTo(FeedbackAnswer::class, 'fixed_question_1');
    }

    public function fixedQuestion2()
    {
        return $this->belongsTo(FeedbackAnswer::class, 'fixed_question_2');
    }

    public function fixedQuestion3()
    {
        return $this->belongsTo(FeedbackAnswer::class, 'fixed_question_3');
    }

    public function photos()
    {
        return $this->hasMany(FeedbackPhoto::class, 'feedback_id');
    }
}
