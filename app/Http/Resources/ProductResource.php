<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $category = $this->category;
        $subcategory = null;

        if ($category) {
            $mainCategory = $category->parent;

            if ($mainCategory) {
                $subcategory = $category;
                $category = $mainCategory;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'photo' => $this->when($this->photo !== null, asset('storage/' . $this->photo)),
            'category' => $category ? new CategoryResource($category) : null,
            'subcategory' => $subcategory ? new CategoryResource($subcategory) : null,
            'rating' => $this->rating,
            'likes' => $this->likesCount(),
            'dislikes' => $this->dislikesCount(),
            'feedback_count' => $this->feedback_count,
            'is_secret' => $this->is_secret,
            'is_popular' => $this->is_popular, // Добавляем поле "популярный"
            'is_special' => $this->is_special, // Добавляем поле "специальный"
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
