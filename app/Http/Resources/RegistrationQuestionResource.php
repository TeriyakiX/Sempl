<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationQuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answers' => $this->answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'answer' => $answer->answer,
                ];
            }),
        ];
    }
}
