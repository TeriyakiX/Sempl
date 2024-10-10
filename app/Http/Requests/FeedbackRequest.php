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
            'description' => 'nullable|string',
            'pro_1' => 'nullable|string',
            'pro_2' => 'nullable|string',
            'con_1' => 'nullable|string',
            'con_2' => 'nullable|string',
            'rating' => 'required|numeric|min:1|max:5',
            'answers' => 'array',
            'photos' => 'array',
            'photos.*' => 'image',
            'videos' => 'array',
            'videos.*' => 'mimes:mp4,mov,ogg,qt',
        ];
    }
}
