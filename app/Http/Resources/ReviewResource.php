<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'question_1' => $this->question_1,
            'question_2' => $this->question_2,
            'question_3' => $this->question_3,
            'description' => $this->description,
            'pro_1' => $this->pro_1,
            'pro_2' => $this->pro_2,
            'con_1' => $this->con_1,
            'con_2' => $this->con_2,
            'photos' => $this->photos ? json_decode($this->photos, true) : [],
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
