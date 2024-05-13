<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'delivery_status' => 'required|in:pending,shipped,delivered',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Необходимо выбрать продукт для заказа.',
            'product_id.exists' => 'Выбранный продукт недействителен.',
            'delivery_status.required' => 'Необходимо указать статус доставки.',
            'delivery_status.in' => 'Недопустимое значение для статуса доставки.',
            'photo.image' => 'Изображение должно быть в формате изображения.',
            'photo.mimes' => 'Изображение должно быть в форматах: jpeg, png, jpg, gif.',
            'photo.max' => 'Изображение не должно превышать 2048 КБ.',
        ];
    }
}
