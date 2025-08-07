<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('finance_accounts', function (Blueprint $table) {
            $table->boolean('display_for_cashier')->comment("Akun kas ini muncul di halaman kasir")->after('id')->nullable();
        });

        Schema::table('_history_finance_accounts', function (Blueprint $table) {
            $table->boolean('display_for_cashier')->comment("Akun kas ini muncul di halaman kasir")->after('id')->nullable();
        });
    }
};
