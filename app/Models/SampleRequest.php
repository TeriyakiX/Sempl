<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'accepted_terms', 'product_id', 'delivery_status_id' , 'delivery_date' , 'created_at', 'updated_at'
    ];
    protected $dates = [
        'delivery_date',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'sample_request_question')
            ->withPivot('answer')
            ->withTimestamps();
    }
    public function deliveryStatus()
    {
        return $this->belongsTo(DeliveryStatus::class);
    }


}
