<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $category = $this->category;

        if ($category) {
            $mainCategory = $category->parent;

            if ($mainCategory) {
                $subcategory = $category;
                $category = $mainCategory;
            } else {
                $subcategory = null;
            }
        } else {
            $category = null;
            $subcategory = null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'photo' => $this->photo,
            'category' => $category ? new CategoryResource($category) : null,
            'subcategory' => $subcategory ? new CategoryResource($subcategory) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
