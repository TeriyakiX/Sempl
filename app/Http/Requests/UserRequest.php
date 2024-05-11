<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login' => 'required|unique:users',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birthdate' => 'required|date',
            'app_name' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Поле Логин является обязательным.',
            'login.unique' => 'Пользователь с таким Логином уже существует.',
            'password.required' => 'Поле Пароль является обязательным.',
            'first_name.required' => 'Поле Имя является обязательным.',
            'last_name.required' => 'Поле Фамилия является обязательным.',
            'gender.required' => 'Поле Пол является обязательным.',
            'birthdate.required' => 'Поле Дата рождения является обязательным.',
            'birthdate.date' => 'Поле Дата рождения должно быть датой.',
            'app_name.required' => 'Поле Название приложения является обязательным.',
            'email.required' => 'Поле Email является обязательным.',
            'email.email' => 'Поле Email должно быть действительным адресом электронной почты.',
            'email.unique' => 'Пользователь с таким Email уже существует.',
            'address.required' => 'Поле Адрес является обязательным.',
            'role' => 'in:0',
            'want_advertising' => 'required|boolean|accepted',
            'accept_policy' => 'required|boolean|accepted',
        ];
    }
}
