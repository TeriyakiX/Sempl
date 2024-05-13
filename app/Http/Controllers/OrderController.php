<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller

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
}
