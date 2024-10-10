<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    protected $fillable = ['product_id', 'question'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function answers()
    {
        return $this->hasMany(ProductQuestionAnswer::class, 'question_id');
    }
}
