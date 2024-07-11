<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $this->merge([
            'want_advertising' => filter_var($this->want_advertising, FILTER_VALIDATE_BOOLEAN),
        ]);
        $this->merge([
            'accept_policy' => filter_var($this->accept_policy, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
    public function rules()
    {
        return [
            'login' => 'required|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birthdate' => 'required|date',
            'app_name' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'people_living_with' => 'nullable|integer',
            'has_children' => 'required|boolean',
            'pets' => 'nullable|string',
            'average_monthly_income' => 'nullable|numeric',
            'percentage_spent_on_cosmetics' => 'nullable|numeric',
            'vk_profile' => 'nullable|string',
            'telegram_profile' => 'nullable|string',
            'delivery_address' => 'required',
            'city' => 'required',
            'street' => 'required',
            'house_number' => 'required',
            'apartment_number' => 'nullable',
            'entrance' => 'nullable',
            'postal_code' => 'required',
            'want_advertising' => 'required|boolean|accepted',
            'accept_policy' => 'required|boolean|accepted',
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
            'people_living_with.integer' => 'Поле Количество проживающих должно быть числом.',
            'has_children.required' => 'Поле Наличие детей является обязательным.',
            'has_children.boolean' => 'Поле Наличие детей должно быть истинным или ложным.',
            'pets.string' => 'Поле Домашние животные должно быть строкой.',
            'average_monthly_income.numeric' => 'Поле Средний месячный доход должно быть числом.',
            'percentage_spent_on_cosmetics.numeric' => 'Поле Процент, потраченный на косметику, должно быть числом.',
            'vk_profile.string' => 'Поле Профиль VK должно быть строкой.',
            'telegram_profile.string' => 'Поле Профиль Telegram должно быть строкой.',
            'profile_photo.image' => 'Поле Фото профиля должно быть изображением.',
            'profile_photo.mimes' => 'Поле Фото профиля должно быть файлом типа: jpeg, png, jpg, gif.',
            'profile_photo.max' => 'Поле Фото профиля не должно превышать 2048 КБ.',
            'delivery_address.required' => 'Поле Адрес доставки является обязательным.',
            'city.required' => 'Поле Город является обязательным.',
            'street.required' => 'Поле Улица является обязательным.',
            'house_number.required' => 'Поле Номер дома является обязательным.',
            'apartment_number.nullable' => 'Поле Номер квартиры может быть пустым.',
            'entrance.nullable' => 'Поле Подъезд может быть пустым.',
            'postal_code.required' => 'Поле Почтовый индекс является обязательным.',
            'want_advertising.required' => 'Поле "Хотите получать рекламу" является обязательным.',
            'accept_policy.required' => 'Поле "Принять политику" является обязательным.',
        ];
    }
}
