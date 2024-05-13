<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return ReviewResource::collection($reviews);
    }

    public function store(StoreReviewRequest $request)
    {
        $review = new Review();
        $review->user_id = auth()->id();
        $review->fill($request->validated());
        $review->product->updateRating();
        $review->save();

        return new ReviewResource($review);
    }

    public function show(Review $review)
    {
        return new ReviewResource($review);
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->all());
        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(null, 204);
    }

    public function uploadMedia(Request $request)
    {
        $files = $request->file('media');
        $paths = [];

        foreach ($files as $file) {
            $path = $file->store('reviews/media', 'public');
            $paths[] = $path;
        }

        return response()->json(['paths' => $paths]);
    }

}
