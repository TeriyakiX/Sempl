<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return ReviewResource::collection($reviews);
    }

    public function store(ReviewRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $review = Review::create([
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
        ]);

        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $file) {
                $path = $file->store('review_photos');
                $photos[] = $path;
            }
            $review->photos = json_encode($photos);
            $review->save();
        }

        return new ReviewResource($review);
    }

    public function show(Review $review)
    {
        return new ReviewResource($review);
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->validated());

        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $file) {
                $path = $file->store('review_photos');
                $photos[] = $path;
            }
            $review->photos = json_encode($photos);
            $review->save();
        }

        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->noContent();
    }

    public function like(Review $review)
    {
        $review->increment('likes');
        return new ReviewResource($review);
    }

    public function dislike(Review $review)
    {
        $review->increment('dislikes');
        return new ReviewResource($review);
    }

    public function uploadMedia(Request $request, Review $review)
    {
        if ($request->hasFile('media')) {
            $media = [];
            foreach ($request->file('media') as $file) {
                $path = $file->store('review_media');
                $media[] = $path;
            }
            $review->media = json_encode($media);
            $review->save();
        }

        return new ReviewResource($review);
    }
}
