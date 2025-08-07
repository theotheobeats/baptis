<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashier_payments', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_cashier_payments', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashier_payments');
        Schema::dropIfExists('_history_cashier_payments');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('student_id')->nullable();
        $table->date('date')->nullable();
        $table->double('amount')->nullable();
        $table->bigInteger('coa_1_id')->nullable();
        $table->double('coa_1_debit')->default(0)->nullable();
        $table->double('coa_1_credit')->default(0)->nullable();
        $table->bigInteger('coa_2_id')->nullable();
        $table->double('coa_2_debit')->default(0)->nullable();
        $table->double('coa_2_credit')->default(0)->nullable();
        $table->text('note')->nullable();

        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
