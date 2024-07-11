<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules()
    {
        return [
            'question_1' => 'required|integer|min:1|max:5',
            'question_2' => 'required|integer|min:1|max:5',
            'question_3' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'pro_1' => 'required|boolean',
            'pro_2' => 'required|boolean',
            'con_1' => 'required|boolean',
            'con_2' => 'required|boolean',
            'rating' => 'required|integer|min:1|max:5',
            'photos.*' => 'nullable|image|max:2048',
        ];
    }
}
