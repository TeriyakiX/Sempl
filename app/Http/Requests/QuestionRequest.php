<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function authorize()
    {
        // Разрешить запрос только авторизованным пользователям
        return true;
    }

    public function rules()
    {
        return [
            'question' => 'required|string|max:255',
        ];
    }
}
