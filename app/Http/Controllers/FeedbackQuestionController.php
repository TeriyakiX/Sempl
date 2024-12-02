<?php

namespace App\Http\Controllers;

use App\Models\FeedbackAnswer;
use App\Models\FeedbackQuestion;
use App\Models\Product;
use Illuminate\Http\Request;

class FeedbackQuestionController extends Controller
{
    public function createQuestion(Request $request, $productId)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255', // Валидация вопроса
        ]);

        // Проверяем, существует ли продукт
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Создаем вопрос для указанного продукта
        $question = FeedbackQuestion::create([
            'product_id' => $productId, // Привязываем вопрос к продукту
            'question' => $validated['question'],
        ]);

        return response()->json($question, 201);
    }

    public function createAnswer(Request $request, $productId, $questionId)
    {
        $validated = $request->validate([
            'answer' => 'required|string',
        ]);

        // Проверяем, существует ли продукт
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Проверяем, существует ли вопрос, связанный с этим продуктом
        $question = FeedbackQuestion::where('product_id', $productId)->find($questionId);
        if (!$question) {
            return response()->json(['message' => 'Question not found for this product'], 404);
        }

        try {
            // Создаем ответ для вопроса
            $answer = FeedbackAnswer::create([
                'question_id' => $questionId,
                'answer' => $validated['answer'],
                'product_id' => $productId,  // Привязываем ответ к продукту
            ]);

            return response()->json($answer, 201);
        } catch (\Exception $e) {
            // Ловим возможные ошибки при создании записи
            return response()->json([
                'message' => 'Failed to create answer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getQuestions($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Получаем все вопросы для продукта
        $questions = FeedbackQuestion::where('product_id', $productId)->with('answers')->get();
        return response()->json(['data' => $questions]);
    }
}
