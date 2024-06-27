<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login' => 'required|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'birthdate' => 'required|date',
            'app_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user()->id,
            'address' => 'required|string|max:255',
            'people_living_with' => 'nullable|integer',
            'has_children' => 'required|boolean',
            'pets' => 'nullable|string|max:255',
            'average_monthly_income' => 'nullable|numeric',
            'percentage_spent_on_cosmetics' => 'nullable|numeric',
            'vk_profile' => 'nullable|string|max:255',
            'telegram_profile' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delivery_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:255',
            'apartment_number' => 'nullable|string|max:255',
            'entrance' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:255',
        ];
    }
}
