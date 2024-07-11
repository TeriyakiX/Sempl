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
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_product', 'purchase_id', 'product_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAwaitingReview()
    {
        return $this->status === 'awaiting_review';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
}
