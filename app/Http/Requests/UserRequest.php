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
            'gender' => 'required|in:male,female,unknown',
            'birthdate' => 'required|date_format:Y-m-d|before_or_equal:today',
            'app_name' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'people_living_with_id' => 'required|exists:registration_answers,id',
            'has_children_id' => 'required|exists:registration_answers,id',
            'pets_id' => 'required|exists:registration_answers,id',
            'average_monthly_income_id' => 'required|exists:registration_answers,id',
            'percentage_spent_on_cosmetics_id' => 'required|exists:registration_answers,id',
            'vk_profile' => 'nullable|string',
            'telegram_profile' => 'nullable|string',
            'city' => 'required|string|regex:/^[A-Za-zА-Яа-я\s\-]+$/u',
            'street' => 'required|string|regex:/^[A-Za-zА-Яа-я\s\-]+$/u',
            'house_number' => 'required|string|regex:/^\d+$/',
            'apartment_number' => 'nullable|string|regex:/^\d*$/',
            'entrance' => 'nullable|string|regex:/^\d*$/',
            'postal_code' => 'required|string|regex:/^\d{5}$/',
            'want_advertising' => 'required|boolean|accepted',
            'accept_policy' => 'required|boolean|accepted',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Поле Логин является обязательным.',
            'login.unique' => 'Пользователь с таким Логином уже существует.',
            'first_name.required' => 'Поле Имя является обязательным.',
            'first_name.string' => 'Поле Имя должно быть строкой.',
            'last_name.required' => 'Поле Фамилия является обязательным.',
            'last_name.string' => 'Поле Фамилия должно быть строкой.',
            'gender.required' => 'Поле Пол является обязательным.',
            'gender.in' => 'Поле Пол должно иметь одно из значений: male, female, unknown.',
            'birthdate.required' => 'Поле Дата рождения является обязательным.',
            'birthdate.date_format' => 'Поле Дата рождения должно быть в формате YYYY-MM-DD.',
            'birthdate.before_or_equal' => 'Дата рождения не может быть в будущем.',
            'app_name.required' => 'Поле Название приложения является обязательным.',
            'app_name.string' => 'Поле Название приложения должно быть строкой.',
            'email.required' => 'Поле Email является обязательным.',
            'email.email' => 'Поле Email должно быть действительным адресом электронной почты.',
            'email.unique' => 'Пользователь с таким Email уже существует.',
            'address.required' => 'Поле Адрес является обязательным.',
            'address.string' => 'Поле Адрес должно быть строкой.',
            'people_living_with_id.required' => 'Поле Количество проживающих является обязательным.',
            'people_living_with_id.exists' => 'Указанный ID количества проживающих не существует.',
            'has_children_id.required' => 'Поле Наличие детей является обязательным.',
            'has_children_id.exists' => 'Указанный ID наличия детей не существует.',
            'pets_id.required' => 'Поле Домашние животные является обязательным.',
            'pets_id.exists' => 'Указанный ID домашних животных не существует.',
            'average_monthly_income_id.required' => 'Поле Средний месячный доход является обязательным.',
            'average_monthly_income_id.exists' => 'Указанный ID среднего месячного дохода не существует.',
            'percentage_spent_on_cosmetics_id.required' => 'Поле Процент, потраченный на косметику, является обязательным.',
            'percentage_spent_on_cosmetics_id.exists' => 'Указанный ID процента, потраченного на косметику, не существует.',
            'vk_profile.string' => 'Поле Профиль VK должно быть строкой.',
            'telegram_profile.string' => 'Поле Профиль Telegram должно быть строкой.',
            'city.required' => 'Поле Город является обязательным.',
            'city.string' => 'Поле Город должно быть строкой.',
            'city.regex' => 'Поле Город содержит недопустимые символы.',
            'street.required' => 'Поле Улица является обязательным.',
            'street.string' => 'Поле Улица должно быть строкой.',
            'street.regex' => 'Поле Улица содержит недопустимые символы.',
            'house_number.required' => 'Поле Номер дома является обязательным.',
            'house_number.string' => 'Поле Номер дома должно быть строкой.',
            'house_number.regex' => 'Поле Номер дома должно содержать только цифры.',
            'apartment_number.nullable' => 'Поле Номер квартиры может быть пустым.',
            'apartment_number.string' => 'Поле Номер квартиры должно быть строкой.',
            'apartment_number.regex' => 'Поле Номер квартиры должно содержать только цифры.',
            'entrance.nullable' => 'Поле Подъезд может быть пустым.',
            'entrance.string' => 'Поле Подъезд должно быть строкой.',
            'entrance.regex' => 'Поле Подъезд должно содержать только цифры.',
            'postal_code.required' => 'Поле Почтовый индекс является обязательным.',
            'postal_code.string' => 'Поле Почтовый индекс должно быть строкой.',
            'postal_code.regex' => 'Поле Почтовый индекс должно состоять из 5 цифр.',
            'want_advertising.required' => 'Поле "Хотите получать рекламу" является обязательным.',
            'want_advertising.boolean' => 'Поле "Хотите получать рекламу" должно быть истинным или ложным.',
            'want_advertising.accepted' => 'Поле "Хотите получать рекламу" должно быть принято.',
            'accept_policy.required' => 'Поле "Принять политику" является обязательным.',
            'accept_policy.boolean' => 'Поле "Принять политику" должно быть истинным или ложным.',
            'accept_policy.accepted' => 'Поле "Принять политику" должно быть принято.',
        ];
    }
}
