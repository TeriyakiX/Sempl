<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'question_1' => 'required|string',
            'question_2' => 'required|string',
            'question_3' => 'required|string',
            'description' => 'required|string',
            'pro_1' => 'nullable|string',
            'pro_2' => 'nullable|string',
            'con_1' => 'nullable|string',
            'con_2' => 'nullable|string',
            'rating' => 'required|integer|between:1,5',
            'photos.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
