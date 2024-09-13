<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SampleRequestResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class   OrderController extends Controller

{
    public function index()
    {
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = new Order();
        $order->user_id = auth()->id();
        $order->fill($request->validated());
        $order->save();

        $product = $order->product;
        if ($product) {
            $product->updateRating($request->rating);
        }

        return new OrderResource($order);
    }

    public function update(StoreOrderRequest $request, Order $order)
    {
        $order->update([
            'delivery_status' => $request->delivery_status,
            'review' => $request->review,
            'photo' => $request->photo,
        ]);

        $product = $order->product;
        if ($product) {
            $product->updateRating();
        }

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    public function userOrders(Request $request)
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

            $orders = $user->orders;

            // Если у пользователя нет заказов и он не заказывал специальный продукт
            if ($orders->isEmpty() && !$user->has_ordered_special_product) {
                $specialProducts = Product::where('is_special', true)->get();
                return response()->json([
                    'orders' => OrderResource::collection($orders),
                    'special_products' => $specialProducts,
                    'access_token' => $accessToken,
                ], 200);
            }

            return response()->json([
                'orders' => OrderResource::collection($orders),
                'access_token' => $accessToken,
            ], 200);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Unable to authenticate user'], 401);
        }
    }
}
