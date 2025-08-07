<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_invoice_details', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
        Schema::dropIfExists('_history_invoice_details');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('invoice_id')->nullable();
        $table->bigInteger('due_id')->nullable();
        $table->string('code')->nullable();
        $table->double('price')->default(0)->nullable();
        $table->double('payed_amount')->default(0)->nullable();
        $table->double('refund_amount')->default(0)->nullable();
        $table->string('payment_for_month')->nullable()->comment('pembayaran untuk iuran bulan');
        $table->string('payment_for_year')->nullable()->comment('pembayaran untuk iuran tahun');
        $table->string('status')->default('open')->nullable()->comment('status pembayaran (open, paid, refund)');

        $table->bigInteger('classroom_id')->nullable();
        $table->bigInteger('school_year_id')->nullable();

        $table->datetime('effective_date')->nullable()->comment('tanggal tagihan mulai berlaku');
        $table->datetime('payment_due_date')->nullable()->comment('tanggal batas bayar');
        $table->bigInteger('backtrack_student_id')->nullable();
        $table->bigInteger('cancel_reason')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
