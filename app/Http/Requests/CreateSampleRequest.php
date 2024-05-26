<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSampleRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Убедитесь, что эта функция возвращает true, если пользователь должен иметь доступ к этому запросу
    }

    public function rules()
    {
        return [
            'accepted_terms' => 'required|boolean',
            'question1' => 'required|string',
            'question2' => 'required|string',
            'question3' => 'required|string',
            'question4' => 'required|string',
            'product_id' => 'required|exists:products,id',
        ];
    }
}
