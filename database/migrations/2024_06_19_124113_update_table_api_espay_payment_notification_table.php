<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('api_espay_payment_notifications', function (Blueprint $table) {
            $table->string('amount')->nullable()->comment("Jumlah pembayaran")->after('id')->nullable();
        });

        Schema::table('_history_api_espay_payment_notifications', function (Blueprint $table) {
            $table->string('amount')->nullable()->comment("Jumlah pembayaran")->after('id')->nullable();
        });
    }
};
