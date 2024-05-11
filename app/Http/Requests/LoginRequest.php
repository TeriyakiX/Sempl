<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Поле :attribute является обязательным.',
            'email.email' => 'Поле :attribute должно быть действительным адресом электронной почты.',
            'password.required' => 'Поле :attribute является обязательным.',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'адрес электронной почты',
            'password' => 'пароль',
        ];
    }
}
