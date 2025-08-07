<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_reconciliation_details', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_invoice_reconciliation_details', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_reconciliation_details');
        Schema::dropIfExists('_history_invoice_reconciliation_details');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger("invoice_reconciliation_id")->nullable();
        $table->string("invoice_detail_id")->nullable()->comment("ID detail invoice");
        $table->double("invoice_amount")->nullable()->comment("Jumlah tagihan");
        $table->timestamps();
        $table->softDeletes();
    }
};
