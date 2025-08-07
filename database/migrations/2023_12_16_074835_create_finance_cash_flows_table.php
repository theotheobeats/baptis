<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_cash_flows', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_finance_cash_flows', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_cash_flows');
        Schema::dropIfExists('_history_finance_cash_flows');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('account_id')->nullable();
        $table->string('code')->nullable();
        $table->string('transaction_number')->nullable();
        $table->date('transaction_date')->nullable();
        $table->text('note')->nullable();
        $table->double('debit')->nullable();
        $table->double('credit')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
