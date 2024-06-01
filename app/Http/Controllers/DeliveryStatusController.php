<?php

namespace App\Http\Controllers;

use App\Models\DeliveryStatus;
use Illuminate\Http\Request;

class DeliveryStatusController extends Controller
{
    public function index()
    {
        $deliveryStatuses = DeliveryStatus::all();
        return response()->json($deliveryStatuses);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'status' => 'required|string',
        ]);

        $deliveryStatus = DeliveryStatus::create($data);

        return response()->json($deliveryStatus, 201);
    }

    public function show(DeliveryStatus $deliveryStatus)
    {
        return response()->json($deliveryStatus);
    }

    public function update(Request $request, DeliveryStatus $deliveryStatus)
    {
        $data = $request->validate([
            'status' => 'required|string',
        ]);

        $deliveryStatus->update($data);

        return response()->json($deliveryStatus);
    }

    public function destroy(DeliveryStatus $deliveryStatus)
    {
        $deliveryStatus->delete();

        return response()->json(null, 204);
    }
}
