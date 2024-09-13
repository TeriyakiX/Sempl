<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'has_children' => filter_var($this->has_children, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
    public function rules()
    {
        return [
            'login' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'app_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user()->id,
            'address' => 'required|string|max:255',
            'has_children' => 'required|boolean',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'password' => 'sometimes|string|min:8|confirmed',
            'profile_photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
