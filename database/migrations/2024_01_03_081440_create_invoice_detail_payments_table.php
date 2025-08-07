<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_detail_payments', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_invoice_detail_payments', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_detail_payments');
        Schema::dropIfExists('_history_invoice_detail_payments');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('invoice_id')->nullable();
        $table->bigInteger('invoice_detail_id')->nullable();
        $table->bigInteger('invoice_payment_id')->nullable();
        $table->bigInteger('student_id')->nullable();
        $table->double('price')->nullable()->comment("Jumlah yang terpakai untuk pembayaran");
        $table->double('refund_amount')->nullable()->comment("Jumlah direfund");
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
