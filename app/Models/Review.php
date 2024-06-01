<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'user_id', 'rating', 'comment', 'pros', 'cons', 'media', 'likes_count', 'dislikes_count'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $review->product->updateRating();
        });

        static::deleted(function ($review) {
            $review->product->updateRating();
        });
    }
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function like(User $user)
    {
        if (!$this->hasLiked($user)) {
            $this->likes()->create(['user_id' => $user->id]);
        }
    }
    public function hasLiked(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function dislikes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Dislike::class);
    }

    public function dislike(User $user)
    {
        if (!$this->hasDisliked($user)) {
            $this->dislikes()->create(['user_id' => $user->id]);
        }
    }

    public function hasDisliked(User $user): bool
    {
        return $this->dislikes()->where('user_id', $user->id)->exists();
    }

    public function updateLikesCount()
    {
        $this->likes_count = $this->likes()->count();
        $this->save();
    }

    public function updateDislikesCount()
    {
        $this->dislikes_count = $this->dislikes()->count();
        $this->save();
    }

}
