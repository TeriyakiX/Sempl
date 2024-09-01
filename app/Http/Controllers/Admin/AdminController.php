<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
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
        $request->validate([
            'status' => 'required|in:pending,awaiting_review,completed',
            'delivery_date' => 'nullable|date_format:Y-m-d',
        ]);

        $purchase->status = $request->status;

        if ($request->status === 'awaiting_review') {
            $purchase->delivery_date = Carbon::now()->addDays(10)->format('Y-m-d');
        } elseif ($request->has('delivery_date')) {
            $purchase->delivery_date = $request->delivery_date;
        }

        // Сохраняем изменения в базе данных
        $purchase->save();

        return response()->json([
            'purchase' => $purchase,
            'message' => 'Order status and delivery date updated successfully.'
        ], 200);
    }


}
