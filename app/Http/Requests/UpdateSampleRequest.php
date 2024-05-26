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
            'accepted_terms' => 'sometimes|boolean',
            'question1' => 'sometimes|string',
            'question2' => 'sometimes|string',
            'question3' => 'sometimes|string',
            'question4' => 'sometimes|string',
            'product_id' => 'sometimes|exists:products,id',
        ];
    }
}
