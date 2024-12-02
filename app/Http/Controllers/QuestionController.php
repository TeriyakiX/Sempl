<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('category')->get();
        return response()->json($questions);
    }

    public function show($id)
    {
        $question = Question::with('category')->findOrFail($id);
        return response()->json($question);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $question = Question::create($request->only(['question_text', 'category_id']));

        return response()->json($question, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $question = Question::findOrFail($id);
        $question->update($request->only(['question_text', 'category_id']));

        return response()->json($question);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(null, 204);
    }

    public function getRandomQuestionsForProduct(Product $product)
    {
        $productCategoryIds = $product->category()->pluck('id')->toArray();

        $randomQuestions = Question::whereIn('category_id', $productCategoryIds)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return response()->json($randomQuestions);
    }


}
