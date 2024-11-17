<?php

namespace App\Http\Controllers;

use App\Models\FeedbackAnswer;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;

class FeedbackQuestionController extends Controller
{
    public function createQuestion(Request $request)
    {
        $question = FeedbackQuestion::create(['question' => $request->question]);
        return response()->json($question, 201);
    }

    public function createAnswer(Request $request, $questionId)
    {
        $validated = $request->validate([
            'answer' => 'required|string',
            'feedback_id' => 'required|integer',
        ]);

        $answer = FeedbackAnswer::create([
            'question_id' => $questionId,
            'answer' => $validated['answer'],
            'feedback_id' => $validated['feedback_id'],
        ]);

        return response()->json($answer, 201);
    }

    public function getQuestions()
    {
        $questions = FeedbackQuestion::with('answers')->get();
        return response()->json(['data' => $questions]);
    }
}
