<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InvoicePublisherTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:invoice-publisher-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terbit tagihan setiap tanggal 1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
