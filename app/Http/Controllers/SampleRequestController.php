<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSampleRequest;
use App\Http\Requests\UpdateSampleRequest;
use App\Http\Resources\SampleRequestResource;
use App\Models\SampleRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SampleRequestController extends Controller
{
    public function index()
    {
        $sampleRequests = SampleRequest::all();
        return SampleRequestResource::collection($sampleRequests);
    }

    public function store(CreateSampleRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Проверка, что продукт принадлежит к категории "образец"
        if ($product->category_id !== 1) {
            return response()->json(['error' => 'Запрос на образец можно сделать только для продуктов категории "образец"'], 400);
        }

        if (!$request->accepted_terms) {
            return response()->json(['error' => 'Вы должны принять условия'], 400);
        }

        $sampleRequestData = $request->validated();
        $sampleRequestData['user_id'] = auth()->id();

        $sampleRequest = SampleRequest::create($sampleRequestData);

        return new SampleRequestResource($sampleRequest);
    }

    public function show(SampleRequest $sampleRequest)
    {
        return new SampleRequestResource($sampleRequest);
    }

    public function update(UpdateSampleRequest $request, SampleRequest $sampleRequest)
    {
        $sampleRequestData = $request->validated();

        if (isset($sampleRequestData['product_id'])) {
            $product = Product::findOrFail($sampleRequestData['product_id']);

            // Проверка, что продукт принадлежит к категории "образец"
            if ($product->category->name !== 'образец') {
                return response()->json(['error' => 'Запрос на образец можно сделать только для продуктов категории "образец"'], 400);
            }
        }

        $sampleRequest->update($sampleRequestData);

        return new SampleRequestResource($sampleRequest);
    }

    public function destroy(SampleRequest $sampleRequest)
    {
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

            $sampleRequests = $user->sampleRequests;

            return response()->json([
                'sample_requests' => SampleRequestResource::collection($sampleRequests),
                'access_token' => $accessToken,
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Unable to authenticate user'], 401);
        }
    }

}
