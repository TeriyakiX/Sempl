<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'app_name' => $this->app_name,
            'email' => $this->email,
            'role' => $this->role,
            'want_advertising' => $this->want_advertising,
            'accept_policy' => $this->accept_policy,
            'people_living_with' => $this->people_living_with,
            'has_children' => $this->has_children,
            'pets' => $this->pets,
            'average_monthly_income' => $this->average_monthly_income,
            'percentage_spent_on_cosmetics' => $this->percentage_spent_on_cosmetics,
            'vk_profile' => $this->vk_profile,
            'telegram_profile' => $this->telegram_profile,
            'profile_photo' => $this->profile_photo,
            'delivery_address' => $this->delivery_address,
            'city' => $this->city,
            'street' => $this->street,
            'house_number' => $this->house_number,
            'apartment_number' => $this->apartment_number,
            'entrance' => $this->entrance,
            'postal_code' => $this->postal_code,
            'full_address' => $this->full_address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'phone' => $this->phone,
        ];
    }
}
