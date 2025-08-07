<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_reconciliations', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_invoice_reconciliations', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_reconciliations');
        Schema::dropIfExists('_history_invoice_reconciliations');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->string("code")->nullable();
        $table->string("invoice_id")->nullable()->comment("ID invoice sebagai penananda rekonsiliasi ini untuk invoice mana");
        $table->string("student_id")->nullable();
        $table->string("base_va_section_1")->nullable();
        $table->string("base_va_section_2")->nullable();
        $table->string("base_va_section_3")->nullable();
        $table->string("va_number")->nullable()->comment("Nomor VA yang diberikan oleh baptis");
        $table->string("maspion_va_number")->nullable()->comment("Nomor VA yang diberikan oleh Maspion");
        $table->string("bca_va_number")->nullable()->comment("Nomor VA yang diberikan oleh BCA");
        $table->double("invoice_amount")->nullable()->comment("Jumlah ditagih ke server");
        $table->double("payed_amount")->nullable()->comment("Jumlah yang dibayar oleh user");
        $table->double("admin_fee")->nullable()->comment("Biaya admin yang dibebankan ke user (Jika ada)");
        $table->double("other_fee")->nullable()->comment("Biaya lain-lain yang dibebankan ke user (Jika ada)");
        $table->bigInteger("bank_id")->nullable();
        $table->string("note")->nullable();
        $table->string("other_col_1", 500)->nullable()->comment("Untuk keperluan lain jika dibutuhkan cepat");
        $table->string("other_col_2", 500)->nullable()->comment("Untuk keperluan lain jika dibutuhkan cepat");
        $table->string("other_col_3", 500)->nullable()->comment("Untuk keperluan lain jika dibutuhkan cepat");
        $table->dateTime("payed_at")->nullable()->comment("Waktu pembayaran, jika lunas close semua invoice di bank lain");
        $table->dateTime("inactive_at")->nullable()->comment("Dinonaktifkan pada saat");
        $table->string("inactive_reason")->nullable()->comment("Alasan dinonaktifkan");
        $table->bigInteger("inactive_by")->nullable()->comment("Dinonaktifkan oelh");
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
