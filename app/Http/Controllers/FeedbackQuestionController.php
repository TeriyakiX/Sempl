<?php

namespace App\Http\Controllers;

use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;

class FeedbackQuestionController extends Controller
{
    public function index()
    {
        $questions = FeedbackQuestion::with('category')->get();
        return response()->json($questions);
    }

    public function show($id)
    {
        $question = FeedbackQuestion::with('category')->findOrFail($id);
        return response()->json($question);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $question = FeedbackQuestion::create($request->only(['question_text', 'category_id']));

        return response()->json($question, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $question = FeedbackQuestion::findOrFail($id);
        $question->update($request->only(['question_text', 'category_id']));

        return response()->json($question);
    }

    public function destroy($id)
    {
        $question = FeedbackQuestion::findOrFail($id);
        $question->delete();

        return response()->json(null, 204);
    }
}
