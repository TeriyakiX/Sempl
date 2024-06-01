<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSampleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'sometimes|required|exists:products,id',
            'accepted_terms' => 'sometimes|required|boolean',
            'questions' => 'sometimes|required|array',
            'questions.*.question_id' => 'required_with:questions|exists:questions,id',
            'questions.*.answer' => 'required_with:questions|string'
        ];
    }

    public function messages()
    {
        return [
            'questions.sometimes' => 'Вопросы обязательны для заполнения.',
            'questions.*.question_id.required_with' => 'ID вопроса обязателен.',
            'questions.*.question_id.exists' => 'ID вопроса должен существовать в базе данных.',
            'questions.*.answer.required_with' => 'Ответ на вопрос обязателен.'
        ];
    }
}
