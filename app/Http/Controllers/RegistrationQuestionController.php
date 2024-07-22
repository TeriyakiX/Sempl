<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistrationQuestionResource;
use App\Models\RegistrationQuestion;
use Illuminate\Http\Request;

class RegistrationQuestionController extends Controller
{
    public function index()
    {
        // Получаем все вопросы вместе с их ответами
        $questions = RegistrationQuestion::with('answers')->get();

        // Возвращаем вопросы в формате JSON
        return RegistrationQuestionResource::collection($questions);
    }
}
