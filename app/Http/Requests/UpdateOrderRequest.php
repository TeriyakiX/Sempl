<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules()
    {
        return [
            'delivery_status' => 'required|in:pending,shipped,delivered',
            'review' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'delivery_status.required' => 'Поле "Статус доставки" является обязательным.',
            'delivery_status.in' => 'Неверное значение для поля "Статус доставки". Допустимые значения: pending, shipped, delivered.',
            'review.string' => 'Поле "Отзыв" должно быть строкой.',
            'photo.image' => 'Поле "Фотография" должно быть изображением.',
            'photo.mimes' => 'Фотография должна быть в формате jpeg, png, jpg или gif.',
            'photo.max' => 'Размер фотографии не должен превышать 2 МБ.',
        ];
    }
}
