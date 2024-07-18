<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Models\Product;
use App\Models\ProductFeedback;
use Illuminate\Http\Request;
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

        // Создаем новый отзыв
        $feedback = ProductFeedback::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'question_1' => $request->question_1,
            'question_2' => $request->question_2,
            'question_3' => $request->question_3,
            'description' => $request->description,
            'pro_1' => $request->pro_1,
            'pro_2' => $request->pro_2,
            'con_1' => $request->con_1,
            'con_2' => $request->con_2,
            'rating' => $request->rating,
            'status' => ProductFeedback::STATUS_AWAITING_REVIEW,
        ]);

        if ($request->hasFile('photos')) {

            $photo = $request->file('photos');
            $path = $photo->store('feedback_photos', 'public');
            $feedback->photos = $path;
        }
        $feedback->save();

        $product = Product::findOrFail($request->product_id);
        $product->updateRating();

        return new FeedbackResource($feedback);
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

    public function like(ProductFeedback $productFeedback)
    {
        $productFeedback->addLike();
        return response()->json(['message' => 'Like added']);
    }

    public function dislike(ProductFeedback $productFeedback)
    {
        $productFeedback->addDislike();
        return response()->json(['message' => 'Dislike added']);
    }

    public function getProductReviews(Request $request, $product)
    {
        $product = Product::findOrFail($product);
        $reviews = $product->feedbacks()->get();

        return FeedbackResource::collection($reviews);
    }
}
