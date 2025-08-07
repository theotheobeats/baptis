<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('api_espay_send_invoices', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_api_espay_send_invoices', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_espay_send_invoices');
        Schema::dropIfExists('_history_api_espay_send_invoices');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->string("rq_uuid")->nullable();
        $table->string("rs_datetime")->nullable();
        $table->string("error_code")->nullable();
        $table->string("error_message")->nullable();
        $table->string("va_number")->nullable();
        $table->string("expired")->nullable();
        $table->string("description")->nullable();
        $table->string("total_amount")->nullable();
        $table->string("amount")->nullable();
        $table->string("fee")->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
