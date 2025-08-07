<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cashier_transactions', function (Blueprint $table) {
            $table->string('transaction_type')->comment("Jenis Transaksi")->after('id')->nullable();
            $table->bigInteger('student_id')->comment("ID Siswa")->after('id')->nullable();
        });

        Schema::table('_history_cashier_transactions', function (Blueprint $table) {
            $table->string('transaction_type')->comment("Jenis Transaksi")->after('id')->nullable();
            $table->bigInteger('student_id')->comment("ID Siswa")->after('id')->nullable();
        });
    }
};
