<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_invoices', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('_history_invoices');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->string('code')->nullable();
        $table->bigInteger('student_id')->nullable();
        $table->bigInteger('due_id')->nullable();
        $table->string('payment_for_month')->nullable()->comment('pembayaran untuk bulan');
        $table->string('payment_for_year')->nullable()->comment('pembayaran untuk tahun');
        $table->datetime('effective_date')->nullable()->comment('tanggal tagihan mulai berlaku');
        $table->datetime('payment_due_date')->nullable()->comment('tanggal batas bayar');
        $table->enum('status', ['pending', 'canceled', 'paid_off', 'part_payment'])->nullable();
        $table->double('price')->default(0)->nullable()->comment("Total tertagih (Akumulasi dari semua iuran)");
        $table->double('payed_amount')->default(0)->nullable()->comment("Total sudah dibayar (Akumulasi dari semua pembayaran)");
        $table->double('bill_price')->default(0)->nullable()->comment("KOLOM INI YANG MUNCUL DI PENAGIHAN - Total yang belum dibayar / terhutang (Hasil price - payed_amount) Jika ada lebih bayar kolom berikut harus tetap 0");
        $table->string('note')->nullable();
        $table->string('invoice_type')->nullable()->default("monthly_fee")->comment("monthly_fee = tagihan bulanan, single_transaction = tagihan pembelian lain-lain");
        $table->bigInteger('payment_bank_id')->nullable()->comment("VA bank yang digunakan untuk membayar");
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
