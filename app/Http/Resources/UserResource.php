<?php

namespace App\Http\Resources;

use App\Models\RegistrationAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {

        $peopleLivingWith = RegistrationAnswer::find($this->people_living_with_id);
        $hasChildren = RegistrationAnswer::find($this->has_children_id);
        $pets = RegistrationAnswer::find($this->pets_id);
        $averageMonthlyIncome = RegistrationAnswer::find($this->average_monthly_income_id);
        $percentageSpentOnCosmetics = RegistrationAnswer::find($this->percentage_spent_on_cosmetics_id);

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
            'people_living_with' => $peopleLivingWith ? $peopleLivingWith->answer : null,
            'has_children' => $hasChildren ? $hasChildren->answer : null,
            'pets' => $pets ? $pets->answer : null,
            'average_monthly_income' => $averageMonthlyIncome ? $averageMonthlyIncome->answer : null,
            'percentage_spent_on_cosmetics' => $percentageSpentOnCosmetics ? $percentageSpentOnCosmetics->answer : null,
            'vk_profile' => $this->vk_profile,
            'telegram_profile' => $this->telegram_profile,
            'profile_photo' => $this->when($this->profile_photo !== null, asset('storage/' . $this->profile_photo)),
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
            'is_registration_completed' => $this->is_registration_completed,
            'has_ordered_secret_product' => $this->has_ordered_secret_product,
            'can_view_secret_products' => $this->can_view_secret_products,  // Добавлено поле для отображения
        ];
    }
}
