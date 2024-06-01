<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->login,
            'product_id' => $this->product_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'pros' => $this->pros,
            'cons' => $this->cons,
            'media' => $this->media,
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'user_has_liked' => $this->hasLiked(auth()->user()),
            'user_has_disliked' => $this->hasDisliked(auth()->user()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
