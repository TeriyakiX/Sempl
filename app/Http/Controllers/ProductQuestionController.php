<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Http\Resources\ProductQuestionResource;
use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;

class ProductQuestionController extends Controller
{
    // Список вопросов для конкретного продукта
    public function index(Product $product)
    {
        $questions = $product->questions()->with('answers')->get(); // Подгружаем ответы вместе с вопросами
        return ProductQuestionResource::collection($questions);
    }

    // Добавление вопроса для продукта
    public function store(QuestionRequest $request, Product $product)
    {
        $question = $product->questions()->create($request->validated());
        return new ProductQuestionResource($question);
    }

    // Обновление вопроса для продукта
    public function update(QuestionRequest $request, Product $product, ProductQuestion $question)
    {
        // Убедитесь, что вопрос принадлежит данному продукту
        if ($question->product_id !== $product->id) {
            return response()->json(['message' => 'Question does not belong to this product'], 404);
        }

        // Обновляем вопрос
        $question->update($request->validated());
        return new ProductQuestionResource($question);
    }
}
