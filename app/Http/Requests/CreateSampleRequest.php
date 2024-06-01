<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSampleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'accepted_terms' => 'required|boolean',
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|exists:questions,id',
            'questions.*.answer' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'questions.required' => 'Вопросы обязательны для заполнения.',
            'questions.*.question_id.required' => 'ID вопроса обязателен.',
            'questions.*.question_id.exists' => 'ID вопроса должен существовать в базе данных.',
            'questions.*.answer.required' => 'Ответ на вопрос обязателен.'
        ];
    }
}
