<?php

namespace App\Jobs;

use App\Helpers\InvoiceHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceBankPublishJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Publish invoices to payment gateway
        InvoiceHelper::publish_invoice();
    }
}
