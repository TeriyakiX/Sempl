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
            'photo' => $this->photo,
            'category' => $category ? new CategoryResource($category) : null,
            'subcategory' => $subcategory ? new CategoryResource($subcategory) : null,
            'likes' => $this->likesCount(),
            'dislikes' => $this->dislikesCount(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
