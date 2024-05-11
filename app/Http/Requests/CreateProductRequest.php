<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Поле :attribute является обязательным.',
            'name.string' => 'Поле :attribute должно быть строкой.',
            'name.max' => 'Поле :attribute не должно превышать :max символов.',
            'description.required' => 'Поле :attribute является обязательным.',
            'description.string' => 'Поле :attribute должно быть строкой.',
            'photo.string' => 'Поле :attribute должно быть строкой.',
            'photo.max' => 'Поле :attribute не должно превышать :max символов.',
            'category_id.required' => 'Поле :attribute является обязательным.',
            'category_id.exists' => 'Выбранная :attribute не существует.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'название',
            'description' => 'описание',
            'photo' => 'фото',
            'category_id' => 'категория',
        ];
    }


}
