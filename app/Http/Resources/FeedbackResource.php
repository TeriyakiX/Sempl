<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray($request)
    {
        $photos = $this->photos ? json_decode($this->photos, true) : [];
        $videos = $this->videos ? json_decode($this->videos, true) : [];

        $photoUrls = array_map(function ($photo) {
            return asset('storage/' . $photo);
        }, $photos);

        $videoUrls = array_map(function ($video) {
            return asset('storage/' . $video);
        }, $videos);

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'description' => $this->description,
            'pro_1' => $this->pro_1,
            'pro_2' => $this->pro_2,
            'con_1' => $this->con_1,
            'con_2' => $this->con_2,
            'rating' => $this->rating,
            'questions' => ProductQuestionResource::collection($this->questions),
            'answers' => ProductFeedbackAnswerResource::collection($this->productFeedbackAnswers),
            'photos' => $photoUrls,
            'videos' => $videoUrls,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
