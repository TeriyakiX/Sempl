<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSampleRequest;
use App\Http\Requests\UpdateSampleRequest;
use App\Http\Resources\SampleRequestResource;
use App\Models\DeliveryStatus;
use App\Models\SampleRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class SampleRequestController extends Controller
{

    use DispatchesJobs;
    public function index()
    {
        $sampleRequests = SampleRequest::with('questions')->get();
        return SampleRequestResource::collection($sampleRequests);
    }

    public function store(CreateSampleRequest $request)
    {
        $sampleRequestData = $request->only(['product_id', 'accepted_terms']);
        $sampleRequestData['user_id'] = auth()->user()->id;
        $sampleRequestData['delivery_status_id'] = 1;
        $sampleRequestData['delivery_date'] = Carbon::now()->addMinute();

        $sampleRequest = SampleRequest::create($sampleRequestData);

        $questions = $request->questions;
        $sampleRequest->questions()->attach($questions);

        foreach ($questions as $question) {
            $sampleRequest->questions()->updateExistingPivot($question['question_id'], ['answer' => $question['answer']]);
        }

        return new SampleRequestResource($sampleRequest);
    }


    public function updateDeliveryStatusForExpiredOrders()
    {
        $expiredOrders = SampleRequest::where('delivery_date', '<', now())
            ->where('delivery_status_id', 1)
            ->get();

        $updatedOrders = [];
        foreach ($expiredOrders as $order) {
            $order->update(['delivery_status_id' => 2]);
            $updatedOrders[] = $order;
        }

        return response()->json([
            'message' => 'Статусы заказов обновлены успешно',
            'updated_orders' => $updatedOrders
        ], 200);
    }

    public function destroy(SampleRequest $sampleRequest)
    {
        $sampleRequest->questions()->detach();
        $sampleRequest->delete();

        return response()->json(['message' => 'Sample request deleted successfully'], 200);
    }

    public function userSamples(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['error' => 'Unable to retrieve token'], 401);
            }

            $accessToken = JWTAuth::refresh($token);

            $sampleRequests = $user->sampleRequests()->with('questions')->get();

            return response()->json([
                'sample_requests' => SampleRequestResource::collection($sampleRequests),
                'access_token' => $accessToken,
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Unable to authenticate user'], 401);
        }
    }

    public function expectedSamples()
    {
        $expectedSamples = SampleRequest::whereHas('deliveryStatuses', function ($query) {
            $query->where('status', 'expected');
        })->get();

        return response()->json($expectedSamples);
    }

    public function completedSamples()
    {
        $completedSamples = SampleRequest::whereHas('deliveryStatuses', function ($query) {
            $query->where('status', 'completed');
        })->get();

        return response()->json($completedSamples);
    }
}
