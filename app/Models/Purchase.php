<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_address',
        'city',
        'street',
        'house_number',
        'apartment_number',
        'entrance',
        'postal_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_product')->withTimestamps();
    }
}
