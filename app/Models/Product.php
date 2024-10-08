<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'photo', 'category_id', 'is_tested', 'likes', 'dislikes','expected', 'feedback_count', 'is_secret' ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function feedbacks()
    {
        return $this->hasMany(ProductFeedback::class);
    }
    public function updateFeedbackCount()
    {
        $this->feedback_count = $this->feedbacks()->count();
        $this->save();
    }

    // Определение связи с вопросами
    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }



    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function updateRating()
    {
        $totalFeedbacks = $this->feedbacks()->count();
        $totalRating = $this->feedbacks()->sum('rating');

        if ($totalFeedbacks > 0) {
            $averageRating = $totalRating / $totalFeedbacks;
            $this->rating = $averageRating;
            $this->is_tested = true;
        } else {
            $this->rating = 0;
            $this->is_tested = false;
        }

        $this->save();

        return $this->rating;
    }

    public function testedBy()
    {
        return $this->belongsToMany(User::class, 'tested_products', 'product_id', 'user_id');
    }

    public function userLikes()
    {
        return $this->hasMany(ProductUserLike::class);
    }

    public function likesCount()
    {
        return $this->userLikes()->where('like', true)->count();
    }

    public function dislikesCount()
    {
        return $this->userLikes()->where('like', false)->count();
    }

    public function deliveryStatuses()
    {
        return $this->hasMany(DeliveryStatus::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'product_purchase', 'product_id', 'purchase_id');
    }

}
