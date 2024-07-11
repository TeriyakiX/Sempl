<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'question_1',
        'question_2',
        'question_3',
        'description',
        'pro_1',
        'pro_2',
        'con_1',
        'con_2',
        'rating',
        'status',
        'delivery_date',
        'photos'
    ];

}
