<?php

use App\Helpers\InvoiceHelper;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('invoice:publish', function () {
    $this->comment('Publishing invoices...');
    InvoiceHelper::publish_invoice();
})->purpose('Publish invoices to payment gateway');


