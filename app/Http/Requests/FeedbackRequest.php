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
            'purchase_id' => 'required|exists:purchases,id',
            'description' => 'required|string',
            'pro_1' => 'nullable|string',
            'pro_2' => 'nullable|string',
            'con_1' => 'nullable|string',
            'con_2' => 'nullable|string',
            'rating' => 'required|integer|between:1,5',
            'photos.*' => 'mimes:jpeg,png,jpg,gif|max:2048', // Правила для фотографий
            'videos.*' => 'mimes:mp4,avi,mov|max:102400', // Правила для видео
            'answers' => 'array',
            'answers.*.question_id' => 'required|exists:product_questions,id',
            'answers.*.answer' => 'required|string|max:255',
        ];
    }
}
