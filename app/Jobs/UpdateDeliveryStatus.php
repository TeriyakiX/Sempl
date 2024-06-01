<?php

namespace App\Jobs;

use App\Models\SampleRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDeliveryStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $ordersToUpdate = SampleRequest::where('delivery_date', '<=', Carbon::now())
            ->where('delivery_status', 'Ожидаемый')
            ->get();

        // Обновить статусы этих заказов
        foreach ($ordersToUpdate as $order) {
            $order->update(['delivery_status' => 'Завершенный']);
        }
    }
}
