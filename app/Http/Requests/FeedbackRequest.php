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
            'description' => 'nullable|string|max:1000',
            'pro_1' => 'nullable|string|max:255',
            'pro_2' => 'nullable|string|max:255',
            'con_1' => 'nullable|string|max:255',
            'con_2' => 'nullable|string|max:255',
            'rating' => 'required|integer|between:1,5',
            'answers' => 'nullable|array',
            'answers.*.question_id' => 'required|exists:feedback_questions,id',
            'answers.*.id' => 'required|exists:feedback_answers,id',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:51200', // Максимум 50 MB
        ];
    }
}
