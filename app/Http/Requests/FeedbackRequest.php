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
            'fixed_question_1' => 'required|exists:feedback_answers,id',
            'fixed_question_2' => 'required|exists:feedback_answers,id',
            'fixed_question_3' => 'required|exists:feedback_answers,id',
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
