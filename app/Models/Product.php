<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'photo', 'category_id'];

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
        } else {
            $this->rating = 0;
        }

        $this->save();

        return $this->rating;
    }
}
