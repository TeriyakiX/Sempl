<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressSuggestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'postal_code' => $postalCode,
                'region' => $region,
                'city' => $city,
            ],
        ];
    }
}
