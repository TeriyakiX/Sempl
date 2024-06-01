<?php

namespace App\Http\Resources;

use App\Models\DeliveryStatus;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class SampleRequestResource extends JsonResource
{
    public function toArray($request)
    {
        Log::info('SampleRequestResource toArray method called', [
            'delivery_date' => $this->delivery_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $deliveryDate = $this->delivery_date;
        if (is_string($deliveryDate)) {
            $deliveryDate = Carbon::parse($deliveryDate);
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'accepted_terms' => $this->accepted_terms,
            'delivery_status' => $this->deliveryStatus ? $this->deliveryStatus->name : null,
            'delivery_date' => $deliveryDate instanceof Carbon ? $deliveryDate->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at instanceof Carbon ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at instanceof Carbon ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
