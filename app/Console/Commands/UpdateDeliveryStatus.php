<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SampleRequest;
use Carbon\Carbon;

class UpdateDeliveryStatus extends Command
{
    protected $signature = 'update:deliverystatus';
    protected $description = 'Update delivery status of sample requests';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        $sampleRequests = SampleRequest::where('delivery_status_id', 1)
            ->where('delivery_date', '<', $now)
            ->get();

        foreach ($sampleRequests as $sampleRequest) {
            $sampleRequest->delivery_status_id = 2; // Завершенный статус
            $sampleRequest->save();
        }

        $this->info('Delivery statuses updated successfully.');
    }
}
