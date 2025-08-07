<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_accounts', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_cash_accounts', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_accounts');
        Schema::dropIfExists('_history_cash_accounts');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger("employee_id")->nullable();
        $table->datetime("open_time")->nullable();
        $table->datetime("close_time")->nullable();
        $table->double("beginning_balance")->nullable();
        $table->double("closing_balance")->nullable();
        $table->double("expense_balance")->default(0)->nullable();
        $table->double("system_closing_balance")->nullable();
        $table->double("backtrack_sell_qty_total")->nullable();
        $table->double("backtrack_sell_total")->nullable();
        $table->integer("print_amount")->default(0)->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
