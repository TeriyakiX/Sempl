<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray($request)
    {
        $media = $this->media ? json_decode($this->media, true) : [];

        $mediaUrls = array_map(function ($mediaItem) {
            return [
                'type' => $mediaItem['type'],
                'url' => asset('storage/' . $mediaItem['path']), // Формируем правильный путь
            ];
        }, $media);

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
            'media' => $mediaUrls, // Объединенное медиа
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
