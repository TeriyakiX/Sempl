<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function index()
    {
        // Получаем все заказы с пользователями и товарами
        $orders = Purchase::with('user', 'products')->get();

        return response()->json([
            'orders' => $orders,
            'message' => 'All orders loaded successfully.'
        ], 200);
    }

    public function updateStatus(Request $request, Purchase $purchase)
    {
        // Проверяем, что запрос содержит корректные данные
        $request->validate([
            'status' => 'required|in:pending,awaiting_review,completed',
            'delivery_date' => 'nullable|date_format:Y-m-d',
        ]);

        // Обновляем статус заказа и дату доставки
        $purchase->status = $request->status;
        if ($request->has('delivery_date')) {
            $purchase->delivery_date = $request->delivery_date;
        }
        $purchase->save();

        return response()->json([
            'purchase' => $purchase,
            'message' => 'Order status and delivery date updated successfully.'
        ], 200);
    }
}
