<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductQuestionAnswerResource extends JsonResource
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
            'answer' => $this->answer,
            'feedback_id' => $this->feedback_id, // Если нужно
            'question_id' => $this->question_id,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
