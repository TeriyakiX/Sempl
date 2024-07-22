<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray($request)
    {

        $photos = $this->photos ? explode(',', $this->photos) : [];
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'question_1' => [
                'id' => $this->fixedQuestion1->id ?? null,
                'question' => $this->fixedQuestion1->question ?? null,
                'answer' => $this->fixedQuestion1->answer ?? null
            ],
            'question_2' => [
                'id' => $this->fixedQuestion2->id ?? null,
                'question' => $this->fixedQuestion2->question ?? null,
                'answer' => $this->fixedQuestion2->answer ?? null
            ],
            'question_3' => [
                'id' => $this->fixedQuestion3->id ?? null,
                'question' => $this->fixedQuestion3->question ?? null,
                'answer' => $this->fixedQuestion3->answer ?? null
            ],
            'description' => $this->description,
            'pro_1' => $this->pro_1,
            'pro_2' => $this->pro_2,
            'con_1' => $this->con_1,
            'con_2' => $this->con_2,
            'rating' => $this->rating,
            'status' => $this->status,
            'delivery_date' => $this->delivery_date,
            'photos' => $this->when($this->photos !== null, asset('storage/' . $this->photos)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'liked_by_user' => $this->liked_by_user,
            'disliked_by_user' => $this->disliked_by_user,
        ];

    }
}
