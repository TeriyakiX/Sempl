<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SampleRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'accepted_terms' => $this->accepted_terms,
            'question1' => $this->question1,
            'question2' => $this->question2,
            'question3' => $this->question3,
            'question4' => $this->question4,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
