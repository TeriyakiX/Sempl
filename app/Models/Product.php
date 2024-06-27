<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'photo', 'category_id', 'is_tested', 'likes', 'dislikes' ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function updateRating()
    {
        $totalReviews = $this->reviews()->count();
        $totalRating = $this->reviews()->sum('rating');

        if ($totalReviews > 0) {
            $averageRating = $totalRating / $totalReviews;
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
}
