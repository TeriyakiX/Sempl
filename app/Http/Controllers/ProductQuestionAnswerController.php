<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Http\Resources\ProductQuestionAnswerResource;
use App\Models\ProductQuestionAnswer;
use Illuminate\Http\Request;

class ProductQuestionAnswerController extends Controller
{
    // Получение всех ответов на конкретный вопрос
    public function index($questionId)
    {
        $answers = ProductQuestionAnswer::where('question_id', $questionId)->get();
        return ProductQuestionAnswerResource::collection($answers);
    }

    // Создание ответа на вопрос
    public function store(AnswerRequest $request)
    {
        $answer = ProductQuestionAnswer::create($request->validated());
        return new ProductQuestionAnswerResource($answer);
    }

    // Обновление ответа на вопрос
    public function update(AnswerRequest $request, $id)
    {
        $answer = ProductQuestionAnswer::findOrFail($id);
        $answer->update($request->validated());
        return new ProductQuestionAnswerResource($answer);
    }

    // Удаление ответа на вопрос
    public function destroy($id)
    {
        $answer = ProductQuestionAnswer::findOrFail($id);
        $answer->delete();
        return response()->json(['message' => 'Answer deleted successfully']);
    }
}
