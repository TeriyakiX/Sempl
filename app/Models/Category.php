<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function getAllSubcategories()
    {
        return $this->subcategories->flatMap(function ($subcategory) {
            return [$subcategory, $subcategory->getAllSubcategories()];
        });
    }
}
