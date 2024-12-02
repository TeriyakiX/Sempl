<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackQuestion;
use App\Models\Product;
use App\Models\ProductFeedback;
use App\Models\ProductFeedbackAnswer;
use App\Models\ProductQuestionAnswer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductFeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = ProductFeedback::all();
        return FeedbackResource::collection($feedbacks);
    }
    public function store(FeedbackRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $feedback = $this->createFeedback($request, $user);


        $answersData = [];
        if ($request->has('answers') && is_array($request->answers)) {
            foreach ($request->answers as $answerData) {

                if (!isset($answerData['question_id']) || !isset($answerData['id'])) {
                    return response()->json([
                        'message' => 'Invalid answer data. Each answer must include question_id and id.',
                    ], 400);
                }

                $answer = FeedbackAnswer::find($answerData['id']);

                if ($answer) {

                    $answersData[] = [
                        'question_id' => $answerData['question_id'],
                        'id' => $answer->id,
                        'answer' => $answer->answer,
                    ];

                    ProductFeedbackAnswer::create([
                        'feedback_id' => $feedback->id,
                        'question_id' => $answerData['question_id'],
                        'answer_id' => $answer->id,
                        'product_id' => $request->product_id,
                    ]);
                }
            }
        }

        $this->handleFiles($request, $feedback);

        $this->updateProductInfo($request->product_id);

        $this->updatePurchaseStatus($request->purchase_id, $request->product_id, $user->id);

        return new FeedbackResource($feedback, $answersData);
    }

    protected function createFeedback($request, $user)
    {
        return ProductFeedback::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'fixed_question_1' => $request->fixed_question_1,
            'fixed_question_2' => $request->fixed_question_2,
            'fixed_question_3' => $request->fixed_question_3,
            'description' => $request->description,
            'pro_1' => $request->pro_1,
            'pro_2' => $request->pro_2,
            'con_1' => $request->con_1,
            'con_2' => $request->con_2,
            'rating' => $request->rating,
            'status' => ProductFeedback::STATUS_AWAITING_REVIEW,
        ]);
    }

    protected function handleFiles($request, $feedback)
    {
        $photoPaths = [];
        $videoPaths = [];

        try {
            // Обработка фотографий
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $newName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('feedback_photos', $newName, 'public');
                    $photoPaths[] = $path;
                }
            }

            // Обработка видео
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $newName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                    $path = $video->storeAs('feedback_videos', $newName, 'public');
                    $videoPaths[] = $path;
                }
            }

            $feedback->photos = json_encode($photoPaths);
            $feedback->videos = json_encode($videoPaths);
            $feedback->save();
        } catch (\Exception $e) {
            Log::error('Error storing files: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function updateProductInfo($productId)
    {
        $product = Product::findOrFail($productId);
        $product->updateRating();
        $product->updateFeedbackCount();
    }
    protected function updatePurchaseStatus($purchaseId, $productId, $userId)
    {
        $purchase = Purchase::whereHas('products', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })
            ->where('id', $purchaseId)
            ->where('user_id', $userId)
            ->where('status', 'awaiting_review')
            ->first();

        if ($purchase) {
            $purchase->update(['status' => 'completed']);
        }
    }




    public function show(ProductFeedback $feedback)
    {
        return new FeedbackResource($feedback);
    }

    public function update(FeedbackRequest $request, ProductFeedback $feedback)
    {
        $feedback->update($request->validated());

        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $file) {
                $path = $file->store('feedback_photos');
                $photos[] = $path;
            }
            $feedback->photos = json_encode($photos);
            $feedback->save();
        }

        return new FeedbackResource($feedback);
    }

    public function destroy(ProductFeedback $feedback)
    {
        $feedback->delete();
        return response()->noContent();
    }

    public function like($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $feedback = ProductFeedback::findOrFail($id);

        if ($feedback->liked_by_user) {
            $feedback->likes -= 1;
            $feedback->liked_by_user = false;
        } else {
            $feedback->likes += 1;
            $feedback->liked_by_user = true;

            if ($feedback->disliked_by_user) {
                $feedback->dislikes -= 1;
                $feedback->disliked_by_user = false;
            }
        }

        $feedback->save();

        return response()->json(['feedback' => new FeedbackResource($feedback)], 200);
    }

    public function dislike($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $feedback = ProductFeedback::findOrFail($id);

        if ($feedback->disliked_by_user) {
            $feedback->dislikes -= 1;
            $feedback->disliked_by_user = false;
        } else {
            $feedback->dislikes += 1;
            $feedback->disliked_by_user = true;

            if ($feedback->liked_by_user) {
                $feedback->likes -= 1;
                $feedback->liked_by_user = false;
            }
        }

        $feedback->save();

        return response()->json(['feedback' => new FeedbackResource($feedback)], 200);
    }

    public function getProductReviews(Request $request, $product)
    {
        $product = Product::findOrFail($product);
        $reviews = $product->feedbacks()->get();

        return FeedbackResource::collection($reviews);
    }


    public function getQuestionsWithAnswers($productId)
    {
        // Проверяем, существует ли продукт
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Получаем вопросы и их ответы для продукта
        $questions = FeedbackQuestion::where('product_id', $productId)
            ->with('answers') // Подгружаем связанные ответы
            ->get();

        return response()->json($questions, 200);
    }
}
