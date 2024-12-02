<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeedbackAnswerResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'question_id' => $this->question_id,
            'answer_id' => $this->answer_id,
            'answer' => $this->answer,
        ];
    }
}
