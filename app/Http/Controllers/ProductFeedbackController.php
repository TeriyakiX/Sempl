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

        if ($request->hasFile('photos')) {
            $photo = $request->file('photos');
            $path = $photo->store('feedback_photos', 'public');
            $feedback->photos = $path;
        }
        $feedback->save();

        $product = Product::findOrFail($request->product_id);
        $product->updateRating();
        $product = $feedback->product;
        $product->updateFeedbackCount();

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
}
