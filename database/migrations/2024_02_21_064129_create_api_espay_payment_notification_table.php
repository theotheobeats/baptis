<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('api_espay_payment_notifications', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_api_espay_payment_notifications', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_espay_payment_notifications');
        Schema::dropIfExists('_history_api_espay_payment_notifications');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->string("success_flag")->nullable();
        $table->string("error_message")->nullable();
        $table->string("reconcile_id")->nullable();
        $table->string("order_id")->nullable();
        $table->string("reconcile_datetime")->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
